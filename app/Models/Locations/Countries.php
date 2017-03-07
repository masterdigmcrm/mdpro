<?php

namespace App\Models\Locations;

use App\Http\Models\Users\UserEntity;
use App\Http\Models\Users\UserMap;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Countries extends BaseModel{

    public $table = 'jos_mdigm_countries';
    public $primaryKey = 'countryid';

    public $timestamps = false;

    public function getCollection( Request $r )
    {
        $this->setLpo( $r );
        $this->order_by = 'country';

        $query = static::from( $this->table );
        // insert conditions here

        $this->total = $query->count();

        $query->limit( $this->limit );
        $query->offset( $this->offset );
        $query->orderBy( $this->order_by , $this->order_direction );

        return $query->get();
    }

    public static function selectList( $default = 0 )
    {
        $s_array = [
            '0' => ' ',
            '1' => 'United States',
        ];
        $countries = static::orderby('country')->get();

        foreach( $countries as $c ){
            if( $c->coutryid != 1 ){
                $s_array[ $c->countryid ] = $c->country;
            }
        }
        //$status = array_merge( $s_array , $status );

        return \Form::select( 'countryid' , $s_array , $default , [ 'id' => 'countryid', 'class' => 'form-control', 'v-on:change'=>'countrySelected()', 'value' =>'' ] );
    }

}