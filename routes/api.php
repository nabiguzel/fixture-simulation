<?php

use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamMatchController;
use Illuminate\Support\Facades\Route;

Route::prefix('teams')->group(function(){
    Route::get('/', [TeamController::class, 'index']);
    Route::get('/points', [TeamController::class, 'getTeamsWithPoints']);
    Route::delete('/reset-data', [TeamController::class, 'resetData']);
});

Route::prefix('team-matches')->group(function(){
    Route::get('/', [TeamMatchController::class, 'index']);
    Route::post('/', [TeamMatchController::class, 'store']);
    Route::get('/next-week', [TeamMatchController::class, 'getNextWeek']);
    Route::put('/next-week', [TeamMatchController::class, 'playNextWeek']);
    Route::put('/play-all-weeks', [TeamMatchController::class, 'playAllWeeks']);
});