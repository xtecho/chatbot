<?php
// pagina principala
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

// pagina de chat
Route::get('/chat', function () {
    return view('chat');
})->name('chat');


// rute pentru paginile de autentificare
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// adaugare/gasire/invatare raspuns
Route::post('/add-answer', 'AnswersController@insert')->name('add-answer');

// permisiuni de accesare decat pentru admini
Route::group(['prefix' => '/questions',  'middleware' => 'admin'], function()
{
    Route::get('', 'QuestionsController@show')->name('show-questions');
    Route::get('/delete/{question}', 'QuestionsController@deleteQuestion')->name('delete-question');
    Route::get('/{question}', 'QuestionsController@getAnswers')->name('get-answers');
    Route::post('/answer/add', 'QuestionsController@addAnswer')->name('add-answer');
    Route::get('/answer/delete/{answer}', 'QuestionsController@deleteAnswer')->name('delete-answer');
    Route::post('/answer/{answer}', 'QuestionsController@editAnswer')->name('edit-answer');
});

// rute pentru pagina de invatare
Route::prefix('/teach')->group(function () {
    Route::get('', 'QuestionsController@teachView')->name('teach');
    Route::post('/add', 'QuestionsController@teach')->name('add-teach');
});