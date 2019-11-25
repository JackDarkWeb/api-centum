<?php

function dump($var){

    $debug = debug_backtrace();
    echo "<a href='#' onClick='$(this).parent().next(\"ol\").slideToggle(); return false;'><strong>{$debug[0]['file']}</strong> line {$debug[0]['line']}</a>";

    echo "<ol>";
        foreach ($debug as $key => $value){
            if($key > 0){
                echo "<li>{$value['file']} line {$value['line']}</li>";
            }
        }
    echo "</ol>";


    echo "<pre>";
        print_r($var);
    echo "</pre>";
}

function dd($var){

    dump($var);
    die();
}