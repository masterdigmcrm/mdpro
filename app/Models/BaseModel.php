<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class BaseModel extends Model
{
    protected $total     = 0;
    protected $pages     = 1;
    protected $page      = 1; // current_page
    protected $limit     = 20;
    protected $errors    = [];
    protected $collection    = [];
    protected $request;


    public $error_code;

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

    public function getLimit()
    {
        return $this->limit;
    }

    public function getPageCount( $return_array = false )
    {
        if( ! $this->total ){
            return 0;
        }
        $count =  ceil( $this->total / $this->limit   );

        // used to return a list of pages for pagination

        if( $return_array ){

            $_arr = [];
            if( $count > 16 ){
                // first four
                for( $i = 1 ; $i <= 4; $i++ ){
                    $_arr[] = $i;
                }

                // middle four
                $middle_four = $this->page - 2;

                if( $this->page > 4 ){
                    $_arr[] = '...';
                }

                for( $i = $middle_four ; $i <= ( $middle_four + 4 ) ; $i++ ){
                    if( $i >= 1 && ! in_array( $i, $_arr )  && $i <= $count - 4 ){
                        $_arr[] = $i;
                    }
                }

                if( $this->page < ( $count - 4 ) ){
                    $_arr[] = '...';
                }

                // last four
                $last_four = $count - 3;

                for( $i = $last_four ; $i <= $count; $i++ ){
                    if( ! in_array( $i, $_arr ) && $i <= $count ){
                        $_arr[] = $i;
                    }
                }


            }else{
                for( $i = 1 ; $i <= $count ; $i++ ){
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

    public function singleFromCollection( $id )
    {

    }

    public function getPaginationData()
    {
        return [
            'page_count' => $this->getPageCount(),
            'current_page' => $this->getCurrentPage(),
            'total' => $this->getTotal(),
            'limit' => $this->getLimit()
        ];
    }

}