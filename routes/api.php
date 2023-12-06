<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CapabilityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OdbsController;
use App\Http\Controllers\OltController;
use App\Http\Controllers\OnuController;
use App\Http\Controllers\OnuTypesController;
use App\Http\Controllers\SpeedProfileController;
use App\Http\Controllers\SnmpController;
use App\Http\Controllers\VpnTunnelController;
use App\Http\Controllers\ZoneController;
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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth.key', 'auth:sanctum'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'dashboard']);
    Route::get('/dashboard/showByOlt/{olt_id}', [DashboardController::class, 'showByOlt']);

    Route::get('/locations/listing', [ZoneController::class, 'getData']);
    Route::post('/locations', [ZoneController::class, 'store']);
    Route::get('/locations/{id}', [ZoneController::class, 'show']);
    Route::patch('/locations/{id}', [ZoneController::class, 'update']);
    Route::delete('/locations/{id}', [ZoneController::class, 'destroy']);

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
    Route::get('/onus_unconfigured', [OnuController::class, 'onusUnconfigureds']);
    Route::get('/onus/get_all_status/{external_id}', [OnuController::class, 'getOnuFullStatus']);
    Route::get('/onus/get_running_config/{external_id}', [OnuController::class, 'getOnuRunningConfig']);
    Route::post('/onus_imports', [OnuController::class, 'importOnus']);

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
    Route::get('/get_olts_uptime_and_env_temperature', [OltController::class, 'getOltTemperature']);
    Route::get('/get/hardware', [OltController::class, 'getHardware']);
    Route::get('/get/software', [OltController::class, 'getSoftware']);
    Route::get('/get/uplinks/{id}', [OltController::class, 'getUplinks']);
    Route::get('/get/vlans/{id}', [OltController::class, 'getVlans']);
    Route::get('/get/pon/{id}', [OltController::class, 'getPONPort']);

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

    Route::get('/get/snmp/portData/{id}', [SnmpController::class, 'ponPortsData']);
    Route::get('/get/snmp/uplink/{id}', [SnmpController::class, 'uplinkRegister']);
    Route::get('/get/snmp/oltcard/{id}', [SnmpController::class, 'oltCardRegister']);
    Route::get('/get/snmp/{id}', [SnmpController::class, 'vlanRegister']);

    Route::get('logout', [AuthController::class, 'logout']);
});
