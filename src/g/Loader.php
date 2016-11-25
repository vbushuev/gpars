<?php
namespace g;
class Loader extends Common{
    public function __construct($a = []){
        $this->loadFromArray(count($a)?$a:[
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
        $p->description = preg_replace("/[\r\n]+/","",pq("#wrapper > div:nth-child(5) > div.pdp-main__slot.js-accordion > div.pdp-main__slot.pdp-main__slot--shadowed.pdp-main__slot--full > div.pdp-main__slot.pdp-main__slot--left-group.pdp-main__slot--border-right > div.pdp-main__slot--outlined.pdp-main__slot--outlined-blue.js-slot-accordion.pdp-main__slot--accordion")->html());
        $p->product_img = $r->find("#pdpMain")->find("img.pdp-main__image")->attr("src");
        $p->sku = $this->_properties["code"].preg_replace("/[\r\n]+/","",$r->find("#pdpMain")->find(".pdp-main__number span[itemprop='productID']:first")->text());
        $p->type = "external";
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
            $o=[];
            foreach($r->find("#pdpMain")->find("ul.swatches.size.attribute.attribute__variants-swatches > li:not(.unselectable)") as $e){//sized
                $v = preg_replace("/[\n\r]*/","",pq($e)->find("div")->text());
                $p_variations[] = [
                    "sku" => $p->sku.$v,
                    "regular_price" =>$p->regular_price
                ];
                $o[] = $v;
            }
            $p_attributes[] = [
                "name" =>"Размер",
                "position" => count($p_attributes),
                "visible" => true,
                "variation" =>true,
                "options" => $o
            ];
        }
        if(count($r->find(".swatches.width.attribute.attribute__variants-swatches"))){
            $o = [];
            foreach($r->find("#pdpMain")->find("ul.swatches.width.attribute.attribute__variants-swatches > li:not(.unselectable)") as $e){//sized
                $v = preg_replace("/[\n\r]*/","",pq($e)->find("div")->text());
                $o[] = $v;
                $p_variations[] = [
                    "sku" => $p->sku.$v,
                    "regular_price" =>$p->regular_price
                ];
            }
            $p_attributes[] = [
                "name" =>"Размер воротника",
                "position" => count($p_attributes),
                "visible" => true,
                "variation" =>true,
                "options" => $o
            ];
        }
        if(count($r->find(".swatches.length.attribute.attribute__variants-swatches"))){
            $o=[];
            foreach($r->find("#pdpMain")->find("ul.swatches.length.attribute.attribute__variants-swatches > li:not(.unselectable)") as $e){//sized
                $v = preg_replace("/[\n\r\D]*/","",pq($e)->find("div")->text());
                $p_variations[] = [
                    "sku" => $p->sku.$v,
                    "regular_price" =>$p->regular_price
                ];
                $o[] = $v;
            }
            $p_attributes[] = [
                "name" =>"Длина рукава",
                "position" => count($p_attributes),
                "visible" => true,
                "variation" =>true,
                "options" => []
            ];
        }
        if(count($r->find(".swatches.cufftype.attribute.attribute__variants-swatches"))){
            $o=[];
            foreach($r->find("#pdpMain")->find("ul.swatches.cufftype.attribute.attribute__variants-swatches > li:not(.unselectable)") as $e){//sized
                $v = preg_replace("/[\n\r]*/","",pq($e)->find("div")->text());
                $p_variations[] = [
                    "sku" => $p->sku.$v,
                    "regular_price" =>$p->regular_price
                ];
                $o[] = $v;
            }
            $p_attributes[] = [
                "name" =>"Тип манжета",
                "position" => count($p_attributes),
                "visible" => true,
                "variation" =>true,
                "options" => $o
            ];
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
        //print(json_encode($p_attributes,JSON_PRETTY_PRINT,JSON_UNESCAPED_UNICODE));
        //$p->images = array_merge($p->images,$p_images);
        $p->loadFromArray([
            "images" => $p_images,
            "attributes" => $p_attributes,
            "variations" => $p_variations,
            "categories" => $p_categories
        ]);
        return $p;
    }
};
?>
