<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--<title>{{ config('app.name', 'Admin | ExtensionBuyer') }}</title>-->
    <title>Admin | ExtensionBuyer</title>

    <!-- Scripts -->
    <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->

    <!-- ==== Document Meta ==== -->
    <meta name="author" content="">
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- ==== Favicon ==== -->
    <link rel="icon" href="{{ asset('admin-assets/favicon.png') }}" type="{{ asset('admin/image/png') }}">

    <!-- ==== Google Font ==== -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700%7CMontserrat:400,500">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('admin-assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/fontawesome-all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/perfect-scrollbar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/morris.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/jquery-jvectormap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/horizontal-timeline.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/weather-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/ion.rangeSlider.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/ion.rangeSlider.skinFlat.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/fullcalendar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/datatables.min.css') }}">
    
    <!-- Page Level Stylesheets -->
</head>


<body >
   <!--  <div id="app">-->
    @yield('content') 
    <!-- </div> -->
</body>
</html>
