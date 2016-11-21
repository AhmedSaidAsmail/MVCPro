<?php
class test {
    public static $routes = [];
    public static $_instance=null;
    private function __construct() {
        
    }
    public static function addRoute($route = []) {
        self::$routes[] = $route;
        if (self::$_instance===null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    public function test() {
        echo "done  ";
    }
    public function dispatch() {
        return self::$routes;
    }
}
test::addRoute(["pattern" => ":name/profile"])->test();
test::addRoute(["pattern" => ":name/profile"])->test();

$test = test::$_instance;




echo "<pre>" . print_r($test->dispatch(), true);
