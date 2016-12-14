<?php
namespace g\Brandalley;
use \g\Common as Common;
use \g\Log as Log;
class Matcher extends \g\Product{
    protected $d=[];
    public $c_matcher = [];
    protected $tags = [];
    public function __construct($a = []){
        parent::__construct();
        $this->code = "BRA";
        $this->c_matcher = json_decode(file_get_contents("categories.new.json"),true);
        //$this->tags = json_decode(file_get_contents("ctshirts.tags.json"),true);
        $this->tr = new \g\Translator(["lang"=>"fr"]);
        $this->d = $a;
        $this->d["categories"] = preg_split("/\s\|\s/",$this->d["categories"]);
    }
    public function store(){
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
        $this->d["short_description"] = $this->tr->translate($this->d["title"]);
        //$this->d["title"] = $cat_name."Charles Tyrwhitt - ".$this->tr->translate($this->d["title"]);
        $this->d["title"] = "Charles Tyrwhitt - ".$this->d["title"];
        $cats = $this->getCategories();
        $o_cat = join("-",$this->d["categories"]);
        //\g\Log::debug("Matching {".$o_cat."}[".(isset($cats[$o_cat])?"->".$cats[$o_cat]:"no match")."]");
        $this->d["categories"] = (isset($cats[$o_cat]))?$this->getparents($cats[$o_cat]):[];
        return $this->d["categories"];
    }
    protected function getparents($id){
        $r = [];
        if(false != ($c = $this->getcategorybyid($id)) ){
            if($c["parent"]>0){
                $r = array_merge($r,$this->getparents($c["parent"]));
            }
            $r[] = ["id"=>$id,"name"=>$c["name"]];//["id"=>$id];
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
            "FEMME-Manteaux, Blousons" => 114,
            "FEMME-Robes" => 129,
            "FEMME-Pulls, Gilets, Sweats" => 119,
            "FEMME-Jeans" => 133,
            "FEMME-T-Shirts, DÃ©bardeurs, Polos" => 141,
            "FEMME-Pantalons, Leggings, Combinaisons" => 131,
            "FEMME-Blouses, Tuniques, Chemisiers" => 138,
            "FEMME-Jupes" => 130,
            "FEMME-Vestes, Blazers" => 118,
            "FEMME-Shorts, Bermudas" => 135,
            "FEMME-Maillots de Bain, ParÃ©os" => 170,
            "FEMME-Bottes, Cuissardes" => 104,
            "FEMME-Boots" => 101,
            "FEMME-Baskets, Sneakers" => 106,
            "FEMME-Escarpins" => 99,
            "FEMME-Chaussures lacÃ©es, Derbies, Mocassins" => 101,
            "FEMME-Sandales, Tongs, Espadrilles" => 97,
            "FEMME-Ballerines, Babies, SalomÃ©s" => 98,
            "FEMME-Chaussons" => 108,
            "FEMME-Chaussures de sport" => 106,
            "FEMME-Accessoires chaussures" => 110,
            "FEMME-Chaussettes, Collants" => 163,
            "FEMME-Sacs" => 165,
            "FEMME-Cartable, Sacoche" => 165,
            "FEMME-Petite Maroquinerie" => 165,
            "FEMME-Bagagerie" => 168,
            "FEMME-Parapluie" => 190,
            "FEMME-Echarpes, Foulards" => 151,
            "FEMME-Lunettes de soleil" => 189,
            "FEMME-Ceintures" => 148,
            "FEMME-Chapeaux, Bonnets, Casquettes" => 146,
            "FEMME-Gants" => 152,
            "FEMME-Accessoires High Tech" => 145,
            "FEMME-Accessoires Chaussures" => 110,
            "FEMME-Soutiens-gorge" => 154,
            "FEMME-Culottes, Strings, Shorties" => 155,
            "FEMME-Homewear" => 142,
            "FEMME-Bodies" => 157,
            "FEMME-Collants, Chaussettes" => 159,
            "FEMME-Lingerie Sexy" => 156,
            "FEMME-Tous les Maillots de Bain" => 170,
            "HOMME-Manteaux, Blousons, Doudounes" => 43,
            "HOMME-Jeans" => 58,
            "HOMME-T-Shirts, Polos" => 72,
            "HOMME-Pulls, Gilets" => 48,
            "HOMME-Sweats" => 49,
            "HOMME-Chemises" => 66,
            "HOMME-Pantalons" => 57,
            "HOMME-Vestes" => 43,
            "HOMME-Shorts, Bermudas" => 60,
            "HOMME-Costumes" => 55,
            "HOMME-Maillots de Bain" => 91,
            "HOMME-Baskets, Sneakers" => 35,
            "HOMME-Boots" => 28,
            "HOMME-Derbies" => 23,
            "HOMME-Mocassins" => 27,
            "HOMME-Chaussures de sport" => 35,
            "HOMME-Chaussures bateau" => 23,
            "HOMME-Richelieus" => 23,
            "HOMME-Sandales, Tongs" => 22,
            "HOMME-Chaussons" => 37,
            "HOMME-Espadrilles" => 34,
            "HOMME-Chaussettes" => 174,
            "HOMME-Sacs" => 85,
            "HOMME-Portefeuille" => 85,
            "HOMME-Bagagerie" => 89,
            "HOMME-Cartable, Sacoche" => 85,
            "HOMME-Petite maroquinerie" => 85,
            "HOMME-Boutons de manchette" => 79,
            "HOMME-Ceintures" => 78,
            "HOMME-Echarpes, ChÃ¨ches" => 81,
            "HOMME-Bonnets, Casquettes, Chapeaux" => 76,
            "HOMME-Lunettes de Soleil" => 188,
            "HOMME-Gants" => 82,
            "HOMME-Accessoires High-Tech" => 75,
            "HOMME-Cravates, Noeuds Papillon" => 77,
            "HOMME-Boutons de Manchette" => 79,
            "HOMME-Parapluies" => 176,
            "HOMME-Accessoires iPhone" => 75,
            "HOMME-Boxers, CaleÃ§ons, Slips" => 175,
            "HOMME-Pyjamas, Maillots de Corps" => 73,
            "HOMME-Chausettes Multicolore" => 174
        ];
    }
};
?>
