<?php
namespace g\CTShirts;
use \g\Common as Common;
class Product extends \g\Product{
    protected $d=[];
    public function __construct($a = []){
        parent::__construct();
        $this->code = "CTS";
    }
    public function get($u,$c){
        $p = [];
        $s = $this->connector->fetch($u);
        $r = \phpQuery::newDocument($s);
        $p["original_price"] = Common::stripNumber($r->find("#pdpMain")->find(".price.price__display.regular")->text());
        $p["regular_price"] = Common::stripNumber($r->find("#pdpMain")->find(".price.price__display.regular")->text());
        $p["title"] = Common::stripText($r->find("#pdpMain")->find(".product-name.pdp-main__name")->text());
        $p["description"] = Common::stripText(preg_replace("/[\r\n]+/","",pq("#wrapper > div:nth-child(5) > div.pdp-main__slot.js-accordion > div.pdp-main__slot.pdp-main__slot--shadowed.pdp-main__slot--full > div.pdp-main__slot.pdp-main__slot--left-group.pdp-main__slot--border-right > div.pdp-main__slot--outlined.pdp-main__slot--outlined-blue.js-slot-accordion.pdp-main__slot--accordion")->html()));
        $p["product_url"] = preg_replace("/www\.ctshirts\.com/","ctshirts.gauzymall.com",$u);
        $p["external_url"] = $p["product_url"];
        $p["product_img"] = $r->find("#pdpMain")->find("img.pdp-main__image")->attr("src");
        $p["sku"] = $this->code.preg_replace("/[\r\n]+/","",$r->find("#pdpMain")->find(".pdp-main__number span[itemprop='productID']:first")->text());
        $p["type"] = "external";
        $p["images"] = [];
        $p["categories"] = [];
        $p["variations"] = [];
        $p["attributes"] = [
            [
                "name"=>"Оригинальная сумма",
                "slug"=>"original_price",
                "position" => 0,
                "visible" => false,
                "variation" =>false,
                "options" => [$p["original_price"]]
            ],
            [
                "name"=>"Оригинальная валюта",
                "slug"=>"original_price_currency",
                "position" => 1,
                "visible" => false,
                "variation" =>false,
                "options" => ["GBP"]
            ],
        ];
        //$p["status"] = "";
        foreach($r->find("#pdpMain")->find("img.pdp-main__image") as $img){
            $p["images"][]=[
                "src"=>preg_replace("/\?.*$/","",$img->getAttribute("src")),
                "position"=>count($p["images"])
            ];
        }
        if(count($r->find(".swatches.size.attribute.attribute__variants-swatches"))){
            $o=[];
            foreach($r->find("#pdpMain")->find("ul.swatches.size.attribute.attribute__variants-swatches > li:not(.unselectable)") as $e){//sized
                $v = preg_replace("/[\n\r]*/","",pq($e)->find("div")->text());
                $p["variations"][] = [
                    "sku" => $p["sku"].$v,
                    "regular_price" =>$p["regular_price"]
                ];
                $o[] = $v;
            }
            $p["attributes"][] = [
                "name" =>"Размер",
                "position" => count($p["attributes"]),
                "visible" => true,
                "variation" =>true,
                "options" => $o
            ];
        }
        if(count($r->find(".swatches.width.attribute.attribute__variants-swatches"))){
            $o = [];
            foreach($r->find("#pdpMain")->find("ul.swatches.width.attribute.attribute__variants-swatches > li:not(.unselectable)") as $e){//sized
                $v = preg_replace("/[\n\r]*/","",pq($e)->find("div")->text());
                $o[] = $v;
                $p["variations"][] = [
                    "sku" => $p["sku"].$v,
                    "regular_price" =>$p["regular_price"]
                ];
            }
            $p["attributes"][] = [
                "name" =>"Размер воротника",
                "position" => count($p["attributes"]),
                "visible" => true,
                "variation" =>true,
                "options" => $o
            ];
        }
        if(count($r->find(".swatches.length.attribute.attribute__variants-swatches"))){
            $o=[];
            foreach($r->find("#pdpMain")->find("ul.swatches.length.attribute.attribute__variants-swatches > li:not(.unselectable)") as $e){//sized
                $v = preg_replace("/[\n\r\D]*/","",pq($e)->find("div")->text());
                $p["variations"][] = [
                    "sku" => $p["sku"].$v,
                    "regular_price" =>$p["regular_price"]
                ];
                $o[] = $v;
            }
            $p["attributes"][] = [
                "name" =>"Длина рукава",
                "position" => count($p["attributes"]),
                "visible" => true,
                "variation" =>true,
                "options" => []
            ];
        }
        if(count($r->find(".swatches.cufftype.attribute.attribute__variants-swatches"))){
            $o=[];
            foreach($r->find("#pdpMain")->find("ul.swatches.cufftype.attribute.attribute__variants-swatches > li:not(.unselectable)") as $e){//sized
                $v = preg_replace("/[\n\r]*/","",pq($e)->find("div")->text());
                $p["variations"][] = [
                    "sku" => $p["sku"].$v,
                    "regular_price" =>$p["regular_price"]
                ];
                $o[] = $v;
            }
            $p["attributes"][] = [
                "name" =>"Тип манжета",
                "position" => count($p["attributes"]),
                "visible" => true,
                "variation" =>true,
                "options" => $o
            ];
        }
        $this->d = $p;
        return $p;
    }
    public function store(){
        file_put_contents("logs/data/".$this->d["sku"].".json",preg_replace('/\\//im',"//",Common::json($this->d)));//->__toString());
        return  $this->connector->fetch("http://service.garan24.bs2/prod/create","POST",Common::json($this->d));
    }
};
?>
