<?php

namespace App\Models\Postcards;

use App\Http\Models\Users\UserEntity;
use Illuminate\Http\Request;


class PostcardCities extends \Eloquent{

    public $table       = 'postcard_cities';
    public $primaryKey  = 'mapid';


    public $timestamps  = false;

    public static function toggle( $r )
    {
        if( ! $r->city || ! $r->postcard_id ){
            // do nothing
            return false;
        }
        $c = static::where('postcard_id' , $r->postcard_id )
            ->where( 'city' , $r->city )
            ->first();

        if( $c ){
            $c->delete();
            return [
               'city' => null,
                'action' => 'removed'
            ];
        }else{
            $city = static::store( $r );
            return [
                'city' => $city,
                'action' => 'added'
            ];
        }
    }

    public static function store( $r )
    {
        $p = new static();
        $p->postcard_id = $r->postcard_id;
        $p->city = $r->city;
        $p->save();

        return $p;
    }
}