<?php

use App\Http\Controllers\analytics\Activities;
use App\Http\Controllers\analytics\Sales;
use App\Http\Controllers\apps\agents\Agents;
use App\Http\Controllers\apps\outlets\ActiveOutlets;
use App\Http\Controllers\apps\outlets\DisabledOutlets;
use App\Http\Controllers\apps\outlets\NewOutlets;
use App\Http\Controllers\apps\outlets\OutletCategories;
use App\Http\Controllers\apps\places\Localities;
use App\Http\Controllers\apps\places\Regions;
use App\Http\Controllers\apps\products\Products;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\pages\MiscError;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/storage-link', function () {
  try {
    Artisan::call('storage:link');
    return "Storage linked";
  } catch (\Exception $e) {
    return 'Error executing storage linking: ' . $e->getMessage();
  }
});

Route::get('/clear-cache', function () {
  try {
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return "Cache is cleared";
  } catch (\Exception $e) {
    return 'Error executing cache clearing : ' . $e->getMessage();
  }

});

Route::get('/migrate', function () {
  try {
    Artisan::call('migrate', [
      '--force' => false, // Add this if you want to force the migration
    ]);
    return 'Migrations successfully executed.';
  } catch (\Exception $e) {
    return 'Error executing migrations: ' . $e->getMessage();
  }
});

// locale
Route::get('/lang/{locale}', [LanguageController::class, 'swap']);
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');


Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->group(function () {
  // Main Page Route
  Route::get('/', [Activities::class, 'index'])->name('index');
  Route::get('/analytics/activities', [Activities::class, 'index'])->name('analytics-activities');
  Route::get('/analytics/sales', [Sales::class, 'index'])->name('analytics-sales');

  Route::get('/apps/agents', [Agents::class, 'AgentManagement'])->name('agents');
  Route::patch('agents-list/{id}/activation', [Agents::class, 'activation']);
  Route::get('/apps/agents/agent/{id}', [Agents::class, 'agent'])->name('apps-agents-agent');
  Route::resource('/agents-list', Agents::class);

  Route::get('/apps/regions', [Regions::class, 'RegionManagement'])->name('regions');
  Route::resource('/regions-list', Regions::class);

  Route::get('/apps/localities', [Localities::class, 'LocalityManagement'])->name('localities');
  Route::resource('/localities-list', Localities::class);

  Route::get('/apps/products', [Products::class, 'ProductManagement'])->name('products');
  Route::patch('products-list/{id}/activation', [Products::class, 'activation']);
  Route::get('/apps/products/product/{id}', [Products::class, 'product'])->name('apps-products-product');
  Route::resource('/products-list', Products::class);

  Route::get('/apps/outlets/active', [ActiveOutlets::class, 'ActiveOutletManagement'])->name('active-outlets');
  Route::patch('active-outlets-list/{id}/activation', [ActiveOutlets::class, 'activation']);
  Route::patch('active-outlets-list/{id}/verification', [ActiveOutlets::class, 'verification']);
  Route::get('/apps/outlets/active/outlet/{id}', [ActiveOutlets::class, 'outlet'])->name('apps-outlets-active-outlet');
  Route::resource('/active-outlets-list', ActiveOutlets::class);

  Route::get('/apps/outlets/new', [NewOutlets::class, 'NewOutletManagement'])->name('new-outlets');
  Route::patch('new-outlets-list/{id}/activation', [NewOutlets::class, 'activation']);
  Route::patch('new-outlets-list/{id}/verification', [NewOutlets::class, 'verification']);
  Route::get('/apps/outlets/new/outlet/{id}', [NewOutlets::class, 'outlet'])->name('apps-outlets-new-outlet');
  Route::resource('/new-outlets-list', NewOutlets::class);

  Route::get('/apps/outlets/disabled', [DisabledOutlets::class, 'DisabledOutletManagement'])->name('disabled-outlets');
  Route::patch('disabled-outlets-list/{id}/activation', [DisabledOutlets::class, 'activation']);
  Route::patch('disabled-outlets-list/{id}/verification', [DisabledOutlets::class, 'verification']);
  Route::get('/apps/outlets/disabled/outlet/{id}', [DisabledOutlets::class, 'outlet'])->name('apps-outlets-disabled-outlet');
  Route::resource('/disabled-outlets-list', DisabledOutlets::class);

  Route::get('/apps/outlets/categories', [OutletCategories::class, 'OutletCategoryManagement'])->name('outlets-categories');
  Route::patch('outlets-categories-list/{id}/status', [OutletCategories::class, 'status']);
  Route::get('/apps/outlets/categories/category/{id}', [OutletCategories::class, 'category'])->name('apps-outlets-outlets-category');
  Route::resource('/outlets-categories-list', OutletCategories::class);

});
