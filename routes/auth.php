<?php

use App\Http\Controllers\Auth\Login;

Route::get('login', [Login::class, 'showFormLogin'])->name(LOGIN_ROUTE);
Route::post('login', [Login::class, 'login']);
Route::middleware('auth')->get('/logout', [Login::class, 'logout'])->name(LOGOUT_ROUTE);
