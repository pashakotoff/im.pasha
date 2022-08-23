<?php

//Переменная нужна для того, чтобы на другие страницы не осуществлялся вход не через index.php
define('VG_ACCESS', true);

//Включаем хедер
header('Content-Type:text/html;charset=utf-8');

//Открываем сессию
session_start();

require_once 'config.php';
require_once 'core/base/settings/internal_settings.php';
require_once 'libraries/functions.php';

use core\base\exceptions\RouteException;
use core\base\controller\RouteController;


try {
    RouteController::getInstance()->route();

}
catch (RouteException $e){
    exit($e);

}
