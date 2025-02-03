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
                <form action="{{ route('admin.new_admin.store') }}" id="bankDetails_create" role="form" method="post" onsubmit="return validateForm()"
                    class='prevent_multiple_submit' files=true enctype='multipart/form-data' accept-charset="utf-8">
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
                                            <th style="font-size: 0.9rem"> Bank Name </th>
                                            <td>
                                                <input type="text" id=""  name="bank_name"
                                                    class="form-control form-control-sm text-right" style="width:50%">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-center" style="font-size: 0.9rem"> 2. </th>
                                            <th style="font-size: 0.9rem"> PAN </th>
                                            <td>
                                                <input type="text" id=""  name="pan"
                                                    class="form-control form-control-sm text-right" style="width:50%">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-center" style="font-size: 0.9rem"> 3. </th>
                                            <th style="font-size: 0.9rem"> Email </th>
                                            <td>
                                                <input type="email" id=""  name="email"
                                                    class="form-control form-control-sm text-right" style="width:50%" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-center" style="font-size: 0.9rem"> 4. </th>
                                            <th style="font-size: 0.9rem"> Contact Person </th>
                                            <td>
                                                <input type="text" id=""  name="contact_person"
                                                    class="form-control form-control-sm text-right" style="width:50%" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-center" style="font-size: 0.9rem"> 5. </th>
                                            <th style="font-size: 0.9rem"> Designation </th>
                                            <td>
                                                <input type="text" id=""  name="designation"
                                                    class="form-control form-control-sm text-right" style="width:50%">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-center" style="font-size: 0.9rem"> 6. </th>
                                            <th style="font-size: 0.9rem"> Mobile </th>
                                            <td>
                                                <input type="tel" id=""  name="mobile" min="0" pattern="(\+91[\-\s]?)?[789]\d{9}" 
                                                    class="form-control form-control-sm text-right" style="width:50%">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-center" style="font-size: 0.9rem"> 7. </th>
                                            <th style="font-size: 0.9rem"> Alternate Mobile </th>
                                            <td>
                                                <input type="number" id=""  name="altr_mobile" min="0"
                                                    class="form-control form-control-sm text-right" style="width:50%">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-center" style="font-size: 0.9rem"> 8. </th>
                                            <th style="font-size: 0.9rem"> Services </th>
                                            <td colspan="2">
                                                <table>
                                                    <tbody>
                                                        @foreach ($services as $key => $serve)
                                                            <tr>
                                                                <td style="width: 50%;">
                                                                    <label for="environment" style="font-size: 0.9rem">{{$serve->services}} </label>&nbsp;&nbsp;
                                                                </td>
                                                                <td class="text-center" style="width: 50%;">
                        <input type="checkbox" class="services margin-right" id="service_{{ $serve->id }}" name="services[]" value="{{$serve->id}}">
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
                    <div class="row">
                        <div class="col-md-2 offset-md-5">
                            <button type="submit" id="submit"
                                class="btn btn-primary btn-sm form-control form-control-sm form-control form-control-sm-sm">
                                <em class="fas fa-save"></em> Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }
    </script>
@section('script')
    @include('partials.js.prevent_multiple_submit')
    @vite(['resources/js/pages/sweet-alerts.init.js'])
@endsection
@endsection