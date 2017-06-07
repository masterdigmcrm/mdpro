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

        $this->query = static::from( $this->table.' as p' )
         ->with( ['photos']);

        $account = UserEntity::me()->userMap->account;
        $account_id = $account->brokerid;

        $this->query->where( 'p.brokerid' , $account_id );

        $this->query->leftJoin( 'jos_mdigm_countries as co' , 'p.countryid' , '=' ,'co.countryid' );
        $this->query->leftJoin( 'jos_mdigm_states as st' , 'p.stateid' , '=' ,'st.id' );
        $this->query->leftJoin( 'jos_mdigm_type as t' , 'p.property_type' , '=' ,'t.typeid' );
        $this->query->leftJoin( 'jos_mdigm_cities as ci' , 'p.cityid' , '=' ,'ci.id' );
        $this->query->leftJoin( 'jos_mdigm_status as s', function( $join ) use( $account_id ) {
            $join->on('p.property_status', '=', 's.statusid')
                ->where('s.brokerid' , $account_id );
        });

        if( $r->propertyid ){
            $this->query->where( 'p.id' , $r->propertyid );
            $this->collection =  $this->query->get( $fields );
            return $this->vuefyThisCollection();
        }

        if( $r->beds ){
            $this->query->where( 'beds' , '>=' , $r->beds );
        }

        if( $r->baths ){
            $this->query->where( 'baths' , '>=' , $r->baths );
        }

        $min_price = str_replace( ',' ,'', $r->min_price );
        $max_price = str_replace( ',' ,'', $r->max_price );

        if( $min_price && $max_price ){
            $this->query->where( 'price' , '>=' , $min_price );
            $this->query->where( 'price' , '<=' , $max_price );

        }elseif( $min_price ){
            $this->query->where( 'price' , '>=' , $min_price );
        }elseif( $max_price ){
            $this->query->where( 'price' , '<=' , $max_price );
        }

        if( $r->typeid ){
            $this->query->where( 'typeid' , $r->typeid );
        }

        $this->total = $this->query->count();
        $this->query = $this->assignLpo( $this->query );

        $this->collection =  $this->query->get( $fields );

        return $this->vuefyThisCollection();
    }
}