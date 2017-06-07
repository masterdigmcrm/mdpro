<?php

namespace App\Models;

abstract class AbstractFilter {

    protected $query;
    protected $filters;

    public function applyFilter( array $filters  )
    {

        $this->filters = $filters;

        foreach( $filters as $k => $v ){
            $method = camel_case( $k ).'Filter';
            if( method_exists( $this , $method )){
                $this->$method( $k , $v );
            }
        }

        return $this->query;
    }
}