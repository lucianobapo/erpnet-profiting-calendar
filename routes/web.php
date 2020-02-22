<?php
Route::group(['middleware' => 'web','language'], function () {
    
    
    Route::group(['middleware' => 'auth'], function () {
        Route::group(['middleware' => ['adminmenu', 'permission:read-admin-panel']], function () {
            
            Route::group(['prefix' => 'calendar', 'namespace'=>'\ErpNET\Profiting\Calendar\Http\Controllers'], function () {
                
                /*
                Route::resource('production', 'Productions', ['middleware' => ['dateformat', 'money']]);
                
                Route::post('production/import', 'Productions@import')->name('production.import');
                Route::get('production/export', 'Productions@export')->name('production.export');
                Route::get('production/{production}/duplicate', 'Productions@duplicate')->name('production.duplicate');
            */
            
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