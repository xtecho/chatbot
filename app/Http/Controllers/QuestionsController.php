<?php

namespace App\Http\Controllers;

use App\Answer;
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

        return [
            'answer' => $answer,
            'question' => $answer->question
        ];
    }

    public function addAnswer(Request $request) {
        $answer = new Answer();
        $answer->answer = $request->get('answer');
        $answer->question_id = $request->get('question');
        $answer->save();

        return [
            'answer' => $answer,
            'question' => $answer->question
        ];
    }

    public function deleteAnswer(\App\Answer $answer, Request $request) {
        try {
            $answer->delete();
            return [
                'answer' => $answer
            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    public function deleteQuestion(\App\Question $question, Request $request) {
        try {
            $question->answers()->delete();
            $question->delete();
            return [
                'question' => $question
            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

}
