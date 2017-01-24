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
        $this->_d = $this->db->selectAll("select * from g_dictionary where lang='".$cfg["lang"]."' order by priority desc");
    }
    public function translate($in){
        $out = $in;
        foreach($this->_d as $di){
            $out = preg_replace("/\b".preg_quote($di["original"])."\b/im",$di["translate"],$out);
        }
        return $out;
    }
};
?>
