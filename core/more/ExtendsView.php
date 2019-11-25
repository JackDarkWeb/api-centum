<?php


class ExtendsView
{

    /**
     * @param $view
     * @param array $data
     * @return false|string
     */
    static function extend($view, $data = []){

        extract($data);

        $view = ROOT.DS.'views'.DS.'layouts'.DS.$view.'.php';

        ob_start();
        require $view;

        $content = ob_get_clean();

        return $content;
    }
}