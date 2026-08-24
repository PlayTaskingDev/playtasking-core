<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\LogApiRequest;

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
    LogApiRequest::class,
    'api.client',
])->prefix('v1')->group(function () {
    Route::get('/reports/getCountUsers', [App\Http\Controllers\Api\Reports::class, 'getCountUsers'])->name('getCountUsers');
    Route::get('/reports/getTotalParticipatingUsers', [App\Http\Controllers\Api\Reports::class, 'getTotalParticipatingUsers'])->name('getTotalParticipatingUsers');
    Route::get('/reports/getConversionRegisterToParticipate', [App\Http\Controllers\Api\Reports::class, 'conversionRegisterToParticipate'])->name('getConversionRegisterToParticipate');
    Route::get('/reports/getTotalInteractions', [App\Http\Controllers\Api\Reports::class, 'totalInteractions'])->name('getTotalInteractions');
    Route::get('/reports/getTotalInteractionsAverage', [App\Http\Controllers\Api\Reports::class, 'totalInteractionsAverage'])->name('getTotalInteractionsAverage');
    Route::get('/reports/getTotalUsedGame', [App\Http\Controllers\Api\Reports::class, 'totalUsedGames'])->name('getTotalUsedGames');
    Route::get('/reports/getTotalMostUsedGames', [App\Http\Controllers\Api\Reports::class, 'totalMostUsedGames'])->name('getTotalMostUsedGames');
    Route::get('/reports/getInteractionsByGame', [App\Http\Controllers\Api\Reports::class, 'interactionsByGame'])->name('getInteractionsByGame');
    Route::get('/reports/getUniqueUsersByGame', [App\Http\Controllers\Api\Reports::class, 'uniqueUsersByGame'])->name('getUniqueUsersByGame');
    Route::get('/reports/getTotalAwardsWon', [App\Http\Controllers\Api\Reports::class, 'totalAwardsWon'])->name('getTotalAwardsWon');
    Route::get('/reports/getTotalParticipantsWithoutAward', [App\Http\Controllers\Api\Reports::class, 'totalParticipantsWithoutAward'])->name('getTotalParticipantsWithoutAward');
    Route::get('/reports/getConversionParticipantsWithAward', [App\Http\Controllers\Api\Reports::class, 'conversionParticipantsWithAward'])->name('getConversionParticipantsWithAward');
    Route::get('/reports/getAverageResolutionTimePerGame', [App\Http\Controllers\Api\Reports::class, 'averageResolutionTimePerGame'])->name('getAverageResolutionTimePerGame');
    Route::get('/reports/getMinimumResolutionTimePerGame', [App\Http\Controllers\Api\Reports::class, 'minimumResolutionTimePerGame'])->name('getMinimumResolutionTimePerGame');
    Route::get('/reports/getMaximumResolutionTimePerGame', [App\Http\Controllers\Api\Reports::class, 'maximumResolutionTimePerGame'])->name('getMaximumResolutionTimePerGame');
    //fase 2
    Route::get('/reports/getLeastUsedGame', [App\Http\Controllers\Api\Reports::class, 'leastUsedGame'])->name('getLeastUsedGame');
    Route::get('/reports/getAverageRegistrationTime', [App\Http\Controllers\Api\Reports::class, 'averageRegistrationTime'])->name('getAverageRegistrationTime');
    Route::get('/reports/getPeakRegistrationHour', [App\Http\Controllers\Api\Reports::class, 'peakRegistrationHour'])->name('getPeakRegistrationHour');
    Route::get('/reports/getPeakInteractionHour', [App\Http\Controllers\Api\Reports::class, 'peakInteractionHour'])->name('getPeakInteractionHour');
    Route::get('/reports/getRegistrationsByHour', [App\Http\Controllers\Api\Reports::class, 'registrationsByHour'])->name('getRegistrationsByHour');
    Route::get('/reports/getInteractionsByHour', [App\Http\Controllers\Api\Reports::class, 'interactionsByHour'])->name('getInteractionsByHour');
    Route::get('/reports/getRegistrationsByDay', [App\Http\Controllers\Api\Reports::class, 'registrationsByDay'])->name('getRegistrationsByDay');
    Route::get('/reports/getInteractionsByDay', [App\Http\Controllers\Api\Reports::class, 'interactionsByDay'])->name('getInteractionsByDay');
    Route::get('/reports/getMostActiveWeekday', [App\Http\Controllers\Api\Reports::class, 'mostActiveWeekday'])->name('getMostActiveWeekday');
    Route::get('/reports/getNewUsersMonth', [App\Http\Controllers\Api\Reports::class, 'newUsersMonth'])->name('newUsersMonth');
    Route::get('/reports/getRegistrationsByWeek', [App\Http\Controllers\Api\Reports::class, 'registrationsByWeek'])->name('getRegistrationsByWeek');
    //Route::get('/reports/getRegistrationsByMonth', [App\Http\Controllers\Api\Reports::class, 'registrationsByMonth'])->name('getRegistrationsByMonth');
    Route::get('/reports/getUserGrowth', [App\Http\Controllers\Api\Reports::class, 'userGrowth'])->name('getUserGrowth');
    Route::get('/reports/getSummary', [App\Http\Controllers\Api\Reports::class, 'summary'])->name('getSummary');

});

