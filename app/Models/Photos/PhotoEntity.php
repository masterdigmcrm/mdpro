<?php


namespace App\Http\Models\Photos;


use App\Models\BaseModel;
use Helpers\ImageHelper;
use Helpers\Utils;
use Illuminate\Http\Request;

class PhotoEntity extends BaseModel{

    protected $table = 'jos_mdigm_property_photos';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function vuefy()
    {
        return $this;
    }

    public function getIdAttribute( $value )
    {
        return Utils::convertInt( $value );
    }

    public function upload( Request $r )
    {
        if( ! $r->file( 'photo' ) ){
            $this->errors[] = 'Photo file not found';
            return false;
        }

        if( ! $r->user()->id ){
            $this->errors[] = 'Session expired';
            return false;
        }

        if( ! $r->propertyid ){
            $this->errors[] = 'Property ID not found';
            return false;
        }

        $valid_files = [ 'png', 'jpeg',  'jpg' , 'gif' ];
        $ext = $r->file( 'photo' )->getClientOriginalExtension();

        if( ! in_array( strtolower( $ext ) , $valid_files  ) ){
            $this->errors[] = 'Invalid file type. Only png,jpg and gif files are allowed';
            return false;
        }

        $orig_filename  = $r->file( 'photo' )->getClientOriginalName();
        $new_filename   = 'p_'.str_random( 8 ).'.'.$ext;
        $uid    = Utils::convertInt( $r->user()->id );
        $lt     = substr( $uid, -3 );

        $destination = $this->generatePhotoPath( $r );
        $url = url( $destination.$new_filename );

        $destination  = public_path().''.$destination;

        if( ! is_dir( $destination )){
            mkdir( $destination , 755 , true );
        }

        $r->file( 'photo' )->move( $destination, $new_filename );
        $file_path  = $destination.$new_filename;

        ImageHelper::generateThumbNails( $file_path );

        $photo_count = PhotosCollection::where( 'propertyid' , $r->propertyid )
            ->count();

        $this->filename    = $new_filename;
        $this->propertyid  = $r->propertyid;
        $this->is_primary  = $photo_count ? '0' : '1';
        $this->added_at = date('Y-m-d H:i:s');
        $this->url = $url;
        $this->catid = 0;
        $this->is_transfer = 0;
        $this->params = '';
        $this->ordering = $photo_count + 1;
        
        $this->save();

        return $this;
    }

    public function remove()
    {

        if( file_exists( $this->absolutePath() )){
            unlink( $this->absolutePath() );
        }

        if( file_exists(  $this->thumbPath() )){
            unlink( $this->thumbPath() );
        }

        if( file_exists(  $this->thumbXsPath() )){
            unlink( $this->thumbXsPath() );
        }

        $this->delete();

        // unlink
    }

    public function absolutePath()
    {
        $url        = $this->url;
        $app_url    = env( 'APP_URL' );
        $path       = str_replace( $app_url , base_path() , $url );

        return $path;
    }

    public function thumbPath()
    {
        $path = $this->absolutePath();
        $basefile = basename( $path );

        return str_replace( $basefile , 'thumb/thumb_'.$basefile , $path );
    }

    public function thumbXsPath()
    {
        $basefile = basename( $this->photo_path );
        return str_replace( $basefile , 'thumb/xs_'.$basefile , $this->photo_path );
    }

    public function thumbUrl()
    {
        $basefile = basename( $this->url );
        return str_replace( $basefile , 'thumb/thumb_'.$basefile , $this->url );
    }

    public function thumbXsUrl()
    {
        $basefile = basename( $this->url );
        return str_replace( $basefile , 'thumb/xs_'.$basefile , $this->url );
    }

    private function generatePhotoPath( Request $r )
    {
        $date = $r->user()->registerDate;
        $m =  date( 'm' , strtotime( $date ));
        $y =  date( 'Y' , strtotime( $date ));

        return '/images/users/'.$m.'/'.$y.'/'.Utils::convertInt( $r->user()->id ).'/';
    }
}