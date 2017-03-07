<?php


namespace Helpers;


class Utils {

    public static function isJson( $string ) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
    /**
     * used to add some numbers to id so ids won't be easily incremented
     * just hiding real user id value
     * @param $int
     * @return string
     */
    public static function convertInt( $int )
    {
        $compute =  ( $int * 4197 ) + 91214;
        return '100'.strval( $compute );
    }

    /*
     * function used to recover the real id value from convertInt
     */
    public static function recoverInt( $int )
    {
        $id = substr( $int , 3 );
        $id = $id - 91214;
        $id = $id / ( 4197 );

        return $id ;
    }
    /**
     * unserialize a string with support to multibyte characters
     * @param $string
     * @return mixed
     */
    public static function mb_unserialize( $string ) {
        if( !is_string( $string )){
            return null;
        }

        $string = preg_replace_callback(
            '!s:(\d+):"(.*?)";!s',
            function ($matches) {
                if ( isset( $matches[2] ) )
                    return 's:'.strlen($matches[2]).':"'.$matches[2].'";';
            },
            $string
        );

        $str = '';

        try{
            $str = unserialize( $string );
        }catch( \Exception $e ){

        }

        return $str;
    }

    /**
     * create a slug
     *
     * @param $text
     * @return mixed|string
     */
    public static function slugify( $text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}