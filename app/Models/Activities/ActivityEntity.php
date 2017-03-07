<?php

namespace App\Models\Activities;

use App\Http\Models\Users\UserEntity;

class ActivityEntity extends \Eloquent{

    protected $table = 'jos_mdigm_activities';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['subject' , 'activity',
        'start_date' , 'start_time' , 'leadid' , 'userid','contactid'  ];

    public static function getNonRecurringActivitiesByDateRange( UserEntity $user , $start_date , $end_date , $options = array() )
    {

        $t =  static::where( 'a.userid' , $user->id )
        //->where( 'is_recurring' ,  0 )
        ->whereBetween( 'start_date' ,  array( "$start_date" , "$end_date" ) )
        ->from( 'jos_mdigm_activities as a' )
        ->select( 'a.*' , 'a.status as status','a.assigned_to as assigned_to',
            \DB::raw( 'HOUR(start_time) as hr' ),
            \DB::raw( 'HOUR(end_time) as end_hr' ),
            \DB::raw( 'MINUTE(end_time) as end_min') );

        $result['sql'] = $t->toSql();
        $result['bindings'] = $t->getBindings();

        $result['activities'] =  $t->get();
        return $result;

    }

    public static function store( $attributes )
    {
        $activity = new static;
        $activity->fill( $attributes->all() );
        $activity->date_added = date('Y-m-d H:i:s');

        if( ! $attributes->end_date ){
            $activity->end_date = $activity->start_date;
        }

        $start_min = $attributes->start_time % 60;
        $start_min = str_pad( $start_min , 2 , '0' );
        $activity->start_time   = floor( $attributes->start_time / 60 ).':'.$start_min.':00';
        $activity->end_time   = floor( $attributes->end_time / 60 ).':'.( $attributes->end_time % 60 ).':00';

        $activity->save();

        return $activity;

    }

}