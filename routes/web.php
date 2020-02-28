<?php
Route::group(['middleware' => 'api'], function () {   
    Route::get('fullcalendar','FullCalendarController@index')->name('fullcalendar.index');
});
Route::group(['middleware' => 'web','language'], function () {    
    Route::group(['middleware' => 'auth'], function () {
        Route::group(['middleware' => ['adminmenu', 'permission:read-admin-panel']], function () {
            
            Route::group(['prefix' => 'calendar', 'namespace'=>'\ErpNET\Profiting\Calendar\Http\Controllers'], function () {
                            
                //fullcalender
                //Route::resource('fullcalendar', 'FullCalendarController');
                
                //*
                Route::get('fullcalendar','FullCalendarController@index')->name('fullcalendar.index');
                Route::post('fullcalendar/store','FullCalendarController@store')->name('fullcalendar.store');
                Route::post('fullcalendar/update','FullCalendarController@update')->name('fullcalendar.update');
                Route::post('fullcalendar/delete','FullCalendarController@destroy')->name('fullcalendar.destroy');
                //*/
            });
        });
    });	
});