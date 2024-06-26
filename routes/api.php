<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});



Route::post("checkAgent", [ApiController::class, "checkAgent"]);
Route::post("getAgent", [ApiController::class, "getAgent"]);
Route::post("updateAgentPIN", [ApiController::class, "updateAgentPIN"]);

Route::post("createRegion", [ApiController::class, "createRegion"]);
Route::post("getRegions", [ApiController::class, "getRegions"]);
Route::post("createLocality", [ApiController::class, "createLocality"]);
Route::post("getLocalities", [ApiController::class, "getLocalities"]);
Route::post("getLocality", [ApiController::class, "getLocality"]);
Route::post("getRegionLocalities", [ApiController::class, "getRegionLocalities"]);


Route::post("updateAgentCurrentLocality", [ApiController::class, "updateAgentCurrentLocality"]);
Route::post("updateAgentSelectedLocality", [ApiController::class, "updateAgentSelectedLocality"]);
Route::post("updateAgentPhotoUrl", [ApiController::class, "updateAgentPhotoUrl"]);
Route::post("updateAgentDetails", [ApiController::class, "updateAgentDetails"]);

Route::post("getOutletCategories", [ApiController::class, "getOutletCategories"]);

Route::group(["middleware" => ["auth:api"]], function(){
  //Route::post("checkAccount", [ApiController::class, "checkAccount"]);
  //Route::get("tenant", [ApiController::class, "tenant"]);
  //Route::get("manager", [ApiController::class, "manager"]);
  //Route::post("regions", [ApiController::class, "regions"]);
  //Route::post("localities", [ApiController::class, "localities"]);
});
