<?php

if( \Request::segment(2) == 'postcards'){
    Route::group( [ 'prefix' => 'postcards', 'namespace' => 'Components\Postcards' ], function(){

        Route::get( '', 'PostcardsAjaxController@getPostcards' );
        Route::get( 'properties', 'PostcardsAjaxController@getPostcardProperties' );
        Route::post( '', 'PostcardsAjaxController@savePostcard' );
        Route::delete( '', 'PostcardsAjaxController@deletePostcard' );
        Route::post( 'upload/{section}', 'PostcardsAjaxController@uploadPostcard' );


    });
    return;
}

if( \Request::segment(2) == 'properties'){
    Route::group( [ 'prefix' => 'properties', 'namespace' => 'Components\Properties' ], function(){
        Route::get( 'get' , 'AjaxPropertiesController@getCollection' );
        Route::get( 'search' , 'AjaxPropertiesController@getCollection' );
        Route::get( 'init' , 'AjaxPropertiesController@init' );
    });

    return;
}


if( \Request::segment(2) == 'property'){
    Route::group( [ 'prefix' => 'property', 'namespace' => 'Components\Properties' ], function(){
        Route::post( 'save' , 'AjaxPropertiesController@store' );
        Route::post( 'photo/upload' , 'AjaxPropertiesController@photoUpload' );
        Route::post( 'photo/delete' , 'AjaxPropertiesController@photoDelete' );
        Route::post( 'photo/setprimary' , 'AjaxPropertiesController@setPrimary' );
    });

    return;
}

if( \Request::segment(2) == 'lead'){
    Route::group( [ 'prefix' => 'lead', 'namespace' => 'Components\Leads' ], function(){
        Route::post( 'addtocampaigns' , 'AjaxLeadsController@addLeadToCampaigns' );
        Route::post( 'groups/campaigns' , 'AjaxLeadsController@addLeadGroupsToCampaigns' );
    });
}

if( \Request::segment(2) == 'account'){
    Route::group( [ 'prefix' => 'account', 'namespace' => 'Components\Leads' ], function(){
        Route::get( 'campaigns' , 'AjaxLeadsController@getAccountCampaigns' );
    });
}

if( \Request::segment(2) == 'leads'){
    Route::group( [ 'prefix' => 'leads', 'namespace' => 'Components\Leads' ], function(){
        Route::get( 'getleads' , 'AjaxLeadsController@getLeads' );
        Route::get( 'getgroups' , 'AjaxLeadsController@getGroups' );
        Route::get( 'search' , 'AjaxLeadsController@getLeads' );
        Route::get( 'summary' , 'AjaxLeadsController@summary' );
        Route::get( 'init' , 'AjaxLeadsController@init' );
        // get group members
        Route::get( 'ggm' , 'AjaxLeadsController@getGroupMembers' );
        Route::post( 'save/group' , 'AjaxLeadsController@saveGroup' );
        Route::post( 'sltg' , 'AjaxLeadsController@saveLeadsToGroups' );
        Route::post( 'savelead' , 'AjaxLeadsController@saveLead' );
        Route::post( 'deletelead' , 'AjaxLeadsController@deleteLead' );
        Route::post( 'delete/group' , 'AjaxLeadsController@deleteGroup' );

    });

    return;
}

if( \Request::segment(2) == 'settings'){
    Route::group( [ 'prefix' => 'settings', 'namespace' => 'Components\Settings' ], function(){
        Route::post( 'lob' , 'AjaxSettingsController@saveLobKey' );
        Route::get( 'init' , 'AjaxSettingsController@init' );
    });
}

if( in_array( \Request::segment(2) , [ 'marketing', 'campaign' , 'campaigns'] ) ){
    Route::group( [ 'prefix' => 'campaign', 'namespace' => 'Components\Marketing' ], function(){
        Route::post( '', 'MarketingAjaxController@saveCampaign' );
        Route::post( 'action', 'MarketingAjaxController@saveAction' );
        Route::delete( 'action', 'MarketingAjaxController@deleteAction' );
        Route::get( 'init', 'MarketingAjaxController@init' );
    });

    Route::group( [ 'prefix' => 'marketing', 'namespace' => 'Components\Marketing' ], function(){
        Route::get( 'postcards', 'MarketingAjaxController@getPostcards' );
    });


    return;
}

if( \Request::segment(2) == 'location'){
    Route::group( [ 'prefix' => 'location', 'namespace' => 'Components\Properties' ], function(){
        Route::get( 'states' , 'AjaxPropertiesController@getStates' );
        Route::get( 'cities' , 'AjaxPropertiesController@getCities' );
    });
}


