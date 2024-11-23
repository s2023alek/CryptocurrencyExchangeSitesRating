<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Error;

class ExchangeListModel extends Model
{
    protected $table = 'exchange_list';
    public $timestamps = false;

    public function s($urlPTitle, $fields) {

        array_shift($fields);

        $this->urlPTitle = $urlPTitle;

        $this->u = array_shift($fields);
        $this->imageUrl = array_shift($fields);
        $this->ct = array_shift($fields);
        $this->pt = array_shift($fields);
        $this->s = array_shift($fields);
        $this->p = array_shift($fields);
        $this->o = array_shift($fields);
        $this->d = array_shift($fields);
        $this->t = array_shift($fields);

        $this->save();
    }


    /*

urlPTitle - название биржи в url страницы для парсинга

u - url биржи
ct - название валюты(Биржа)
pt - Пара
s - Ставка
p - Цена USD
o - Оборот (24ч)
d - 24ч %
t - Сделки

     */
}
