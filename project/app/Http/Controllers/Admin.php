<?php

namespace App\Http\Controllers;

use App\Models\ACoinModel;
use App\Models\AnExchangeModel;
use App\Models\ExchangeListModel;
use App\Models\ExchangeModel;
use App\Models\ExchangePairsModel;
use App\Models\PageMetaModel;
use App\Models\PageViewsCount;
use App\Parsers\CoinParser;
use App\Parsers\GenericParser;
use App\Parsers\XMLSiteMapDataParser;
use App\settings\SiteSettings;
use Illuminate\Http\Request;
use \App\Models\CoinModel;
use PhpParser\Error;

class Admin extends Controller {


    public function index(Request $request) {

        return view('admin', [
            'cacheExpiryTimeCoins' => SiteSettings::getSetting(SiteSettings::IDSN_CACHE_EXPIRY_TIME_COINS),
            'cacheExpiryTimeExchanges' => SiteSettings::getSetting(SiteSettings::IDSN_CACHE_EXPIRY_TIME_EXCHANGES),
        ]);

    }

    public function indexMeta(Request $request) {

        $pm = PageMetaModel::query()->get();

        $pm = $pm->toArray();
        $r = [];
        foreach ($pm as $a) {
            array_push($r, (object)$a);
        }
        $pm = $r;

        return view('adminMeta', [
            'pageMetaList' => $pm
        ]);

    }

    public function index2(Request $request) {

        $refs = AnExchangeModel::query()->leftJoin('coin_ref_url_list', 'exchanges.urlPTitle', '=', 'coin_ref_url_list.urlPTitle')->get(["exchanges.title", "exchanges.imageUrl", "exchanges.urlPTitle", "coin_ref_url_list.b", "coin_ref_url_list.ex", "coin_ref_url_list.bb"]);

        $refs = $refs->toArray();
        $r = [];
        foreach ($refs as $a) {
            array_push($r, (object)$a);
        }
        $refs = $r;

        usort($refs, function ($a, $b) {
            return strcmp($a->urlPTitle, $b->urlPTitle);
        });


        return view('admin2', [
            'refs' => $refs,

            'coinPageCoinInfoBuyRef' => SiteSettings::getSetting(SiteSettings::IDSN_CRU_COIN_PAGE_COIN_INFO_BUY_REF),

            'coinPageCoinInfoSellRef' => SiteSettings::getSetting(SiteSettings::IDSN_CRU_COIN_PAGE_COIN_INFO_SELL_REF),

            'coinPageCoinInfoExchangeRef' => SiteSettings::getSetting(SiteSettings::IDSN_CRU_COIN_PAGE_COIN_INFO_EXCHANGE_REF),

            'coinPageExchangesListBuyRef' => SiteSettings::getSetting(SiteSettings::IDSN_CRU_COIN_PAGE_EXCHANGES_LIST_BUY_REF),

            'coinPageExchangesListSellRef' => SiteSettings::getSetting(SiteSettings::IDSN_CRU_COIN_PAGE_EXCHANGES_LIST_SELL_REF),

            'coinPageExchangesListExchangeRef' => SiteSettings::getSetting(SiteSettings::IDSN_CRU_COIN_PAGE_EXCHANGES_LIST_EXCHANGE_REF),

            'exchangeInfoDefaultBuyRef' => SiteSettings::getSetting(SiteSettings::IDSN_CRU_EXCHANGE_INFO_DEFAULT_BUY_REF),

            'exchangeInfoDefaultSellRef' => SiteSettings::getSetting(SiteSettings::IDSN_CRU_EXCHANGE_INFO_DEFAULT_SELL_REF),

            'exchangeInfoDefaultExchangeRef' => SiteSettings::getSetting(SiteSettings::IDSN_CRU_EXCHANGE_INFO_DEFAULT_EXCHANGE_REF),

        ]);

    }

