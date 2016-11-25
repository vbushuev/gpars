<?php
namespace g;
class ProductGetter extends Common{
    protected $db;
    public function __construct($u){
        $h = new Connector;
        $f = new Filter;
        $l = new Loader;
        print($l->__toString());
        $this->db = new DBConnector;
        $r = $h->fetch($u);
        $r = $f->filter($r);
        $s = "logs/pages/".preg_replace("/[\?\#].+/i","",basename($u));
        file_put_contents($s,$r);
        $d = $l->loader($r);
        $d->product_url = $u;
        $d->external_url = $u;
        $this->store($d);
    }
    public function store($p){
        file_put_contents("logs/data/".$p->sku.".json",preg_replace('/\\//im',"//",$p));//->__toString());
        try{
            $o = $this->get($p->sku);
            Log::debug("need update");
        }
        catch(Exception $e){
            //Log::debug($e->getMessage());
            try{
                $p->original_price =preg_replace("/[^\d\.]+/","",$p->original_price);
                $s = "INSERT INTO g_product(`rawdata`,`shop`,`title`,`description`,`original_price`,`currency`,`sku`,`url`) ";
                $s.= "VALUES('".$p."','".$p->shop."','".$p->title."','".$p->description."','".$p->original_price."','".$p->currency."','".$p->sku."','".$p->product_url."')";
                $id = $this->db->insert($s);
                Log::debug("new product id: ".$id);
            }
            catch(Exception $e){
                Log::debug($e->getMessage());
            }
        }

    }
    protected function get($sku){
        $s = "select rawdata from g_product where sku ='".$sku."'";
        $r = $this->db->select($s);
        $p = new Product;
        \var_dump(\json_decode($r["rawdata"]));
        $p->loadFromJson($r["rawdata"]);
        return $p;
    }
};
?>
