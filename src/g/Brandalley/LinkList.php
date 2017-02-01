<?php
namespace g\Brandalley;
use \g\Common as Common;
class LinkList extends Common{
    protected $pageGet = 16;
    protected $connector=null;
    protected $options = [
        "url" => "https://www-v6.brandalley.fr",
        "code" => "BRA"
    ];
    public function __construct(){
        $this->connector = new \g\Connector();
    }
    public function get($u){
        $r = [];
        $s = $this->connector->fetch($u);
        $p = \phpQuery::newDocument($s);
        foreach($p->find("#container_loaded .article_card .image_more_info") as $e){
            $pe = pq($e);
            $t = Common::stripText($pe->parent()->find(".container_info_text .container_info_text_desc")->text());
            $l = Common::stripText($pe->attr("href"));
            //\g\Log::debug("\t\t".$t." [".$l."]");
            $r[] = $this->options["url"].$l;
        }
        $allProducts = $p->find("#container_listing > div.global_max_width > span > span.nb")->text();
        $pages = $this->pageGet;
        $pages = ($allProducts/60 < $pages)?ceil($allProducts/60):$pages;
        \g\Log::debug("Lets get {$pages} of ".ceil($allProducts/60)." pages");
        $ups = [];
        for($i=2;$i-2<$pages;++$i)$ups[] = $u."/I-Page{$i}_60";
        $ss = $this->connector->fetchMulti($ups);
        foreach($ss as $u=>$s){
            $p = \phpQuery::newDocument($s);
            foreach($p->find("#container_loaded .article_card .image_more_info") as $e){
                $pe = pq($e);
                $t = Common::stripText($pe->parent()->find(".container_info_text .container_info_text_desc")->text());
                $l = Common::stripText($pe->attr("href"));
                //\g\Log::debug("\t\t".$t." [".$l."]");
                $r[] = $this->options["url"].$l;
            }
        }
        return $r;
    }

};
?>
