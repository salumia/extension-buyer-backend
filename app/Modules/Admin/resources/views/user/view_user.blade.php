 
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
            <!-- Main Content Start -->
            <section class="main--content">
                <div class="panel">

                    <!-- View Order Start -->
                    <div class="records--body">
                        <!--<div class="title">
                            <h6 class="h6">Order #052656225<span class="text-lightergray"> - June 15, 2017 02:30</span></h6>
                        </div>-->

                        <!-- Tabs Nav Start -->
                        <!-- <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a href="#tab01" data-toggle="tab" class="nav-link active">Details</a>
                            </li>
                            <li class="nav-item">
                                <a href="#tab02" data-toggle="tab" class="nav-link">Extension Listing</a>
                            </li>
                        </ul> -->
                        <!-- Tabs Nav End -->

                        <!-- Tab Content Start -->
                        <div class="tab-content">
                            <!-- Tab Pane Start -->
                            <div class="tab-pane fade show active" id="tab01">
                                <h4 class="subtitle">User Details</h4>
                                <div class="row">
                                    
                                    <div class="col-md-6">
                                        <table class="table table-simple">
                                            <tbody>
                                                <tr>
                                                  <img src="{{$user['detail']->image_path}}" alt="{{$user['detail']->name}}" height="100" width="100" style="margin-left: 16px;">  
                                                </tr>
                                                 <tr>
                                                    <td>Name:</td>
                                                    <th><a href="#" class="btn-link">{{$user['detail']->name}} {{$user['detail']->last_name}}</a></th>
                                                </tr>
                                                
                                                <tr>
                                                    <td>Phone No:</td>
                                                    <th>{{$user['detail']->phone_no}}</th>
                                                </tr>
                                                <tr>
                                                    <td>Email:</td>
                                                    <th>{{$user['detail']->email}}</th>
                                                </tr>
                                                
                                                
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-6">
                                        <table class="table table-simple">
                                            <tbody>
                                               
                                                
                                                <tr>
                                                    <td>Country:</td>
                                                    <th>{{$user['country']}}</th>
                                                </tr>
                                                <tr>
                                                    <td>State:</td>
                                                    <th>{{$user['state']}}</th>
                                                </tr>
                                                <tr>
                                                    <td>City:</td>
                                                    <th>{{$user['city']}}</th>
                                                </tr>
                                                <tr>
                                                    <td>Address:</td>
                                                    <th>{{$user['detail']->address_line}}</th>
                                                </tr>
                                                <tr>
                                                    <td>Zipcode:</td>
                                                    <th>{{$user['detail']->zip_code}}</th>
                                                </tr>
                                                <tr>
                                                    <td>Status:</td>
                                                    @php
                                                        $CompleteStatus=array('0'=>'Under Review ','1'=>'Approve','2'=>'Deal in Progress','3'=>'Sold','4'=>'Complete','5'=>'Reject');
                                                    @endphp
                                                    @foreach($CompleteStatus as $s => $s_value)
                                                       @if($user['detail']->status==$s)
                                                            <th>{{$s_value}}</th>
                                                        @endif
                                                    @endforeach
                                                    
                                                </tr>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- Tab Pane End -->
                            <!-- Tab Pane Start -->
                            <!-- <div class="tab-pane fade" id="tab02">
                                
                            </div> -->
                            <!-- Tab Pane End -->

                         </div>
                        <!-- Tab Content End -->

                    </div>
                    <!-- View Order End -->
                </div>
                <div class="panel">
                    <!-- Records List Start -->
                    <div class="records--list" data-title="Extension Listing">
                        <table id="recordsListView">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Product Name</th>
                                    <th>Type</th>
                                    <th>Total User</th>
                                    <th>Status</th>
                                    <th>Price</th>
                                    <th>Service Fee</th>
                                    <th>Visibilty</th>
                                    <th>sold</th>
                                    <th>Negotiate</th>
                                    <th>Currency</th>
                                    <th>Store url</th>
                                    <th>Website</th>
                                    <th>Publish Date</th>
                                    
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;  
                                    ?>  
                                  @foreach($extensions as $extension)
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>
                                            {{$extension['product_name']}}
                                        </td>
                                        <td>
                                            {{$extension['type']}}
                                        </td>
                                        
                                        <td>
                                            {{$extension['total_users']}}
                                        </td>
                                        @php
                                         $status=array('0'=>'Under Review ','1'=>'Approve','2'=>'Deal in Progress','3'=>'Sold','4'=>'Complet','5'=>'Reject');
                                        @endphp
                                        <td>
                                            @foreach($status as $s => $s_value)
                                               @if($extension['status']==$s)
                                                    {{$s_value}}
                                                @endif
                                        @endforeach
                                            
                                        </td>
                                        <td>
                                            {{$extension['price']}}
                                        </td>
                                        <td>
                                            {{$extension['service_fee']}}
                                        </td>
                                        <td>
                                            @if($extension['visibilty']==1)
                                                Public
                                            @else
                                                Private
                                            @endif
                                            
                                        </td>
                                        <td>
                                            {{$extension['is_sold']}}
                                        </td>
                                        <td>
                                            @if($extension['negotiate']==0)
                                                NO
                                            @else
                                                Yes
                                            @endif
                                        </td>
                                       <td>
                                            {{$extension['currency']}}
                                        </td>
                                        
                                        <td>
                                            {{$extension['store_url']}}
                                        </td>
                                        <td>
                                            {{$extension['website']}}
                                        </td>
                                        <td>
                                            {{$extension['product_created_date']}}
                                        </td>
                                        
                                    </tr>
                                    <?php $i++; ?>
                                  @endforeach
                                
                            </tbody>
                        </table>
                    </div>
                    <!-- Records List End -->
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
                
                