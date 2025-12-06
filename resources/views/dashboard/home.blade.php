@extends('layouts.layout', ['dashboard' => 1])

@section('page-title', 'Welcome')

@section('page-content')
    <h1>Welcome to MeghaGas {{ env('APP_VERSION') }}</h1>
@endsection