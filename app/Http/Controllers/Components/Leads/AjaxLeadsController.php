<?php

namespace App\Http\Controllers\Components\Leads;

use App\Models\Accounts\AccountEntity;
use App\Models\Activities\TodoCollection;
use App\Models\Leads\LeadCollection;
use App\Models\Leads\LeadEntity;
use App\Models\Leads\LeadGroupCampaignMap;
use App\Models\Leads\LeadGroupEntity;
use App\Models\Leads\LeadGroupMap;
use App\Models\Leads\LeadGroupsCollection;
use App\Models\Leads\LeadNotes;
use App\Models\Leads\LeadSource;
use App\Models\Leads\LeadStatusMap;
use App\Models\Leads\LeadTypes;
use App\Models\Locations\Countries;
use App\Models\Marketing\ActionTriggerMap;
use App\Models\Marketing\CampaignActionCollection;
use App\Models\Marketing\CampaignActionEntity;
use App\Models\Marketing\CampaignCollection;
use App\Models\Users\UserEntity;
use Helpers\Layout;
use Illuminate\Http\Request;

class AjaxLeadsController{

    public function getLeads( Request $r )
    {

        $leads = new LeadCollection();
        $r->request->add([ 'assigned_to' => $r->user()->id ]);

        return [
            'success' => true,
            'leads' => $leads->getCollection( $r ),
            'total' => $leads->getTotal(),
            'offset' => $leads->getOffset(),
            'limit' => $leads->getLimit(),
            'page_count' => $leads->getPageCount( true )
        ];
    }

    public function saveLead( Request $r )
    {

        $lead = new LeadEntity();
        if( ! $lead->store( $r ) ){
            return [
                'success' =>false,
                'message' => $lead->displayErrors()
            ];
        }

        $account = AccountEntity::me();
        // get all marketing campaigns that match lead credentials

        $campaigns = (new CampaignCollection)->getByLeadCredentials( $lead );

        $r->merge( ['primaryKeyValue' => $lead->leadid , 'assigned_to' => $lead->assigned_to  ] );
        $lead = ( new LeadCollection )->getCollection( $r );

        return [
            'success' => true,
            'lead' => $lead,
            'campaigns' => $campaigns
        ];
    }
    
    public function getLeadCampaigns( Request $r )
    {
        $lead = LeadEntity::find( $r->leadid );
        $campaigns = (new CampaignCollection)->getByLeadCredentials( $lead );
        return [
            'success' =>true ,
            'campaigns' => $campaigns
        ];
    }

    public function addLeadGroupsToCampaigns( Request $r )
    {
        if( !  $r->cbcampaigns ){
            return [
                'success' => false,
                'message' => 'No campaigns selected'
            ];
        }

        foreach( $r->cbcampaigns as $c  ){
            // check duplicates
            $map =  new LeadGroupCampaignMap;
            if( $map->isDuplicate( $r->group_id , $c ) ){
                continue;
            }

            $map->group_id = $r->group_id;
            $map->campaign_id = $c;
            $map->save();


        }

        return [
            'success' =>true,
            'r' => $r->all()
        ];
    }

    /**
     * Add lead to campaigns
     * @param Request $r
     * @return array
     */
    public function addLeadToCampaigns( Request $r )
    {
        if( ! $r->campaignid ){
            return [
                'success' => false,
                'message' => 'Please select a campaign to add'
            ];
        }

        $campaignids = $r->campaignid;

        // get all actions of selected campaigns
        $r->merge([ 'deleted' => 0 ]);
        $actions = ( new CampaignActionCollection )->getCollection( $r );

        $duplicate = 0;
        $exceeds_sending_date = 0;
        $success_count = 0;

        $now = time();

        foreach( $actions as $a ){
            $action_map = new ActionTriggerMap();

            $r->merge( [ 'action' => $a ]);

            $sending_date = strtotime( $a->sending_specific_datetime ) ? strtotime( $a->sending_specific_datetime ) : 0;

            // check if sending date is already from the past
            // skip if true
            if( $a->sending_type == 'specific_date' &&  $now > $sending_date ){
                $exceeds_sending_date++;
                continue;
            }

            if( ! $action_map->store( $r ) ){
                $duplicate++;
                continue;
            }
            $success_count++;
        }
        return [
            'success' =>true,
            'campaignids' => $campaignids,
            'actions' => $actions,
            'duplicate' => $duplicate,
            'success_count' => $success_count,
            'exceeds_sending_date' => $exceeds_sending_date
        ];
    }

    public function deleteLead( Request $r )
    {

        $lead = LeadEntity::find( $r->lid );

        if( !$lead ){
            return [
                'success' =>false,
                'message' => 'Lead not found',
                'leadid' => $r->lid
            ];
        }

        if( $lead->brokerid != AccountEntity::me()->brokerid ){
            return [
                'success' =>false,
                'message' => 'Access denied. You can not delete leads'
            ];
        }

        $allowed_to_delete = [ $lead->ownerid , $lead->assigned_to, AccountEntity::me()->userid ];

        if( ! in_array(  UserEntity::me()->id, $allowed_to_delete  ) ){
            return [
                'success' =>false,
                'message' => 'You are not allowed to delete this lead'
            ];
        }

        $lead->deleted = 1;
        $lead->save();

        return [
            'success' => true
        ];

    }

