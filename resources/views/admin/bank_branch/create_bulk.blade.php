@extends('layouts.vertical', ['title' => 'File Uploads | Dropzone | Dropify'])



@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        @include('layouts.shared.page-title' , ['title' => 'File Uploads','subtitle' => 'Forms'])

        <div class="row">
            <div class="col-12">
                <div class="card">
                        <div class="position-absolute top-0 end-0 p-2">
                             <a href="{{ asset('csv_sample/Branch_upload_sample.csv') }}">  <button class="btn btn-danger"> Sample CSV file</button> </a>
                    </div>
                    <div class="card-body">
                        <h4 class="header-title">Branch List File Upload</h4>
                        <p class="sub-header">
                            Upload Your Branches with valid IFSC Code 
                        </p>

                        <form action="/" method="post" class="dropzone" id="myAwesomeDropzone" data-plugin="dropzone"
                              data-previews-container="#file-previews"
                              data-upload-preview-template="#uploadPreviewTemplate">
                            <div class="fallback">
                                <input name="file" type="file" multiple/>
                            </div>

                            <div class="dz-message needsclick">
                                <i class="h1 text-muted dripicons-cloud-upload"></i>
                                <h3>Drop files here or click to upload.</h3>
                                <span class="text-muted font-13">( Selected files are
                                    <strong>not</strong> more than 2 MB uploaded.)</span>
                            </div>
                        </form>

                        <!-- Preview -->
                        <div class="dropzone-previews mt-3" id="file-previews"></div>

                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div><!-- end col -->
        </div>
        <!-- end row -->

        <!-- file preview template -->
        <div class="d-none" id="uploadPreviewTemplate">
            <div class="card mt-1 mb-0 shadow-none border">
                <div class="p-2">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <img data-dz-thumbnail src="#" class="avatar-sm rounded bg-light" alt="">
                        </div>
                        <div class="col ps-0">
                            <a href="javascript:void(0);" class="text-muted fw-bold" data-dz-name></a>
                            <p class="mb-0" data-dz-size></p>
                        </div>
                        <div class="col-auto">
                            <!-- Button -->
                            <a href="" class="btn btn-link btn-lg text-muted" data-dz-remove>
                                <i class="dripicons-cross"></i>
                            </a>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
                    <div class="col-md-2 offset-md-4">
                        <a href="{{ route('admin.bank_branch_bulk.index') }}" class="btn btn-secondary btn-sm form-control form-control-sm">
                            <em class="fas fa-arrow-left"></em> Back
                        </a>
                    </div>

        <!-- end row -->

    </div> <!-- container -->
@endsection

@section('script')
    @vite(['resources/js/pages/form-fileuploads.init.js'])
@endsection
