<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/threads/create', 'ThreadsController@create')->name('threads.create');
Route::get('/threads/{channel}/{thread}', 'ThreadsController@show')->name('threads.show');
//Route::get('/threads/{channel}', 'ThreadsController@index')->name('channel.threads');
Route::post('/threads', 'ThreadsController@store')->name('threads.store');
Route::post('/threads/{channel}/{thread}/replies', 'RepliesController@store')->name('replies.store');

Route::get('/threads/{channel?}', 'ThreadsController@index')->name('threads.index');

Route::post('/replies/{reply}/favorites', 'FavoritesController@store')->name('favorites.store');
