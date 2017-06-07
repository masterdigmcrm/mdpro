<?php

namespace App\Models\Postcards;

use App\Http\Controllers\Tests\LobTestController;
use App\Http\Models\Properties\Sources\HomeJunctionProperties;
use App\Http\Models\Users\UserEntity;
use Illuminate\Http\Request;
use App\Api\Lob\LobApi;

class PostcardPropertiesQueue extends \Eloquent{

    public $table       = 'postcard_properties_queue';
    public $primaryKey  = 'mapid';

    public $fillable    = [ 'postcard_id' , 'property_id' , 'sent_at', 'status' ];
    public $timestamps  = false;

    /**
     * called in CronController
     * @param $postcard
     */
    public function send( $key, $postcard , $to_address , $from_address  )
    {
        // postcards are sent through Lob.com
        $lobapi = new LobApi( $key );
        $postcard = $lobapi->sendPostcard( $postcard , $to_address , $from_address );

        return $postcard;
    }

    public function getOwnerName()
    {
        $invalid_owners = [ '' , 'none' ];
        if( $this->owner_name == '' ||  $this->owner_name == 'none' ){
          return 'Property Owner';
        }

        return $this->owner_name;
    }

    public static function isQueued( $address , $postcard_id ){
        return static::where( 'property_address' , $address )
            ->where( 'postcard_id', $postcard_id )
            ->first();
    }

    /**
     * @param array $options
     * @return array
     */
    public static function forSending( $options = [] )
    {
        $limit = !empty( $options['limit'] ) ? $options['limit'] : 10;

        $postcards = static::where( 'p.status', 'on queue' )
            ->from( 'postcard_properties_queue as p' )
            ->join( 'postcards as pc' , 'pc.postcard_id' , '=' , 'p.postcard_id' )
            ->limit( $limit )
            ->get( [ 'p.*' , 'pc.*' ] );

        return [
          'success' => true,
          'postcards' => $postcards
        ];

    }

    public static function noName( $options = [] )
    {
        $limit = !empty( $options['limit'] ) ? $options['limit'] : 10;

        $postcards = static::whereNull( 'p.owner_name' )
            ->orWhere( 'p.owner_name' , '' )
            ->from( 'postcard_properties_queue as p' )
            ->join( 'postcards as pc' , 'pc.postcard_id' , '=' , 'p.postcard_id' )
            ->limit( $limit )
            ->get( [ 'p.*' , 'pc.*' ] );

        return [
            'postcards' => $postcards
        ];
    }

    public function isValidAddress( )
    {

    }

    public static function store( $p_obj , $postcard_id , $type )
    {
        $map = new static;
        $map->property_id = $p_obj->property_id;
        $address = $p_obj->Address ? $p_obj->Address : HomeJunctionProperties::address( $p_obj );
        //$p_obj->street.' '.$p_obj->city.' '.$p_obj->state.' '.$p_obj->zip;

        $map->property_address  =  $address;
        $map->property_type     =  $type;
        $map->postcard_id       =  $postcard_id;

        $params         = json_encode( $p_obj );
        $map->params    = $params;
        $map->status    = 'on queue';
        $map->sent_by   = isset( $p_obj->sent_by ) ? $p_obj->sent_by : UserEntity::me()->id ;

        try{
            $map->save();
        }catch( \Exception $e ){
            return false;
        }

        return $map;
    }

    public static function getPropertiesByPostcardId( Request $p )
    {
        $limit  = ! empty( $p->limit ) ? $p->limit : 50 ;
        $page   = ! empty( $p->page ) ? $p->page : 1 ;
        $offset = ( $page - 1 ) * $limit;

        $properties = static::where( 'postcard_id', $p->postcard_id );

        $count = $properties->count();

        $properties->orderBy( 'mapid' , 'DESC' )
            ->limit( $limit )
            ->offset( $offset );

        return [
            'count' => $count,
            'properties'=>$properties->get()
        ];
    }

    public static function unsentCount( $postcard_id )
    {
        $count = static::where( 'postcard_id', $postcard_id )
        ->where( 'status' , 'sent' )->count();

        return $count;
    }


}