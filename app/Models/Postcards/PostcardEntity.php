<?php

namespace App\Models\Postcards;

use App\Http\Models\Users\UserEntity;
use App\Http\Models\Users\UserMap;
use App\Models\BaseModel;
use Helpers\ImageHelper;
use Helpers\Utils;
use Illuminate\Http\Request;

class PostcardEntity extends BaseModel{

    public $table = 'postcards';
    public $primaryKey = 'postcard_id';

    public $timestamps = false;

    protected $fillable = [ 'postcard_id', 'postcard_name',
        'account_id' , 'front' , 'back' , 'dimension'];

    public function store( Request $r )
    {
        $validator = \Validator::make( $r->all() , [
            // validation rules here
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

        }
        $this->save();

        return $this;
    }

    public function upload( Request $r )
    {
        // check if postcard has an id already
        if( ! $this->postcard_id ){
            $this->errors[] = 'Postcard was not set';
            return false;
        }

        if( ! $r->file( $r->file_name ) ){
            $this->errors[] = 'Postcard file not found';
            return false;
        }

        if( ! $r->user()->id ){
            $this->errors[] = 'Session expired';
            return false;
        }


        $valid_files = [ 'png', 'jpeg',  'jpg'  ];
        $ext = $r->file( $r->file_name )->getClientOriginalExtension();

        if( ! in_array( strtolower( $ext ) , $valid_files  ) ){
            $this->errors[] = 'Invalid file type. Only png and jpg files are allowed';
            return false;
        }

        $orig_filename  = $r->file( $r->file_name )->getClientOriginalName();

        $new_filename   = 'pc_'.str_random( 8 ).'.'.$ext;
        $uid            = Utils::convertInt( $r->user()->id );
        $lt             = substr( $uid, -3 );

        $destination = $this->generatePostcardPath( $r );
        $url = url( $destination.$new_filename );

        $destination  = public_path().''.$destination;

        if( ! is_dir( $destination )){
            mkdir( $destination , 755 , true );
        }

        $r->file( $r->file_name )->move( $destination, $new_filename );
        $file_path  = $destination.$new_filename;

        // check if image is valid size

        $image = \Image::make( $file_path );
        $dimension = explode( '-' , $r->dimension );

        if( $image->width() != $dimension[1] ){
            // delete image
            unlink( $file_path );
            $this->errors[] = 'Invalid image width of '.$image->width().'px. Expecting '.$dimension[1].'px';
            return false;
        }

        if(  $image->height() != $dimension[0]  ){
            // delete image
            unlink( $file_path );
            $this->errors[] = 'Invalid image height of '.$image->height().'px. Expecting '.$dimension[0].'px';
            return false;
        }

        ImageHelper::generateThumbNails( $file_path , [] , $image  );

        $section = $r->section;
        $this->$section = $url;
        $this->dimension = $r->dimension;
        $this->save();

        return $this;
    }

    public function unlinkImages()
    {

    }

    private function generatePostcardPath( Request $r )
    {
        $date = $r->user()->registerDate;
        $m =  date( 'm' , strtotime( $date ));
        $y =  date( 'Y' , strtotime( $date ));

        return '/images/users/'.$m.'/'.$y.'/'.Utils::convertInt( $r->user()->id ).'/postcard/';
    }
}