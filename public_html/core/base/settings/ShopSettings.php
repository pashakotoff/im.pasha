<?php

namespace core\base\settings;
use core\base\settings\Settings;


class ShopSettings
{
    static private $_instance;
    private $baseSettings;
    private $routes = [
        'plugins' => [
            'dir' => 'false',
            'routes' => [
            ]
        ]
    ];


    private $templateArr = [
        'text' => ['price', 'short'],
        'textarea' => ['goods_content']
    ];

    static public function get($property) {
        return self::instance()->$property;
    }

    static public function instance(){
        if(self::$_instance instanceof self) {
            return self::$_instance;
        } else {
            self::$_instance = new self;//создает объект
            self::$_instance->baseSettings = Settings::instance();//записывает baseSettings из Settings
            $baseProperties = self::$_instance->baseSettings->clueProperties(get_class());
            self::$_instance->setProperty($baseProperties);
            return self::$_instance;
        }
    }

    protected function setProperty($properties){
        if($properties) {
            foreach($properties as $key => $val) {
                $this->$key = $val;

            }
        }

    }

    private function __construct() {

    }

    private function __clone() {
    }
}