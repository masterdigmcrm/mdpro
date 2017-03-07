<?php

namespace App\Http\Controllers\Components\Properties;

use App\Http\Models\Photos\PhotoEntity;
use App\Http\Models\Photos\PhotosCollection;
use App\Models\Locations\Cities;
use App\Models\Locations\Countries;
use App\Models\Locations\States;
use App\Models\Properties\PropertiesCollection;
use App\Models\Properties\PropertyEntity;
use App\Models\Properties\PropertyStatus;
use App\Models\Properties\PropertyTypes;
use App\Models\Users\UserEntity;
use Helpers\Layout;
use Helpers\Utils;
use Illuminate\Http\Request;


class AjaxPropertiesController {

    public function getCollection( Request $r )
    {

        $properties = new PropertiesCollection();
        $account_id = UserEntity::me()->userMap->account->brokerid;
        $r->request->add( ['account_id'=>$account_id]);

        return [
            'success' =>true,
            'properties' => $properties->getCollection( $r )
        ];
    }

    public function store( Request $r )
    {
        $property = new PropertyEntity();
        if( ! $property->store( $r ) ){
            return [
                'success' =>false,
                'message' => $property->displayErrors()
            ];
        }

        $r->request->add( ['propertyid' => $property->id ] );
        $ps = (new PropertiesCollection())->getCollection( $r );

        return [
            'success' =>true,
            'property' => $ps[0]
        ];
    }

    public function init( Request $r )
    {
        $status = new PropertyStatus();
        $types = new PropertyTypes();
        $countries = new Countries();

        $account_id = UserEntity::me()->userMap->account->brokerid;
        $r->request->add([ 'account_id' => $account_id ]);

        return [
            'success' =>true,
            'property_status'   => $status->getCollection( $r ),
            'property_types'    => $types->getCollection( $r ),
            'account_id' => $account_id,
            'countries' => $countries->getCollection( $r )
        ];
    }

    public function photoUpload( Request $r )
    {
        $photo = new PhotoEntity();
        $photo->upload( $r );

        return [
            'success' =>true,
            'photo' => $photo->vuefy()
        ];
    }

    public function photoDelete( Request $r )
    {
        $photo_id = Utils::recoverInt( $r->photo_id );
        $photo = PhotoEntity::find( $photo_id );

        if( ! $photo ){
            return [
                'success' => false,
                'message' => 'Photo not found'
            ];
        }
        $property_id = $photo->propertyid;
        $photo->remove();

        return [
            'success'       => true,
            'photo_id'      => $r->photo_id,
            'property_id'   => $property_id
        ];

    }

    public function setPrimary( Request $r )
    {
        $photo_id = Utils::recoverInt( $r->photo_id );
        $photo = PhotoEntity::find( $photo_id );

        if( ! $photo ){
            return [
                'success' => false,
                'message' => 'Photo not found'
            ];
        }

        PhotosCollection::where( 'propertyid' , $photo->propertyid )
         ->update( [ 'is_primary' =>'0' ] );

        $property_id = $photo->propertyid;
        $photo->is_primary = '1';

        if( ! $photo->save() ){
            return [
                'success' =>false,
                'message' => 'Can not save photo'
            ];
        }

        return [
            'success'       => true,
            'photo_id'      => $photo_id,
            'photo'         => $photo,
            'property_id'   => $property_id
        ];
    }

    public function getStates( Request $r )
    {
        $state = new States();

        return [
            'success' => true,
             'states' => $state->getCollection( $r )
        ];
    }

    public function getCities( Request $r )
    {
        $cities = new Cities();
        $c_arr = [];
        foreach( $cities->getCollection( $r ) as $k => $c){
            $std = new \stdClass();
            $std->id    = $c->id;
            $std->city  = $c->city;
            $c_arr[]    = $std;
        }

        $ret = new \stdClass();
        $ret->stateid = $r->stateid;
        $ret->cities = $c_arr;

        return [
            'success' => true,
            'cities' => $ret
        ];
    }

}