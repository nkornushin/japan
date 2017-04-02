<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\MyAuth;
use Illuminate\Support\Facades\Redirect;
use App\User;


class SiteController extends Controller
{
    //

    public function signIn() {
        return view('site.signin');
    }

    public function signUp() {
        return view('site.signup');
    }

    public function postSignUp(Request $request) {

        $error = [];

        if(!$request->has('email')) {
            $error['email'] = 'Email не может быть пустым';
        } elseif(!filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
            $error['email'] = 'Некоректный Email';
        } elseif(MyAuth::emailExist($request->input('email'))) {
            $error['email'] = 'Данный Email уже зарегестрирован';
        }

        if(empty($error)) {
            MyAuth::preRegister($request->input('email'));
            return Redirect::back()->with('message', 'На email '.$request->input('email').' выслан пароль!');
        }

        return Redirect::back()->with('email', $error['email']);
    }
    
    public function activate($code) {
        
        $data = MyAuth::getDataFromHash($code);
        
        $error = [];
        
        if(empty($data) || empty($data['password']) || empty($data['email'])) {
                $error['email'] = 'Ошибка ссылки';
        } elseif(MyAuth::emailExist($data['email'])) {
                $error['email'] = 'Данный Email уже зарегестрирован';
        } else {
                $user = new User();
                $user->email = $data['email'];
                $user->password = \Hash::make($data['password']);
                
                $user->save();
                
                return Redirect::route('signin');
        }
        
        return Redirect::route('signin')->with('email', $error['email']);
    }
    
    public function postLogin(Request $request) {
        
        $user = MyAuth::checkLogin($request->input('email'), $request->input('password'));
        if(!$user) {
                return Redirect::back()->with('error', 'Не правильный Email или пароль!');
        }
        
        MyAuth::login($user);
        
        return Redirect::route('index');
    }
    
    public function logout() {
        MyAuth::logout();
        return Redirect::route('signin');
    }
}
