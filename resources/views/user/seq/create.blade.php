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
    
        <div class="row justify-content-center">
            <div class="col-md-12">
                <form action="{{ route('user.governance.store') }}" id="gov_store" role="form" method="post"
                    class='form-horizontal prevent_multiple_submit' files=true enctype='multipart/form-data'
                    accept-charset="utf-8">
                    @csrf
                    <input type="hidden" value="{{ $fy_id }}" name="fy_id">
                    <input type="hidden" value="{{ $user->id }}" name="com_id">

                    <div class="card card-success card-outline card-tabs shadow-lg">
                        <div class="card-header p-0 pt-3 border-bottom-0">
                            <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="social-tab" data-toggle="pill" href="#social"
                                        role="tab" aria-controls="social" aria-selected="true"><b >Governance Data For FY-{{$fys->fy}}</b></a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-three-tabContent">
                                <div class="tab-pane fade show active" id="governance" role="tabpanel" aria-labelledby="governance-tab">

                                    <div class="card card-success card-outline">
                                        <div class="card-header">
                                            <b class="table-heading-sec">Board And Stakeholders</b>
                                        </div>
                                        <div class="card-body p-3">
                                            <table class="table table-bordered  table-sm table-striped" id="board-table">
                                                <thead>
                                                    <tr class="text-center table-success">
                                                        <th style="width: 5%" class="text-center">
                                                            Sr. No.
                                                        </th>
                                                        <th style="width: 30%" class="text-center">
                                                            Question
                                                        </th>
                                                        <th style="width: 20%" class="text-center">
                                                            Value
                                                        </th>
                                                        <th style="width: 20%" class="text-center">
                                                            Details
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $a=1;
                                                    @endphp
                                                    @foreach ($quesMast->where('section','First') as $key => $board)
                                                        <tr>
                                                            <td class="text-center">
                                                                {{ $key+1 }}
                                                            </td>
                                                            <td>
                                                                {{$board->question}}
                                                                <input type="hidden" value="{{ $board->id }}" name="board[{{ $a }}][ques_id]">
                                                            </td>
                                                            <td>
                                                                <select class="form-control form-control-sm board" name="board[{{ $a }}][value]" id="val_{{$key}}">
                                                                    <option value="" selected disabled>Please Select</option>
                                                                    <option value="Y">Yes</option>
                                                                    <option value="N">No</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm" name="board[{{ $a }}][details]" id="details_{{$key}}" readonly>
                                                            </td>
                                                        </tr>
                                                        @php
                                                            $a++;
                                                        @endphp
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>




                                </div>
                            </div>
                        </div>


                        <div class="row pb-2 mt-2">
                            <div class="col-md-2 ml-4">
                                <a href="{{ route('user.governance.index') }}"
                                class="btn btn-warning btn-sm float-left Custom-btn"> <i
                                    class="fas fa-arrow-left"></i> Back </a>
                            </div>

                            <div class="col-md-1 offset-md-3">

                                {{-- <a class="btn btn-warning m-2 btn-sm form-control form-control-sm"
                                        href="{{ route('user.print_preview', ['com_id'=>encrypt($user->id), 'fy_id'=>encrypt($fy_id)]) }}">
                                        Print Preview</a> --}}
                                {{-- @if(!$busi_value->isEmpty()) --}}
                                    <button type="submit" id="submit" class="btn btn-primary btn-sm form-control form-control-sm Custom-btn"><i
                                            class="fas fa-save"></i>
                                        Submit</button>
                                {{-- @endif --}}
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\User\GovernanceRequest', '#gov_store') !!}
    @include('partials.js.prevent_multiple_submit')
    <script>
        $(document).ready(function() {

            $(document).delegate(".board", "change", function() {
                var Id = $(this).attr('id');
                Id = Id.substring(4);
                var value_dropdown = $('#details_' + Id);

                if (this.value === "Y") {
                    value_dropdown.removeAttr('readonly');
                } else {
                    value_dropdown.attr('readonly', 'readonly');
                    value_dropdown.val('');
                }
            });

        });

        function Total(e) {
            var classNames = $(e).attr('class');
            classNames = classNames.substring(29);
            className = classNames.replace(' is-valid', '');
            // alert('d');
            var sum = 0;
            $('.' + className).each(function() {
                var value = parseFloat($(this).val()) || 0;  // Convert the value to a number or default to 0
                sum += value;
            });
            $('#tot_' + className).val(sum.toFixed(2));

             // Retrieve total complaints and pending complaints
            var tot_complaints = parseFloat($('#tot_complaints').val()) || 0;
            var tot_pending_complaints = parseFloat($('#tot_pending_complaints').val()) || 0;

            // Calculate percentage and handle division by zero
            var percentage = tot_complaints > 0 ? (tot_pending_complaints / tot_complaints) * 100 : 0;

            // Set the percentage value in the respective field
            $('#complaints_percentage').val(percentage.toFixed(2));
        }


    </script>
@endpush

