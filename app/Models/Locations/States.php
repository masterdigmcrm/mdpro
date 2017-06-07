<?php

namespace App\Models\Locations;

use App\Http\Models\Users\UserEntity;
use App\Http\Models\Users\UserMap;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class States extends BaseModel{

    public $table = 'jos_mdigm_states';
    public $primaryKey = 'id';

    public $timestamps = false;

    public function getCollection( Request $r )
    {
        $this->setLpo( $r );
        $this->order_by = 'state';
        $this->limit = 500;

        $query = static::from( $this->table );
        // insert conditions here
        if( $r->countryid ){
            $query->where( 'countryid' , $r->countryid );
        }

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
        ];
        // states will be populated through ajax

        return \Form::select( 'id' , $s_array , $default , [ 'id' => 'stateid', 'class' => 'form-control',  'value' =>'' ] );
    }

    public static function getByCountryid( $cid )
    {
        return static::where( 'countryid' , $cid )
            ->get();
    }

    public static function getAbbr( $state )
    {
        return static::where( 'state' , $state )
            ->first();
    }

}