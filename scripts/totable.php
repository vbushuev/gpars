<?php
include("autoload.php");
use g\Log as Log;
use g\Common as Common;
use g\DBConnector as DB;
$dir = 'logs/data';
$db = new DB();
if ($handle = opendir($dir)) {
    while (false !== ($entry = readdir($handle))) {
        if(preg_match("/^\.{1,2}$/",$entry))continue;
        $data = file_get_contents($dir.'/'.$entry);
        $op = json_decode($data,true);
        try{
            if(isset($op["sku"])){
                $data = preg_replace("/\'/m","\\'",$data);
                $op["title"] = preg_replace("/\'/m","\\'",$op["title"]);
                $op["description"] = preg_replace("/\'/m","\\'",$op["description"]);
                if(!$db->exists("select 1 from g_product where sku='".$op["sku"]."'")){
                    $db->insert("insert into g_product(rawdata,shop,title,categories,description,original_price,currency,sku,url) values(
                        '".$data."','ctshirts.com','".$op["title"]."','".join(' | ',$op["categories"])."','".$op["description"]."','".$op["original_price"]."','GBP','".$op["sku"]."','".$op["product_url"]."')");
                }else {
                    $db->insert("update g_product set
                        rawdata = '".$data."',
                        title = '".$op["title"]."',
                        categories ='".join(' | ',$op["categories"])."',
                        description = '".$op["description"]."',
                        original_price ='".$op["original_price"]."',
                        currency = 'GPB',
                        url = '".$op["product_url"]."'
                        where sku='".$op["sku"]."'");
                }

            }
        }
        catch(\Exception $e){
            Log::debug($entry." error load".$e->getMessage());
        }
    }
    closedir($handle);
}

?>
