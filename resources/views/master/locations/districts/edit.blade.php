<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit District</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="loc-dist-edit-success">
                <form action="{{ url('master/location/districts/'.$districts->id) }}" method="POST" id="loc-dist-edit-form">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        <label for="cluster_id" class="col-sm-2 col-form-label text-end">Cluster</label>
                        <div class="col-sm-9">
                            <select name="cluster_id" id="cluster_id" class="form-select">
                            <option value="">Select Cluster</option>
                            @foreach ($clusters as $item )
                               <option value="{{ $item->id }}" @selected($districts->cluster_id == $item->id)>{{ $item->name }}</option> 
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="state_id" class="col-sm-2 col-form-label text-end">State</label>
                        <div class="col-sm-9">
                            <select name="state_id" id="state_id" class="form-select">
                            <option value="">Select State</option>
                            @foreach ($states as $item )
                            <option value="{{ $item->id }}" @selected($districts->state_id == $item->id )>{{ $item->name }}</option> 
                            @endforeach
                        </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="ga_id" class="col-sm-2 col-form-label text-end">Geo Area</label>
                        <div class="col-sm-9">
                            <select name="ga_id" id="ga_id" class="form-select">
                            <option value="">Select GeoArea</option>
                            @foreach ($geo_areas as $item)
                               <option value="{{ $item->id }}" @selected($districts->ga_id == $item->id) >{{ $item->name }}</option> 
                            @endforeach
                            </select>
                        </div> 
                    </div>
                    <div class="row mb-3">
                        <label for="name" class="col-sm-2 col-form-label text-end">Name</label>
                        <div class="col-sm-9">
                            <input type="text" name="name" id="name" class="form-control" value="{{ $districts->name }}">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="code" class="col-sm-2 col-form-label text-end">Code</label>
                        <div class="col-sm-9">
                            <input type="text" name="code" id="code" class="form-control" value="{{ $districts->code }}">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="status" class="col-sm-2 col-form-label text-end">Status</label>
                        <div class="col-sm-9">
                            <select name="status" id="status" class="form-select">
                                <option value="1" @selected($districts->status == 1)>Enable</option>
                                <option value="0" @selected($districts->status == 0)>Disable</option>
                            </select>
                        </div>
                    </div>
                    <div id="loc-dist-create-error" class="text-danger"></div>
                    <div class="row mb-3">
                        <div class="offset-sm-2 col-sm-9">
                            <button type="submit" class="btn btn-success"><i class="bi bi-plus-lg"></i>&nbsp;Update</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Close</button>
        </div>
    </div>
</div>
{{-- Scripts --}}
@include('scripts.ajax-form-submit', ['form' => 'loc-dist-edit'])
