<?php
namespace g;
class Loader extends Common{
    protected $config;
    public function __construct($a = []){
        $this->config = new Common;
        $this->config->loadFromArray(count($a)?$a:[

        ]);
    }
    public function loader($s){
        $r = null;//simplexml_load_string($s);
        return $r;
    }
};
?>
