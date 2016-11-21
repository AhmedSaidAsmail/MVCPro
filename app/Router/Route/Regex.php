<?php
namespace App\Router\Route;

use App\Router\Route;
class Regex extends Route {
    /**
     * @readwrite
     */
    protected $keys;
    public function matches($url) {
        $pattern = $this->pattern;
        preg_match_all("#^{$pattern}$#", $url, $values);
        if (sizeof($values) && sizeof($values[0]) && sizeof($values[1])) {
            $derived          = array_combine($this->keys, $values[1]);
            $this->parameters = array_merge($this->parameters, $derived);
            return true;
        }
        return false;
    }
}