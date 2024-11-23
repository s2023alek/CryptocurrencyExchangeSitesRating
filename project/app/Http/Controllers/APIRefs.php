<?php

namespace App\Http\Controllers;

use App\Models\ACoinModel;
use App\Models\CoinRefUrlList;
use App\Models\ExchangeListModel;
use App\Models\PageViewsCount;
use App\Parsers\CoinParser;
use App\Parsers\GenericParser;
use Illuminate\Http\Request;
use \App\Models\CoinModel;
use PhpParser\Error;

class APIRefs extends Controller {


    /**
     * Реферальные ссылки
     * ACCEPT JSON: [
     * {"u":"bitcoin","b":"www1","e":"www12","bb":"www13"},
     * {"u":"dogecoin","b":"www2","e":"www21","bb":"www24.a.com"}
     * ]// b-url кнопка купить; c-url кнопка обменять; bb-url кнопка поставить;
     *
     * RETURN: JSON: {
     * resultCode : 0-success; 1-error,
     * errorText : non-empty if error occured
     * }
     */
    public function save(Request $request) {
        //$refs = ACoinModel::query()->join('coin_ref_url_list', 'coin_ref_url_list.urlPTitle', '=', 'coins.urlPTitle')->get();
        try {
            $d = json_decode($request->getContent());

            foreach ($d as $i => $a) {
                $ep = new CoinRefUrlList();
                $ep->s($a->u, $a->b, $a->ex, $a->bb);
            }

            //refresh json
            $data = CoinRefUrlList::query()->get()->toJson();
            $fn = "./refs.json";
            if(file_exists($fn)) {
                unlink($fn);
            }
            file_put_contents($fn, $data, LOCK_EX);

        } catch (Exception $e) {
            return json_encode((object)['resultCode' => '1', 'errorText' => $e]);
        }

        return json_encode((object)['resultCode' => '0']);
    }

}
