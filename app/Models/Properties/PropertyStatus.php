<?php

namespace App\Models\Properties;

use App\Http\Models\Photos\PhotoEntity;
use App\Http\Models\Photos\PhotosCollection;
use App\Models\BaseModel;
use Illuminate\Http\Request;

class PropertyStatus extends BaseModel{

    protected $table = 'jos_mdigm_status';
    protected $primaryKey = 'statusid';

    public $timestamps = false;

    public function getCollection( Request $r )
    {
        $this->setLpo( $r );
        $this->order_by = 'status';

        $query = static::from( $this->table );
        // insert conditions here
        $query->where( 'section' , 'properties' );
        $query->where( 'brokerid' , $r->account_id );

        $this->total = $query->count();

        $query->limit( $this->limit );
        $query->offset( $this->offset );
        $query->orderBy( $this->order_by , $this->order_direction );

        return $query->get();
    }

    public function vuefy()
    {
        return $this;
    }



}