<form name="filterForm" id="filterForm">
    <div class="clearfix">
        <div class="float-start d-flex align-items-center">
            <div class="me-1">
                <input class="form-control form-control-sm" type="text" id="keywords" name="keywords" placeholder="Search...">
            </div>
            <div class="me-1">
                <a class="btn btn-success btn-sm" id="search" title="search">
                    <i  class="bi bi-search"></i>
                </a>
            </div>
            <div class="me-1">
                <a class="btn btn-warning btn-sm" onclick="reset_lead_body()" title="Reset">
                    <i  class="bi bi-arrow-clockwise"></i>
                </a>
            </div>
            <div class="me-1">
                <span class="cgd-records-count"><strong><? print '(' . 0 . ' ' . "Records" .')';?></strong></span>
            </div>
        </div>
        <div class="float-end d-flex align-items-center">
            <div class="me-1">
                <a href="{{ url('spot/prospects/create') }}" class="btn btn-success btn-sm link-modal">
                    <i class="bi bi-plus-lg"></i>&nbsp;Create
                </a>
            </div>
            {{-- Right Section --}}
            {{-- <div class="me-1">
                <button type="button" class="btn btn-sm btn-primary"><i class="bi bi-download"></i>&nbsp;Export</button>
            </div>
            <div class="me-1">
                <select class="form-select form-select-sm">
                    <option value="10" >10 Records</option>
                    <option value="20">20 Records</option>
                    <option value="50">50 Records</option>
                    <option value="100">100 Records</option>
                    <option value="1000">1000 Records</option>
                </select>
            </div> --}}
        </div>
    </div>
    <button type="submit" class="d-none" id="submit_content"></button>
    <!-- Display prospects list -->
    <div class="table-responsive spot-table mt-2">
        <table class="table table-bordered table-hover table-striped table-sm align-middle">
            <thead>
                <tr class="spot-table-bg">
                    <th nowrap>S No.</th>
                    <th nowrap>
                        <a href="javascript:void(0)">
                            GA</a>
                    </th>
                    <th nowrap>
                        <a href="javascript:void(0)">
                            Prospect Name
                        </a>
                    </th>
                    <th nowrap>
                        <a href="javascript:void(0)">
                            Industrial Area
                        </a>
                    </th>
                    <th nowrap>
                        <a href="javascript:void(0)">
                            Current Fuel
                        </a>
                    </th>
                    <th class="text-end">
                        <a href="javascript:void(0)">
                           Natural Gas<br/>Potential (SCMD)
                        </a>
                    </th>
                    <th nowrap>
                        <a href="javascript:void(0)">
                            Gas service<br/>expected date</a>
                    </th>
                    <th nowrap class="text-center">
                        <a href="javascript:void(0)">
                            Stage</a>
                    </th>
                    <th nowrap class="text-center">
                        <a href="javascript:void(0)">
                            Sub Stage</a>
                    </th>
                    <th nowrap class="text-center">
                        <a href="javascript:void(0)">
                            Last Status date</a>
                    </th>
                    <th nowrap class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if ($prospects->count() > 0)
                    @foreach ($prospects as $prospect)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="11">No records found</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</form>
@include('scripts.link-modal')
