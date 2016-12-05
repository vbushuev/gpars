<?php
$s = file_get_contents("logs/ctshirts.json");
$j = json_decode($s,true);
$r = [];
foreach ($j as $k => $v) {
    $r[] = [$k,""];
    foreach ($v["l"] as $kk => $vv) {
        $r[]=[$k,$kk];
    }

}
foreach ($r as $key => $v) {
    echo $key.",".$v[0].",".$v[1]."\n";
}
?>
