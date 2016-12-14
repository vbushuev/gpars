<?php
namespace g;
class Translator extends Common{
    protected $_d = [];
    protected $db;
    public function __construct($cfg){
        $this->db = new \g\DBConnector();
        $this->setLang($cfg);

    }
    public function setLang($cfg){
        $dict = $this->db->selectAll("select * from g_dictionary where lang='".$cfg["lang"]."' order by priority desc");
        $dict_fname = "dicts/".$cfg["lang"].".php";
        if(file_exists($dict_fname)){
            include($dict_fname);
            if(isset($_dicts))$this->_d = array_merge($this->_d,$_dicts);
        }
        /*$dict_fname = "dicts/".$cfg->section.".php";
        if(file_exists($dict_fname)){
            include($dict_fname);
            if(isset($_dicts))$this->_d = array_merge($this->_d,$_dicts);
        }*/
    }
    public function translate($in){
        $out = $in;
        foreach($this->_d as $s=>$t){
            $out = preg_replace("/".preg_quote($s)."/im",$t,$out);
        }
        return $out;
    }
};
?>
