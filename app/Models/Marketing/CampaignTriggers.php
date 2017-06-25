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

    public static function byCampaignId( $campaignid )
    {
        return static::where( 'campaignid' , $campaignid )->first();
    }

    public function store( Request $r )
    {
        $validator = \Validator::make( $r->all() , [

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
            $this->action       =  'add';
            $this->condition    =  'equals';
            $this->value    =  ' ';
            $this->andor    = 'and';
        }

        $this->save();

        return $this;
    }

}
