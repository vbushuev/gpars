<?php
namespace g;
class Common{
    public function loadFromJson($s){
        $d = json_decode($s,true);
        $this->loadFromArray($d);
    }
    public function loadFromArray($d){
        foreach($d as $k=>$v){
            $this->$k = $v;
        }
    }
    public function __get($n){
        return isset($this->$n)?$this->$n:false;
    }
    public function __set($n,$v){
        if(isset($this->$n))$this->$n=$v;
    }
    public function __toString(){
        return json_encode($this,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    }
    public function checkPath($p){
        $pi = pathinfo($p);
        $dir = $pi["dirname"];
        //Log::debug("check dir ".$dir);
        if(!file_exists($dir)||!is_dir($dir))mkdir($dir,0777,true);
    }
};
?>
