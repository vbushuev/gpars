<?php
include("autoload.php");
use g\Log as Log;
use g\Common as Common;
use g\DBConnector as DB;
ini_set('max_execution_time', 60*60*12);
$connector = new \g\Connector();
$db = new DB();
$db->schema = 'gauzymall';
$db->user = 'gauzymall';
$db->pass = 'D6a8O2e1';
$db->prefix = '';
$tick = time();
$total = 0;
$total_removed = 0;
$max = 100;
$sleeped = 2;
//echo "Start in ".date("H:i:s")."\n";
try{
    $i = 0;
    $post_id = 0;
    //get products
    $products = $db->selectAll("select p.*, m.meta_value from xr_posts p join xr_postmeta m on m.post_id = p.id where p.post_type = 'product' and m.meta_key = '_product_url' and p.post_status = 'publish' and p.id> ".$post_id." order by p.id");
    //$products = $db->selectAll("select p.*, m.meta_value from xr_posts p join xr_postmeta m on m.post_id = p.id where p.post_type = 'product' and m.meta_key = '_product_url' and p.post_status = 'publish' limit 10");
    $urls = [];
    $pps = [];
    foreach ($products as $product) {
        $url = preg_replace("/http:\/\/g-ct\.gauzymall\.com/i","http://www.ctshirts.com",$product["meta_value"]);
        $url = preg_replace("/http:\/\/shop1\.gauzymall\.com/i","http://www.ctshirts.com",$url);
        $url = preg_replace("/http:\/\/g-ba\.gauzymall\.com/i","https://www.brandalley.fr",$url);
        $urls[]=$url;
        $pps[$url] = $product["ID"];
        $post_id = $product["ID"];
        $i++;
        if($i>=$max){
            $to_remove=[];
            $connector->fetchMulti($urls);
            foreach ($connector->http_info as $url => $value) {
                if($value["http_code"] == "0"){
                    echo "0 code for ".$url."\n";
                }
                if($value["http_code"] == "404"){
                    if(isset($pps[$url])){
                        $to_remove[] = $pps[$url];
                        $total_removed++;
                    }
                }

                //echo "check HTTP ".$value["http_code"]." ".$url."\n";
            }
            if(count($to_remove)){
                echo ("update xr_posts set post_status='trash' where id in (".join(',',$to_remove).")"."\n");break;
                echo "Remove ids: ".join(',',$to_remove)."\n";
                $db->update("update xr_posts set post_status='trash' where id in (".join(',',$to_remove).")");
            }
            echo "next 100 links checked\n";
            $i=0;
            $urls = [];
        }
        $total++;
    }
    //print_r($pps);
}
catch(\Exception $e){
    Log::debug("Error ".$e->getMessage());
}
//echo "End in  ".date("H:i:s")."\n";
echo $total_removed." of ".$total." in ". (time()-$tick)." seconds\n";
?>
