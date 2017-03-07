<?php

namespace App\Models\Leads;

use App\Models\Accounts\AccountEntity;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class LeadSource extends BaseModel{

    protected $table = 'jos_mdigm_sources';
    protected $primaryKey = 'sourceid';
    public $timestamps = false;

    public function getCollection( Request $r )
    {
        $this->setLpo( $r );

        $account = AccountEntity::me();
        $fields = [ 's.*' ];

        $query = static::from( $this->table,' as s' );
        $query->where( 'ownerid' , $account->userid );

        $this->total = $query->count();

        $query->limit( $this->limit );
        $query->offset( $this->offset );
        $query->orderBy( $this->order_by , $this->order_direction );

        return $query->get();
    }
    /**
     * prevents multiple calls to status
     * @param $accountid
     * @return mixed
     */

    static $sources_by_account = [];

    public static function byBrokerUserId( $broker_userid )
    {
        if( isset( static::$sources_by_account[ $broker_userid ] ) ){
            return static::$sources_by_account[ $broker_userid ];
        }

        $sources = static::where( 'ownerid' , $broker_userid )
            ->from( 'jos_mdigm_sources as s')
            ->orderBy('source' , 'ASC')
            ->get();

        static::$sources_by_account[ $broker_userid ] = [];

        foreach( $sources as $s ){
            static::$sources_by_account[ $broker_userid ][ $s->sourceid ] = $s->source;
        }

        return static::$sources_by_account[ $broker_userid ];

    }

    public static function selectList( $broker_userid , $default = 0)
    {
        $s_array = [ '0' => 'Select Source' ];

        $sources = static::byBrokerUserId( $broker_userid );
        foreach( $sources as $k => $v ){
            $s_array[ $k ] = $v;
        }
        //$sources = array_merge( $s_array , $sources );

        return \Form::select( 'sourceid' , $s_array , $default , [ 'id' => 'sourceid', 'class' => 'form-control' ] );
    }

}