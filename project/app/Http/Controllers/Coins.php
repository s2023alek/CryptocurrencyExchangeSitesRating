<?php

namespace App\Http\Controllers;

use App\Models\ACoinModel;
use App\Models\AnExchangeModel;
use App\Models\CoinRefUrlList;
use App\Models\ExchangeListModel;
use App\Models\PageMetaModel;
use App\Models\PageViewsCount;
use App\Parsers\CoinParser;
use App\Parsers\GenericParser;
use App\settings\SiteSettings;
use Illuminate\Http\Request;
use \App\Models\CoinModel;
use PhpParser\Error;

class Coins extends Controller {

    public function index() {
    }


    public function show(Request $request) {

        $cil = ACoinModel::query()->get();

        $pm = PageMetaModel::get("coins");

        return view('coins', [
            'pageMeta' => $pm,
            'coinInfoList' => $cil
        ]);

    }

}
