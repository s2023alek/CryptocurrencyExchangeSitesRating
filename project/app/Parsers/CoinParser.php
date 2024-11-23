<?php


namespace App\Parsers;


use PhpParser\Error;

class CoinParser extends GenericParser {

    public function __construct($url, $urlPTitle) {
        $this->loadData($url);

        if ($this->getStateCode() !== GenericParser::STATE_LOADING_SUCCEED) {
            return;
        }


        $h = $this->loadedData;

        $sdned = "/images/coins/32/.png";
        $sdned1 = 'Курс / () на сегодня';

        if (strpos($h, $sdned) !== false || strpos($h, $sdned1) !== false) {
            $this->resultCode = CoinParser::RESULT_COIN_DOES_NOT_EXIST;
            return;
        }

        //coin urlPTitle
        $s0 = '<link rel="canonical" href="';
        $s = strpos($h, $s0) + strlen($s0);
        $e = strpos($h, '"', $s);
        $coinURL = substr($h, $s, $e - $s);
        $s0 = '/coin/';
        $s = strpos($coinURL, $s0) + strlen($s0);
        $coinURL = substr($coinURL, $s);

        // coin code
        $coinCode = "NO_DATA";
        //<span class="text-muted small"> (BTC)</span>
        $s0 = '<span class="text-muted small"> (';
        $a = strpos($h, $s0) + strlen($s0);
        if ($a !== false) {
            $s1 = ")</span>";
            $b = strpos($h, $s1, $a);
            $coinCode = substr($h, $a, $b - $a);
        }

        //coin image url
        $s0 = '<meta property="og:image" content="https://rucoinmarketcap.com';
        $s = strpos($h, $s0) + strlen($s0);
        $e = strpos($h, '"', $s);
        $coinImageURL = substr($h, $s, $e - $s);

        $this->vObrasheniiUSD = "0";


        $s0 = "contentUrl";
        $a = strpos($h, $s0)+ strlen($s0);
        $s1 = "<span style=\"font-size:20px\"> ";
        $s = strpos($h, $s1, $a) + strlen($s1);
        $s2 = "</span>";
        $e = strpos($h, $s2, $s);

        $coinTitle = substr($h, $s, $e - $s);


        $sc0 = "<span class=\"clearfix text-success\" style=\"font-size:20px\"></span>";
        $a = strpos($h, $sc0, $e);

        if ($a === false) {
            $s3 = "col-lg-6 col-sm-12";
            $a = strpos($h, $s3, $e);
            $s4 = ">$";
            $s = strpos($h, $s4, $a) + strlen($s4);
            $s5 = " USD";
            $e = strpos($h, $s5, $s);

            $exchangeRateUSD = substr($h, $s, $e - $s);

            $s6 = "clearfix text-body\">";
            $s = strpos($h, $s6, $e) + strlen($s6);
            $s7 = "BTC";
            $e = strpos($h, $s7, $s);

            $exchangeRateBTC = substr($h, $s, $e - $s - 1);

            $s8 = "₽";
            $s = strpos($h, $s8, $e) + strlen($s8);
            $s7 = " RUB";
            $e = strpos($h, $s7, $s);

            $exchangeRateRUR = substr($h, $s, $e - $s);

            $s9 = "€";
            $s = strpos($h, $s9, $e) + strlen($s9);
            $s10 = " EUR";
            $e = strpos($h, $s10, $s);

            $exchangeRateEUR = substr($h, $s, $e - $s);

            $s11 = "Капитализация";
            $a = strpos($h, $s11, $e);
            $s12 = "$";
            $s = strpos($h, $s12, $a) + strlen($s12);
            $s13 = "</span>";
            $e = strpos($h, $s13, $s);

            $capitalizaciaUSD = substr($h, $s, $e - $s);
            $capitalizaciaUSD = str_replace(' ', '', $capitalizaciaUSD);

            $s14 = "Оборот";
            $a = strpos($h, $s14, $e);
            $s15 = "$";
            $s = strpos($h, $s15, $a) + strlen($s15);
            $s16 = "</span>";
            $e = strpos($h, $s16, $s);

            $oborotUSD = substr($h, $s, $e - $s);
            $oborotUSD = str_replace(' ', '', $oborotUSD);


            $s16 = "В обращении";
            $a = strpos($h, $s16, $e);
            if ($a !== false) {
                $s17 = "light btn-sm\">";
                $s = strpos($h, $s17, $a) + strlen($s17);
                $s18 = "<span class=\"text-success\">";
                $s19 = "</span>";
                $pe = strpos($h, $s19, $s);
                $e = strpos($h, $s18, $s);

                $res1 = null;
                if ($e === false || $e > $pe) {
                    $e = $pe;
                } else {
                    $ss1 = $e + strlen($s18) + 1;
                    $ee1 = strpos($h, $s19, $ss1);
                    $res1 = substr($h, $ss1, $ee1 - $ss1 - 1);
                }

                $res = substr($h, $s, $e - $s);
                $res = $this->parseNumberAndCoinTitle($res);

                $vObrashenii = $res[0] . '!@#$^' . $res[1];
                if ($res1) {
                    $vObrashenii .= '!@#$^' . $res1;
                }
            } else {
                $vObrashenii = "NO_DATA";
            }


            $s16 = "Эмиссия";
            $a = strpos($h, $s16, $e);
            if ($a !== false) {
                $s17 = "light btn-sm\">";
                $s = strpos($h, $s17, $a) + strlen($s17);
                $s18 = "<span class=\"text-success\">";
                $s19 = "</span>";
                $pe = strpos($h, $s19, $s);
                $e = strpos($h, $s18, $s);

                $res1 = null;
                if ($e === false || $e > $pe) {
                    $e = $pe;
                } else {
                    $ss1 = $e + strlen($s18) + 1;
                    $ee1 = strpos($h, $s19, $ss1);
                    $res1 = substr($h, $ss1, $ee1 - $ss1 - 1);
                }

                $res = substr($h, $s, $e - $s);
                $res = $this->parseNumberAndCoinTitle($res);

                $emissia = $res[0] . '!@#$^' . $res[1];
                if ($res1) {
                    $emissia .= '!@#$^' . $res1;
                }
            } else {
                $emissia = "NO_DATA";
            }
        } else {
            $exchangeRateUSD = "NO_DATA";
            $exchangeRateBTC = "NO_DATA";
            $exchangeRateRUR = "NO_DATA";
            $exchangeRateEUR = "NO_DATA";
            $capitalizaciaUSD = "NO_DATA";
            $oborotUSD = "NO_DATA";
            $vObrashenii = "NO_DATA";
            $emissia = "NO_DATA";
        }

        /* PAIRS */

        $s0 = "<tbody>";
        $s = strpos($h, $s0, $e) + strlen($s0);
        $s1 = "</tbody>";
        $e = strpos($h, $s1, $s);

        $h1 = substr($h, $s, $e - $s);
        $trs = explode('<tr>', $h1);
        array_splice($trs, 0, 1);

        $this->pairs = [];
        foreach ($trs as $i => $a) {
            array_push($this->pairs, $this->parseTableRow($a));
        }






        $this->coinTitle = $coinTitle;
        $this->coinCode = $coinCode;
        $this->exchangeRateUSD = $exchangeRateUSD;
        $this->exchangeRateBTC = $exchangeRateBTC;
        $this->exchangeRateRUR = $exchangeRateRUR;
        $this->exchangeRateEUR = $exchangeRateEUR;
        $this->capitalizaciaUSD = $capitalizaciaUSD;
        $this->oborotUSD = $oborotUSD;
        $this->vObrashenii = $vObrashenii;
        $this->emissia = $emissia;

        $this->url = $coinURL;
        $this->imageURL = $coinImageURL;


        $this->resultCode = GenericParser::RESULT_SUCCEED;

    }

