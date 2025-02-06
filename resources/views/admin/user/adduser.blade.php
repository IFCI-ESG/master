@extends('layouts.vertical', ['title' => 'ESG PRAKRIT'])

@section('css')
    @vite(['node_modules/sweetalert2/dist/sweetalert2.min.css'])
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">
       @include('layouts.shared.page-title' , ['title' => 'Exposure','subtitle' => ''])
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
            <div class="col-md-12">
                <div class="user-add-sec">
                 <form action="{{ route('admin.user.apidata') }}" id="user_create" role="form" method="get"
                    class='prevent_multiple_submit_details' files=true enctype='multipart/form-data' accept-charset="utf-8">                   @csrf

                        <div class="card border-primary">
                            <div class="card-body m-4">
                                <h3 class="text-center">Add Exposure</h3>
                                <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <th rowspan="2" class="company-head">PAN of the Customer</th>
                                            <td rowspan="2">
                                                <input type="text" id="pan" name="pan" class="form-control">
                                                @error('pan')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </td>
                                            <td class="text-right" rowspan="2">
                                                <button type="submit" id="details" class="btn company-btn btn-primary btn-sm form-control form-control-sm">
                                                    <em class="fa fa-search"></em>&nbsp;&nbsp; Get Details
                                                </button>

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                </div>
                             </div>
                            </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    {{-- {!! JsValidator::formRequest('App\Http\Requests\UserRequest', '#user_create') !!} --}}
    <!-- @include('partials.js.prevent_multiple_submit') -->

        <script>

$(document).ready(function () {

const btn1 = document.getElementById("details");

$('.prevent_multiple_submit_details').on('submit', function(event) {
    //event.preventDefault();

    $('#det').html('<div class="offset-md-4 msg1"><span class="text-danger text-sm text-center">Please wait while your request is being processed. &nbsp&nbsp&nbsp<i class="fa fa-spinner fa-spin" style="font-size:24px;color:black"></i></span></div>');

    btn1.disabled = true;
    setTimeout(function() {
        btn1.disabled = false;
    }, 1000 * 20);  // 20 seconds

    // Hide the loading message after 20 seconds
    setTimeout(function() {
        $(".msg1").fadeOut();
    }, 1000 * 20);  // 20 seconds
});



     });


    </script>

@endpush
