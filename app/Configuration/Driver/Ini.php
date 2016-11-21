<?php
namespace App\Configuration\Driver;

use App\Configuration\Driver;
use App\ArrayMethods;
class Ini extends Driver {
    public function _pair($config, $key, $value) {
        if (strstr($key, ".")) {
            $parts = explode(".", $key, 2);
            if (empty($config[$parts[0]])) {
                $config[$parts[0]] = [];
            }
            $config[$parts[0]] = $this->_pair($config[$parts[0]], $parts[1], $value);
        }
        else {
            $config[$key] = $value;
        }
        return $config;
    }
    public function parse($path) {
        if (!isset($path)) {
            throw new \Exception("$path argument is not valid");
        }
        if (empty($this->parsed[$path])) {
            $config = [];
            ob_start();
            include __DIR__ . "/../{$path}.ini";
            $string = ob_get_contents();
            ob_end_clean();
            $pairs  = parse_ini_string($string);
            if ($pairs === FALSE) {
                throw new \Exception("Could not parse configuration file");
            }
            foreach ($pairs as $key => $value) {
                $config = $this->_pair($config, $key, $value);
            }
            $this->parsed[$path] = ArrayMethods::toObject($config);
        }
        return $this->parsed[$path];
    }
}