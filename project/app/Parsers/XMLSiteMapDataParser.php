<?php


namespace App\Parsers;


use PhpParser\Error;

class XMLSiteMapDataParser extends GenericParser {

    public function __construct($xmlData) {

        $h = $xmlData;

        $xmlStart = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n\r\n";

        $a1 = "<url>";
        $s = strpos($h, $a1);
        $h = "<document>".substr($h, $s)."</document>";

        try {
            $d = $this->XMLtoArray($h);
        } catch (\Exception $e) {
            //report error
            $this->stateCode = GenericParser::STATE_PARSING_FAILED;
            $this->resultCode = GenericParser::RESULT_FAILED;
            //echo "bad xml:".$e->getMessage();
        }

        $coins=[];
        $exchanges=[];

        foreach($d['DOCUMENT']['URL'] as $item) {
            $t = $item['LOC'];

            if (strpos($t, "/exchange/")!==false) {
                $a1 = "/exchange/";
                $t = substr($t, stripos($t, $a1)+strlen($a1));
                array_push($exchanges, $t);
            }

            if (strpos($t, "/coin/")!==false) {
                $a1 = "/coin/";
                $t = substr($t, stripos($t, $a1)+strlen($a1));
                array_push($coins, $t);
            }
        }

        $this->stateCode = GenericParser::STATE_PARSING_SUCCEED;
        $this->resultCode = GenericParser::RESULT_SUCCEED;

        $this->coinsList = $coins;
        $this->exchangesList = $exchanges;

        /*
        echo 'coin urls:<br>';
        foreach ($coins as $u) {
            echo $u.'<br>';
        }
        echo '<br><hr>exchange urls:<br>';
        foreach ($exchanges as $u) {
            echo $u.'<br>';
        }
        */

    }

    public $coinsList;
    public $exchangesList;


    /**
     * Convert XML to an Array
     *
     * @param string  $XML
     * @return array
     */
    private function XMLtoArray($XML){
        $xml_array=null;

        $xml_parser = xml_parser_create();
        xml_parse_into_struct($xml_parser, $XML, $vals);
        xml_parser_free($xml_parser);
        // wyznaczamy tablice z powtarzajacymi sie tagami na tym samym poziomie
        $_tmp='';
        foreach ($vals as $xml_elem) {
            $x_tag=$xml_elem['tag'];
            $x_level=$xml_elem['level'];
            $x_type=$xml_elem['type'];
            if ($x_level!=1 && $x_type == 'close') {
                if (isset($multi_key[$x_tag][$x_level]))
                    $multi_key[$x_tag][$x_level]=1;
                else
                    $multi_key[$x_tag][$x_level]=0;
            }
            if ($x_level!=1 && $x_type == 'complete') {
                if ($_tmp==$x_tag)
                    $multi_key[$x_tag][$x_level]=1;
                $_tmp=$x_tag;
            }
        }
        // jedziemy po tablicy
        foreach ($vals as $xml_elem) {
            $x_tag=$xml_elem['tag'];
            $x_level=$xml_elem['level'];
            $x_type=$xml_elem['type'];
            if ($x_type == 'open')
                $level[$x_level] = $x_tag;
            $start_level = 1;
            $php_stmt = '$xml_array';
            if ($x_type=='close' && $x_level!=1)
                $multi_key[$x_tag][$x_level]++;
            while ($start_level < $x_level) {
                $php_stmt .= '[$level['.$start_level.']]';
                if (isset($multi_key[$level[$start_level]][$start_level]) && $multi_key[$level[$start_level]][$start_level])
                    $php_stmt .= '['.($multi_key[$level[$start_level]][$start_level]-1).']';
                $start_level++;
            }
            $add='';
            if (isset($multi_key[$x_tag][$x_level]) && $multi_key[$x_tag][$x_level] && ($x_type=='open' || $x_type=='complete')) {
                if (!isset($multi_key2[$x_tag][$x_level]))
                    $multi_key2[$x_tag][$x_level]=0;
                else
                    $multi_key2[$x_tag][$x_level]++;
                $add='['.$multi_key2[$x_tag][$x_level].']';
            }
            if (isset($xml_elem['value']) && trim($xml_elem['value'])!='' && !array_key_exists('attributes', $xml_elem)) {
                if ($x_type == 'open')
                    $php_stmt_main=$php_stmt.'[$x_type]'.$add.'[\'content\'] = $xml_elem[\'value\'];';
                else
                    $php_stmt_main=$php_stmt.'[$x_tag]'.$add.' = $xml_elem[\'value\'];';
                eval($php_stmt_main);
            }
            if (array_key_exists('attributes', $xml_elem)) {
                if (isset($xml_elem['value'])) {
                    $php_stmt_main=$php_stmt.'[$x_tag]'.$add.'[\'content\'] = $xml_elem[\'value\'];';
                    eval($php_stmt_main);
                }
                foreach ($xml_elem['attributes'] as $key=>$value) {
                    $php_stmt_att=$php_stmt.'[$x_tag]'.$add.'[$key] = $value;';
                    eval($php_stmt_att);
                }
            }
        }
        return $xml_array;
    }



}
