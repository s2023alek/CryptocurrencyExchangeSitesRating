<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Error;

class ExchangeModel extends Model
{
    protected $table = 'exchange_info';
    public $timestamps = false;

    /*

urlPTitle - название биржи в url страницы для парсинга

title - читабельное название биржи

oUSD - оборот(24ч)
np - количество криптовалютных пар
wsu - url website

     */
}
