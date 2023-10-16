<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SummaryController;

Route::get('/', function () {
    return view('index');
});

// SUMMARY DATA 
Route::get('/summarize-data', [SummaryController::class, 'summary'])->name('summary');
Route::post('/summarize-data-t', [SummaryController::class, 'summaryData'])->name('summary-data');


// Your authentication routes (if needed)
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
