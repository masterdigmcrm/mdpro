<?php

namespace App\Models\Leads;

use App\Models\Accounts\AccountEntity;
use App\Models\BaseModel;
use Illuminate\Http\Request;

class LeadStatusMap extends BaseModel{

    protected $table = 'jos_mdigm_lead_status_map';
    protected $primaryKey = 'id';
    public $timestamps = false;

    /**
     * prevents multiple calls to status
     * @param $accountid
     * @return mixed
     */

    static $status_by_account = [];

    public function getCollection( Request $r )
    {
        $r->merge([ 'limit' => 500 ]);
        $this->setLpo( $r );
        $this->order_by = 'status';

        $account = AccountEntity::me();
        $fields = [ 'map.*', 's.status' ];

        $query = static::from( $this->table.' as map' );
        $query->where( 'map.brokerid', $account->brokerid );
        $query->join( 'jos_mdigm_lead_status as s' , 's.statusid' , 'map.statusid' );


        $this->total = $query->count();

        $query->limit( $this->limit );
        $query->offset( $this->offset );
        $query->orderBy( $this->order_by , $this->order_direction );

        return $query->get( $fields );
    }

}