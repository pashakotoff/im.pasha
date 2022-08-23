<?php
defined('VG_ACCESS') or die('access denied');

const TEMPLATES = 'templates/default/';
const ADMIN_TEMPLATE = 'core/admin/views';

const COOKIE_VERSION = '1.0.0';
const CRYPT_KEY = '';
const COOKIE_TIME = 60;
const BLOCK_TIME = 3;

const QTY = 8;
const QTY_LINKS = 3;

const ADMIN_CSS_JS = [
    'styles' => [],
    'scripts' => []
];

const USER_CSS_JS = [
    'styles' => [],
    'scripts' => []
];

use core\base\exceptions\RouteException;

function autoloadMainClasses($class_name) {
    $class_name = str_replace('\\', '/', $class_name);
    //Если include once выдает ошибку - пробрось это сообщение.
    if(!@include_once $class_name . '.php') {
        throw new RouteException('Не верное имя файла для подключения класса - '.$class_name);
    }
    include_once $class_name.'.php';
}

spl_autoload_register('autoloadMainClasses');