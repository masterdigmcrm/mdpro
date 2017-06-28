<?php

namespace App\Http\Controllers\Components\Settings;

use App\Http\Models\Photos\PhotoEntity;
use App\Http\Models\Photos\PhotosCollection;
use App\Models\Locations\Cities;
use App\Models\Locations\Countries;
use App\Models\Locations\States;
use App\Models\Properties\PropertiesCollection;
use App\Models\Properties\PropertyEntity;
use App\Models\Properties\PropertyStatus;
use App\Models\Properties\PropertyTypes;
use App\Models\Users\UserEntity;
use App\Models\Users\UserMap;
use Helpers\Layout;
use Helpers\Utils;
use Illuminate\Http\Request;


class AjaxSettingsController {

    public function init( Request $r )
    {
        $user_map = UserMap::byUserId( $r->user()->id );
        $params = Utils::mb_unserialize( $user_map->params );

        return [
            'success' => true,
            'lob_key' => isset( $params['param_lob_key'] ) ? $params['param_lob_key'] : ''
        ];
    }

    /**
     * @param Request $r
     * @return array
     */
    public function saveLobKey( Request $r )
    {
        $user_map = UserMap::byUserId( $r->user()->id );
        //$user_map->getParamValue( 'param_lob_key' );
        $params = Utils::mb_unserialize( $user_map->params );
        $params[ 'param_lob_key' ] = $r->lob_key;

        $user_map->params = serialize( $params );
        $user_map->save();

        return [
            'success' => true
        ];
    }

}