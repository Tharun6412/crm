@props([
    'verification' => [],
])
<div class="table-responsive">
    <h4>Verified Details :</h4>
    <table class="table table-bordered">
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
                @php
                    if($step->status == 0){
                        $status = 'No';
                    }else{
                        $status = 'Yes';
                    }
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $step->step->name ?? ''}}</td>
                    <td>{{ $status ?? '' }}</td>
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