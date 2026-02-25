<?php

namespace App\Exports\Spot;

use App\Models\Spot\Prospects;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProspectsExport implements FromQuery, WithHeadings, WithMapping 
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
        //
        $sortBy = ($this->request->get('sortBy')) ? $this->request->get('sortBy') : 'created_at';
        $sortOr = ($this->request->get('sortOr')) ? $this->request->get('sortOr') : 'desc';
        $query = Prospects::with(['stage'])->when($this->request->has('search_key'), function($q) {
            $q->where(function($q) {
                $q->where('name', 'like', '%'.$this->request->get('search_key').'%');
                $q->orWhere('code', 'like', '%'.$this->request->get('search_key').'%');
            });
        })->When($this->request->has('geo_area'), function($q) {
            $q->whereIn('ga_id', $this->request->get('geo_area'));
        })->When($this->request->has('industrial_area_id'), function($q) {
            $q->whereIn('industrial_area_id', $this->request->get('industrial_area_id'));
        })->When($this->request->has('fuel_id'), function($q) {
            $q->whereIn('fuel_id', $this->request->get('fuel_id'));
        })->when($this->request->has('stage_id'), function($q) {
            $q->whereHas('stage', function($q2) {
                $q2->whereIn('parent_id', $this->request->get('stage_id'));
            });
        })->When($this->request->has('sub_stage_id'), function($q) {
            $q->whereIn('stage_id', $this->request->get('sub_stage_id'));
        })->When($this->request->has('status_id'), function($q) {
            $q->whereIn('status_id', $this->request->get('status_id'));
        })
        ->when((!empty($this->request->date_from) and !empty($this->request->date_to)), function($q) {
            $q->whereBetween('expected_date', [Carbon::createFromFormat('d-m-Y', $this->request->date_from)->startOfDay()->toDateTimeString(), Carbon::createFromFormat('d-m-Y', $this->request->date_to)->endOfDay()->toDateTimeString()]);
        });
        $prospects = $query->orderBy($sortBy, $sortOr);
        return $prospects;
    }

    /**
     * Headings 
     */
    public function headings():array
    {
        return ['S.No', 'GA', 'Name', 'Industrial Area', 'Current Fuel', 'Potential', 'Expected Date', 'Stage', 'Sub Stage', 'Status', 'Last Status Date'];
    }

    /**
     * Mapping [Loop the data from the collection]
     */
    public function map($prospect): array
    {
        $this->i++; //Increment serial Number
        return [
            $this->i,
            $prospect->ga->name ?? '',
            $prospect->name,
            $prospect->industrialArea->name ?? '',
            $prospect->fuelType->name ?? '',
            $prospect->potential ?? '',
            !empty($prospect->expected_date) ? Carbon::parse($prospect->expected_date)->format('d-m-Y') : '',
            $prospect->stage->parent->name,
            $prospect->stage->name,
            $prospect->statusType->name,
            !empty($prospect->status_date) ? Carbon::parse($prospect->status_date)->format('d-m-Y') : '',
        ];
    }
}
