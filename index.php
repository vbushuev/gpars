<?php
include("autoload.php");
use g\Log as Log;
use g\Common as Common;
use g\CTShirts\Catalog as Catalog;
use g\CTShirts\Product as Product;
//use g\Brandalley\Catalog as Catalog;
//use g\Brandalley\Product as Product;
//use g\Brandalley\Matcher as Matcher;
//$u = "http://www.ctshirts.com/uk/slim-fit-button-down-collar-non-iron-business-casual-white-shirt/FOB0188WHT.html#cgid=shirts-business-casual-shirts&start=1";
//$pl = new g\ProductGetter($u);
$c = new Catalog;
$pp = new Product;
$cc = $c->get();
Log::$console=false;
Log::debug($cc);
Log::$console=true;
print_r($cc);
exit;
foreach($cc as $k=>$d){
    foreach($d["l"] as $kk=>$dd){
        foreach ($dd["l"] as $p) {
            $fp = "logs/data/CTS".preg_replace("/\.html/",".json",basename($p));
            Log::debug($fp);
            if(!file_exists($fp)){
                $r = $pp->get($p,[$k,$kk]);
            }
            //Log::debug(Common::json($r));
            //Log::debug($pp->store());
            //exit();
        }
    }
}
/*
$i = -10;
if ($handle = opendir('logs/data')) {
    while (false !== ($entry = readdir($handle))) {
        if(preg_match("/^\.{1,2}$/",$entry))continue;
        //if(file_exists("logs/data.parsed/".$entry))continue;
        Log::debug($entry);
        $data = file_get_contents('logs/data/'.$entry);

        //$data = preg_replace('/\\/\\//im',"/",$data);
        $r = json_decode($data,true);
        $pm = new CTSMatcher($r);
        $pm->store();
        if(--$i==0)break;
    }
    closedir($handle);
}
*/
?>
