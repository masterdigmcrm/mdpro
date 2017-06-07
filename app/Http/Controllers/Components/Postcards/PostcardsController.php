<?php
/**
 * Created by PhpStorm.
 * User: Dennis
 * Date: 8/17/2015
 * Time: 3:22 PM
 */

namespace App\Http\Controllers\Components\Postcards;

use App\Http\Controllers\Components\ComponentsBaseController;
use App\Http\Models\Postcards\PostcardCollection;
use App\Models\Accounts\AccountEntity;
use Helpers\Layout;

class PostcardsController extends ComponentsBaseController{

    public function __construct()
    {
        view()->addLocation( __DIR__.'/views' );
        parent::__construct();
    }

    public function index()
    {
        $account = AccountEntity::me();
        $content = view( 'postcard_index')
            ->with( 'account' , $account )
            ->render();
        $this->layout->content = $content;

        $this->indexAssets();

        return $this->layout;
    }

    private function indexAssets()
    {
        Layout::loadVue();
        //Layout::instance()->addScript( '/js/components/postcards/postcards.js' , 'bottom');
        Layout::instance()->addScript( '/js/components/postcards/postcard_index.js' , 'bottom');
    }


}