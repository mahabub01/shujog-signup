<?php



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

// Route::middleware('auth:api')->get('/agent', function (Request $request) {
//     return $request->user();
// });

Route::post('load-district-by-division',[\Modules\Core\Http\Controllers\LocationApi\LocationController::class,'getDistrictForSignUp']);
Route::post('load-upazila-by-district',[\Modules\Core\Http\Controllers\LocationApi\LocationController::class,'getUpazilaForSignUp']);
Route::post('load-union-by-upazila',[\Modules\Core\Http\Controllers\LocationApi\LocationController::class,'getUnionForSignUp']);
Route::post('load-village-by-union',[\Modules\Core\Http\Controllers\LocationApi\LocationController::class,'getVillageForSignUp']);



//For Dashboard
Route::post('dashboard-load-upazila-by-district',[\Modules\Agent\Http\Controllers\LocationApi\LocationController::class,'dashboardLoadUpazilaByDistrict']);
Route::post('dashboard-load-district-by-division',[\Modules\Agent\Http\Controllers\LocationApi\LocationController::class,'dashboardLoadDistrictByDivision']);



//For Stakeholder List By Product Id
Route::post('pro-stkholder-load-upazila-by-district',[\Modules\Agent\Http\Controllers\LocationApi\LocationController::class,'stkLoadUpazilaByDistrict']);
Route::post('pro-stkholder-load-district-by-division',[\Modules\Agent\Http\Controllers\LocationApi\LocationController::class,'stkLoadDistrictByDivision']);

