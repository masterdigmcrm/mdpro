<?php

namespace App\Models\Marketing;

use App\Http\Models\Users\UserEntity;
use App\Http\Models\Users\UserMap;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CampaignTriggers extends Model{

    public $table = 'jos_mdigm_marketing_triggers';
    public $primaryKey = 'triggerid';

    public $timestamps = false;

    protected $fillable = [ 'campaignid', 'statusid', 'typeid' ];

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

}