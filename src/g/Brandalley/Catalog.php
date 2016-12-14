<?php
namespace g\Brandalley;
use \g\Common as Common;
class Catalog extends Common{
    protected $connector=null;
    protected $linker=null;
    protected $catalog = [];
    protected $options = [
        "url" => "https://www-v6.brandalley.fr",
        "code" => "BRA"
    ];
    public function __construct(){
        $this->connector = new \g\Connector();
        $this->linker = new \g\Brandalley\LinkList();
    }
    public function get($c=false){
        if(count($this->catalog))return $this->catalog;
        $i = 0;
        $r = [];
        $s = $this->connector->fetch($this->options["url"]);
        $p = \phpQuery::newDocument($s);
        foreach($p->find("#ba-mainmenu > ul > .elems_menu > a") as $e){
            $pe = pq($e);
            $t = Common::stripText($pe->text());
            $l = $this->options["url"].Common::stripText($pe->attr("href"));
            if(preg_match("/maison/i",$t)) break;
            //\g\Log::debug($t);
            if(strlen($t)){
                $r[$t] = ["u"=>$l,"l"=>[]];
                //\g\Log::debug($pe->parent()->find("div > div > div > div.menu_level_3 > div > ul > li > a") );
                //\g\Log::debug($pe->parent()->find(".menu_level_3 ul li a")->text() );
                //foreach($pe->parent()->children(".dropdown-menu .menu-body .menu-container .menu_level_3 > div > ul > li > a") as $e2){

                foreach($pe->parent()->find(".menu_level_3 ul li a") as $e2){
                    #menu_femme > div > ul > li.col-xs-12.tab.tab-news.noselection
                    $pe2 = pq($e2);
                    $t2 = Common::stripText($pe2->text());
                    \g\Log::debug($t." -> ".$t2);
                    $l2 = $this->options["url"].Common::stripText($pe2->attr("href"));
                    //if(preg_match("/view all/i",$t2))continue;
                    //if(strlen($t2))$r[$t]["l"][$t2]=["u"=>$l2,"l"=>$this->linker->get($l2)];
                    if(strlen($t2))$r[$t]["l"][$t2]=["u"=>$l2,"l"=>[]];
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
