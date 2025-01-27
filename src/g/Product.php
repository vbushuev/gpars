<?php
namespace g;
class Product extends Common{
    protected $code;
    protected $db;
    protected $connector=null;
    /*
    protected $shop = "ctshirts.com";
    protected $currency = "GBP";
    protected $original_price = "";
    protected $regular_price = "";
    protected $title = "";
    protected $description = "";
    protected $product_img = "";
    protected $images = [];
    protected $product_url = "";
    protected $external_url = "";
    protected $sku = "";
    protected $categories = [];
    protected $type = "";
    protected $variations = [];
    protected $attributes = [];
    protected $status = "";
    */
    protected $tr = null;
    public function __construct(){
        $this->connector = new \g\Connector();
        $this->db = new \g\DBConnector();
    }
    public function __set($n,$v){
        $v = preg_replace("/^\\n/m","",$v);
        $v = preg_replace("/\\n$/m","",$v);
        $this->_properties[$n]=$v;
    }

};
?>
