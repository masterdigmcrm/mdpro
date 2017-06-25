<?php

namespace App\Models\Marketing;

use App\Http\Models\Users\UserEntity;
use App\Http\Models\Users\UserMap;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CampaignEntity extends BaseModel{

    public $table = 'jos_mdigm_marketing_campaigns';
    public $primaryKey = 'campaignid';

    public $timestamps = false;

    protected $fillable = [ 'campaign_description', 'campaign_name', 'availability' , 'published' , 'campaign_type' ];

    public function store( Request $r )
    {
        $validator = \Validator::make( $r->all() , [
            'campaign_name' => 'required'
        ] );

        if( $validator->fails() ){
            $this->errors = $validator->errors()->all();
            return false;
        }

        $this->fill( $r->all() );
        $pk = $this->primaryKey;

        $this->campaign_type = $r->campaign_type ? $r->campaign_type : 'drip';

        if( $r->$pk  ){
            $this->exists = true;
        }else{
            $this->date_created = date( 'Y-m-d H:i:s' );
            $this->ownerid = $r->user()->id;
            $this->deleted = '0';
        }

        $this->save();

        return $this;
    }

    public function actions( )
    {
        return $this->hasMany( CampaignActionEntity::class, 'campaignid' , 'campaignid' );
    }

}