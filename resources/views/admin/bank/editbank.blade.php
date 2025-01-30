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
                                        <th style="font-size: 0.9rem">
                                            Bank Name <span style="color: red;">*</span>
                                        </th>
                                        <td>
                                            <input type="text" id="bank_name" name="bank_name" value="{{$bank_details->name}}"
                                                class="form-control form-control-sm text-right" style="width:50%"
                                                oninput="restrictBankNameInput(event)" placeholder="Enter Bank Name"
                                                required />
                                        </td>
                                    </tr>
                                    <tr>
                                    <th class="text-center" style="font-size: 0.9rem"> 2. </th>
                                    <th style="font-size: 0.9rem">PAN <span style="color: red;">*</span></th>
                                     <td>
                                     <input type="text" id="pan" name="pan" value="{{$bank_details->pan}}"
                                     class="form-control form-control-sm text-right" style="width:50%; background-color: #f0f0f0; color: #333;"
                                     oninput="restrictPANInput(event)" onblur="validatePAN()"
                                     placeholder="ABCDE1234F" required />
                                  <div id="pan-error-message"
                                  style="color: red; display: none; font-size: 0.9rem;"></div>
                                   <!-- <span style="color: #888; font-size: 0.8rem; display: block; margin-top: 5px;">(PAN Number - Cannot be changed)</span> -->
                                    </td>
                                    </tr>
                                        <tr>
                                        <th class="text-center" style="font-size: 0.9rem"> 3. </th>
                                        <th style="font-size: 0.9rem"> Email <span style="color: red;">*</span></th>
                                        <td>
                                            <input type="email" id="email" name="email" value="{{$bank_details->email}}"
                                                class="form-control form-control-sm text-right" style="width:50%"
                                                oninput="restrictEmailInput(event)" onblur="validateEmail()"
                                                placeholder="example@domain.com" required />
                                            <div id="email-error-message"
                                                style="color: red; display: none; font-size: 0.9rem;"></div>
                                        </td>
                                    </tr>
                                        <tr>
                                        <th class="text-center" style="font-size: 0.9rem"> 4. </th>
                                        <th style="font-size: 0.9rem"> Contact Person <span style="color: red;">*</span>
                                        </th>
                                        <td>
                                            <input type="text" id="contact_person" name="contact_person" value="{{$bank_details->contact_person}}"
                                                class="form-control form-control-sm text-right" style="width:50%"
                                                oninput="restrictContactPersonInput(event)"
                                                placeholder="Enter Contact Person" required />
                                        </td>
                                    </tr>
                                        <tr>
                                        <th class="text-center" style="font-size: 0.9rem"> 5. </th>
                                        <th style="font-size: 0.9rem"> Designation <span style="color: red;">*</span>
                                        </th>
                                        <td>
                                            <input type="text" id="designation" name="designation" value="{{$bank_details->designation}}"
                                                class="form-control form-control-sm text-right" style="width:50%"
                                                oninput="restrictDesignationInput(event)"
                                                placeholder="Enter Designation" />
                                        </td>
                                    </tr>
                                        <tr>
                                        <th class="text-center" style="font-size: 0.9rem"> 6. </th>
                                        <th style="font-size: 0.9rem"> Mobile <span style="color: red;">*</span> </th>
                                        <td>
                                            <input type="tel" id="mobile" name="mobile" value="{{$bank_details->mobile}}"
                                                class="form-control form-control-sm text-right" style="width:50%"
                                                oninput="restrictMobileInput(event)"
                                                placeholder="Enter 10 digit mobile number" required />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="font-size: 0.9rem"> 7. </th>
                                        <th style="font-size: 0.9rem"> Alternate Mobile </th>
                                        <td>
                                            <input type="number" id="altr_mobile" name="altr_mobile" value="{{$bank_details->altr_mobile}}"
                                                class="form-control form-control-sm text-right" style="width:50%"
                                                oninput="restrictAlternateMobileInput(event)"
                                                placeholder="Enter Alternate Mobile" />
                                        </td>
                                    </tr>
                                    <tr>
                                            <th class="text-center" style="font-size: 0.9rem"> 8. </th>
                                            <th style="font-size: 0.9rem"> Purpose <span style="color: red;">*</span></th>
                                            <td colspan="2">
                                                <table>
                                                    <tbody>
                                                        @foreach ($services as $key => $serve)
                                                            <tr>
                                                                <td style="width: 50%;">
                                                                    <label for="environment" style="font-size: 0.9rem">{{$serve->services}} </label>&nbsp;&nbsp;
                                                                </td>
                                                                <td class="text-center" style="width: 50%;">
                                                                    <input type="checkbox" class="services margin-right" id="service_{{ $serve->id }}" name="services[]" value="{{$serve->id}}" {{ in_array($serve->id, $storedServices) ? 'checked' : '' }}>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                              
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row pb-3">
                        <div class="col-md-2 offset-md-2">
                            <button type="submit" id="save_submit"
                                class="btn btn-primary btn-sm form-control form-control-sm">
                                <em class="fas fa-save"></em>&nbsp; Update
                            </button>
                        </div>
                </form>
                <script>
    function showError(inputField, message) {
        var errorMessage = document.getElementById(inputField + "-error-message");
        errorMessage.style.display = "block";
        errorMessage.textContent = message;
    }
    function hideError(inputField) {
        var errorMessage = document.getElementById(inputField + "-error-message");
        errorMessage.style.display = "none";
    }
    function validatePAN() {
        var panNumber = document.getElementById("pan").value;
        var regex = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;

        if (!regex.test(panNumber)) {
            showError("pan", "Please enter a valid PAN number (e.g., ABCDE1234F).");
        } else {
            hideError("pan");
        }
    }
    function validateEmail() {
        var email = document.getElementById("email").value;
        var regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        if (!regex.test(email)) {
            showError("email", "Please enter a valid email address (e.g., example@domain.com).");
        } else {
            hideError("email");
        }
    }
    function restrictEmailInput(e) {
        var regex = /^[a-zA-Z0-9._%+-@]*$/;
        var inputValue = e.target.value;
        if (!regex.test(inputValue)) {
            e.target.value = inputValue.replace(/[^a-zA-Z0-9._%+-@]/g, '');
        }
    }

    function restrictBankNameInput(e) {
        var regex = /^[A-Za-z\s]*$/;
        var inputValue = e.target.value;
        if (!regex.test(inputValue)) {
            e.target.value = inputValue.replace(/[^A-Za-z\s]/g, '');
        }
    }
    function restrictContactPersonInput(e) {
        var regex = /^[A-Za-z\s]*$/;
        var inputValue = e.target.value;
        if (!regex.test(inputValue)) {
            e.target.value = inputValue.replace(/[^A-Za-z\s]/g, '');
        }
    }
    function restrictDesignationInput(e) {
        var regex = /^[A-Za-z\s]*$/;
        var inputValue = e.target.value;
        if (!regex.test(inputValue)) {
            e.target.value = inputValue.replace(/[^A-Za-z\s]/g, '');
        }
    }
    function restrictMobileInput(e) {
        var regex = /^[0-9]*$/;
        var inputValue = e.target.value;
        if (!regex.test(inputValue)) {
            e.target.value = inputValue.replace(/[^0-9]/g, '');
        }
    }

</script>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Check for success messages in the session
        @if(session('success'))
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                title: 'Error!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        @endif
    </script>
@endpush
@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\Admin\BankRequest', '#bankDetails_edit') !!}
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
