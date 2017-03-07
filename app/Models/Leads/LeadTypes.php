<?php

namespace App\Models\Leads;

use App\Models\Accounts\AccountEntity;
use App\Models\BaseModel;
use Illuminate\Http\Request;

class LeadTypes extends BaseModel{

    protected $table = 'jos_mdigm_lead_type_map';
    protected $primaryKey = 'id';

    // types by broker
    public static $types_by_account    = [];

    public function getCollection( Request $r )
    {
        $this->setLpo( $r );
        $this->order_by = 'type';

        $account = AccountEntity::me();
        $fields = [ 'map.*', 't.type' ];

        $query = static::from( $this->table.' as map' );
        $query->where( 'map.brokerid', $account->brokerid );
        $query->join( 'jos_mdigm_lead_type as t' , 't.typeid' , 'map.typeid' );

        $this->total = $query->count();

        $query->limit( $this->limit );
        $query->offset( $this->offset );
        $query->orderBy( $this->order_by , $this->order_direction );

        return $query->get( $fields );
    }

}