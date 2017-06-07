<?php

namespace App\Http\Controllers\Components\Postcards;

use App\Models\Accounts\AccountEntity;
use App\Models\Marketing\ActionTriggerMap;
use App\Models\Marketing\CampaignActionCollection;
use App\Models\Postcards\PostcardCities;
use App\Models\Postcards\PostcardCollection;
use App\Models\Postcards\PostcardEntity;
use App\Models\Postcards\PostcardPropertiesQueue;
//use App\Models\Postcards\PostcardsQueue;
//use App\Models\Properties\Properties;
//use App\Models\Properties\Sources\HomeJunctionProperties;
use App\Models\Users\UserEntity;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PostcardsAjaxController extends Controller
{

    public function savePostcard( Request $r )
    {
        $postcard =  new PostcardEntity;

        if( ! $postcard->store( $r ) ){
            return [
                'success' => false,
                'message' => $postcard->displayErrors()
            ];
        }

        return [
            'success' => true,
            'postcard' => $postcard
        ];
    }
    
    public function uploadPostcard( $section, Request $r )
    {
        $postcard = ( new PostcardEntity )->f( $r->postcard_id );

        if( ! $postcard ){
            return [
                'success' => false,
                'message' => 'Postcard not found'
            ];
        }

        if( $postcard->account_id != AccountEntity::me()->brokerid ){
            return [
                'success' => false,
                'message' => 'Account does not match postcard owner'
            ];
        }

        $r->merge( [ 'file_name' => 'postcard_'.$section,
            'section' => $section ] );

        if( ! $postcard->upload( $r ) ){
            return [
                'success' => false,
                'message' => $postcard->displayErrors()
            ];
        }

        return [
            'success' => true,
            'postcard' => $postcard,
            'section' => $section
        ];
    }

    public function deletePostcard( Request $r )
    {
        // only manager can delete postcards
        if( AccountEntity::me()->userid != $r->user()->id ){
            return [
                'success' =>false,
                'message' => 'Only managers are allowed to delete postcards'
            ];
        }

        $postcard = ( new PostcardEntity )->f( $r->postcard_id );
        if( ! $postcard ){
            return [
                'success' =>false,
                'message' => 'Postcard not found'
            ];
        }

        $postcard->unlinkImages();
        $postcard->delete();

        // get all actions using the postcard
        $r->merge(['get_all' => true ]);
        $actions = ( new CampaignActionCollection )->getCollection( $r );

        if( count( $actions )){

            $a_arr = $actions->pluck( 'actionid' )->toArray();

            $a = ActionTriggerMap::where( 'status' , 'onqueue' )
                ->whereIn( 'actionid' , $a_arr )
                ->update( ['status' => 'cancelled' ] );
        }

        return [
            'success' => true
        ];
    }

    public function getCities( Request $r )
    {
        //$hj = new HomeJunctionProperties();
        //$cities = $hj->getLocationLookups();
        $cities = [];

        $postcard_cities = PostcardCities::where('postcard_id', $r->postcard_id )->get( ['city'] );
        $c_array = [];
        $l_array = [];

        foreach( $postcard_cities as $c )
        {
            $c_array[] = $c->city;
        }

        foreach( $cities as $c ){
            $std = new \stdClass();
            $std->full      =  $c['full'];
            $std->no_space  =  str_replace( ' ', '_', $c['full'] );
            $std->is_added  =  in_array( $c['full'] , $c_array ) ? 1 : 0;
            $l_array[] = $std;
        }

        return [
            'success' =>true,
            'c_array' => $c_array,
            'lookups'=> $l_array
        ];
    }

    public function toggleCity( Request $r )
    {
        if( ! $r = PostcardCities::toggle( $r ) ){
            [
                'success' => 'false',
                'message' => 'Something went wrong'
            ];
        }

        return [
           'success' => 'true',
            'action' => $r['action'],
            'city' => $r['city']
        ];
    }

    public function getPostcardProperties( Request $r )
    {
        if( ! $p_id = $r->postcard_id ){
            return [
                'success' => false,
                'message' => 'Invalid postcard id'
            ];
        }

        $r->request->add( [ 'postcard_id' => $p_id ] );
        $properties  = PostcardPropertiesQueue::getPropertiesByPostcardId( $r );
        $sent_count = PostcardPropertiesQueue::unsentCount( $p_id );

        return [
           'success'    => true,
           'total'      => $properties[ 'count' ],
           'properties' => $properties[ 'properties' ],
            'sent_count' => $sent_count
        ];

    }

    public function getPostcards( Request $request )
    {
        if( ! $aid = $request->aid  ){
            $aid = AccountEntity::me()->brokerid;
        }

        $postcards  = PostcardCollection::getByAccountId( $aid );
        return [
            'success' => true,
            'postcards'=> $postcards
        ];
    }

    public function sendPostcardsToPropertyOwners( Request $r )
    {
        $properties = explode( "|", $r->ps );

        if( ! count( $properties )){
            return [
                'success' => false,
                'message' => 'No property found'
            ];
        }

        $cnt = 0;
        /**
        foreach( $properties as $p ){
            // $p is a form property_id::address
            $p_obj   = json_decode( $p );
            $address = HomeJunctionProperties::address( $p_obj );
            // check if property was sent with a postcard already

            if( ! PostcardPropertiesQueue::isQueued( $address , $r->postcard_id )){
                // push to queue if not
                PostcardPropertiesQueue::store( $p_obj , $r->postcard_id ,  $r->type );
                $cnt++;
            }
        }
        **/
        return [
            'success' => true,
            'cnt'   => $cnt
        ];
    }

}