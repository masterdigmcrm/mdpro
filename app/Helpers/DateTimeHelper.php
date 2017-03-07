<?php


namespace Helpers;


class DateTimeHelper {

    public static function yearSelect( $options = [] )
    {
        $default_options = [
            'start' => 1990,
            'end'   => date('Y'),
            'name'  => 'year',
            'value' => 0,
            'id'    =>'year',
            'class' =>'form-control'
        ];

        $f_options = array_replace( $default_options , $options );

        if( isset($f_options['with_all']) && $f_options['with_all'] == true ){
            $arr[ 0 ] = 'All';
        }

        $start = $f_options['start'];
        $end =$f_options['end'];
        $arr = [] ;

        for( $i = $end ; $i > $start  ; $i--){
            $arr[$i] = $i;
        }

        $attr = [
            'value' => $f_options['value'],
            'id'    => $f_options['id'],
            'class' => $f_options['class']
        ];

        // support to vue change event
        if( isset( $f_options['@change'] )){
            $attr['@change'] = $f_options['@change'];
        }

        if( isset( $f_options['v-model'] )){
            $attr['v-model'] = $f_options['v-model'];
        }

        return \Form::select( $default_options['name'] , $arr , $f_options[ 'value' ] , $attr );
    }
}