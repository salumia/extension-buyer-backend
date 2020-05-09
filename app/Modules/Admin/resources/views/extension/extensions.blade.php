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
                            <h2 class="page--title h5">Extension</h2>
                            <!-- Page Title End -->

                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active"><span>Extensions</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Page Header End -->

            <!-- Main Content Start -->
            <section class="main--content">
                <div class="panel">
                    <!-- Records Header Start -->
                    <div class="records--header">
                        
                        <div class="title">
                          <a href="javascript:void(0);" id="back-user-list" class="m-4 fa fa-arrow-left" style="color: black"> Back</a>  
                            
                        </div>
                        <div class="actions">
                            <form action="{{url('admin/extension')}}" method="get" class="search">
                                {{ csrf_field() }}
                                <input id='search-extension' type="text" class="form-control" name="extension" placeholder="extension..." required>
                                <button type="submit" class="btn btn-rounded"><i class="fa fa-search"></i></button>
                            </form>
                        </div>


                    </div>
                    <!-- Records Header End -->
                </div>

                <div class="panel">

                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </p>
                            
                        </div>
                    @endif
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger">
                            <p>{{ $message }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </p>
                            
                        </div>
                    @endif
                    <!-- Records List Start -->
                    <div class="records--list" data-title="Extension Listing">
                        <table id="recordsListView">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Seller</th>
                                    <th>Status</th>
                                    <th>Received Offers</th>
                                    <th>Created</th>
                                    <th class="not-sortable">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;  
                                ?>  
                              @foreach($extensions as $extension)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>
                                        <a href="{{url('admin/extension/'.$extension['id'].'/')}}" class="btn-link">{{$extension['product_name']}}</a>
                                    </td> 
                                    <td>
                                        <a href="{{url('admin/extension/'.$extension['id'].'/')}}" class="btn-link">{{$extension['type']}}</a>
                                    </td>
                                    <td>
                                        <a href="{{url('admin/extension/'.$extension['id'].'/')}}" class="btn-link">{{$extension['seller']}}</a>
                                    </td>
                                    
                                    @php
                                    $status=array('0'=>'Under review ','4'=>'Completed','5'=>'rejected');

                                    $CompleteStatus=array('0'=>'Under review ','1'=>' verified and public available','2'=>'Deal in progress','3'=>'sold','4'=>'Completed','5'=>'rejected');
                                    @endphp
                                    
                                    <td>
                                        @if($extension['status']==0)
                                        <select name="status" extension_id="{{$extension['id']}}" class="form-control extensionId" required>
                    
                                                  @foreach($status as $s => $s_value)
                                                  <option value="{{$s}}"@if($extension['status']==$s){{'selected'}}@endif>{{$s_value}}</option>
                                                 @endforeach 
                                            </select>
                                        @else    
                                            @foreach($CompleteStatus as $s => $s_value)
                                               @if($extension['status']==$s)
                                                    <a href="{{url('admin/extension/'.$extension['id'].'/')}}" class="btn-link">{{$s_value}}</a>
                                                @endif
                                            @endforeach 
                                        @endif
                                    </td>
                                    
                                    <td>
                                        <a href="{{url('admin/extension/'.$extension['id'].'/')}}" class="btn-link">{{$extension['received_offer']}}</a>
                                    </td>

                                    <td><a href="{{url('admin/extension/'.$extension['id'].'/')}}" class="btn-link">{{date('d M,Y ',strtotime($extension['created_at']))}}</a></td>               
                                    <td>
                                        <div class="dropleft">
                                            <a href="#" class="btn-link" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>

                                            <div class="dropdown-menu">
                                                <a href="{{url('admin/extension/'.$extension['id'].'/')}}" class="dropdown-item"><button class="btn btn-rounded btn-outline-info btn-st">View</button></a>

                                                <!-- <form style="margin-left: 24px;" action="{{url('admin/extension/'.$extension['id'])}}" method="post">
                                                {!! csrf_field() !!} 
                                                {{Method_field('DELETE')}}
                                                    <button type="submit" id="delete_btn" class="btn btn-rounded btn-outline-info btn-st" onClick="return confirm('Are you really want to delete this Product')" >Delete</button>
                                                </form> -->
                                            </div>
                                        </div>
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
