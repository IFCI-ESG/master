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
    <div class="row" >

        <div class="col-lg-8 offset-md-2 mt-5">
            <div class="col-lg-12">
                <h3>Physical Risk </h3>
            </div>
            <div class="card card-success card-outline mt-2 ml-2" style="box-shadow: 0 4px 10px 0 rgba(182, 233, 152, 0.474), 0 5px 20px 0 rgba(182, 233, 152, 0.474);">
                <div class="card-header">
                    <b>Selection of Financial Year </b>
                </div>
                <div class="card border-primary">
                    <div class="card-body p-1 m-2">
                        <div class="row ">
                            <div class="table-responsive rounded col-md-12">
                                <table class="table table-hover table-sm table-striped" id="appTable"
                                    style="width: 100%">
                                    <thead>
                                        <tr class="text-center table-environment">
                                            <th class="">Sr No.</th>
                                            <th class="">Financial Year</th>
                                            <th class="">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($fys->where('status', '1') as $key => $fy)
                                            <tr>
                                                <td class="text-center" style="font-size: 1rem"><b>{{ $key + 1 }}</b></td>
                                                <td class="text-center" style="font-size: 1rem">
                                                    {{ $fy->fy }}
                                                </td>
                                                @if (count($physical_value->where('fy_id',$fy->id)))
                                                    @if ($module_mast->where('fy_id',$fy->id)->first()->status=='S')
                                                        {{-- <td class="text-center">
                                                            <a class="btn Custom-btn-view btn-sm"
                                                                href="{{ route('user.physical.view', ['com_id' => encrypt($user->id),'fy_id'=> encrypt($fy->id)] ) }}"> View</a>
                                                        </td> --}}
                                                    @elseif($module_mast->where('fy_id',$fy->id)->first()->status=='D')
                                                        <td class="text-center">
                                                            <a class="btn Custom-btn-edit btn-sm"
                                                                href="{{ route('user.physical.edit', encrypt($module_mast->where('fy_id',$fy->id)->first()->id) ) }}"> Edit</a>
                                                        </td>
                                                    @endif
                                                @else
                                                    <td class="text-center">
                                                        <a class="btn Custom-btn-create btn-sm"
                                                            href="{{ route('user.physical.create', encrypt($fy->id)) }}"> Create</a>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row pb-2 mt-2 d-flex align-items-center">
                <div class="col-md-2 ml-4">
                    <a href="{{ route('user.risk') }}"
                    class="btn Custom-btn-back btn-sm float-left"> <i
                        class="fas fa-arrow-left"></i> Back </a>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    {{-- {!! JsValidator::formRequest('App\Http\Requests\User\QuestionaireRequest', '#questions') !!} --}}
    @include('partials.js.prevent_multiple_submit')
    <script>
        $(document).ready(function() {

        });
    </script>
@endpush
