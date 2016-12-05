<?php
include("autoload.php");
use g\Log as Log;
use g\CTShirts\Matcher as CTSMatcher;
echo "Добавлено,Original,Translated\n";
$dirs = ['logs/data','logs/data.2'];
$out = [];
foreach ($dirs as $dir) {
    if ($handle = opendir($dir)) {
        while (false !== ($entry = readdir($handle))) {
            if(preg_match("/^\.{1,2}$/",$entry))continue;
            $data = file_get_contents($dir.'/'.$entry);
            $r = json_decode($data,true);
            if(preg_match_all("/\<li(.+?)\>(.+?)\<\/li\>/i",$r["description"],$ms)){
                foreach($ms[2] as $f){
                    $f = preg_replace("/[\d\%\-]+/im","",$f);
                    $ff = preg_split("/\//",$f);
                    if(is_array($ff)){
                        foreach ($ff as $fp) {
                            $out[$fp] = 0;
                        }
                    }else $out[$f] = 0;
                }
            }
        }
        closedir($handle);
    }
}
foreach ($out as $key => $value) {
    echo $value.',"'.trim($key).'",""'."\n";
}

?>
