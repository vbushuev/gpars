<?php
include("autoload.php");
use g\Log as Log;
use g\Common as Common;
use g\DBConnector as DB;
use g\CTShirts\Matcher as CTSMatcher;
$db = new DB();
$dirs = ["logs/data","logs/data.1"];
try{
    $ops = $db->selectAll("select * from g_product where status  in  (0)");
    foreach ($ops as $op) {
        $data="{}";
        foreach ($dirs as $dir) {
            if(file_exists($dir.'/'.$op["sku"].".json")){
                $data = file_get_contents($dir.'/'.$op["sku"].".json");
                break;
            }
        }
        //$data = preg_replace('/\\/\\//im',"/",$data);
        if($data=="{}")continue;
        $r = json_decode($data,true);
        $r = array_merge($op,$r);
        //Log::debug($r);exit;
        $pm = new CTSMatcher($r);
        $pm->store();
        $pm->storewoo();
    }
}
catch(\Exception $e){
    Log::debug("Error ".$e->getMessage());
}
?>
