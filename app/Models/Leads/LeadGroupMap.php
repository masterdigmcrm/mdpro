<?php

namespace App\Models\Leads;

use App\Models\Accounts\AccountEntity;
use App\Models\BaseModel;
use Illuminate\Http\Request;

class LeadGroupMap extends BaseModel{

    protected $table = 'jos_mdigm_lead_group_map';
    protected $primaryKey = 'map_id';

    public $timestamps = false;

    protected $fillable = [ 'group_id' , 'leadid' ];


    public function getCollection( Request $r )
    {
        $this->setLpo( $r );
        $this->fields = [ 'a.*' , 'l.first_name' , 'l.last_name' , 'g.group_name' ];

        $this->query = static::from( $this->table.' as a' );
        $this->query->join( 'jos_mdigm_leads as l' , 'l.leadid' , '=' , 'a.leadid' );
        $this->query->join( 'jos_mdigm_lead_groups as g' , 'g.lead_group_id' , '=' , 'a.group_id' );

        if( $r->group_id ){
            $this->query->where( 'group_id' , $r->group_id );
        }

        $this->total = $this->query->count();

        $this->assignLpo();
        return $this->vuefyThisCollection();
    }

    public function exists( $lead_id , $group_id )
    {
        $map = static::where( 'leadid' , $lead_id )
            ->where( 'group_id' , $group_id )
            ->first();

        return $map;
    }

}