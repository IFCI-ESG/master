@extends('layouts.user.dashboard-master')
@section('title')
    Financial Year
@endsection
@push('styles')
    <link href="{{ asset('css/app/application.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app/progress.css') }}" rel="stylesheet">
    <style>
        input[type="file"] {
            padding: 1px;
        }
    </style>
@endpush
@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.
            <br>
            <br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- ContentStarts --}}

    <div class="row" >
        <div class="col-lg-8 offset-md-2">
            <div class="card card-success card-outline mt-5 ml-2" style="box-shadow: 0 4px 10px 0 rgba(182, 233, 152, 0.474), 0 5px 20px 0 rgba(182, 233, 152, 0.474);">
                <div class="card-header">
                    <b class="table-heading-sec">Bank Details </b>
                </div>
                <div class="card border-primary">
                    <div class="card-body p-1 m-2">
                        <div class="row ">
                            <div class="table-responsive rounded col-md-12">
                                <table class="table table-bordered table-hover table-sm table-striped" id="appTable"
                                    style="width: 100%">
                                    <thead>
                                        <tr class="text-center table-success">
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
                                                    <a class="btn btn-primary btn-sm Custom-btn" href="{{ route('user.fy',['bank_id' => encrypt($bank->bank_id),'class_type'=> encrypt($bank->class_type_id)])}}"> Create</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row pb-2 mt-2 d-flex align-items-center">
                <div class="col-md-12 ml-4">
                    <a href="{{ route('user.environment') }}"
                    class="btn btn-warning btn-sm Custom-btn"> <i
                        class="fas fa-arrow-left Custom-btn"></i> Back </a>
                </div>
            </div>
            </div>
     
        </div>
    </div>
@endsection
@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\User\QuestionaireRequest', '#questions') !!}
    @include('partials.js.prevent_multiple_submit')
    <script>
        $(document).ready(function() {

        });
    </script>
@endpush
