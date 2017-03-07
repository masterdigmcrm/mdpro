<?php

namespace App\Models\Properties;

use App\Http\Models\Accounts\Api;
use App\Http\Models\Users\UserEntity;
use Illuminate\Http\Request;
use MlsConnector\MlsConnector;

class Properties{

    public static function getProperties( UserEntity $user , Request $request )
    {
        $account_id = $user->getAccountId();
        $api_access = Api::getByAccountId( $account_id );

        $client     = new MlsConnector( $api_access->api_key , $api_access->api_token , getenv( 'MLS_API_ENDPOINT' ) , 'v2' );
        $options    = [];

        if( $request->min_listprice ){
            $options[ 'min_listprice'] = $request->min_listprice;
        }

        if( $request->max_listprice ){
            $options[ 'max_listprice'] = $request->max_listprice;
        }

        if( $request->bathrooms ){
            $options[ 'bathrooms' ] = $request->bathrooms;
        }

        if( $request->bedrooms ){
            $options[ 'bedrooms'] = $request->bedrooms;
        }

        if( $request->mlsid ){
            $options['mlsid'] = $request->mlsid;
        }

        if( $request->status ){
            $options['status'] = $request->status;
        }

        if( $request->property_type ){
            $options['property_type'] = $request->property_type;
        }

        if( $request->q ){
            $options['q'] = $request->q;
        }


        $properties = $client->getProperties( $options );

        return $properties;
    }

    public static function displayPrice( $p )
    {
        return ' $'.number_format( $p->ListPrice , 0 );
    }

    public static function getAddress( $p, $mls,  $complete = false , $linked = false )
    {
        $street_address = $p->StreetNumber.' '.$p->StreetName.' '.$p->StreetSuffix.', '.$p->City;
        $address = ucwords( strtolower( $street_address ) );

        if( $complete ){
            $address = $address.' '.$p->State.' '.$p->PostalCode;
        }

        if( $linked ){
            return '<a href="'.Url( 'property/'.$mls.'/'.$p->ListingId ).'">'.$address.'</a>';
        }

        return $address;

    }

    public static function getPrimaryPhoto( $p )
    {
        return $p->PrimaryPhotoUrl ? $p->PrimaryPhotoUrl : Url( 'assets/icons/house.png');
    }

    public static function typesSelect( $default = 0 )
    {

        $types = static::getPropertyTypes();
        $t_array[0] = 'Select';

        foreach( $types as $t ){
            $t_array[ $t ] = $t;
        }

        return \Form::select( 'property_type' , $t_array , $default , [ 'class' => 'form-control' , 'id'=>'property_type' ] );
    }

    public static function getPropertyTypes(  )
    {

        $user = UserEntity::instance('me');

        $account_id = $user->getAccountId();
        $api_access = Api::getByAccountId( $account_id );

        // check if property types is already saved
        $api_params   = unserialize( $api_access->params );

        if( isset( $api_params['property_types'] ) ){
            // skip API calls to make things faster
            return $api_params['property_types'];
        }

        $client     = new MlsConnector( $api_access->api_key , $api_access->api_token , getenv( 'MLS_API_ENDPOINT' ) , 'v2' );
        $response = $client->getPropertyTypes();

        if( isset( $response->result ) && $response->result == 'success'){


            $api_params['property_types'] = $response->types;
            $api_access->params = serialize( $api_params );

            $api_access->save();
            return $response->types;
        }

        return [];
    }

    public static function bedsSelect( $default = 0 )
    {
        $b_array[ 0 ] = 'Select';

        for( $i = 1; $i <= 10 ; $i++ ){
            $b_array[ $i ] = $i;
        }

        return \Form::select( 'bedrooms' , $b_array , $default , [ 'class' => 'form-control' , 'id'=>'beds' ] );
    }

    public static function bathsSelect( $default = 0 )
    {
        $b_array[ 0 ] = 'Select';

        for( $i = 1; $i <= 10 ; $i++ ){
            $b_array[ $i ] = $i;
        }

        return \Form::select( 'bathrooms' , $b_array , $default , [ 'class' => 'form-control' , 'id'=>'bathrooms' ] );
    }
}