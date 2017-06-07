<?php

namespace App\Http\Controllers\Components\Marketing;

use App\Http\Controllers\Components\ComponentsBaseController;
use App\Http\Controllers\Controller;
use App\Models\Accounts\AccountEntity;
use App\Models\Leads\LeadCollection;
use App\Models\Leads\LeadEntity;
use App\Models\Users\UserEntity;
use Helpers\Layout;
use Illuminate\Http\Request;

class MarketingController extends ComponentsBaseController{

    public function __construct()
    {
        parent::__construct();
    }

    public function index( Request $r )
    {
        $account = AccountEntity::me();

        $this->layout->content = view( 'marketing.viewmarketing' , compact( 'account' ) );

        Layout::instance()->addScript( '/app/marketing/viewmarketing.js' );
        Layout::loadlodash();
        Layout::loadCkeditor();

        return $this->layout;
    }
    
    public function postcards( Request $r )
    {
        $content = view( 'marketing.viewpostcards' )->render();
        $this->layout->content = $content;
        Layout::loadVue();
        Layout::loadFileupload();
        Layout::instance()->addScript( '/app/marketing/viewpostcards.js');

        return $this->layout;
    }

}