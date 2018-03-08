<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Answer;
use App\Question;
use App\Keyword;
use JanDrda\LaravelGoogleCustomSearchEngine\LaravelGoogleCustomSearchEngine;
use Gmopx\LaravelOWM\LaravelOWM;

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
            $questions = session('questions');
            if(empty($questions)) {
                session('questions', 1);
                $questions = 1;
            } else {
                session('questions', $questions++);
            }
            if($questions>4){
                return redirect('login');
            }
            if (empty($request->input('answer_teach'))) {
                $params = array();
                $params['question'] = $request->input('answer');

                $question = Question::firstOrCreate($params);

                if (!empty($question)) {
                    $answers = $question->answers;
                    $answer = $answers->isEmpty() ? null : $answers->random();

                    if (empty($answer)) {
                        if (strpos(strtolower($params['question']), 'weather') !== false) {
                            $words = explode(' ', strtolower($params['question']));
                            $country = '';
                            foreach ($words as $key => $word) {
                                if($word == 'weather') {
                                    if ($words[$key+1] == 'in') {
                                        $country = explode('?', $words[$key+2])[0];
                                    } else {
                                        $country = explode('?', $words[$key+1])[0];
                                    }
                                }
                            }
                            
                            $lowm = new LaravelOWM();
                            $current_weather = $lowm->getCurrentWeather($country);

                            $answer = new Answer;
                            $answer->answer = 'Weather in ' . ucfirst($country) . '? It\'s ' . $current_weather->temperature->now->getValue() . ' degrees celsius.';
                            $answer->question_id = $question->id;
                        } else {
                            $keywords = Keyword::all()->pluck('name');
                            foreach ($keywords as $keyword) {
                                if (strpos(strtolower($params['question']), $keyword) !== false) {
                                    $seachEngine = new LaravelGoogleCustomSearchEngine();
                                    $results = $seachEngine->getResults($params['question'], ['num' => 1]);
                                    //todo: swearing word to do
                                    $answer = new Answer;
                                    $answer->answer = explode('. ', $results[0]->htmlSnippet)[0] . '. ' . explode('. ', $results[0]->htmlSnippet)[1] . '.' .
                                            "<br><a href='" . $results[0]->link . "' target='_blank'>Read more...</a>";
                                    $answer->question_id = $question->id;
                                    $answer->save();
                                }
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
