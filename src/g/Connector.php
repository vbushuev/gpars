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
            CURLOPT_HTTPHEADER=>[
                'Cookie: CTCountry=gb; GlobalE_Data='.urlencode('{"countryISO":"gb","cultureCode":"ru","currencyCode":"GBP","apiVersion":"2.1.4","clientSettings":"{"AllowClientTracking":{"Value":"true"},"FullClientTracking":{"Value":"true"},"IsMonitoringMerchant":{"Value":"true"},"IsV2Checkout":{"Value":"true"}}"}')
            ],
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

        //$method = $_SERVER['REQUEST_METHOD'];
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
    public function fetchMulti($urls,$m="GET",$d=""){
        if(!is_array($urls))$urls = [$urls];
        $response=[];
        $curls = [];
        $mh = curl_multi_init();
        foreach($urls as $url){
            $curls[$url] = curl_init();
            //$host = parse_url($url);
            $curlOptions = [
                CURLOPT_URL => $url,
                CURLOPT_HTTPHEADER=>['Cookie: CTCountry=gb; GlobalE_Data='.urlencode('{"countryISO":"gb","cultureCode":"ru","currencyCode":"GBP","apiVersion":"2.1.4","clientSettings":"{"AllowClientTracking":{"Value":"true"},"FullClientTracking":{"Value":"true"},"IsMonitoringMerchant":{"Value":"true"},"IsV2Checkout":{"Value":"true"}}"}')],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => 1,
                CURLOPT_MAXREDIRS =>20, // останавливаться после 10-ого редиректа
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_ENCODING => ""
            ];
            if($this->config["proxy"]!==false)$curlOptions[CURLOPT_PROXY] = $this->config["proxy"];
            if($m == 'POST'){
                $curlOptions[CURLOPT_POST]=1;
                $curlOptions[CURLOPT_POSTFIELDS]=$d;
            }
            curl_setopt_array($curls[$url], $curlOptions);
            curl_multi_add_handle($mh,$curls[$url]);
        }
        do{
            curl_multi_exec($mh, $running);
            curl_multi_select($mh);
        } while ($running > 0);

        // Obtendo dados de todas as consultas e retirando da fila
        foreach($curls as $url=>$curl){
            $response[$url]=curl_multi_getcontent($curl);
            curl_multi_remove_handle($mh, $curl);
        }
        curl_multi_close($mh);
        return $response;
    }
};
?>
