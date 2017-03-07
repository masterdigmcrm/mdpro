<?php

namespace App\Models\Activities;

use App\Http\Models\Users\UserEntity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class TodoCollection extends TodoEntity{

    public function getCollection( Request $r )
    {
        if( ! $r->leadid ){
            return [];
        }
        $this->setLpo( $r );
        $this->order_by = 'start_date';
        $this->order_direction = 'DESC';

        $fields = [ 'a.*' ];
        $query = static::from( $this->table.' as a' );

        // insert conditions here
        $query->where( 'leadid' , $r->leadid );

        $this->total = $query->count();

        $query->limit( $this->limit );
        $query->offset( $this->offset );
        $query->orderBy( $this->order_by , $this->order_direction );

        return $query->get( $fields );
    }

    public static function getByLeadId( $leadid , $options = [] ){

        $limit = ! empty( $options['limit']) ? $options['limit'] : 20;
        $orderby = ! empty( $options['orderby']) ? $options['orderby'] : 'start_date';

        $order_direction = ! empty( $options['order_direction']) ? $options['order_direction'] : 'asc';

        return static::where( 'leadid' , $leadid )
            ->limit( $limit )
            ->orderBy( $orderby , $order_direction );
    }
}