{{-- Access Denied--}}

@extends('layouts.layout')

@section('title', 'File not found')

@section('page-title', 'File not found')

@section('page-content')
    
    <div class="alert alert-danger">
        <strong><i class="bi bi-binoculars"></i>&nbsp;File not found / Invalid record</strong><br>
        The file you are trying to download is either invalid file record or file not found in server.
    </div>

@endsection