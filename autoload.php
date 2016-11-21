<?php
function __autoload($className){
	$sourceDir = "/Applications/AMPPS/www/gpars/src";
	$vendorDir = "/Applications/AMPPS/www/gpars/vendor";
	$classmap = [
		"phpQuery" => $vendorDir."/phpquery/phpQuery/phpQuery.php"
	];
	if(isset($classmap[$className])){
		require_once $classmap[$className];
		return true;
	}
	$file = str_replace('\\','/',$className);
	require_once $sourceDir.'/'.$file.'.php';
	return true;
}
?>
