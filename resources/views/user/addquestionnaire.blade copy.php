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
    <div class="container  py-4 px-2 col-lg-12">
        <div class="row justify-content-center">
            <div class="col-md-12">
                {{-- <form action="{{ route('user.questionnaire.store') }}" id="questions" role="form" method="post"
                    class='form-horizontal prevent_multiple_submit' files=true enctype='multipart/form-data'
                    accept-charset="utf-8">
                    @csrf --}}

                    <div class="card card-success card-outline card-tabs shadow-lg">
                        <div class="card-header p-0 pt-3 border-bottom-0">
                            <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="environment-tab" data-toggle="pill" href="#environment"
                                        role="tab" aria-controls="environment" aria-selected="true">Environment</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-three-tabContent">
                                <div class="tab-pane fade show active" id="environment" role="tabpanel"
                                    aria-labelledby="environment-tab">
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
                                                        <th style="width: 10%" class="text-center">
                                                            Unit
                                                        </th>
                                                        {{-- <th style="width: 20%" class="text-center">
                                                            Type
                                                        </th>
                                                        <th style="width: 20%" class="text-center">
                                                            Value
                                                        </th> --}}
                                                        <th style="width: 20%" class="text-center">
                                                            Action
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($ques_mast->where('status','1') as $key => $ques)
                                                        <tr>
                                                            <td class="text-center">
                                                                {{ $key+1 }}
                                                            </td>
                                                            <td style="font-size: 1rem;" >
                                                                {{$ques->particular}}
                                                                <input type="hidden" value="{{ $ques->id }}" name="part[{{ $key }}][ques_id]">
                                                            </td>
                                                            <td class="text-center">
                                                                {{$ques->unit}}
                                                            </td>
                                                            {{-- <td>
                                                                @if($ques->unit=='TPA')
                                                                    <select class="form-control form-control-sm" name="part[{{ $key }}][value]" id="">
                                                                        <option value="" disabled selected>Select</option>
                                                                        @foreach ($drop_mast->where('ques_id',$ques->id)->whereIn('sector_id',[1,$user->sector_id]) as $drop)
                                                                            <option value="N">{{$drop->particular}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                @endif
                                                            </td> --}}
                                                            {{-- <td>
                                                                <input type="text"  class="form-control form-control-sm" value="{{ $ques->id }}" name="part[{{ $key }}][ques_id]">
                                                            </td> --}}
                                                            {{-- @if($ques->unit=='TPA') --}}
                                                                @if ($ques_value->where('ques_id', $ques->id)->where('fy_id',$fy_id)->isNotEmpty())
                                                                    <td class="text-center test1" data_part_id={{ $ques->id }} data_comp_id="{{$user->id}}" ques_data="{{ $ques->particular }}" data_unit="{{$ques->unit}}" data_fy_id="{{$fy_id}}">
                                                                        {{-- <td class="text-center test1" data_view_part_id={{  $eligible->eligible_pro_id }} data_claim_id={{$claimMast->id}} data_app_id={{$appMast->id}} section_data="{{ $eligible->product_name }}"> --}}
                                                                        <a class="btn btn-warning btn-sm ShowRow"
                                                                            data-toggle="modal" data-target="#ViewModalCenter">
                                                                            <i class="fa fa-eye"></i>View</a>
                                                                    </td>
                                                                @else
                                                                    <td class="text-center test" data_part_id={{ $ques->id }} ques_data="{{ $ques->particular }}" data_unit="{{$ques->unit}}">
                                                                        <a class="btn btn-primary btn-sm ShowRow"
                                                                            data-toggle="modal" data-target="#exampleModalCenter">
                                                                            <i class="fa fa-plus"></i>&nbsp;&nbsp; Add</a>
                                                                    </td>
                                                                @endif
                                                            {{-- @endif --}}
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('user.fy') }}"
                                class="btn btn-warning btn-sm float-left col-1 ml-2"> <i
                                    class="fas fa-arrow-left"></i> Back </a>
                        </div>
                        <!-- /.card -->
                    </div>
                {{-- </form> --}}
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\User\QuestionaireRequest', '#questions') !!}
    @include('partials.js.prevent_multiple_submit')
    @include('partials.user.environment.popup')
    @include('partials.user.environment.popup_view')
    <script>
        function resetSerialNumbers() {
            var table = document.getElementById('popupTable');
            var rows = table.getElementsByTagName('tr');
            // var rows=rows.length-1

            // Start from 1 because rows[0] is the header row
            for (let i = 0; i < rows.length; i++) {
                rows[i].cells[0].textContent = i+1; // Reset the serial number
            }
        }

        $(document).ready(function() {

            // js for popup

            var count = 1;
            var sr = 2;


            $('#addRawFeild').click(function() {
                //var id=$(this).attr("data_part_id");
                // var idcount=AddRowCount-2;
                // var fileName = $('#remark'+idcount),
                // fileType = $('#claim_doc'+idcount);

                //     if(fileName.val() === ''|| fileType.val() === '' ){
                // return false;
                //     }
                // Secdata = $(this).attr('sector_data');
                // alert($(".part_name").val($(this).attr('ques_data')));

                $("#popupTable").append(
                    '<tr id="row' + sr + '"><td class="text-center"></td>' +
                    // '<tr id="row' + sr + '"><td class="text-center">' + sr + '</td>' +
                    '<td>' + '<div style="border:none; font-weight: bold;" id="part_name" class="form-control form-control-sm part_name"></div>'+

                    '</td>' + '<td>' +
                        '<select class="form-control form-control-sm select" name="ques[' + count +'][type]" id="">'+
                            '<option value="" disabled selected>Select</option>'+
                            '@foreach ($drop_mast as $drop)'+
                                '<option class="drop Ab_{{$drop->ques_id}}" value="{{$drop->id}}">{{$drop->particular}}</option>'+
                            '@endforeach'+
                        '</select>' +
                    '</td>' + '<td>' + '<input type="number" id="txtup' + sr + '" name="ques[' + count +
                    '][value]"  class="form-control form-control-sm">' +
                    '</td>'+
                    '<td>' +'<button type="button" class="form-control btn-sm btn btn-danger pop_remove" id="' +
                    sr +
                    '"><i class="far fa-trash-alt"></i></button>' +
                    '</td>' + '</tr>'
                );
                count++;
                sr++;

                $(".part_name").html(Quesdata);
                $(".drop").hide();
                $(".Ab_"+a).show();
                if(unit!='TPA')
                {
                    $(".select").prop("disabled", true);
                }
                else
                {
                    $(".select").prop("disabled", false);
                }
                resetSerialNumbers();
            });
            $(document).on('click', '.pop_remove', function() {
                var button_id = $(this).attr("id");
                $('#row' + button_id + '').remove();
                resetSerialNumbers();
                // alert(sr);
                // sr=sr-1;
            });

            // for dynamic pop
            var Quesdata;
            var a;
            var unit;
            $("#env-table").on('click', '.test', function() {
                var table = document.getElementById('popupTable');
                while (table.rows.length > 1) {
                    table.deleteRow(1);
                }
                // resetSerialNumbers();
                a = $(this).attr('data_part_id');
                $(".drop").hide();
                $(".Ab_"+a).show();
                Quesdata = $(this).attr('ques_data');
                unit = $(this).attr('data_unit');
                if(unit!='TPA')
                {
                    $(".select").prop("disabled", true);
                    $("#addRawFeild").hide();
                }
                else
                {
                    $(".select").prop("disabled", false);
                    // var $select = $('#selectDrop');
                    // $select.empty();
                    $("#addRawFeild").show();
                }

                $("#ques_id").val($(this).attr('data_part_id'));
                $(".part_name").html(Quesdata);
            });


            // for dynamic pop_view
            var Quesdata;
            $("#env-table").on('click', '.test1', function() {
                a = $(this).attr('data_part_id');
                b = $(this).attr('data_fy_id');
                // $(".drop").hide();
                // $(".Ab_"+a).show();
                Quesdata = $(this).attr('ques_data');
                unit = $(this).attr('data_unit');
            // var a = $(this).attr('data_view_part_id');
            // var b = $(this).attr('data_claim_id');
            // var c = $(this).attr('data_app_id');
            // Quesdata = $(this).attr('ques_data');
            // $(".part_name_view").val($(this).attr('section_data'));
            // alert(Secdata);
            // console.log('claim/hsn_view/' + a + '/' + b + '/' + c);

            jQuery.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: '../edit/questionnaire/' + a + '/' + b ,
                // dataType: "json",
                success: function(data) {
                    // console.log(data);
                    if (data.id.length == 0) {
                        $(".quesview").append(
                        '<tr><td colspan=8>No Data Available</td></tr>');
                    } else if (data) {
                        // console.log(data);
                        tableContent = data;
                        var index = 0;
                        var tablelength = data.id.length;
                        // alert(tablelength);
                        BindMrrReportData(index, tablelength, a, b);
                    }

                    function BindMrrReportData(index, tablelength, a, b) {
                        // alert(typeof(a));
                        // alert(typeof(b));
                        // alert(Quesdata);
                        var tr = "";
                        $(".quesview").empty();

                        serial = tablelength + 1;
                        counter = tablelength ;

                        for (let i = index; i < tablelength; i++) {
                            sno = i + 1;
                            var value = tableContent.value[i];
                            var row_id = tableContent.id[i];
                            var type_id = tableContent.type_id[i];
                            // alert({{$drop->id}});
                            tr += "<tr>";
                            tr += "<td class='text-center' >" + sno + "</td>";
                            // tr += "<td>" + tableContent.remarks[i] + "</td>";
                            tr += "<td>" + '<input type="hidden" name="ques[' + i +'][row_id]" value="' +
                                row_id + '" class="form-control form-control-sm">'+
                                '<div style="border:none; font-weight: bold;" id="part_name"'+
                                                    'class="form-control form-control-sm part_name"></div>' + "</td>";
                            tr += "<td class='text-center'>" +
                                    '<select class="form-control form-control-sm select_view" name="ques[' + count +'][type]" id="">'+
                                        // '<option value="" disabled selected>Select</option>'+
                                        '@foreach ($drop_mast as $drop)'+
                                            '<option class="drop_view Ab_view_{{$drop->ques_id}}" {{ ($drop->id == ' + type_id +') ? "selected" : "" }} value="{{$drop->id}}">{{$drop->particular}}</option>'+
                                        '@endforeach'+
                                    '</select>' +
                                "</td>";
                            tr += "<td class='text-center'>" + '<input type="number" value="' + value +'"'+
                                                    'class="form-control form-control-sm" name="ques[' + count +'][value]">' + "</td>";
                            tr += "<td class='text-center'>" + '<a class=" mt-2 btn-sm btn-danger" onclick="delButtonId(' +
                                row_id + ')"><i class="far fa-trash-alt"></i></a>' + "</td>";
                            tr += "</tr>";

                        }
                        $(".quesview").empty();
                        $("#ques_id").val(a);
                        // $("#section_data_view").val(b);
                        $(".quesview").append(tr);
                        $(".part_name").html(Quesdata);
                        $(".drop_view").hide();
                        $(".Ab_view_"+a).show();
                        if(unit!='TPA')
                        {
                            $(".select_view").prop("disabled", true);
                            $("#addRawFeild_view").hide();
                        }
                        else
                        {
                            $(".select_view").prop("disabled", false);
                            $("#addRawFeild_view").show();
                        }
                    }

                }
            });
                $("#ques_id_view").val(a);
            });

            // js for popup_view

            $('#addRawFeild_view').click(function() {
                //var id=$(this).attr("data_ca_id");
                // var idcount=AddRowCount-2;
                // var fileName = $('#file_name'+idcount),
                // fileType = $('#file_type'+idcount);

                //     if(fileName.val() === ''|| fileType.val() === '' ){
                // return false;
                //     }
                $("#popupTable_view").append(
                    '<tr id="row_view' + serial + '"><td class="text-center">' + serial + '</td>' +
                    // '<tr id="row' + sr + '"><td class="text-center">' + sr + '</td>' +
                    '<td>' + '<div style="border:none; font-weight: bold;" id="part_name" class="form-control form-control-sm part_name"></div>'+

                    '</td>' + '<td>' +
                        '<select class="form-control form-control-sm select" name="ques[' + counter +'][type]" id="">'+
                            '<option value="" disabled selected>Select</option>'+
                            '@foreach ($drop_mast as $drop)'+
                                '<option class="drop Ab_{{$drop->ques_id}}" value="{{$drop->id}}">{{$drop->particular}}</option>'+
                            '@endforeach'+
                        '</select>' +
                    '</td>' + '<td>' + '<input type="number" id="txtup' + serial + '" name="ques[' + counter +
                    '][value]"  class="form-control form-control-sm">' +
                    '</td>'+
                    '<td>' +'<button type="button" class="form-control btn-sm btn btn-danger pop_remove_view" id="' +
                        serial +
                    '"><i class="far fa-trash-alt"></i></button>' +
                    '</td>' + '</tr>'
                    // '<tr id="row_view' + serial + '"><td class="text-center">' + serial + '</td>' +
                    // '<td>' + '<input type="text"  id="Fileup' + serial + '" value="'+Secdata+'" name="part_name'+counter+'" '+
                    //     'class="form-control form-control-sm part_name_view" disabled>' +
                    // '</td>' + '<td>' + '<input type="number" id="txtup' + serial + '" name="hsn[' +
                    // counter +
                    // '][hsn_no]" class="form-control form-control-sm">' +
                    // '</td>' + '<td>' + '<button class="btn-danger pop_remove_view" id="' + serial +
                    // '"><i class="far fa-trash-alt"></i></button>' +
                    // '</td>' + '</tr>'
                );
                // $(".part_name").val(Secdata);
                counter++;
                serial++;
                $(".part_name").html(Quesdata);
                $(".drop").hide();
                $(".Ab_"+a).show();
            });
            $(document).on('click', '.pop_remove_view', function() {
                var button_id = $(this).attr("id");
                $('#row_view' + button_id + '').remove();
                resetSerialNumbers();
            });

        });

        function delButtonId(id) {
            swal({
                    title: "Do You Want to Delete this Document",
                    icon: "warning",
                    buttons: {
                        cancel: true,
                        confirm: {
                            text: "Yes",
                            value: "Y",
                        },
                    },
                    dangerMode: true,
                    closeOnClickOutside: false,
                })
                .then((result) => {
                    if (result == 'Y') {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "GET",
                            url: '../ques_delete/' + id ,
                            success: function(data) {
                                console.log(data);
                                if (data == true) {
                                    swal(
                                        'Deleted!',
                                        'Your file has been deleted.',
                                        'success')
                                    window.location.reload();
                                } else {
                                    swal(
                                        'Not Deleted!',
                                        'Your file has been Not Deleted.',
                                        'warning')

                                }
                            }
                        })
                    }
                });
        }
    </script>
@endpush
