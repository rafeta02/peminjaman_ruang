<?php

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
    // Lantai
    Route::apiResource('lantais', 'LantaiApiController');

    // Ruang
    Route::post('ruangs/media', 'RuangApiController@storeMedia')->name('ruangs.storeMedia');
    Route::apiResource('ruangs', 'RuangApiController');

    // Pinjam
    Route::post('pinjams/media', 'PinjamApiController@storeMedia')->name('pinjams.storeMedia');
    Route::apiResource('pinjams', 'PinjamApiController');

    // Log Pinjam
    Route::apiResource('log-pinjams', 'LogPinjamApiController');
});
