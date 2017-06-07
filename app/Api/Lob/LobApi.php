<?php

namespace App\Api\Lob;

use App\Models\Postcards\PostcardCollection;

class LobApi{

    private $lob;
    private $key;

    public function __construct( $key = '' )
    {
        $this->key = $key ? $key : env( 'LOB_TEST_KEY' );
        $this->lob = new \Lob\Lob( $this->key );
    }

    public function createPostcard( $to_address , $from_address , $options = [] )
    {
        $file = file_get_contents( public_path().'/api/lob/card.html');
        $to_address = $this->lob->addresses()->create( $to_address );
        $from_address = $this->lob->addresses()->create( $from_address );

        $postcard = $this->lob->postcards()->create(array(
            'to'            => $to_address['id'],
            'from'          => $from_address['id'],
            'front'         => $file,
            'message'       => 'Happy Graduation to You and Me',
            'data[name]'    => 'Gray Lee'
        ));

        print_r($postcard);
    }

    public function sendPostcard( $postcard,  $to_address , $from_address , $options = [] )
    {
        $front  = view( 'postcards.p'.$postcard->postcard_id.'_front');
        $back   = view( 'postcards.p'.$postcard->postcard_id.'_back');

        $to_address     = $this->lob->addresses()->create( $to_address );
        $from_address   = $this->lob->addresses()->create( $from_address );

        $postcard = $this->lob->postcards()->create( array(
            'to'            => $to_address['id'],
            'from'          => $from_address['id'],
            'front'         => $front,
            'back'          => $back,
        ));

        return $postcard;
    }

    public function sendMarketingPostcards( $postcard,  $to_address , $from_address , $options = [] )
    {

        $to_address     = $this->lob->addresses()->create( $to_address );
        $from_address   = $this->lob->addresses()->create( $from_address );

        $postcard = $this->lob->postcards()->create( array(
            'to'            => $to_address['id'],
            'from'          => $from_address['id'],
            'front'         => $postcard->front,
            'back'          => $postcard->back,
        ));

        return $postcard;
    }
}