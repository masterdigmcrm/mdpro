<?php


Route::get('', 'LeadsController@index');
Route::get('profile/{leadid}', 'LeadsController@lead');