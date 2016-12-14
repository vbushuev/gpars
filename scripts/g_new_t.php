<?php
include("autoload.php");
use g\Log as Log;
use g\Common as Common;
use g\DBConnector as DB;
$dir = 'logs/data';
$db = new DB();
$sql = [];
$tick = time();
echo "Start in ".date("H:i:s")."\n";
if ($handle = opendir($dir)) {
    while (false !== ($entry = readdir($handle))) {
        if(preg_match("/^\.{1,2}$/",$entry))continue;
        $data = file_get_contents($dir.'/'.$entry);
        $op = json_decode($data,true);
        try{
            if(isset($op["sku"])){
                $images = [];foreach($op["images"] as $img) $images[]=$img["src"];//join(' / ',$op["images"])
                $attrs = [];//foreach($op["attributes"] as $attr)if(isset($attr["options"])&&count($attr["options"]))$attrs[]=$attr["name"]."=".$attr["options"][0];//join(' / ',$op["attributes"])
                $data = preg_replace("/\'/m","\\'",$data);
                $op["title"] = preg_replace("/\'/m","\\'",$op["title"]);
                $op["description"] = preg_replace("/\'/m","\\'",$op["description"]);
                $op["sku"] = preg_replace('/CTS/',"",$op["sku"]);
                $s="insert into g_product(shop,shop_url,brand,title,categories,description,original_price,currency,sku,url,options,images,rawdata)"
                    ."values('Charles Tyrwitt','www.ctshirts.com','Charles Tyrwitt','"
                        .$op["title"]."','"
                        .join(' / ',$op["categories"])."','"
                        .htmlspecialchars($op["description"])."','"
                        .$op["original_price"]."','GBP','"
                        .$op["sku"]."','"
                        .$op["product_url"]."','"

                        .join(",",$attrs)."','"
                        .join(",",$images)."','"
                        ."{}"//.preg_replace("/\s*[\n]\s*/","",$data)
                        ."')";
                $sql[]=$s;
                $db->insert($s);
            }
        }
        catch(\Exception $e){
            Log::debug($entry." error load".$e->getMessage());
        }
    }
    closedir($handle);
}
echo "End in  ".date("H:i:s")."\n";
echo (time()-$tick)." seconds\n";
//Log::debug(join("\n",$sql));
//print(join("\n",$sql));
?>
