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
    <div class="row">
        <div class="col-lg-10 offset-md-1">
            <form action=" {{ route('user.plant.store') }} " id="addForm" method="post"
                enctype="multipart/form-data" class="prevent_multiple_submit">
                @csrf
                <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}">

                <div class="card card-success card-outline mt-5"
                    style="box-shadow: 0 4px 10px 0 rgba(182, 233, 152, 0.474), 0 5px 20px 0 rgba(182, 233, 152, 0.474);">
                    <div class="card-header">
                        <b class="table-heading-sec"> Locations</b>
                    </div>
                    <div class="card-body p-2">
                        <div class="table-responsive rounded col-md-12">
                            <table class="table table-sm table-bordered " id="company_table">
                                <tbody>
                                     <tr class="text-center ">
                                        <th style="width: 50%" class="text-center">
                                            Name of the Company
                                        </th>
                                        <td style="font-size: 1rem" style="width: 50%" class="text-center">
                                            {{ Auth::user()->name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 50%" class="text-center">CIN</th>
                                        <td style="font-size: 1rem" style="width: 50%" class="text-center">{{ Auth::user()->cin_llpin }}</td>
                                    </tr>
                                     <tr>
                                        <th colspan="2" class="text-center ">
                                            <b class="table-heading-sec">Registered Office</b>
                                        </th>
                                    </tr>
                                   <tr class="text-center ">
                                    <th style="width: 50%" class="text-center">
                                        Address
                                    </th>
                                    <td style="font-size: 1rem" style="width: 50%" class="text-center">
                                        <textarea id="reg_off_add" name="reg_address" class="form-control form-control-sm" readonly  placeholder="Registered office address">{{$user->reg_off_add}}</textarea>
                                    </td>
                                </tr>
                                <tr class="text-center ">
                                    <th style="width: 50%" class="text-center">
                                        Pincode
                                    </th>
                                    <td style="font-size: 1rem" style="width: 50%" class="text-center">
                                           <input type="number" min="0" id="reg_off_pin" readonly name="pincode" class="form-control form-control-sm"  placeholder="Pin Code" value="{{$user->reg_off_pin}}">      
                                    </td>
                                </tr>
                                <tr class="text-center ">
                                    <th style="width: 50%" class="text-center">
                                        State
                                    </th>
                                <td style="font-size: 1rem" style="width: 50%" class="text-center">
                                <input type="text" class="form-control form-control-sm select-state"  name="state" id="regAddState"  value="{{$user->reg_off_state}}" readonly>
                                </td>
                                </tr>
                                  <tr class="text-center ">
                                    <th style="width: 50%" class="text-center">
                                        City
                                    </th>
                                    <td style="font-size: 1rem" style="width: 50%" class="text-center">
                                 <input type="text" class="form-control form-control-sm select-state" name="city" id="regAddState"  value="{{$user->reg_off_city}}" readonly>
                                    </td>
                                </tr>



                                <tr>
                                        <th colspan="2" class="text-center ">
                                            <b class="table-heading-sec">Business Office</b>
                                        </th>
                                    </tr>
                                   <tr class="text-center ">
                                    <th style="width: 50%" class="text-center">
                                        Address
                                    </th>
                                    <td style="font-size: 1rem" style="width: 50%" class="text-center">
                                         <textarea id="co_off_add" name="co_off_add" class="form-control form-control-sm"   placeholder="Business office address">{{$user->co_off_add}}</textarea>
                                    </td>
                                </tr>
                                <tr class="text-center ">
                                    <th style="width: 50%" class="text-center">
                                        Pincode
                                    </th>
                                    <td style="font-size: 1rem" style="width: 50%" class="text-center">
                                        <input type="number" min="0" id="co_off_pin" name="co_off_pin"
                                                        class="form-control form-control-sm" placeholder="Pin Code"
                                                        onkeyup="GetCityByPinCode('co',this.value)" value="{{$user->co_off_pin}}">     
                                    </td>
                                </tr>
                                <tr class="text-center ">
                                    <th style="width: 50%" class="text-center">
                                        State
                                    </th>
                                <td style="font-size: 1rem" style="width: 50%" class="text-center">
                                     <input type="text" class="form-control form-control-sm select-state" readonly   name="co_off_state" id="coAddState" value="{{$user->co_off_state}}">
                                </td>
                                </tr>
                                  <tr class="text-center ">
                                    <th style="width: 50%" class="text-center">
                                        City
                                    </th>
                                    <td style="font-size: 1rem" style="width: 50%" class="text-center">
                               <select id="coAddCity" name="co_off_city"
                                                        class="form-control form-control-sm select-city">
                                                        <option value="" selected="selected">{{$user->co_off_city}}</option>
                                                    </select>
                                    </td>
                                </tr>
                                    <tr>
                                        <th colspan="2" class="text-center ">
                                            <input type="checkbox" name="plant_flag" class="form-check-input-lg" id="plant_check"
                                                value="Y">
                                            <span style="font-size: 1rem"><b> Plant Location, If Available </b></span>
                                        </th>
                                    </tr>





                                    <tr class="plant_loc">
                                        <td>
                                            <div class="row">
                                                <div class="form-group col-md-3">
                                                    <label for="plant_off_add"
                                                        class="col-form-label col-form-label-sm">Plant
                                                        Address </label>
                                                    <textarea id="plant_off_add" name="plant[0][plnt_address]" class="form-control form-control-sm"
                                                        placeholder="Plant address"></textarea>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label for="plant_off_pin"
                                                        class="col-form-label col-form-label-sm">Pincode</label>
                                                    <input type="number" min="0" id="plant_off_pin"
                                                        name="plant[0][plnt_pincode]" class="form-control form-control-sm"
                                                        placeholder="Pin Code"
                                                        onkeyup="GetCityByPinCode1('plant',this.value, 0)">
                                                    <span id="pincodeMsg0"
                                                        style="color:red;font-weight:bold;display: none"></span>

                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label for="plant_off_state"
                                                        class="col-form-label col-form-label-sm">State</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm select-state"
                                                        name="plant[0][plnt_state]" id="plantAddState0" readonly>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label for="plant_off_city"
                                                        class="col-form-label col-form-label-sm">City</label>
                                                    <select id="plantAddCity0" name="plant[0][plnt_city]"
                                                        class="form-control form-control-sm select-city">
                                                        <option value="" selected="selected">Please choose..
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        <th style="width: 5% " >
                                            <a class="btn btn-success btn-sm float-right mb-2 Custom-btn" id="addmore" style=" margin-top: 15%;margin-rigth: 40%; ">
                                                <i class="fa fa-plus"></i> Add Row</a>
                                        </th>


                                        </td>
                                    </tr>


                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12 offset-md-2">
                    <div class="col-md-10">
                        <div class="form-group form-actions">
                            <div class="col-lg-9 col-12 text-center">
                                <button type="submit" class="btn btn-primary btn-sm Custom-btn" id="submit"> Save As Draft </button>
                            </div>
                        </div>
                    </div>
                </div>
                    </div>


                </div>

             
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    {{-- {!! JsValidator::formRequest('App\Http\Requests\User\QuestionaireRequest', '#questions') !!} --}}
    @include('partials.js.prevent_multiple_submit')
    @include('partials.user.pincode-js')
    <script>
        $(document).ready(function() {
            var plantcount = 1;
            $('#addmore').click(function() {
                $('#company_table').append(
                    '<tr class="plant_loc"><td colspan=""><div class="row"><div class="form-group col-md-3">' +
                    '<label for="plant_off_add" class="col-form-label col-form-label-sm">Plant Address</label>' +
                    '<textarea id="plant_off_add" name="plant[' + plantcount +
                    '][plnt_address]" class="form-control form-control-sm" placeholder="Plant address"></textarea>' +
                    '</div><div class="form-group col-md-3">' +
                    '<label for="plant_off_pin" class="col-form-label col-form-label-sm">Pincode</label>' +
                    '<input type="number" min="0" id="plant_off_pin" name="plant[' + plantcount +
                    '][plnt_pincode]" class="form-control form-control-sm" placeholder="Pin Code" onkeyup="GetCityByPinCode1(`plant`,this.value,' +
                    plantcount + ')">' +
                    '<span id="pincodeMsg' + plantcount +
                    '" style="color:red;font-weight:bold;display: none"></span></div>' +
                    '<div class="form-group col-md-3"><label for="plant_off_state" class="col-form-label col-form-label-sm">State</label>' +
                    '<input type="text" class="form-control form-control-sm select-state" name="plant[' +
                    plantcount + '][plnt_state]" id="plantAddState' + plantcount + '" readonly></div>' +
                    '<div class="form-group col-md-3"><label for="plant_off_city" class="col-form-label col-form-label-sm">City</label><select id="plantAddCity' +
                    plantcount + '" name="plant[' + plantcount +
                    '][plnt_city]" class="form-control form-control-sm select-city"><option value="" selected="selected">Please choose..</option></select> ' +
                    '</div></div><th><a class="btn btn-danger btn-sm float-right mb-2 remove" style=" margin-top: 15%;margin-right: 40%; "> <i class="far fa-trash-alt"></i></a>' +
                    '</th></td></tr>'
                );

                plantcount++;
            });

            $("#company_table").on('click', '.remove', function() {
                $(this).parent().parent().remove();
            });

            $('.plant_loc').closest('tr').hide();
            $("#plant_check").on('change', function() {
                var plant = $('#plant_check').is(':checked');
                if (plant) {
                    $('.plant_loc').closest('tr').show();
                } else {
                    $('.plant_loc').closest('tr').hide();
                }
            });

        });


        function GetCityByPinCode1(name, pincode, key) {
            console.log(name + pincode + key);
            var state = '#' + name + 'AddState' + key;
            var city = '#' + name + 'AddCity' + key;
            // var district = '#' + name + 'AddDistrict';
            var pinmsg = '#' + 'pincodeMsg' + key;

            if (pincode.length != 6) {
                $(pinmsg).text('Pincode Incorrect!');
                $(pinmsg).show();
                $(state).val('');
                $(city).val('');
                // $(district).val('');
            }
            if (pincode.length == 6 && $.isNumeric(pincode)) {
                $.ajax({
                    url: '/{{ url("pincodes")}}/' + pincode,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        // console.log(data.state.length);
                        if (data.state.length == 0) {
                            // alert('g');
                            $("#pincodeMsg1").text('Pincode Not Found');
                            $("#pincodeMsg1").show();

                        } else {
                            $("#pincodeMsg1").hide();
                            $.each(data.state, function(index, value) {
                                $(state).val(value)
                            });
                            var selOpts = "<option  selected disabled>Please choose..</option>";
                            // var selOpts1 = "<option  selected disabled>Please choose..</option>";

                            $.each(data.city, function(index1, value1) {
                                $(city).val(value1);
                                selOpts += "<option value='" + value1 + "'>" +
                                    value1 +
                                    "</option>";
                            });
                            $(city)
                                .empty()
                                .append(selOpts);

                            // $.each(data.district, function(index2, value2) {
                            //     $(district + count).val(value2);
                            //     selOpts1 += "<option value='" + value2 + "'>" +
                            //         value2 +
                            //         "</option>";
                            // });
                            // $(district + count)
                            //     .empty()
                            //     .append(selOpts1);
                        }

                    }
                });
            };
        }
    </script>
@endpush
