<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <link rel="shortcut icon" href="{{ asset('img/robot-avatar.png') }}" />
        <title>TechBot</title>

        <!-- Bootstrap Core CSS -->
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

        <!-- MetisMenu CSS -->
        <link href="{{ asset('css/metisMenu.min.css') }}" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

        <!-- Morris Charts CSS -->
        <link href="{{ asset('css/morris.css') }}" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">

        <link href="{{ asset('css/homepage.css') }}" rel="stylesheet">

    </head>

    <body>

        <div class="img_login">
            <a href="{{ route('dashboard') }}" style="color:white;">
                <button type="submit" class="btn btn-primary" id="back-button">
                    Back
                </button>
            </a>
            <div class="robot_img_login">
                <img src="{{ asset('img/robot-status-happy.png') }}" style="width: 9.5%;">
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">Register</div>

                        <div class="panel-body">
                            <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                                {{ csrf_field() }}

                                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label for="name" class="col-md-4 control-label">Name</label>

                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                        @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                        @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label for="password" class="col-md-4 control-label">Password</label>

                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control" name="password" required>

                                        @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
                                            Register
                                        </button>
                                    </div>
                                    <div class="col-md-offset-4">
                                        <a class="btn btn-link" style="margin-top: 10px;" href="{{ route('login') }}">
                                            Already registered? Login here!
                                        </a>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{    asset('js/jquery.min.js') }}"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="{{ asset('js/metisMenu.min.js') }}"></script>

        <!-- Custom Theme JavaScript -->
        <script src="{{ asset('js/sb-admin-2.js') }}"></script>

        <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>


    </body>

</html>