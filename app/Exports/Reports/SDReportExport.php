<?php

namespace App\Exports\Reports;

use App\Models\Consumer\ConsumerScheme;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SDReportExport implements FromQuery, WithHeadings, WithMapping
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
        // Get Security Deposit Details
        $sortBy = ($this->request->get('sortBy')) ? $this->request->get('sortBy') : 'created_at';
        $sortOr = ($this->request->get('sortOr')) ? $this->request->get('sortOr') : 'desc';
        $sd_amounts = ConsumerScheme::from('cns_consumer_schemes as cs')
            ->leftJoin('cns_consumers as c', 'c.id', '=', 'cs.consumer_id')
            ->leftJoin('mst_segments as ms', 'ms.id', '=', 'c.segment_id')
            ->leftJoin('mst_gas as mg', 'mg.id', '=', 'c.ga_id')
            ->leftJoin('mst_connection_types as mct','mct.id', '=', 'c.connection_type_id')
            ->leftJoin('mst_cns_schemes as mcs','mcs.id', '=', 'cs.scheme_id')
            ->select('c.crn', 'mg.name as ga_name', 'ms.name as segment_name', 'mct.name as type_name', 'mcs.name as scheme_name', 'cs.total_deposit', 'cs.paid_deposit', 'cs.balance', 'cs.created_at')
            ->when(!isAdmin() && !isSuperAdmin(), function ($q) {
                $q->whereIn('c.ga_id', session('user')['gas']);
            })
            ->when($this->request->filled('key'), function($q) {
                $q->whereAny(['cs.total_deposit', 'cs.paid_deposit', 'cs.balance', 'cs.created_at', 'c.crn'], 'like', '%'.$this->request->key.'%');
            })
            ->when($this->request->filled('geo_area'), function($q) {
                $q->whereIn('c.ga_id', $this->request->geo_area);
            })
            ->when($this->request->filled('scheme'), function ($q) {
                $q->whereIn('cs.scheme_id', $this->request->scheme);
            })
            ->when($this->request->filled('segments'), function ($q) {
                $q->whereIn('c.segment_id', $this->request->segments);
            })
            ->when($this->request->filled('connection_type_id'), function($q) {
                $q->whereIn('c.connection_type_id', $this->request->connection_type_id);
            })
            ->when((!empty($this->request->date_from) and !empty($this->request->date_to)), function($q) {
                $q->whereBetween('cs.created_at', [Carbon::createFromFormat('d-m-Y', $this->request->date_from)->startOfDay()->toDateTimeString(), Carbon::createFromFormat('d-m-Y', $this->request->date_to)->endOfDay()->toDateTimeString()]);
            })
            ->orderBy($sortBy, $sortOr);
        return $sd_amounts;
    }

    /**
     * Headings 
     */
    public function headings():array
    {
        return ['S.No', 'CRN', 'GA', 'Segment', 'Connection Type', 'Scheme', 'Total Deposit', 'Paid Deposit', 'Balance', 'Added Date'];
    }

    /**
     * Mapping [Loop the data from the query]
     */
    public function map($amount): array
    {
        $this->i++; //Increment serial Number
        return [
            $this->i,
            $amount->crn ?? '',
            $amount->ga_name ?? '',
            $amount->segment_name ?? '',
            $amount->type_name ?? '',
            $amount->scheme_name ?? '',
            $amount->total_deposit ?? 0,
            $amount->paid_deposit ?? 0,
            $amount->balance ?? 0,
            dateFormat($amount->created_at),
        ];
    }
}
