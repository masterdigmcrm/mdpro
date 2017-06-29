<?php

namespace App\Models\Marketing\Filters;

use App\Models\AbstractFilter;
use Illuminate\Database\Eloquent\Builder;

class ActionTriggerMapFilter extends AbstractFilter{

    public function __construct( Builder $query )
    {
        $this->query = $query;
    }

    public function statusFilter( $k , $v  )
    {
        $this->query->where( 'm.status' , $v );
    }
    
    public function actionTypeIdFilter( $k, $v  )
    {
        $this->query->where( 'action_typeid' , $v );
    }
    
    public function actionIdFilter( $k , $v )
    {
        if( is_array( $v) ){
            $this->query->whereIn( 'm.actionid' , $v );
        }else{
            $this->query->where( 'm.actionid' , $v );
        }
    }

    public function dateSendingSchedFilter( $k, $v )
    {
        $this->query->whereDate( 'date_sending_sched', $v );
    }
}