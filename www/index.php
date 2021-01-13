<?php

use app\router\Router;

mb_internal_encoding("UTF-8");

require("../vendor/autoload.php");

/**
 * @param $class
 */
function autoloadFunction($class)
{
    $classname="./../" . str_replace("\\","/",$class) . ".php";
    var_dump($classname);
    if (is_readable($classname)) {
        /** @noinspection PhpIncludeInspection */
        require($classname);
    }
}
spl_autoload_register("autoloadFunction");
var_dump(phpversion());
session_start();
$router = new Router();
$router->process(array($_SERVER['REQUEST_URI']));