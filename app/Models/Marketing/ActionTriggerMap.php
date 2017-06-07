<?php

namespace App\Models\Marketing;

use App\Http\Models\Users\UserEntity;
use App\Http\Models\Users\UserMap;
use App\Models\BaseModel;
use App\Models\Marketing\Filters\ActionTriggerMapFilter;
use Carbon\Carbon;
use Faker\Provider\Base;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ActionTriggerMap extends BaseModel{

    public $table = 'jos_mdigm_marketing_trigger_map';
    public $primaryKey = 'mapid';

    public $timestamps = false;

    protected $fillable = [ 'leadid' , ''  ];

    /***
     * Store an action trigger map
     *
     * @param Request $r
     * @return $this|bool
     */
    public function store( Request $r )
    {
        // check if there are duplicate
        $duplicate = static::where( 'leadid' , $r->leadid )
            ->where( 'actionid' , $r->actionid )
            ->first( );

        if( $duplicate ){
            $this->errors[] = 'Duplicate entry';
            return false;
        }
        // if sending date is

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
            $this->actionid     = $r->action->actionid;
            $this->added_by     = $r->user()->id;
            $this->date_added   = date( 'Y-m-d H:i:s' );
            $this->date_sending_sched = $this->getDateSendingSched( $r->action );
            $this->date_sent    = "1970-01-01 00:00:00";
            $this->status       = 'onqueue';
            $this->is_opened = '0';
            $this->todoid = $r->todoid ? $r->todoid : 0;
        }

        $this->save();

        return $this;
    }

    /**
     * Get collection of trigger maps
     * @param Request $r
     * @return array
     */
    public function getCollection( Request $r )
    {

        $this->setLpo( $r );
        $this->fields = [ 'm.*' , 'l.assigned_to' , 'l.ownerid' , 'l.first_name', 'l.last_name',
            'l.primary_address_street' ,'l.primary_address_city' ,'l.primary_address_state', 'l.primary_address_country', 'l.primary_address_postalcode',
            'a.postcard_id' ];

        $this->query    = static::from( $this->table.' as m' );
        $this->query->join( 'jos_mdigm_marketing_actions as a', 'm.actionid' , '=', 'a.actionid'  );
        $this->query->join( 'jos_mdigm_leads as l', 'l.leadid' , '=', 'm.leadid'  );

        // apply filters
        $this->query = ( new ActionTriggerMapFilter( $this->query ) )->applyFilter( $r->all() );

        $this->total = $this->query->count();

        $this->assignLpo();
        return $this->vuefyThisCollection();
    }

    /**
     * Returns the sending date of the trigger
     * @param $action
     * @return string sending date
     */
    private function getDateSendingSched( $action )
    {

        switch( $action->sending_type   ){
            case 'date_entered':
            case 'date_entered2':
                // today plus sending delay
                return  Carbon::now()->addDays( $action->sending_delay )->toDateString();
            break;
            case 'specific_date':
                return $action->sending_specific_datetime;
            break;
            default:
                return '1970-01-01';
            break;
        }

    }
}