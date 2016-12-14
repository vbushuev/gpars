<?php
namespace g\CTShirts;
use \g\Common as Common;
use \g\Log as Log;
class Matcher extends \g\Product{
    protected $d=[];
    public $c_matcher = [];
    protected $tags = [];
    public function __construct($a = []){
        parent::__construct();
        $this->code = "CTS";
        $this->c_matcher = json_decode(file_get_contents("ctshirts.categories.json"),true);
        //$this->tags = json_decode(file_get_contents("ctshirts.tags.json"),true);
        $this->tr = new \g\Translator(["lang"=>"en"]);
        $this->d = $a;
    }
    public function store(){
        $this->matcher();
        $data = preg_replace('/\\//im',"/",Common::json($this->d));
        if(count($this->d["categories"])){
            file_put_contents("logs/data.parsed/".$this->d["sku"].".json",$data);//->__toString());
            $op = $this->d;
            $data = preg_replace("/\'/m","\\'",$data);
            $op["title"] = preg_replace("/\'/m","\\'",$op["title"]);
            $op["description"] = preg_replace("/\'/m","\\'",$op["description"]);
            try{
                if(!$this->db->exists("select 1 from g_product_adapted where id=".$op["id"])){
                    $this->db->insert("insert into g_product_adapted(id,rawdata,title,description,sku) values(
                        ".$op["id"].",'".$data."','".$op["title"]."','".$op["description"]."','".$op["sku"]."')");
                }else {
                    $this->db->insert("update g_product_adapted set
                        rawdata = '".$data."',
                        title = '".$op["title"]."',
                        description = '".$op["description"]."',
                        sku = '".$op["sku"]."'
                        where id=".$op["id"]);
                }
                $this->db->insert("delete from g_product_categories where product_id=".$op["id"]);
                foreach ($op["categories"] as $cat) {
                    $this->db->insert("insert into g_product_categories (category_id,product_id) values(".$cat.",".$op["id"].")");
                }
                $this->db->insert("update g_product set status=1,adapt_timestamp=CURRENT_TIMESTAMP where id=".$op["id"]);
            }catch(\Exception $e){
                Log::debug("Error ".$e->getMessage());
            }
            //$r = $this->connector->fetch("http://service.garan24.bs2/prod/create","POST",$data);

        }
    }
    public function storewoo(){
        $data = preg_replace('/\\//im',"/",Common::json($this->d));
        $r = $this->connector->fetch("http://service.garan24.bs2/prod/create","POST",$data);
        //$r = $this->connector->fetch("http://l.gauzymall.com/prod/create","POST",$data);
        $this->db->insert("update g_product set status=2 where sku = '".$this->d["sku"]."'");
        \g\Log::debug($r);
    }
    public function matcher(){
        $cat_name = "";
        //\g\Log::debug($this->d["categories"][0]);
        switch($this->d["categories"][0]){
            case "Shirts": $cat_name = "Рубашки ";break;
            case "Ties": $cat_name = "Галстуки ";break;
            case "Suits": $cat_name = "Костюмы ";break;
            case "Shoes": $cat_name = "Обувь ";break;
            case "Casualware": $cat_name = "Повседневная одежда ";break;
            case "Outerware": $cat_name = "Уличная одежда ";break;
            case "Accessories": $cat_name = "Акксессуары ";break;
            case "Womenswear": $cat_name = "Женская одежда ";break;
        }
        $this->d["short_description"] = $this->tr->translate($this->d["title"]);
        //$this->d["title"] = $cat_name."Charles Tyrwhitt - ".$this->tr->translate($this->d["title"]);
        $this->d["title"] = "Charles Tyrwhitt - ".$this->d["title"];
        $cats = $this->getCategories();
        $o_cat = join("-",$this->d["categories"]);
        //\g\Log::debug("Matching {".$o_cat."}[".(isset($cats[$o_cat])?"->".$cats[$o_cat]:"no match")."]");
        $this->d["categories"] = (isset($cats[$o_cat]))?$this->getparents($cats[$o_cat]):[];
        /* tags */
        $this->gettags($o_cat);


        //$this->d["status"] = preg_replace('/<h3(.+?)h3>/im',"",$this->d["description"]);
        $this->d["description"] = preg_replace('/<h3(.+?)h3>/im',"",$this->d["description"]);
        $this->d["description"] = preg_replace('/\s*class\s*="(.+?)"/im',"",$this->d["description"]);
        $this->d["description"] = $this->tr->translate($this->d["description"]);

        $this->d["product_url"] = preg_replace("/www\.ctshirts\.com/","ctshirts.gauzymall.com",$this->d["product_url"]);
        $this->d["product_url"] = preg_replace("/\/intl\//","/uk/",$this->d["product_url"]);
        /*$this->d['custom_meta'] = [
            'eg-original-price' => $this->d["regular_price"] ,
            'eg-original-price-currency' => "GBP" ,
        ];*/
        $this->d["status"] = "publish";
    }
    protected function getparents($id){
        $r = [];
        if(false != ($c = $this->getcategorybyid($id)) ){
            if($c["parent"]>0){
                $r = array_merge($r,$this->getparents($c["parent"]));
            }
            $r[] = $id;//["id"=>$id];
        }
        return $r;
    }
    protected function gettags($o_cat){
        if(preg_match_all('/<li(.+?)>(.+?)<(.+?)li>/',$this->d["description"], $m)){
            foreach($m[2] as $prop){
                $slug = $prop;
                $slug = preg_replace("/\d*%/","",$slug);
                $slug = strtolower(preg_replace("/\s+/","-",$slug));
                $slug = strtolower($o_cat)."-".$slug;
                $this->d["attributes"][] = [
                    "name"=>$this->tr->translate($prop),
                    "slug"=>$slug,
                    "position" => count($this->d["attributes"]),
                    "visible" => true,
                    "variation" =>false,
                    "options" => [$this->tr->translate($prop)]
                ];
            }
        }
    }
    public function getcategorybyid($id){
        foreach ($this->c_matcher as $c) {
            if($c["id"]==$id)return $c;
        }
        return false;
    }
    public function toArray(){
        return $this->d;
    }
    public function getCategories(){
        return [
            "Shirts-Formal shirts" => 64,
            "Shirts-Casual shirts" => 67,
            "Shirts-Business casual shirts" => 64,
            "Shirts-Luxury shirts" => 64,
            "Shirts-Evening shirts" => 64,
            "Shirts-Classic" => 64,
            "Shirts-Slim" => 63,
            "Shirts-Extra slim" => 63,
            "Shirts-Non-iron shirts" => 63,
            "Shirts-White shirt collection" => 63,
            "Shirts-Blue shirt collection" => 63,
            "Shirts-Egyptian cotton shirts" => 63,
            "Shirts-Short sleeve shirts" => 68,
            "Shirts-Non-iron honeycomb" => 63,
            "Shirts-Blue textured shirts" => 63,
            "Shirts-Mouline stripe shirts" => 63,
            "Shirts-Pima cotton shirts" => 63,
            "Shirts-Prince of Wales shirts" => 63,
            "Shirts-Ties" => 77,
            "Shirts-Cufflinks" => 79,
            "Ties-Ties" => 77,
            "Ties-Classic ties" => 77,
            "Ties-Luxury ties" => 77,
            "Ties-Bow ties" => 77,
            "Ties-Slim ties" => 77,
            "Ties-Silk ties" => 77,
            "Ties-Wool ties" => 77,
            "Ties-Tie bars" => 77,
            "Suits-Complete suits" => 55,
            "Suits-Trousers" => 57,
            "Suits-Jackets" => 47,
            "Suits-Waistcoats" => 46,
            "Suits-Business suits" => 55,
            "Suits-Luxury suits" => 55,
            "Suits-Travel suits" => 55,
            "Suits-Morning suits" => 55,
            "Suits-Tuxedos" => 55,
            "Suits-Classic" => 55,
            "Suits-Slim" => 55,
            "Suits-Two-piece suits" => 55,
            "Suits-Three-piece suits" => 55,
            "Suits-Navy blazers" => 47,
            "Suits-Pocket squares" => 80,
            "Suits-Ties" => 77,
            "Suits-Clearance suits" => 55,
            "Shoes-Business shoes" => 24,
            "Shoes-Business casual shoes" => 25,
            "Shoes-Casual shoes" => 30,
            "Shoes-Boots" => 31,
            "Shoes-Brogues" => 28,
            "Shoes-Oxford shoes" => 30,
            "Shoes-Derby shoes" => 30,
            "Shoes-Boat shoes" => 31,
            "Shoes-Monk shoes" => 28,
            "Shoes-Loafers" => 27,
            "Shoes-Wide fit shoes" => 28,
            "Shoes-Black shoes" => 24,
            "Shoes-Brown shoes" => 24,
            "Shoes-Goodyear welted shoes" => 24,
            "Shoes-Made in England shoes" => 24,
            "Shoes-Ultimate comfort shoes" => 28,
            "Shoes-Shoe care" => 39,
            "Shoes-Belts" => 78,
            "Shoes-Socks" => 174,
            "Shoes-Caring for your shoes" => 39,
            "Shoes-Clearance shoes" => 21,
            "Casualwear-Polo & rugby shirts" => 69,
            "Casualwear-Knitwear" => 49,
            "Casualwear-Casual trousers" => 56,
            "Casualwear-Shorts" => 60,
            "Casualwear-T-shirts & pyjamas" => 73,
            "Casualwear-Casual shirts" => 66,
            "Casualwear-Jumpers" => 49,
            "Casualwear-Cashmere knitwear" => 48,
            "Casualwear-Merino knitwear" => 48,
            "Casualwear-Non-iron chinos" => 59,
            "Casualwear-Corduroy trousers" => 57,
            "Casualwear-Tweed jackets" => 47,
            "Casualwear-Casual jackets" => 47,
            "Casualwear-Belts" => 78,
            "Casualwear-Short sleeve shirts" => 68,
            "Casualwear-Clearance casualwear" => 40,
            "Outerwear-Jackets & blazers" => 47,
            "Outerwear-Coats" => 41,
            "Outerwear-Overcoats" => 42,
            "Outerwear-Raincoats" => 42,
            "Outerwear-Tweed jackets" => 47,
            "Outerwear-Casual jackets" => 47,
            "Outerwear-Navy blazers" => 47,
            "Outerwear-Umbrellas" => 176,
            "Outerwear-Pocket squares" => 80,
            "Outerwear-Scarves" => 81,
            "Outerwear-Hats" => 76,
            "Outerwear-The Harrington jacket" => 47,
            "Outerwear-Clearance outerwear" => 40,
            "Accessories-Cufflinks" => 79,
            "Accessories-Socks" => 174,
            "Accessories-Belts" => 78,
            "Accessories-Underwear" => 175,
            "Accessories-Scarves" => 81,
            "Accessories-Shoe care" => 39,
            "Accessories-Pocket squares" => 80,
            "Accessories-Umbrellas" => 176,
            "Accessories-Gloves" => 82,
            "Accessories-Hats" => 76,
            "Accessories-Evening accessories" => 75,
            "Accessories-Collar stiffeners" => 75,
            "Accessories-Braces" => 75,
            "Accessories-Tie bars" => 75,
            "Accessories-Leather belts" => 78,
            "Accessories-Novelty cufflinks" => 79,
            "Accessories-Cuff knots" => 79,
            "Accessories-Ties" => 77,
            "Accessories-Bow ties" => 77,
            "Accessories-T-shirts & pyjamas" => 73,
            "Womenswear-Shirts" => 138,
            "Womenswear-Casual tops" => 138,
            "Womenswear-Knitwear" => 120,
            "Womenswear-Coats" => 112,
            "Womenswear-Essential white shirts" => 138,
            "Womenswear-Print shirts" => 138,
            "Womenswear-Long line knitwear" => 120
        ];
    }
};
?>
