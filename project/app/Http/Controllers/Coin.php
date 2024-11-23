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

class Coin extends Controller {

    public function index() {
    }


    public function show(Request $request) {

        /*/get list from DB: coin ID => coin urlPTitle
        $r = AnExchangeModel::query()->get()->toArray();$ra = [];
        foreach ($r as $a) {array_push($ra, (object)['i'=>$a['id'], 'u'=>$a['urlPTitle']]);}
        uasort($ra, function($a, $b){return $a->i > $b->i;});echo json_encode($ra);exit;*/


        $cun = $request->coinUrlName;

        $cbs = ACoinModel::query()->where(['urlPTitle' => $cun])->first();
        $ci = CoinModel::query()->where(['coin_info.urlPTitle' => $cun])->leftJoin('coin_ref_url_list', 'coin_info.urlPTitle', '=', 'coin_ref_url_list.urlPTitle')->first();
        $eps = null;

        $expired = true;

        if ($ci !== null) {
            $cet = SiteSettings::getSetting(SiteSettings::IDSN_CACHE_EXPIRY_TIME_COINS);
            $dt = new \DateTime(date_format(date_create($ci->created), 'Y-m-d H:i:s'));
            $dt->add(new \DateInterval("PT" . $cet . "M"));
            $dtNow = new \DateTime();
            /*
                            echo $dt->format('Y-m-d H:i:s').'<br>';
                            echo $dtNow->format('Y-m-d H:i:s').'<br>';
                            echo ($dtNow>$dt)?'more':'less'.'<br>';
                            die();*/

            if ($dtNow < $dt) {
                $expired = false;
                $eps = ExchangeListModel::query()->where(['urlPTitle' => $cun])->get();
            }

        }


        if ($expired || $ci === null) {
            //parse coin data(if exist)
            $cp = new CoinParser('https://rucoinmarketcap.com/coin/' . $cun, $cun);

            if ($cp->getResultCode() === CoinParser::RESULT_COIN_DOES_NOT_EXIST) {
                //if does not exist, show 404 page:

                $metaValues = (object) ['coinTitle'=>$cun];
                $pm = PageMetaModel::get("coin-does-not-exist", $metaValues, $metaValues);

                return view('coin404', [
                    'pageMeta' => $pm,
                    'coinTitle' => $cun
                ]);
            }

            //todo: if failed to load or parse:
            if ($cp->getResultCode() === GenericParser::RESULT_FAILED) {
                throw new Error('не удалось загрузить или распарсить данные с сайта источинка данных');
            }

            //if parsed successfully, create coin model with data
            if ($cp->getResultCode() === GenericParser::RESULT_SUCCEED) {
                $ci = new CoinModel();

                //$ci->urlPTitle = $cp->url;
                $ci->urlPTitle = $cun;
                $ci->title = $cp->coinTitle;
                $ci->imageUrl = $cp->imageURL;

                $ci->erUSD = $cp->exchangeRateUSD;
                $ci->erBTC = $cp->exchangeRateBTC;
                $ci->erRUR = $cp->exchangeRateRUR;
                $ci->erEUR = $cp->exchangeRateEUR;

                $ci->cUSD = $cp->capitalizaciaUSD;
                $ci->oUSD = $cp->oborotUSD;
                $ci->v = $cp->vObrashenii;
                $ci->e = $cp->emissia;

                CoinModel::query()->where('urlPTitle','=', $cun)->delete();
                //save coin data
                $ci->save();

                $eps = [];

                ExchangeListModel::query()->where('urlPTitle','=', $cun)->delete();
                foreach ($cp->pairs as $a) {
                    $ep = new ExchangeListModel();
                    $ep->s($cun, $a);
                    array_push($eps, $ep);
                }

            }
        }

        if ($cbs !== null) {
            if (strlen($cbs->imageUrl) > 0) {
                $ci->imageUrl = $cbs->imageUrl;
            }
            if (strlen($cbs->title) > 0) {
                $ci->title = $cbs->title;
            }
        }

        $ci->loadRefsData();


        $metaValues = (object) ['coinTitle'=>$ci->title, 'coinCode'=>$cbs->code];
        $pm = PageMetaModel::get("coin", $metaValues, $metaValues);

        return view('coin', [
            'pageMeta' => $pm,
            'coinInfo' => $ci,
            'exchangePairs' => $eps,
        ]);

    }

}
