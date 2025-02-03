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
            <form action="#" id="branchDetails_view" role="form" method="post" class='prevent_multiple_submit' files=true enctype='multipart/form-data' accept-charset="utf-8">

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
                                            <input type="text" id="branch_name" name="branch_name" class="form-control form-control-sm text-right" style="width:50%" value="{{ old('branch_name', $branch->branch_name) }}" readonly />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="font-size: 0.9rem">2.</th>
                                        <th style="font-size: 0.9rem">Email <span style="color: red;">*</span></th>
                                        <td>
                                            <input type="email" id="email" name="email" class="form-control form-control-sm text-right" style="width:50%" value="{{ old('email', $branch->email) }}" readonly />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="font-size: 0.9rem">3.</th>
                                        <th style="font-size: 0.9rem">Contact Person <span style="color: red;">*</span></th>
                                        <td>
                                            <input type="text" id="contact_person" name="contact_person" class="form-control form-control-sm text-right" style="width:50%" value="{{ old('contact_person', $branch->contact_person) }}" readonly />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="font-size: 0.9rem">4.</th>
                                        <th style="font-size: 0.9rem">Designation <span style="color: red;">*</span></th>
                                        <td>
                                            <input type="text" id="designation" name="designation" class="form-control form-control-sm text-right" style="width:50%" value="{{ old('designation', $branch->designation) }}" readonly />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="font-size: 0.9rem">5.</th>
                                        <th style="font-size: 0.9rem">Mobile <span style="color: red;">*</span></th>
                                        <td>
                                            <input type="tel" id="mobile" name="mobile" class="form-control form-control-sm text-right" style="width:50%" value="{{ old('mobile', $branch->mobile) }}" readonly />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="font-size: 0.9rem">6.</th>
                                        <th style="font-size: 0.9rem">IFSC Code <span style="color: red;">*</span></th>
                                        <td>
                                            <input type="text" id="ifsc_code" name="ifsc_code" class="form-control form-control-sm text-right" style="width:50%" value="{{ old('ifsc_code', $branch->ifsc_code) }}" readonly />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="font-size: 0.9rem">7.</th>
                                        <th style="font-size: 0.9rem">Pincode <span style="color: red;">*</span></th>
                                        <td>
                                            <input type="number" id="pincode" name="pincode" class="form-control form-control-sm text-right" style="width:50%" value="{{ old('pincode', $branch->pincode) }}" readonly />
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
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
