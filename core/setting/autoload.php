<?php

spl_autoload_register(function ($class){
    if(file_exists(ROOT.DS.'models'.DS.$class.'.php')){
        require_once ROOT.DS.'models'.DS.$class.'.php';
    }elseif (file_exists(CORE.DS.$class.'.php')){
        require_once CORE.DS.$class.'.php';
    }
});


