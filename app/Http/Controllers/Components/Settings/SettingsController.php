<?php

namespace App\Http\Controllers\Components\Settings;

use App\Http\Controllers\Components\ComponentsBaseController;
use Helpers\Layout;
use Illuminate\Http\Request;

class SettingsController extends ComponentsBaseController{

    public function index( Request $r )
    {
        Layout::instance()->addScript( '/app/settings/settings.js' );
        $this->layout->content = view( 'settings.settings_index' );
        return $this->layout;
    }

}