@extends('layout')

@section('content')
<link href="{{ asset('css/chat.css') }}" rel="stylesheet">
<div id="page-wrapper">
    <table id="questions-datatable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Question</th>
                <th>Anwers</th>
                <th>Counter</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($questions as $question)
            <tr data-id="{{ $question->id }}">
                <td>{{ $question->id }}</td>
                <td>{{ $question->question }}</td>
                <td class="answers">{{ !empty($question->answers) ? $question->answers->count() : 0 }}</td>
                <td>{{ $question->counter }}</td>
                <td class="text-center">
                    <span class="fa fa-pencil edit-question" data-id="{{ $question->id }}"></span>
                    <span class="fa fa-remove delete-question" data-id="{{ $question->id }}"></span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-xs-10 modal-title"id="editModalLabel">
                        Edit Answers
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    {{--<button type="button" class="btn btn-primary">Save changes</button>--}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection