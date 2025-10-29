{{-- List Body --}}
<div class="table-responsive">
    <table class="table table-bordered table-hover table-primary">
        <thead>
            <tr>
                <th width="1%" nowrap rowspan="2">S No</th>
                <th rowspan="2"></th>
                <th rowspan="2">GA</th>
                @for ($date = $y_start->copy(); $date->lte($y_end); $date->addMonth())
                    <th colspan="2" class="text-center">{{ $date->format('M-y') }}</th>
                @endfor
            </tr>
            <tr>
                @for ($date = $y_start->copy(); $date->lte($y_end); $date->addMonth())
                    @foreach ($segments as $segment)
                        <th>{{ $segment->code }}</th>
                    @endforeach
                @endfor
            </tr>
        </thead>
        <tbody>
            @foreach ($geo_areas as $ga)
            <tr>
                <td nowrap>{{ $loop->iteration }}</td>
                <td><a type="button" class="btn btn-secondary btn-sm link-modal" href="{{ url('spot/targets/'.$ga->id.'/edit') }}?target_year={{ $target_year }}">Edit/Update</a></td>
                <td nowrap>{{ $ga->name }}</td>
                @for ($date = $y_start->copy(); $date->lte($y_end); $date->addMonth())
                    @foreach ($segments as $segment)
                        @php
                            $target_val = $targets->where('ga_id', $ga->id)->where('segment_id', $segment->id)->where('target_date', $date->copy()->startOfMonth()->format('Y-m-d'))->first();
                        @endphp
                        <td class="text-end"> {{ $target_val->target_value ?? '' }}</td>
                    @endforeach
                @endfor
            </tr>
            @endforeach
            <tr>
                <td colspan="3" class="text-end">Totals</td>
                @for ($date = $y_start->copy(); $date->lte($y_end); $date->addMonth())
                    @php
                        $segment_val[2] = $segment_val[3] = 0;
                    @endphp
                    @foreach ($segments as $segment)
                        @php
                            $targets_sum_val = $targets->where('segment_id', $segment->id)->where('target_date', $date->copy()->startOfMonth()->format('Y-m-d'))->sum('target_value');
                            $segment_val[$segment->id] += $targets_sum_val;
                        @endphp
                        <td class="text-end">{{ $segment_val[$segment->id] }}</td>
                    @endforeach
                @endfor
            </tr>
        </tbody>
    </table>
</div>
@include('scripts.link-modal')
<script>
    function reloadTargets()
    {
        $('#target-year-form').submit();
    }
</script>