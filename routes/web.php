<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return "Welocome to my website with Laravel13";
});

Route::get('/about', function () {
    return "About page";
});

Route::get('/blog', function () {
    return "Blog page";
});

Route::get('/blog/{id}', function ($id) {
    return "Blog id is {$id}";
});

Route::get('/admin', function () {
    return "Admin pages";
});
