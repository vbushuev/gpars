<?php
if ($handle = opendir('logs/data')) {
    while (false !== ($entry = readdir($handle))) {
        if(preg_match("/^\.{1,2}$/",$entry))continue;

        $data = file_get_contents('logs/data/'.$entry);
        $data = preg_replace('/\\/\\//im',"/",$data);
        if(preg_match('/<h3(.+?)\<\\\\.{1}h3\>/im',$data,$m)){
            echo $m[0]."\n";
            $data = preg_replace('/<h3(.+?)\<\\\\.{1}h3\>/im',"",$data);
        }

        file_put_contents('logs/data/'.$entry,$data);
    }
    closedir($handle);
}

?>
