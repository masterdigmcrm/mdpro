<?php


namespace App\Http\Models\Photos;

use Illuminate\Http\Request;

class PhotosCollection extends PhotoEntity{

    public function getCollection( Request $r )
    {
        $this->setLpo( $r );

        $query = static::from( $this->table );
        // insert conditions here

        $this->total = $query->count();

        $query->limit( $this->limit );
        $query->offset( $this->offset );
        $query->orderBy( $this->order_by , $this->order_direction );

        $this->collection = $query->get();

        return $this->vuefyThisCollection();
    }
}