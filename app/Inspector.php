<?php
namespace App;

use App\ArrayMethods;
use App\StringMethods;
class Inspector {
    protected $_class;
    protected $_methods;
    protected $_properties;
    protected $_meta = [
        "class"      => [],
        "methods"    => [],
        "properties" => []
    ];
    public function __construct($calss) {
        $this->_class = $calss;
    }
    protected function _getClassMethods() {
        $rc = new \ReflectionClass($this->_class);
        return $rc->getMethods();
    }
    protected function _getClassPerporties() {
        $rc = new \ReflectionClass($this->_class);
        return $rc->getProperties();
    }
    protected function _getClassComment() {
        $rc = new \ReflectionClass($this->_class);
        return $rc->getDocComment();
    }
    protected function _getMethodComment($method) {
        $rc = new \ReflectionMethod($this->_class, $method);
        return $rc->getDocComment();
    }
    protected function _getPropertyComment($propertie) {
        $rc = new \ReflectionProperty($this->_class, $propertie);
        return $rc->getDocComment();
    }
    protected function _parse($comment) {
        $meta    = [];
        $pattern = "([a-zA-z]+\s*[a-zA-Z0-9,()_]*)";
        $matches = StringMethods::match($comment, $pattern);
        foreach ($matches as $match) {
            $parts           = ArrayMethods::clean(ArrayMethods::trim(StringMethods::split($match, '[\s]', 2)));
            $meta[$parts[0]] = TRUE;
            if (count($parts) > 1) {
                $meta[$parts[0]] = ArrayMethods::clean(ArrayMethods::trim(StringMethods::split($parts[1], ",")));
            }
        }
        return $meta;
    }
    public function getClassMethods() {
        if (!isset($this->_methods)) {
            $methods = $this->_getClassMethods();
            foreach ($methods as $method) {
                $this->_methods[] = $method->getName();
            }
            return $this->_methods;
        }
    }
    public function getClassProperties() {
        if (!isset($this->_properties)) {
            $properties = $this->_getClassPerporties();
            foreach ($properties as $propertie) {
                $this->_properties = $propertie->getName();
            }
            return $this->_properties;
        }
    }
    public function getClassMeta() {
        if (!isset($this->_meta["class"])) {
            $comment              = $this->_getClassComment();
            $this->_meta["class"] = (!empty($comment)) ? $this->_parse($comment) : NULL;
        }
        return $this->_meta["class"];
    }
    public function getMethodMeta($method) {
        if (!isset($this->_meta["methods"][$method])) {
            $this->_meta["methods"][$method] = (!empty($this->_getMethodComment($method))) ? $this->_parse($this->_getMethodComment($method)) : NULL;
            return $this->_meta["methods"][$method];
        }
    }
    public function getPropertyMeta($propertie) {
        if (!isset($this->_meta["properties"][$propertie])) {
            $this->_meta["properties"][$propertie] = (!empty($this->_getPropertyComment($propertie))) ? $this->_parse($this->_getPropertyComment($propertie)) : NULL;
            return $this->_meta["properties"][$propertie];
        }
    }
}