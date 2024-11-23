<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Error;
use function Nette\Utils\first;

class PageMetaModel extends Model {
    protected $table = 'page_meta_with_vars';
    public $timestamps = false;


    /**
     * @param $page example: "home"
     * @param $metaValuesTitle vars in meta text content for "title"
     * @param $metaValuesDescription vars in meta text content for "description"
     * @example:
     * //data in db:
     * row #1: page:"Product", name:"title", text:"this is a page for a product %title% which costs %price% %currencyTitle% "
     * row #2: page:"Product", name:"description", text:"description of the product %title% page for %region% region"
     * //code:
     * $metaValuesTitle = (object) [
     * 'title'=>'some product title'
     * ,'price'=>number
     * ,'currencyTitle'=>'some currency title'
     * ];
     *
     * $metaValuesDescription = (object) [
     * 'title'=>'some product title'
     * ,'region'=>'some country name'
     * ];
     *
     * $pm = PageMeta::get("Product", $metaValuesTitle, $metaValuesDescription);
     */
    public static function get($page, $metaValuesTitle = null, $metaValuesDescription = null) {
        $pm = new PageMetaModel();

        $pm->titleText = PageMetaModel::query()->where(["page" => $page, "name" => "title"])->first()->getText();
        $pm->descriptionText = PageMetaModel::query()->where(["page" => $page, "name" => "description"])->first()->getText();

        if ($metaValuesTitle) {
            $pm->titleText = PageMetaModel::processPageMetaUnitText($pm->titleText, $metaValuesTitle);
        }

        if ($metaValuesDescription) {
            $pm->descriptionText = PageMetaModel::processPageMetaUnitText($pm->descriptionText, $metaValuesDescription);
        }

        return $pm;

    }

    public static function s($page, $name, $text) {
        PageMetaModel::query()->where('page', '=', $page)->where('name', '=', $name)->delete();

        $pm = new PageMetaModel();

        $pm->page = $page;
        $pm->name = $name;
        $pm->text = $text;

        $pm->save();
    }

    /**
     * @param $metaValues vars in meta text content
     * @example:
     * $this->text = "this is a page for a product %title% which costs %price% %currencyTitle% "
     *
     * $metaValues: (object) [
     * 'title'=>'some product title'
     * ,'price'=>number
     * ,'currencyTitle'=>'some currency title'
     * ];
     */
    private static function processPageMetaUnitText($text, $metaValues) {
        foreach ($metaValues as $metaItemName => $metaItemValue) {
            $text = implode($metaItemValue, explode("%".$metaItemName."%", $text));
        }

        return $text;
    }

    private function getText() {
        return $this->text;
    }

    public function getTitle() {
        return $this->titleText;
    }

    public function getDescription() {
        return $this->descriptionText;
    }

    private $titleText;
    private $descriptionText;


    /*
    page
    name
    text
    */

}
