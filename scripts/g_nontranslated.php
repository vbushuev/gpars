<?php
include("autoload.php");
use g\Log as Log;
use g\Common as Common;
use g\DBConnector as DB;
use g\CTShirts\Matcher as CTSMatcher;
$db = new DB();
$dirs = ["logs/data","logs/data.1"];
$tick = time();
$out = [];
try{
    //get products
    //$ops = $db->selectAll("select * from g_product where status in ('new')");
    $ops = $db->selectAll("select * from g_product");
    foreach ($ops as $op) {
        if(preg_match("/([a-z\-\:\'\s]+)/im",$op["g_title"],$ms)) if(strlen(trim($ms[1]))&&$ms[1]!="-")echo $out[strtolower(trim($ms[1]))]=0;
        //if(preg_match("/([a-z\-\:\']+)/im",$op["description"],$ms)) echo $ms[1]."\n";
    }
}
catch(\Exception $e){
    Log::debug("Error ".$e->getMessage());
}
echo join("\n",array_keys($out));
?>