    /**
     * Кеш данных сбрасывается после:
     * ACCEPT JSON: {scec:Валюты(минут), scee:Биржи(минут)}
     *
     * RETURN: JSON: {
     * resultCode : 0-success; 1-error,
     * errorText : non-empty if error occured
     * }
     */
    public function saveSiteSettings(Request $request) {
        try {
            $d = json_decode($request->getContent());
            //data cache expiry
            if (property_exists($d, "scec")) {
                SiteSettings::setSetting(SiteSettings::IDSN_CACHE_EXPIRY_TIME_COINS, $d->scec);
            }
            if (property_exists($d, "scee")) {
                SiteSettings::setSetting(SiteSettings::IDSN_CACHE_EXPIRY_TIME_EXCHANGES, $d->scee);
            }

            //common ref urls


            if (property_exists($d, "coinPageCoinInfoBuyRef")) {
                SiteSettings::setSetting(SiteSettings::IDSN_CRU_COIN_PAGE_COIN_INFO_BUY_REF, $d->coinPageCoinInfoBuyRef);
            }
            if (property_exists($d, "coinPageCoinInfoSellRef")) {
                SiteSettings::setSetting(SiteSettings::IDSN_CRU_COIN_PAGE_COIN_INFO_SELL_REF, $d->coinPageCoinInfoSellRef);
            }
            if (property_exists($d, "coinPageCoinInfoExchangeRef")) {
                SiteSettings::setSetting(SiteSettings::IDSN_CRU_COIN_PAGE_COIN_INFO_EXCHANGE_REF, $d->coinPageCoinInfoExchangeRef);
            }

            if (property_exists($d, "coinPageExchangesListBuyRef")) {
                SiteSettings::setSetting(SiteSettings::IDSN_CRU_COIN_PAGE_EXCHANGES_LIST_BUY_REF, $d->coinPageExchangesListBuyRef);
            }
            if (property_exists($d, "coinPageExchangesListSellRef")) {
                SiteSettings::setSetting(SiteSettings::IDSN_CRU_COIN_PAGE_EXCHANGES_LIST_SELL_REF, $d->coinPageExchangesListSellRef);
            }
            if (property_exists($d, "coinPageExchangesListExchangeRef")) {
                SiteSettings::setSetting(SiteSettings::IDSN_CRU_COIN_PAGE_EXCHANGES_LIST_EXCHANGE_REF, $d->coinPageExchangesListExchangeRef);
            }

            if (property_exists($d, "exchangeInfoDefaultBuyRef")) {
                SiteSettings::setSetting(SiteSettings::IDSN_CRU_EXCHANGE_INFO_DEFAULT_BUY_REF, $d->exchangeInfoDefaultBuyRef);
            }
            if (property_exists($d, "exchangeInfoDefaultSellRef")) {
                SiteSettings::setSetting(SiteSettings::IDSN_CRU_EXCHANGE_INFO_DEFAULT_SELL_REF, $d->exchangeInfoDefaultSellRef);
            }
            if (property_exists($d, "exchangeInfoDefaultExchangeRef")) {
                SiteSettings::setSetting(SiteSettings::IDSN_CRU_EXCHANGE_INFO_DEFAULT_EXCHANGE_REF, $d->exchangeInfoDefaultExchangeRef);
            }

            $data = SiteSettings::generatePublicSettingsJson();
            $fn = "./sitePublicSettings.json";
            if(file_exists($fn)) {
                unlink($fn);
            }
            file_put_contents($fn, $data, LOCK_EX);

        } catch (Exception $e) {
            return json_encode((object)['resultCode' => '1', 'errorText' => $e]);
        }

        return json_encode((object)['resultCode' => '0']);
    }


