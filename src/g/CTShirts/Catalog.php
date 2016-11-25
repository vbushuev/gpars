<?php
namespace g\CTShirts;
use \g\Common as Common;
class Catalog extends Common{
    protected $connector=null;
    protected $linker=null;
    protected $catalog = [];
    protected $options = [
        "url" => "http://ctshirts.com",
        "code" => "CTS"
    ];
    public function __construct(){
        $this->connector = new \g\Connector();
        $this->linker = new \g\CTShirts\LinkList();
    }
    public function get($c=false){
        if(count($this->catalog))return $this->catalog;
        $i = 0;
        $r = [];
        $s = $this->connector->fetch($this->options["url"]);
        $p = \phpQuery::newDocument($s);
        foreach($p->find("#navigation-li-container > li") as $e){
            $pe = pq($e);
            $t = Common::stripText($pe->find(".navigation__li-link--has-sub")->text());
            $l = Common::stripText($pe->find(".navigation__li-link--has-sub")->attr("data-link"));
            \g\Log::debug($t);
            if(strlen($t)){
                $r[$t] = ["u"=>$l,"l"=>[]];
                foreach($pe->find(".level-2 > .navigation__level-container a.navigation__li-link") as $e2){
                    $pe2 = pq($e2);
                    $t2 = Common::stripText($pe2->text());
                    $l2 = Common::stripText($pe2->attr("href"));
                    if(strlen($t2))$r[$t]["l"][$t2]=["u"=>$l2,"l"=>$this->linker->get($l2)];
                }
            }
            $i++;
            if($c!==false) {if( $c>0 && $c<$i)break;}
        }
        $this->catalog = $r;
        return $r;
    }

};
?>
