@extends('layouts.user.dashboard-master')

@section('content')

<style>
    .shadow-div {
 /*       width: 300px;
        height: 200px;*/
        background-color: #f0f0f0;
        margin: 50px;
        padding: 20px;
        box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.3);
        border-radius: 10px;
        transition: box-shadow 0.3s ease; /* Smooth transition */
    }

    .shadow-div:hover {
        box-shadow:10px 10px 25px rgb(7 87 25 / 35%);; /* Shadow on hover */
    }
</style>
<div class="container">
         <div class="content">
          <div class="container-fluid pt-2">
                <div class="user_home">
                    <div class="container " >
                        <div class="row justify-content-center">
                            <div class="col-lg-10 col-md-10">
                                <div class="  shadow-div">
                                    <div class="row align-items-center ">
                                        <div class="col-sm-4 bg-c-lite-green user-profile">
                                            <div class="card-block text-center text-white">
                                                <div class="user-profile-sec">
                                                    <img src="{{asset('images/company_image.png')}}" class="img-radius" alt="User-Profile-Image">
                                                </div>
{{dd($users)}}
                                                <h5>{{$users->name}}</h5>
                                                <h4>{{$users->pan}}</h4>
                                                <p>CIN- {{$users->cin_llpin}}</p>
                                            </div>
                                        </div>

                           <div class="col-sm-8">
                                            <div class="card-block">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <h4>Sector</h4>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <h3>{{$users->sector_name}}</h3>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <h4>Bank Zone</h4>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <h3>{{$users->zone}}</h3>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <h4>Segment</h4>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <h3>{{$users->com_type}}</h3>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <h4>Authorized Signatory</h4>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <h3>{{$users->contact_person}}</h3>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <h4>Designation</h4>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <h3>{{$users->designation}}</h3>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <h4>Email</h4>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <h3>{{$users->email}}</h3>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <h4>Mobile</h4>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <h3>{{$users->mobile}}</h3>
                                                    </div>
                                                </div>
                                                
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carbon Footprints_user_home new html end -->

              
          
          </div>
        </div>
</div>
@endsection
