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
            if(in_array("Shirts",$r["categories"])){
                if(preg_match("/(classic fit|slim fit|extra slim fit)\s+(.+?)\sshirt/i",$r["title"],$ms)){
                    $out[$ms[1]]= $r["product_url"];
                    $tail = $ms[2];
                    if(preg_match("/((\S+)\s+collar)\s*(.+)/i",$tail,$ms2)){
                        $out[$ms2[1]]= $r["product_url"];
                        $tail = $ms2[2];
                    }
                    if(preg_match("/(non\-iron)\s+(.+)/i",$tail,$ms2)){
                        $out[$ms2[1]]= $r["product_url"];
                        $tail = $ms2[2];
                    }
                    if(preg_match("/((.+?)\s+cotton)\s+(.+)/i",$tail,$ms2)){
                        $out[$ms2[1]]= $r["product_url"];
                        $tail = $ms2[2];
                    }
                    $out[$tail]= $r["product_url"];
                }
            }
            if(in_array("Suits",$r["categories"])){
                if(preg_match("/^(.+?)\s+(classic fit|slim fit|extra slim fit)\s+(.+?)\s(\S*\ssuit\s.*?)$/i",$r["title"],$ms)){
                    $out[$ms[1]]= $r["product_url"];
                    $out[$ms[2]]= $r["product_url"];
                    $out[$ms[3]]= $r["product_url"];
                    if(!preg_match("/[\r\n]+/i",$ms[4]))
                        $out[$ms[4]]= $r["product_url"];
                }
            }
            if(in_array("Ties",$r["categories"])){
                //echo $r["title"]."\n";
                //Navy and pink silk block stripe classic tie
                if(preg_match("/(\S+)\sand\s(\S+)\s(\S+)\s(.+?)\stie/i",$r["title"],$ms)){
                //if(preg_match("/(\S+)\sand(\S+)/i",$r["title"],$ms)){
                    $out[$ms[1]." and ".$ms[2]]= $r["product_url"];
                    $out[$ms[3]]= $r["product_url"];
                    $out[$ms[4]]= $r["product_url"];

                }
            }
        }
        closedir($handle);
    }
}
foreach ($out as $key => $value) {
    echo '0,"'.trim($key).'","","'.$value.'"'."\n";
}

?>
