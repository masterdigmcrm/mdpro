<?php

namespace App\Console\Commands;

use App\Api\Lob\LobApi;
use App\Models\Accounts\AccountEntity;
use App\Models\Locations\States;
use App\Models\Marketing\ActionTriggerMap;
use App\Models\Marketing\ActionTriggerMapMessage;
use App\Models\Postcards\PostcardEntity;
use App\Models\Users\UserMap;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class SendPostcards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'postcards:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send marketing campaign postcards';

    protected $error_message;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $r = new Request();
        $r->merge( [ 'date_sending_sched' => date('Y-m-d'), 'action_typeid' => 6 , 'status' => 'onqueue'  ] );

        $collection = ActionTriggerMap::factory()->getCollection( $r );
        $user_maps = [];

        foreach( $collection as $postcard_triggers ){

            // get user LOB API key
            // user is the agent/maanger who is the owner of the lead
            $userid = $postcard_triggers->assigned_to ? $postcard_triggers->assigned_to : $postcard_triggers->ownerid;

            if( isset( $user_maps[$userid] ) ){
                $user_map = $user_maps[ $userid ];
            }else{
                $user_map = UserMap::where( 'm.userid' , $userid )
                    ->from( 'jos_mdigm_broker_user_map as m')
                    ->join( 'jos_mdigm_broker as b' , 'm.broker_userid' ,'=' ,'b.userid' )
                    ->leftJoin( 'jos_mdigm_contacts as c' , 'm.contactid' , '=' , 'c.contactid' )
                    ->first( [ 'm.userid as userid', 'm.contactid', 'broker_userid','br_firstname','br_lastname', 'br_address' , 'br_city','br_state',
                        'br_zip' ,  'b.brokerid' ,'m.params' ,
                        'c.contact_firstname' , 'c.contact_lastname' , 'contact_street' , 'contact_city' , 'contact_stateid' , 'contact_zipcode' ] );

                $user_maps[ $userid ] = $user_map;
            }

            if( ! $user_map  ){
                $this->triggerFailed( $postcard_triggers, 'User Map not found' );
                continue;
            }

            if( ! $postcard_triggers->postcard_id ){
                $this->triggerFailed( $postcard_triggers, 'Action has no postcard id' );
                continue;
            }

            $postcard = (new PostcardEntity)->f( $postcard_triggers->postcard_id );

            $to_address     =  $this->leadAddress( $postcard_triggers );
            $from_address   =  $this->accountAddress( $user_map );
            $key = $this->getLobKey( $user_map );

            $ttext = json_encode( $to_address );

            if( ! $to_address ){
                $this->triggerFailed( $postcard_triggers , $this->error_message.' '.$ttext );
                continue;
            }

            if( ! $key ){
                $this->triggerFailed( $postcard_triggers , 'LOB key not found' );
                continue;
            }
            try{
                $this->sendPostcards(  $key , $postcard , $to_address ,$from_address );
            }catch( \Exception $e ){
                $this->triggerFailed( $postcard_triggers , $e->getMessage().' '.$ttext );
                continue;
            }


            $postcard_triggers->status = 'sent';
            $postcard_triggers->date_sent = date('Y-m-d H:i:s');
            $postcard_triggers->save();

        }
    }

    private function triggerFailed( $postcard_triggers , $message  )
    {
        $postcard_triggers->status = 'failed';
        $postcard_triggers->save();

        $m = ( new ActionTriggerMapMessage )->store(
            [
                'mapid' => $postcard_triggers->mapid ,
                'message' => $message,
                'generated_by' => 'SendPostcard cron command'
            ]
        );

        return $m;
    }

    private function getLobKey( $user_map )
    {
         return $user_map->getParamValue( 'param_lob_key' );
    }

    private function accountAddress( $user_map )
    {
        if( $user_map->broker_userid == $user_map->userid ){


            $name       = $user_map->br_firstname.' '.$user_map->br_lastname;
            $address    = $user_map->br_address;
            $city       = $user_map->br_city;
            $s  = States::where( 'id' , $user_map->stateid )->first();
            $state      = $s ? $s->stateid : 'CA';
            $postal_code = $user_map->zip;
        }else{

            $name       = $user_map->contact_firstname.' '.$user_map->contact_lastname;
            $address    = $user_map->contact_street;
            $city       = $user_map->contact_city;
            $s  = States::where( 'id' , $user_map->contact_stateid )->first();
            $state          = $s ? $s->stateid : 'CA';
            $postal_code    = $user_map->contact_zipcode;
        }

        return [
            'name' => $name,
            'address_line1' =>  $address ,
            'address_city' =>  $city,
            'address_state' => $state,
            'address_country' => 'US',
            'address_zip' => $postal_code
        ];
        //return $user_map->br_address.' '.$user_map->br_city.' '.$user_map->br_state.' '.$user_map->br_zip;
    }

    private function leadAddress( $lead )
    {
        if( ! $lead->primary_address_street ){
            $this->error_message = 'Empty street address';
            return false;
        }
        if( ! $lead->primary_address_city ){
            $this->error_message = 'Empty city address';
            return false;
        }

        if( ! $state = $this->getState( $lead ) ){
            $this->error_message = 'Empty state address';
            return false;
        }

        return [
            'name' => $lead->first_name.' '.$lead->last_name,
            'address_line1' =>  $lead->primary_address_street ,
            'address_city' => $lead->primary_address_city,
            'address_state' => $state,
            'address_country' => "US",
            'address_zip' => $lead->primary_address_postalcode
        ];
    }

    private function sendPostcards( $key , $postcard , $to_address ,$from_address )
    {
        $lobapi = new LobApi( $key );

        $ps     =   $lobapi->sendMarketingPostcards( $postcard , $to_address , $from_address );
        return $ps;
    }


    private function getState( $lead )
    {
        if( $lead->stateid ){
            $state = States::find( $lead->stateid );
            if( strlen( $state->stateid ) == 2 ){
                return $state->stateid;
            }
        }

        $state = $lead->primary_address_state ;

        if( strlen( $state ) == 2 ){
            return $state;
        }else{

            if(  $state_obj = States::getAbbr( $state ) ) {
                $state = $state_obj->stateid;

                if( strlen( $state ) == 2 ){
                    return $state;
                }

                return false;
            }else{

                return false;

            }

        }


    }
}
