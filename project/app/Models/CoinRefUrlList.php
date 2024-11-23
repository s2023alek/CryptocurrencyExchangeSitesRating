<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Error;

class CoinRefUrlList extends Model
{
    protected $table = 'coin_ref_url_list';
    public $timestamps = false;

    public function s($urlPTitle, $b,$ex,$bb) {
        $this->query()->where('urlPTitle', '=', $urlPTitle)->delete();

        /*$this->query()->where('urlPTitle', '=', $urlPTitle)->update([
            'b' => $b,
            'ex' => $ex,
            'bb' => $bb
        ]);*/


        $this->urlPTitle = $urlPTitle;

        $this->b = $b;
        $this->ex = $ex;
        $this->bb = $bb;

        $this->save();

    }


    /*

urlPTitle - название биржи в url страницы для парсинга
        b url кнопка купить
        ex url кнопка обменять
        bb url кнопка поставить

     */
}
