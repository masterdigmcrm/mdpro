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
}