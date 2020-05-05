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

      <h2>Admin profile data</h2>
    </div>

    <main class="main--container">
            <section class="main--content">
                <div class="panel">

                    <!-- Edit Product Start -->
                    <div class="records--body">
                        <div class="title">
                            <h6 class="h6">Edit Profile</h6>                  
                        </div>
                     
                        <!-- Tab Content Start -->
                        <div class="tab-content">
                            <!-- Tab Pane Start -->
                            <div class="tab-pane fade show active" id="tab01">
                                @if ($message = Session::get('success'))
                                    <div class="alert alert-success">
                                        <p>{{ $message }}</p>
                                    </div>
                                @endif
                                @if ($message = Session::get('error'))
                                    <div class="alert alert-danger">
                                        <p>{{ $message }}</p>
                                    </div>
                                @endif
                                <form id="profile-admin-edit" action="{{ url('admin/update') }}/{{ $admin->id }}" method="post">
                                    {{ csrf_field() }}

                                    <!-- <div class="form-group drop-custum focused">
                                       <div class="mb-4">
                                        
                                         <img src="{{ asset('admin/images/userImage')}}"
                                         class="rounded-circle z-depth-1-half avatar-pic" alt="example placeholder avatar">
                                        </div>
                                        <span id="image_name"></span>
                                        <div class="form-group row">
                                            <span class="label-text col-md-3 col-form-label">Upload Image :</span>
                                            <div class="btn btn-mdb-color btn-rounded float-left">
                                               <input type="file" id="select_image" name="image" accept="image/*">
                                            </div>
                                        </div>
                                    </div> -->

                                   
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Email: *</span>

                                        <div class="col-md-9">
                                            <input type="email" value="{{ $admin->email }}" name="email" class="form-control" required>
                                        </div>
                                    </div>
                                 
                                    <div class="row mt-3">
                                        <div class="col-md-9 offset-md-3">
                                            <input type="submit" value="Update" class="btn btn-rounded btn-success">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- Tab Pane End -->

                            

                           
                        </div>
                        <!-- Tab Content End -->
                    </div>
                    <!-- Edit Product End -->
                </div>
            </section>
            <!-- Main Content End -->

            @include('Admin::layouts.main_footer')
        <!-- Main Container End -->
          <!-- Scripts -->
           @include('Admin::layouts.footer')
         <!-- Scripts -->
@endsection
