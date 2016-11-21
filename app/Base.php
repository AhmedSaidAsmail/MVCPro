<?php
namespace App;

use App\Inspector;
use App\StringMethods;
class Base {
    private $_inspector;
    public function __construct($options = []) {
        $this->_inspector = new Inspector($this);
        if (is_array($options) || is_object($options)) {
            foreach ($options as $key => $value) {
                $key    = ucfirst($key);
                $method = "set{$key}";
                $this->$method($value);
            }
        }
    }
    public function __call($name, $arguments) {
        if (empty($this->_inspector)) {
            throw new Exception("Call parent::__construct!");
        }
        $setMatches = StringMethods::match($name, "^set([a-zA-Z09]+)$");
        if (count($setMatches) > 0) {
            $property = lcfirst($setMatches[0]);
            if (property_exists($this, $property)) {
                $meta = $this->_inspector->getPropertyMeta($property);
                if (isset($meta["read"]) && !isset($meta["readwrite"])) {
                    throw new \Exception("Prperty $property is for Read Only");
                }
                $this->$property = $arguments[0];
                return $meta;
            }
            return NULL;
        }
        $getMatches = StringMethods::match($name, "^get([a-zA-Z0-9]+)$");
        if (count($getMatches) > 0) {
            $property = lcfirst($getMatches[0]);
            $meta     = $this->_inspector->getPropertyMeta($property);
            if (isset($meta["write"]) && !isset($meta["@readwrite"])) {
                throw new \Exception("Prperty $property is for Write Only");
            }
            return $this->$property;
        }
        throw new \Exception("Method with does not exist.");
    }
    public function __get($name) {
        $function = "get" . ucfirst($name);
        return $this->$function();
    }
    public function __set($name, $value) {
        $function = "set" . ucfirst($name);
        return $this->$function($value);
    }
}