<?php

namespace App\Http\Controllers\Components\Marketing;


use App\Models\Accounts\AccountEntity;
use App\Models\Leads\LeadStatusMap;
use App\Models\Leads\LeadTypes;
use App\Models\Marketing\ActionTriggerMap;
use App\Models\Marketing\CampaignActionCollection;
use App\Models\Marketing\CampaignActionEntity;
use App\Models\Marketing\CampaignCollection;
use App\Models\Marketing\CampaignEntity;
use App\Models\Marketing\CampaignTriggers;
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
        $r->merge( ['ownerid' => $r->user()->id ,
            'with_actions' => true , 'with_trigger' => true,
            'order_by' => 'campaign_name'] );

        $lead_types     =  ( new LeadTypes() )->getCollection( $r );
        $lead_status    = ( new LeadStatusMap )->getCollection( $r );

        return [
            'success' =>true,
            'campaigns' => $campaigns->getCollection( $r ),
            'lead_types' => $lead_types,
            'lead_status' => $lead_status
        ];
    }
    
    public function initHistory( Request $r )
    {

    }

    public function getHistory( Request $r )
    {
        $r->merge( ['with_total' => true ] );
        $history_entries =   new ActionTriggerMap ;

        $collection =  $history_entries->getCollection( $r );

        return [
            'success' =>true,
            'entries' => $collection,
            'total' => $history_entries->getTotal(),
            'page_count' => $history_entries->getPageCount( true ),
            'total_pages' => $history_entries->getPageCount(),

        ];
    }

    public function saveCampaign( Request $r )
    {
        $campaign = new CampaignEntity();

        if( $r->campaignid  ){
            $campaign = CampaignEntity::f( $r->campaignid );
        }

        if( ! $campaign->store( $r ) ){
            return [
                'success' => false,
                'message' => $campaign->displayError()
            ];
        }

        if( ! $trigger = CampaignTriggers::byCampaignId( $campaign->campaignid ) ){
            $r->merge(['campaignid' => $campaign->campaignid ]);
            $trigger = new CampaignTriggers();
        }

        $trigger->store( $r );


        return [
            'success' => true,
            'campaign' => $campaign
        ];

    }

    public function deleteCampaign( Request $r )
    {
        if( ! $campaign = CampaignEntity::f( $r->campaignid ) ){
            return [
                'success' =>false,
                'message' => ' Campaign not found'
            ];
        }

        $campaign->deleted = '1';
        $campaign->save();

        // cancel all actions on queue
        $actionid = ( new CampaignActionCollection )->getCollection( $r )->pluck('actionid')->toArray();
        $updated = [];

        if( count( $actionid )){
            $updated = ( new ActionTriggerMap )->cancelByActionId( $actionid );
        }

        return [
            'success'  => true,
            'campaign' => $campaign,
            'updated' => $updated
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
    
    public function deleteAction( Request $r )
    {
        $action = ( new CampaignActionEntity())->f( $r->actionid );

        if( ! $action ){
          return [
              'success' =>false,
              'message' => 'Campaign action not found'
          ];
        }

        if( $action->ownerid != $r->user()->id ){
            return [
                'success' => false,
                'message' => 'You are not allowed to delete action'
            ];
        }

        $action->deleted = 1;
        $action->save();

        // cancel all emails on queue for this action
        $updated = ( new ActionTriggerMap )->cancelByActionId( $r->actionid );

        return [
            'success' =>true,
            'updated' => $updated,
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