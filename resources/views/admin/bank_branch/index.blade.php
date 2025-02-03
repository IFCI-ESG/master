@extends('layouts.vertical', ['title' => 'ESG PRAKRIT'])

@section('content')
    <div class="container-fluid">

    @section('css')
        @vite([
            'node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css',
            'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css',
            'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css',
            'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css'
        ])
    @endsection

    <div class="row">
        <div class="col-md-12">
            <div class="card border-primary mt-4">
                <div class="card card-success card-outline shadow p-1">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">List of Branches</h5>
                        <div class="dropdown">
                            <a class="dropdown-toggle text-dark text-decoration-none" href="#" role="button" id="branchMenu"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-bank"></i> Manage Branches
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="branchMenu">
                                <li>
                                <a class="dropdown-item" href="{{ route('admin.bank_branch_bulk.addbranch') }}">
    <i class="mdi mdi-plus"></i> Add Branch
</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.bank_branch_bulk.create') }}">
                                        <i class="mdi mdi-upload-multiple"></i> Bulk Upload
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="branchTable" class="table table-sm table-striped table-hover">
                            <thead>
                                <tr class="table-dark text-center" style="font-size: 0.8rem; height: 40px;">
                                    <th style="padding: 8px 5px; text-align: center; vertical-align: middle;">SL.No</th>
                                    <th style="padding: 8px 5px; text-align: center; vertical-align: middle;">Branch Name</th>
                                    <th style="padding: 8px 5px; text-align: center; vertical-align: middle;">Email</th>
                                    <th style="padding: 8px 5px; text-align: center; vertical-align: middle;">IFSC Code</th>
                                    <th style="padding: 8px 5px; text-align: center; vertical-align: middle;">Pincode</th>
                                    <th style="padding: 8px 5px; text-align: center; vertical-align: middle;">Contact Person</th>
                                    <th style="padding: 8px 5px; text-align: center; vertical-align: middle;">Designation</th>
                                    <th style="padding: 8px 5px; text-align: center; vertical-align: middle;">Mobile</th>
                                    <th style="padding: 8px 5px; text-align: center; vertical-align: middle;">Status</th>
                                    <th style="padding: 8px 5px; text-align: center; vertical-align: middle;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($branch_details) > 0)
                                    @foreach ($branch_details as $key => $bank)
                                        <tr>
                                            <td class="text-center" style="font-size:0.8rem;">{{ $key + 1 }}</td>
                                            <td class="text-center" style="font-size:0.8rem;">{{ $bank->name }}</td>
                                            <td class="text-center" style="font-size:0.8rem;">{{ $bank->email }}</td>
                                            <td class="text-center" style="font-size:0.8rem;">{{ $bank->ifsc_code }}</td>
                                            <td class="text-center" style="font-size:0.8rem;">{{ $bank->pincode }}</td>
                                            <td class="text-center" style="font-size:0.8rem;">{{ $bank->contact_person ?? '--' }}</td>
                                            <td class="text-center" style="font-size:0.8rem;">{{ $bank->designation ?? '--' }}</td>
                                            <td class="text-center" style="font-size:0.8rem;">{{ $bank->mobile ?? '--' }}</td>
                                            <td class="text-center" style="font-size:0.8rem;">
                                                @if ($bank->status == 'S')
                                                    <span class="badge bg-success">Created</span>
                                                @elseif($bank->status == 'D')
                                                    <span class="badge bg-warning">Draft</span>
                                                @endif
                                            </td>
                                            <td class="text-center" style="font-size:0.8rem;">
                                                <div class="dropdown">
                                                    <button class="btn btn-light dropdown-toggle" type="button" id="actionMenu{{ $bank->id }}"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="actionMenu{{ $bank->id }}">
                                                        @if ($bank->status == 'S')
                                                            <li>
                                                                <a class="dropdown-item text-warning"
                                                                   href="{{ route('admin.new_admin.com_list', ['bank_id' => encrypt($bank->id)]) }}">
                                                                    <i class="fa fa-eye"></i> View
                                                                </a>
                                                            </li>
                                                        @elseif($bank->status == 'D')
                                                            <li>
                                                                <a class="dropdown-item text-success"
                                                                   href="{{ route('admin.new_admin.edit', ['id' => encrypt($bank->id)]) }}">
                                                                    <i class="fa fa-edit"></i> Edit
                                                                </a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center" colspan="10"><b>No data found</b></td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @vite(['resources/js/pages/dashboard-4.init.js', 'resources/js/pages/datatables.init.js'])
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function () {
        // Check if DataTable is already initialized on the table
        if (!$.fn.dataTable.isDataTable('#branchTable')) {
            // Initialize DataTable only if it isn't already initialized
            $('#branchTable').DataTable({
                dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                responsive: true,
                paging: true,
                searching: true,
                ordering: true,
                info: true
            });
        }
    });
</script>

@endsection
