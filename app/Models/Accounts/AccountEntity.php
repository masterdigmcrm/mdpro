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

    public function getParamByKey( $key )
    {
        $params = unserialize( $this->params );
        if( isset( $params[ $key ] )){
            return $params[ $key ];
        }
        return null;
    }

}