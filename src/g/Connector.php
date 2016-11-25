<?php
namespace g;
class Connector extends Common{
    protected $results;
    protected $response;
    protected $cookies=[];
    protected $headers=[];
    protected $cookieFile ='';
    protected $config = [
        "method" => "GET",
        "proxy" => false
    ];
    public function __construct($a=[]){
        $this->config = array_merge($this->config,$a);
    }
    public function fetch($url,$m="GET",$d=""){
        $curl = curl_init();
        $host = parse_url($url);
        $curlOptions = [
            CURLOPT_URL => $url,
            //CURLOPT_HTTPHEADER=>$headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_MAXREDIRS =>20, // останавливаться после 10-ого редиректа
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_ENCODING => "", // обрабатывает все кодировки
            //CURLOPT_HEADER => 1,
            //CURLOPT_VERBOSE => 1,
            //CURLOPT_STDERR => $verbose,
            //CURLINFO_HEADER_OUT => 1,
        ];

        $method = $_SERVER['REQUEST_METHOD'];
        if($this->config["proxy"]!==false){
            $curlOptions[CURLOPT_PROXY] = $this->config["proxy"];
        }
        if($m == 'POST'){
            $curlOptions[CURLOPT_POST]=1;
            $curlOptions[CURLOPT_POSTFIELDS]=$d;
        }
        curl_setopt_array($curl, $curlOptions);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
};
?>
