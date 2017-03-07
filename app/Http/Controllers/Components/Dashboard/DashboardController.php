<?php

namespace App\Http\Controllers\Components\Dashboard;

use App\Http\Controllers\Components\ComponentsBaseController;


class DashboardController extends ComponentsBaseController{

    public function __construct( )
    {
        parent::__construct();
    }

    public function index()
    {
        $this->layout->content = view('dashboard.dashboard')->render();
        return $this->layout;
    }

    public static function recentActivities()
    {
        return view( 'dashboard.recent_activities' );
    }

}