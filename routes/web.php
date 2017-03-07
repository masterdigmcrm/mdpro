<?php

Route::get('/', function () {
    return redirect( 'login' );
});

Route::get('login',  'FrontController@login' );
Route::post('login',  'FrontController@login' );
Route::get('logout',  'FrontController@logout' );

Route::group( ['prefix'=>'dashboard', 'middleware' => 'auth.md'], function(){
    Route::get('', 'Components\Dashboard\DashboardController@index');
});

if( \Request::segment(1) == 'component'){
    Route::group( ['prefix'=>'component', 'middleware' => 'auth.md'  ], function(){

        if( \Request::segment(2) == 'properties'){
            Route::group( ['prefix'=>'properties',  'namespace'=> 'Components\Properties' ], function(){
                require_once( __DIR__.'\web\route_properties.php');
            });
        }
        if( in_array( \Request::segment(2), [ 'leads' , 'lead' ]   ) ){
            Route::group( ['prefix'=>'leads',  'namespace'=> 'Components\Leads', 'middleware' => 'auth.md' ], function(){
                require_once( __DIR__.'\web\route_leads.php');
            });
        }
    });
}


if( \Request::segment(1) == 'admin'){

    Route::group( ['prefix'=>'admin', 'middleware' => 'auth.md'], function(){
        Route::get('properties', 'Components\Properties\PropertiesController@index');
    });
}

/*********** Ajax calls *************/

if( \Request::segment(1) == 'ajax'){
    Route::group( ['prefix'=>'ajax', 'middleware' => ['auth.md' , 'ajax'] ], function(){
        require_once( __DIR__.'\web\route_ajax.php');
    });
}
/**************************************/