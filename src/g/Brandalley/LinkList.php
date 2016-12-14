<?php
namespace g\Brandalley;
use \g\Common as Common;
class LinkList extends Common{
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
            \g\Log::debug("\t\t".$t." [".$l."]");
            $r[] = $this->options["url"].$l;
        }
        return $r;
    }

};
?>
