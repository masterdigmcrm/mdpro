<?php

namespace App\Models\Activities;

use App\Models\Users\UserEntity;
use App\Models\BaseModel;
use Illuminate\Http\Request;

class TodoEntity extends BaseModel{

    protected $table        = 'jos_mdigm_todo';
    protected $primaryKey   = 'todoid';
    public $timestamps      = false;

    protected $fillable     = [ 'todo' , 'start_date', 'leadid' , 'ownerid',
        'created_by' , 'todo_status' , 'priority' ];

    public function getStartDateAttribute( $value )
    {
        $dt = $value.' '.$this->time_due;
        return date( 'M d, H:i a' , strtotime( $dt ) );
    }

    public static function store( Request $request  )
    {
        $todo = new static;
        $todo->fill( $request->all());
        $todo->save();

        return $todo;

    }

}