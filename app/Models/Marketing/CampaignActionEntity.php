<?php

namespace App\Models\Marketing;

use App\Models\Users\UserEntity;
use App\Models\Users\UserMap;
use App\Models\Accounts\AccountEntity;
use App\Models\BaseModel;
use App\Models\Leads\LeadEntity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CampaignActionEntity extends BaseModel{

    public $table = 'jos_mdigm_marketing_actions';
    public $primaryKey = 'actionid';

    public $timestamps = false;

    public $appends = [ 'type','delay' , 'sending_day', 'sending_month', 'sending_year' ];

    protected $fillable = [ 'actionid','campaignid', 'ownerid', 'subject' , 'message', 'message_format' ,
        'sending_delay', 'sending_specific_datetime', 'sending_type', 'sending_field',
        'is_public', 'published' , 'templateid' , 'attachment',
        'auto_cancel','action_typeid'
    ];

    public $merged_subject   = '';
    public $merged_message	= '';


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

        $this->sending_specific_datetime = $this->sending_delay == -1 ? $r->send_year.'-'.$r->send_month.'-'.$r->send_day.' 00:00:00' : null;

        if( $r->$pk  ){
            $this->exists = true;
        }else{
            $this->sending_delay    = $this->sending_delay == -1 ? 0 : $this->sending_delay;
            $this->sending_type     = 'date_entered';
            $this->date_created     = date( 'Y-m-d H:i:s' );
            $this->params           =  ' ';
            //$this->template_name    = $this->template_name ? $this->template_name : ' ';
            $this->ownerid          = $r->user()->id;
        }

        $this->save();

        return $this;
    }

    public function getTypeAttribute()
    {
        $types = [
            1=>  'Email' , 2=> 'To do',
            3=>  'Letter' , 4 => 'One Off', 5 => 'Newsletter',
            6 => 'Postcard'
        ];

        return isset( $types[$this->action_typeid] ) ? $types[$this->action_typeid] : '';
    }

    public function getSendingDelayAttribute( $value )
    {
        if( $this->sending_specific_datetime  && ! in_array( $this->sending_specific_datetime  , ['0000-00-00 00:00:00' , '1990-01-01 00:00:00'] ) ){
            return -1;
        }

        return  $value;
    }

    public function getSendingDayAttribute()
    {
        if( ! $this->sending_specific_datetime){
            return '00';
        }
        return date('d' , strtotime($this-> sending_specific_datetime ));
    }

    public function getSendingMonthAttribute()
    {
        if( ! $this->sending_specific_datetime){
            return '00';
        }
        return date('m' , strtotime($this-> sending_specific_datetime ));
    }

    public function getSendingYearAttribute(){
        if( ! $this->sending_specific_datetime){
            return '00';
        }
        return date('Y' , strtotime($this-> sending_specific_datetime ));
    }

    public function getDelayAttribute()
    {
        if( $this->sending_delay == 0 ){
            return 'Same Day';
        }
        if( $this->sending_delay > 0 ){
            return 'After '.$this->sending_delay.' days ';
        }
        if( $this->sending_delay == -1  ){
            return $this->sending_specific_datetime;
        }
    }

    public function getMergedSubject()
    {
        return $this->merged_subject;
    }


    public function mergeMessage( $leadid )
    {
        if( ! $leadid ){
            return  null;
        }

        if( ! $action_lead  = (new LeadEntity )->f( $leadid ) ){
            return null;
        }

        //$action_user	=	new broker_user_map($this->_db);
        /**
         * if lead is assigned to an agent , make agent as the owner
         * else make broker as owner
         * else make action creator as the owner
         */
        if( $action_lead->assigned_to ){
            $ownerid =	$action_lead->assigned_to;
        }elseif($action_lead->ownerid){
            $ownerid =	$action_lead->ownerid;
        }else{
            $ownerid =	$this->ownerid;
        }

        $action_user    =   UserMap::byUserId( $ownerid );
        $action_broker  =   ( new AccountEntity )->byUserId( $action_user->broker_userid );

        if(  $action_user->is_manager() ){

            //$my_contact  = contact::getByUserid( $ownerid );

            $my_firstname		=	$action_broker->br_firstname;
            $my_lastname		=	$action_broker->br_lastname;
            $my_street			=	$action_broker->br_address;
            $my_city			=	$action_broker->br_city;
            $my_state	   		=	$action_broker->br_state;
            $my_zip	   		    =	$action_broker->br_zip;
            $my_company   		=	$action_broker->br_company;
            $my_work_phone   	=	$action_broker->br_work_phone;
            $my_home_phone   	=	$action_broker->br_home_phone;
            $my_mobile_phone  	=	$action_broker->br_mobile_phone;
            $my_email   		=	$action_broker->br_email;
            $my_website   		=	$action_broker->br_website;

            $my_signature		=	$action_user->signature;
            $my_nmls	   		=	$action_user->nmls;
            $my_dre	   		    =   $action_user->dre;

        }else{

            $my_firstname		=	$action_user->contact_firstname;
            $my_lastname		=	$action_user->contact_lastname;
            $my_street			=	$action_user->contact_street;
            $my_city			=	$action_user->contact_city;
            $my_state	   		=	$action_user->contact_state;
            $my_zip	   		    =	$action_user->contact_zipcode;
            $my_company   		=	$action_user->contact_company;
            $my_work_phone   	=	$action_user->contact_phone;
            $my_home_phone   	=	$action_user->contact_homephone;
            $my_mobile_phone  	=	$action_user->contact_mobile;
            $my_email   		=	$action_user->contact_email;
            $my_website   		=	$action_user->contact_website;
            $my_dre	   		    =	$action_user->dre;
            $my_nmls	   		=	$action_user->nmls;
            $my_signature		=	$action_user->signature;

        }

        $about 			= 	$action_user->about;
        //$designation	=	$action_broker->getParamByKey( 'param_designation' ,  $action_user );
        //$team_logo		=	$action_broker->getParamByKey( 'team_logo' ,  $action_user ) ? '<img src="'.$action_broker->getParamByKey( 'team_logo' ,  $action_user ).'" />' : '';
        //$team_picture	=	$action_broker->getParamByKey( 'team_picture' ,  $action_user ) ? '<img src="'.$action_broker->getParamByKey( 'team_picture' ,  $action_user ).'" />' : '';
        $designation    = '';
        $team_logo      = '';
        $team_picture   = '';

        $select_detail = new \stdClass();
        $select_detail->location=null;
        $select_detail->state=null;
        $select_detail->bedrooms=null;
        $select_detail->bathrooms=null;
        $select_detail->minpricerange=null;
        $select_detail->pricerange=null;

        $lead_contact = new \stdClass();
        $lead_contact->contact_firstname=null;
        $lead_contact->contact_lastname=null;
        $lead_contact->contact_photo_url=null;
        $lead_contact->contact_logo_url=null;
        $lead_contact->contact_signature=null;
        $lead_contact->contact_email=null;
        $lead_contact->contact_phone=null;
        $lead_contact->dre=null;
        $lead_contact->nmls=null;

        $logo 	= 	$action_broker->getLogoImage();
        $pic	=	$action_user->getPictureHTML();

        $keyArray 	= 	array(
            '[my_firstname]',
            '[my_lastname]',
            '[my_street]',
            '[my_city]',
            '[my_state]',
            '[my_zip]',
            '[my_company]',
            '[my_company_logo]',
            '[my_work_phone]',
            '[my_home_phone]',
            '[my_mobile_phone]',
            '[my_email]',
            '[my_website]',
            '[my_picture]',
            '[my_signature]',
            '[my_dre]',
            '[my_nmls]',
            '[lead_firstname]',
            '[lead_lastname]',
            '[lead_email]',
            '[lead_work_phone]',
            '[lead_home_phone]',
            '[lead_mobile_phone]',
            '[lead_address]',
            '[lead_city]',
            '[lead_state]',
            '[lead_zip]',
            '[searched_location]',
            '[searched_state]',
            '[number_of_bedrooms]',
            '[number_of_bathrooms]',
            '[min_price_range]',
            '[max_price_range]',
            '[about_me]',
            '[end_primark_newsletter]',
            '[contact_first_name]',
            '[contact_last_name]',
            '[contact_photo]',
            '[contact_logo]',
            '[contact_signature]',
            '[contact_email]',
            '[contact_phone]',
            '[dre]',
            '[nmls]',
            '[agent_firstname]',
            '[agent_lastname]',
            '[agent_company]',
            '[agent_address]',
            '[agent_email]',
            '[agent_zip]',
            '[agent_work_phone]',
            '[agent_mobile_phone]',
            '[agent_website]',
            '[agent_picture]',
            '[agent_company_logo]',
            '[agent_signature]',
            '[my_about]',
            '[my_designation]',
            '[my_team_logo]',
            '[my_team_picture]',
        );

        $replaceArray 	= 	array(
            $my_firstname,
            $my_lastname,
            $my_street,
            $my_city,
            $my_state,
            $my_zip,
            $my_company,
            $logo,
            $my_work_phone,
            $my_home_phone,
            $my_mobile_phone,
            $my_email,
            $my_website,
            $pic,
            $my_signature,
            $my_dre,
            $my_nmls,
            $action_lead->first_name,
            $action_lead->last_name,
            $action_lead->email1,
            $action_lead->phone_work,
            $action_lead->phone_home,
            $action_lead->phone_mobile,
            $action_lead->primary_address_street,
            $action_lead->primary_address_city,
            $action_lead->primary_address_state,
            $action_lead->primary_address_postalcode,
            $select_detail->location,
            $select_detail->state,
            $select_detail->bedrooms,
            $select_detail->bathrooms,
            $select_detail->minpricerange,
            $select_detail->pricerange,
            $about,
            '</a>',
            $lead_contact->contact_firstname,
            $lead_contact->contact_lastname,
            '<img src="'.$lead_contact->contact_photo_url.'" />',
            '<img src="'.$lead_contact->contact_logo_url.'" />',
            $lead_contact->contact_signature,
            $lead_contact->contact_email,
            $lead_contact->contact_phone,
            $lead_contact->dre,
            $lead_contact->nmls,
            $action_user->contact_firstname,
            $action_user->contact_lastname,
            $action_user->contact_company,
            $action_user->contact_street.' '.$action_user->contact_city.', '.$action_user->contact_state,
            $action_user->contact_email,
            $action_user->contact_zipcode,
            $action_user->contact_phone,
            $action_user->contact_mobile,
            $action_user->contact_website,
            '<img src="'.$action_user->contact_photo_url.'" />',
            '<img src="'.$action_user->contact_logo_url.'" />',
            $action_user->signature,
            $about,
            $designation,
            $team_logo,
            $team_picture

        );

        $this->merged_subject	=	 str_replace( $keyArray,$replaceArray,$this->subject );
        $this->merged_message	=	 str_replace( $keyArray,$replaceArray,$this->message );
        $this->merged_message	=	 str_replace('[my_picture]',$pic,$this->merged_message );

        return $this->merged_message;
    }
    

}