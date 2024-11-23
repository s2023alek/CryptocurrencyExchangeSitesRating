<?php

namespace App\Http\Controllers;

use App\Models\ACoinModel;
use App\Models\CoinRefUrlList;
use App\Models\ExchangeListModel;
use App\Models\PageMetaModel;
use App\Models\PageViewsCount;
use App\Parsers\CoinParser;
use App\Parsers\GenericParser;
use Illuminate\Http\Request;
use \App\Models\CoinModel;
use PhpParser\Error;

class APIMetaData extends Controller {


    /**
     * мета данные - один элемент
     * ACCEPT JSON: {"page":"home", "name":"title", "text":"some title text"}
     * ]
     *
     * RETURN: JSON: {
     * resultCode : 0-success; 1-error,
     * errorText : non-empty if error occured
     * }
     */
    public function saveSingle(Request $request) {

        try {
            $d = json_decode($request->getContent());
            PageMetaModel::s($d->page, $d->name, $d->text);

        } catch (Exception $e) {
            return json_encode((object)['resultCode' => '1', 'errorText' => $e]);
        }

        return json_encode((object)['resultCode' => '0']);
    }

    /**
     * мета данные - один элемент
     * ACCEPT JSON: [{"p":"home", "n":"title", "t":"some title text"}, ...]
     * ]
     *
     * RETURN: JSON: {
     * resultCode : 0-success; 1-error,
     * errorText : non-empty if error occured
     * }
     */
    public function saveList(Request $request) {

        try {
            $d = json_decode($request->getContent());

            foreach ($d as $i) {
                PageMetaModel::s($i->p, $i->n, $i->t);
            }

        } catch (Exception $e) {
            return json_encode((object)['resultCode' => '1', 'errorText' => $e]);
        }

        return json_encode((object)['resultCode' => '0']);
    }
}
