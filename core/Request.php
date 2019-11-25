<?php


class Request
{
    /**
     * @var mixed
     * Get URL typing by user
     */
    public  $url;
    public $controller;
    public $action;

    # the rest of the params after controller and action for example (id, slug)
    public $params;
    /**
     * @var float
     */
    public $page = 1;
    public $q    = null;
    public $v    = null;

    function __construct()
    {
        $this->url = $_SERVER['PATH_INFO'];

        #Paginte
        if(isset($_GET['page'])){
            
            if(is_numeric($_GET['page'])){

                if($_GET['page'] > 0)
                  $this->page = round(filter_var($_GET['page'], FILTER_SANITIZE_URL));
            }

        }

        if(isset($_GET['q']) || isset($_GET['v'])){

            $this->q = filter_var($_GET['q'], FILTER_SANITIZE_URL);
        }

        if(isset($_GET['v'])){

            $this->v = filter_var($_GET['v'], FILTER_SANITIZE_URL);
        }
    }
}