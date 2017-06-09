<?php

namespace App\Models\Leads;

use App\Models\Accounts\AccountEntity;
use App\Models\BaseModel;
use App\Models\Marketing\CampaignActionEntity;
use App\Models\Marketing\CampaignEntity;
use Illuminate\Http\Request;

class LeadGroupCampaignMap extends BaseModel{

    protected $table = 'jos_mdigm_lead_group_campaign_map';
    protected $primaryKey = 'map_id';
    public $timestamps = false;

    public function store( Request $r )
    {
        $validator = \Validator::make( $r->all() , [
            // validation rules here
        ] );

        if( $validator->fails() ){
            $this->errors = $validator->errors()->all();
            return false;
        }

        $this->fill( $r->all() );
        $pk = $this->primaryKey;

        if( $r->$pk  ){
            $this->exists = true;
        }else{

        }

        $this->save();

        return $this;
    }

    public function isDuplicate( $group_id , $campaign_id )
    {
        return static::where( 'group_id' , $group_id )
            ->where('campaign_id' , $campaign_id )
            ->first();
    }

    public function getCollection( Request $r )
    {
        $this->setLpo( $r );
        $this->fields   = [ 'a.*', 'c.campaign_name',  't.typeid', 't.statusid' , 't.andor' ];

        $this->query   = static::from( $this->table.' as a' )
            ->join( 'jos_mdigm_marketing_campaigns as c' , 'c.campaignid' , '=' ,'a.campaign_id')
            ->join( 'jos_mdigm_marketing_triggers as t' , 't.campaignid' , '=' ,'a.campaign_id')
            ->with( ['actions']);

        if( $r->group_id ){
            $this->query->where( 'group_id', $r->group_id  );
        }
        if( $r->campaign_id ){
            $this->query->where( 'campaign_id', $r->campaign_id  );
        }

        $this->total = $this->query->count();
        $this->assignLpo();

        return $this->query->get( $this->fields );
    }

    public function campaign()
    {
        return $this->hasOne( CampaignEntity::class , 'campaign_id', 'campaignid' );
    }

    public function actions()
    {
        return $this->hasMany( CampaignActionEntity::class, 'campaignid' ,'campaign_id' );
    }

}