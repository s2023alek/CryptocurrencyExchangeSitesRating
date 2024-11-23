<!DOCTYPE html>
<html lang="<li>{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>admin</title>

    <!-- Fonts -->

    <!-- Styles -->
    <style>
        h1, h2 {
            text-align: center;
        }

        button {
            padding: 5px;
            margin: 3px;
            size: 2em;
        }

        .section {
            border-style: dashed;
            padding: 5px;
            border-color: #0f227e;
            border-radius: 10px;
            border-width: 2px;
            margin: 15px;
            box-shadow: #6db3f0 3px 3px;
            border-right: none;
            border-bottom: none;
        }

        .input-ref {
            width: 95%;
            padding: 2px 2px 0px 0px;
            background-color: rgba(0, 0, 0, 0);
        }

        .table-tools-panel {
            display: flex;
            justify-content: space-around;
            align-items: center;
            background-color: #96b1d5;
            border-radius: 10px;
            padding: 5px 0;
        }

        .table-tools-panel-page-num {
            background-color: #ececec;
            border-radius: 10px;
            padding: 0 5px 0 5px;
        }

        #pageNumber {
            width: 4em;
        }

        table {
            width: 100%;
        }

        table td {
            border-width: 1px 1px 0 0;
            border-style: solid;
        }

        table tr:nth-child(2n) {
            background-color: #e8e7e7;
        }

        .common-refs input {
            width: 70%;
            margin-bottom: .7em;
        }
    </style>
    <!-- Scripts -->
    <script src="{{ asset('js/jquery-3.6.4.min.js')}}"></script>
    <script src="{{ asset('/modules/alertify/alertify.js')}}"></script>
    <link rel="stylesheet" href="{{ asset('/modules/alertify/alertify.css')}}" type="text/css">
    <script src="{{ asset('js/admin2.js?v=0')}}"></script>

</head>
<body class="antialiased">


<div class="section common-refs">
    <h2>Реферальные ссылки по умолчанию</h2>

    <h3>Страница валюты</h3>

    <p><a href="/coin/bitcoin" target="_blank">пример страницы</a><br></p>

    Установить партнерские ссылки для карточки (любой) валюты:

    <ol style="width: 90%;">
        <li>Покупка валюты:
            <input type="text" type="text" id="coinPageCoinInfoBuyRef" value="{{$coinPageCoinInfoBuyRef}}"></li>
        <li>Продажа валюты: <input type="text" id="coinPageCoinInfoSellRef" value="{{$coinPageCoinInfoSellRef}}"></li>
        <li>Обмен валюты: <input type="text" id="coinPageCoinInfoExchangeRef" value="{{$coinPageCoinInfoExchangeRef}}">
        </li>
    </ol>

    Под карточкой валюты есть список бирж, в нем есть кнопки покупки / продажи / обмена. Они имеют свои партнерские ссылки, их можно указывать для каждой биржи - на этой странице их можно указать ниже в следующем разделе настроек. Но если они еще не были указаны (например новая биржа добавилась недавно), то, по умолчанию, использовать эти ссылки:
    <ol>
        <li>Покупка валюты:
            <input type="text" id="coinPageExchangesListBuyRef" value="{{$coinPageExchangesListBuyRef}}">
        </li>
        <li>Продажа валюты:
            <input type="text" id="coinPageExchangesListSellRef" value="{{$coinPageExchangesListSellRef}}">
        </li>
        <li>Обмен валюты:
            <input type="text" id="coinPageExchangesListExchangeRef" value="{{$coinPageExchangesListExchangeRef}}"></li>
    </ol>

    <h3>Страница биржи</h3>

    <p><a href="/exchange/crypto" target="_blank">пример страницы</a><br></p>

    В случае когда для биржи не указаны партнерские ссылки (например биржа была добавлена недавно), использовать эти партнерские ссылки:
    <ol style="width: 90%;">
        <li>Покупка валюты: <input type="text" id="exchangeInfoDefaultBuyRef" value="{{$exchangeInfoDefaultBuyRef}}">
        </li>
        <li>Продажа валюты: <input type="text" id="exchangeInfoDefaultSellRef" value="{{$exchangeInfoDefaultSellRef}}">
        </li>
        <li>Обмен валюты:
            <input type="text" id="exchangeInfoDefaultExchangeRef" value="{{$exchangeInfoDefaultExchangeRef}}"></li>
    </ol>

    <button id="saveCommonRefs">Сохранить настройки</button>

</div>

<div class="section">
    <h2>Реферальные ссылки для конкретных бирж</h2>
    <em>Если вы используете firefox, открывайте это окно в новой вкладке после сохранения, чтобы таблица отображала актуальные данные. Не обновляйте страницу(особенность работы firefox с формами)</em>

    <br><br>

    <!-- ПАНЕЛЬ ПАГИНАТОР -->
    <div class="table-tools-panel">
        <button id="saveRefs">Сохранить ссылки</button>
        <button id="prevPage">&lt;- Пред страница</button>
        <div class="table-tools-panel-page-num">&nbsp;Страница&nbsp;<span id="currentPage"></span>&nbsp;из&nbsp;<span id="totalPages">&nbsp;</span>
            <button id="gotoPage">Перейти на страницу:</button>
            <input type="number" id="pageNumber" value="1" min="1" max="999999999999999">
        </div>
        <button id="nextPage">След страница -&gt;</button>
    </div>

    <br>

    <table>
        <thead>
        <tr>
            <th>Название<br>биржи</th>
            <th>Страница<br>биржи</th>
            <th>url кнопка купить</th>
            <th>url кнопка обменять</th>
            <th>url кнопка поставить</th>
        </tr>
        </thead>
        <tbody>
        <?php $nPage = 0;?>
        @foreach ($refs as $a)
            <?php $nPage += 1;?>

            @if($nPage > 50)
                @break
            @endif

            <tr class="ref-coin" data-url-p-title="{{$a->urlPTitle}}">
                <td><img class="img-ref" data-type="i" src="{{$a->imageUrl}}">
                    <span class="td-ref" data-type="t">{{$a->title}}</span></td>
                <td><a target="_blank" class="href-ref" data-type="u" href="/coin/{{$a->urlPTitle}}">Перейти</a></td>
                <td><input class="input-ref" type="text" data-type="b" value="{{$a->b}}"></td>
                <td><input class="input-ref" type="text" data-type="ex" value="{{$a->ex}}"></td>
                <td><input class="input-ref" type="text" data-type="bb" value="{{$a->bb}}"></td>
            </tr>
        @endforeach

        </tbody>
    </table>


    </a>
</div>

<script type="text/javascript">
    let refsList = [
        <?php foreach ($refs as $a) {
        echo '{t:"' . $a->title . '"';
        echo ',u:"' . $a->urlPTitle . '"';
        echo ',i:"' . $a->imageUrl . '"';
        echo ',b:"' . $a->b . '"';
        echo ',ex:"' . $a->ex . '"';
        echo ',bb:"' . $a->bb . '"}, ';
    }?>];

</script>

</body>
</html>
