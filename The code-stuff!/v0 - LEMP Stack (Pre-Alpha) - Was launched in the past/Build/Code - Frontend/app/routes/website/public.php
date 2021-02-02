<?php


Route::get('/chat', function () {
	return view('website/public/landing', ['pageTitle' => 'Customer Support']);
})->name('public-chatLanding');


Route::get('/', function () {
	return Redirect::route('public-chatLanding');
})->name('public-home');


Route::get('/widget-credit', function () {
	return Redirect::route('public-home');
});
