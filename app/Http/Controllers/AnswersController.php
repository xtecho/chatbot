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
            // daca tipul de cerere este de tip raspuns atunci salveaza-l pentru intrebarea respectiva daca nu a mai fost adaugat
            $params = array();
            $params['answer'] = $request->input('answer');
            $params['question_id'] = empty($request->input('question_id')) ? 1 : $request->input('question_id');
            $answer = Answer::firstOrCreate($params);

            return json_encode(['answer' => $answer, 'question' => $answer->question]);
        } else {
            if (empty($request->input('answer_teach'))) {
                // daca cerinta nu este de tip invatare
                if (Auth::guest()) {
                    // pentru utilizatorii neautentificati se salveaza pe sesiune cate intrebari a pus
                    $questions = Session::get('questions');
                    if (empty($questions)) {
                        Session::put('questions', 1);
                        $questions = 1;
                    } else {
                        Session::put('questions', ++$questions);
                    }
                    Session::save();
                    if ($questions > 3) {
                        // daca numarul de intrebari este mai mare ca 3 atunci se face redirect la pagina de login
                        return json_encode(['redirect' => true]);
                    }
                }
                $params = array();
                $params['question'] = strtolower($request->input('answer'));

                // se cauta intrebarea si daca nu exista se creeaza
                $question = Question::firstOrCreate($params);
                // incrementam contorul care numara de cate ori a fost solicitata intrebarea
                $question->counter++;
                $question->save();

                // selecteaza toate cuvintele urate
                $badwords = Badword::all()->pluck('word');
                foreach ($badwords as $badword) {
                    // verifica daca intrebarea contine cuvinte interzise
                    if (strpos(strtolower($params['question']), $badword) !== false) {
                        $answer = new Answer;
                        $answer->answer = "That is a bad word... I don't like bad words :(";
                        $answer->question_id = $question->id;

                        return json_encode(['answer' => $answer, 'question' => $question]);
                    }
                }

                if (!empty($question)) {
                    // furnizeaza un raspuns random al intrebarii (daca exista raspunsuri)
                    $answers = $question->answers;
                    $answer = $answers->isEmpty() ? null : $answers->random();

                    if (empty($answer)) {
                        // daca in intrebare se regaseste cuvantul 'weather'
                        if (strpos(strtolower($params['question']), 'weather') !== false) {
                            // separam intrebarea in cuvinte
                            $words = explode(' ', strtolower($params['question']));
                            $country = '';
                            foreach ($words as $key => $word) {
                                // pentru fiecare cuvant verificam daca este 'weather'
                                if ($word == 'weather') {
                                    // in caz afirmativ adauga in variabila numele locatiei
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

                            try {
                                // apeleaza API-ul de vreme cu locatia curenta
                                $lowm = new LaravelOWM();
                                $current_weather = $lowm->getCurrentWeather($country);

                                $answer = new Answer;
                                $answer->answer = 'Weather in ' . ucfirst($country) . '? It\'s ' . $current_weather->temperature->now->getValue() . ' degrees celsius.';
                                $answer->question_id = $question->id;
                            } catch (\Exception $e) {
                                // daca exista vreo problema cu API-ul se returneaza un raspuns predefinit
                                $answer = new Answer;
                                $answer->answer = 'I don\'t know...';
                                $answer->question_id = $question->id;
                            }
                        } else {
                            // se selecteaza toate cuvintele cheie din baza de date
                            $keywords = Keyword::all()->pluck('name');
                            foreach ($keywords as $keyword) {
                                // pentru fiecare cuvant cheie se cauta daca exista in intrebare
                                if (strpos(strtolower($params['question']), $keyword) !== false) {
                                    try {
                                        // se foloseste motorul de cautare personalizat pentru a cauta un raspuns pe internet
                                        $seachEngine = new LaravelGoogleCustomSearchEngine();
                                        $results = $seachEngine->getResults($params['question'], ['num' => 1]);
                                        if (!empty($results)) {
                                            // in cazul in care se gaseste cel putin un raspuns atunci se ia prima varianta
                                            $answer = new Answer;
                                            // se prelucreaza snippet-ul pentru a afisa decat primele 2 propozitii
                                            $answer->answer = explode('. ', $results[0]->htmlSnippet)[0];
                                            if (isset(explode('. ', $results[0]->htmlSnippet)[1])) {
                                                $answer->answer .= '. ' . explode('. ', $results[0]->htmlSnippet)[1] . '.';
                                            }
                                            // se adauga un link catre pagina de unde a fost gasit raspunsul
                                            $answer->answer .= "<br><a href='" . $results[0]->link . "' target='_blank'>Read more...</a>";
                                            $answer->question_id = $question->id;
                                        } else {
                                            // daca nu exista nici un raspuns pentru intrebare atunci se returneaza unul predefinit
                                            $answer = new Answer;
                                            $answer->answer = "Even the internet doesn't know that...";
                                            $answer->question_id = $question->id;

                                            return json_encode(['answer' => $answer, 'question' => $question]);
                                        }
                                    } catch (\Exception $e) {
                                        // daca exista o eroare atunci se returneaza un raspuns predefinit
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

                // se returneaza catre functia de javascript raspunsul astfel generat
                return json_encode(['answer' => $answer, 'question' => $question]);
            } else {
                // daca cerinta este de tip invatare
                $answer = new Answer;

                // se salveaza raspunsul pentru intrebare
                $answer->answer = $request->input('answer_teach');
                $answer->question_id = empty($request->input('question_id')) ? 1 : $request->input('question_id');
                $answer->save();

                return json_encode(['answer' => $answer, 'question' => $answer->question]);
            }
        }
    }

}
