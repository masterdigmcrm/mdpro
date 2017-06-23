<?php

namespace App\Http\Controllers\Components\Utils;

use App\Api\Lob\LobApi;
use App\Http\Controllers\Controller;
use App\Models\Leads\LeadEntity;
use App\Models\Marketing\CampaignCollection;
use App\Models\Postcards\PostcardEntity;
use App\Models\Users\UserEntity;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UtilsController extends Controller{

    /**
     * test sending of postcards
     */
    public function lob( Request $r )
    {
        $postcard = PostcardEntity::find( 1 );
        $to_address = '711-2880 Nulla St. Mankato Mississippi 96522';
        $from_address = '606-3727 Ullamcorper Street Roseville NH 11523';
        $key = '';
        $lobapi = new LobApi( $key );
        $ps = $lobapi->sendPostcard( $postcard , $to_address , $from_address );

    }

    public function letters( Request $r )
    {
        /**
        $postcard = PostcardEntity::find( 1 );
        $to_address = '711-2880 Nulla St. Mankato Mississippi 96522';
        $from_address = '606-3727 Ullamcorper Street Roseville NH 11523';
        $key = '';
        $lobapi = new LobApi( $key );
        $ps = $lobapi->sendPostcard( $postcard , $to_address , $from_address );
        **/
    }

    public function getCampaignsByLead()
    {
        $user_id = 119;
        $user = UserEntity::find( $user_id );
        UserEntity::instance( 'me' , $user );

        $lead = (new LeadEntity())->f( 100887 );
        $campaigns = (new CampaignCollection )->getByLeadCredentials( $lead );
        dd( $campaigns );
    }

    public function carbon( )
    {
        echo Carbon::now()->addDays( 0 )->toDateString();
        exit;
    }

}