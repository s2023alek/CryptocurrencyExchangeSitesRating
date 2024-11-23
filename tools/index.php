<?php

$xml= new DOMDocument();

ob_start();
include "sitemap.xml";
$xmlDoc = ob_get_clean();

$xml = simplexml_load_string($xmlDoc);

$urls = [];

foreach ($xml->url as $u) {
    //node are accessted by -> operator
    array_push($urls, $u->loc);
}

function myFilter($string) {
    if(strpos($string, '/events/') !== false) {
        return false;
    }

    for($nn=0;$nn<10;$nn++) {
        if(strpos($string, 'https://rucoinmarketcap.com/' . $nn) !== false){
            return false;
        }
    }
    return true;

}

$urls = array_filter($urls, 'myFilter');

natcasesort($urls);

$fileName="urlsList.txt";

file_put_contents($fileName, implode("\n", $urls));
echo "check file 'urlsList.txt'";

//num coins
function myFilter1($string) {
    return strpos($string, '/coin/') !== false;
}
$uc = array_filter($urls, 'myFilter1');
$numCoins=count($uc);


//num exchanges
function myFilter2($string) {
    return strpos($string, '/exchange/') !== false;
}
$ue = array_filter($urls, 'myFilter2');
$numExchanges=count($ue);


echo '<br>Number of coins:'.$numCoins;
echo '<br>Number of exchanges:'.$numExchanges."<br>-------------------<br>";
echo 'exchanges list:<br>';

foreach($ue as $key => $value) {
    echo $value.'<br>';
}