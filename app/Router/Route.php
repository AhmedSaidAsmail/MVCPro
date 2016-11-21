<?php
namespace App\Router;

use App\Base;
class Route extends Base {
    /**
     * @readwrite
     */
    protected $pattern;
    /**
     * @readwrite
     */
    protected $controller;
    /**
     * @readwrite
     */
    protected $action;
    /**
     * @readwrite
     */
    protected $parameters = [];
}