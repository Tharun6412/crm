<?php

namespace App\Exports\Geysers;

use App\Models\Consumer\ConsumerGeyser;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Override;

class GeyserExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Query
    */
    protected $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function query(){
        return ConsumerGeyser::query()->with([
            'consumer:id,crn,fname,lname,ga_id,district_id',
            'invoice:id,invoice_number,status_id,balance_amount,payable_amount,paid_amount,type_id',
        ])
        ->when(!empty($this->request->geo_area),function ($q) {
            $q->whereHas('consumer', function($q1){
                $q1->whereIn('ga_id', $this->request->geo_area);
            });
        })
        ->when(!empty($this->request->district),function($q) {
            $q->whereHas('consumer', function($q1){
                $q1->whereIn('district_id', $this->request->district);
            });
        })
        ->when(!empty($this->request->status_id),function($q) {
            $q->whereHas('invoice', function ($q1) {
                $q1->whereIn('status_id', array($this->request->status_id));
            });
        })
        ->when(!empty($this->request->geyser_status), function($q) {
            $q->where('status_id',$this->request->geyser_status);
        })
        ->orderBy('created_at');
    }

    public function headings(): array
    {
        return[
            'CRN No',
            'Name',
            'GA',
            'District',
            'Geyser Code',
            'Invoice No',
            'Amount',
            'Balance Amount',
            'Invoice Status',
            'Geyser Status',
            'Created Date',
            'Created_by',
        ];
    }

    public function map($geyser): array
    {
        return[
            $geyser->consumer->crn,
            $geyser->consumer->name,
            $geyser->consumer->ga->name,
            $geyser->consumer->district->name,
            $geyser->code,
            $geyser->invoice->invoice_number,
            numberFormat($geyser->invoice->payable_amount),
            numberFormat($geyser->invoice->balance_amount),
            $geyser->invoice->status->name,
            $geyser->status->name,
            $geyser->created_at,
            $geyser->createdBy->name,
        ];
    }
}
