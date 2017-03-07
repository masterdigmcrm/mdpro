<?php

namespace App\Models\Leads;

use Illuminate\Database\Eloquent\Model;

class LeadStatus extends Model{

    protected $table = 'jos_mdigm_leads_status_map';
    protected $primaryKey = 'id';
    public $timestamps = false;

    /**
     * prevents multiple calls to status
     * @param $accountid
     * @return mixed
     */

    static $status_by_account = [];

    public static function byBrokerId( $accountid )
    {
        if( isset( static::$status_by_account[ $accountid ] ) ){
            return static::$status_by_account[ $accountid ];
        }

        $status = static::where( 'm.brokerid' , $accountid )
            ->from( 'jos_mdigm_lead_status_map as m')
            ->join( 'jos_mdigm_lead_status as s' , 's.statusid', '=' , 'm.statusid' )
            ->orderBy( 'ordering' , 'ASC' )
            ->get();


        foreach( $status as $t ){
            static::$status_by_account[ $accountid ][ $t->statusid ] = $t->status;
        }

        return static::$status_by_account[ $accountid ];

    }

    public static function selectList( $accountid , $default = 0)
    {
        $s_array = [ '0' => 'Select Status' ];
        $status = static::byBrokerId( $accountid);

        foreach( $status as $k => $v ){
            $s_array[ $k ] = $v;
        }

        return \Form::select( 'status' , $s_array , $default , [ 'id' => 'statusid', 'class' => 'form-control',  'value' =>'{{lead.statusid}}' ] );
    }

}