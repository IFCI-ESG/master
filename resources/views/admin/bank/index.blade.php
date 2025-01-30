@extends('layouts.vertical', ['title' => 'ESG PRAKRIT'])

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    @include('layouts.shared.page-title', ['title' => 'Exposure', 'subtitle' => ''])


    @section('css')
    @vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css'])
    @endsection

    <div class="row mb-2">
        <div class="col-sm-4">
            <a href="{{ route('admin.new_admin.create') }}"
                class="btn btn-danger rounded-pill waves-effect waves-light mb-3"><i class="mdi mdi-plus"></i> Create
                New Bank</a>
        </div>
    </div>



    <div class="row">
        <div class="col-md-12">

            <div class="card border-primary mt-4">
                <div class="card card-success card-outline shadow p-1">
                    <h5 class="card-title">Banks1</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="example" class="table table-sm table-striped table-hover">
                                <thead>
    <tr class="table-dark text-center" style="font-size: 0.8rem; height: 40px;">
        <th style="padding: 8px 5px; text-align: center; vertical-align: middle;">SL.No</th>
        <th style="padding: 8px 5px; text-align: center; vertical-align: middle;">Bank Name</th>
        <th style="padding: 8px 5px; text-align: center; vertical-align: middle;">Email</th>
        <th style="padding: 8px 5px; text-align: center; vertical-align: middle;">Contact Person</th>
        <th style="padding: 8px 5px; text-align: center; vertical-align: middle;">Designation</th>
        <th style="padding: 8px 5px; text-align: center; vertical-align: middle;">Mobile</th>
        <th style="padding: 8px 5px; text-align: center; vertical-align: middle;">Status</th>
        <th style="padding: 8px 5px; text-align: center; vertical-align: middle;">Action</th>
    </tr>
</thead>
                                    <tbody>
                                        {{-- {{dd($bank_details)}} --}}
                                        @if ($bank_details)
                                            @foreach ($bank_details as $key => $bank)
                                                <tr>
                                                    <td class="text-center" style="font-size:0.8rem;">{{ $key + 1 }}</td>
                                                    <td class="text-center" style="font-size:0.8rem;">{{$bank->name}}</td>
                                                    <td class="text-center" style="font-size:0.8rem;">{{$bank->email}}</td>
                                                    <td class="text-center" style="font-size:0.8rem;">
                                                        {{$bank->contact_person ? $bank->contact_person : '--'}}</td>
                                                    <td class="text-center" style="font-size:0.8rem;">
                                                        {{$bank->designation ? $bank->designation : '--'}}</td>
                                                    <td class="text-center" style="font-size:0.8rem ;">
                                                        {{$bank->mobile ? $bank->mobile : '--'}}</td>
                                                 
                                                    <td class="text-center">
                                                        @if ($bank->status == 'S')
                                                            <span class="badge text-bg-success"
                                                                style="background-color: #E5F5E5; color: #28A745; font-size: 0.8rem; padding: 3px 8px; border-radius: 4px;">Created</span>
                                                        @elseif($bank->status == 'D')
                                                            <span class="badge text-bg-warning"
                                                                style="background-color: #FFF4E5; color: #FFA500; font-size: 0.8rem; padding: 3px 8px; border-radius: 4px;">Draft</span>
                                                        @endif
                                                    </td>
                                                   <td class="text-center">
    @if ($bank->status == 'S')
        <a href="{{ route('admin.new_admin.com_list', ['bank_id' => encrypt($bank->id)]) }}" class="text-warning">
            <i class="fa fa-eye"></i>
        </a>
    @elseif($bank->status == 'D')
        <a href="{{ route('admin.new_admin.edit', ['id' => encrypt($bank->id)]) }}" class="text-success">
            <i class="fa fa-edit"></i>
        </a>
    @endif
</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@section('script')
@vite(['resources/js/pages/datatables.init.js'])
@endsection
<!-- Load jQuery first -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#example').DataTable({
            dom: 'Bfrtip', // This controls the elements on the page (B = buttons, f = search box, r = processing, t = table, i = table info, p = pagination)
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print' // Export buttons
            ],
            responsive: true,  // Ensures the table is responsive on small screens
            paging: true,      // Enable pagination
            searching: true,   // Enable search bar
            ordering: true,    // Enable sorting
            info: true         // Display the table information (e.g. "Showing 1 to 10 of 57 entries")
        });
    });
</script>

@endsection