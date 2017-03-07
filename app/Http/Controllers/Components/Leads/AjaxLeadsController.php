<?php

namespace App\Http\Controllers\Components\Leads;

use App\Models\Accounts\AccountEntity;
use App\Models\Activities\TodoCollection;
use App\Models\Leads\LeadCollection;
use App\Models\Leads\LeadEntity;
use App\Models\Leads\LeadNotes;
use App\Models\Leads\LeadSource;
use App\Models\Leads\LeadStatusMap;
use App\Models\Leads\LeadTypes;
use App\Models\Locations\Countries;
use App\Models\Users\UserEntity;
use Helpers\Layout;
use Illuminate\Http\Request;

class AjaxLeadsController{

    public function getLeads( Request $r )
    {
        $leads = new LeadCollection();
        $r->request->add(['ownerid' => UserEntity::me()->userMap->account->userid ]);
        return [
            'success' => true,
            'leads' => $leads->getCollection( $r ),
            'total' => $leads->getTotal(),
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

        $r->request->add(['primaryKeyValue' => $lead->leadid ]);
        $lead = ( new LeadCollection() )->getCollection( $r );

        return [
            'success' => true,
            'lead' => $lead
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