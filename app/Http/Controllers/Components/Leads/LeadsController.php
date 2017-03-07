<?php

namespace App\Http\Controllers\Components\Leads;

use App\Http\Controllers\Components\ComponentsBaseController;
use App\Http\Controllers\Controller;
use App\Models\Accounts\AccountEntity;
use App\Models\Leads\LeadCollection;
use App\Models\Leads\LeadEntity;
use App\Models\Users\UserEntity;
use Helpers\Layout;
use Illuminate\Http\Request;

class LeadsController extends ComponentsBaseController{

    public function __construct()
    {
        parent::__construct();
    }

    public function index( Request $r )
    {
        $leads = LeadCollection::getByUserId( $r->user()->id );
        $account = AccountEntity::me();

        $this->layout->content = view( 'leads.viewleads' , compact( 'account' ) );
        Layout::instance()->addScript( '/app/leads/viewleads.js' );

        return $this->layout;
    }

    /**
     * lead profile page
     */
    public function lead( $leadid )
    {

        $lead = LeadEntity::find( $leadid );

        $this->layout->content = view( 'leads.viewlead' )
            ->with( 'leads', $lead )
            ->render();

        return $this->layout;

    }
}