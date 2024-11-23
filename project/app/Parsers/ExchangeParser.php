<?php


namespace App\Parsers;


use PhpParser\Error;

class ExchangeParser extends GenericParser {

    public function __construct($url, $urlPTitle) {
        $this->loadData($url);

        if ($this->getStateCode() !== GenericParser::STATE_LOADING_SUCCEED) {
            return;
        }


        $h = $this->loadedData;


        $exchangeTitle = "NO_DATA";

        $oborotUSD = "NO_DATA";
        $numPairs = "NO_DATA";
        $websiteURL = "NO_DATA";

        //coin urlPTitle
        $s0 = '<link rel="canonical" href="';
        $s = strpos($h, $s0) + strlen($s0);
        $e = strpos($h, '"', $s);
        $coinURL = substr($h, $s, $e - $s);
        $s0 = '/exchange/';
        $s = strpos($coinURL, $s0) + strlen($s0);
        $coinURL = substr($coinURL, $s);

        // image url
        $coinImageURL = "NO_DATA";
        $s0 = "/images/exchanges/32";
        $s = strpos($h, $s0);
        if ($s !== false) {
            $e = strpos($h, '?', $s);
            $coinImageURL = substr($h, $s, $e - $s);
        }//else {echo 'error ';var_dump([$s,$e,$ib0]);exit;}

        $s0 = "<span class=\"text-dark font-weight-bold\"> ";
        $s = strpos($h, $s0) + strlen($s0);
        $s1 = "</span>";
        $e = strpos($h, $s1, $s);

        $exchangeTitle = substr($h, $s, $e - $s);


        $s0 = "href";
        $s = strpos($h, $s0, $e)+strlen($s0)+2;
        $s1 = "role";
        $e = strpos($h, $s1, $s)-2;

        $websiteURL = substr($h, $s, $e-$s);


        $s0 = "Оборот за 24ч: $";
        $s = strpos($h, $s0)+strlen($s0);
        $s1 = "</div>";
        $e = strpos($h, $s1, $s);

        $rr = substr($h, $s, $e-$s);
        $rr = $this->parseNumberAndCoinTitle($rr);
        $oborotUSD = $this->pes($rr[0]).'!@#$^'.$rr[1];

        $s0 = "Криптовалютные пары: ";
        $s = strpos($h, $s0, $e)+strlen($s0);
        $s1 = "</div>";
        $e = strpos($h, $s1, $s);

        $numPairs = substr($h, $s, $e-$s);

/* PAIRS */
        $pairsList = [];

        $s0 = "<tbody>";
        $s = strpos($h, $s0, $e)+strlen($s0);
        $s1 = "</tbody>";
        $e = strpos($h, $s1, $s);

        $h1 = substr($h, $s, $e-$s);
        $trs = explode('<tr>', $h1);
        array_splice($trs, 0, 1);

        $this->pairs = [];
        foreach ($trs as $i=>$a) {
            array_push($this->pairs, $this->parseTableRow($a));
        }


        if (strlen($exchangeTitle) < 1 || $exchangeTitle === ' ') {
            $this->resultCode = ExchangeParser::RESULT_EXCHANGE_DOES_NOT_EXIST;
            return;
        }


        $this->exchangeTitle = $exchangeTitle;
        $this->websiteURL = $websiteURL;
        $this->oborotUSD = $oborotUSD;
        $this->numPairs = max(intval($numPairs), count($this->pairs));

        $this->url = $coinURL;
        $this->imageURL = $coinImageURL;


        $this->resultCode = GenericParser::RESULT_SUCCEED;

    }


    function parseTableRow($h) {
        $r = [];

        $tds = explode('<td', $h);
        array_splice($tds, 0, 1);
        foreach ($tds as $i=>$a) {
            $tds[$i] = '<td'.$a;
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
        $s += 1 + strlen('href=')+strlen('/coin/');
        $e = strpos($h, '"', $s);
        $url = substr($h, $s, $e - $s);
        array_push($r, $url);

        //image url
        $h = $this->getTagContents($h, 'a');
        $imgUrl = $this->getTagContents($h, 'img');
        $s = strpos($h, 'src="')+strlen('src="');
        $e = strpos($h, '"', $s);
        $imageUrl = substr($h, $s, $e - $s);
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
        $h = substr($h, 0, strlen($h)-2);
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
    function pes($h){
        if (strlen($h)<1){return "0";}
        return $h;
    }

    function getTagContents($h, $tagName){
        //<td class="d-none d-lg-table-cell align-middle text-nowrap">1</td>
        $s = strpos($h, "<".$tagName);
        $s = strpos($h, ">", $s)+1;
        $e = strpos($h, "</".$tagName, $s);
        $r = substr($h, $s, $e-$s);
        return $r;
    }

    function parseNumberAndCoinTitle($sscns, $isFloat = false) {
        $nd = "0123456789";
        if ($isFloat) {$nd = "0123456789.";}
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

    public $exchangeTitle;
    public $websiteURL;

    public $oborotUSD;
    public $numPairs;

    public $url;
    public $imageURL;


    /**
     * @var array TABLE ROW[TABLE COLUMN, ...]
     */
    public $pairs;

    /**
     * валюта не существует на сайте, с которого был парсинг
     */
    const RESULT_EXCHANGE_DOES_NOT_EXIST = 10;

}
