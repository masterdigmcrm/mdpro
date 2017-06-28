<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;

class UtilsController extends Controller {

    public function sendLetters( Request $r )
    {
        $l = new App\Console\Commands\SendLetters();
        dd( $l );
        //$l =;
        //$l->handle();

    }
}