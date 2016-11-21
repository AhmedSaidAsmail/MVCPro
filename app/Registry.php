<?php
namespace App;
class Registry {
    private static $instances = [];
    private function __construct() {
        
    }
    private function __clone() {
        
    }
    public static function set($key, $instance = NULL) {
        self::$instances[$key] = $instance;
    }
    public static function get($key, $default = null) {
        return (isset(self::$instances[$key])) ? self::$instances[$key] : $default;
    }
    public static function erase($key) {
        unset(self::$instances[$key]);
    }
}