<?php

use App\Http\Controllers\analytics\Activities;
use App\Http\Controllers\analytics\Sales;
use App\Http\Controllers\apps\agents\Agents;
use App\Http\Controllers\apps\outlets\ActiveOutlets;
use App\Http\Controllers\apps\outlets\DisabledOutlets;
use App\Http\Controllers\apps\outlets\NewOutlets;
use App\Http\Controllers\apps\outlets\Categories;
use App\Http\Controllers\apps\places\Localities;
use App\Http\Controllers\apps\places\Regions;
use App\Http\Controllers\apps\products\Products;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\pages\MiscError;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/storage-link', function () {
  Artisan::call('storage:link');
  return "Storage linked";
});

Route::get('/clear-cache', function () {
  Artisan::call('config:cache');
  Artisan::call('cache:clear');
  Artisan::call('config:clear');
  Artisan::call('view:clear');
  Artisan::call('route:clear');
  return "Cache is cleared";
})->name('clear.cache');

// locale
Route::get('/lang/{locale}', [LanguageController::class, 'swap']);
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');


Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->group(function () {
  // Main Page Route
  Route::get('/', [Activities::class, 'index'])->name('index');
  Route::get('/analytics/activities', [Activities::class, 'index'])->name('analytics-activities');
  Route::get('/analytics/sales', [Sales::class, 'index'])->name('analytics-sales');

  Route::get('/apps/agents', [Agents::class, 'AgentManagement'])->name('agents');
  Route::patch('agents-list/{id}/status', [Agents::class, 'status']);
  Route::get('/apps/agents/agent/{id}', [Agents::class, 'agent'])->name('apps-agents-agent');
  Route::resource('/agents-list', Agents::class);

  Route::get('/apps/regions', [Regions::class, 'RegionManagement'])->name('regions');
  Route::resource('/regions-list', Regions::class);

  Route::get('/apps/localities', [Localities::class, 'LocalityManagement'])->name('localities');
  Route::resource('/localities-list', Localities::class);

  Route::get('/apps/products', [Products::class, 'ProductManagement'])->name('products');
  Route::patch('products-list/{id}/status', [Products::class, 'status']);
  Route::get('/apps/products/product/{id}', [Products::class, 'product'])->name('apps-products-product');
  Route::resource('/products-list', Products::class);

  Route::get('/apps/outlets/active', [ActiveOutlets::class, 'OutletManagement'])->name('active-outlets');
  Route::patch('outlets-list/{id}/status', [ActiveOutlets::class, 'status']);
  Route::get('/apps/outlets/active/outlet/{id}', [ActiveOutlets::class, 'outlet'])->name('apps-outlets-active-outlet');
  Route::resource('/active-outlets-list', ActiveOutlets::class);

  Route::get('/apps/outlets/new', [NewOutlets::class, 'OutletManagement'])->name('new-outlets');
  Route::patch('new-outlets-list/{id}/status', [NewOutlets::class, 'status']);
  Route::get('/apps/outlets/new/outlet/{id}', [NewOutlets::class, 'outlet'])->name('apps-outlets-new-outlet');
  Route::resource('/new-outlets-list', NewOutlets::class);

  Route::get('/apps/outlets/disabled', [DisabledOutlets::class, 'OutletManagement'])->name('disabled-outlets');
  Route::patch('disabled-outlets-list/{id}/status', [DisabledOutlets::class, 'status']);
  Route::get('/apps/outlets/disabled/outlet/{id}', [DisabledOutlets::class, 'outlet'])->name('apps-outlets-disabled-outlet');
  Route::resource('/disabled-outlets-list', DisabledOutlets::class);

});
