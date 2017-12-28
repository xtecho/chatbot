<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Answer;

class AnswersController extends Controller {

    public function insert(Request $request) {

        $addanswer = new Answer;

        $addanswer->answers = $request->input('answers');

        $addanswer->save();

        return redirect('home');
    }

}
