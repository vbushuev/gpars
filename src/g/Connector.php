<?php
namespace g;
class Connector extends Common{
        protected $results;
        protected $response;
        protected $cookies=[];
        protected $headers=[];
        protected $cookieFile ='';
        protected $config;
        public function __construct($a=[]){
            $this->config = new Common;
            $this->config->loadFromArray(count($a)?$a:[
                "method" => "GET",
                "proxy" => false
            ]);
        }
        public function fetch($url){
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
            if($this->config->proxy!==false){
                $curlOptions[CURLOPT_PROXY] = $this->config->proxy;
            }
            if($this->method == 'POST'){
                $curlOptions[CURLOPT_POST]=1;
                foreach($_POST as $n=>$v)
                    $postData .= ((strlen($postData)==0)?"":"&").$n."=".$v;
                $curlOptions[CURLOPT_POSTFIELDS]=$postData;
            }
            curl_setopt_array($curl, $curlOptions);
            $response = curl_exec($curl);
            //$this->response = curl_getinfo($curl);
            //$this->results = $this->stripHeaders($response,$this->response);
            curl_close($curl);
            //return $this->results;
            return $response;
        }
        };
?>
