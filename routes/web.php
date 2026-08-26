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
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// Serve files from the "public" storage disk directly.
// Used in production when PUBLIC_STORAGE_URL is set (see config/filesystems.php) —
// on hosts where symlink() is disabled, `php artisan storage:link` cannot create
// public/storage, so uploaded logos/photos/certificates are served through the
// app instead. The path is passed as a query string, not a URL segment, since
// some hosts' Apache config intercepts request paths ending in a static file
// extension (.png, .jpg, ...) and 404s them before Laravel ever sees the request.
Route::get('/media', function (\Illuminate\Http\Request $request) {
    $path = ltrim((string) $request->query('f', ''), '/');

    abort_unless($path !== '' && !str_contains($path, '..') && Storage::disk('public')->exists($path), 404);

    return Storage::disk('public')->response($path);
})->name('media.show');

// One-off maintenance endpoint for hosts where the terminal/SSH is not
// available. Runs Artisan commands in-process (Artisan::call does not use
// exec()/proc_open, so it works even where those are disabled). Guarded by
// a secret in .env (DEPLOY_SECRET) — set it once, then visit e.g.
//   https://your-domain.com/ops/optimize-clear?key=YOUR_SECRET
//   https://your-domain.com/ops/migrate?key=YOUR_SECRET
// Only whitelisted, safe-to-repeat commands are allowed on purpose.
Route::get('/ops/{action}', function (string $action, \Illuminate\Http\Request $request) {
    $secret = config('app.deploy_secret');
    abort_if(!$secret || !hash_equals($secret, (string) $request->query('key', '')), 404);

    $commands = [
        'cache-clear' => 'cache:clear',
        'config-clear' => 'config:clear',
        'route-clear' => 'route:clear',
        'view-clear' => 'view:clear',
        'optimize-clear' => 'optimize:clear',
        'config-cache' => 'config:cache',
        'route-cache' => 'route:cache',
        'migrate' => 'migrate',
    ];

    abort_unless(array_key_exists($action, $commands), 404);

    $exitCode = Artisan::call($commands[$action], $action === 'migrate' ? ['--force' => true] : []);

    return response("[$action] exit code: $exitCode\n\n".Artisan::output(), 200, ['Content-Type' => 'text/plain']);
})->name('ops.run');

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
