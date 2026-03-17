{{-- List Body --}}
<div class="table-responsive">
    <table class="table table-bordered table-primary">
        <thead class="table-primary">
            <tr>
                <th width="1%" nowrap rowspan="2">S No</th>
                <th rowspan="2">Action</th>
                <th rowspan="2">GA</th>
                @for ($date = $y_start->copy(); $date->lte($y_end); $date->addMonth())
                    <th colspan="2" class="text-center">{{ $date->format('M-y') }}</th>
                @endfor
                <th colspan="2" class="text-center">Totals</th>
            </tr>
            <tr>
                @for ($date = $y_start->copy(); $date->lte($y_end); $date->addMonth())
                    @foreach ($segments as $segment)
                        <th nowrap>{{ $segment->code }}</th>
                    @endforeach
                @endfor
                @foreach ($segments as $segment)
                    <th nowrap>{{ $segment->code }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($geo_areas as $ga)
                <tr>
                    <td nowrap>{{ $loop->iteration }}</td>
                    <td nowrap>
                        <x-auth.link type="button" class="btn btn-secondary btn-sm link-modal" action="trgt" href="{{ url('spot/targets/'.$ga->id.'/edit') }}?target_year={{ $target_year }}"><i class="bi bi-pencil"></i>&nbsp;Edit</x-auth.link>
                    </td>
                    <td nowrap>{{ $ga->name }}</td>
                    @for ($date = $y_start->copy(); $date->lte($y_end); $date->addMonth())
                        @foreach ($segments as $segment)
                            @php
                                $target_val = $targets->where('ga_id', $ga->id)->where('segment_id', $segment->id)->where('target_date', $date->copy()->startOfMonth()->format('Y-m-d'))->first();
                            @endphp
                            <td class="text-end {{ ($segment->id == 2) ? 'bg-light' : '' }}">{{ numberFormat($target_val->target_value ?? 0) }}</td>
                        @endforeach
                    @endfor
                    @foreach ($segments as $segment)
                        @php
                            $target_val = $targets->where('ga_id', $ga->id)->where('segment_id', $segment->id)->sum('target_value');
                        @endphp
                        <td class="text-end bg-primary-subtle fw-semibold">{{ numberFormat($target_val ?? 0) }}</td>
                    @endforeach
                </tr>
            @endforeach
            <tr>
                <td colspan="3" class="text-end bg-primary-subtle">Totals</td>
                @for ($date = $y_start->copy(); $date->lte($y_end); $date->addMonth())
                    @php
                        $segment_val[2] = $segment_val[3] = 0;
                    @endphp
                    @foreach ($segments as $segment)
                        @php
                            $targets_sum_val = $targets->where('segment_id', $segment->id)->where('target_date', $date->copy()->startOfMonth()->format('Y-m-d'))->sum('target_value');
                            $segment_val[$segment->id] += $targets_sum_val;
                        @endphp
                        <td class="text-end bg-primary-subtle fw-semibold">{{ $segment_val[$segment->id] }}</td>
                    @endforeach
                @endfor
                @foreach ($segments as $segment)
                    @php
                        $target_val = $targets->where('segment_id', $segment->id)->sum('target_value');
                    @endphp
                    <td class="text-end bg-primary-subtle fw-bold">{{ numberFormat($target_val ?? 0) }}</td>
                @endforeach
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