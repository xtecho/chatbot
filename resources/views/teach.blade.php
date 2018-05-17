@extends('layout')

@section('content')
<link href="{{ asset('css/chat.css') }}" rel="stylesheet">
{{--<div id="page-wrapper">--}}

    <div class="flex-center full-height col-lg-12 col-xs-12">
        <div class="content_homepage">
            <img src="{{ asset('img/robot-status-wrong.png') }}" class="robot_img">
            {!! Form::open(['id' => 'teach-form']) !!}
            <div class="square col-lg-12 col-xs-12">
                <div class="typewriter">
                    <h1>Help me improve!<span class="write">|</span></h1>
                </div>
                <div class="answear_input" id="answear_appearance" data-type="question">
                    {!! Form::hidden('type', 'question', ['id' => 'type']) !!}
                    {!! Form::hidden('question_id', '', ['id' => 'question_id']) !!}
                    <input type="text" name="answer" placeholder="Type your question here..." id="answer-input" autocomplete="off">
                    <button type="submit" name="send" class="btn btn-info homepage_send_button" id="submit-btn">Send</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
{{--</div>--}}
@endsection