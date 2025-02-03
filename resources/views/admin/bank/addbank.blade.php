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
    <div class="row">

        <div class="col-md-12">
            <form action="{{ route('admin.new_admin.store') }}" id="bankDetails_create" role="form" method="post"
                onsubmit="return validateForm()" class='prevent_multiple_submit' files=true
                enctype='multipart/form-data' accept-charset="utf-8">
                @csrf
                <div class="card">
                    <div class="card card-success card-outline shadow p-1">
                        <b>Bank Details</b>
                    </div>
                    {{-- <input type="hidden" value="{{ $appMast->id }}" name="app_id">
                    <input type="hidden" name="claim_id" value="{{ $claimMast->id }}"> --}}
                    <div class="card border-primary m-2">
                        <div class="card-body mt-4">
                            <table class="table table-sm table-striped  table-bordered table-hover">
                                <tbody>
                                    <tr class="table-success">
                                        <th class="text-center" style="width: 20%">Sr.No.</th>
                                        <th class="text-center" style="width: 30%">Particulars</th>
                                        <th class="text-center" style="width: 40%">Value</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="font-size: 0.9rem"> 1. </th>
                                        <th style="font-size: 0.9rem">
                                            Bank Name <span style="color: red;">*</span>
                                        </th>
                                        <td>
                                            <input type="text" id="bank_name" name="bank_name"
                                                class="form-control form-control-sm text-right" style="width:50%"
                                                oninput="restrictBankNameInput(event)" placeholder="Enter Bank Name"
                                                required />
                                            <span
                                                style="color: #888; font-size: 0.8rem; display: block; margin-top: 5px;">(Bank
                                                Name - Special Characters And Integers Are Not Allowed)</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="font-size: 0.9rem"> 2. </th>
                                        <th style="font-size: 0.9rem">
                                            PAN <span style="color: red;">*</span>
                                        </th>
                                        <td>
                                            <input type="text" id="pan" name="pan"
                                                class="form-control form-control-sm text-right" style="width:50%"
                                                oninput="restrictPANInput(event)" onblur="validatePAN()"
                                                placeholder="ABCDE1234F" required />
                                            <div id="pan-error-message"
                                                style="color: red; display: none; font-size: 0.9rem;"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="font-size: 0.9rem"> 3. </th>
                                        <th style="font-size: 0.9rem">
                                            License Key <span style="color: red;">*</span>
                                        </th>
                                        <td>
                                            <input type="text" id="license_key" name="license_key"
                                                class="form-control form-control-sm text-right" style="width:50%"
                                                required />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="font-size: 0.9rem"> 4. </th>
                                        <th style="font-size: 0.9rem">
                                            Valid From <span style="color: red;">*</span>
                                        </th>
                                        <td>
                                            <input type="date" id="valid_from" name="valid_from"
                                                class="form-control form-control-sm text-right" style="width:50%"
                                                required onchange="setMinValidToDate()" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="font-size: 0.9rem"> 5. </th>
                                        <th style="font-size: 0.9rem">
                                            Valid To <span style="color: red;">*</span>
                                        </th>
                                        <td>
                                            <input type="date" id="valid_to" name="valid_to"
                                                class="form-control form-control-sm text-right" style="width:50%"
                                                required />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="font-size: 0.9rem"> 6. </th>
                                        <th style="font-size: 0.9rem"> Email <span style="color: red;">*</span></th>
                                        <td>
                                            <input type="email" id="email" name="email"
                                                class="form-control form-control-sm text-right" style="width:50%"
                                                oninput="restrictEmailInput(event)" onblur="validateEmail()"
                                                placeholder="example@domain.com" required />
                                            <div id="email-error-message"
                                                style="color: red; display: none; font-size: 0.9rem;"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="font-size: 0.9rem"> 7. </th>
                                        <th style="font-size: 0.9rem"> Contact Person <span style="color: red;">*</span>
                                        </th>
                                        <td>
                                            <input type="text" id="contact_person" name="contact_person"
                                                class="form-control form-control-sm text-right" style="width:50%"
                                                oninput="restrictContactPersonInput(event)"
                                                placeholder="Enter Contact Person" required />
                                            <span
                                                style="color: #888; font-size: 0.8rem; display: block; margin-top: 5px;">(Contact
                                                Person - Special Characters And Integers Are Not Allowed)</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="font-size: 0.9rem"> 8. </th>
                                        <th style="font-size: 0.9rem"> Designation <span style="color: red;">*</span>
                                        </th>
                                        <td>
                                            <input type="text" id="designation" name="designation"
                                                class="form-control form-control-sm text-right" style="width:50%"
                                                oninput="restrictDesignationInput(event)"
                                                placeholder="Enter Designation" />
                                            <span
                                                style="color: #888; font-size: 0.8rem; display: block; margin-top: 5px;">(Designation
                                                - Special Characters And Integers Are Not Allowed)</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="font-size: 0.9rem"> 9. </th>
                                        <th style="font-size: 0.9rem"> Mobile <span style="color: red;">*</span> </th>
                                        <td>
                                            <input type="tel" id="mobile" name="mobile"
                                                class="form-control form-control-sm text-right" style="width:50%"
                                                oninput="restrictMobileInput(event)"
                                                placeholder="Enter 10 digit mobile number" required />
                                            <span
                                                style="color: #888; font-size: 0.8rem; display: block; margin-top: 5px;">(Please
                                                enter a valid 10-digit Mobile Number)</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="font-size: 0.9rem"> 10. </th>
                                        <th style="font-size: 0.9rem"> Alternate Mobile <span
                                                style="color: red;">*</span></th>
                                        <td>
                                            <input type="number" id="altr_mobile" name="altr_mobile"
                                                class="form-control form-control-sm text-right" style="width:50%"
                                                oninput="restrictAlternateMobileInput(event)"
                                                placeholder="Enter Alternate Mobile" required />
                                            <span
                                                style="color: #888; font-size: 0.8rem; display: block; margin-top: 5px;">(Please
                                                enter a valid 10-digit Alternate Mobile Number)</span>

                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="font-size: 0.9rem"> 11. </th>
                                        <th style="font-size: 0.9rem"> Services <span style="color: red;">*</span></th>
                                        <td colspan="2">
                                            <table>
                                                <tbody>
                                                    @foreach ($services as $key => $serve)
                                                        <tr>
                                                            <td style="width: 50%;">
                                                                <label for="environment"
                                                                    style="font-size: 0.9rem">{{$serve->services}}
                                                                </label>&nbsp;&nbsp;
                                                            </td>
                                                            <td class="text-center" style="width: 50%;">
                                                                <input type="checkbox" class="services margin-right"
                                                                    id="service_{{ $serve->id }}" name="services[]"
                                                                    value="{{$serve->id}}">
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
                <!-- <br> -->
                <div class="row">
    <div class="col-md-2 offset-md-4">
        <a href="{{ route('admin.new_admin.index') }}" class="btn btn-secondary btn-sm form-control form-control-sm">
            <em class="fas fa-arrow-left"></em> Back
        </a>
    </div>
    <div class="col-md-2">
        <button type="submit" id="submit" class="btn btn-primary btn-sm form-control form-control-sm" disabled>
            <em class="fas fa-save"></em> Save As Draft
        </button>
    </div>
