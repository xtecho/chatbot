<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuestionsController extends Controller {

    public function show(Request $request) {
        $questions = \App\Question::all();
        return view('questions', ['questions' => $questions]);
    }

    public function getAnswers(\App\Question $question, Request $request) {
        return [
            'question' => $question,
            'answers' => $question->answers
        ];
    }

    public function editAnswer(\App\Answer $answer, Request $request) {
        $answer->answer = $request->get('answer');
        $answer->save();

        return json_encode(['answer' => $answer, 'question' => $answer->question]);
    }

}
