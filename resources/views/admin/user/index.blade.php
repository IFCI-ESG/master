@extends('layouts.vertical', ['title' => 'ESG PRAKRIT'])

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        @include('layouts.shared.page-title' , ['title' => 'Exposure','subtitle' => ''])
@section('css')
    @vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css'])
@endsection
    <div class="row mb-2">
        <div class="col-sm-4">
            <a href="{{ route('admin.user.bulk.company.create') }}" class="btn btn-danger rounded-pill waves-effect waves-light mb-3"><i class="mdi mdi-plus"></i> Create Exposure</a>
        </div>
  
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card border-primary mt-3">
                <div class="card card-success card-outline shadow p-1">
                   <h5 class="card-title">Corporate Exposure List</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="example" class="table table-sm table-striped table-hover">
                                    <thead>
                                        <tr class="table-dark text-center">
                                            <th>Sr. No</th>
                                            <th>Company Name</th>
                                            <th>Company Type</th>
                                            <th>Sector</th>
                                            <th>PAN</th>
                                            <th>Unique Login ID</th>
                                            {{-- <th>Email</th>
                                            <th>Mobile</th> --}}
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($corp_detail)
                                            @foreach ($corp_detail as $key => $user)
                                                <tr>
                                                    <td class="text-center">{{ $key + 1 }}</td>
                                                    <td class="text-center">{{$user->name}}</td>
                                                    <td class="text-center">{{$user->comp_type}}</td>
                                                    <td class="text-center">{{$user->sector}}</td>
                                                    <td class="text-center">{{$user->pan ? $user->pan : '--'}}</td>
                                                    <td class="text-center">{{$user->unique_login_id ? $user->unique_login_id : '--'}}</td>
                                                    {{-- <td class="text-center" style="font-size:1rem;">{{$user->email}}</td>
                                                    <td class="text-center" style="font-size:1rem;">{{$user->mobile}}</td> --}}
                                                    <td class="text-center">
                                                        @if ($user->status == 'S')
                                                            <span class="text-success"><b>Created</b></span>
                                                        @elseif($user->status == 'D')
                                                            <span class="text-warning"><b>Draft</b></span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        {{-- @if ($user->status == 'S')
                                                            <a href="{{route('admin.user.viewuser',['id' => encrypt($user->id)])}}" class="btn btn-warning btn-sm">
                                                                <i class="fa fa-eye"></i>View</a>
                                                        @elseif($user->status == 'D') --}}
                                                            @if($user->created_by==Auth::user()->id)
                                                                <a href="{{route('admin.user.edituser',['id' => encrypt($user->id)])}}" class="btn btn-primary Custom-btn-edit btn-sm">
                                                                    <i class="fa fa-edit"></i>Edit</a>
                                                            @else
                                                                <a href="{{route('admin.user.existuser_edit',['id' => encrypt($user->id)])}}" class="btn Custom-btn-edit btn-sm  btn-primary ">
                                                                    <i class="fa fa-edit"></i>Edit</a>
                                                            @endif
                                                            {{-- <a class="btn btn-danger btn-sm" onclick="delButtonId({{$user->id}})">
                                                                <i class="fa fa-trash"></i></a> --}}
                                                        {{-- @endif --}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td class="text-center" colspan="5">No data found</td>
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
@section('script')


    @vite(['resources/js/pages/dashboard-4.init.js','resources/js/pages/datatables.init.js'])
@endsection
    <script>

        // $(document).ready(function() {
        //     $('table').DataTable();
        // });


// $(document).ready(function() {
// $('table').DataTable({
//     "bPaginate": true,
//     "bLengthChange": true,
//     "bFilter": true,
//     "bInfo": true,
//     "bAutoWidth": true,
//     "bPaginate":true,
//  "sPaginationType":"full_numbers",


// });
// });
        function delButtonId(id) {
            // alert(id);
            swal({
                    title: "Do You Want to Delete this Company and All the records related to that",
                    icon: "warning",
                    buttons: {
                        cancel: true,
                        confirm: {
                            text: "Yes",
                            value: "Y",
                        },
                    },
                    dangerMode: true,
                    closeOnClickOutside: false,
                })
                .then((result) => {
                    if (result == 'Y') {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "GET",
                            url: 'user/delete/' + id ,
                            success: function(data) {
                                // console.log(data);
                                if (data == true) {
                                    swal(
                                        'Deleted!',
                                        'Company has been deleted.',
                                        'success')
                                    window.location.reload();
                                } else {
                                    swal(
                                        'Not Deleted!',
                                        'Company has been Not Deleted.',
                                        'warning')

                                }
                            }
                        })
                    }
                });
            }
    </script>




@endsection





