<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Error;

class ACoinModel extends Model
{
    protected $table = 'coins';
    public $timestamps = false;

}
/*

urlPTitle - название валюты в url страницы для парсинга

title - читабельное название валюты

imageUrl - путь к картинке
*/
