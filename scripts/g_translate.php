<?php
include("autoload.php");
use g\Log as Log;
use g\Common as Common;
use g\DBConnector as DB;
use g\Translator as Translator;
use g\CTShirts\Matcher as CTSMatcher;
$db = new DB();
$tr = new Translator(["lang"=>"en"]);
$dirs = ["logs/data","logs/data.1"];
$tick = time();
echo "Start in ".date("H:i:s")."\n";
function tr($s,$dict,$type="text"){
    $r = $s;
    foreach ($dict as $di) {
        $r = preg_replace("/\b".preg_quote($di["original"])."\b/im",$di["translate"],$r);
        if(!preg_match("/[a-z]+/i",$r))break;
    }
    if($type == "title"){
        foreach([
                'пиджак для делового костюма',
                'брюки для делового костюма',
                'пиджак для роскошного костюма',
                'брюки для роскошного костюма',
                'пиджак для дорожного костюма',
                'брюки для дорожного костюма',
                'плащ для дорожного костюма',
                'подарочная коробка носков',
                'пижамные брюки',
                'джемпер с воротником',
                'коробка носков',
                'плавательные шорты',
                'крем для обуви',
                'удлиненный джемпер',
                'костюмные брюки',
            ] as $fraze){
                if(preg_match("/".$fraze."/i",$r)){
                    $r = $fraze." ".preg_replace("/".$fraze."/i","",$r);
                    return $r;
                }
        }
        $fw = preg_split("/\s/",$r);
        if(is_array($fw)&&count($fw)>1){
            $a1 = [$fw[count($fw)-1]];
            array_pop($fw);
            $a = array_merge($a1,$fw);
            $r = join(" ", $a);
        }
    }
    //$r = preg_replace("/[a-z]+/im","",$r);
    return $r;
}
try{
    //get dictionary
    $dict = $db->selectAll("select * from g_dictionary where lang='en' order by priority desc");
    //get products
    //$ops = $db->selectAll("select * from g_product where status in ('new')");
    $ops = $db->selectAll("select * from g_product");
    foreach ($ops as $op) {
        $g_t = preg_replace('/[\\\']/im', '\\\'',tr($op["title"],$dict,"title"));
        $g_d = preg_replace('/[\\\']/im', '\\\'',tr($op["description"],$dict));

        $db->update("update g_product set g_title='".$g_t."',g_description='".$g_d."',g_sku='CTS".$op["sku"]."',status='translated' where id=".$op["id"]);
    }
}
catch(\Exception $e){
    Log::debug("Error ".$e->getMessage());
}
echo "End in  ".date("H:i:s")."\n";
echo (time()-$tick)." seconds\n";
?>
