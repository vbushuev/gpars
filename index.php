<?php
include("autoload.php");

$u = "http://www.ctshirts.com/uk/slim-fit-button-down-collar-non-iron-business-casual-white-shirt/FOB0188WHT.html#cgid=shirts-business-casual-shirts&start=1";
$h = new g\Connector;
$f = new g\Filter;
$l = new g\Loader;

$r = $h->fetch($u);
$r = $f->filter($r);
$d = $l->loader($r);

$s = "logs/pages/".preg_replace("/[\?\#].+/i","",basename($u));
file_put_contents($s,$r);
?>
