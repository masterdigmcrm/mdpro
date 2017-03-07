<?php

namespace App\Models\Leads;

use Illuminate\Database\Eloquent\Model;

class LeadNotes extends Model{

    protected $table = 'jos_mdigm_notes';
    protected $primaryKey = 'noteid';
    public $timestamps = false;

    public static function byLeadid( $leadid , $options = [])
    {
        $notes =  static::where( 'leadid' , $leadid )
            ->limit( 20 )
            ->orderBy( 'date_added' , 'DESC')
            ->get();

        if( ! empty( $options['vuetify'] )){
            $v_notes = [];
            foreach( $notes as $n ){
               $v_notes[] = $n->vuetify();
            }

            return $v_notes;
        }

        return $notes;

    }

    public function getDateAddedAttribute( $value )
    {
        return date( 'M d, H:i a', strtotime( $value ) );
    }

    public function vuetify()
    {
        $vuety = $this;
        $vuety->timestamp = strtotime( $this->date_added );
        return $vuety;

    }

}