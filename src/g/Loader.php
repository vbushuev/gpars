<?php
namespace g;
class Loader extends Common{
    protected $config;
    public function __construct($a = []){
        $this->config = new Common;
        $this->config->loadFromArray(count($a)?$a:[
            "code" => "CTS"
        ]);
    }
    public function loader($s){
        $r = \phpQuery::newDocument($s);
        $p = new Product;
        /*
        original_price:f.j.find(".price.price__display.regular").text().trim().replace(/[^\d\.\,]/,""),
        regular_price:f.j.find(".price.price__display.regular").text().trim().replace(/[^\d\.\,]/,""),
        title:tr.a(f.j.find(".product-name.pdp-main__name").text().trim()),
        description:tr.a(htmlEscape($("#wrapper > div:nth-child(5) > div.pdp-main__slot.js-accordion > div.pdp-main__slot.pdp-main__slot--shadowed.pdp-main__slot--full > div.pdp-main__slot.pdp-main__slot--left-group.pdp-main__slot--border-right > div.pdp-main__slot--outlined.pdp-main__slot--outlined-blue.js-slot-accordion.pdp-main__slot--accordion").html())),
        //description:tr.a(htmlEscape($("#wrapper > div:nth-child(5) > div.pdp-main__slot.js-accordion > div.pdp-main__slot.pdp-main__slot--shadowed.pdp-main__slot--full > div.pdp-main__slot.pdp-main__slot--left-group.pdp-main__slot--border-right > div.pdp-main__slot--outlined.pdp-main__slot--outlined-blue.js-slot-accordion.pdp-main__slot--accordion").html())),
        //description:tr.a(encodeURI($("#wrapper > div:nth-child(5) > div.pdp-main__slot.js-accordion > div.pdp-main__slot.pdp-main__slot--shadowed.pdp-main__slot--full > div.pdp-main__slot.pdp-main__slot--left-group.pdp-main__slot--border-right > div.pdp-main__slot--outlined.pdp-main__slot--outlined-blue.js-slot-accordion.pdp-main__slot--accordion").html())),
        product_img:f.j.find("img.pdp-main__image").attr("src"),
        images:[],
        product_url:document.location.href.replace(/xray\.bs2/,"gauzymall.com"),
        external_url:document.location.href.replace(/xray\.bs2/,"gauzymall.com"),
        sku:f.c+f.j.find(".pdp-main__number span[itemprop='productID']:first").text().trim(),
        */
        $p->original_price = $r->find("#pdpMain")->find(".price.price__display.regular")->text();
        $p->regular_price = $r->find("#pdpMain")->find(".price.price__display.regular")->text();
        $p->title = $r->find("#pdpMain")->find(".product-name.pdp-main__name")->text();
        $p->description = pq("#wrapper > div:nth-child(5) > div.pdp-main__slot.js-accordion > div.pdp-main__slot.pdp-main__slot--shadowed.pdp-main__slot--full > div.pdp-main__slot.pdp-main__slot--left-group.pdp-main__slot--border-right > div.pdp-main__slot--outlined.pdp-main__slot--outlined-blue.js-slot-accordion.pdp-main__slot--accordion")->html();
        $p->product_img = $r->find("#pdpMain")->find("img.pdp-main__image")->attr("src");
        $p->sku = $this->config->code.$r->find("#pdpMain")->find(".pdp-main__number span[itemprop='productID']:first")->text();
        $p->type = "variable";
        //$p->images = [];
        //$p->categories = [];
        //$p->variations = [];
        //$p->attributes = [];
        //$p->status = "";

        $p_images = [];
        $p_attributes=[];
        $p_variations=[];
        $p_categories=[];
        foreach($r->find("#pdpMain")->find("img.pdp-main__image") as $img){
            $p_images[]=[
                "src"=>preg_replace("/\?.*$/","",$img->getAttribute("src")),
                "position"=>count($p_images)
            ];
        }

        if(count($r->find(".swatches.size.attribute.attribute__variants-swatches"))){
            $p_attributes[] = [
                "name" =>"Размер",
                "position" => count($p_attributes),
                "visible" => true,
                "variation" =>true,
                "options" => []
            ];
            foreach($r->find("#pdpMain")->find("ul.swatches.size.attribute.attribute__variants-swatches > li:not(.unselectable)") as $e){//sized
                $v = $e.find("div")->text();
                $p_variations[] = [
                    "sku" => $p->sku.$v,
                    "regular_price" =>$p->regular_price
                ];
                $p_attributes[count($p_attributes)]["options"][]= $v;
            }
        }
        /*
        for(i in utag_data.product_category){
            var re = new RegExp(f.f)
            var c = this.cat.get(utag_data.product_category[i].replace(re,""));
            if(c==false) console.warn("No category {"+utag_data.product_category[i]+"} matching!!!");
            else p.categories = p.categories.concat(c);
        }

        if($(".swatches.width.attribute.attribute__variants-swatches").length){
            p.attributes.push({
                name:"Размер воротника",
                slug:"collar",
                position:ai,
                visible:true,
                variation:true,
                options:[]
            });
            $("ul.swatches.width.attribute.attribute__variants-swatches > li:not(.unselectable)").each(function(t){//sized
                var $t = $(this),v = $t.find("div").text().trim();
                p.variations.push({
                    sku:p.sku+v,
                    regular_price:p.regular_price
                });
                p.attributes[ai].options.push(v);
            });
            ai++;
        }
        if($(".swatches.length.attribute.attribute__variants-swatches").length){
            p.attributes.push({
                name:"Длинна рукава",
                slug:"sleeve",
                position:ai,
                visible:true,
                variation:true,
                options:[]
            });
            $("ul.swatches.length.attribute.attribute__variants-swatches > li:not(.unselectable)").each(function(t){//sized
                var $t = $(this),v = $t.find("div").text().trim().replace(/[\D\s]+/,"");
                p.variations.push({
                    sku:p.sku+v,
                    regular_price:p.regular_price
                });
                p.attributes[ai].options.push(v);
            });
            ai++;
        }
        if($(".swatches.cufftype.attribute.attribute__variants-swatches").length){
            p.attributes.push({
                name:"Тип манжета",
                slug:"cuff",
                position:ai,
                visible:true,
                variation:true,
                options:[]
            });
            $("ul.swatches.cufftype.attribute.attribute__variants-swatches > li:not(.unselectable)").each(function(t){//sized
                var $t = $(this),v = $t.find("div").text().trim();
                p.variations.push({
                    sku:p.sku+v,
                    regular_price:p.regular_price
                });
                p.attributes[ai].options.push(v);
            });
            ai++;
        }*/
        $p->images = $p_images;
        $p->attributes = $p_attributes;
        $p->variations = $p_variations;
        $p->categories = $p_categories;
        return $p;
    }
};
?>
