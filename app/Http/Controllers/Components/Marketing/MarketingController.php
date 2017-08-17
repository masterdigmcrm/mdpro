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
        Layout::loadlodash();
        Layout::instance()->addScript( '/app/marketing/viewmarketing.js' );
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

    public function history( Request $r )
    {
        $content = view( 'marketing.history' )->render();
        $this->layout->content = $content;

        Layout::loadVue();
        Layout::loadJqueryUI();
        Layout::instance()->addScript( '/app/marketing/marketing_history.js');

        return $this->layout;
    }

}