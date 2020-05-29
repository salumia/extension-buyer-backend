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

                    <!-- Edit Product Start -->
                    <div class="records--body">
                        <div class="title">
                            <h6 class="h6">Edit User</h6>
                        </div>

                        <!-- Tab Content Start -->
                        <div class="tab-content">
                            <!-- Tab Pane Start -->
                            <div class="tab-pane fade show active" id="tab01">
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
                                
  
                                <form id="edit-user-form" action="{{ url('admin/user/'.$user->id) }}" method="post">
                                    {{ csrf_field() }}
                                    
                                    {{Method_field('PUT')}}
                                    
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Profile Image : </span>
                                        <div class="col-md-9">
                                            <img src="{{$user->image_path}}" alt="{{$user->name}}" height="150" width="150">
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">First Name*: </span>
                                        <div class="col-md-9">
                                            <input type="text" value="{{$user->name}}"  name="firstName" class="form-control">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Last Name*: </span>
                                        <div class="col-md-9">
                                            <input type="text" value="{{$user->last_name}}"  name="lastName" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Email*: </span>
                                        <div class="col-md-9">
                                            <input type="email" readonly value="{{$user->email}}" name="email" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Phone*: </span>
                                        <div class="col-md-9">
                                            <input type="text" minlength="10" maxlength="10"  name="phone_no" value="{{$user->phone_no}}" class="form-control">
                                        </div>
                                    </div>
                                   
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Country*:</span>
                                        <div class="col-md-9">
                                            <select name="country_id" id="country_id" class="form-control" required>

                                                <option value="">Please Select country</option>

                                                @foreach($countries as $country)
                                                  <option value="{{$country->id}}"@if($user->country_id==$country->id){{'selected'}}@endif>{{$country->name}}
                                                  </option>
                                                @endforeach 
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">State*:</span>
                                        <div class="col-md-9">
                                            <select name="state_id" id="state_id" class="form-control" required>
                                                <option value="">Please Select State</option>
                                                @foreach($states as $state)
                                                  <option value="{{$state->id}}"@if($user->state_id==$state->id){{'selected'}}@endif>{{$state->name}}
                                                  </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">City*:</span>
                                        <div class="col-md-9">
                                            <select name="city_id" id="city_id" class="form-control" required>
                                                <option value="">Please Select city</option>
                                                @foreach($cities as $city)
                                                  <option value="{{$city->id}}"@if($user->city_id==$city->id){{'selected'}}@endif>{{$city->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                     <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Address*: </span>
                                        <div class="col-md-9">
                                            <input type="text" value="{{$user->address_line}}" name="address_line" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Zipcode*: </span>
                                        <div class="col-md-9">
                                            <input type="text" value="{{$user->zip_code}}"  name="zip_code" class="form-control">
                                        </div>
                                    </div>

                                    @php
                                    $status=array('1'=>'Enable','2'=>'Disable')
                                    @endphp

                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Status*:</span>
                                        <div class="col-md-9">
                                            <select name="status" class="form-control" required>
                                                <option value="">Please Select Status</option>
                                                @foreach($status as $s => $s_value)
                                                  <option value="{{$s}}"@if($user->status==$s){{'selected'}}@endif>{{$s_value}}</option>
                                                 @endforeach 
                                            </select>
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
    <!-- footer -->
       @include('Admin::layouts.main_footer')
     <!-- end footer -->
      <!-- Scripts -->
       @include('Admin::layouts.footer')
     <!-- Scripts -->
@endsection
