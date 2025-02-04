@extends('layouts.vertical', ['title' => 'ESG PRAKRIT'])

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

        <div class="row ">
            <div class="col-md-12">
                <form action="{{ route('admin.new_admin.update') }}" id="bankDetails_edit" role="form" method="post" onsubmit="return validateForm()"
                    class='det_prevent_multiple_submit' files=true enctype='multipart/form-data' accept-charset="utf-8">
                    @csrf
                    <div class="card border-success m-2">
                        <div class="card card-success card-outline shadow p-1">
                            <b>Bank Details</b>
                        </div>
                        <input type="hidden" value="{{ $bank_details->id }}" name="user_id">
                        {{-- <input type="hidden" name="claim_id" value="{{ $claimMast->id }}"> --}}
                        <div class="card border-primary m-2">
                            <div class="card-body mt-4">
                                <table class="table table-sm table-striped  table-hover">
                                    <tbody>
                                        <tr class="table-success">
                                            <th class="text-center" style="width: 20%">S.No</th>
                                            <th class="text-center" style="width: 30%">Particulars</th>
                                            <th class="text-center" style="width: 40%">Value</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center" style="font-size: 0.9rem"> 1. </th>
                                            <th style="font-size: 0.9rem"> Bank Name </th>
                                            <td>
                                                <input type="text" id=""  name="bank_name" value="{{$bank_details->name}}"
                                                    class="form-control form-control-sm text-right" style="width:50%">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-center" style="font-size: 0.9rem"> 2. </th>
                                            <th style="font-size: 0.9rem"> Email </th>
                                            <td>
                                                <input type="text" id=""  name="email" value="{{$bank_details->email}}"
                                                    class="form-control form-control-sm text-right" style="width:50%">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-center" style="font-size: 0.9rem"> 3. </th>
                                            <th style="font-size: 0.9rem"> Contact Person </th>
                                            <td>
                                                <input type="text" id=""  name="contact_person" value="{{$bank_details->contact_person}}"
                                                    class="form-control form-control-sm text-right" style="width:50%">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-center" style="font-size: 0.9rem"> 4. </th>
                                            <th style="font-size: 0.9rem"> Designation </th>
                                            <td>
                                                <input type="text" id=""  name="designation" value="{{$bank_details->designation}}"
                                                    class="form-control form-control-sm text-right" style="width:50%">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-center" style="font-size: 0.9rem"> 5. </th>
                                            <th style="font-size: 0.9rem"> Mobile </th>
                                            <td>
                                                <input type="number" id=""  name="mobile" min="0" value="{{$bank_details->mobile}}"
                                                    class="form-control form-control-sm text-right" style="width:50%">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-center" style="font-size: 0.9rem"> 6. </th>
                                            <th style="font-size: 0.9rem"> Purpose </th>
                                            <td colspan="2">
                                                <label for="environment"
                                                    style="font-size: 0.9rem">Environment:</label>&nbsp;&nbsp;
                                                <input type="checkbox" id="environment" name="purpose[]"
                                                    value="E"
                                                    {{ in_array('E', explode(',', $bank_details->purpose ?? '')) ? 'checked' : '' }}>
                                                <br>
                                                <label for="social"
                                                    style="font-size: 0.9rem">Social:</label>&nbsp;&nbsp;
                                                <input type="checkbox" id="social" name="purpose[]" value="S"
                                                    {{ in_array('S', explode(',', $bank_details->purpose ?? '')) ? 'checked' : '' }}>
                                                <br>
                                                <label for="governance"
                                                    style="font-size: 0.9rem">Governance:</label>&nbsp;&nbsp;
                                                <input type="checkbox" id="governance" name="purpose[]"
                                                    value="G"
                                                    {{ in_array('G', explode(',', $bank_details->purpose ?? '')) ? 'checked' : '' }}>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <br>
                    {{-- <div class="row">
                        <div class="col-md-2 offset-md-5">
                            <button type="submit" id="submit"
                                class="btn btn-primary btn-sm form-control form-control-sm form-control form-control-sm-sm">
                                <em class="fas fa-save"></em> Update
                            </button>
                        </div>
                    </div> --}}
                    <div class="row pb-3">
                        <div class="col-md-2 offset-md-2">
                            <button type="submit" id="save_submit"
                                class="btn btn-primary btn-sm form-control form-control-sm">
                                <em class="fas fa-save"></em>&nbsp; Update
                            </button>
                        </div>
                </form>

                    <div class="col-md-2 offset-md-0">
                        <form action="{{ route('admin.new_admin.submit') }}" id="final_submit" role="form" method="post"
                            class='fin_prevent_multiple_submit' files=true enctype='multipart/form-data' accept-charset="utf-8">
                            @csrf
                            <button type="submit" id="finalsubmit" @if($bank_details->status=='S') disabled @endif
                                class="btn btn-primary btn-sm form-control form-control-sm">
                                <em class="fas fa-save"></em> Submit
                            </button>
                            <input type="hidden" name="user_id" value="{{ $bank_details->id }}">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\Admin\BankRequest', '#bankDetails_edit') !!}
    {{-- @include('partials.js.prevent_multiple_submit') --}}
    <script>
        $(document).ready(function() {

            const saveBtn = document.getElementById("save_submit");
            const finalBtn = document.getElementById("finalsubmit");

            $('.det_prevent_multiple_submit').on('submit', function() {
                if ($('.det_msg').length === 0) {
                    $( ".det_prevent_multiple_submit" ).parent().append('<div class="offset-md-4 det_msg"><span class="text-danger text-sm text-center">Please wait while your request is being processed. &nbsp&nbsp&nbsp<i class="fa fa-spinner fa-spin" style="font-size:24px;color:black"></i></span></div>');
                }
                saveBtn.disabled = true;
                setTimeout(function(){saveBtn.disabled = false;}, (1000*20));
                finalBtn.disabled = true;
                setTimeout(function(){finalBtn.disabled = false;}, (1000*20));
                setTimeout(function(){$( ".det_msg" ).hide()}, (1000*20));
            });


            $('.fin_prevent_multiple_submit').on('submit', function() {
                if ($('.det_msg').length === 0) {
                    $(finalBtn).parent().after('<div class="offset-md-4 det_msg"><span class="text-danger text-sm text-center">Please wait while your request is being processed. &nbsp&nbsp&nbsp<i class="fa fa-spinner fa-spin" style="font-size:24px;color:black"></i></span></div>');
                }
                finalBtn.disabled = true;
                setTimeout(function(){finalBtn.disabled = false;}, (1000*20));
                saveBtn.disabled = true;
                setTimeout(function(){saveBtn.disabled = false;}, (1000*20));
                setTimeout(function(){$( ".det_msg" ).hide()}, (1000*20));
            });

            // const saveBtn = document.getElementById("save_submit");
            // const finalBtn = document.getElementById("finalsubmit");
            // $('.prevent_multiple_submit').on('submit', function() {
            //     if ($('.msg').length === 0) {
            //         $(finalBtn).parent().after(
            //         '<div class="offset-md-4 msg"><span class="text-danger text-sm text-center">Please wait while your request is being processed. &nbsp&nbsp&nbsp<i class="fa fa-spinner fa-spin" style="font-size:24px;color:black"></i></span></div>'
            //         );
            //     }

            //     saveBtn.disabled = true;
            //     finalBtn.disabled = true;

            //     setTimeout(function() {
            //         saveBtn.disabled = false;
            //     }, (1000 * 20));
            //     setTimeout(function() {
            //         finalBtn.disabled = false;
            //     }, (1000 * 20));
            //     setTimeout(function() {
            //         $(".msg").hide()
            //     }, (1000 * 20));
            // });
        });

        function validateForm() {
            const checkboxes = document.querySelectorAll('input[name="purpose[]"]');
            let isChecked = false;
            checkboxes.forEach((checkbox) => {
                if (checkbox.checked) {
                    isChecked = true;
                }
            });
            if (!isChecked) {
                alert("Please select at least one purpose.");
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }
    </script>
@endpush
