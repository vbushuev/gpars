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
    //get products
    //$ops = $db->selectAll("select * from g_product where status in ('new')");
    $ops = $db->selectAll("select * from g_product where shop='brandalley' and status='categories'");
    foreach ($ops as $op) {
        $g_t = preg_replace('/[\\\']/im', '\\\'',$tr->translate($op["title"]));
        $g_d = preg_replace('/[\\\']/im', '\\\'',$tr->translate($op["description"]));
        $g_d = preg_replace('/\<\!\-\-(.+?)\-\->/im', "",$g_d);
        $g_d = preg_replace('/[\r\n]+/im', "",$g_d);
        $g_d = preg_replace('/référence:\s*\d+/im', "",$g_d);
        $g_d = preg_replace('/caractéristiques|paiement sécurisé/im', "",$g_d);
        $g_d = preg_replace('/\<(.+?)\>\s*([^\<]+)\<(.+?)\>/im', "\n$2\n",$g_d);
        $g_d = preg_replace('/\<(.+?)\>/im', "",$g_d);
        $g_d = preg_replace('/\<([^\>]+)$/im', "",$g_d);
        $g_d = preg_replace('/\s*\n\s*\n/im', "",$g_d);
        //$g_d = trim(preg_replace('/\<(.+?)\>([^\<]+)\<(.+?)\>/im', "<li>$2</li>",$g_d));
        $g_d = preg_replace('/^\s*(.+)\s*$/im', "<li>$1</li>",$g_d);
        $g_d = "<ul>".$g_d."</ul>";
        $db->update("update g_product set g_title='".$g_t."',g_description='".$g_d."',g_sku='".$op["sku"]."',status='translated' where id=".$op["id"]);
    }
}
catch(\Exception $e){
    Log::debug("Error ".$e->getMessage());
}
echo "End in  ".date("H:i:s")."\n";
echo (time()-$tick)." seconds\n";
?>