    /**
     * Загрузите полученную карту (файл sitemap.xml)
     * ACCEPT application/text
     *
     * RETURN: JSON: {
     * resultCode : 0-success; 1-error,
     * errorText : non-empty if error occured
     * }
     */
    public function uploadSiteMap(Request $request) {
        //parse xml data
        $p = new XMLSiteMapDataParser($request->getContent());

        if ($p->getResultCode() === GenericParser::RESULT_SUCCEED) {
            //get lists from DB
            $ec = ACoinModel::query()->get();
            $ee = AnExchangeModel::query()->get();


            $existingCoins = [];
            foreach ($ec as $i => $a) {
                array_push($existingCoins, $a->urlPTitle);
            }
            $existingExchanges = [];
            foreach ($ee as $i => $a) {
                array_push($existingExchanges, $a->urlPTitle);
            }

            /*
            echo '//coins://';
            var_dump($existingCoins);
            echo '//ex://';
            var_dump($existingExchanges);*/

            $newC = [];
            $newE = [];
            // remove duplicates:
            foreach ($p->coinsList as $i => $a) {
                $clear = true;
                foreach ($existingCoins as $ii => $aa) {
                    if ($aa == $a) {
                        $clear = false;
                        break;
                    }
                }
                if ($clear) {
                    array_push($newC, $a);
                }
            }
            foreach ($p->exchangesList as $i => $a) {
                $clear = true;
                foreach ($existingExchanges as $ii => $aa) {
                    if ($aa == $a) {
                        $clear = false;
                        break;
                    }
                }
                if ($clear) {
                    array_push($newE, $a);
                }
            }

            /*
            echo '//NEW coins://';
            var_dump($newC);
            echo '//NEW exchanges://';
            var_dump($newE);*/

            // save urls to DB, you will get titles later manually, then add title parsing feature here
            foreach ($newC as $i => $a) {
                $t = new ACoinModel();
                $t->urlPTitle = $a;
                $t->title = "NO_DATA";
                $t->save();
            }
            foreach ($newE as $i => $a) {
                $t = new AnExchangeModel();
                $t->urlPTitle = $a;
                $t->title = "NO_DATA";
                $t->save();
            }


            return json_encode((object)['resultCode' => '0']);
        }

        return json_encode((object)['resultCode' => '1', 'errorText' => ""]);
    }


    /**
     * Загрузите файл CoinAndExchangeInfo.json
     * ACCEPT application/text
     *
     * RETURN: JSON: {
     * resultCode : 0-success; 1-error,
     * errorText : non-empty if error occured
     * }
     */
    public function uploadInitialData(Request $request) {
        $d = json_decode($request->getContent());

        if ($d === null) {
            return json_encode((object)['resultCode' => '1', 'errorText' => "failed to parse json"]);
        }

        //clear db
        ACoinModel::query()->delete();
        AnExchangeModel::query()->delete();

        $c = [];
        foreach ($d->coins as $a) {
            $t = new ACoinModel();
            $t->urlPTitle = $a->pTitleURL;
            $t->imageUrl = $a->imageURL;
            $t->title = $a->title;
            $t->code = $a->code;
            $t->save();
        }

        $e = [];
        foreach ($d->exchanges as $a) {
            $t = new AnExchangeModel();
            $t->urlPTitle = $a->pTitleURL;
            $t->imageUrl = $a->imageURL;
            $t->title = $a->title;
            $t->save();
        }

        return json_encode((object)['resultCode' => '0']);
    }


    /**
     * Очистить кеш данных
     * ACCEPT JSON: {target: 0-валюты; 1-биржы}
     *
     * RETURN: JSON: {
     * resultCode : 0-success; 1-error,
     * errorText : non-empty if error occured
     * }
     */
    public function clearDataCache(Request $request) {
        try {
            $d = json_decode($request->getContent());

            if ($d->target == 0) {
                //coins
                CoinModel::query()->delete();
                ExchangeListModel::query()->delete();
            }

            if ($d->target == 1) {
                //exchanges
                ExchangeModel::query()->delete();
                ExchangePairsModel::query()->delete();
            }

        } catch (Exception $e) {
            return json_encode((object)['resultCode' => '1', 'errorText' => $e]);
        }

        return json_encode((object)['resultCode' => '0']);
    }

}
