<?php
include("autoload.php");
use g\Log as Log;
use g\Common as Common;
use g\DBConnector as DB;
$db = new DB();
$tick = time();
$out = 'Категория, Подкатегория, подкатегория, брэнд, название, перевод, описание, перевод, ссылка'."\n";
echo "Start in ".date("H:i:s")."\n";
try{
    $ops = $db->selectAll("SELECT g_categories,brand,title,g_title,description,g_description,url FROM g_product where shop='brandalley'");
    foreach ($ops as $op) {
        $desc = preg_replace("/[\r\n]+/im","",$op["description"]);
        $g_desc = preg_replace("/\&.+?\;/im","",$op["g_description"]);
        $out .= preg_replace("/(.+?)\s\/\s(.+?)\s\/\s(.+)/im","$1,$2,$3",$op["g_categories"]);
        $out .= ",".$op["brand"];
        $out .= ",".$op["title"];
        $out .= ",".$op["g_title"];
        $out .= ",".$desc;
        $out .= ",".$g_desc;
        $out .= ",".$op["url"];
        $out .= "\n";
    }
    file_put_contents("products_brand.csv",$out);
}
catch(\Exception $e){
    Log::debug("Error ".$e->getMessage());
}
echo "End in  ".date("H:i:s")."\n";
echo (time()-$tick)." seconds\n";
?>
