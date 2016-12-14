<?php
include("autoload.php");
use g\Log as Log;
use g\Common as Common;
use g\DBConnector as DB;
use g\CTShirts\Matcher as CTSMatcher;
$db = new DB();
$dirs = ["logs/data.parsed"];
foreach ($dirs as $dir) {
    if ($handle = opendir($dir)) {
        while (false !== ($entry = readdir($handle))) {
            try{
                if(preg_match("/^\.{1,2}$/",$entry))continue;
                $data = file_get_contents($dir.'/'.$entry);
                $r = json_decode($data,true);
                $pm = new CTSMatcher($r);
                $pm->storewoo();
                $db->insert("update g_product set status=2 where sku = '".$r["sku"]."'");
            }
            catch(\Exception $e){
                Log::debug("Error ".$e->getMessage());
            }
        }
        closedir($handle);
    }
}
?>
