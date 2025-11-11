<div class="row mb-2">
    <label for="cluster_head" class="col-sm-3 col-form-label text-end">Cluster Head&nbsp;<span class="error text-danger">*</span>&nbsp;:</label>
    <div class="col-sm-8">
        <select name='cluster_head' id='cluster_head' class="form-select">
            <option value=''>Select Cluster Head</option>
            @if(!empty($users_list))
                @foreach($users_list->filter(fn($user) => $user->spotRoles->contains('id', 2)) as $list)
                    <option value='{{ $list->id }}'@selected($list->id == $prospect->cluster_head)>{{ $list->first_name }}&nbsp;{{ $list->last_name }}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>
<div class="row mb-2">
    <label for="ga_head" class="col-sm-3 col-form-label text-end">GA Head&nbsp;<span class="error text-danger">*</span>&nbsp;:</label>
    <div class="col-sm-8">
        <select name='ga_head' id='ga_head' class="form-select">
            <option value=''>Select Ga Head</option>
            @if(!empty($users_list))
                @foreach($users_list->filter(fn($user) => $user->spotRoles->contains('id', 3)) as $list)
                    <option value='{{ $list->id }}' @selected($list->id == $prospect->ga_head)>{{ $list->first_name }}&nbsp;{{ $list->last_name }}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>
<div class="row mb-2">
    <label for="ga_head" class="col-sm-3 col-form-label text-end">Sales Officer&nbsp;<span class="error text-danger">*</span>&nbsp;:</label>
    <div class="col-sm-8">
        <select name='sales_officer' id='sales_officer' class="form-select">
            <option value=''>Select sales officer</option>
            @if (!empty($users_list))    
                @foreach($users_list->filter(fn($user) => $user->spotRoles->contains('id', 4)) as $list)
                    <option value='{{ $list->id }}' @selected($list->id == $prospect->sales_officer)>{{ $list->first_name }}&nbsp;{{ $list->last_name }}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>
<div class="row mb-2">
    <label for="industrial_area_id" class="col-sm-3 col-form-label text-end">Industrial Area&nbsp;<span class="error text-danger">*</span>&nbsp;:</label>
    <div class="col-sm-8">
        <select name='industrial_area_id' id='industrial_area_id' class="form-select">
            <option value=''>Select Industrial Area</option>
            @foreach($industrial_areas as $area)
                <option value='{{ $area->id }}' @selected($area->id == $prospect->industrial_area_id)>{{ $area->name }}</option>
            @endforeach
        </select>
    </div>
</div>