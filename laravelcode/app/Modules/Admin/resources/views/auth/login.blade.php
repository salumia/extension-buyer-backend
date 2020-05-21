@extends('Admin::layouts.app')
@section('content')
<div class="wrapper" style="background:#000000">
        <!-- Login Page Start -->
        <div class="m-account-w" data-bg-img="assets/img/account/wrapper-bg.jpg">
            <div class="m-account">
                <div class="row no-gutters">

                    <div class="col-md-12 mx-auto">
                        <!-- Login Form Start -->
                        <div class="m-account--form-w">
                            <div class="m-account--form">
                                <!-- Logo Start -->
                                <div class="logo">
                                    <!--<img src="{{ asset('admin/img/logo1.png') }}" alt="ExtensioBuyer Admin">-->
                                    <h2>ExtensioBuyer Admin</h2>
                                </div>
                                <!-- Logo End -->

                                <form id='login-form-id' action="{{ route('admin.login') }}" method="post">
                                    {{ csrf_field() }}
                                    <label class="m-account--title">Login to your account</label>

                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <i class="fas fa-user"></i>
                                            </div>

                                            <input id="email" type="email" placeholder="Email" class="form-control @error('email') is-invalid @enderror" name="email"  required autocomplete="email" autofocus>
                                            @if ($errors->has('email'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                           @endif
                                        </div>
                                        @if(session()->has('userstatus'))
                                        <label class="error">
                                            {{ session()->get('userstatus') }}
                                        </label>
                                       @endif
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <i class="fas fa-key"></i>
                                            </div>

                                            <input placeholder="Password" id="password" type="password" class="form-control @error('password') is-invalid @enderror"  name="password"  required autocomplete="current-password">

                                            @if ($errors->has('password'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="m-account--actions">
                                        <!-- <a href="#" class="btn-link">Forgot Password?</a> -->

                                        <button type="submit" class="btn btn-block btn-rounded btn-info">Login</button>
                                    </div>

                                    <!-- <div class="m-account--alt">
                                        <p><span>OR LOGIN WITH</span></p>

                                        <div class="btn-list">
                                            <a href="#" class="btn btn-rounded btn-warning">Facebook</a>
                                            <a href="#" class="btn btn-rounded btn-warning">Google</a>
                                        </div>
                                    </div> -->

                                    <div class="m-account--footer">
                                        <p>&copy; 2020 ExtensionBuyer</p>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Login Form End -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Login Page End -->
    </div>
    <!-- Wrapper End -->
    @include('Admin::layouts.footer')
@endsection
