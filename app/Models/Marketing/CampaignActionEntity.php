<?php

namespace App\Models\Marketing;

use App\Http\Models\Users\UserEntity;
use App\Http\Models\Users\UserMap;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CampaignActionEntity extends BaseModel{

    public $table = 'jos_mdigm_marketing_actions';
    public $primaryKey = 'actionid';

    public $timestamps = false;

    protected $fillable = [ 'campaignid', 'ownerid', 'subject' , 'message', 'message_format' ,
        'sending_delay', 'sending_specific_datetime', 'sending_type', 'sending_field',
        'is_public', 'published' , 'templateid' , 'attachment',
        'auto_cancel','action_typeid'
    ];

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

        if( $r->action_typeid == 6 ){
            $this->message = ' ';
            $this->postcard_id = $r->postcard_id;
        }

        if( $r->$pk  ){
            $this->exists = true;
        }else{
            $this->sending_delay    = $this->sending_delay == -1 ? 0 : $this->sending_delay;
            $this->sending_type     = 'date_entered';
            $this->sending_specific_datetime = $this->sending_delay == -1 ? $r->send_year.'-'.$r->send_month.'-'.$r->send_day.' 00:00:00' : '1990-01-01 00:00:00';
            $this->date_created     = date( 'Y-m-d H:i:s' );
            $this->params           =  ' ';
            //$this->template_name    = $this->template_name ? $this->template_name : ' ';
            $this->ownerid          = $r->user()->id;
        }

        $this->save();

        return $this;
    }
}