<?php
namespace App\Exports\Reports;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Models\Invoice\BillInvoice;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AgingInvoicesExport implements FromQuery, WithHeadings, WithMapping 
{
    use Exportable;
    /**
     * Construct Method
     */
    protected $request;
    protected $i = 0;
    public function __construct($request)
    {
        $this->request = $request;
    }
    /**
    * @return query
    */
    public function query()
    {
        $today = Carbon::today();
        $query = BillInvoice::query()
            ->join('cns_consumers', 'cns_consumers.id', '=', 'bil_invoices.consumer_id')
            ->where('cns_consumers.ga_id', $this->request->ga_id)
            ->whereNotIn('bil_invoices.type_id', [
                    InvoiceType::LATE_PAYMENT_CHARGES->value,
                    InvoiceType::RENTAL_CHARGES->value,
                    InvoiceType::SD_EMI->value
                ])
            ->where('bil_invoices.status_id', InvoiceStatus::NOT_PAID->value);
        if ($this->request->filled('invoice_type')) {
            $query->where('bil_invoices.type_id', $this->request->invoice_type);
        }
        // Aging Filter
        switch ($this->request->range) 
        {
            case '1-15':
                $query->whereBetween('bil_invoices.due_date', [
                    $today->copy()->subDays(15),
                    $today->copy()->subDay()
                ]);
                break;
            case '16-30':
                $query->whereBetween('bil_invoices.due_date', [
                    $today->copy()->subDays(30),
                    $today->copy()->subDays(16)
                ]);
                break;
            case '31-60':
                $query->whereBetween('bil_invoices.due_date', [
                    $today->copy()->subDays(60),
                    $today->copy()->subDays(31)
                ]);
                break;
            case '61-90':
                $query->whereBetween('bil_invoices.due_date', [
                    $today->copy()->subDays(90),
                    $today->copy()->subDays(61)
                ]);
                break;
            case '90+':
                $query->where('bil_invoices.due_date', '<', $today->copy()->subDays(90));
                break;
        }
        // Search Filters
        $query->when($this->request->filled('key'), function ($q) {
            $q->where('invoice_number', 'like', '%' . $this->request->key . '%');
        });
        $invoices = $query->select('invoice_number','invoice_date','type_id','due_date','payable_amount', 'total_amount', 'balance_amount', 'consumer_id')
            ->orderBy('invoice_date', 'desc');
        return $invoices;
    }

    /**
     * Headings 
     */
    public function headings():array
    {
        return ['S.No', 'Invoice Number', 'Invoice Date', 'Invoice Type', 'CRN', 'Name', 'Segment', 'Due Date', 'Invoice Amount', 'Balance Amount'];
    }

    /**
     * Mapping [Loop the data from the query]
     */
    public function map($invoice): array
    {
        $this->i++; //Increment serial Number
        return [
            $this->i,
            $invoice->invoice_number ?? '',
            dateFormat($invoice->invoice_date) ?? '',
            $invoice->invoiceType->name,
            $invoice->consumer->crn,
            $invoice->consumer->name,
            $invoice->consumer->segment->name,
            dateFormat($invoice->due_date),
            numberFormat($invoice->payable_amount),
            numberFormat($invoice->balance_amount),
        ];
    }
}
