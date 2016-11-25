<?php
include("autoload.php");
use g\Log as Log;
use g\Common as Common;
use g\CTShirts\Catalog as CTSCatalog;
use g\CTShirts\Product as CTSProduct;
//$u = "http://www.ctshirts.com/uk/slim-fit-button-down-collar-non-iron-business-casual-white-shirt/FOB0188WHT.html#cgid=shirts-business-casual-shirts&start=1";
//$pl = new g\ProductGetter($u);
$c = new CTSCatalog;
$pp = new CTSProduct;
$cc = $c->get();
Log::$console=false;
Log::debug($cc);
Log::$console=true;
foreach($cc as $k=>$d){
    foreach($d["l"] as $kk=>$dd){
        foreach ($dd["l"] as $p) {
            $r = $pp->get($p,[$k,$kk]);
            Log::debug(Common::json($r));
            Log::debug($pp->store());
            //exit();
        }
    }
}
?>
