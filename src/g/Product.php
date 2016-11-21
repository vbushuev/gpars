<?php
namespace g;
class Product extends Common{
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
    public function __set($n,$v){
        $v = preg_replace("/^\\n/","",$v);
        $v = preg_replace("/\\n$/","",$v);
        if(isset($this->$n))$this->$n=$v;
    }
};
?>
