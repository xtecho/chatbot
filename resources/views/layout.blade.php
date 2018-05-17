<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="csrf-token" content="{{ csrf_token() }}" />

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
        
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.css"/>

        <link href="{{ asset('css/animate.css') }}" rel="stylesheet">

    </head>

    <body>
        <div id="wrapper">
            <!-- Navigation -->
            <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand logo-title" href="{{ route('dashboard') }}">TechBot</a>
                </div>
                <!-- /.navbar-header -->

                @if (Auth::guest())
                <style>
                    #page-wrapper{
                        border-left: 0px;
                    }
                </style>

                <div class="top-right links">
                    <a href="{{ route('login') }}" class="login">Login</a>
                    <a href="{{ route('register') }}" class="register">Register</a>
                </div>
                @else

                <ul class="nav navbar-top-links navbar-right">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                    <i class="fa fa-sign-out fa-fw"></i> Logout</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                        <!-- /.dropdown-user -->
                    </li>
                    <!-- /.dropdown -->
                </ul>

                <!-- /.navbar-top-links -->

                <div class="navbar-default sidebar" role="navigation">
                    <div class="sidebar-nav navbar-collapse">
                        <ul class="nav" id="side-menu">
                            <li>
                                <a href="{{ route('dashboard') }}"><i class="fa fa-question fa-fw"></i> Question by Question type</a>
                            </li>
                            <li>
                                <a href="{{ route('chat') }}"><i class="fa fa-comments-o fa-fw"></i> Chat type</a>
                            </li>
                            <li>
                                <a href="{{ route('teach') }}"><i class="fa fa-graduation-cap fa-fw"></i> Teach the TechBot</a>
                            </li>
                            @if(Auth::user()->admin)
                            <li>
                                <a href="{{ route('show-questions') }}"><i class="fa fa-edit fa-fw"></i> Question Management</a>
                            </li>
                                @endif
                        </ul>
                    </div>
                    <!-- /.sidebar-collapse -->
                </div>
                @endif
                <!-- /.navbar-static-side -->
            </nav>


            @yield('content')


        </div>
        <!-- /#wrapper -->

        <!-- jQuery -->
        <script src="{{    asset('js/jquery.min.js') }}"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="{{ asset('js/metisMenu.min.js') }}"></script>

        <!-- Custom Theme JavaScript -->
        <script src="{{ asset('js/sb-admin-2.js') }}"></script>
        <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
        <script src="{{ asset('js/homepage.js') }}"></script>
        <script src="{{ asset('js/chat.js') }}"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.js"></script>
        <script src="{{ asset('js/questions.js') }}"></script>
        <script src="{{ asset('js/sweetalert2.all.js') }}"></script>

    </body>

</html>
