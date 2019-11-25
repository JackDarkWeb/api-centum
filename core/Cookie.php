<?php


class Cookie
{
    /**
     * @param $key
     * @param $value
     */
    static function set($key, $value){
        setcookie($key, $value, time()+86400);
    }

    /**
     * @param $key
     * @return bool|mixed
     */
    static function get($key){

        if(isset($_COOKIE[$key]))
            return $_COOKIE[$key];
        else
          return false;
    }

    /**
     * @param $key
     * @return bool
     */
    static function check($key){

        if(self::get($key) === false){
            return false;
        }
        return true;
    }

    /**
     * @param $key
     * @return bool
     */
    static function destroy($key){

        if(self::get($key) === false){
            return false;
        }
        setcookie($key);
        return true;
    }

}