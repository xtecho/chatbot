<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Answer;
use App\Question;
use App\Keyword;
use App\Badword;
use JanDrda\LaravelGoogleCustomSearchEngine\LaravelGoogleCustomSearchEngine;
use Gmopx\LaravelOWM\LaravelOWM;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class AnswersController extends Controller
{

    public function insert(Request $request)
    {
        if ($request->input('type') == 'answer') {
            $params = array();
            $params['answer'] = $request->input('answer');
            $params['question_id'] = empty($request->input('question_id')) ? 1 : $request->input('question_id');
            $answer = Answer::firstOrCreate($params);

            return json_encode(['answer' => $answer, 'question' => $answer->question]);
        } else {
            if (empty($request->input('answer_teach'))) {
                if (Auth::guest()) {
                    $questions = Session::get('questions');
                    if (empty($questions)) {
                        Session::put('questions', 1);
                        $questions = 1;
                    } else {
                        Session::put('questions', ++$questions);
                    }
                    Session::save();
                    if ($questions > 3) {
                        return json_encode(['redirect' => true]);
                    }
                }
                $params = array();
                $params['question'] = strtolower($request->input('answer'));

                $question = Question::firstOrCreate($params);
                $question->counter++;
                $question->save();

                $badwords = Badword::all()->pluck('word');
                foreach ($badwords as $badword) {
                    if (strpos(strtolower($params['question']), $badword) !== false) {
                        $answer = new Answer;
                        $answer->answer = "That is a bad word... I don't like bad words :(";
                        $answer->question_id = $question->id;

                        return json_encode(['answer' => $answer, 'question' => $question]);
                    }
                }

                if (!empty($question)) {
                    $answers = $question->answers;
                    $answer = $answers->isEmpty() ? null : $answers->random();

                    if (empty($answer)) {
                        if (strpos(strtolower($params['question']), 'weather') !== false) {
                            $words = explode(' ', strtolower($params['question']));
                            $country = '';
                            foreach ($words as $key => $word) {
                                if ($word == 'weather') {
                                    if ($words[$key + 1] == 'in') {
                                        $country = explode('?', $words[$key + 2])[0];
                                        if (isset($words[$key + 3]) AND $words[$key + 3]) {
                                            $country .= ' ' . explode('?', $words[$key + 3])[0];
                                        }
                                    } else {
                                        $country = explode('?', $words[$key + 1])[0];
                                        if (isset($words[$key + 2]) AND $words[$key + 2]) {
                                            $country .= ' ' . explode('?', $words[$key + 2])[0];
                                        }
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
                                    if (!empty($results)) {
                                        $answer = new Answer;
                                        $answer->answer = explode('. ', $results[0]->htmlSnippet)[0];
                                        if (isset(explode('. ', $results[0]->htmlSnippet)[1])) {
                                            $answer->answer .= '. ' . explode('. ', $results[0]->htmlSnippet)[1] . '.';
                                        }
                                        $answer->answer .= "<br><a href='" . $results[0]->link . "' target='_blank'>Read more...</a>";
                                        $answer->question_id = $question->id;
                                    } else {
                                        $answer = new Answer;
                                        $answer->answer = "Even the internet doesn't know that...";
                                        $answer->question_id = $question->id;

                                        return json_encode(['answer' => $answer, 'question' => $question]);
                                    }
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
