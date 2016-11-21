<?php
namespace App;

//use App\Base;
use App\Registry;
use App\Inspector;
use App\Router\Route\Simple;
class Router {
    public static $_instance;
    /**
     * @readwrite
     */
    public $url;
    /**
     * @readwrite
     */
    protected $extension;
    /**
     * @read
     */
    protected $controller;
    /**
     * @read
     */
    protected $action;
    protected static $routes = [];
    private function __construct() {
        
    }
    private function __clone() {
        
    }
    public function _getExceptionForImplementation($method) {
        return new \Exception("{$method} method not implemented");
    }
    public static function addRoute($route = []) {
       self::$routes[] = new Simple($route);
        if (!isset(self::$_instance)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    public function removeRoute($route) {
        foreach ($this->routes as $i => $stored) {
            if ($stored == $route) {
                unset($this->routes[$i]);
            }
        }
        return $this;
    }
    public function getRoutes() {
        $list = [];
        foreach ($this->routes as $route) {
            $list[$route->pattern] = get_class($route);
        }
        return $list;
    }
    protected function _pass($controller, $action, $parameters = []) {
        $this->controller = $controller;
        $this->action     = $action;
        $controller   = "App\Http\Controllers\\" . $controller;
        try {
            //Http\Controllers\Web\WebController
            $instance = new $controller(array(
                "parameters" => $parameters
            ));
            Registry::set("controller", $instance);
        }
        catch (\Exception $e) {
            echo $e->getMessage("Controller teste {$name} not found");
        }
        if (!method_exists($instance, $action)) {
            $instance->willRenderLayoutView = false;
            $instance->willRenderActionView = false;
            throw new \Exception("Action {$action} not found");
        }
        $inspector  = new Inspector($instance);
        $methodMeta = $inspector->getMethodMeta($action);
        if (!empty($methodMeta["protected"]) || !empty($methodMeta["private"])) {
            throw new \Exception("Action {$action} is protected");
        }
        $hooks = function($meta, $type) use ($inspector, $instance) {
            if (isset($meta[$type])) {
                $run = [];
                foreach ($meta[$type] as $method) {
                    $hookMeta = $inspector->getMethodMeta($method);
                    if (in_array($method, $run) && !empty($hookMeta["once"])) {
                        continue;
                    }
                    $instance->$method();
                    $run[] = $method;
                }
            }
        };
        $hooks($methodMeta, "before");
        call_user_func_array(array(
            $instance,
            $action
                ), is_array($parameters) ? $parameters : []);
        $hooks($methodMeta, "after");
// unset controller
        Registry::erase("controller");
    }
    public function dispatch() {
        
       
        $url        = $this->url;
        $parameters = [];
        $controller = "index";
        $action     = "index";
        foreach (self::$routes as $route) {
            $matches = $route->matches($url);
            if ($matches) {
                
                $controller = $route->controller;
                $action     = $route->action;
                $parameters = $route->parameters;
                $this->_pass($controller, $action, $parameters);
                return;
            }
        }
    }
}