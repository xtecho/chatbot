<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/chat', function () {
    return view('chat');
})->name('chat');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/add-answer', 'AnswersController@insert')->name('add-answer');

Route::prefix('/questions')->group(function () {
    Route::get('', 'QuestionsController@show')->name('show-questions');
    Route::get('/{question}', 'QuestionsController@getAnswers')->name('get-answers');
    Route::post('/answer/{answer}', 'QuestionsController@editAnswer')->name('edit-answer');
});