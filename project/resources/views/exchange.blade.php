@extends('layouts.general')

@section('head')

    <!-- Styles -->
    <style>
        .section-header {
            text-align: center;
            background-color: aliceblue;
            padding: 5px;
            border-radius: 10px;
            margin: 15px;
            box-shadow: #858ad2 0px 2px, #858ad2 0px -2px;
        }

        .b0 {
            padding: 10px;
            margin: 5px;
            size: 30px;
            width: 100%;
        }

        .coinInfoBar {
            display: flex;
            align-items: center;
            justify-content: space-evenly;
            flex-wrap: wrap;
        }

        .buttons-actions {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-evenly;
            margin: 5px;
            background-color: lightgray;
            padding: 9px;
            border-radius: 5px;
        }

        .buttons-actions-cnt {
            width: 100%;
            margin-right: 10px;
        }

        .coin-bar-part {
            list-style: none;
            margin: 5px;
            background-color: lightgray;
            padding: 9px;
            border-radius: 5px;
        }

        .coin-bar-part li {
            background-color: white;
            margin: 5px;
            padding: 5px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
        }
    </style>

    <!-- Scripts -->
    <script type="text/javascript">
        let urlPTitle = "<?php echo $exchangeInfo->urlPTitle;?>"
    </script>

    <!-- Modules -->
    <script src="{{ asset('/modules/table-actions/table-actions.min.js')}}"></script>
    <link rel="stylesheet" href="{{ asset('/modules/table-actions/table-actions.min.css')}}" type="text/css">

@endsection

@section('content')

    <h1><img src="{{$exchangeInfo->imageUrl}}"> {{$exchangeInfo->title}}</h1>

    <h3 class="section-header">Информация о бирже</h3>

    <div class="coinInfoBar">

        <ul class="coin-bar-part">
            @if ($exchangeInfo->np !== "NO_DATA")
                <li>
                    <span>Криптовалютные пары:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <span>{{$exchangeInfo->np}}</span>
                </li>
            @endif
            @if ($exchangeInfo->oUSD !== "NO_DATA")
                <li>
                    <span>Оборот(24ч)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <span>{{ join(" ", explode("!@#$^",$exchangeInfo->oUSD))}}$</span>
                </li>
            @endif
        </ul>

        <div class="buttons-actions">
            <a id="cardBVisitOfficialWebSite" href="TODO: add default ref url for each exchange" class="buttons-actions-cnt" target="_blank">
                <button class="b0">Перейти на сайт биржи</button>
            </a>
        </div>

    </div>

    @if($exchangePairs!==null && count($exchangePairs)>0)

        <h3 class="section-header">Активные пары</h3>

        <p>заметка разработчика - нужны иконки для кнопок купить продать обменять для списка бирж</p>
        <div class="table-container">
            <table class="bottom-table  sort-table ta ta-table-responsive-full">
                <thead>
                <tr>
                    <th>#</th>
                    <th data-unsortable>Информация</th>
                    <th data-unsortable>Действия</th>
                    <th>Валюта</th>
                    <th>Пара</th>
                    <th>Ставка</th>
                    <th>Цена USD</th>
                    <th>Оборот (24ч)</th>
                    <th>24ч</th>
                    <th>Сделки</th>
                </tr>
                </thead>
                <tbody>


                <?php $i = 1; ?>
                @foreach ($exchangePairs as $a)
                    <tr class="anExchange" data-url="{{$a->u}}">
                        <td>{{$i++}}</td>
                        <td><a target="_blank" href="/coin/{{$a->u}}">
                                <button>Открыть</button>
                            </a></td>
                        <td>
@spaceless
                            <a class="action-button" data-action="b" title="Купить" href="">
                                <button>К</button>
                            </a><span class="ws"></span>
                            <a class="action-button" data-action="ex" title="Обменять" href="">
                                <button>О</button>
                            </a><span class="ws"></span>
                            <a class="action-button" data-action="bb" title="Поставить" href="">
                                <button>П</button>
                            </a>
@endspaceless
                        </td>
                        <td><img class="eoc-logo-list" src="{{$a->imageUrl}}"> {{$a->ct}}</td>
                        <td>{{$a->pt}}</td>
                        <td>{{$a->s}}</td>
                        <td>$ {{$a->p}}</td>
                        <td>$ {{$a->o}}</td>
                        <td>{{$a->d}} %</td>
                        <td>{{$a->t}}</td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>

    @endif

@endsection



@section('footerScripts')

    <script src="{{ asset('js/exchange.js?v=0')}}"></script>

@endsection
