<?php

namespace App\Models\Contacts;

use App\Models\Accounts\AccountEntity;
use App\Models\BaseModel;
use Illuminate\Http\Request;

class ContactEntity extends BaseModel{

    protected $table        = 'jos_mdigm_contacts';
    protected $primaryKey   = 'contactid';

    public $timestamps = false;

    protected $fillable = [];


}