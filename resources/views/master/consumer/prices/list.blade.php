{{-- PNG Price setting --}}

@extends('layouts.layout')

@section('title', 'Gas Price')

@section('page-title', 'Gas Price')

@section('page-content')
    <div>
        @if ($gas_prices->count() > 0)
        <table class="table table-bordered table-primary">
            <thead class="table-primary">
                <tr>
                    <th width="1%" nowrap>S No</th>
                    <th>District</th>
                    <th>Basic Price</th>
                    <th>VAT%</th>
                    <th>RSP</th>
                    <th>Affective From</th>
                    <th>Affective To</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($gas_prices as $price)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $price->district->name ?? '' }}</td>
                        <td class="text-end">{{ numberFormat($price->basic_price, 2) }}</td>
                        <td class="text-end">{{ numberFormat($price->tax_value, 2) }}</td>
                        <td class="text-end">{{ numberFormat($price->rsp, 2) }}</td>
                        <td>{{ $price->effective_from }}</td>
                        <td>{{ $price->effective_to }}</td>
                        <td>
                            drop down
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @else
            No
        @endif
    </div>
@endsection