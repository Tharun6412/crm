<?php

namespace App\Exports\Reports;

use App\Models\Consumer\ConsumerRefund;
use App\Models\Consumer\ConsumerStatus as ConsumerConsumerStatus;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RefundReportExport implements FromQuery, WithHeadings, WithMapping
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
        $sortBy = ($this->request->get('sortBy')) ? $this->request->get('sortBy') : 'created_at';
        $sortOr = ($this->request->get('sortOr')) ? $this->request->get('sortOr') : 'desc';
        $refunds_list = ConsumerRefund::with(['consumer'])->when($this->request->has('key'), function ($q) {
                $q->whereAny(['request_no'], 'like', '%' . $this->request->key . '%');
            })
            ->when($this->request->filled('geo_area'), function($q) {
                $q->whereHas('consumer', function($query) {
                    $query->whereIn('ga_id', $this->request->geo_area);
                });
            })
            ->when($this->request->filled('refund_status'), function($q) {
                $q->whereIn('status_id', $this->request->refund_status);
            })
            ->orderBy($sortBy, $sortOr);
        return $refunds_list;
    }

    /**
     * Headings 
     */
    public function headings():array
    {
        return ['S.No', 'CRN', 'GA', 'Request Number', 'Refund Amount', 'Refunded', 'Status', 'Added Date'];
    }

    /**
     * Mapping [Loop the data from the query]
     */
    public function map($refund): array
    {
        // check Refundable Amount
        if($refund->status_id == 4) {
            $ref_amt = $refund->refund_amount;
        }else {
            $ref_amt = 0;
        }
        $this->i++; //Increment serial Number
        return [
            $this->i,
            $refund->consumer->crn,
            $refund->consumer->ga->name ?? '',
            $refund->request_no,
            $refund->refund_amount ?? 0,
            $ref_amt,
            $refund->status->name,
            $refund->created_at?->format('d-m-Y'),
        ];
    }
}
