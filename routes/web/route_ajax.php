<?php

if( \Request::segment(2) == 'properties'){
    Route::group( [ 'prefix' => 'properties', 'namespace' => 'Components\Properties' ], function(){
        Route::get( 'get' , 'AjaxPropertiesController@getCollection' );
        Route::get( 'search' , 'AjaxPropertiesController@getCollection' );
        Route::get( 'init' , 'AjaxPropertiesController@init' );
    });
}

if( \Request::segment(2) == 'property'){
    Route::group( [ 'prefix' => 'property', 'namespace' => 'Components\Properties' ], function(){
        Route::post( 'save' , 'AjaxPropertiesController@store' );
        Route::post( 'photo/upload' , 'AjaxPropertiesController@photoUpload' );
        Route::post( 'photo/delete' , 'AjaxPropertiesController@photoDelete' );
        Route::post( 'photo/setprimary' , 'AjaxPropertiesController@setPrimary' );
    });
}

if( \Request::segment(2) == 'leads'){
    Route::group( [ 'prefix' => 'leads', 'namespace' => 'Components\Leads' ], function(){
        Route::get( 'getleads' , 'AjaxLeadsController@getLeads' );
        Route::get( 'search' , 'AjaxLeadsController@getLeads' );
        Route::get( 'summary' , 'AjaxLeadsController@summary' );
        Route::get( 'init' , 'AjaxLeadsController@init' );
        Route::post( 'savelead' , 'AjaxLeadsController@saveLead' );
        Route::post( 'deletelead' , 'AjaxLeadsController@deleteLead' );
    });
}

if( \Request::segment(2) == 'location'){
    Route::group( [ 'prefix' => 'location', 'namespace' => 'Components\Properties' ], function(){
        Route::get( 'states' , 'AjaxPropertiesController@getStates' );
        Route::get( 'cities' , 'AjaxPropertiesController@getCities' );
    });
}


