<?php

namespace App\Http\Controllers\Navigation;

class NavigationController {

    public static function NavLeft()
    {
        return view('navigation.nav_left')->render();
    }
}