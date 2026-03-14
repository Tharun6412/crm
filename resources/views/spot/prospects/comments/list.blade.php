{{-- Displaying Last 50 Comments --}}
@extends('layouts.layout')

@section('title', 'Comments')

@section('page-title', 'Comments')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('spot') }}">SPot</a></li>
@endsection

@section('page-content')
    <div class="bd-callout bd-callout-info mt-0 mb-3">Displaying latest 50 comments</div>
        <table class="table table-bordered table-striped table-hover bg-white">
            <thead class="table-success">
                <tr>
                    <th width="1%" nowrap>S No</th>
                    <th>Prospect</th>
                    <th>Comment</th>
                    <th>By</th>
                    <th>Comment Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($comments as $comment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><a class="link-modal" href="{{ url('spot/prospects/'.$comment->prospect_id) }}">{{ $comment->prospects->name }}</a></td>
                        <td>{{ $comment->comments }}</td>
                        <td>{{ $comment->createdBy->first_name }}&nbsp;{{ $comment->createdBy->last_name }}</td>
                        <td>{{ $comment->created_at->format('d-m-Y H:i:s') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center bg-info-subtle fw-semibold">
                            No comments found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
@endsection
@include('scripts.link-modal')

