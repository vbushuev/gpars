<?php
$s = file_get_contents("categories.new.json");
$j = json_decode($s,true);
$r = [];
foreach ($j as $row) {
    $r[$row["id"]] = $row["name"].",".$row["parent"];
}

for($i=0;$i<200;++$i){
    $str = $r[$i];
    if(strlen($str))echo $i.",".$str."\n";
}
exit;
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
