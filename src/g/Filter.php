<?php
namespace g;
class Filter extends Common{
    protected $config;
    public function __construct($a = []){
        $this->config = new Common;
        $this->config->loadFromArray(count($a)?$a:[

        ]);
    }
    public function filter($s){
        $r = $s;
        $r = preg_replace("/(\\r\\n)(\\r\\n)+/i","$1",$r);
        $r = preg_replace("/(\\n)(\\n)+/i","$1",$r);
        return $r;
    }
};
?>