    public function init( Request $r )
    {
        $countries = ( new Countries() )->getCollection( $r );
        $lead_status = ( new LeadStatusMap() )->getCollection( $r );
        $lead_types = (new LeadTypes() )->getCollection( $r );
        $lead_sources = (new LeadSource() )->getCollection( $r );

        return [
            'success' =>true,
            'countries' => $countries,
            'lead_status' => $lead_status,
            'lead_types' => $lead_types,
            'lead_sources' => $lead_sources
        ];

    }
    
    public function saveGroup( Request $r )
    {
        $brokerid   = UserEntity::me()->getAccountId();
        $group      = new LeadGroupEntity();
        $r->merge(['brokerid' => $brokerid ]);

        if( ! $group->store( $r ) ){
            return [
                'success' => false,
                'message' => $group->displayErrors()
            ];
        }

        return [
            'success' =>true ,
            'group' => $group
        ];
    }
    
    public function getGroups( Request $r )
    {
        $account_id =  UserEntity::me()->getAccountId() ;
        $groups = new LeadGroupsCollection();
        $r->merge( [ 'brokerid' => $account_id , 'order_by' => 'group_name' ] );

        return [
            'success'   => true,
            'groups'     => $groups->getCollection( $r )
        ];
    }

    public function saveLeadsToGroups( Request $r )
    {
        if( ! $r->l ){
            return [
                'success' => false,
                'message' => 'No lead to add to a group'
            ];
        }

        if( ! $r->group_id ){
            return [
                'success' => false,
                'message' => 'No group to add to a lead'
            ];
        }

        $duplicates = 0;
        $added = 0;

        $leadid_arr = [];

        foreach( $r->l as $l ){
            // check if lead is aleready associated with the group
            $map = (new LeadGroupMap() )->exists( $l , $r->group_id );
            if( ! $map ){
                $map = new LeadGroupMap();
                $map->leadid = $l;
                $map->group_id = $r->group_id;
                $map->save();
                $added++;
                $leadid_arr[] = $l;
            }else{
                $duplicates++;
            }

        }

        // add leads to group campaigns
        if( $r->algc && count( $leadid_arr )){

            // get all campaigns of the group
            $r->merge( [ 'with_campaign' => true ] );
            $campaign_map = ( new LeadGroupCampaignMap )->getCollection( $r )->keyBy( 'campaign_id' );

            $campaign_ids = [];
            $triggers = [];

            if( $campaign_map ){
                $campaign_ids   = $campaign_map->pluck( 'campaign_id' )->toArray();

                // set up triggers
                foreach( $campaign_map as $map ){
                    $std = new \stdClass();
                    $std->typeid = $map->typeid;
                    $std->statusid = $map->statusid;

                    $triggers[ $map->campaign_id ] = $std;
                }

                $leads = LeadCollection::whereIn( 'leadid' , $leadid_arr )->get();
                // match leads base on campaign triggers
                foreach( $leads as $l ){

                    $matched_campaigns = [];

                    foreach( $triggers as $k => $t ){
                        if( ( $t->statusid == 0 || $t->statusid == $l->status ) && ( $t->typeid == 0 || $t->statusid == $l->status  )  ){

                            $actions = $campaign_map[ $k ]->actions;
                            foreach( $actions as $a ){
                                if( $a->sending_type == 'field_date' ){
                                    // skip field dates for now
                                    continue;
                                }
                                $req = new Request();
                                $req->merge(['leadid' => $l->leadid , 'action' => $a , 'actionid' => $a->actiond ]);

                                ( new ActionTriggerMap )->store( $req );
                            }
                        }
                    }

                }
            }



        }

        return [
            'success' => true,
            'success_count' => $added,
            'group_count' => count( $r->g ),
            'duplicates' => $duplicates
        ];
    }

    public function deleteGroup( Request $r )
    {
        $group = (new LeadGroupEntity)->f( $r->gid );
        if( ! $group ){
            return [
                'success' => false,
                'message' => 'Invalid group map id'
            ];
        }
        // check if group brokerid is the same as the current user
        if( AccountEntity::me()->brokerid != $group->brokerid ){
                return [
                    'success' => false,
                    'message' => 'You are not allowed to delete this group'
                ];
        }
        $group->delete();

        // delete lead group map entries
        LeadGroupMap::where( 'group_id' , $r->gid )
            ->delete();

        return [
            'success' =>true
        ];
    }
    
    public function getGroupMembers( Request $r )
    {
        $groups = new LeadGroupMap();
        $r->merge([ 'group_id' => $r->gid ]);
        return [
            'success' =>true,
            'members' => $groups->getCollection( $r )
        ];
    }
    
    public function getAccountCampaigns( Request $r )
    {
        $campaigns= (new CampaignCollection)->getCollection( $r );

        return [
            'success' =>true,
            'campaigns' =>$campaigns
        ];
    }

    public function summary( Request $r )
    {
        $leadid = $r->leadid;
        $notes = LeadNotes::byLeadid( $leadid , ['vuetify' => true ] );
        $todos = new TodoCollection( );



        return [
            'success' => true,
            'notes' => $notes,
            'todos' => $todos->getCollection( $r )
        ];
    }
}