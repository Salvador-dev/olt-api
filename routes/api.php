<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CapabilityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OdbsController;
use App\Http\Controllers\OltController;
use App\Http\Controllers\OnuController;
use App\Http\Controllers\OnuTypesController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SpeedProfileController;
use App\Http\Controllers\SnmpController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VpnTunnelController;
use App\Http\Controllers\entity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\super_admin\SuperAdminController;
use App\Http\Middleware\Cors; // Agrega la importación de tu middleware Cors


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


Route::post('company', [entity::class, 'registered']);
Route::post('login', [entity::class, 'login']);
Route::get('company/{id}', [entity::class, 'show']);

