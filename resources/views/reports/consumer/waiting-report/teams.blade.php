<div class="mt-2">
    <div class="row row-cols-8 g-2 mb-2">
        <div class="col-3">
            <div class="bg-success bg-gradient rounded text-white py-1 px-2 fs-5">GA</div>
        </div>
        <div class="col-3">
            <div class="bg-primary bg-gradient rounded text-white text-center py-1 px-2 fs-5">Teams</div>
        </div>
    </div>
    @foreach ($geo_areas as $ga )
        <div class="row row-cols-10 g-2 mb-2">
            <div class="col-3">
                <div class="bg-body-secondary rounded py-1 px-2 fs-5 text-truncate">
                    <div class="d-flex justify-content-between">
                        <span>{{ $ga->name }}</span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="border rounded text-center py-1 px-2 fs-5">
                    <span><a href="{{ url('reports/consumer/waiting/getTeams') }}?ga_id={{ $ga->id }}&ga_name={{ $ga->name }}" class="link-modal">{{ $ga->teams->count() }}</a></span>
                </div>
            </div>
        </div>
    @endforeach
</div>
@include('scripts.link-modal')