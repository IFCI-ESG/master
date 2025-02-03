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
        <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show" role="alert">
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
            <form action="{{ route('admin.bank_branch_bulk.store') }}" id="branchDetails_create" role="form" method="post"
                onsubmit="return validateForm()" class='prevent_multiple_submit' files=true
                enctype='multipart/form-data' accept-charset="utf-8">

                
                @csrf
                <div class="card">
                    <div class="card card-success card-outline shadow p-1">
                        <b>Branch Details</b>
                    </div>
                    <div class="card border-primary m-2">
                        <div class="card-body mt-4">
                            <table class="table table-sm table-striped table-bordered table-hover">
                                <tbody>
                                    <tr class="table-success">
                                        <th class="text-center" style="width: 20%">Sr.No.</th>
                                        <th class="text-center" style="width: 30%">Particulars</th>
                                        <th class="text-center" style="width: 40%">Value</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="font-size: 0.9rem">1.</th>
                                        <th style="font-size: 0.9rem">Bank Name <span style="color: red;">*</span></th>
                                        <td>
                                            <input type="text" id="branch_name" name="branch_name"
                                                class="form-control form-control-sm text-right" style="width:50%"
                                                placeholder="Enter Branch Name" required />
                                            <span
                                                style="color: #888; font-size: 0.8rem; display: block; margin-top: 5px;">(Branch
                                                Name - Special Characters And Integers Are Not Allowed)</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="font-size: 0.9rem">2.</th>
                                        <th style="font-size: 0.9rem">Email <span style="color: red;">*</span></th>
                                        <td>
                                            <input type="email" id="email" name="email"
                                                class="form-control form-control-sm text-right" style="width:50%"
                                                placeholder="example@domain.com" required />
                                            <div id="email-error-message"
                                                style="color: red; display: none; font-size: 0.9rem;"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="font-size: 0.9rem">3.</th>
                                        <th style="font-size: 0.9rem">Contact Person <span style="color: red;">*</span>
                                        </th>
                                        <td>
                                            <input type="text" id="contact_person" name="contact_person"
                                                class="form-control form-control-sm text-right" style="width:50%"
                                                placeholder="Enter Contact Person" required />
                                            <span
                                                style="color: #888; font-size: 0.8rem; display: block; margin-top: 5px;">(Contact
                                                Person - Special Characters And Integers Are Not Allowed)</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="font-size: 0.9rem">4.</th>
                                        <th style="font-size: 0.9rem">Designation <span style="color: red;">*</span>
                                        </th>
                                        <td>
                                            <input type="text" id="designation" name="designation"
                                                class="form-control form-control-sm text-right" style="width:50%"
                                                placeholder="Enter Designation" />
                                            <span
                                                style="color: #888; font-size: 0.8rem; display: block; margin-top: 5px;">(Designation
                                                - Special Characters And Integers Are Not Allowed)</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="font-size: 0.9rem">5.</th>
                                        <th style="font-size: 0.9rem">Mobile <span style="color: red;">*</span> </th>
                                        <td>
                                            <input type="tel" id="mobile" name="mobile"
                                                class="form-control form-control-sm text-right" style="width:50%"
                                                placeholder="Enter 10 digit mobile number" required />
                                            <span
                                                style="color: #888; font-size: 0.8rem; display: block; margin-top: 5px;">(Please
                                                enter a valid 10-digit Mobile Number)</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="font-size: 0.9rem">6.</th>
                                        <th style="font-size: 0.9rem">IFSC Code <span style="color: red;">*</span></th>
                                        <td>
                                            <input type="text" id="ifsc_code" name="ifsc_code"
                                                class="form-control form-control-sm text-right" style="width:50%"
                                                placeholder="Enter IFSC Code" required maxlength="11" />
                                            <span style="color: #888; font-size: 0.8rem; display: block; margin-top: 5px;">(Please enter a valid 11-character IFSC Code)</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="font-size: 0.9rem">7.</th>
                                        <th style="font-size: 0.9rem">Pincode <span style="color: red;">*</span></th>
                                        <td>
                                            <input type="number" id="pincode" name="pincode"
                                                class="form-control form-control-sm text-right" style="width:50%"
                                                placeholder="Enter Pincode" required minlength="6" maxlength="6" />
                                            <span style="color: #888; font-size: 0.8rem; display: block; margin-top: 5px;">(Please enter a valid 6-digit Pincode)</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2 offset-md-4">
                        <a href="{{ route('admin.bank_branch_bulk.index') }}" class="btn btn-secondary btn-sm form-control form-control-sm">
                            <em class="fas fa-arrow-left"></em> Back
                        </a>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" id="submit" class="btn btn-primary btn-sm form-control form-control-sm" disabled>
                            <em class="fas fa-save"></em> Save As Draft
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Consolidate input validation and form validation
    function toggleSaveButton() {
        var branchName = document.getElementById("branch_name").value;
        var email = document.getElementById("email").value;
        var contactPerson = document.getElementById("contact_person").value;
        var designation = document.getElementById("designation").value;
        var mobile = document.getElementById("mobile").value;
        var ifscCode = document.getElementById("ifsc_code").value;
        var pincode = document.getElementById("pincode").value;

        var isValid = true;

        // Validation for each field
        if (!/^[A-Za-z\s]+$/.test(branchName)) isValid = false;
        if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email)) isValid = false;
        if (!/^[A-Za-z\s]+$/.test(contactPerson)) isValid = false;
        if (!/^[A-Za-z\s]+$/.test(designation)) isValid = false;
        if (!/^\d{10}$/.test(mobile)) isValid = false;
        if (!/^[A-Za-z]{4}\d{7}$/.test(ifscCode)) isValid = false;
        if (!/^\d{6}$/.test(pincode)) isValid = false;

        // Enable or disable the submit button
        document.getElementById("submit").disabled = !isValid;
    }

    // Attach event listener to input fields
    document.querySelectorAll('input').forEach(input => {
        input.addEventListener('input', toggleSaveButton);
    });

    // Validate form submission
    function validateForm() {
        return !document.getElementById("submit").disabled;
    }
</script>
@endsection
