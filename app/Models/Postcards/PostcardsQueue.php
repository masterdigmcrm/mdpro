<?php

namespace App\Models\Postcards;

use App\Http\Controllers\Tests\LobTestController;
use App\Http\Models\Users\UserEntity;
use Illuminate\Http\Request;
use App\Api\Lob\LobApi;

class PostcardsQueue extends \Eloquent{

    public $table       = 'postcards_queue';
    public $primaryKey  = 'mapid';

    public $fillable    = [ 'postcard_id' , 'leadid' , 'sent_at', 'status' ];
    public $timestamps  = false;
    /**
     * called in CronController
     * @param $postcard
     */
    public function send( $postcard , $to_address , $from_address)
    {
        // postcards are sent through Lob.com
        $lobapi = new LobApi();
        $postcard = $lobapi->sendPostcard( $postcard , $to_address , $from_address );

        return $postcard;
    }

    public static function isQueued( $leadid , $postcard_id ){
        return static::where( 'leadid' , $leadid )
            ->where( 'postcard_id', $postcard_id )
            ->first();
    }

    /**
     * @param array $options
     * @return array
     */
    public static function forSending( $options = [] ){

        $limit = !empty( $options['limit'] ) ? $options['limit'] : 10;

        $postcards = static::where( 'p.status' , 'on queue' )
            ->from( 'postcards_queue as p' )
            ->join( 'postcards as pc' , 'pc.postcard_id' , '=' , 'p.postcard_id' )
            ->join( 'jos_mdigm_leads as l' , 'p.leadid' , '=' , 'l.leadid' )
            ->join( 'jos_mdigm_states as st' , 'st.id' , '=' , 'l.stateid' , 'LEFT' )
            ->limit( $limit )
            ->get( ['p.*' , 'pc.*' ,'l.*' , 'st.stateid as state' ]);

        return [
          'postcards' => $postcards
        ];
    }

    public function isValidAddress( )
    {
        if( ! $this->primary_address_street){
            return false;
        }
        if( ! $this->primary_address_city || ! $this->primary_address_state ){

        }

    }

    public static function store( Request $r )
    {
        $map = new static;
        $map->fill( $r->all() );
        $map->save();

        return $map;
    }
}