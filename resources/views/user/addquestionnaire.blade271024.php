@extends('layouts.user.dashboard-master')
@section('title')
    Add Questionnaire
@endsection
@push('styles')
    {{-- <link href="{{ asset('css/app/application.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('css/app/progress.css') }}" rel="stylesheet">
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
    <div class="row" >
        <div class="col-lg-8 offset-md-2">
            <form action="{{ route('user.activity.store') }}" id="activity" role="form" method="post"
                class='form-horizontal busi_prevent_multiple_submit' files=true enctype='multipart/form-data'
                accept-charset="utf-8">
                @csrf
                <input type="hidden" name="fy_id" value="{{ $fy_id }}" >
                <input type="hidden" name="com_id" value="{{$user->id}}">
                <div class="card card-success card-outline mt-5 ml-2" style="box-shadow: 0 4px 10px 0 rgba(182, 233, 152, 0.474), 0 5px 20px 0 rgba(182, 233, 152, 0.474);">
                    <div class="card-header">
                        <b>Business Activity </b>
                    </div>
                    <div class="card border-primary">
                        <div class="card-body p-1 m-2">
                            <div class="row ">
                                <div class="table-responsive rounded col-md-12">
                                    <table class="table table-bordered table-hover table-sm table-striped" id="appTable"
                                        style="width: 100%">
                                        <thead>
                                            <tr class="text-center">
                                                <th class="">Sr No.</th>
                                                <th class="">Activity</th>
                                                <th class="">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($busi_value->isEmpty())
                                                @foreach ($busi_mast->where('status', '1') as $key => $busi)
                                                    <tr>
                                                        <td class="text-center" style="font-size: 1rem"><b>{{ $key + 1 }}</b></td>
                                                        <td style="font-size: 1rem">
                                                            {{ $busi->acitvity }}
                                                            <input type="hidden" name="business[{{$key}}][part_id]" value="{{$busi->id}}">
                                                        </td>
                                                        <td class="text-center">
                                                            <label>
                                                                <input type="checkbox" class="business_check" name="business[{{$key}}][check]">
                                                            </label>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                @foreach ($busi_value as $key => $busi)
                                                    <tr>
                                                        <td class="text-center" style="font-size: 1rem"><b>{{ $key + 1 }}</b></td>
                                                        <td style="font-size: 1rem">
                                                            {{ $busi->acitvity }}
                                                            {{-- <input type="hidden" name="business[{{$key}}][row_id]" value="{{$busi->id}}"> --}}
                                                        </td>
                                                        <td class="text-center">
                                                            <label>
                                                                <input type="checkbox" name="business[{{$key}}][check]" disabled  @if($busi->is_checked) checked @endif>
                                                            </label>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if($busi_value->isEmpty())
                    <div class="row pb-2 mt-2">
                        <div class="col-md-2 offset-md-5">
                            <button type="submit" id="busi_submit" class="btn btn-primary btn-sm form-control form-control-sm"><i
                                class="fas fa-save"></i>
                            Submit</button>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>
    <div class="container  py-4 px-2 col-lg-8">
        <div class="row justify-content-center">
            <div class="col-md-12">
                {{-- <form action="{{ route('user.data.store') }}" id="questions" role="form" method="post"
                    class='form-horizontal prevent_multiple_submit' files=true enctype='multipart/form-data'
                    accept-charset="utf-8">
                    @csrf --}}

                    <div class="card card-success card-outline card-tabs shadow-lg">
                        <div class="card-header p-0 pt-3 border-bottom-0">
                            <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="environment-tab" data-toggle="pill" href="#environment"
                                        role="tab" aria-controls="environment" aria-selected="true"><b>{{$sector->name}} Sector Environment Data For FY-{{$fys->fy}}</b></a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-three-tabContent">
                                <div class="tab-pane fade show active" id="environment" role="tabpanel"  aria-labelledby="environment-tab">
                                    <div class="card">
                                        <div class="card-body p-3">
                                            @php
                                                if ($seg_tot > 0) {
                                                    $percentage = ($ques_tot / $seg_tot) * 100;
                                                }
                                                else{
                                                    $percentage = 0;
                                                }
                                            @endphp
                                            <h5 class="text-center">Progress</h5> <br>
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="{{$percentage}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$percentage}}%">{{ number_format($percentage, 2) }}%</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-body p-3">
                                            <table class="table table-striped projects" id="env-table">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 10%" class="text-center">
                                                            Sr. No.
                                                        </th>
                                                        <th style="width: 15%" class="text-center">
                                                            Particulars
                                                        </th>
                                                        <th style="width: 20%" class="text-center">
                                                            Scope
                                                        </th>
                                                        <th style="width: 20%" class="text-center">
                                                            Action
                                                        </th>
                                                        <th style="width: 20%" class="text-center">
                                                            Data Quality
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- {{dd($seg_mast)}} --}}
                                                    @foreach ($seg_mast->where('status','1') as $key => $seg)
                                                        <tr>
                                                            <td class="text-center">
                                                                {{ $key+1 }}
                                                            </td>
                                                            <td style="font-size: 1rem;" >
                                                                {{$seg->header_name}}
                                                                <input type="hidden" value="{{ $seg->id }}" name="part[{{ $key }}][seg_id]">
                                                            </td>
                                                            <td class="text-center" style="font-size: 1rem;" >
                                                                {{$seg->scope_name}}
                                                            </td>
                                                            @if(!$busi_value->isEmpty())
                                                                @if ($ques_value->where('segment_id', $seg->id)->where('fy_id',$fy_id)->isNotEmpty())
                                                                    <td class="text-center test1" data_seg_id={{ $seg->id }} data_comp_id="{{$user->id}}" ques_data="{{ $seg->header_name }}" data_fy_id="{{$fy_id}}" >
                                                                        <a class="btn btn-warning btn-sm ShowRow"
                                                                            data-toggle="modal" data-target="#ViewModalCenter">
                                                                            <i class="fa fa-eye"></i>View</a>
                                                                    </td>
                                                                @else
                                                                    <td class="text-center test" data_seg_id={{ $seg->id }} ques_data="{{ $seg->header_name }}" data_sector_id="{{Auth::user()->sector_id}}">
                                                                        <a class="btn btn-primary btn-sm ShowRow"
                                                                            data-toggle="modal" data-target="#exampleModalCenter">
                                                                            <i class="fa fa-plus"></i>&nbsp;&nbsp; Add</a>
                                                                    </td>
                                                                @endif
                                                                <td>
                                                                    <select name="quality" id="quality" class="form-control form-control-sm">
                                                                        <option value="" disabled selected>Quality</option>
                                                                        @foreach ($data_quality as $data)
                                                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                                                                        @endforeach
                                                                    </select>
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


                        {{-- <form action="{{ route('user.questionnaire.submit') }}" id="" role="form" method="post"
                                class='form-horizontal prevent_multiple_submit' files=true enctype='multipart/form-data'
                                accept-charset="utf-8">
                            @csrf --}}

                            <div class="row pb-2 mt-2">
                                <div class="col-md-2 ml-4">
                                    <a href="{{ route('user.fy') }}"
                                    class="btn btn-warning btn-sm float-left"> <i
                                        class="fas fa-arrow-left"></i> Back </a>
                                </div>

                                <div class="col-md-2 offset-md-3">
                                    <input type="hidden" value="{{ $fy_id }}" name="fy_id">

                                    <a class="btn btn-warning m-2 btn-sm form-control form-control-sm"
                                            href="{{ route('user.print_preview', ['com_id'=>encrypt($user->id), 'fy_id'=>encrypt($fy_id)]) }}">
                                            Print Preview</a>
                                    {{-- @if(!$busi_value->isEmpty())
                                        <button type="submit" id="final_submit" class="btn btn-primary btn-sm form-control form-control-sm"><i
                                                class="fas fa-save"></i>
                                            Submit</button>
                                    @endif --}}
                                </div>
                            </div>
                        {{-- </form> --}}
                        <!-- /.card -->
                    </div>
                {{-- </form> --}}
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\User\QuestionaireRequest', '#user_store') !!}
    {!! JsValidator::formRequest('App\Http\Requests\User\QuestionaireRequest', '#user_update') !!}
    {{-- {!! JsValidator::formRequest('App\Http\Requests\User\DataRequest', '#data_store') !!} --}}
    {{-- @include('partials.js.prevent_multiple_submit') --}}
    @include('partials.user.environment.popup')
    @include('partials.user.environment.popup_view')
    <script>
        $(document).ready(function() {

            $('#activity').on('submit', function(e) {
                // Check if at least one checkbox is checked
                // alert($('.business_check:checked').length);
                if ($('.business_check:checked').length === 0) {
                    alert('Please check at least one checkbox.');
                    e.preventDefault(); // Prevent form submission
                }

                var confirmation = confirm('You cannot revert the changes. Do you want to continue?');
                if (!confirmation) {
                    event.preventDefault();
                }
            });


            $('#user_store').on('submit', function(e) {
                // Check if at least one checkbox is checked
                // alert($('input[type="checkbox"]:checked').length);
                if ($('.first:checked').length === 0) {
                    alert('Please check at least one checkbox.');
                    e.preventDefault(); // Prevent form submission
                }
            });

            $('#user_update').on('submit', function(e) {
                // Check if at least one checkbox is checked
                // alert($('input[type="checkbox"]:checked').length);
                if ($('.second:checked').length === 0) {
                    alert('Please check at least one checkbox.');
                    e.preventDefault(); // Prevent form submission
                }
            });


            const busiBtn = document.getElementById("busi_submit");

            $('.busi_prevent_multiple_submit').on('submit', function() {
                if ($('.busi_msg').length === 0) {
                    $( ".busi_prevent_multiple_submit" ).parent().append('<div class="offset-md-4 busi_msg"><span class="text-danger text-sm text-center">Please wait while your request is being processed. &nbsp&nbsp&nbsp<i class="fa fa-spinner fa-spin" style="font-size:24px;color:black"></i></span></div>');
                }
                busiBtn.disabled = true;
                setTimeout(function(){busiBtn.disabled = false;}, (1000*20));
                setTimeout(function(){$( ".busi_msg" ).hide()}, (1000*20));
            });



            const popBtn = document.getElementById("pop_submit");
            const popClose = document.getElementById("pop_close");

            $('.pop_prevent_multiple_submit').on('submit', function() {
                if ($('.pop_msg').length === 0) {
                    $(popClose).parent().after('<div class="offset-md-4 pop_msg"><span class="text-danger text-sm text-center">Please wait while your request is being processed. &nbsp&nbsp&nbsp<i class="fa fa-spinner fa-spin" style="font-size:24px;color:black"></i></span></div>');
                }
                popBtn.disabled = true;
                setTimeout(function(){popBtn.disabled = false;}, (1000*20));
                setTimeout(function(){$( ".pop_msg" ).hide()}, (1000*20));
            });



            const popviewBtn = document.getElementById("popview_submit");
            const popViewClose = document.getElementById("pop_view_close");

            $('.pop_view_prevent_multiple_submit').on('submit', function() {
                if ($('.pop_view_msg').length === 0) {
                    $(popViewClose).parent().after('<div class="offset-md-4 pop_view_msg"><span class="text-danger text-sm text-center">Please wait while your request is being processed. &nbsp&nbsp&nbsp<i class="fa fa-spinner fa-spin" style="font-size:24px;color:black"></i></span></div>');
                }
                popviewBtn.disabled = true;
                setTimeout(function(){popviewBtn.disabled = false;}, (1000*20));
                setTimeout(function(){$( ".pop_view_msg" ).hide()}, (1000*20));
            });



            // for dynamic pop
            var Quesdata;
            var a;
            var unit;
            $("#env-table").on('click', '.test', function() {

                segId = $(this).attr('data_seg_id');
                sectId = $(this).attr('data_sector_id');
                // alert(segId);
                // var button = $(event.relatedTarget); // Button that triggered the modal
                // var segId = button.data('ques-id'); // Extract ques_id from data-* attributes

                // Clear previous content
                $('#popupTable').empty();
                var row = '';
                // Perform AJAX request to get subques_mast data
                $.ajax({
                    url: '../get_ques_data/'+ sectId + '/' + segId,  // Adjust the route as needed
                    type: 'GET',
                    // data: { ques_id: quesId },
                    success: function(response) {
                        // console.log(response);
                        var subQuesData = response.data;

                        // Populate the modal content
                        subQuesData.forEach(function(drop, key) {
                            $('#popupTable').append('<tr>' +
                                        '<td>' +
                                            '<label class="" style=" margin-left: 50px;" data-toggle="tooltip" data-placement="right" title="' + drop.descrption + '">' +
                                                '<input type="checkbox" class="first" id="check_' + key + '" name="ques[' + key + '][check]">&nbsp;&nbsp;' + drop.particular +
                                            '</label>' +
                                            '<input type="hidden" name="ques[' + key + '][ques_id]" value="'+drop.id+'">'+
                                        '</td>' +
                                        '<td>' +
                                            '<input type="number" class="form-control form-control-sm text-right" min="0" name="ques[' + key + '][value]" id="text_' + key + '"  disabled step=".01">' +
                                        '</td>' +
                                        '<td class="text-center" style="width: 20%">' + drop.unit + '</td>' +
                                        '<td class="text-center" style="width: 20%">' + drop.data_source + '</td>' +
                                    '</tr>');
                        });
                        // $('#popupTable').append(row);

                        $('[data-toggle="tooltip"]').tooltip();
                    },

                    error: function(xhr) {
                        console.error(xhr.responseText); // Handle error
                    }
                });

                Quesdata = $(this).attr('ques_data');
                unit = $(this).attr('data_unit');

                $("#seg_id").val($(this).attr('data_seg_id'));
                $(".part_name").html(Quesdata);
                $(".unit").html(unit);

            });



            $("#popupTable").delegate(".first", "change", function() {
            // $('.first').on('change', function() {
                var Id = $(this).attr('id');
                Id = Id.substring(6);
                if ($(this).is(':checked')) {
                    $('#text_'+Id).removeAttr('disabled'); // Enable the text input
                } else {
                    $('#text_'+Id).attr('disabled', 'disabled'); // Disable the text input
                    $('#text_' + Id).val('');
                }
            });


            // for dynamic pop_view
            // var Quesdata;

            $("#env-table").on('click', '.test1', function() {
                segId = $(this).attr('data_seg_id');
                fy_id = $(this).attr('data_fy_id');
                Quesdata = $(this).attr('ques_data');
                unit = $(this).attr('data_unit');


                $('#popupTable_view').empty();

                $.ajax({
                    url: '../get_ques_data_view/'+ segId + '/' + fy_id,  // Adjust the route as needed
                    type: 'GET',
                    // data: { ques_id: segId },
                    success: function(response) {
                        // console.log(response);
                        var subQuesData = response.data;

                        // Populate the modal content
                        subQuesData.forEach(function(drop, key) {

                            var isChecked = drop.is_checked ? 'checked' : ''; // Assuming response includes isChecked property
                            var isDisabled = drop.is_checked ? '' : 'disabled'; // Enable if checked, else disable
                            var formattedValue = parseFloat(drop.value).toFixed(2);

                            $('#popupTable_view').append('<tr>' +
                                '<td>' +
                                    '<input type="hidden" name="ques[' + key + '][row_id]" value="'+drop.id+'">'+
                                    '<label class="" style=" margin-left: 50px;" data-toggle="tooltip" data-placement="right" title="' + drop.descrption + '">' +
                                        '<input type="checkbox" class="second" id="checkview_' + key + '" name="ques[' + key + '][check]" value="1"' + isChecked + '>&nbsp;&nbsp;' + drop.particular +
                                    '</label>' +
                                '</td>' +
                                '<td>' +
                                    '<input type="number" class="form-control form-control-sm text-right" name="ques[' + key + '][value]" id="textview_' + key + '" ' + isDisabled + ' value="' + formattedValue + '" step=".01">' +
                                '</td>' +
                                '<td class="text-center">' + drop.unit + '</td>' +
                                '<td class="text-center">' + drop.data_source + '</td>' +
                                '</tr>');
                            // $('#popupTable_view').append(row);
                        });

                        $('[data-toggle="tooltip"]').tooltip();

                    },
                    error: function(xhr) {
                        console.error(xhr.responseText); // Handle error
                    }
                });
                // $("#ques_id_view").val(a);
                Quesdata = $(this).attr('ques_data');
                unit = $(this).attr('data_unit');

                $("#head_id").val($(this).attr('data_head_id'));
                $(".part_name").html(Quesdata);
                $(".unit").html(unit);
            });

            $("#popupTable_view").delegate(".second", "change", function() {
                // $('.first').on('change', function() {
                var Id = $(this).attr('id');
                Id = Id.substring(10);
                if ($(this).is(':checked')) {
                    $('#textview_'+Id).removeAttr('disabled'); // Enable the text input
                } else {
                    $('#textview_'+Id).attr('disabled', 'disabled'); // Disable the text input
                    $('#textview_' + Id).val('');
                }
            });

        });

    </script>
@endpush