</div>
                


                    
                </div>
            </form>
        </div>
    </div>
</div>

<script>

    function toggleSaveButton() {
        var bankName = document.getElementById("bank_name").value;
        var panNumber = document.getElementById("pan").value;
        var license_key = document.getElementById("license_key").value;
        var valid_from = document.getElementById("valid_from").value;
        var valid_to = document.getElementById("valid_to").value;
        var email = document.getElementById("email").value;
        var contactPerson = document.getElementById("contact_person").value;
        var designation = document.getElementById("designation").value;
        var mobile = document.getElementById("mobile").value;
        var altr_mobile = document.getElementById("altr_mobile").value;

        var isValid = true;

        if (!/^[A-Za-z\s]+$/.test(bankName)) {
            isValid = false;
        }
        if (!/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/.test(panNumber)) {
            isValid = false;
        }
        if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email)) {
            isValid = false;
        }
        if (!/^[A-Za-z\s]+$/.test(contactPerson)) {
            isValid = false;
        }
        if (designation && !/^[A-Za-z\s]+$/.test(designation)) {
            isValid = false;
        }
        if (!/^[0-9]{10}$/.test(mobile)) {
            isValid = false;
        }
        if (!/^[0-9]{10}$/.test(altr_mobile)) {
            isValid = false;
        }
        if (!license_key) {
            isValid = false;
        }
        if (!valid_from) {
            isValid = false;
        }
        if (!valid_to) {
            isValid = false;
        }

        document.getElementById("submit").disabled = !isValid;
    }


    window.onload = function () {
        document.getElementById("bank_name").addEventListener("input", toggleSaveButton);
        document.getElementById("pan").addEventListener("input", toggleSaveButton);
        document.getElementById("license_key").addEventListener("input", toggleSaveButton);
        document.getElementById("valid_from").addEventListener("input", toggleSaveButton);
        document.getElementById("valid_to").addEventListener("input", toggleSaveButton);
        document.getElementById("email").addEventListener("input", toggleSaveButton);
        document.getElementById("contact_person").addEventListener("input", toggleSaveButton);
        document.getElementById("designation").addEventListener("input", toggleSaveButton);
        document.getElementById("mobile").addEventListener("input", toggleSaveButton);
        document.getElementById("altr_mobile").addEventListener("input", toggleSaveButton);
    };


    toggleSaveButton();
</script>
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
    function restrictPANInput(e) {
        var regex = /^[A-Z0-9]*$/;
        var inputValue = e.target.value;
        if (!regex.test(inputValue)) {
            e.target.value = inputValue.replace(/[^A-Z0-9]/g, '');
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
    function restrictAlternateMobileInput(e) {
        var regex = /^[0-9]*$/;
        var inputValue = e.target.value;
        if (!regex.test(inputValue)) {
            e.target.value = inputValue.replace(/[^0-9]/g, '');
        }
    }
    function setMinValidToDate() {
        var validFromDate = document.getElementById("valid_from").value;
        var validToDateInput = document.getElementById("valid_to");

        // Set the min date of the "Valid To" field as the "Valid From" date
        validToDateInput.min = validFromDate;
    }
</script>
<script>

    function validateForm() {
        const checkboxes = document.querySelectorAll('input[name="services[]"]');
        let isChecked = false;
        checkboxes.forEach((checkbox) => {
            if (checkbox.checked) {
                isChecked = true;
            }
        });
        if (!isChecked) {
            alert("Please select at least one purpose.");
            return false;
        }
        return true;
    }
</script>
@section('script')
@include('partials.js.prevent_multiple_submit')
@vite(['resources/js/pages/sweet-alerts.init.js'])
@endsection
@endsection