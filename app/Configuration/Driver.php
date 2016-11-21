<?php
namespace App\Configuration;

use App\Base;
class Driver extends Base {
    protected $parsed = [];
    public function initialize() {
        return $this;
    }
}