<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;

// Redirect root to demande page for students
Route::get('/', function () {
    return redirect('/demande');
});
