<?php

use App\Http\Controllers\DocsController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::view('/business.html', 'business')->name('business');
Route::view('/help.html', 'help')->name('help');
Route::view('/pricing.html', 'pricing')->name('pricing');
Route::view('/about.html', 'about')->name('about');
Route::view('/compliance.html', 'compliance')->name('compliance');
Route::view('/privacy.html', 'privacy')->name('privacy');
Route::view('/terms.html', 'terms')->name('terms');

Route::get('/contact.html', [ContactController::class, 'show'])->name('contact');
Route::post('/contact.html', [ContactController::class, 'store'])->name('contact.store');

// robots.txt and sitemap.xml are served directly from public/ by the webserver,
// no routes needed for them.

Route::get('/docs', [DocsController::class, 'index'])->name('docs.index');
Route::get('/docs/{section}/{page}', [DocsController::class, 'show'])
    ->where(['section' => '[a-z0-9-]+', 'page' => '[a-z0-9-]+'])
    ->name('docs.show');
