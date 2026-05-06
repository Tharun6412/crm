<?php
namespace App\Exports\Reports\Invoices;

use App\Enums\InvoiceStatus;
use App\Models\Consumer\Consumer;
use App\Models\Invoice\BillInvoice;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InvoiceExport implements FromQuery, ShouldQueue, WithChunkReading, WithHeadings, WithMapping
{
    use Exportable;

    /**
     * Construct Method
     */
    protected $exportId;
    protected $request;
    public function __construct($request, $exportId)
    {
        $this->exportId = $exportId;
        $this->request = $request;
    }
    public function query()
    {
        $invoices = BillInvoice::query()
            ->leftJoin('cns_consumers', 'cns_consumers.id', '=', 'bil_invoices.consumer_id')
            ->whereNot('bil_invoices.status_id', InvoiceStatus::CANCEL->value)
            ->when(!empty($this->request['invoice_type']), function ($q) {
                $q->whereIn('bil_invoices.type_id', $this->request['invoice_type']);
            })
            ->when(!empty($this->request['key']), function($q) {
                $q->where(function($q) {
                    $q->where('bil_invoices.invoice_number', 'like', '%' . $this->request['key'] . '%');
                    $q->orWhere('cns_consumers.crn', 'like', '%' . $this->request['key'] . '%');
                });
            })
            ->when(!empty($this->request['segments']), function ($q) {
                $q->whereIn('cns_consumers.segment_id', $this->request['segments']);
            })
             ->when(!empty($this->request['status_id']), function ($q) {
                $q->whereIn('bil_invoices.status_id', $this->request['status_id']);
            })
            ->when(!empty($this->request['connection_type_id']), function ($q) {
                $q->whereIn('cns_consumers.connection_type_id', $this->request['connection_type_id']);
            })
            ->when(!empty($this->request['geo_area']), function ($q) {
                $q->whereIn('cns_consumers.ga_id', $this->request['geo_area']);
            })
            ->when(!empty($this->request['district']), function ($q) {
                $q->whereIn('cns_consumers.district_id', $this->request['district']);
            })
            ->when((!empty($this->request['date_from']) and !empty($this->request['date_to'])), function($q) {
                $q->whereBetween('bil_invoices.invoice_date', [Carbon::createFromFormat('d-m-Y', $this->request['date_from'])->startOfDay()->toDateTimeString(), Carbon::createFromFormat('d-m-Y', $this->request['date_to'])->endOfDay()->toDateTimeString()]);
            })
            ->select('bil_invoices.id','bil_invoices.consumer_id','bil_invoices.invoice_number','bil_invoices.invoice_date','bil_invoices.type_id','bil_invoices.due_date','bil_invoices.total_amount', 'bil_invoices.payable_amount', 'bil_invoices.balance_amount', 'bil_invoices.status_id', 'bil_invoices.created_at')
            ->orderBy('bil_invoices.created_at', 'desc');
        return $invoices;
    }

    public function headings(): array
    {
        return [
            'Id',
            'Invoice Number',
            'Invoice Date',
            'CRN',
            'Name',
            'GA',
            'Type',
            'Due Date',
            'Invoice Amount',
            'Balance Amount',
            'Status',
            'Added Date',
        ];
    }

    public function chunkSize(): int
    {
        return 5000;
    }

    public function map($invoice) : array
    {
        return [
            $invoice->id ?? '',
            $invoice->invoice_number ?? '',
            dateFormat($invoice->invoice_date),
            $invoice->consumer->crn ?? '',
            $invoice->consumer->name ?? '',
            $invoice->consumer->ga->name ?? '',
            $invoice->invoiceType->name ?? '',
            $invoice->due_date ? dateFormat($invoice->due_date) : '',
            $invoice->total_amount ?? '',       
            $invoice->balance_amount ?? '',       
            $invoice->status?->name,  
            $invoice->created_at ? dateFormat($invoice->created_at) : '',
        ];
    }
}
