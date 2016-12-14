<?php
include("autoload.php");
include("logs/brandalley.categories.php");
use g\Log as Log;
use g\Common as Common;
use g\Brandalley\Product as Product;
$linker = new \g\Brandalley\LinkList();
$product = new Product();
$tick = time();
echo "Start in ".date("H:i:s")."\n";
//foreach ($cats as $c => $v) {
    $c = "HOMME";
    //$c = "ENFANT";
    $v = $cats[$c];
    foreach ($v[l] as $cc => $vv) {
        if(in_array($cc,$need)){
            $pp = $linker->get($vv["u"]);
            $c = preg_replace("/\'+/","",$c);
            $cc = preg_replace("/\'+/","",$cc);
            echo $c." -> ".$cc." : products = ".count($pp)."\n";
            foreach ($pp as $p) {
                $product->get($p,[$c,$cc]);
            }
        }
    }
//}
echo "End in  ".date("H:i:s")."\n";
echo (time()-$tick)." seconds\n";
//Log::debug(join("\n",$sql));
//print(join("\n",$sql));
?>
