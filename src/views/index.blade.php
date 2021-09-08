@extends('template::paper', ['title' => 'Lambda Platform'])

@push('styles')
    <link rel="stylesheet" href="/assets/lambda/fonts/flaticons/flaticons.css">
    <link rel="stylesheet" href="/assets/lambda/fonts/themify/themify-icons.css">
    <link rel="stylesheet" href="{{ mix('assets/lambda/css/moqup.css') }}">

    <link rel="stylesheet" href="{{ mix('assets/lambda/css/dataform.css') }}">
    <link rel="stylesheet" href="{{ mix('assets/lambda/css/datagrid.css') }}">
    <link rel="stylesheet" href="{{ mix('assets/lambda/css/datasource.css') }}">
    <link rel="stylesheet" href="{{ mix('assets/lambda/css/agent.css') }}">
    <link rel="stylesheet" href="{{ mix('assets/lambda/css/krud.css') }}">
    <link rel="stylesheet" href="{{ mix('/assets/lambda/css/adminmodule.css') }}">

@endpush

@section('app')
    <div id="puzzle" class="app-wrapper">{!! $title !!}</div>
@endsection

@push('scripts')
    <script type="text/javascript"
            src="http://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyCZCNSSfKeatvY1-QpSc_ShPyWmk7lEx4M&sensor=false&language=mn"></script>
    <script type="text/javascript" src="/vendor/echart/echarts-en.js"></script>
    <script type="text/javascript" src="/vendor/ckeditor/ckeditor.js"></script>
    <script>
        window.init = {
            user: {!! json_encode(auth()->user()) !!},
            dbSchema: {!! json_encode($dbSchema) !!},
            gridList: {!! json_encode($gridList) !!},
            user_fields:{!! json_encode($user_fields) !!},
            data_form_custom_elements: {!! json_encode(isset(config('lambda')['data_form_custom_elements']) ? config('lambda')['data_form_custom_elements'] : []) !!}
        };
    </script>

    <script src="{{ mix('assets/lambda/js/moqup.js') }}"></script>
    <script src="{{ mix('assets/lambda/js/dataform.js') }}"></script>
    <script src="{{ mix('assets/lambda/js/dataform-builder.js') }}"></script>

    <script src="{{ mix('assets/lambda/js/datagrid-vendor.js') }}"></script>
    <script src="{{ mix('assets/lambda/js/datagrid.js') }}"></script>
    <script src="{{ mix('assets/lambda/js/datagrid-builder.js') }}"></script>


    <script src="{{ mix('assets/lambda/js/datasource.js') }}"></script>
    <script src="{{ mix('assets/lambda/js/krud.js') }}"></script>
    <script src="{{ mix('assets/lambda/js/agent.js') }}"></script>
    <script src="{{ mix('/assets/lambda/js/adminmodule.js') }}"></script>
@endpush
