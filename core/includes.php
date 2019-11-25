<?php
$start = microtime(true);

require CORE . DS . 'more' . DS . 'debug.php';
require CORE . DS . 'more' . DS . 'assets.php';
require 'Router.php';
require CORE . DS . 'setting' . DS . 'Config.php';

require CORE . DS . 'helpers' . DS . 'Helper.php';
require CORE . DS . 'helpers' . DS . 'ModelJson.php';
require CORE . DS . 'more' . DS . 'ExtendsView.php';
require 'Request.php';
require 'Book.php';
require 'CalculateAge.php';
require 'Validator.php';

require ROOT.DS.'controllers'.DS.'Controller.php';
require 'Db.php';
require ROOT.DS.'models/Model.php';
require CORE . DS . 'more' . DS . 'CounterView.php';
require 'Dispatcher.php';

require CORE.DS.'setting'.DS.'autoload.php';



    //echo "<div style='position:fixed; bottom:0; background:#900; color:#FFF; line-height:30px; height:30px; left:0; right:0 padding-left:10px;'>";

        //echo "Page generated in ".round(microtime(true) - $start, 5);

    //echo "</div>";









