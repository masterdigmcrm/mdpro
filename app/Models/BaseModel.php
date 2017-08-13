<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class BaseModel extends Model
{
    protected $total        =   0;
    protected $pages        =   1;
    protected $page         =   1; // current_page
    protected $limit        =   20;
    protected $offset       =   0;
    protected $errors       =   [];
    protected $collection   =   null;
    protected $request;

    public $error_code;

    public static function factory(){
        return new static;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function setLpo( Request $r )
    {
        $this->limit = $r->limit ? $r->limit : 20;
        $this->page = $r->page ? $r->page : 1;
        $this->offset = ($this->page-1) * $this->limit;
        $this->order_by = $r->order_by ? $r->order_by :  $this->primaryKey;
        $this->order_direction = $r->order_direction ? $r->order_direction : 'ASC';
    }

    public function assignLpo()
    {

        $this->query->limit( $this->limit );
        $this->query->offset( $this->offset );
        $this->query->orderBy( $this->order_by , $this->order_direction );

        return $this->query;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function getPageCount( $return_array = false )
    {
        if( ! $this->total ){
            return 0;
        }
        // page count
        $count =  ceil( $this->total / $this->limit   );

        // used to return a list of pages for pagination
        if( $return_array ){

            $_arr = [];

            if( $count < 6 ){
                for( $i = 1 ; $i <= $count; $i++  ){
                    $_arr[] = $i;
                }
                return $_arr;
            }

            if( $count > 6 ){

                $start  =   $this->page - 3;
                $start  =  ( $start < 1 )  ? 1 : $start;

                $end    = $this->page + 3;
                $end    = $end > $count ? $count : $end;

                for( $i = $start ; $i <= $end; $i++  ){
                    $_arr[] = $i;
                }
            }

            return $_arr;
        }

        return $count;
    }

    public function getCurrentPage()
    {
        return $this->page;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function displayErrors()
    {
        if( ! count( $this->errors )){
            return '';
        }

        $html = '<ul>';
        foreach( $this->errors as $e ){
            $html .= '<li>'.$e.'</li>';
        }
        $html .= '</ul>';

        return $html;
    }

    /**
     * Vuefy all objects of the current collection
     *
     * @return array
     */
    public function vuefyThisCollection()
    {

        if( $this->collection === null ){
            $this->collection = $this->query->get( $this->fields );
        }

        $c_arr = [];

        foreach( $this->collection as $c ){
            $c_arr[] = $c->vuefy();
        }

        return $c_arr;
    }

    /**
     * Vuefy a given collection
     *
     * @param Collection $collection
     * @return array
     */
    public function vuefyCollection( Collection $collection )
    {
        $c_arr = [];
        foreach( $collection as $c ){
            $c_arr[] = $c->vuefy();
        }

        return $c_arr;
    }

    /**
     * Model Mutator - returns only fields needed
     *
     * @return $this
     */
    public function vuefy()
    {
        return $this;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function getPaginationData()
    {
        return [
            'page_count'    =>  $this->getPageCount(),
            'current_page'  =>  $this->getCurrentPage(),
            'total'     =>  $this->getTotal(),
            'limit'     =>  $this->getLimit()
        ];
    }

    /**
     * @return static
     */
    public static function f( $id )
    {
          return static::find( $id );
    }

}