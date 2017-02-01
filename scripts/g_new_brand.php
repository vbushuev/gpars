<?php
include("autoload.php");
include("logs/brandalley.categories.php");
ini_set('max_execution_time', 60*60*12);
use g\Log as Log;
use g\Common as Common;
use g\Brandalley\Product as Product;

$controlFileName = 'ba.done.json';
$needRestart = (count($argv)>1&&isset($argv[1])&&$argv[1]=="restart");
Log::debug(($needRestart)?"Restarting parsing...":"Continue parsing ...");
$done = (file_exists($controlFileName)&&(!$needRestart))?json_decode(file_get_contents($controlFileName),true):[];
$linker = new \g\Brandalley\LinkList();
$product = new Product();
$tick = time();
$totalCount = 0;
Log::debug("Start in ".date("H:i:s"));
foreach ($cats as $c => $v) {
    //$c = isset($argv[1])&&isset($cats[$argv[1]])?$argv[1]:"FEMME";//$c = "HOMME";$c = "ENFANT";
    $v = $cats[$c];
    $done[$c] = isset($done[$c])?$done[$c]:[];
    foreach ($v["l"] as $cc => $vv) {
        if(in_array($cc,$done[$c]))continue;
        if(!in_array($cc,$need))continue;
        $done[$c][]=$cc;
        Log::debug($c." -> ".$cc." ...");
        $pp = $linker->get($vv["u"]);
        $c = preg_replace("/\'+/","",$c);
        $cc = preg_replace("/\'+/","",$cc);
        $totalCount+=count($pp);
        $product->getMulti($pp,[$c,$cc]);
        Log::debug("Got products = ".count($pp));
        file_put_contents($controlFileName,json_encode($done,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
    }
}
Log::debug("getted {$totalCount} products");
Log::debug("End in  ".date("H:i:s"));
Log::debug((time()-$tick)." seconds");

//Log::debug(join("\n",$sql));
//print(join("\n",$sql));
?>
