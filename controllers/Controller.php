<?php


class Controller extends Validator
{
    public $request;
    private $vars     = [],


        # Know If the view has already been rendered
        $rendered = false;



    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @param $view
     * @param array $data
     * @return bool
     */
    public function view($view, $data = []){

        //var_dump($view);die();
        if(!$this->rendered){

            $this->make($view, $data);
            $this->rendered = true;
        }
        return $this->rendered;
    }

    /**
     * @param $view
     * @param array $data
     */
    private function make($view, $data = []){

        $trans_view = str_replace('.', DS, $view);

        foreach ($data as $key => $val){
            $this->vars[$key] = $val;
        }
        extract($this->vars);

        if(strpos($view, '/') === 0){
            $view = ROOT.DS.'views'.DS.$trans_view.'.php';
        }else{
            $view = ROOT.DS.'views'.DS.$trans_view.'.php';
        }


        ob_start();

        require($view);

        $yield = ob_get_clean();
        require ROOT.DS.'views'.DS.'layouts'.DS.'default.php';
        //die($view);
    }

    /**
     * @param $message
     */
    function e404($message){
        //header('HTTP//1.0 404 not found');
        $this->view('.errors.404', [
            'message' => $message
        ]);
        die();
    }


    function redirect($url, array $data, $status){
        if($status == 301){
            header('HTTP//1.0 301 Moved Permanently');
        }
        header('Location:'.Router::url($url, $data));
    }

    /**
     * @param $name
     * @return mixed
     */
    function loadModel($name){

        $file = ROOT.DS.'Models'.DS.$name.'.php';
        require_once $file;

        // Load the file once if it has not already been loaded
        if(!isset($this->name)){
            $this->name = new $name();
        }
    }

}