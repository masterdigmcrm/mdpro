<?php

namespace App\Http\Controllers\Components\Properties;

use App\Http\Controllers\Components\ComponentsBaseController;
use App\Models\Accounts\AccountEntity;
use Helpers\Layout;
use Illuminate\Http\Request;


class PropertiesController extends ComponentsBaseController{

    public function __construct()
    {
        parent::__construct();
    }

    public function index( Request $request )
    {
        Layout::instance()->addScript( '/app/properties/properties_index.js' );
        Layout::loadFileupload();
        $account = AccountEntity::me();

        //Layout::loadGoogleMap();
        $this->layout->content = view( 'properties.properties_index' , compact('account') );
        return $this->layout;
    }

    public function view()
    {

    }

}