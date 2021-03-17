<?php

use App\Http\Controllers\SocialSecurityNumberController;
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

Route::get('/validate/cnp', [SocialSecurityNumberController::class, 'getValidationView'])->name("get");
Route::post('/validate', [SocialSecurityNumberController::class, 'validate'])->name("validate");

