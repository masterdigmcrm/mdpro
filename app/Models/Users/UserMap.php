<?php
namespace App\Models\Users;

use App\Models\Accounts\AccountEntity;
use App\Models\Contacts\ContactEntity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UserMap extends Model
{
    protected $table = 'jos_mdigm_broker_user_map';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function account()
    {
        return $this->hasOne( AccountEntity::class, 'userid', 'broker_userid' );
    }

    /**
     * @param $userid
     * @return static
     */
    public static function byUserId( $userid , $options = [] )
    {
            $with = ['account'];
            $user = static::where( 'a.userid' , $userid )
                ->from( 'jos_mdigm_broker_user_map as a' )
                ->leftJoin( 'jos_mdigm_contacts as c' , 'c.contactid', 'a.contactid' )
                ->with( ['account' ] );

            return $user->first();


    }

    public function contact( )
    {
        return $this->hasOne( ContactEntity::class , 'contactid' , 'contactid'  );
    }

    public function getParamValue( $value )
    {
        if( ! $this->params ){
            return null;
        }

        $params = unserialize( $this->params );
        if( ! isset( $params[ $value ])){
            return null;
        }

        return $params[ $value ];
    }

    public function is_manager()
    {
        return $this->broker_userid == $this->userid ? true : false ;
    }
    /**
     * This will set all user relationships like
     * who are underneath or who are supervisors for this user
     */
    public function getAllSubordinates( $options =[])
    {
        $users_container = [];

        if( $this->broker_userid == $this->userid ){

            // get all user accounts under this user map

            $users_builder = UserMap::where( 'broker_userid' ,   $this->userid )
                ->from( 'jos_mdigm_broker_user_map as a' )
                ->join( 'jos_users as b' , 'b.id', '=', 'a.userid' )
                ->orderby( 'name')
                ->select( 'b.*' );
        }else{

            $users_builder = UserMap::where( 'supervisor_userid' ,   $this->userid )
                ->from( 'jos_mdigm_broker_user_map as a' )
                ->join( 'jos_users as b' , 'b.id', '=', 'a.userid' )
                ->orderby( 'name' )
                ->select( 'b.*' );

        }

        // this
        if( ! empty( $options['from-active-users-only'] ) ){
            $users_builder->where( 'a.status' , 'active' );
        }

        $users = $users_builder->get();

        if( ! empty($options['ids-only']) ){

            foreach( $users as $u ){
                $users_container[] = $u->id;
            }

            return $users_container;
        }

        return $users;

    }

    public function subordinateSelectList( $default = 0)
    {

        $children = $this->getAllSubordinates();
        $c_array = [ '0' => trans( 'general.select') ];

        foreach( $children as $child ){
            $c_array[ $child->id ] = $child->name;
        }

        return \Form::select('assigned_to', $c_array , $default, [ 'id' => 'assigned_to', 'class' => 'form-control' ] );
    }

    public function getPictureHTML()
    {
        if( !$this->picture ){
            return null;
        }

        if( substr( $this->picture, 0 , 4 ) == 'http' ){
            $photo_url	=	$this->picture;
        }else{
            $photo_url	=	'http://www.masterdigm.com/images/brokers/'.$this->picture;
        }

        $photo	= 	'<img src="'.$photo_url.'" border="0" />';

        return $photo;

    }
}