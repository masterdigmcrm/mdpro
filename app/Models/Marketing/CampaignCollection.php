<?php

namespace App\Models\Marketing;


use App\Models\Accounts\AccountEntity;
use App\Models\Leads\LeadEntity;
use App\Models\Users\UserEntity;
use Illuminate\Http\Request;

class CampaignCollection extends CampaignEntity{

    public function getCollection( Request $r )
    {
        $user_id = $r->user()->id;
        $account = AccountEntity::me();

        $this->setLpo( $r );
        $this->fields = [ 'a.*' ];

        $this->query = static::from( $this->table.' as a' )
            ->whereRaw( "published = '1' " )
            ->whereRaw( "deleted = '0' " );

        $this->query->leftjoin( 'jos_mdigm_marketing_triggers as t', 't.campaignid' , '=', 'a.campaignid'  );

        if( $r->status ){
            $this->query->where( 't.statusid' , $r->status );
        }
        if( $r->typeid ){
            $this->query->where( 't.typeid' , $r->typeid );
        }

        if( $r->with_actions ){
            $this->query->with(['actions' => function( $query ){
                $query->whereRaw( " deleted = '0' " );
                $query->whereRaw( " published = '1' " );
            }]);
        }

        if( $account->userid != $user_id ){

            $account_userid = $account->userid;
            $this->query->where( function( $qry) use ( $user_id , $account_userid){
                $qry->where( function($q) use ( $user_id , $account_userid ) {
                    $q->where( 'ownerid' , $account_userid )
                        ->where( 'availability' , 'public' );
                })->orWhere( 'ownerid' , $user_id );
            });

        }else{
            $this->query->where( 'ownerid' , $user_id );
        }

        if( $r->with_total ){
            $this->total = $this->query->count();
        }

        $this->assignLpo();
        return $this->vuefyThisCollection();
    }

    public function getByLeadCredentials( LeadEntity $lead )
    {
        $user_id = UserEntity::me()->id;
        $account = AccountEntity::me();

        $query = static::from( $this->table.' as a' )
            ->where( 'published' , '1' )
            ->where( 'deleted', '0' );

        $query->join( 'jos_mdigm_marketing_triggers as t', 't.campaignid' , '=', 'a.campaignid'  );

        if( $lead->status ){
            $query->where( 't.statusid' , $lead->status );
        }
        if( $lead->typeid ){
            $query->where( 't.typeid' , $lead->typeid );
        }

        if( $account->userid != $user_id ){

            $account_userid = $account->userid;
            $query->where( function( $qry) use ( $user_id , $account_userid){
                $qry->where( function($q) use ( $user_id , $account_userid ) {
                    $q->where( 'ownerid' , $account_userid )
                        ->where( 'availability' , 'public' );
                })->orWhere( 'ownerid' , $user_id );
            });

        }else{
            $query->where( 'ownerid' , $user_id );
        }

        $campaigns = $query->get();

        return $campaigns;
    }

}