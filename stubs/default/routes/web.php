<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

//Route::middleware(['auth:sanctum', 'verified'])->get(
//    '/admin/posts/{sublevels?}',
//    [\App\Http\Controllers\PostsController::class, 'getFrontView']
//)->name('admin/posts');
//
//Route::get('post', \App\Http\Livewire\Posts::class);


require_once(__DIR__.'/../vendor/eleganttechnologies/grok/routes/web.php');
require_once(__DIR__.'/../vendor/tallandsassy/page-guide/routes/web.php');
require_once(__DIR__.'/../vendor/tallandsassy/app-theme-base-admin/routes/web.php');
require_once(__DIR__.'/../vendor/tallandsassy/plugin-grok/routes/web.php');

/* Pending module incorporation
require_once(__DIR__.'/../modules/admin-users/routes/web.php');
require_once(__DIR__.'/../modules/admin-teams/routes/web.php');
*/
