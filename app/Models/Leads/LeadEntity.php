<?php

namespace App\Models\Leads;

use App\Models\BaseModel;
use Illuminate\Http\Request;

class LeadEntity extends BaseModel{

    protected $table = 'jos_mdigm_leads';
    protected $primaryKey = 'leadid';

    public $timestamps = false;

    protected $fillable = [ 'leadid', 'first_name', 'last_name' , 'birthday',
        'sourceid', 'status', 'typeid', 'phone_home', 'phone_mobile', 'phone_work',
        'email1', 'primary_address_street', 'primary_address_city', 'primary_address_state',
        'primary_address_postalcode', 'primary_address_country' ,'description',
        'assigned_to', 'company', 'company_position','brokerid', 'ownerid'
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

        $this->status = $r->status;

        if( $r->$pk  ){
            $this->exists = true;
        }else{
            $this->date_entered  = date( 'Y-m-d H:i:s' );
            $this->alt_birthday  = NULL;
            $this->date_deleted  = NULL;
            $this->date_assigned = NULL;
            $this->last_modified = NULL;
            $this->date_expected_to_buy = NULL;
            $this->converted = 0;
        }

        $this->save();

        return $this;
    }

    public function vuefy()
    {
        $this->full_name    =   $this->last_name.', '.$this->first_name;
        $this->phone_full   =   $this->fullPhoneDetails();
        return $this;
    }

    private function fullPhoneDetails()
    {
        $phone_arr = [];

        if( $this->phone_work ){
            $phone_arr[]  = '<a href="tel:'.$this->phone_work.'">'.$this->phone_work.' (Work) </a>';
        }
        if( $this->phone_home ){
            $phone_arr[]  = '<a href="tel:'.$this->phone_home.'">'.$this->phone_home.' (Home) </a>';
        }
        if( $this->phone_mobile ){
            $phone_arr[]  = '<a href="tel:'.$this->phone_mobile.'">'.$this->phone_mobile.' (Mobile) </a>';
        }

        return implode( '<br />', $phone_arr );
    }

    public function displayName( $type = 'simple' )
    {
        switch( $type ){
            case 'link':
                return '<a href="'.Url( 'component/leads/profile/'.$this->leadid ).'">'.$this->last_name.', '.$this->first_name.'</a>';
            break;
            default:
            case 'simple':
                return $this->last_name.', '.$this->first_name;
            break;
        }
    }

}