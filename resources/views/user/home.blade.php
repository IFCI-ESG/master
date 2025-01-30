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
                <div class="container ">
                    <div class="row justify-content-center">
                        <div class="col-lg-10 col-md-10">
                            <div class="  shadow-div">
                                <div class="row align-items-center ">
                                    <div class="col-sm-4 bg-c-lite-green user-profile">
                                        <div class="card-block text-center text-white">
                                            <div class="user-profile-sec">
                                                <img src="{{ asset('images/company_image.png') }}" class="img-radius"
                                                    alt="User-Profile-Image">
                                            </div>
                                            @if (isset($corp_users))
                                                <h3 class="profile-username text-center">{{ $corp_users->name }}</h3>
                                                <p class="text-muted text-center">CIN- {{ $corp_users->cin_llpin }}</p>
                                            @else
                                                <h3 class="profile-username text-center">{{ $user->name }}</h3>
                                                <p class="text-muted text-center">PAN- {{ $user->pan }}</p>
                                            @endif

                                        </div>
                                    </div>

                                    <div class="col-sm-8">
                                        <div class="card-block">
                                            @if (isset($corp_users))
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <h4>Sector</h4>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <h3>{{ $corp_users->sector_name }}</h3>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <h4>Segment</h4>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <h3>{{ $corp_users->com_type }}</h3>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <h4>Authorized Signatory</h4>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <h3>{{ $corp_users->contact_person }}</h3>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <h4>Designation</h4>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <h3>{{ $corp_users->designation }}</h3>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <h4>Email</h4>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <h3>{{ $corp_users->email }}</h3>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <h4>Mobile</h4>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <h3>{{ $corp_users->mobile }}</h3>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <h4>Email</h4>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <h3>{{ $user->email }}</h3>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <h4>Mobile</h4>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <h3>{{ $user->mobile }}</h3>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <h4>Address</h4>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <h3>{{ $user->reg_off_add }},{{ $user->reg_off_city }},{{ $user->reg_off_state }},{{ $user->reg_off_pin }}</h3>
                                                    </div>
                                                </div>
                                            @endif


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

    {{-- <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6"><br>
                <div class="card">
                    <div class="card card-success card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle"
                                    src="{{ asset('images/company_image.png') }}" alt="User profile picture">
                            </div>
                            @if (isset($corp_users))
                                <h3 class="profile-username text-center">{{ $corp_users->name }}</h3>
                                <p class="text-muted text-center">CIN- {{ $corp_users->cin_llpin }}</p>
                            @else
                                <h3 class="profile-username text-center">{{ $user->name }}</h3>
                                <p class="text-muted text-center">PAN- {{ $user->pan }}</p>
                            @endif
                            <ul class="list-group list-group-unbordered mb-3">
                                @if (isset($corp_users))
                                    <li class="list-group-item">
                                        <b>Sector</b> <a class="float-right">{{ $corp_users->sector_name }}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Segment</b> <a class="float-right">{{ $corp_users->com_type }}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Authorized Signatory</b> <a
                                            class="float-right">{{ $corp_users->contact_person }}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Designation</b> <a class="float-right">{{ $corp_users->designation }}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Email</b> <a class="float-right">{{ $corp_users->email }}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Mobile</b> <a class="float-right">{{ $corp_users->mobile }}</a>
                                    </li>
                                @else
                                    <li class="list-group-item">
                                        <b>Email</b> <a class="float-right">{{ $user->email }}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Mobile</b> <a class="float-right">{{ $user->mobile }}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Address</b> <a
                                            class="float-right">{{ $user->reg_off_add }},{{ $user->reg_off_city }},{{ $user->reg_off_state }},{{ $user->reg_off_pin }}</a>
                                    </li>
                                @endif
                            </ul>

                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
