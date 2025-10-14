{{-- Prospects list --}}

@extends('layouts.layout')

@section('title', 'Prospects')

@section('page-title', 'Prospects')

@section('page-content')
    <div id="prospects-list" class="current-page-reload">
        @include('spot.prospects.list-body')
    </div>
@endsection
@include('scripts.link-modal')
<script type="text/javascript">
    // Industrial Area Based on GA
    function getIndustrialAreaByGA(ga)
    {
        let options = '<option value="">Select</option>';
        $.get("{{ url('spot/prospects/getIndustrialAreaByGA') }}", {ga_id : ga}, function(data) {
            $.each(data.industrial_areas, function(index, area){
                options += `<option value="${area.id}">${area.name}</option>`;
            });
            $('#industrial_area_id').html(options);
        });
    }
</script>
