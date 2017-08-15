<?php

namespace App\Models\Leads;

use App\Models\Accounts\AccountEntity;
use Illuminate\Http\Request;

class LeadCollection extends LeadEntity{

    public function getCollection( Request $r )
    {
        if( ! $r->assigned_to ){
            return [];
        }

        $this->setLpo( $r );
        $this->order_by = $r->order_by ? $r->order_by :  'date_entered';
        $this->order_direction = $r->order_direction ? $r->order_direction :  'DESC';

        $assigned_to = $r->assigned_to;

        $fields = [ 'l.*' , 's.statusid',  's.status', 't.type', 'so.source' ];

        $query = static::from( $this->table.' as l' )
            ->leftjoin( 'jos_mdigm_lead_status as s', 's.statusid' , 'l.status' )
            ->leftjoin( 'jos_mdigm_lead_type as t', 't.typeid' , 'l.typeid' )
            ->leftjoin( 'jos_mdigm_sources as so', 'so.sourceid' , 'l.sourceid' );

        if( $r->primaryKeyValue ){
            $query->where( $this->primaryKey , $r->primaryKeyValue );
            if( ! $lead =  $query->first( $fields ) ){
                return false;
            }

            return $lead->vuefy();
        }

        $owner_id = $r->owner_id ? $r->owner_id : AccountEntity::me()->userid  ;
        $query->where( 'l.ownerid' , $owner_id );

        if( $assigned_to ){

            if( is_array( $assigned_to ) ){
                $query->whereIn( 'l.assigned_to' , $assigned_to );
            }else{
                $query->where( 'l.assigned_to' , $assigned_to );
            }

        }

        if( $r->typeid ){
            $query->where( 'l.typeid' , $r->typeid );
        }

        if( $r->sourceid ){
            $query->where( 'l.sourceid' , $r->sourceid );
        }

        if( $r->statusid ){
            $query->where( 'l.status' , $r->statusid );
        }

        $query->where( 'l.deleted' , 0 );

        if( $r->q ){
            $query->whereRaw( "MATCH(first_name,last_name,email1) AGAINST(? IN BOOLEAN MODE )" , [$r->q] );
            $fields[] =\DB::raw( " MATCH ( first_name,last_name,email1 ) AGAINST( '$r->q' IN BOOLEAN MODE ) as `relevance` " );
            $this->order_by = 'relevance';
            $this->order_direction  = 'DESC';
        }

        $this->total = $query->count();

        $query->limit( $this->limit );
        $query->offset( $this->offset );
        $query->orderBy( $this->order_by , $this->order_direction );

        $this->collection =  $query->get( $fields );

        return $this->vuefyThisCollection();
    }

    public static function getByUserId( $user_id )
    {
        return LeadCollection::where( 'assigned_to' , $user_id  )
        ->limit(  100 )
        ->get();
    }

}