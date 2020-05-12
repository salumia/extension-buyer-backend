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
                            <h2 class="page--title h5">Categories</h2>
                            <!-- Page Title End -->

                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active"><span>Categories</span></li>
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
                        <!--  <a href="javascript:void(0);" id="back-user-list" class="m-4 fa fa-arrow-left" style="color: black"> Back</a>  
                        -->    
                        </div>
                        <div class="actions">
                            <form action="{{url('admin/categories')}}" method="get" class="search">
                                {{ csrf_field() }}
                                <input id='search-category' type="text" class="form-control" name="category" placeholder="Name..." required>
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
                    <div class="records--list" data-title="Categories Listing">
                        <table id="recordsListView">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th class="not-sortable">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;  
                                ?>  
                              @foreach($categories as $category)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>
                                        <a href="{{url('admin/categories/'.$category->id.'/edit')}}" class="btn-link">{{$category->category_name}}</a>
                                    </td>
                                    @php
                                    $status=array('0'=>'Disable','1'=>'Enable');
                                    @endphp
                                    <td>
                                        @foreach($status as $s => $s_value)
                                           @if($category->status==$s)
                                                <a href="{{url('admin/categories/'.$category->id.'/edit')}}" class="btn-link"><span class="label label-success">{{$s_value}}</span></a>
                                            @endif
                                        @endforeach 
                                    </td>
                                    
                                    <td>
                                        <div class="dropleft">
                                            <a href="#" class="btn-link" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>

                                            <div class="dropdown-menu">
                                                <a href="{{url('admin/categories/'.$category->id.'/edit')}}" class="dropdown-item"><button class="btn btn-rounded btn-outline-info btn-st">Edit</button></a>

                                                <form style="margin-left: 24px;" action="{{url('admin/categories/'.$category->id)}}" method="post">
                                                {!! csrf_field() !!} 
                                                {{Method_field('DELETE')}}
                                                    <button type="submit" id="delete_btn" class="btn btn-rounded btn-outline-info btn-st" onClick="return confirm('Are you really want to delete this Category')" >Delete</button>
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
