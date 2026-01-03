{{-- Meter Change list body --}}

{{-- Search form --}}
<div class="row gx-1 mb-1">
    <div class="col-auto">
        <div class="input-group input-group-sm">
            <span class="input-group-text" id="search-key">Search</span>
            <input type="text" name="key" id="search-key" class="form-control" value="{{ request()->key }}">
        </div>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-search"></i></button>
    </div>
    <div class="col-auto">
        <a href="{{ url('consumers/meterChange') }}" class="btn btn-warning btn-sm"><i class="bi bi-arrow-clockwise"></i></a>
    </div>
    <div class="col-auto float-end">
        <x-auth.link href="{{ url('consumers/meterChange/2/edit') }}" class="btn btn-success btn-sm link-modal">Create</x-auth.link>
    </div>
</div>
{{-- Consumers list --}}
<div class="table-responsive" style="min-height: 500px;">
    <table class="table table-bordered table-hover">
        <thead class="table-success">
            <tr>
                <th width="1%" nowrap>Meter Change</th>
            </tr>
        </thead>
        <tbody>
            @if ($meterChange->count() > 0)
                @foreach ($meterChange as $change)
                    <tr>
                        <td>{{ $change->meter->consumer_id }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="9">
                        <x-layouts.callout-info>No records found!</x->
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
@include('scripts.link-modal')