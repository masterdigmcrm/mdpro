<?php

namespace App\Models\Postcards;

use App\Http\Models\Users\UserEntity;
use App\Http\Models\Users\UserMap;
use Illuminate\Http\Request;

class PostcardCollection extends PostcardEntity{

    public function getCollection( Request $r )
    {
        $this->setLpo( $r );
        $this->fields = [ 'a.*' ];

        $this->query = static::from( $this->table.' as a' );
        // insert conditions here
        if( $r->account_id ){
           $this->query->where( 'account_id', $r->account_id );
        }

        $this->total = $this->query->count();

        $this->assignLpo();
        return $this->vuefyThisCollection();
    }

    public static function getByAccountId( $account_id )
    {
        return static::where( 'account_id' , $account_id )
            ->get();
    }

}