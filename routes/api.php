<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware([
    'auth:sanctum',
    'api.client'
])->prefix('v1')->group(function () {
    Route::get('/reports/getUsers', [App\Http\Controllers\Api\Reports::class, 'getUsers'])->name('getUsers');
    Route::get('/reports/getActiveCampaign', [App\Http\Controllers\Api\Reports::class, 'getActiveCampaign'])->name('getActiveCampaign');
    Route::get('/reports/getActiveGames/{slug}', [App\Http\Controllers\Api\Reports::class, 'getActiveGames'])->name('getActiveGames');
    Route::get('/reports/getInteractionsByGame/{game_id}', [App\Http\Controllers\Api\Reports::class, 'getInteractionsByGame'])->name('getInteractionsByGame');
    Route::get('/reports/getInteractionsByUser/{user_email}', [App\Http\Controllers\Api\Reports::class, 'getInteractionsByUser'])->name('getInteractionsByUser');
    Route::get('/reports/getTotalCoupons', [App\Http\Controllers\Api\Reports::class, 'getTotalCoupons'])->name('getTotalCoupons');
    Route::get('/reports/getAwardByCode/{code}', [App\Http\Controllers\Api\Reports::class, 'getAwardByCode'])->name('getAwardByCode');
});

