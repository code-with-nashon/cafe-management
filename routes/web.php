<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::livewire('/post/create', 'pages::post.create');

Route::get('/checkout', function () {
    return view('checkout-page');
});
