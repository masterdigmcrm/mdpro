<?php

namespace App\Models\Leads;

use App\Models\Accounts\AccountEntity;
use App\Models\BaseModel;
use Illuminate\Http\Request;

class LeadGroupEntity extends BaseModel{

    protected $table = 'jos_mdigm_lead_groups';
    protected $primaryKey = 'lead_group_id';

    public $timestamps = false;

    protected $fillable = [ 'group_name' , 'brokerid' ];

    public function store( Request $r )
    {
        $validator = \Validator::make( $r->all() , [
            // validation rules here
            'group_name' => 'required|unique:jos_mdigm_lead_groups'
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

}