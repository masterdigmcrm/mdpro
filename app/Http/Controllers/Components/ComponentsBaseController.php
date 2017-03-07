<?php

namespace App\Http\Controllers\Components;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SideBarsController;
use App\Http\Models\Accounts\AccountEntity;
use App\Http\Models\Users\UserEntity;


class ComponentsBaseController extends Controller{

    protected $layout;
    protected $user;

    protected $account;

    public function __construct( $theme = NULL )
    {
        $theme = $theme  ? $theme : env( 'THEME' ) ;

        view()->addLocation( app_path().'/Http/Views/'.$theme );
        view()->addLocation( base_path().'/resources/views/themes/'.$theme );

        $this->layout = view( 'layouts.layout_default' );
        $this->layout->sidebar = SideBarsController::render( 'admin' );
    }

}