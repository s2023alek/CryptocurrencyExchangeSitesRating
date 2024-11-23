@extends('layouts.general')

@section('head')

@endsection


@section('content')
    <!--
    <script type="text/javascript">
        baseUrl = "https://widgets.cryptocompare.com/";
        var scripts = document.getElementsByTagName("script");
        var embedder = scripts[ scripts.length - 1 ];
        var cccTheme = {"General":{"borderWidth":"1px","showExport":true},"Tabs":{"activeBorderColor":"#ffa100"}};
        (function (){
            var appName = "rucoinmarketcap.com";
            if(appName==""){appName="local";}
            var s = document.createElement("script");
            s.type = "text/javascript";
            s.async = true;
            var theUrl = baseUrl+"serve/v3/coin/chart?fsym=BTC&tsyms=USD,EUR,RUB";
            s.src = theUrl + ( theUrl.indexOf("?") >= 0 ? "&" : "?") + "app=" + appName;
            embedder.parentNode.appendChild(s);
        })();
    </script>
-->


<h1>Курсы криптовалют</h1>
<h3>На этом сайте можно узнать курсы, состояние криптовалют и бирж</h3>

Например:
Валюта <a href="/coin/bitcoin">Bitcoin</a>
Биржа <a href="/exchange/bitcoin-com">Bitcoin.com</a>

<div style="margin-bottom: 30px; width: 100%;"></div>


@endsection
