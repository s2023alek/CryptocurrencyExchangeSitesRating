@extends('layouts.general')

@section('head')

    <!-- Styles -->
    <style>

        table th:nth-child(1) {
            width: 2em;
        }
        table th:nth-child(2) {
            width: 8em;
            text-align: center !important;
        }
        table tr td:nth-of-type(2) {
            text-align: center;
        }

        .ta {
            max-width: 80%;
            width: 80%;
        }

        .section-header {
            text-align: center;
            background-color: aliceblue;
            padding: 5px;
            border-radius: 10px;
            box-shadow: #858ad2 0px 2px, #858ad2 0px -2px;
        }

        .coin-bar-part li {
            background-color: white;
            margin: 5px;
            padding: 5px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
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



        button {
            padding: 5px;
            margin: 3px;
            size: 2em;
        }

    </style>

    <!-- Scripts -->

    <!-- Modules -->
    <link rel="stylesheet" href="{{ asset('/modules/table-actions/table-actions.min.css')}}" type="text/css">

@endsection


@section('content')

    <h1 class="section-header">Список криптовалют</h1>

    @if($coinInfoList!==null && count($coinInfoList)>0)

        <table class="sort-table ta ta-table-responsive-full ">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Информация</th>
                    <th>Название</th>
                </tr>
                </thead>
                <tbody>

                @for($i=0;$i<20;$i++)
                    <tr class="ref-coin" data-url-sn="{{$i}}">
                        <td class="td-ref" data-type="sn">{{$i+1}}</td>
                        <td><a target="_blank" class="href-ref" data-type="url" href=""><button>Открыть</button></a>
                        <td><img class="img-ref eoc-logo-list" data-type="imageUrl" src="">
                            <span class="td-ref" data-type="title"></span></td>
                    </tr>
                @endfor

                </tbody>
            </table>

        <div class="ta-bottom-div">
            <button id="prevPage" class="ta-btn ta-btn-pag backward-page">&lt;</button>
            <div class="ta-paginable-pages"><span id="currentPage"></span>&nbsp;-&nbsp;<span id="totalPages"></div>
            <button id="nextPage" class="ta-btn ta-btn-pag forward-page">&gt;</button>
        </div>


    @endif

@endsection



@section('footerScripts')

    <script type="text/javascript">
        <?php
        echo 'let coinInfoList = [';
        $i = 1;
        foreach ($coinInfoList as $a) {
            //if ($i > 45) {break;}
            echo '{';
            echo "sn:'" . $i . "'";
            echo ", url:'" . $a->urlPTitle . "'";
            echo ", imageUrl:'" . $a->imageUrl . "'";
            echo ", title:'" . $a->title . "'";
            echo '},';
            $i += 1;
        }
        echo "];";
        ?>

    </script>

    <script src="{{ asset('js/coins.js?v=0')}}"></script>

@endsection
