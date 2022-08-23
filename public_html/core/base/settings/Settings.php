<?php

namespace core\base\settings;

class Settings
{
    static private $_instance;

    private $routes = [
        'admin' => [
            'alias' => 'admin',
            'path' => 'core/admin/controller/',
            'hrUrl' => false,
            'routes' => [
            ]
        ],
        'settings' => [
            'path' => 'core/base/settings/'
        ],
        'plugins' => [
            'path' => 'core/plugins/',
            'hrUrl' => false,
            'dir' => false
        ],
        'user' => [
            'path' => 'core/user/controller/',
            'hrUrl' => true,
            'routes' => [
            ]
        ],
        'default' => [
            'controller' => 'IndexController',
            'inputMethod' => 'inputData',
            'outputMethod' => 'outputData'
        ]
    ];


    private $templateArr = [
        'text' => ['name', 'phone', 'address'],
        'textarea' => ['content', 'keyword']
    ];

    private function __construct() {
    }

    private function __clone() {
    }

    static public function get($property) {
        return self::instance()->$property;
    }

    static public function instance(){
        if(self::$_instance instanceof self) {
            return self::$_instance;
        } else {
            return self::$_instance = new self;
        }
    }

    public function clueProperties($class) {
        $baseProperties = [];
        foreach ($this as $key => $val) {
            $property = $class::get($key);
            if(is_array($property) && is_array($val)) {
                $baseProperties[$key] = $this::arrayMergeRecursive($val, $property);
                continue;
            }
            if(!$property) $baseProperties[$key] = $val;
        }
        return $baseProperties;
    }

    //Функция склеивает два массива по логике необходимой для объединения настроек
    public function arrayMergeRecursive() {
        $arrays = func_get_args();
        $base = array_shift($arrays);
        foreach ($arrays as $array) {
            foreach ($array as $key => $val) {
                if(is_array($val) && is_array($base[$key])){
                    $base[$key] = $this->arrayMergeRecursive($base[$key], $val);
                } else {
                    if(is_int($key)) {
                        if(!in_array($val, $base)){
                            array_push($base,$val);
                            continue;
                        }
                    }
                    $base[$key] = $val;
                }
            }

        }
        return $base;
    }
}