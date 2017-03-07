<?php

namespace App\Http\Controllers;

use Illuminate\View;

class FrontBaseController extends Controller{

    public function  __construct()
    {
        view()->addLocation( base_path().'/resources/views/themes/'.env('THEME') );
    }

}