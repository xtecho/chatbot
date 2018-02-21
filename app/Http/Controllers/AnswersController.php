<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Answer;
use App\Question;
use App\Keyword;
use JanDrda\LaravelGoogleCustomSearchEngine\LaravelGoogleCustomSearchEngine;

class AnswersController extends Controller {

    public function insert(Request $request) {
        if ($request->input('type') == 'answer') {
            $answer = new Answer;

            $params = array();
            $params['answer'] = $request->input('answer');
            $params['question_id'] = empty($request->input('question_id')) ? 1 : $request->input('question_id');
            $answer = Answer::firstOrCreate($params);

            return json_encode(['answer' => $answer, 'question' => $answer->question]);
        } else {
            if (empty($request->input('answer_teach'))) {
                $params = array();
                $params['question'] = $request->input('answer');

                $question = Question::firstOrCreate($params);

                if (!empty($question)) {
                    $answers = $question->answers;
                    $answer = $answers->isEmpty() ? null : $answers->random();

                    if (empty($answer)) {
                        $keywords = Keyword::all()->pluck('name');
                        foreach ($keywords as $keyword) {
                            if (strpos(strtolower($params['question']), $keyword) !== false) {
                                $seachEngine = new LaravelGoogleCustomSearchEngine();
                                $results = $seachEngine->getResults($params['question'], ['num' => 1]);
                                //todo: swearing word to do
                                $answer = new Answer;
                                $answer->answer = $results[0]->htmlSnippet;
                                $answer->question_id = $question->id;
                                $answer->save();
                            }
                        }
                    }
                }

                return json_encode(['answer' => $answer, 'question' => $question]);
            } else {
                $answer = new Answer;

                $answer->answer = $request->input('answer_teach');
                $answer->question_id = empty($request->input('question_id')) ? 1 : $request->input('question_id');
                $answer->save();

                return json_encode(['answer' => $answer, 'question' => $answer->question]);
            }
        }
    }

}
