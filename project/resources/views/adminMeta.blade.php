<!DOCTYPE html>
<html lang="<li>{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>admin metadata</title>

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

        .meta-list {
            background-color: #eee;
        }

        .meta-for-a-page {
            margin: 5px;
            padding: 5px;
            border: #1812cc;
            border-style: solid;
            border-radius: 10px;
            border-width: 1px;
            background-color: white;
            display: flex;
            align-content: center;
            flex-direction: column;
            box-shadow: #d1dae1 2px 2px;
            margin-bottom: 10px;
            background-color: aliceblue;
        }

        .meta-for-a-page-name {
            background-color: #5c53bf;
            color: #fdffff;
            padding: 2px;
            border-radius: 5px;
            display: inline;
            align-self: center;
        }

        .meta-for-a-page-type {
            background-color: #996504;
            color: #fdffff;
            float: left;
            padding: 2px;
            border-radius: 5px;
        }

        .meta-for-a-page-cnt {
            margin-top: .8em;
        }

        #container-list {
            display: flex;
            flex-direction: column;
        }

        .meta-for-a-page-cnt-type {
            border-color: blueviolet;
            border-radius: 10px;
            padding: 3px;
            margin-top: 5px;
            border-width: 1px !important;
            border: solid;
            box-shadow: #d1dae1 2px 2px;
            background-color: white;
        }

        input.meta-text {
            width: 90%;
        }

        .meta-text-save {
            width: 8%;
        }

    </style>
    <!-- Scripts -->
    <script src="{{ asset('js/jquery-3.6.4.min.js')}}"></script>
    <script src="{{ asset('/modules/alertify/alertify.js')}}"></script>
    <link rel="stylesheet" href="{{ asset('/modules/alertify/alertify.css')}}" type="text/css">
    <script src="{{ asset('js/adminMeta.js?v=0')}}"></script>

</head>
<body class="antialiased">


<div class="section meta-list">
    <h2>Метаданные для страниц сайта</h2>

    <button class="save-data meta-text-save">Сохранить&nbsp;все</button>
    <br>


    <div id="container-list"></div>

    <div style="display: none">

        <div id="pageItem" data-id="%pageName%">
            <div class="meta-for-a-page">
                <div class="meta-for-a-page-name">
                    Страница: <b>%pageName%</b>
                </div>

                <div class="legend">%helpText%</div>

                <div class="page-meta-elements-list">%metaElementsList%</div>
            </div>
        </div>

        <div id="pageMetaItem">
            <div class="meta-for-a-page-cnt-type">
                <div class="meta-for-a-page-type">
                    <b>%metaItemName%:</b>
                </div>
                <br>
                <div class="meta-for-a-page-cnt">
                    <input type="text" class="meta-text" type="text" data-page="%pageName%" data-name="%metaItemName%" value="%metaItemValue%">
                    <button onclick="savePageMetaItem(this);" class="meta-text-save">Сохранить</button>
                </div>
            </div>
        </div>
    </div>

    <button class="save-data meta-text-save">Сохранить&nbsp;все</button>

    <script type="text/javascript">
        let pageMetaList = [
            <?php foreach ($pageMetaList as $a) {
            echo '{p:"' . $a->page . '"';
            echo ',n:"' . $a->name . '"';
            echo ',t:"' . $a->text . '"';
            echo '}, ';
        }?>];
    </script>
</body>
</html>
