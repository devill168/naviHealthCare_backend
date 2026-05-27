<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CommuneController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\DistrictController;
use App\Http\Controllers\API\FindLocationController;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\API\HcController;
use App\Http\Controllers\API\HealthFacilityController;
use App\Http\Controllers\API\HealthFacilityVisitorController;
use App\Http\Controllers\API\OdController;
use App\Http\Controllers\API\ProvinceController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\VillageController;
use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BiometricController;




Route::post('/login', [AuthController::class, 'login']); // Login Form

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']); // Reset Password
Route::post('/reset-password', [ForgotPasswordController::class, 'reset']);


// Role routes
Route::post('/register', [RegisterController::class, 'store']); // Insert Data in form register
Route::get('/registers', [RegisterController::class, 'index']); // Get Data in form register
Route::put('/registers/{id}', [RegisterController::class, 'update']); // Update Data in from register
Route::delete('/registers/{id}', [RegisterController::class, 'destroy']); // Delete Data in from register


// Role routes
Route::get('/roles', [RoleController::class, 'index']);
Route::post('/roles', [RoleController::class, 'store']);
Route::put('/roles/{id}', [RoleController::class, 'update']);
Route::delete('/roles/{id}', [RoleController::class, 'destroy']);

Route::get('/find-location/search', [FindLocationController::class, 'search']);

// Province routes
Route::get('/provinces', [ProvinceController::class, 'index']);
Route::get('/get-provinces', [ProvinceController::class, 'getProvince']);
Route::get('/provinces/next-code', [ProvinceController::class, 'nextCode']);
Route::post('/provinces', [ProvinceController::class, 'store']);
Route::put('/provinces/{id}', [ProvinceController::class, 'update']);
Route::delete('/provinces/{id}', [ProvinceController::class, 'destroy']);


// District routes
Route::get('/districts', [DistrictController::class, 'index']);
Route::get('/get-districts', [DistrictController::class, 'getDistrict']);
Route::get('/districts/next-code/{province_code}', [DistrictController::class, 'nextCode']);
Route::post('/districts', [DistrictController::class, 'store']);
Route::put('/districts/{id}', [DistrictController::class, 'update']);
Route::delete('/districts/{id}', [DistrictController::class, 'destroy']);

// Commune routes
Route::get('/communes', [CommuneController::class, 'index']);
Route::get('/get-communes', [CommuneController::class, 'getCommunes']);
Route::get('/communes/next-code/{district_code}', [CommuneController::class, 'nextCode']);
Route::post('/communes', [CommuneController::class, 'store']);
Route::put('/communes/{id}', [CommuneController::class, 'update']);
Route::delete('/communes/{id}', [CommuneController::class, 'destroy']);

// OD routes
Route::get('/ods', [OdController::class, 'index']);
Route::get('/ods/next-code/{commune_code}', [OdController::class, 'nextCode']);
Route::post('/ods', [OdController::class, 'store']);
Route::put('/ods/{id}', [OdController::class, 'update']);
Route::delete('/ods/{id}', [OdController::class, 'destroy']);

// HC routes
Route::get('/health-centers', [HcController::class, 'index']);
Route::get('/health-centers/next-code/{od_code}', [HcController::class, 'nextCode']);
Route::post('/health-centers', [HcController::class, 'store']);
Route::put('/health-centers/{id}', [HcController::class, 'update']);
Route::delete('/health-centers/{id}', [HcController::class, 'destroy']);


// Village routes
Route::get('/villages', [VillageController::class, 'index']);
Route::get('/get-villages', [VillageController::class, 'getVillage']);
Route::get('/villages/next-code/{hc_code}', [VillageController::class, 'nextCode']);
Route::post('/villages', [VillageController::class, 'store']);
Route::get('/villages/{id}', [VillageController::class, 'show']);
Route::put('/villages/{id}', [VillageController::class, 'update']);
Route::delete('/villages/{id}', [VillageController::class, 'destroy']);
Route::get('/villages/next-code/{commune_code}', [VillageController::class, 'nextCode']);


//Health Facilities
Route::prefix('health-facilities')->group(function () {
    Route::get('/', [HealthFacilityController::class, 'index']);
    Route::post('/', [HealthFacilityController::class, 'store']);
    Route::get('/find-location', [HealthFacilityController::class, 'findLocation']);
    Route::get('/{healthFacility}', [HealthFacilityController::class, 'show']);
    Route::put('/{healthFacility}', [HealthFacilityController::class, 'update']);
    Route::delete('/{healthFacility}', [HealthFacilityController::class, 'destroy']);
});
//dashboard
Route::get('/dashboard', [DashboardController::class, 'index']);

//Visitor HF
Route::prefix('health-facility-visitors')->group(function () {
    Route::get('/', [HealthFacilityVisitorController::class, 'index']);
    Route::post('/', [HealthFacilityVisitorController::class, 'store']);
    Route::get('/{healthFacilityVisitor}', [HealthFacilityVisitorController::class, 'show']);
    Route::put('/{healthFacilityVisitor}', [HealthFacilityVisitorController::class, 'update']);
    Route::patch('/{healthFacilityVisitor}', [HealthFacilityVisitorController::class, 'update']);
    Route::delete('/{healthFacilityVisitor}', [HealthFacilityVisitorController::class, 'destroy']);
});


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/biometric/register', [BiometricController::class, 'register']);
Route::post('/biometric/login', [BiometricController::class, 'login']);
Route::post('/biometric/disable', [BiometricController::class, 'disable'])
    ->middleware('auth:sanctum');