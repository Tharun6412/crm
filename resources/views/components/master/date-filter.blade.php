{{-- Proposal date filter --}}
<div class="dropdown float-end">
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (!empty(request()->get('date_from')) AND !empty(request()->get('date_to'))) ? '-fill' : '' }}"></i>
        @if (!empty(request()->get('date_from')) AND !empty(request()->get('date_to')))
            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
        @endif
    </button>
    <div class="dropdown-menu p-3 bg-light">
        <label for="created_at" class="form-label">Date from:</label>
        <div class="input-group input-group-sm">
            <input type="text" name="date_from" id="date_from" class="form-control" placeholder="DD-MM-YYYY" value="{{ request()->get('date_from') }}">
            <label for="date_from" class="input-group-text"><i class="bi bi-calendar3"></i></label>
        </div>
        <label for="created_at" class="form-label">Date to:</label>
        <div class="input-group input-group-sm">
            <input type="text" name="date_to" id="date_to" class="form-control" placeholder="DD-MM-YYYY" value="{{ request()->get('date_to') }}">
            <label for="date_to" class="input-group-text"><i class="bi bi-calendar3"></i></label>
        </div>
    </div>
</div>
{{-- Dynamic script --}}
@include('scripts.datepicker', ['list' => ['date_from', 'date_to']])