<?php


abstract class ModelJson
{
    private static $db = db_message,
            $messages = [];

    /**
     * @return array|mixed
     */
    static function findAll(){

        self::$messages = file_get_contents(self::$db);
        self::$messages = json_decode( self::$messages, true);

        return  self::$messages;
    }

    /**
     * @param array $array
     */
    static  function insert(array $array){

        // I retrieve all messages json
        $data = file_get_contents(self::$db);

        //I convert the Json into a table so I can add a new mew message
        $data = json_decode($data, true);
        $data[] = $array;

        //Convert back to Json
        $data = json_encode($data);
        file_put_contents(self::$db, $data);
    }

    /**
     * @param $id
     * @return mixed
     */
    static function find($id){

        $data = [];
        foreach (self::findAll() as $message){
            if(in_array($id, $message)){
               $data = $message;
            }
        }
        return $data;
    }

    /**
     * @return int
     */
    static function count(){
        return sizeof(self::findAll());
    }
}