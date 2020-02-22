<?php
Route::group(['middleware' => 'web','language'], function () {    
    Route::group(['middleware' => 'auth'], function () {
        Route::group(['middleware' => ['adminmenu', 'permission:read-admin-panel']], function () {
            
            Route::group(['prefix' => 'calendar', 'namespace'=>'\ErpNET\Profiting\Calendar\Http\Controllers'], function () {
                            
                //fullcalender
                Route::resource('fullcalendar', 'FullCalendarController');
                
                /*
                Route::get('fullcalendar','FullCalendarController@index'->name('production.duplicate'));
                Route::post('fullcalendar/create','FullCalendarController@create');
                Route::post('fullcalendar/update','FullCalendarController@update');
                Route::post('fullcalendar/delete','FullCalendarController@destroy');*/
            });
        });
    });	
});