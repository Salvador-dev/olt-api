<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CapabilityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DummyController;
use App\Http\Controllers\OdbsController;
use App\Http\Controllers\OltController;
use App\Http\Controllers\OnuController;
use App\Http\Controllers\OnuTypesController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SpeedProfileController;
use App\Http\Controllers\SnmpController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VpnTunnelController;
use App\Http\Controllers\ZoneController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\entity;



Route::post('empresa', [entity::class, 'registered']);


