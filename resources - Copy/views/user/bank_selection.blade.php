@extends('layouts.user_vertical', ['title' => 'ESG PRAKRIT'])

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        @include('layouts.shared.page-title' , ['title' => 'Finnacial Year','subtitle' => 'Dashboards'])

<div class="content">
    

  </div>
</div>
@section('script')
    @vite(['resources/js/pages/dashboard-4.init.js'])
@endsection
@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\User\QuestionaireRequest', '#questions') !!}
    @include('partials.js.prevent_multiple_submit')
    <script>
        $(document).ready(function() {

        });
    </script>
@endpush
@ensection