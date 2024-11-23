<?php

/* json data format:
{
    currentTask: 0,
    pagesFound: 0,
    completed: false,
    urls: [],
    emails: [],
    currentPage: 0
}
*/

$url = "https://rucoinmarketcap.com/coin/usd-coin";

$start = floor(microtime(true) * 1000);

$handle = curl_init($url);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
$html = curl_exec($handle);

curl_close($handle);

$end = floor(microtime(true) * 1000);

if (strlen($html) > 0) {
    echo 'S:'.$start.'<br>';
    echo 'E:'.$end.'<br>';
    echo 'DIFF:'.($end-$start).'<br>';
    echo '----------------------------------------------------------<br>';
    echo $html;
}
