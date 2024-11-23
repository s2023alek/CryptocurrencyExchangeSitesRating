<?php

namespace App\settings;

use App\Models\SiteSettingsModel;
use PhpParser\Error;

class SiteSettings {

    /**
     * ID SETTING NAME COMMON REF URL
     */
    const IDSN_CRU_COIN_PAGE_COIN_INFO_BUY_REF = "coinPageCoinInfoBuyRef";
    const IDSN_CRU_COIN_PAGE_COIN_INFO_SELL_REF = "coinPageCoinInfoSellRef";
    const IDSN_CRU_COIN_PAGE_COIN_INFO_EXCHANGE_REF = "coinPageCoinInfoExchangeRef";
    /**
     * ID SETTING NAME COMMON REF URL
     */
    const IDSN_CRU_COIN_PAGE_EXCHANGES_LIST_BUY_REF = "coinPageExchangesListBuyRef";
    const IDSN_CRU_COIN_PAGE_EXCHANGES_LIST_SELL_REF = "coinPageExchangesListSellRef";
    const IDSN_CRU_COIN_PAGE_EXCHANGES_LIST_EXCHANGE_REF = "coinPageExchangesListExchangeRef";
    /**
     * ID SETTING NAME COMMON REF URL
     */
    const IDSN_CRU_EXCHANGE_INFO_DEFAULT_BUY_REF = "exchangeInfoDefaultBuyRef";
    const IDSN_CRU_EXCHANGE_INFO_DEFAULT_SELL_REF = "exchangeInfoDefaultSellRef";
    const IDSN_CRU_EXCHANGE_INFO_DEFAULT_EXCHANGE_REF = "exchangeInfoDefaultExchangeRef";


    /**
     * ID SETTING NAME
     */
    const IDSN_CACHE_EXPIRY_TIME_COINS = "cacheExpiryTimeCoins";
    /**
     * ID SETTING NAME
     */
    const IDSN_CACHE_EXPIRY_TIME_EXCHANGES = "cacheExpiryTimeExchanges";


    public static function getSetting($name) {
        if (!self::$settingsAreLoaded) {
            self::loadSettings();
        }

        foreach (self::$settings as $a) {
            if ($a->name == $name) {
                return $a->value;
            }
        }
        return "";//return "undefined"???;
        //throw new Error('site setting not found:'.$name);
    }

    public static function setSetting($name, $value) {
        $m = new SiteSettingsModel();
        $m->updateSetting($name, $value);
        self::loadSettings();
    }


    public static function loadSettings() {
        self::$settings = \App\Models\SiteSettingsModel::query()->get();
        self::$settingsAreLoaded = true;
        /*foreach (self::$settings as $i=>$a) {
            echo $a->name.'='.$a->value.'<br>';
        }*/
    }

    public static function generatePublicSettingsJson() {
        self::loadSettings();
        $d = [];
        foreach (self::$publicSettingsList as $a) {
            array_push($d, (object)[$a => self::getSetting($a)]);
        }
        return json_encode($d);
    }


    private static $publicSettingsList = [
        self::IDSN_CRU_COIN_PAGE_COIN_INFO_BUY_REF,
        self::IDSN_CRU_COIN_PAGE_COIN_INFO_SELL_REF,
        self::IDSN_CRU_COIN_PAGE_COIN_INFO_EXCHANGE_REF,

        self::IDSN_CRU_COIN_PAGE_EXCHANGES_LIST_BUY_REF,
        self::IDSN_CRU_COIN_PAGE_EXCHANGES_LIST_SELL_REF,
        self::IDSN_CRU_COIN_PAGE_EXCHANGES_LIST_EXCHANGE_REF,

        self::IDSN_CRU_EXCHANGE_INFO_DEFAULT_BUY_REF,
        self::IDSN_CRU_EXCHANGE_INFO_DEFAULT_SELL_REF,
        self::IDSN_CRU_EXCHANGE_INFO_DEFAULT_EXCHANGE_REF,
    ];

    private static $settingsAreLoaded = false;
    private static $settings;
}