    function parseTableRow($h) {
        $r = [];

        $tds = explode('<td', $h);
        array_splice($tds, 0, 1);
        foreach ($tds as $i => $a) {
            $tds[$i] = '<td' . $a;
        }

//1
        $h = array_shift($tds);
        $h = $this->getTagContents($h, 'td');
        array_push($r, $this->pes($h));
//2
        $h = array_shift($tds);
        $h = $this->getTagContents($h, 'td');
        //url
        $s = strpos($h, 'href=');
        $s += 1 + strlen('href=') + strlen('/exchange/');
        $e = strpos($h, '"', $s);
        $url = substr($h, $s, $e - $s);
        array_push($r, $url);

        //image url
        $h = $this->getTagContents($h, 'a');
        $imgUrl = $this->getTagContents($h, 'img');
        $s = strpos($h, 'src="')+strlen('src="');
        $e = strpos($h, '"', $s);
        $imageUrl = join('32', explode('16', substr($h, $s, $e - $s)));//replace 16x16 icons with 32x32
        array_push($r, $imageUrl);

        //title
        $h = $this->getTagContents($h, 'span');
        array_push($r, $h);
        /* -3-4 */
        $h = array_shift($tds);
        $h = array_shift($tds);

//3
        $h = array_shift($tds);
        $h = $this->getTagContents($h, 'td');
        array_push($r, $this->pes($h));
//4
        $h = array_shift($tds);
        $h = $this->getTagContents($h, 'td');
        $h = $this->parseNumberAndCoinTitle($h, true)[0];
        array_push($r, $this->pes($h));
//5
        $h = array_shift($tds);
        $h = $this->getTagContents($h, 'td');
        $h = substr($h, 1);
        $h = $this->parseNumberAndCoinTitle($h, true)[0];
        array_push($r, $this->pes($h));

//6
        $h = array_shift($tds);
        $h = $this->getTagContents($h, 'td');
        $h = substr($h, 1);
        $h = $this->parseNumberAndCoinTitle($h, true)[0];
        array_push($r, $this->pes($h));

//6
        $h = array_shift($tds);
        $h = $this->getTagContents($h, 'td');
        $h = substr($h, 0, strlen($h) - 2);
        $h = $this->parseNumberAndCoinTitle($h, true)[0];
        array_push($r, $this->pes($h));
//7
        $h = array_shift($tds);
        $h = $this->getTagContents($h, 'td');
        $h = $this->parseNumberAndCoinTitle($h, true)[0];
        array_push($r, $this->pes($h));

        return $r;
    }

