<?php

namespace App\Http\Controllers;

use App\Models\AnExchangeModel;
use App\Models\ExchangeModel;
use App\Models\ExchangePairsModel;
use App\Models\PageMetaModel;
use App\Models\PageViewsCount;
use App\Parsers\CoinParser;
use App\Parsers\ExchangeParser;
use App\Parsers\GenericParser;
use App\settings\SiteSettings;
use Illuminate\Http\Request;
use \App\Models\CoinModel;
use PhpParser\Error;

class Exchange extends Controller {

    public function index() {
    }


    public function show(Request $request) {

        $cun = $request->exchangeUrlName;

        $cbs = AnExchangeModel::query()->where(['urlPTitle' => $cun])->first();

        $ci = ExchangeModel::query()->where(['urlPTitle' => $cun])->first();
        $eps = null;

        $expired = true;

        if ($ci !== null) {
            $cet = SiteSettings::getSetting(SiteSettings::IDSN_CACHE_EXPIRY_TIME_EXCHANGES);
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
                $eps = ExchangePairsModel::query()->where(['urlPTitle' => $cun])->get();
            }
        }

        $lowInfo = false;

        if ($ci === null) {
            //parse data(if exist)
            $cp = new ExchangeParser('https://rucoinmarketcap.com/exchange/' . $cun, $cun);

            if ($cp->getResultCode() === ExchangeParser::RESULT_EXCHANGE_DOES_NOT_EXIST) {
                //$lowInfo = true;

                //if does not exist, show 404 page:

                $metaValues = (object) ['exchangeTitle'=>$cun];
                $pm = PageMetaModel::get("exchange-does-not-exist", $metaValues, $metaValues);

                return view('exchange404', [
                    'pageMeta' => $pm,
                    'exchangeTitle' => $cun
                ]);
            }

            // if failed to load or parse:
            if ($cp->getResultCode() === GenericParser::RESULT_FAILED) {
                throw new Error('не удалось загрузить или распарсить данные с сайта источинка данных');
            }

            //if parsed successfully, create exchange model with data
            if ($lowInfo || $cp->getResultCode() === GenericParser::RESULT_SUCCEED) {
                $ci = new ExchangeModel();

                //$ci->urlPTitle = $cp->url;
                $ci->urlPTitle = $cun;
                $ci->title = $cp->exchangeTitle;
                $ci->imageUrl = $cp->imageURL;

                $ci->oUSD = $cp->oborotUSD;
                $ci->np = $cp->numPairs;
                $ci->wsu = $cp->websiteURL;

                ExchangeModel::query()->where('urlPTitle', '=', $cun)->delete();
                //save data
                $ci->save();

                ExchangePairsModel::query()->where('urlPTitle', '=', $cun)->delete();
                $eps = [];
                foreach ($cp->pairs as $i => $a) {
                    $ep = new ExchangePairsModel();
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

        $metaValues = (object) ['exchangeTitle'=>$ci->title];
        $pm = PageMetaModel::get("exchange", $metaValues, $metaValues);


        return view('exchange', [
            'pageMeta' => $pm,
            'exchangeInfo' => $ci,
            'exchangePairs' => $eps,
        ]);

    }

}
