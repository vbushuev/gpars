<?php
include("autoload.php");
include("logs/brandalley.categories.php");
ini_set('max_execution_time', 60*60*12);
use g\Log as Log;
use g\Common as Common;
use g\Brandalley\Product as Product;

$u = (count($argv)>1)?$argv:"https://www.brandalley.fr/product/2032921-jack-jones-t-shirt-imprime";

$product = new Product();
$tick = time();
Log::debug("Start in ".date("H:i:s"));
$p = $product->get($u,["Test","T-test"]);
print_r($p);
Log::debug("End in  ".date("H:i:s"));
Log::debug((time()-$tick)." seconds");

//Log::debug(join("\n",$sql));
//print(join("\n",$sql));
?>
