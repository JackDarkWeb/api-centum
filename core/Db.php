<?php


abstract class Db extends Config
{
    private  $instance = null;
    private $pdo;
    protected $last_id;

    protected function setDb()
    {
        $config = Config::$databases['dev'];
        try
        {
            $this->pdo = new PDO('mysql:host='.$config['host'].';dbname='.$config['dbname'].';charset=utf8', $config['user'], $config['password']);
            $this->last_id = $this->pdo->lastInsertId();

        }catch(PDOException $e)
        {
            if(Config::$debug >= 1){
                die($e->getMessage());
            }else{
                die('impossible to connect to the database');
            }

        }
    }

    /**
     * @return null
     */
    protected function getInstance()
    {
        if(($this->instance) == null)
        {
            $this->setDb();
        }
        return $this->pdo;
    }
}