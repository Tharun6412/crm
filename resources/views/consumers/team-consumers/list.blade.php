@extends('layouts.layout')
@section('title','TeamConsumers')
@section('page-title','TeamConsumers')
@section('page-content')
<div>
    <form id="team-consumers-search-form" action="{{ url('consumers/waiting/pending-consumers') }}" method="GET">
        <div id="team-consumers-list" class="current-page-reload">
             @include('consumers.team-consumers.list-body')
        </div>
    </form>
</div>
@endsection
@once
   @push('scripts')
        @include('scripts.ajax-form-search',['form' => 'team-consumers'])
    @endpush 
@endonce
