<?php

namespace App\Exports\Consumers;

use App\Models\Consumer\VerifyConsumer;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ConsumerVerificationExport implements  FromQuery, WithMapping, WithHeadings
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
        return VerifyConsumer::with([
            'consumer:id,crn,fname,lname,ga_id,district_id,ca_id',
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
        ->when(!empty($this->request->charge_area),function($q) {
            $q->whereHas('consumer', function($q1){
                $q1->whereIn('ca_id', $this->request->charge_area);
            });
        })
        ->when(!empty($this->request->status), function($q) {
            $q->where('status',$this->request->status);
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
            'Charge Area',
            'status',
            'Verified By',
            'Verified Date'
        ];
    }

    public function map($verification): array
    {
        if($verification->status == 1){
            $status = "Verified Success";
        }else{
            $status = "Verified Failed";
        }
        return[
            $verification->consumer?->crn,
            $verification->consumer?->name,
            $verification->consumer->ga?->name,
            $verification->consumer->district?->name,
            $verification->consumer->ca?->name,
            $status,
            $verification->createdBy?->name,
            $verification->created_at?->format('d-m-Y'),
        ];
    }

}
