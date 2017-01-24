<?php
namespace g\Brandalley;
use \g\Common as Common;
use \g\Log as Log;
class Product extends \g\Product{
    protected $d=[];
    protected $c_matcher = [];
    public function __construct($a = []){
        parent::__construct();
        $this->code = "BRA";
        $this->c_matcher = json_decode(file_get_contents("categories.json"),true);
    }
    public function get($u,$c){
        $p = [];
        $s = $this->connector->fetch($u);

        $r = \phpQuery::newDocument($s);
        $p["product_url"]  = $u;

        $p["sku"] = $this->code.preg_replace("/[\D]+/","",preg_replace("/^(.+?)\/([^\/]+)$/","$2",$u));
        echo "\t\t\tSKU:[".$p["sku"]."]\n";
        $p["images"] = [];
        $p["categories"] = $c;
        $p["variations"] = [];
        $p["type"] = "external";
        $p["brand"] = Common::stripText($r->find("#col-right > div.col_right_haut > h1 > a")->text());
        $p["title"] =Common::stripText($r->find("#col-right > div.col_right_haut > h1 > span")->text());
        $p["description"] =Common::stripText($r->find("#description_produit > div")[0]->html());
        $p["original_price"] = Common::stripNumber($r->find("#block_price > span.price_stroke.font_trade_gothic.regular-price")->text());
        $p["regular_price"] = Common::stripNumber($r->find("#price > span.pull-left.font_trade_gothic.price.current-price")->text());
        $p["sale_price"] = Common::stripNumber($r->find("#price > span.pull-left.font_trade_gothic.price.current-price")->text());

        foreach($r->find("#mainImage .slide") as $img){
            $pe = pq($img);
            $p["images"][]=[
                "src"=>"http:".preg_replace("/\?.*$/","",$pe->find("a img")->attr("src")),
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
        //file_put_contents("logs/data/".$this->d["sku"].".json",preg_replace('/\\//im',"/",Common::json($this->d)));//->__toString());
        $data = preg_replace("/\'/m","\\'",Common::json($this->d));
        $op = $this->d;
        $op["title"] = preg_replace("/\'/m","\\'",$op["title"]);
        $op["description"] = preg_replace("/\'/m","\\'",$op["description"]);
        try{
            $imgs = [];
            foreach($op["images"] as $img){
                $imgs[]=$img["src"];
            }
            if(!$this->db->exists("select 1 from g_product where sku='".$op["sku"]."'")){
                $this->db->insert("insert into g_product(rawdata,shop,shop_url,brand,title,categories,description,original_price,currency,sku,url,regular_price,sale_price,images) values(
                    '".$data."','BrandAlley','www-v6.brandalley.fr','".$op["brand"]."','".$op["title"]."','".join(' | ',$op["categories"])."','".$op["description"]."','".$op["original_price"]."','EUR','".$op["sku"]."','".$op["product_url"]."',
                    '".$op["regular_price"]."','".$op["sale_price"]."',
                    '".join(',', $imgs)."')");
            }else {
                $this->db->insert("update g_product set
                    shop = 'BrandAlley',
                    shop_url = 'www-v6.brandalley.fr',
                    brand = '".$op["brand"]."',
                    rawdata = '".$data."',
                    title = '".$op["title"]."',
                    categories ='".join(' | ',$op["categories"])."',
                    description = '".$op["description"]."',
                    original_price ='".$op["original_price"]."',
                    regular_price ='".$op["regular_price"]."',
                    sale_price ='".$op["sale_price"]."',
                    currency = 'EUR',
                    url = '".$op["product_url"]."',
                    status = 'new',
                    images = '".join(' | ', $imgs)."'
                    where sku='".$op["sku"]."'");
            }
        }
        catch(\Exception $e){
            Log::debug("Error load ".$e->getMessage());
        }
        //return  $this->connector->fetch("http://service.garan24.bs2/prod/create","POST",Common::json($this->d));
    }
};
?>
