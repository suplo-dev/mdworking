<?php

use App\Enums\PermissionEnum;
use App\Http\Controllers\Campaign\CampaignFacebookController;
use App\Http\Controllers\Campaign\CampaignGoogleController;
use App\Http\Controllers\Campaign\Google\AddAdsGoogleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\PermissionMiddleware;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/ve-mdworking', function () {
    return view('welcome');
})->name('about');

Route::get('/tin-tuc', function () {
    return view('welcome');
})->name('news');

Route::get('/mang-luoi', function () {
    return view('welcome');
})->name('network');

Route::get('/tuyen-dung', function () {
    return view('welcome');
})->name('career');

Route::get('/dashboard', function () {
    return view('dashboard',);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::group(['prefix' => 'user'], function () {
        Route::get('search', [UserController::class, 'search'])->middleware(PermissionMiddleware::using(PermissionEnum::VIEW_USER))->name('user.index');
        Route::get('add', [UserController::class, 'view'])->middleware(PermissionMiddleware::using(PermissionEnum::ADD_USER))->name('user.add');
        Route::post('add', [UserController::class, 'add'])->middleware(PermissionMiddleware::using(PermissionEnum::ADD_USER))->name('user.add');
        Route::get('{user}', [UserController::class, 'detail'])->middleware(PermissionMiddleware::using(PermissionEnum::VIEW_USER))->name('user.detail');
        Route::put('{user}', [UserController::class, 'update'])->middleware(PermissionMiddleware::using(PermissionEnum::UPDATE_USER))->name('user.update');
    });
    Route::group(['prefix' => 'campaign'], function () {
        Route::group(['prefix' => 'google'], function () {
            Route::get('search', [CampaignGoogleController::class, 'search'])->middleware(PermissionMiddleware::using(PermissionEnum::VIEW_ADS_GG))->name('campaign.google.index');
            Route::get('add', [CampaignGoogleController::class, 'view'])->middleware(PermissionMiddleware::using(PermissionEnum::ADD_ADS_GG))->name('campaign.google.add');
            Route::post('add', [CampaignGoogleController::class, 'add'])->middleware(PermissionMiddleware::using(PermissionEnum::ADD_ADS_GG))->name('campaign.google.add');
            Route::get('detail', [CampaignGoogleController::class, 'detail'])->middleware(PermissionMiddleware::using(PermissionEnum::VIEW_ADS_GG))->name('campaign.google.detail');
            Route::group(['prefix' => 'ads'], function () {
                Route::get('', [CampaignGoogleController::class, 'indexAds'])->middleware(PermissionMiddleware::using(PermissionEnum::ADD_ADS_GG))->name('campaign.google.ads.index');
                Route::post('add', [CampaignGoogleController::class, 'addAds'])->middleware(PermissionMiddleware::using(PermissionEnum::ADD_ADS_GG))->name('campaign.google.ads.add');
            });
        });
        Route::group(['prefix' => 'facebook'], function () {
            Route::get('search', [CampaignFacebookController::class, 'search'])->middleware(PermissionMiddleware::using(PermissionEnum::VIEW_ADS_FB))->name('campaign.facebook.index');
            Route::get('add', [CampaignFacebookController::class, 'addView'])->middleware(PermissionMiddleware::using(PermissionEnum::ADD_ADS_FB))->name('campaign.facebook.add');
            Route::post('add', [CampaignFacebookController::class, 'add'])->middleware(PermissionMiddleware::using(PermissionEnum::ADD_ADS_FB))->name('campaign.facebook.add');
            Route::post('update', [CampaignFacebookController::class, 'updateStatus'])->middleware(PermissionMiddleware::using(PermissionEnum::UPDATE_ADS_FB))->name('campaign.facebook.update');
        });
    });
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::put('/profile/changePassword', [ProfileController::class, 'changePassword'])->name('profile.changePassword');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
