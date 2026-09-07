<?php

use App\Http\Controllers\Api\EmergencyController;
use Illuminate\Support\Facades\Route;

Route::post('/emergencies', [EmergencyController::class, 'store']);
Route::get('/test-routing', [EmergencyController::class, 'testRouting']);
Route::get('/test-orchestrator', [EmergencyController::class, 'testOrchestrator']);