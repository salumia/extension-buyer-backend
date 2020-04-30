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

        <!-- Main Container Start -->
        <main class="main--container">
     

            <!-- Main Content Start -->
            <section class="main--content">
                <div class="row gutter-20">
                    <div class="col-lg-8">
                        <!-- Panel Start -->
                        <div class="panel profile-cover">
                            <div class="profile-cover__img">
                                <img src="{{ asset('admin/img/avatars/01_150x150.png') }}" alt="">
                                
                            </div>

                            <div class="profile-cover__action" data-bg-img="{{ asset('admin/img/covers/01_800x150.jpg') }}" data-overlay="0.3">
                               
                            </div>

                            <div class="profile-cover__info">
                                <ul class="nav">
                                    <li><strong>56</strong>Tabs</li>
                                </ul>
                            </div>
                        </div>
                        <!-- Panel End -->

                        
                    </div>

                    <div class="col-lg-4">
                        <!-- Panel Start -->
                        <div class="panel">
                            <div class="panel-heading">
                                <h3 class="panel-title">About Me</h3>
                            </div>

                            <div class="panel-content panel-about">
                                <table>
                                   
                                    <tr>
                                        <th><i class="fa fa-envelope"></i>Email</th>
                                        <td>{{ $adminData['email'] }}</td>
                                    </tr>                                
                                </table>
                            </div>

                            <!-- <div class="panel-social">
                                <ul class="nav">
                                    <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                    <li><a href="#"><i class="fab fa-google-plus-g"></i></a></li>
                                    <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                    <li><a href="#"><i class="fab fa-behance"></i></a></li>
                                    <li><a href="#"><i class="fab fa-dribbble"></i></a></li>
                                </ul>
                            </div> -->
                        </div>
                        <!-- Panel End -->

                        

       
                    </div>
                </div>
            </section>
           
        @include('Admin::layouts.main_footer')

        <!-- Main Container End -->
          <!-- Scripts -->
           @include('Admin::layouts.footer')
         <!-- Scripts -->
@endsection
