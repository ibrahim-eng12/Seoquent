<?php

use IbrahimEng12\Seoquent\Http\Controllers\RobotsController;
use IbrahimEng12\Seoquent\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

if (config('seoquent.sitemap.enabled')) {
    Route::get(config('seoquent.sitemap.route', 'sitemap.xml'), SitemapController::class)
        ->name('seoquent.sitemap');
}

if (config('seoquent.robots.enabled')) {
    Route::get(config('seoquent.robots.route', 'robots.txt'), RobotsController::class)
        ->name('seoquent.robots');
}
