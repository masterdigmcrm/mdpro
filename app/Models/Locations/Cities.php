<?php

namespace App\Models\Locations;


use App\Models\BaseModel;
use Illuminate\Http\Request;

class Cities extends BaseModel{

    public $table = 'jos_mdigm_cities';
    public $primaryKey = 'id';

    public $timestamps = false;

    public function getCollection( Request $r )
    {
        $this->setLpo( $r );

        $this->order_by = 'city';
        $this->limit = 1015;

        $query = static::from( $this->table );
        // insert conditions here
        $query->where( 'stateid' , $r->stateid );

        $this->total = $query->count();

        $query->limit( $this->limit );
        $query->offset( $this->offset );
        $query->orderBy( $this->order_by , $this->order_direction );

        return $query->get( ['id','city'] );
    }

    public function getCityAttribute( $value )
    {
        return ucwords( strtolower( $value ) );
    }

    public static function getByStateid( $sid )
    {
        return static::where( 'stateid' , $sid )
            ->orderBy( 'city' )
            ->get();
    }

}