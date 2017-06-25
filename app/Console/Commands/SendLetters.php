<?php

namespace App\Console\Commands;

use App\Api\Lob\LobApi;
use App\Models\Locations\States;
use App\Models\Marketing\ActionTriggerMap;
use App\Models\Marketing\ActionTriggerMapMessage;
use App\Models\letters\letterEntity;
use App\Models\Marketing\CampaignActionEntity;
use App\Models\Users\UserMap;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class SendLetters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'letters:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send marketing campaign letters';

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
        $r->merge( [ 'date_sending_sched' => date('Y-m-d'), 'action_typeid' => 3 , 'status' => 'onqueue' , 'limit' => 1  ] );

        $collection = ActionTriggerMap::factory()->getCollection( $r );
        $user_maps = [];

        foreach( $collection as $letter_triggers ){

            // get user LOB API key
            // user is the agent/maanger who is the owner of the lead
            $userid = $letter_triggers->assigned_to ? $letter_triggers->assigned_to : $letter_triggers->ownerid;

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
                $this->triggerFailed( $letter_triggers, 'User Map not found' );
                continue;
            }

            $to_address     =  $this->leadAddress( $letter_triggers );
            $from_address   =  $this->accountAddress( $user_map );
            $key = $this->getLobKey( $user_map );

            if( ! $to_address ){
                $this->triggerFailed( $letter_triggers , $this->error_message );
                continue;
            }


            if( ! $key ){
                $this->triggerFailed( $letter_triggers , 'LOB key not found' );
                continue;
            }

            $message = ( new CampaignActionEntity )->f( $letter_triggers->actionid )
                ->mergeMessage( $letter_triggers->leadid );

            try{
                $this->sendLetters(  $key , $message , $to_address ,$from_address );
            }catch( \Exception $e ){
                $this->triggerFailed( $letter_triggers , $e->getMessage() );
                continue;
            }


            $letter_triggers->status = 'sent';
            $letter_triggers->date_sent = date('Y-m-d H:i:s');
            $letter_triggers->save();

        }
    }

    private function triggerFailed( $letter_triggers , $message  )
    {
        $letter_triggers->status = 'failed';
        $letter_triggers->save();

        $m = ( new ActionTriggerMapMessage )->store(
            [
                'mapid' => $letter_triggers->mapid ,
                'message' => $message,
                'generated_by' => 'Sendletter cron command'
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

    private function sendLetters( $key , $message , $to_address ,$from_address )
    {
        $lobapi = new LobApi( $key );
        
        $ps     =   $lobapi->sendMarketingLetters( $message , $to_address , $from_address );
        return $ps;
    }

}
