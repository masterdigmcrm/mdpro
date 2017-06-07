<?php

namespace App\Models\Marketing\Filters;

use App\Models\AbstractFilter;
use Illuminate\Database\Eloquent\Builder;

class CampaignActionFilter extends AbstractFilter{

    public function __construct( Builder $query )
    {
        $this->query = $query;
    }

    public function campaignIdFilter( $k , $v  )
    {
        if( is_array( $v ) ){
            $this->query->whereIn( 'campaignid', $v );
        }else{
            $this->query->where( 'campaignid', $v );
        }
    }

    public function deletedFilter( $k , $v )
    {
        $this->query->where( 'a.deleted', "$v" );
    }

    public function isTemplateFilter( $k , $v )
    {
        $this->query->where( $k , "$v" );
    }
    
    public function publishedFilter( $k , $v )
    {
        $this->query->where( $k , "$v" );
    }

    public function postcardIdFilter( $k , $v )
    {
        $this->query->where( $k , $v );
    }

}