@extends('Admin::layouts.app')

@section('content')
 <!-- Wrapper Start -->
    <div class="wrapper">
       <!-- Navbar Start -->
       @include('Admin::layouts.navbar')
        <!-- Navbar End -->

         <!-- Sidebar sart -->
          @include('Admin::layouts.sidebar')
        <!-- Sidebar End -->

    <!-- Main Container Start -->
        <main class="main--container">
            <!-- Page Header Start -->
            <section class="page--header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-6">
                            <!-- Page Title Start -->
                            <h2 class="page--title h5">EXTENSION</h2>
                            <!-- Page Title End -->

                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('admin/extension') }}">Extension</a></li>
                                <li class="breadcrumb-item active"><span>View Extension</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Page Header End -->

            <!-- Main Content Start -->
            <section class="main--content">
                <!-- <div class="panel">
                    
                    <div class="records--header">
                        <div class="title fa-shopping-bag">
                            <h3 class="h3">Ecommerce Order View <a href="#" class="btn btn-sm btn-outline-info">View Order Details</a></h3>
                        </div>
                    </div>
                </div> -->
                
                <div class="panel">

                    <!-- View Order Start -->
                    <div class="records--body">
                        <div class="title">
                            <h6 class="h6">Extension - <span class="text-lightergray">{{$product['product_name']}}</span></h6>
                        </div>

                        <!-- Tabs Nav Start -->
                        <!-- <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a href="#tab01" data-toggle="tab" class="nav-link active">Overview</a>
                            </li>
                            <li class="nav-item">
                                <a href="#tab02" data-toggle="tab" class="nav-link">Order Details</a>
                            </li>
                        </ul> -->
                        <!-- Tabs Nav End -->

                        <!-- Tab Content Start -->
                        <div class="tab-content">
                            <!-- Tab Pane Start -->
                            <div class="tab-pane fade show active" id="tab01">
                                <h4 class="subtitle">Quick Stats</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-simple">
                                            <tbody>
                                                <tr>
                                                    <td>Total Users:</td>
                                                    <th><a href="#" class="btn-link">{{$product['total_users']}}</a></th>
                                                </tr>
                                                <tr>
                                                    <td>Date Created:</td>
                                                    <th>{{date('d M,Y ',strtotime($product['product_created_date']))}}</th>

                                                </tr>
                                                <tr>
                                                    <td>Website:</td>
                                                    <th>{{$product['website']}}</th>
                                                </tr>
                                                <tr>
                                                    <td>Categories:</td>
                                                    <th>{{$product['categories']}}</th>
                                                </tr> 
                                                <tr>
                                                    <td>Visibility:</td>
                                                    @if($product['visibilty']==1)
                                                    <th>Public</th>
                                                    @else
                                                    <th>Private</th>
                                                    @endif
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-6">
                                        <table class="table table-simple">
                                            <tbody>
                                                <tr>
                                                    <td>Asking Price:</td>
                                                    <th>{{$product['price']}} {{$product['currency']}}</th>
                                                </tr>
                                                <tr>
                                                    <td>Seller Name:</td>
                                                    <th>{{$product['seller']['name']}}</th>
                                                </tr>
                                                <tr>
                                                    <td>Willing To Negotiate:</td>
                                                    @if($product['negotiate']==0)
                                                        <th>No</th>
                                                    @elseif($product['negotiate']==1) 
                                                        <th>No</th>
                                                    @endif       
                                                </tr>
                                                <tr>
                                                    <td>Type:</td>
                                                    <th>{{$product['type']}}</th>
                                                </tr>
                                                
                                                
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- Tab Pane End -->
                            
                        </div>
                        <!-- Tab Content End -->
                        <br>
                        <!-- Tab Content Start -->
                        <div class="tab-content">
                            <!-- Tab Pane Start -->
                            <div class="tab-pane fade show active" id="tab01">
                                <h4 class="subtitle">User Base</h4>
                                <div class="row">
                                    @foreach($product['userbase'] as $userbase)
                                    <div class="col-md-6">
                                        <table class="table table-simple">
                                            <tbody>
                                                <tr>
                                                    <td>{{$userbase->country_name}}:</td>
                                                    <th>{{$userbase->users}}</th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <!-- Tab Pane End -->

                            
                        </div>
                        <!-- Tab Content End -->
                        <br>
                        <!-- Tab Content Start -->
                        <div class="tab-content">
                            <!-- Tab Pane Start -->
                            <div class="tab-pane fade show active" id="tab01">
                                    <h4 class="subtitle">Description</h4>
                                <div class="row" >
                                    <textarea id="w3mission" rows="5" cols="140" readonly>{{$product['description']}}</textarea>
                                </div>

                            </div>
                            <!-- Tab Pane End -->
                        </div>
                        
                        
                        <br>
                        <!-- Tab Content Start -->
                        <div class="tab-content">
                            <!-- Tab Pane Start -->
                            <div class="tab-pane fade show active" id="tab01">
                                    <h4 class="subtitle">Banners</h4>
                                <div class="row" >
                                    @if(count($product['banners'])>0)
                                    <div id="myCarousel" class="carousel slide" data-ride="carousel" style="margin-left: 127px">
                                    <ol class="carousel-indicators">
                                        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                                    </ol>
                                    <div class="carousel-inner">
                                        @foreach($product['banners'] as $key => $img)
                                        <div class="carousel-item {{$key == 0 ? 'active' : '' }}">
                                            <img src="{{$img->image_path}}" class="d-block" width="100%" alt="statistics"> 
                                        </div>
                                        @endforeach
                                    </div>
                                    <a class="carousel-control-prev" href="#myCarousel" role="button"  data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div> 
                                    @endif
                                </div>

                            </div>
                            <!-- Tab Pane End -->
                        </div>
                        <br>
                        <!-- Tab Content Start -->
                        <div class="tab-content">
                            <!-- Tab Pane Start -->
                            <div class="tab-pane fade show active" id="tab01">
                                    <h4 class="subtitle">Statistics</h4>
                                <div class="row" >
                                    @if(count($product['statistics'])>0)
                                    <div id="myCarousel" class="carousel slide" data-ride="carousel" style="margin-left: 127px">
                                    <ol class="carousel-indicators">
                                        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                                    </ol>
                                    <div class="carousel-inner">
                                        @foreach($product['statistics'] as $key => $img)
                                        <div class="carousel-item {{$key == 0 ? 'active' : '' }}">
                                            <img src="{{$img->image_path}}" class="d-block" width="100%" alt="statistics"> 
                                        </div>
                                        @endforeach
                                    </div>
                                    <a class="carousel-control-prev" href="#myCarousel" role="button"  data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div> 
                                    @endif
                                </div>

                            </div>
                            <!-- Tab Pane End -->
                        </div>
                        <!-- Tab Content End -->
                        <br>
                        <!-- Tab Content Start -->
                        <div class="tab-content">
                            <!-- Tab Pane Start -->
                            <div class="tab-pane fade show active" id="tab01">
                                    <h4 class="subtitle">About Seller</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-simple">
                                            <tbody>
                                                <tr>
                                                    <td>Name:</td>
                                                    <th><a href="{{url('admin/user/'.$product['user_id'])}}" class="btn-link">{{$product['seller']['name']}}</a></th>
                                                </tr>
                                                <tr>
                                                    <td>Location:</td>
                                                    <th>{{$product['seller']['country']}}</th>
                                                </tr>
                                                
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-6">

                                        <table class="table table-simple">
                                            <tbody>
                                                <tr>
                                                    <td>Listings:</td>
                                                    <th>{{$product['seller']['total_listings']}}</th>
                                                </tr>
                                                <tr>
                                                    <td>Listings Sold:</td>
                                                    <th>{{$product['seller']['sold_listings']}}</th>
                                                </tr>
                                                 
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- Tab Pane End -->
                            
                        </div>
                        <!-- Tab Content End -->

                    </div>
                    <!-- View Order End -->
                </div>
            </section>
            <!-- Main Content End -->
    <!-- footer -->
       @include('Admin::layouts.main_footer')
     <!-- end footer -->
      <!-- Scripts -->
       @include('Admin::layouts.footer')
     <!-- Scripts -->
@endsection
