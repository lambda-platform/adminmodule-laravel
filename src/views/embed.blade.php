<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    <title>Lambda embed</title>
    <link rel="stylesheet" href="/assets/lambda/fonts/roboto/roboto.css?family=Roboto:400,300,100,100italic,300italic,400italic,700,700italic">
    <link rel="stylesheet" href="{{ mix('lambda/vendor.css') }}">
    <link rel="stylesheet" href="/fonts/themify/themify-icons.css">
    <link rel="stylesheet" href="{{ mix('lambda/moqup.css') }}">
    <link rel="stylesheet" href="{{ mix('lambda/dataform.css') }}">
    <link rel="stylesheet" href="{{ mix('lambda/datagrid.css') }}">
    <link rel="stylesheet" href="{{ mix('lambda/datasource.css') }}">
    <link rel="stylesheet" href="{{ mix('lambda/chart.css') }}">
    <link rel="stylesheet" href="{{ mix('lambda/agent.css') }}">
    <link rel="stylesheet" href="{{ mix('lambda/puzzle.css') }}">
    <style>
        sidebar{
            display: none !important;
        }
    </style>
</head>
<body>

<div id="puzzle" class="app-wrapper"></div>
<script>
    window.init = {
        dbSchema: {!! json_encode($dbSchema) !!},
        gridList: {!! json_encode($gridList) !!}
    };
</script>
<script src="/lambda/manifest.js"></script>
<script type="text/javascript"
        src="http://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyCZCNSSfKeatvY1-QpSc_ShPyWmk7lEx4M&sensor=false&language=mn"></script>
<script type="text/javascript" src="/vendor/echart/echarts-en.js"></script>
<script type="text/javascript" src="/vendor/ckeditor/ckeditor.js"></script>
<script src="{{ mix('lambda/vendor.js') }}"></script>

<script src="{{ mix('lambda/dataform.js') }}"></script>
<script src="{{ mix('lambda/dataform-builder.js') }}"></script>

<script src="{{ mix('lambda/datagrid-vendor.js') }}"></script>
<script src="{{ mix('lambda/datagrid.js') }}"></script>
<script src="{{ mix('lambda/datagrid-builder.js') }}"></script>

<script src="{{ mix('lambda/krud.js') }}"></script>
<script src="{{ mix('lambda/agent.js') }}"></script>
<script src="{{ mix('lambda/puzzle.js') }}"></script>
</body>
</html>
