@extends('auth.layout')
@section('content')

    <div class="login-content">
        <div>
            <img class="login-logo" style="height:128px;margin-top: 80px;margin-left: 160px"
                 src="{{ asset('images/logo.png') }}"/>
        </div>
        <br><br>
        <form action="{{ route('login') }}" class="login-form" method="post" autocomplete="off">
            @csrf
            @if ($errors->any())
                <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    <span>
                                  @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                                </span>
                </div>
            @endif
            <div class="row">
                <div class="col-xs-6">
                    <input
                            class="form-control form-control-solid placeholder-no-fix form-group {{ $errors->has('email') ? ' is-invalid' : '' }}"
                            type="text" autocomplete="off" placeholder="Email address" name="email"
                            value="{{ old('email') }}" required/>


                </div>
                <div class="col-xs-6">
                    <input
                            class="form-control form-control-solid placeholder-no-fix form-group {{ $errors->has('password') ? ' is-invalid' : '' }}"
                            type="password" autocomplete="off" placeholder="Password" name="password" required/>

                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">

                </div>
                <div class="col-sm-8 text-right">
                    <div class="forgot-password">
                        <a href="javascript:;" id="forget-password" class="forget-password">Forgot Password?</a>
                    </div>
                    <button class="btn btn-primary" type="submit">Sign In</button>
                </div>
            </div>
        </form>
        <!-- BEGIN FORGOT PASSWORD FORM -->
        <form method="POST" class="forget-form" action="{{ route('password.email') }}">
            @csrf
            <h3 class="font-green">Forgot Password ?</h3>
            <p> Enter your e-mail address below to reset your password. </p>
            <div class="form-group">
                <input class="form-control placeholder-no-fix form-group" type="text" autocomplete="off"
                       placeholder="Email" name="email" value="{{ old('email') }}" required/>
                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                @endif
            </div>
            <div class="form-actions">
                <button type="button" id="back-btn" class="btn green btn-outline">Back</button>
                <button type="submit" class="btn btn-success uppercase pull-right">Submit</button>
            </div>
        </form>
        <!-- END FORGOT PASSWORD FORM -->
    </div>

@endsection
