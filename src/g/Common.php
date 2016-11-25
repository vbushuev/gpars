<?php
namespace g;
class Common{
    protected $_properties = [];
    public function loadFromJson($s){
        $d = json_decode($s,true);
        $this->loadFromArray($d);
    }
    public function loadFromArray($d){
        if(is_array($d)){
            foreach($d as $k=>$v){
                $this->__set($k,$v);
            }
        }
    }
    public function __get($n){
        return isset($this->_properties[$n])?$this->$this->_properties[$n]:false;
    }
    public function __set($n,$v){
        $this->_properties[$n]=$v;
    }
    public function __toString(){
        return json_encode($this->toArray(),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    }
    public function toArray(){
        $r = [];
        foreach($this->_properties as $k=>$v){
            if(false && is_object($v)&&($v instanceof Common)) $r[$k] = $v->toArray();
            //elseif(is_array($v)) $r[$k] = $v;
            else $r[$k] = $v;
        }
        return $this->_properties;
    }
    public function checkPath($p){
        $pi = \pathinfo($p);
        $dir = $pi["dirname"];
        //Log::debug("check dir ".$dir);
        if(!file_exists($dir)||!is_dir($dir))mkdir($dir,0777,true);
    }
    public static function stripText($s){
        $r = trim($s);
        return $r;
    }
    public static function stripNumber($s){
        $r = preg_replace("/,/",".",$s);
        $r = preg_replace("/[^\d\.]/m","",$r);
        return $r;
    }
    public static function json($a){
        return (is_array($a))?json_encode($a,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE):'{"nodata"}';
    }
};
?>
