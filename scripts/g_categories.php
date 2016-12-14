<?php
include("autoload.php");
use g\Log as Log;
use g\Common as Common;
use g\DBConnector as DB;
use g\CTShirts\Matcher as CTSMatcher;
$db = new DB();
class cmatcher{
    protected $g_categories;
    public function __construct(){
        $this->g_categories = json_decode(file_get_contents("ctshirts.categories.json"),true);
    }
    public function match($o_c){
        $o_cat = join("-",$o_c);
        $cats = $this->getCategories();
        return (isset($cats[$o_cat]))?$this->getparents($cats[$o_cat]):[];
    }
    protected function getcategorybyid($id){
        foreach ($this->g_categories as $c) {
            if($c["id"]==$id)return $c;
        }
        return false;
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
    protected function getCategories(){
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
$cm = new cmatcher();
$dirs = ["logs/data","logs/data.1"];
$tick = time();
echo "Start in ".date("H:i:s")."\n";
try{
    //get products
    //$ops = $db->selectAll("select * from g_product where status in ('translated')");
    $ops = $db->selectAll("select * from g_product where status = 'new'");
    foreach ($ops as $op) {
        $o_c = preg_split("/\s\/\s/",$op["categories"]);
        $g_cs = $cm->match($o_c);
        //print_r($g_cs);exit;
        $g_c = [];
        $g_c_id = [];
        $g_url = preg_replace("/http(s*)\:\/\/(.+?)\.(.+?)\.(.+?)\//i","http://$3.gauzymall.com/",$op["url"]);
        foreach ($g_cs as $gcc) {
            $g_c[] = $gcc["name"];
            $g_c_id[] = $gcc["id"];
        }
        $db->update("update g_product set g_categories='".join(" / ",$g_c)."',g_categories_id='".join(" / ",$g_c_id)."',status='categories',g_url='".$g_url."' where id=".$op["id"]);
    }
}
catch(\Exception $e){
    Log::debug("Error ".$e->getMessage());
}
echo "End in  ".date("H:i:s")."\n";
echo (time()-$tick)." seconds\n";
?>
