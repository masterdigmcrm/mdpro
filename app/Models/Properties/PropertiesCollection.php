<?php


namespace App\Models\Properties;


use App\Models\Users\UserEntity;
use Illuminate\Http\Request;

class PropertiesCollection extends PropertyEntity{

    public function getCollection( Request $r )
    {
        $this->setLpo( $r );
        $this->order_by = 'added_at';
        $this->order_direction = 'DESC';

        $fields = [ 'p.*' , 'p.stateid as stateid', 's.status' , 't.type' , 'co.country' , 'st.state' , 'ci.city' ];

        $query = static::from( $this->table.' as p' )
         ->with( ['photos']);

        $account = UserEntity::me()->userMap->account;
        $account_id = $account->brokerid;

        $query->where( 'p.brokerid' , $account_id );

        $query->leftJoin( 'jos_mdigm_countries as co' , 'p.countryid' , '=' ,'co.countryid' );
        $query->leftJoin( 'jos_mdigm_states as st' , 'p.stateid' , '=' ,'st.id' );
        $query->leftJoin( 'jos_mdigm_type as t' , 'p.property_type' , '=' ,'t.typeid' );
        $query->leftJoin( 'jos_mdigm_cities as ci' , 'p.cityid' , '=' ,'ci.id' );
        $query->leftJoin( 'jos_mdigm_status as s', function( $join ) use( $account_id ) {
            $join->on('p.property_status', '=', 's.statusid')
                ->where('s.brokerid' , $account_id );
        });

        if( $r->propertyid ){
            $query->where( 'p.id' , $r->propertyid );
            $this->collection =  $query->get( $fields );
            return $this->vuefyThisCollection();
        }

        if( $r->beds ){
            $query->where( 'beds' , '>=' , $r->beds );
        }

        if( $r->baths ){
            $query->where( 'baths' , '>=' , $r->baths );
        }

        $min_price = str_replace( ',' ,'', $r->min_price );
        $max_price = str_replace( ',' ,'', $r->max_price );

        if( $min_price && $max_price ){
            $query->where( 'price' , '>=' , $min_price );
            $query->where( 'price' , '<=' , $max_price );

        }elseif( $min_price ){
            $query->where( 'price' , '>=' , $min_price );
        }elseif( $max_price ){
            $query->where( 'price' , '<=' , $max_price );
        }

        if( $r->typeid ){
            $query->where( 'typeid' , $r->typeid );
        }

        $this->total = $query->count();
        $query = $this->assignLpo( $query );

        $this->collection =  $query->get( $fields );

        return $this->vuefyThisCollection();
    }
}