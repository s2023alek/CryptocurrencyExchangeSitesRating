<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Error;

class AnExchangeModel extends Model
{
    protected $table = 'exchanges';
    public $timestamps = false;

}
/*

urlPTitle - название валюты в url страницы для парсинга

title - читабельное название валюты

imageUrl - путь к картинке
*/
