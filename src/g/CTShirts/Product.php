<?php
namespace g\CTShirts;
use \g\Common as Common;
use \g\Log as Log;
class Product extends \g\Product{
    protected $d=[];
    protected $c_matcher = [];
    public function __construct($a = []){
        parent::__construct();
        $this->code = "CTS";
        $this->c_matcher = json_decode(file_get_contents("categories.json"),true);
        $this->tr = new \g\Translator(["lang"=>"en"]);
    }
    public function get($u,$c){
        $p = [];
        $s = $this->connector->fetch($u);

        $r = \phpQuery::newDocument($s);
        $p["product_url"]  = $u;
        $p["original_price"] = Common::stripNumber($r->find("#pdpMain")->find(".price.price__display:first")->text());
        $p["regular_price"] = Common::stripNumber($r->find("#pdpMain")->find(".price.price__display:first")->text());
        $p["title"] =Common::stripText($r->find("#pdpMain")->find(".product-name.pdp-main__name:first")->text());
        if(!strlen($p["title"]))$p["title"] =Common::stripText($r->find("#pdpMain")->find(".product-set__name.product-set__name--main")->text());
        $p["short_description"] = Common::stripText(preg_replace("/[\r\n]+/","",pq("#wrapper > div:nth-child(5) > div.pdp-main__slot.js-accordion > div.pdp-main__slot.pdp-main__slot--shadowed.pdp-main__slot--full > div.pdp-main__slot.pdp-main__slot--left-group.pdp-main__slot--border-right > div.pdp-main__slot--outlined.pdp-main__slot--outlined-blue.js-slot-accordion.pdp-main__slot--accordion")->html()));
        $p["description"] = Common::stripText(preg_replace("/[\r\n]+/","",pq("#wrapper > div:nth-child(5) > div.pdp-main__slot.js-accordion > div.pdp-main__slot.pdp-main__slot--shadowed.pdp-main__slot--full > div.pdp-main__slot.pdp-main__slot--left-group.pdp-main__slot--border-right > div.pdp-main__slot--outlined.pdp-main__slot--outlined-blue.js-slot-accordion.pdp-main__slot--accordion")->html()));
        $p["sku"] = $this->code.preg_replace("/[\r\n]+/","",$r->find("#pdpMain")->find(".pdp-main__number span[itemprop='productID']:first")->text());
        $p["type"] = "external";
        $p["images"] = [];
        $p["categories"] = [];
        if(file_exists('logs/data/'.$p["sku"].".json")){
            $p_old = json_decode(file_get_contents('logs/data/'.$p["sku"].".json"),true);
            if(is_array($p_old["categories"])){
                $p["categories"] = $p_old["categories"];
            }

        }
        $p["categories"] = array_merge($p["categories"],$c);
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
            [
                "name"=>"Оригинальное название",
                "slug"=>"original_title",
                "position" => 2,
                "visible" => false,
                "variation" =>false,
                "options" => $p["title"]
            ],
            [
                "name"=>"Оригинальное описание",
                "slug"=>"original_description",
                "position" => 1,
                "visible" => false,
                "variation" =>false,
                "options" => $p["description"]
            ],
            [
                "name"=>"Оригинальная ссылка",
                "slug"=>"original_url",
                "position" => 1,
                "visible" => false,
                "variation" =>false,
                "options" => $p["product_url"]
            ],
            [
                "name"=>"Оригинальная категория",
                "slug"=>"original_category",
                "position" => 1,
                "visible" => false,
                "variation" =>false,
                "options" => $c
            ]
        ];

        //$p["external_url"] = $p["product_url"];
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
        file_put_contents("logs/pages/".$this->d["sku"].".html",$s);
        $this->store();
        return $p;
    }
    public function store(){
        file_put_contents("logs/data/".$this->d["sku"].".json",preg_replace('/\\//im',"/",Common::json($this->d)));//->__toString());
        $data = preg_replace("/\'/m","\\'",Common::json($this->d));
        $op = $this->d;
        $op["title"] = preg_replace("/\'/m","\\'",$op["title"]);
        $op["description"] = preg_replace("/\'/m","\\'",$op["description"]);
        try{
            if(!$this->db->exists("select 1 from g_product where sku='".$op["sku"]."'")){
                $this->db->insert("insert into g_product(rawdata,shop,title,categories,description,original_price,currency,sku,url) values(
                    '".$data."','ctshirts.com','".$op["title"]."','".join(' | ',$op["categories"])."','".$op["description"]."','".$op["original_price"]."','GBP','".$op["sku"]."','".$op["product_url"]."')");
            }else {
                $this->db->insert("update g_product set
                    rawdata = '".$data."',
                    title = '".$op["title"]."',
                    categories ='".join(' | ',$op["categories"])."',
                    description = '".$op["description"]."',
                    original_price ='".$op["original_price"]."',
                    currency = 'GBP',
                    url = '".$op["product_url"]."',
                    status = 0
                    where sku='".$op["sku"]."'");
            }
        }catch(\Exception $e){
            Log::debug($entry." error load".$e->getMessage());
        }
        //return  $this->connector->fetch("http://service.garan24.bs2/prod/create","POST",Common::json($this->d));
    }
};
?>
