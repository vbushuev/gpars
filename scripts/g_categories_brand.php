<?php
include("autoload.php");
use g\Log as Log;
use g\Common as Common;
use g\DBConnector as DB;
use g\Brandalley\Matcher as Matcher;
$db = new DB();
$tick = time();
echo "Start in ".date("H:i:s")."\n";
try{
    //get products
    //$ops = $db->selectAll("select * from g_product where status in ('translated')");
    $ops = $db->update("update g_product set g_description = replace(g_description,'Ã©','é'),description = replace(description,'Ã©','é'),g_description = replace(g_description,'Ã¨','è'),description = replace(description,'Ã¨','è'),g_description = replace(g_description,'Ã','à'),description = replace(description,'Ã','à') where shop = 'brandalley';");
    $ops = $db->selectAll("select * from g_product where status in ('new','updated') and shop='brandalley'");
    foreach ($ops as $op) {
        $cm = new Matcher($op);
        $g_cs = $cm->matcher();
        $g_c = [];
        $g_c_id = [];
        $g_url = preg_replace("/http(s*)\:\/\/(.+?)\.(.+?)\.(.+?)\//i","http://g-ba.gauzymall.com/",$op["url"]);
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
