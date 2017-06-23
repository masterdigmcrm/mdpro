<?php


namespace App\Models\Accounts;


use App\Models\BaseModel;
use App\Models\Users\UserEntity;

class AccountEntity extends  BaseModel{
    protected $table = 'jos_mdigm_broker';
    protected $primaryKey = 'brokerid';

    public $timestamps = false;

    private static $instance;

    public static function me()
    {
        if( isset( static::$instance )){
            return static::$instance;
        }

        static::$instance = UserEntity::me()->userMap->account;

        return static::$instance;
    }

    /**
     * @param $user_id
     * @return static
     */
    public function byUserId( $user_id )
    {
        return static::where( 'userid' , $user_id )->first();
    }

    public function getParamByKey( $key )
    {
        $params = unserialize( $this->params );

        if( isset( $params[ $key ] )){
            return $params[ $key ];
        }
        return null;
    }
    
    public function getLogoImage(  )
    {
        if( substr( $this->logo, 0 , 4 ) == 'http' ){
            $logo_url	=	$this->logo;
        }else{
            $logo_url	=	'http://www.masterdigm.com/images/brokers/logo/'.$this->logo;
        }

        $logo	= 	'<img src="'.$logo_url.'" border="0" />';

        return $logo;
    }

}