<?php
namespace App;
class ArrayMethods {
    private function __construct() {
        
    }
    private function __clone() {
        
    }
    public function flatten($array, $return = []) {
        foreach ($array as  $value) {
            if (is_array($value) || is_object($value)) {
                $return = self::flatten($value, $return);
            }
            else {
                $return[] = $value;
            }
        }
        return $return;
    }
    public static function clean($array) {
        return array_filter($array, function($item) {
            return !empty($item);
        });
    }
    public static function trim($array) {
        return array_map(function ($item) {
            return trim($item);
        }, $array);
    }
    public static function toObject($array) {
        $result = new \stdClass();
        foreach ($array as $key => $value) {
            $result->{$key} = (is_array($value)) ? self::toObject($value) : $value;
        }
        return $result;
    }
}