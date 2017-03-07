<?php
/**
 * Created by PhpStorm.
 * User: Dennis
 * Date: 10/1/2015
 * Time: 6:10 PM
 */

namespace App\Http\Models\Properties;


use App\Http\Models\Accounts\Api;
use App\Http\Models\Users\UserEntity;
use Illuminate\Database\Eloquent\Model;
use MlsConnector\MlsConnector;
use Mockery\CountValidator\Exception;

class Mls extends Model{

    protected $table        = 'jos_mdigm_mls';
    protected $primaryKey   = 'mlsid';

    public function getFields()
    {
        $user = UserEntity::instance('me');
        if( ! $account_id     = $user->getAccountId() ){
            throw new Exception( 'Account id not found' );
        }
        $api_access     = Api::getByAccountId( $account_id );

        $client     = new MlsConnector( $api_access->api_key , $api_access->api_token , getenv( 'MLS_API_ENDPOINT' ) , 'v2' );
        $client->getPropertyTypes();
    }
}