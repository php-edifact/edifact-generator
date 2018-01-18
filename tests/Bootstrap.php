<?php
/**
 * Created by PhpStorm.
 * User: Sascha
 * Date: 18.01.2018
 * Time: 14:35
 */

$file = __DIR__ . '/../../../autoload.php';
if (file_exists($file)) {
    include_once $file; //autoload from main composer autoloader
}
error_reporting(E_ALL | E_STRICT);
chdir(__DIR__);
require('classBootstrap.php');
Bootstrap::init();