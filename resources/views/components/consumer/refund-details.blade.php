{{-- Consumer basic details --}}
{{-- @type 0:Nothing, 1:SD Schemes, 2:SD payment, 3:Meter Details --}}

@props([
    'refund' => [],
    'type' => 0,
])
{{-- Consumer basic details --}}
<x-consumer.basic-details :consumer="$refund->consumer" :type="$type" {{ $attributes->merge(['class']) }} />
{{-- Refund details --}}
<div class="bg-primary-subtle p-2 mt-2 mb-2 rounded">
    <table class="table table-borderless table-info table-sm">
        <tr>
            <td><span class="fw-semibold">Request :</span>&nbsp;{{ $refund->request_no }}</td>
            <td><span class="fw-semibold">Status :</span>&nbsp;{{ $refund->status->name }}</td>
        </tr>
        <tr>
            <td><span class="fw-semibold">Requested :</span>&nbsp;{{ $refund->createdBy->first_name . ' ' . $refund->createdBy->last_name }}</td>
            <td><span class="fw-semibold">Date :</span>&nbsp;{{ $refund->created_at->format('d-m-Y') }}</td>
        </tr>
    </table>
</div>
<div class="p-2 mt-2 mb-2 rounded">
    <table class="table table-bordered table-sm">
        <thead class="table-success">
            <tr>
                <th width="1%" nowrap>S.No</th>
                <th width="1%" nowrap>Document</th>
                <th width="1%" nowrap>Notes</th>
            </tr>
        </thead>
        <tbody>
            @if($refund->refundDocuments->count() > 0 )
                @foreach($refund->refundDocuments as $document)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-center">
                            <a href="{{ url('master/dc/documents/' . $document->file_id) }}"
                            target="_blank"
                            title="{{ $document->file?->file_name }}">
                                <i class="bi bi-file-earmark-pdf-fill text-danger fs-4"></i>
                            </a>
                        </td>
                        <td class="text-break">{{ $document->notes ?? '-' }}</td>
                    </tr>
                    @endforeach
            @else
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        No documents uploaded.
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>