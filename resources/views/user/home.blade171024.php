@extends('layouts.user.dashboard-master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6"><br>
            <div class="card">
                {{-- <div class="card-header">{{ __('Dashboard') }}</div> --}}

                <div class="card card-success card-outline">
                    <div class="card-body box-profile">
                      <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle"
                             src="{{asset('images/company_image.png')}}" alt="User profile picture">
                      </div>

                      <h3 class="profile-username text-center">{{$users->name}}</h3>

                      <p class="text-muted text-center">CIN- {{$users->cin_llpin}}</p>

                      <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                          <b>Sector</b> <a class="float-right">{{$users->sector_name}}</a>
                        </li>
                        {{-- <li class="list-group-item">
                            <b>Bank Zone</b> <a class="float-right">{{$users->zone}}</a>
                          </li> --}}
                        <li class="list-group-item">
                            <b>Segment</b> <a class="float-right">{{$users->com_type}}</a>
                        </li>
                        <li class="list-group-item">
                          <b>Authorized Signatory</b> <a class="float-right">{{$users->contact_person}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Designation</b> <a class="float-right">{{$users->designation}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Email</b> <a class="float-right">{{$users->email}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Mobile</b> <a class="float-right">{{$users->mobile}}</a>
                        </li>
                      </ul>

                    </div>
                    <!-- /.card-body -->
                  </div>

                {{-- <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                </div> --}}
            </div>
        </div>
    </div>
</div>
@endsection
