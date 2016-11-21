<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
class WebController extends Controller {
    public function ahmedtest($name) {
        echo "my name is " . $name;
    }
        public function ahmedtest2($name) {
        echo "my son is " . $name;
    }
    public function testbefore()
    {
        echo "test before ";
    }
    /**
     * @before testbefore 
     */
            public function ahmedtest3() {
        echo "my none is ";
    }
}