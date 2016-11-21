<?php
namespace App;

use App\Base;
use App\Configuration as Configuration;
class Configuration extends Base {
    protected $type;
    protected $option;
    public function initialize() {
        if (!$this->type) {
            throw new Exception("Invalid type");
        }
        switch ($this->type) {
            case "ini": {
                    return new Configuration\Driver\Ini($this->option);
                    break;
                }
            default : {
                    throw new \Exception("Invalid type");
                    break;
                }
        }
    }
}