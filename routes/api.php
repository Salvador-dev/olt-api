<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\CapabilityController;
use App\Http\Controllers\OdbsController;
use App\Http\Controllers\OltController;
use App\Http\Controllers\OnuController;
use App\Http\Controllers\OnuTypesController;
use App\Http\Controllers\SpeedProfileController;
use App\Http\Controllers\UnconfiguredController;
use App\Http\Controllers\VpnTunnelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/locations/listing', [ApiController::class, 'getData']);
Route::post('/locations', [ApiController::class, 'store']);
Route::get('/locations/{id}', [ApiController::class, 'show']);
Route::patch('/locations/{id}', [ApiController::class, 'update']);
Route::delete('/locations/{id}', [ApiController::class, 'destroy']);

Route::get('/odbs/listing', [OdbsController::class, 'getData']);
Route::post('/odbs', [OdbsController::class, 'store']);
Route::get('/odbs/{id}', [OdbsController::class, 'show']);
Route::patch('/odbs/{id}', [OdbsController::class, 'update']);
Route::delete('/odbs/{id}', [OdbsController::class, 'destroy']);

Route::get('/capabilities/listing', [CapabilityController::class, 'getData']);
Route::post('/capabilities', [CapabilityController::class, 'store']);
Route::get('/capabilities/{id}', [CapabilityController::class, 'show']);
Route::patch('/capabilities/{id}', [CapabilityController::class, 'update']);
Route::delete('/capabilities/{id}', [CapabilityController::class, 'destroy']);

Route::get('/onus/listing', [OnuController::class, 'getData']);
Route::post('/onus', [OnuController::class, 'store']);
Route::get('/onus/{id}', [OnuController::class, 'show']);
Route::patch('/onus/{id}', [OnuController::class, 'update']);
Route::delete('/onus/{id}', [OnuController::class, 'destroy']);
Route::get('/onus/showbyOlt/{olt}', [OnuController::class, 'showByOlt']);
Route::get('/onus', [OnuController::class, 'paginater']);


Route::get('/speed_profiles/listing', [SpeedProfileController::class, 'getData']);
Route::post('/speed_profiles', [SpeedProfileController::class, 'store']);
Route::get('/speed_profiles/{id}', [SpeedProfileController::class, 'show']);
Route::patch('/speed_profiles/{id}', [SpeedProfileController::class, 'update']);
Route::delete('/speed_profiles/{id}', [SpeedProfileController::class, 'destroy']);

Route::get('/olts/listing', [OltController::class, 'getData']);
Route::post('/olts', [OltController::class, 'store']);
Route::get('/olts/{id}', [OltController::class, 'show']);
Route::patch('/olts/{id}', [OltController::class, 'update']);
Route::delete('/olts/{id}', [OltController::class, 'destroy']);
Route::get('/olts', [OltController::class, 'paginater']);
Route::get('/olts/search/{search}', [OltController::class, 'search']);

Route::get('/onuTypes/listing', [OnuTypesController::class, 'getData']);
Route::post('/onuTypes', [onuTypesController::class, 'store']);
Route::get('/onuTypes/{id}', [onuTypesController::class, 'show']);
Route::patch('/onuTypes/{id}', [onuTypesController::class, 'update']);
Route::delete('/onuTypes/{id}', [onuTypesController::class, 'destroy']);

Route::get('/vpn-tunnels/listing', [VpnTunnelController::class, 'getData']);
Route::post('/vpn-tunnels', [VpnTunnelController::class, 'store']);
Route::get('/vpn-tunnels/{id}', [VpnTunnelController::class, 'show']);
Route::patch('/vpn-tunnels/{id}', [VpnTunnelController::class, 'update']);
Route::delete('/vpn-tunnels/{id}', [VpnTunnelController::class, 'destroy']);
