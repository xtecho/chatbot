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

Route::group(['prefix' => '/questions',  'middleware' => 'admin'], function()
{
    Route::get('', 'QuestionsController@show')->name('show-questions');
    Route::get('/delete/{question}', 'QuestionsController@deleteQuestion')->name('delete-question');
    Route::get('/{question}', 'QuestionsController@getAnswers')->name('get-answers');
    Route::post('/answer/add', 'QuestionsController@addAnswer')->name('add-answer');
    Route::get('/answer/delete/{answer}', 'QuestionsController@deleteAnswer')->name('delete-answer');
    Route::post('/answer/{answer}', 'QuestionsController@editAnswer')->name('edit-answer');
});

Route::prefix('/teach')->group(function () {
    Route::get('', 'QuestionsController@teachView')->name('teach');
    Route::post('/add', 'QuestionsController@teach')->name('add-teach');
});