<?php


abstract class Session
{

    protected static function init(){
        session_start();
    }

    /**
     * @param $key
     * @param $value
     */
    static function set($key, $value){
        self::init();
        $_SESSION[$key] = $value;
    }

    /**
     * @param $key
     * @return bool|mixed
     */
    static function get($key){

        self::init();
        if(isset($_SESSION[$key]))
            return $_SESSION[$key];
        else
        return false;
    }

    /**
     * @param $key
     * @return bool
     */
    static function check($key){

        self::init();
        if(self::get($key) === false) {
            return false;
        }
        return true;
    }


    /**
     * @param string $redirect
     */
    static function destroy($redirect = '/'){
        self::init();
        session_destroy();
        session_unset();
        header('Location:'.$redirect);
    }
}