<?php

namespace App\Models\Leads;

use Illuminate\Http\Request;

class LeadGroupsCollection extends LeadGroupEntity
{

    public function getCollection( Request $r )
    {

        $this->setLpo( $r );
        $this->fields = [ 'a.*' ];

        // broker id is required
        if( ! $r->brokerid ) {
            return [  ] ;
        }

        $this->query = static::from( $this->table.' as a' );
        $this->query->where( 'brokerid' , $r->brokerid );

        $this->total = $this->query->count();
        $this->assignLpo();
        return $this->vuefyThisCollection();
    }
}