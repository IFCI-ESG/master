@extends('layouts.user_vertical', ['title' => 'ESG PRAKRIT'])

@section('css')
    @vite(['node_modules/sweetalert2/dist/sweetalert2.min.css'])
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">
 

    @if ($errors->any())
        @foreach ($errors->all() as $error)
        <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert">
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                           {{ $error }}
        </div>
        @endforeach

    @endif

  @if(session('success'))
   
<div class="alert alert-success alert-dismissible bg-danger text-white border-0 fade show" role="alert">
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
       {{ session('success') }}
    </div>
    @elseif(session('error'))
        <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert">
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
           {{ session('error') }}
        </div>
    @endif
    

    <div class="row" >
        <div class="col-lg-8 offset-md-2">
            <div class="card card-success card-outline mt-5 ml-2" style="box-shadow: 0 4px 10px 0 rgba(182, 233, 152, 0.474), 0 5px 20px 0 rgba(182, 233, 152, 0.474);">
                <div class="card-header">
                    <b>Bank Details </b>
                </div>
                <div class="card border-primary">
                    <div class="card-body p-1 m-2">
                        <div class="row ">
                            <div class="table-responsive rounded col-md-12">
                                <table class="table table-bordered table-hover table-sm table-striped" id="appTable"
                                    style="width: 100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="">Sr. No.</th>
                                            <th class="">Bank Name</th>
                                            <th class="">Loan Type</th>
                                            <th class="">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bank_details as $key => $bank)
                                            <tr>
                                                <td class="text-center" style="font-size: 1rem"><b>{{ $key + 1 }}</b></td>
                                                <td class="text-center" style="font-size: 1rem">
                                                    {{ $bank->bank_name }}
                                                </td>
                                                <td class="text-center" style="font-size: 1rem">
                                                    {{ $bank->loan_type }}
                                                </td>
                                                <td class="text-center" style="font-size: 1rem">
                                                    <a class="btn btn-primary btn-sm" href="{{ route('user.fy',['bank_id' => encrypt($bank->bank_id),'class_type'=> encrypt($bank->class_type_id)])}}"> Create</a>
                                                </td>
                                            </tr>
                                        @endforeach
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


@endsection
