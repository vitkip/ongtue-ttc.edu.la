<?php

use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ApplyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/courses', CourseController::class)->name('courses');
Route::get('/events', EventController::class)->name('events');
Route::get('/about-us', AboutUsController::class)->name('about-us');
Route::get('/contact', ContactController::class)->name('contact');
Route::get('/apply', ApplyController::class)->name('apply');
Route::get('/students', StudentController::class)->name('students');

Route::post('/inquiries', [InquiryController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('inquiries.store');
Route::post('/applications', [ApplicationController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('applications.store');
