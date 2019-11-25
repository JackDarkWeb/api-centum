<?php


class Router
{
    static $routes = [];


    /**
     * @param $url
     * @param $request
     * @return bool
     * Allows parser url to be retrieved by the Request object to Dispatcher object
     */
    static function parse($url, $request)
    {

        $url = trim($url, '/');

        foreach (Router::$routes as $val_routes){

            if(preg_match($val_routes['catcher'], $url, $match)){

                $request->controller = $val_routes['controller'];
                $request->action     = $val_routes['action'];

                $request->params = [];
                foreach ($val_routes['params'] as $key => $val){

                    $request->params[$key] = $match[$key];
                }

                return $request;

            }
        }





        $params = explode('/', $url);


        if($params[0] === ""){
            $request->controller = 'home';
            $request->action = 'welcome';
            $request->params = array();
        }else{

            $request->controller = $params[0];
            $request->action = isset($params[1]) ? $params[1] : 'index';
            $request->params = (!empty(array_slice($params, 2))) ? array_slice($params, 2) : [false];
        }

                        /*
                         $params = [
                            'controller' => $detach[0],
                            'action' => isset($detach[1]) ? $detach[1] : 'index',
                        ];
                        $params['params'] = array_slice($params, 2); // le reste des params apres controller et action
                        //print_r($params);
                        return $params
                        */
        return true;
    }


    static function connect($redirect, $url){

        $r = [];
        $r['params'] = [];

        $r['redirect'] = $redirect;
        $r['origin'] = preg_replace('/([a-zA-Z0-9àçéèêëíìîïñóòôöõúùûüýÿæ]+):([^\/]+)/', '${1}:(?P<${1}>${2})', $url);
        $r['origin'] = '/'.str_replace('/', '\/', $r['origin']).'/';


        $params =  explode('/', $url);
        foreach ($params as $key => $val_param){

            # if the url contains arguments, by checking the presence of the two points
            if(strpos($val_param, ':')){

                $args = explode(':', $val_param);

                #i associate the arguments with their values
                $r['params'][$args[0]] = $args[1];
            }else{

                if($key === 0){

                    $r['controller'] = $val_param;

                }elseif($key === 1){

                    $r['action'] = $val_param;
                }
            }
        }


        $r['catcher'] = $redirect;

        foreach ($r['params'] as $key => $val){

            $r['catcher'] = str_replace(":$key", "(?P<$key>$val)", $r['catcher']);
        }
        $r['catcher'] = '/'.str_replace('/', '\/', $r['catcher']).'/';

        self::$routes[] = $r;
        //dd($r);
    }

    static function url($url, $data = []){

        if(count($data) == 2){
            $data = (object)$data;
            $url = "$url/id:{$data->id}/slug:{$data->slug}";
        }

        foreach (self::$routes as $value){

            if(preg_match($value['origin'], $url, $match)){

                foreach ($match as $key => $val){

                    if(!is_numeric($key)){

                        $value['redirect'] = str_replace(":$key", $val, $value['redirect']);
                    }
                }

                return '/'.$value['redirect'];
                //dd($match);
            }
        }
        return '/'.$url;
    }

}