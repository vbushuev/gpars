<?php
include("autoload.php");
use g\Log as Log;
use g\Common as Common;
use g\DBConnector as DB;
use g\Translator as Translator;
$db = new DB();
$tr = new Translator(["lang"=>"fr"]);
$tick = time();
echo "Start in ".date("H:i:s")."\n";
function tr($s,$dict,$type="text"){
    $r = $s;
    foreach ($dict as $di) {
        $r = preg_replace("/\b".preg_quote($di["original"])."\b/im",$di["translate"],$r);
        if(!preg_match("/[a-z]+/i",$r))break;
    }
    return $r;
}
try{
    //get dictionary
    $dict = $db->selectAll("select * from g_dictionary where lang='fr' order by priority desc");
    //get products
    //$ops = $db->selectAll("select * from g_product where status in ('new')");
    $ops = $db->selectAll("select * from g_product where status='categories'");
    foreach ($ops as $op) {
        $g_t = preg_replace('/[\\\']/im', '\\\'',$tr->translate($op["title"],$dict,"title"));
        $g_d = preg_replace('/[\\\']/im', '\\\'',$tr->translate($op["description"],$dict));

        $db->update("update g_product set g_title='".$g_t."',g_description='".$g_d."',g_sku='".$op["sku"]."',status='translated' where id=".$op["id"]);
    }
}
catch(\Exception $e){
    Log::debug("Error ".$e->getMessage());
}
echo "End in  ".date("H:i:s")."\n";
echo (time()-$tick)." seconds\n";
?>
