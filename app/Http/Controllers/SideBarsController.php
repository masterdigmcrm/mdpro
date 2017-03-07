<?php

namespace App\Http\Controllers;


class SideBarsController{

    public static function render( $user_type )
    {
        return view( 'sidebars.sidebar_default' )->render();
    }

}