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
    <!-- Wrapper End -->
  <?php
      $adminData = Session::get('adminSessionData');
      $adminId =    $adminData['id']                 // var_dump($name);
   ?>

   <main class="main--container">


            <!-- Main Content Start -->
            <section class="main--content">
                <div class="panel">

                    <!-- Edit Product Start -->
                    <div class="records--body">
                        <div class="title">
                            <h6 class="h6">Change Password</h6>                  
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
                                <form id="admin-change-password" method="POST" action="{{ url('admin/changepassword') }}/{{ $adminId }}">
                                     {{ csrf_field() }}
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">New Password: *</span>

                                        <div class="col-md-9">
                                            <input id="admin-password" type="password" name="password" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Confirm Password: *</span>

                                        <div class="col-md-9">
                                            <input name="confirmpassword" type="password" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-9 offset-md-3">
                                            <input type="submit" value="Save" class="btn btn-rounded btn-success">
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

            
        <!-- Main Container End -->
         @include('Admin::layouts.main_footer')
          <!-- Scripts -->
           @include('Admin::layouts.footer')
         <!-- Scripts -->
@endsection

