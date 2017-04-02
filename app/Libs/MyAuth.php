<?php
namespace App\Libs;

use App\User;
use Illuminate\Support\Facades\Mail;

class MyAuth {

    public static function emailExist($email) {
        return (bool)User::where('email', $email)->count();
    }

    public static function preRegister($email) {

        $password = rand(100000, 999999);

        $hash = self::encrypt_decrypt('encrypt', $email.';'.$password);

        return self::sendInvaiteEmail($email, $password, $hash);
    }

    private static function encrypt_decrypt($action, $string) {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'some key';
        $secret_iv = 'some key2';
        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if( $action == 'decrypt' ) {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }

    private static function sendInvaiteEmail($email, $password, $hash) {

        $title = 'Регстрация на '.config('app.name');
        $link = route('signup.activate', [$hash]);
        
        $content = 'Добро пожаловать в '.config('app.name');
        $content .= '<br>';
        $content .= 'Ваш пароль: '.$password;
        $content .= '<br>';
        $content .= '<br>';
        $content .= 'Для завершения регистрации перейдите по ссылке <a href="'.$link.'">'.$link.'</a>';
        
        $to      = $email;
        $subject = $title;
        $message = $content;
        $headers = 'From: info@etalonsoft.ru' . "\r\n" .
            'Reply-To: info@etalonsoft.ru' . "\r\n" .
            'Content-Type: text/html; charset=UTF-8\r\n'.
            'X-Mailer: PHP/' . phpversion();
        
        return mail($to, $subject, $message, $headers);
        
    }
    
    public static function getDataFromHash($hash) {
        try {
                
                $str = self::encrypt_decrypt('decrypt', $hash);
                list($email, $password) = explode(';', $str);
        } catch(\ErrorException $e) {
                return [];
        }
        
        
        return ['email' => $email, 'password' => $password];
    }
    
    public static function checkLogin($email, $password) {
        $user = User::where('email', $email)->first();
        
        if(count($user) && \Hash::check($password, $user->password)) {
                return $user;
        }
        
        return false;
    }
    
    public static function isLogin() {
        
        return \Session::has('userId');
    }
    
    public static function user() {
        
        if(self::isLogin()) {
                return User::find(\Session::get('userId', false));
        }
        
        return false;
    }
    
    public static function login($user) {
        \Session::put('userId', $user->id);
    }
    
    public static function logout() {
        \Session::forget('userId');
    }
}

