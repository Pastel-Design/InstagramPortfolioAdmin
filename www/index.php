<?php

use app\router\Router;

mb_internal_encoding("UTF-8");
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
require("../vendor/autoload.php");

/**
 * @param $class
 */
function autoloadFunction($class)
{
    $classname="./../" . str_replace("\\","/",$class) . ".php";
    if (is_readable($classname)) {
        /** @noinspection PhpIncludeInspection */
        require($classname);
    }
}
spl_autoload_register("autoloadFunction");
session_start();
$router = new Router();
$router->process(array($_SERVER['REQUEST_URI']));