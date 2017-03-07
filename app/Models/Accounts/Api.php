<?php
/**
 * Created by PhpStorm.
 * User: Dennis
 * Date: 9/25/2015
 * Time: 11:01 AM
 */

namespace App\Http\Models\Accounts;


use Illuminate\Database\Eloquent\Model;

class Api extends Model{

    protected $table        =   'api_access';
    protected $primaryKey   =   'access_id';
    public $timestamps      =   false;

    public static function getByAccountId( $account_id )
    {
        return Api::where( 'accountid' , $account_id )->first();
    }

}