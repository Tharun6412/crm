{{-- Prospects list --}}

@extends('layouts.layout')

@section('title', 'Prospects')

@section('page-title', 'Prospects')

@section('page-content')
    <div id="prospects-list" class="current-page-reload">
        @include('spot.prospects.list-body')
    </div>
@endsection
<script type="text/javascript">
    // Industrial Area Based on GA
    function getDetailsByGA(ga)
    {
        $.get("{{ url('spot/prospects/getDetailsByGA') }}", {ga_id : ga}, function(data) {
            $('#add-sub-form').html(data);
        });
    }
</script>
