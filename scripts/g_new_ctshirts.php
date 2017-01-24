<?php
include("autoload.php");
include("logs/ctshirts.categories.php");
ini_set('max_execution_time', 60*240);
use g\Log as Log;
use g\Common as Common;
use g\CTShirts\Product as Product;
$product = new Product();
$tick = time();
echo "Start in ".date("H:i:s")."\n";
foreach ($cats as $c => $v) {
    $v = $cats[$c];
    foreach ($v["l"] as $cc => $vv) {
        if(in_array($cc,$need)){
            $c = preg_replace("/\'+/","",$c);
            $cc = preg_replace("/\'+/","",$cc);
            echo $c." -> ".$cc." : products = ".count($vv["l"])."\n";
            foreach ($vv["l"] as $p) {
                $product->get($p,[$c,$cc]);
            }
        }
    }
}
echo "End in  ".date("H:i:s")."\n";
echo (time()-$tick)." seconds\n";
//Log::debug(join("\n",$sql));
//print(join("\n",$sql));
?>
