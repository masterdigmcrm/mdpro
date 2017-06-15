<?php

namespace App\Models;

abstract class AbstractFilter {

    protected $query;
    protected $filters;

    /**
     * Scans through an array and find matches to available filter methods
     *
     * @param array $filters
     * @return mixed
     */
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