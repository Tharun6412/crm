@extends('layouts.layout')
@section('title','Team Consumers')
@section('page-title','Team Consumers')
@section('page-content')
<div>
    <form action="{{ url('consumers/waiting/pending-consumers') }}" method="GET" id="team-consumers-search-form">        
        <div id="team-consumers-list" class="current-page-reload">
            @include('consumers.team-consumers.list-body')
        </div>
    </form>    
</div>
@endsection
@include('scripts.ajax-form-search',['form' => 'team-consumers'])
@include('scripts.ajax-form-submit', ['form' => 'team-bulk'])
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script type="text/javascript">
    $(document).on('click', '#bulkAssignBtn', function () {
        console.log('clicked');
        $('#bulkAssignSection').toggleClass('d-none');
    });
</script>
