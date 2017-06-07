<?php

namespace App\Models\Marketing;

use App\Http\Models\Users\UserEntity;
use App\Http\Models\Users\UserMap;
use App\Models\BaseModel;
use App\Models\Marketing\Filters\ActionTriggerMapFilter;
use Carbon\Carbon;
use Faker\Provider\Base;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ActionTriggerMapMessage extends BaseModel{

    public $table = 'jos_mdigm_marketing_trigger_map_message';
    public $primaryKey = 'm_id';

    public $timestamps = false;

    protected $fillable = [ 'm_id', 'mapid' , 'message' , 'generated_by'];


    public function store( array $data )
    {
        $validator = \Validator::make( $data , [
            // validation rules here
        ] );

        if( $validator->fails() ){
            $this->errors = $validator->errors()->all();
            return false;
        }

        $this->fill( $data );
        $pk = $this->primaryKey;

        if( isset( $data[$pk] ) ){
            $this->exists = true;
        }else{
            $this->added_at = date('Y-m-d H:i:s');
        }

        $this->save();

        return $this;
    }
}