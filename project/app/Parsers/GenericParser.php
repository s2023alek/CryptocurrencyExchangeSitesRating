<?php


namespace App\Parsers;


class GenericParser {

    /**
     * @param $url
     * @return String, null if failed
     */
    protected function loadData($url) {
        //$start = floor(microtime(true) * 1000);

        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $this->loadedData = curl_exec($handle);
        curl_close($handle);

        //$end = floor(microtime(true) * 1000);

        $this->stateCode = (strlen($this->loadedData) > 0) ? GenericParser::STATE_LOADING_SUCCEED : GenericParser::STATE_LOADING_FAILED;
    }

    protected $loadedData = null;


    /// RESULT CODE

    /**
     * @return int 0 if no error
     */
    public function getResultCode(){
        return $this->resultCode;
    }
    protected $resultCode = 1;

    const RESULT_SUCCEED = 0;
    const RESULT_FAILED = 1;


    /// STATE

    /**
     * @return int 0 if no error
     */
    public function getStateCode(){
        return $this->stateCode;
    }
    protected $stateCode = 0;

    const STATE_LOADING_SUCCEED = 1;
    const STATE_LOADING_FAILED = 2;
    const STATE_PARSING_SUCCEED = 3;
    const STATE_PARSING_FAILED = 4;

}
