{{-- Transactions --}}
@extends('layouts.layout')

@section('title', 'Transactions')

@section('page-title', 'Transactions')


@section('page-content')
    <div>
        <form action="{{ url('payments/transactions') }}" id="transactions-search-form" method="GET">
            <div id="transactions-list" class="current-page-reload">
                @include('payments.transactions.list-body')
            </div>
        </form>
    </div>
@endsection

@once
    @push('scripts')
        @include('scripts.ajax-form-search', ['form' => 'transactions'])
    @endpush
@endonce