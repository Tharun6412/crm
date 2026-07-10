@props([
    'verification' => [],
])
<div class="table-responsive">
    <h4>Verified Details :</h4>
    <table class="table table-bordered table-striped">
        <thead class="table-success">
            <tr>
                <th width="1%">S.NO</th>
                <th>Verified Details</th>
                <th>Verified Status</th>
                <th>Issues</th>
            </tr>
        </thead>
        <tbody>
            @if ($verification->steps->count() > 0)  
            @foreach ($verification->steps as $step )
                {{-- @php
                    if($step->status == 0){
                        $status = '<span class="badge bg-danger">No</span>';
                    }else{
                        $status = '<span class="badge bg-success">Yes</span>';
                    }
                @endphp --}}
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $step->step->name ?? ''}}</td>
                    <td>
                        <span class="badge {{ $step->status == 1 ? 'bg-success' : 'bg-danger' }}">
                            @if($step->status == 1)
                                <i class="bi bi-check fs-6">&nbsp;</i>Yes
                            @else
                                <i class="bi bi-x fs-6">&nbsp;</i>No
                            @endif
                        </span>
                    </td>
                    <td>{{ $step->remarks ?? ''}}</td>
                </tr>
            @endforeach
            @else
                <tr>
                    <th colspan="6">No Records Found</th>
                </tr>
            @endif
        </tbody>
    </table>
</div>