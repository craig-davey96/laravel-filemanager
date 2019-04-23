<?php

    Route::prefix('filemanager')->group(function () {

        Route::any('/', 'FilemanagerController@index');
        Route::any('/get', 'FilemanagerController@get');
        Route::any('/getMarkup', 'FilemanagerController@getMarkup');
        Route::post('/upload', 'FilemanagerController@upload');

    });

?>