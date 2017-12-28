@extends('layout')

@section('content')

<style>
    #page-wrapper{
        border-left: 0px;
    }
</style>

<div class="top-right links">
    <a href="{{ route('login') }}" class="login">Login</a>
    <a href="{{ route('register') }}" class="register">Register</a>
</div>

<div class="flex-center full-height row">
    <div class="content_homepage">
        <img src="{{ asset('img/robot.png') }}" style="width:50%">
        <form action="{{ route('add-answer') }}" method="post">
            {{ csrf_field() }}
            <div class="square col-12">
                <div class="typewriter">
                    <h1>Welcome human, how are you today?</h1>
                </div>
                <div class="answear_input" id="answear_appearance">
                    <input type="text" name="answers" placeholder="Type your answear here...">
                    <button type="submit" name="send" class="btn btn-info homepage_send_button">Send</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection