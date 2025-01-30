@extends('layouts.user.dashboard-master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10"><br>
            <div class="card">
                {{-- <div class="card-header">{{ __('Dashboard') }}</div> --}}

                <div class="card-success card-outline">
                    <div class="card-body box-profile">
                      <div class="text-center profile-header">
                        <img class="profile-user-img img-fluid img-circle"
                             src="{{asset('images/company_image.png')}}" alt="User profile picture">

                             <div class="profile-user-info">
                              <h3 class="profile-username text-center">{{$users->name}}</h3>
                              <p class="text-muted text-center">CIN- {{$users->cin_llpin}}</p>
                            </div>
                      </div>

                     

                      <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                          <b>Sector</b> <p class="float-right">{{$users->sector_name}}</p>
                        </li>
                        {{-- <li class="list-group-item">
                            <b>Bank Zone</b> <a class="float-right">{{$users->zone}}</a>
                          </li> --}}
                        <li class="list-group-item">
                            <b>Segment</b> <p class="float-right">{{$users->com_type}}</p>
                        </li>
                        <li class="list-group-item">
                          <b>Authorized Signatory</b> <p class="float-right">{{$users->contact_person}}</p>
                        </li>
                        <li class="list-group-item">
                            <b>Designation</b> <p class="float-right">{{$users->designation}}</p>
                        </li>
                        <li class="list-group-item">
                            <b>Email</b> <p class="float-right">{{$users->email}}</p>
                        </li>
                        <li class="list-group-item">
                            <b>Mobile</b> <p class="float-right">{{$users->mobile}}</p>
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
