<?php

Route::prefix('{website}')->middleware(['customer', 'auth:customer', 'can:view,website'])->group(function () {
    Route::prefix('analytics')->as('analytics.')->group(function () {
        Route::get('index', 'AnalyticsController@index')
            ->name('index');
    });
});
