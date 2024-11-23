<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Error;

class CoinModel extends Model
{
    protected $table = 'coin_info';
    public $timestamps = false;

    public function loadRefsData() {
        $cru = CoinRefUrlList::query()->where(['urlPTitle' => $this->urlPTitle])->first();
        if ($cru === null){
            //no ref urls, set to empty
            $cru = (object)['b'=>'','ex'=>'','bb'=>''];
        }
        $this->b = $cru->b;
        $this->ex = $cru->ex;
        $this->bb = $cru->bb;
    }

    /*

urlPTitle - название валюты в url страницы для парсинга
code - код валюты

title - читабельное название валюты
erUSD - обменный курс USD
erBTC - обменный курс BTC
erRUR - обменный курс RUR
erEUR - обменный курс EUR

cUSD - капитализация
oUSD - оборот(24ч)
vUSD - в обращении

     */
}
