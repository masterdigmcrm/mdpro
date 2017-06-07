<?php

namespace App\Http\Controllers\Components\Marketing;


use App\Models\Accounts\AccountEntity;
use App\Models\Marketing\CampaignActionEntity;
use App\Models\Marketing\CampaignCollection;
use App\Models\Marketing\CampaignEntity;
use App\Models\Postcards\PostcardCollection;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MarketingAjaxController extends Controller
{

    public function __construct()
    {
        // validate if ajax here
        //parent::__construct();
    }

    public function init( Request $r )
    {
        $campaigns = new CampaignCollection();
        $r->merge([ 'ownerid' => $r->user()->id , 'with_actions' => true ]);
        return [
            'success' =>true,
            'campaigns' => $campaigns->getCollection( $r )
        ];
    }

    public function saveCampaign( Request $r )
    {
        $campaign = new CampaignEntity();
        if( ! $campaign->store( $r ) ){
            return [
                'success' => false,
                'message' => $campaign->displayError()
            ];
        }

        return [
            'success' => true,
            'campaign' => $campaign
        ];
    }
    
    public function saveAction( Request $r )
    {
        $action = new CampaignActionEntity();
        if( ! $action->store( $r )){
            return [
                'success' => false,
                'message' => $action->displayErrors()
            ];
        }

        return [
            'success' =>true,
            'action' => $action
        ];
    }

    public function getPostcards( Request $r )
    {
        $account_id = AccountEntity::me()->brokerid;
        if( ! $account_id ){
            return [
                'success' => false,
                'message' => 'Account not found'
            ];
        }
        $r->merge([ 'account_id' => $account_id ]);

        $postcards = ( new PostcardCollection )->getCollection( $r);
        return [
            'success' => true,
            'postcards' => $postcards
        ];
    }
}