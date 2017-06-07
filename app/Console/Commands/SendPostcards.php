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
        $r->merge( [ 'action_typeid' => 6 , 'status' => 'onqueue'  ] );
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
                    ->first( [ 'br_address' , 'br_city','br_state', 'br_zip' , 'b.userid' , 'b.brokerid' ,'m.params' ] );

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

            if( ! $to_address ){
                $this->triggerFailed( $postcard_triggers , $this->error_message );
                continue;
            }

            if( ! $key ){
                $this->triggerFailed( $postcard_triggers , 'LOB key not found' );
                continue;
            }
            try{
                $this->sendPostcards(  $key , $postcard , $to_address ,$from_address );
            }catch( \Exception $e ){
                $this->triggerFailed( $postcard_triggers , $e->getMessage() );
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
        return [
            'name' => 'Masterdigm ',
            'address_line1' =>  '10 Second St' ,
            'address_city' =>  'Ladera Ranch',
            'address_state' => 'CA',
            'address_country' => 'US',
            'address_zip' => '92694'
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

        $state = $lead->primary_address_state;

        if( strlen( $state ) > 2 ){
            if(  $state_obj = States::getAbbr( $state ) ) {
                $state = $state_obj->stateid;
            }else{
                $this->error_message = 'Empty street address';
                return false;
            }

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

}
