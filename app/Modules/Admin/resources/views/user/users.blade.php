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
                            <h2 class="page--title h5">USERS</h2>
                            <!-- Page Title End -->

                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active"><span>Users</span></li>
                            </ul>
                        </div>

                        <div class="col-lg-6">
                            <!-- Summary Widget Start -->
                            <div class="summary--widget">
                                <div class="summary--item">
                                    <p class="summary--chart" data-trigger="sparkline" data-type="bar" data-width="5" data-height="38" data-color="#009378">2,9,7,9,11,9,7,5,7,7,9,11</p>

                                    <p class="summary--title">This Month</p>
                                    <p class="summary--stats text-green">2,371,527</p>
                                </div>

                                <div class="summary--item">
                                    <p class="summary--chart" data-trigger="sparkline" data-type="bar" data-width="5" data-height="38" data-color="#e16123">2,3,7,7,9,11,9,7,9,11,9,7</p>

                                    <p class="summary--title">Last Month</p>
                                    <p class="summary--stats text-orange">2,527,371</p>
                                </div>
                            </div>
                            <!-- Summary Widget End -->
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
                            <form action="{{url('admin/user')}}" method="get" class="search">
                                {{ csrf_field() }}
                                <input id='search-email' type="text" class="form-control" name="email" placeholder="Email..." required>
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
                    <div class="records--list" data-title="Users Listing">
                        <table id="recordsListView">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Country</th>
                                    <th>Status</th>
                                    <!-- <th>Created</th> -->
                                    <th class="not-sortable">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;  
                                ?>  
                              @foreach($users as $user)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>
                                        <a href="{{url('admin/user/'.$user->id.'/edit')}}" class="btn-link">{{$user->name}}</a>
                                    </td>
                                    <td><a href="{{url('admin/user/'.$user->id.'/edit')}}" class="btn-link">{{$user->email}}</a></td>
                                    <td>
                                        @foreach($country as $c)
                                            @if($c->id==$user->country_id)
                                                <a href="{{url('admin/user/'.$user->id.'/edit')}}" class="btn-link">{{$c->name}}</a>
                                            @endif
                                        @endforeach
                                    </td>
                                    @php
                                    $status=array('0'=>'Deactivated','1'=>'Pending','2'=>'Activated')
                                    @endphp
                                    <td>
                                        @foreach($status as $s => $s_value)
                                           @if($user->status==$s)
                                                <a href="{{url('admin/user/'.$user->id.'/edit')}}" class="btn-link"><span class="label label-success">{{$s_value}}</span></a>
                                            @endif
                                        @endforeach 
                                    </td>
                                    <!-- <td>                                        <a href="{{ url('admin/user') }}/{{ $user->id }}" class="btn-link">{{$user->created_at}}</a></td> -->
                                    
                                    <td>
                                        <div class="dropleft">
                                            <a href="#" class="btn-link" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>

                                            <div class="dropdown-menu">
                                                <a href="{{url('admin/user/'.$user->id.'/edit')}}" class="dropdown-item"><button class="btn btn-rounded btn-outline-info">Edit</button></a>

                                                <form style="margin-left: 24px;" action="{{url('admin/user/'.$user->id)}}" method="post">
                                                {!! csrf_field() !!} 
                                                {{Method_field('DELETE')}}
                                                    <button type="submit" id="delete_btn" class="btn btn-rounded btn-outline-info" onClick="return confirm('Are you really want to delete this User')" >Delete</button>
                                                </form>
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
