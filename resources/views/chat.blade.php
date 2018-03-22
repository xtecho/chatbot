@extends('layout')

@section('content')
<link href="{{ asset('css/chat.css') }}" rel="stylesheet">
<div id="page-wrapper">
    <div class="img_center">
        <img src="{{ asset('img/robot.png') }}" style="width: 15%;">
    </div>
    <div class="chat-panel panel panel-default">
        <div class="panel-heading">
            <i class="fa fa-comments fa-fw chat_title"></i> Chat
        </div>
        <!-- /.panel-heading -->

        <div class="panel-body">
            <ul class="chat">
                <li class="left clearfix hidden">
                    <span class="chat-img pull-left">
                        <img src="http://placehold.it/50/55C1E7/fff" alt="User Avatar" class="img-circle">
                    </span>
                    <div class="chat-body clearfix">
                        <div class="header">
                            <strong class="primary-font">{{ Auth::user()->name }}</strong>
                            <small class="pull-right text-muted">
                                <i class="fa fa-clock-o fa-fw"></i>
                                <span class="time">17:26</span>
                            </small>
                        </div>
                        <p class="message">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales.
                        </p>
                    </div>
                </li>
                <li class="right clearfix hidden">
                    <span class="chat-img pull-right">
                        <img src="http://placehold.it/50/FA6F57/fff" alt="User Avatar" class="img-circle">
                    </span>
                    <div class="chat-body clearfix">
                        <div class="header">
                            <small class=" text-muted">
                                <i class="fa fa-clock-o fa-fw"></i>
                                <span class="time">17:26</span>
                            </small>
                            <strong class="pull-right primary-font">Techbot</strong>
                        </div>
                        <p class="message">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales.
                        </p>
                    </div>
                </li>
            </ul>
        </div>
        <!-- /.panel-body -->
        <div class="panel-footer">
            {!! Form::open(['id' => 'chat-form']) !!}
            {!! Form::hidden('question_id', 1, ['id' => 'question_id']) !!}
            {!! Form::hidden('type', 'question', ['id' => 'type']) !!}
            <div class="input-group">
                <input id="question" name="answer" type="text" class="form-control input-sm" placeholder="Type your question here...">
                <span class="input-group-btn">
                    <button class="btn btn-warning btn-sm" id="btn-chat">
                        Send
                    </button>
                </span>
            </div>
            {!! Form::close() !!}
        </div>
        <!-- /.panel-footer -->
    </div>
</div>
@endsection