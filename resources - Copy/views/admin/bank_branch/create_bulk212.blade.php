@extends('layouts.vertical', ['title' => 'ESG PRAKRIT'])


@section('content')
    <div class="container-fluid">

        @include('layouts.shared.page-title' , ['title' => 'Bulk Upload Branch ','subtitle' => 'Forms'])
        <div class="row ">
            <div class="col-md-12">
                <form action="{{ route('admin.bank_branch_bulk.store') }}"  role="form" method="post"    class='prevent_multiple_submit' files=true enctype='multipart/form-data' accept-charset="utf-8">
                    @csrf
                    <div class="card">
                        <div class="row" style="margin-top:10px">
                            <div class="col-12 ">
                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                            </div>
                        </div>

                        <div class="row" style="margin-top: 2vh;margin-right: 5vh;margin-bottom: 2vh; text-align:right;">
                       
                             <div class="col-md-12">
                                  <a href="{{ asset('csv_sample/Branch_upload_sample.csv') }}">Sample CSV file </a>
                             </div></div>


                         <div class="row" style="padding: 5vh;">
                            <div class="col-md-4"><label>Upload Branchs Data</label></div>
                             <div class="col-md-4">
                                  <input type="file" id=""  name="files"  class="form-control form-control-sm text-right" >
                             </div>
                             <div class="col-md-4">
                                 <button type="submit" id="submit"
                                class="btn btn-primary btn-mb form-control form-control-sm form-control form-control-sm-sm" style=" margin:auto;display:block;width: auto;">
                                <em class="fas fa-save"> </em> Upload
                            </button>
                             </div>
                    
                        </div>
                        <div class="row">
                        <div class="col-md-2 offset-md-5">
                            
                        </div>
                    </div>
                    </div>
                    <br>
                  
                </form>
            </div>
        </div>
    </div>
        </div> <!-- container -->
@endsection
@push('scripts')

    @include('partials.js.prevent_multiple_submit')

@endpush






@section('script')
    @vite(['resources/js/pages/bulk-copmay-create.js'])
@endsection
