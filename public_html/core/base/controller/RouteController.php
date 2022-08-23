<?php

namespace core\base\controller;
use core\base\settings\Settings;
use core\base\settings\ShopSettings;

//Шаблон проектирования singleton;
class RouteController extends BaseController
{
    static private $_instance;
    protected $routes;



    static public function getInstance(){
        if(self::$_instance instanceof self) {
            return self::$_instance;
        } else {
            return self::$_instance = new self;
        }
    }



    private function __construct() {
        $address_str = $_SERVER['REQUEST_URI'];
        if(strrpos($address_str, '/') === strlen($address_str) -1 && strrpos($address_str, '/') !== 0) {
            $this->redirect(rtrim($address_str, '/'), 301);
        }
        $path = substr($_SERVER['PHP_SELF'],0, strpos($_SERVER['PHP_SELF'], 'index.php'));

        if($path === PATH){

            $this->routes = Settings::get('routes');

            if(!$this->routes) throw new \RouteException('Сайт находится на техническом обслуживании');

            $urlArr = explode('/', substr($address_str, strlen(PATH)));

            if(isset($urlArr[0]) && $urlArr[0] === $this->routes['admin']['alias']){
                //Обработка админки
                $urlArr = explode('/', substr($address_str, strlen(PATH) + strlen
                    ($this->routes['admin']['alias'])+1));
                $plugin = array_shift($urlArr);

                if(is_dir($_SERVER['DOCUMENT_ROOT'] . PATH . $this->routes['plugins']['path'] .
                        $urlArr[0])) {
                    //Работа с плагинами


                    $pluginSettings = $this->routes['settings']['path'] . ucfirst($plugin) . 'Settings';
                    if(file_exists($_SERVER['DOCUMENT_ROOT'] . PATH. $pluginSettings . '.php')){

                        $pluginSettings = str_replace('/','\\', $pluginSettings);
                        $this->routes = $pluginSettings::get('routes');
                    }
                    $dir = $this->routes['plugins']['dir'] ? '/' . $this->routes['plugins']['dir'] . '/' : '/';
                    $dir = str_replace('//', "/", $dir);//защита от неправильных наименований

                    $this->controller = $this->routes['plugins']['path'] . $plugin . $dir;

                    $hrUrl = $this->routes['plugins']['hrUrl'];

                    $route = 'plugins';

                } else {
                    $this->controller = $this->routes['admin']['path'];

                    $hrUrl = $this->routes['admin']['hrUrl'];

                    $route = 'admin';
                }

            } else {

                $hrUrl = $this->routes['user']['hrUrl'];

                $this->controller = $this->routes['user']['path'];

                $route = 'user';

            }

            $this->createRoute($route, $urlArr);

            if(isset($urlArr[1])){
                $countUrlArr = count($urlArr);
                $key = '';
                if(!$hrUrl) {
                    $i = 1;
                } else {
                    $this->parameters['alias'] = $urlArr['1'];
                    $i = 2;
                }
                for( ; $i < $countUrlArr; $i++) {
                    if(!$key) {
                        $key = $urlArr[$i];
                        $this->parameters[$key] = '';
                    } else {
                        $this->parameters[$key] = $urlArr[$i];
                        $key = '';
                    }
                }
            }

         } else {
            try {
                throw new \Exception('Не корректная директория сайта');
            }
            catch (\Exception $e){
                exit($e->getMessage());
            }
        }
    }
    //Функция создания маршрутов
    private function createRoute($routeName, $arr){

        $route = [];

        if(!empty($arr[0])) {
            if(isset($this->routes[$routeName]['routes'][$arr[0]])){

                $route = explode('/', $this->routes[$routeName]['routes'][$arr[0]]);

                $this->controller .= ucfirst($route[0].'Controller');
            } else {
                $this->controller .= ucfirst($arr[0].'Controller');
            }
        } else {
            $this->controller .= $this->routes['default']['controller'];
        }
        $this->inputMethod = isset($route[1]) ? $route[1] : $this->routes['default']['inputMethod'];
        $this->outputMethod = isset($route[2]) ? $route[2] : $this->routes['default']['outputMethod'];

        return;
    }

    private function __clone() {
    }
}