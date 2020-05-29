<?php
    $adminData = Session::get('adminSessionData');
    $adminId = $adminData['id'];
    $adminName = $adminData['name'];
    $adminEmail = $adminData['email'];
?>
<aside class="sidebar" data-trigger="scrollbar">
    <!-- Sidebar Profile Start -->
    <div class="sidebar--profile">
        <div class="profile--img">
            <a href="#">
                <img src="{{ asset('admin-assets/img/avatars/01_80x80.png')}}" alt="" class="rounded-circle">
            </a>
        </div>

        <div class="profile--name">
            <a href="#" class="btn-link">{{$adminName}}</a>
        </div>

        <div class="profile--nav">
            <ul class="nav">
                <li class="nav-item">
                    <a href="{{ url('/profile') }}" class="nav-link" title="User Profile">
                        <i class="fa fa-user"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/changePassword') }}" class="nav-link" title="Password change">
                        <i class="fa fa-lock"></i>
                    </a>
                </li>
                <!--<li class="nav-item">
                    <a href="mailbox_inbox.html" class="nav-link" title="Messages">
                        <i class="fa fa-envelope"></i>
                    </a>
                </li>-->
                <li class="nav-item">
                    <a href="{{ url('/logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" class="nav-link" title="Logout">
                        <i class="fa fa-sign-out-alt"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- Sidebar Profile End -->

    <!-- Sidebar Navigation Start -->
    <div class="sidebar--nav">
        <ul>
            <li>
                <ul>
                    <li class="active">
                        <a href="{{ url('/dashboard') }}">
                            <i class="fa fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-user-circle"></i>
                            <span>Manage User</span>
                        </a>

                        <ul>
                            <li><a href="{{ url('/user') }}">Users</a></li>
                            <li><a href="{{ url('/user/create') }}">Add User</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-th-list"></i>
                            <span>Manage Category</span>
                        </a>

                        <ul>
                            <li><a href="{{ url('/categories') }}">Categories</a></li>
                            <li><a href="{{ url('/categories/create') }}">Add Category</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-th"></i>
                            <span>Manage Extension</span>
                        </a>

                        <ul>
                            <li><a href="{{ url('/extension') }}">Listing</a></li>
                            
                        </ul>
                    </li>
                    
                    
                    
                </ul>
            </li>

        </ul>
    </div>
    <!-- Sidebar Navigation End -->


</aside>
<!-- Sidebar End -->