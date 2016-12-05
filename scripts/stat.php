<?php
include("autoload.php");
use g\Log as Log;
use g\CTShirts\Matcher as CTSMatcher;
echo "Добавлено в Woo,Артикул,Title,URL,Оригинальная категория,Наши категории\n";
if ($handle = opendir('logs/data')) {
    while (false !== ($entry = readdir($handle))) {
        $parsed=file_exists("logs/data.parsed/".$entry);
        if(preg_match("/^\.{1,2}$/",$entry))continue;
        //Log::debug($entry);
        $data = file_get_contents('logs/data/'.$entry);

        //$data = preg_replace('/\\/\\//im',"/",$data);
        $r = json_decode($data,true);
        $pm = new CTSMatcher($r);
        $pm->matcher();
        $p = $pm->toArray();
        //print_r($p);exit;
        $cats = [];
        foreach($p["categories"] as $pc){
            $cats[] = '['.$pc.'] '.$pm->getcategorybyid($pc)["name"];
        }
        if(!$parsed)
            echo ($parsed?"1":"0").','.(isset($r["sku"])?$r["sku"]:"").','.preg_replace("/[\r\n]+/"," ",$r["title"]).','.$r["product_url"].','.join('-',$r["categories"]).','.join(" | ",$cats)."\n";
    }
    closedir($handle);
}
?>
