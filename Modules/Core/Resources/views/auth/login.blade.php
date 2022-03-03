@extends('core::layouts.master')
@section('content')
    <div class="box">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="form_section d-flex flex-column justify-content-center align-items-center ">
                        <div class="logo">
                            <img class="img-fluid w-100 mb-3 logo" src="{{asset('assets/backend/images/design/logo.png')}}" alt="Logo">
                        </div>
                        <div class="card card-signup">
                            <div class="card-body mb-2">

                                {{Form::open(['route'=>['core.login.submit'],'method'=>'POST','class'=>'login-form'])}}

                                     @include('core::inc.toaster-message')

                                    <div class="mb-3">
                                        <label for="exampleInputEmail1" class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp">
                                    </div>

                                    <div class="mb-3">
                                        <label for="exampleInputPassword1" class="form-label">Password</label>
                                        <input type="password" class="form-control" name="password" id="exampleInputPassword1">
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button class="btn btn-design" type="submit">Sign In</button>
                                    </div>

                                <div class="mb-3 mt-2 form-check">
                                    <input type="checkbox" class="form-check-input" name="remember" id="exampleCheck1">
                                    <label class="form-check-label" for="exampleCheck1">Remember me</label>
                                </div>
                                {{Form::close()}}
                                <a class="text-decoration-none" href="#">Forget Your Password?</a>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="content_section" style="background: url({{asset('assets/backend/images/design/bg.png')}})">
                        <h1>Start your free trial. No credit card required, no softwer to install</h1>
                        <p>With your trial, you get.</p>
                        <ul>
                            <li><i class="fas fa-check text-success"></i>Preloaded data or upload your own</li>
                            <li><i class="fas fa-check text-success"></i>Preconfigerd dashboard prossec and reports</li>
                            <li><i class="fas fa-check text-success"></i>Preloaded data or upload your own</li>
                            <li><i class="fas fa-check text-success"></i>Preloaded data or upload your own</li>
                        </ul>
                        <button type="submit" class="btn btn-primary mt-3 text-uppercase">Start My Free Trial</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection




