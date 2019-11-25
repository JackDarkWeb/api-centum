<?php


trait CalculateAge
{
    private static $age,
                   $birthDay = '';



    /**
     * @param object $user
     * @return int
     */
    function getAge($user){

        $date = [$user->day, $user->month, $user->year];
        $toDay = explode('/', date('d/m/Y'));

        if($date[1] < $toDay[1] || ($date[1] == $toDay[1] && $date[0] <= $toDay[0] )){
            self::$age = $toDay[2] - $date[2];
        }else{
            self::$age = $toDay[2] - $date[2] - 1;
        }
        return self::$age;
    }

    /**
     * @param object $user
     * @return string
     */
    function getBirthDay($user){

        $date = [$user->day, $user->month, $user->year];
        $toDay = explode('/', date('d/m/Y'));

        if($date[1] == $toDay[1] && $date[0] == $toDay[0]){
            self::$birthDay = 'Happy Birth Day';
        }
        return self::$birthDay;
    }
}