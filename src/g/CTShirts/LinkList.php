<?php
namespace g\CTShirts;
use \g\Common as Common;
class LinkList extends Common{
    protected $connector=null;
    protected $options = [
        "url" => "http://ctshirts.com/uk/home",
        "code" => "CTS"
    ];
    public function __construct(){
        $this->connector = new \g\Connector();
    }
    public function get($u){
        $r = [];
        $s = $this->connector->fetch($u."?sz=1000&start=0");
        $p = \phpQuery::newDocument($s);
        foreach($p->find(".product-image.tile__image a.thumb-link ") as $e){
            $pe = pq($e);
            $t = Common::stripText($pe->attr("title"));
            $l = Common::stripText($pe->attr("href"));
            if(strlen($t)){
                $r[] = $l;
                //$r[$t] = ["u"=>$l,"l"=>$this->];
            }
        }
        return $r;
    }

};
?>
