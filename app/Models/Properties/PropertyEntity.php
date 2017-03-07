<?php

namespace App\Models\Properties;

use App\Http\Models\Photos\PhotoEntity;
use App\Http\Models\Photos\PhotosCollection;
use App\Models\BaseModel;
use App\Models\Users\UserEntity;
use Helpers\Utils;
use Illuminate\Http\Request;

class PropertyEntity extends BaseModel{

    protected $table = 'jos_mdigm_properties';
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [ 'id', 'assigned_to' , 'address' , 'countryid', 'stateid', 'cityid', 'postal_code',
        'price', 'beds', 'baths', 'floor_area' , 'lot_area', 'year_built' , 'garage', 'property_type',
        'property_status', 'tag_line', 'description', 'transaction_type' , 'lead_status' , 'lead_type'
    ];

    public function store( Request $r )
    {
        $validator = \Validator::make( $r->all() , [
            // validation rules here
            'address'   => 'required',
            'tag_line'  => 'required',
            'price'     => 'required'
        ] );

        if( $validator->fails() ){
            $this->errors = $validator->errors()->all();
            return false;
        }

        $this->fill( $r->all() );
        $pk = $this->primaryKey;


        if( $r->$pk  ){
            $this->exists = true;
        }else{

            $account = UserEntity::me()->userMap->account;

            $this->added_at = date('Y-m-d H:i:s');
            $this->lead_status = $r->lead_status ? $r->lead_status : 0 ;
            $this->lead_type = $r->lead_type ? $r->lead_type : 0 ;
            $this->communityid = $r->communityid ? $r->communityid : 0 ;
            $this->subdivisionid = $r->subdivisionid ? $r->subdivisionid : 0;
            $this->countyid = $r->countyid ? $r->countyid : 0;
            $this->countryid = $r->countryid ? $r->countryid : 0;
            $this->assigned_to = $r->assigned_to ? $r->assigned_to : 0;
            $this->ownerid      = $account->userid;
            $this->brokerid     = $account->brokerid;
            $this->idx     = $r->idx ? $r->idx : '';
            $this->slug     = Utils::slugify( $r->address );
        }

        /***** dirty assignments ***/
        $this->postal_code = $this->postal_code ? $this->postal_code : '';
        $this->price =str_replace(',', '', $r->price );

        if( ! $this->save() ){
            $this->errors[] = 'Error saving property';
            return false;
        }

        return $this;
    }

    public function vuefy()
    {
        $this->p_photo = new \stdClass();

        if( count( $this->photos )){
            foreach( $this->photos as $k => $p){
                if( $p->is_primary == 1 ){
                    $this->p_photo = $p;
                    break;
                }
            }
        }
        return $this;
    }

    public function getTagLineAttribute( $value )
    {
        return $value ? $value : 'No tag line';
    }

    public function getPostedAtAttribute( $value )
    {
        return date( 'M d, Y' , strtotime( $value ));
    }

    public function primaryPhoto()
    {
        return $this->hasOne( PhotoEntity::class , 'propertyid' , 'id', function( $query ){
            $query->where('is_primary',  1 );
        });
    }

    public function photos()
    {
        return $this->hasMany( PhotosCollection::class , 'propertyid' , 'id' );
    }

    public function getPriceAttribute( $value )
    {
        return number_format( $value , 2 );
    }

    public function getCityAttribute( $value )
    {
        return ucwords(strtolower( $value ));
    }

    /**
    public function setPriceAttribute( $value )
    {
        return str_replace(',','',$value );
    }
     ***/
}