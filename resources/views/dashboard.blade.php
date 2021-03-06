@extends('layout')

@section('content')

<style>
    #page-wrapper{
        border-left: 0px;
    }
</style>

<!--<div class="top-right links">
    <a href="{{ route('login') }}" class="login">Login</a>
    <a href="{{ route('register') }}" class="register">Register</a>
</div>-->

<div class="flex-center full-height col-lg-12 col-xs-12">
    <div class="content_homepage">
        <img src="{{ asset('img/robot-status-ok.png') }}" class="robot_img">
        {!! Form::open(['id' => 'chatbot-form']) !!}
        <div class="square col-lg-12 col-xs-12">
            <div class="typewriter">
                <h1>Welcome human, how are you today?<span class="write">|</span></h1>
            </div>
            <div class="answear_input" id="answear_appearance">
                {!! Form::hidden('question_id', 1, ['id' => 'question_id']) !!}
                {!! Form::hidden('type', 'answer', ['id' => 'type']) !!}
                <input type="text" name="answer" placeholder="Type your answer here..." id="answer-input" autocomplete="off">
                <button type="submit" name="send" class="btn btn-info homepage_send_button" id="submit-btn">Send</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>

@endsection