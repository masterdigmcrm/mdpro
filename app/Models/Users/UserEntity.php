<?php
namespace App\Models\Users;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;

class UserEntity extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    protected $table = 'jos_users';
    protected $primaryKey = 'id';

    protected $fillable = ['name', 'username', 'email'  ];

    protected $hidden = ['password'];

    /***
     * a container of UserEntities
     * @var array
     */
    private static $instance = [];


    public function getRememberToken()
    {
        return null; // not supported
    }

    public function setRememberToken($value)
    {
        // not supported
    }

    public function getRememberTokenName()
    {
        return null; // not supported
    }

    /**
     * Overrides the method to ignore the remember token.
     */
    public function setAttribute($key, $value)
    {
        $isRememberTokenAttribute = $key == $this->getRememberTokenName();
        if (!$isRememberTokenAttribute)
        {
            parent::setAttribute($key, $value);
        }
    }

    public function getAccountId()
    {
        if( ! $this->id ){
            return false;
        }

        $user_map = UserMap::where( 'jos_mdigm_broker_user_map.userid' , $this->id )
            ->join( 'jos_mdigm_broker as b' , 'b.userid' , '=' , 'jos_mdigm_broker_user_map.broker_userid' )
            ->first();

        if( isset( $user_map->brokerid ) ){
            return $user_map->brokerid;
        }

        return false;

    }

    public function userMap()
    {
        return $this->hasOne( UserMap::class, 'userid' ,'id' );
    }

    public static function instance( $key , $value = null )
    {
        if( $value === null ){
            if( isset( static::$instance[ $key ] ) ){
                return static::$instance[ $key ];
            }
            return null;
        }

        return static::$instance[$key] = $value;

    }

    /**
     * me is the instance created on controller constructor to identify the current user
     * @return static
     */
    public static function me()
    {
        if( isset( static::$instance[ 'me' ] ) ){
            return static::$instance[ 'me' ];
        }
        return null;
    }

}