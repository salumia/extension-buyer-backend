<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Admin-Panel') }}</title>

    <!-- Scripts -->
    <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->

    <!-- ==== Document Meta ==== -->
    <meta name="author" content="">
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- ==== Favicon ==== -->
    <link rel="icon" href="{{ asset('admin/favicon.png') }}" type="{{ asset('admin/image/png') }}">

    <!-- ==== Google Font ==== -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700%7CMontserrat:400,500">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('admin/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/fontawesome-all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/perfect-scrollbar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/morris.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/jquery-jvectormap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/horizontal-timeline.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/weather-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/ion.rangeSlider.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/ion.rangeSlider.skinFlat.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/fullcalendar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">

    <!-- Page Level Stylesheets -->
</head>


<body >
   <!--  <div id="app">-->
    @yield('content') 
    <!-- </div> -->
</body>
</html>
