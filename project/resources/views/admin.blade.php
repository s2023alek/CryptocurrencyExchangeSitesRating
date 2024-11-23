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

        .section-parts {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: row;
            justify-content: space-around;
        }

        .section-part {
            border-style: dotted;
            border-right-style: dotted;
            border-bottom-style: dotted;
            padding: 5px;
            border-color: #24bd1f;
            border-right-color: rgb(36, 189, 31);
            border-bottom-color: rgb(36, 189, 31);
            border-radius: 10px;
            margin: 5px;
            box-shadow: #26cc8e 3px 3px;
            border-right: none;
            border-bottom: none;
        }

        .input-ref {
            width: 95%;
            padding: 2px 2px 0px 0px;
            background-color: rgba(0, 0, 0, 0);
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
    </style>
    <!-- Scripts -->
    <script src="{{ asset('js/jquery-3.6.4.min.js')}}"></script>

    <script src="{{ asset('/modules/alertify/alertify.js')}}"></script>
    <link rel="stylesheet" href="{{ asset('/modules/alertify/alertify.css')}}" type="text/css">

    <script src="{{ asset('js/admin.js?v=0')}}"></script>

</head>
<body class="antialiased">


<div class="section">
    <div class="section-parts">

        <div class="section-part">
            <h2 style="color:red;">Initial</h2>
            <p style="color: red;">
                <em>Начальный набор данных полученный с помощью полуручного парсинга, необходимо загрузить в первую очередь после установки сайта на хостинг</em>
            </p>

            <ol>
                <li>
                    <label for="caeinfo">Загрузите файл CoinAndExchangeInfo.json</label>
                    <input type="file" id="caeinfo" name="files[]" accept="*" size=30>
                </li>
            </ol>
        </div>


        <div class="section-part">
            <h2>SEO</h2>
            Необходимо раз в неделю или в месяц обновлять список валют и бирж чтобы сайт мог сообщать поисковому роботу актуальную информацию

            <ol>
                <li>Используйте инструмент
                    <a target="_blank" href="http://www.check-domains.com/sitemap/">www.check-domains.com/sitemap/</a>
                </li>
                <li>Сгенерируйте карту сайта https://rucoinmarketcap.com</li>
                <li>
                    <label for="sitemap">Загрузите полученную карту (файл sitemap.xml)</label>
                    <input type="file" id="sitemap" name="files[]" accept="*" size=30>
                </li>
            </ol>
        </div>

    </div>
</div>


<div class="section">
    <div class="section-parts">

        <div class="section-part">
            <h2>Кеш</h2>
            <h3>Кеш данных обновляется после:</h3>
            Валюты(минут): <input id="cacheCoins" value="{{$cacheExpiryTimeCoins}}"><br>
            Биржи(минут): <input id="cacheExchange" value="{{$cacheExpiryTimeExchanges}}"><br>
            <button id="saveCacheSettings">Сохранить настройки</button>

            <h3>Очистить кеш данных</h3>
            <button id="clearCacheCoins">Очистить кеш данных валют</button>
            <button id="clearCacheExchanges">Очистить кеш данных бирж</button>
        </div>


        <div class="section-part">
            <h2>Реферальные ссылки</h2>
            <a target="_blank" href="/admin2">
                <button>Открыть редактор реферальных ссылок</button>
            </a>
        </div>

        <div class="section-part">
            <h2>Метаданные страниц</h2>
            <a target="_blank" href="/admin-meta">
                <button>Открыть редактор метаданных</button>
            </a>
        </div>

    </div>
</div>

</body>
</html>