    /**
     * processEmptyString
     */
    function pes($h) {
        if (strlen($h) < 1) {
            return "0";
        }
        return $h;
    }

    function getTagContents($h, $tagName) {
        //<td class="d-none d-lg-table-cell align-middle text-nowrap">1</td>
        $s = strpos($h, "<" . $tagName);
        if ($s === false) {
            return "";
        }
        $s = strpos($h, ">", $s) + 1;
        if ($s === false) {
            return "";
        }
        $e = strpos($h, "</" . $tagName, $s);
        if ($e === false) {
            return "";
        }
        $r = substr($h, $s, $e - $s);
        return $r;
    }

    function parseNumberAndCoinTitle($sscns, $isFloat = false) {
        $nd = "0123456789";
        if ($isFloat) {
            $nd = "0123456789.";
        }

        $ws = " ";
        $ss = mb_str_split($sscns);
        $rn = [];
        $sps = 0;
        foreach ($ss as $c) {
            if ($c !== $ws) {
                if (strpos($nd, $c) !== false) {
                    array_push($rn, $c);
                } else {
                    break;
                }
            }
            $sps += 1;
        }
        $rn = implode('', $rn);
        $rs = substr($sscns, $sps);
        return [$rn, $rs];
    }

    public $coinTitle;
    public $coinCode;

    public $exchangeRateUSD;
    public $exchangeRateBTC;
    public $exchangeRateRUR;
    public $exchangeRateEUR;

    public $capitalizaciaUSD;
    public $oborotUSD;
    public $vObrashenii;
    public $emissia;

    public $url;
    public $imageURL;


    /**
     * @var array TABLE ROW[TABLE COLUMN, ...]
     */
    public $pairs;

    /**
     * валюта не существует на сайте, с которого был парсинг
     */
    const RESULT_COIN_DOES_NOT_EXIST = 10;

}
