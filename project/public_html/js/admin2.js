let prepared = false;

function prepare() {
    if (prepared) {
        return;
    }
    prepared = true;


    l("start");
    prepareControl();
    prepareData();
    prepareView();
    startApp();
}

function prepareControl() {
    $('#saveCommonRefs').click(elBSaveSettings);

    $('#saveRefs').click(elBSaveRefs);
    $('#nextPage').click(nextPage);
    $('#prevPage').click(prevPage);
    $('#gotoPage').click(elBGotoPage);
}

function startApp() {
    gotoPage(0);
}

// UI
function elBSaveSettings() {
    netSaveSettings(dataGenerateSettings());
}

function elBGotoPage() {
    gotoPage($('#pageNumber').val());
}

function elBSaveRefs() {
    saveCurrentPage();
    let d = dataGenerateRefs();
    if (d === null) {
        return;
    }
    uiDisplayMsg(TEXT_OP_SAVE_PREFS_IN_PROGRESS + '<br>' + TEXT_PLEASE_WAIT, true);
    netSaveRefs(d);

}

// DATA
let totalPages = 0;
let currentPage = 0;
let prevRefsList = [];

function prepareData() {
    /*let refsList = [ {t:"$a->title" ,u:"$a->urlPTitle" ,b:"$a->b" ,ex:"$a->ex" ,bb:"$a->bb"} ];*/
    copyRefsListToPrev();
    totalPages = calcNumPages();
}

function copyRefsListToPrev() {
    for (const i in refsList) {
        const a = refsList[i];
        prevRefsList[i] = {
            't': a['t']
            ,'i': a['i']
            , 'u': a['u']
            , 'b': a['b']
            , 'ex': a['ex']
            , 'bb': a['bb']
        }
    }
}

function gotoPage(a) {
    saveCurrentPage();
    currentPage = Math.min(totalPages, Math.max(0, a));
    loadCurrentPage();
    uiUpdatePaginator();
}

function nextPage() {
    saveCurrentPage();
    currentPage += 1;
    if (currentPage > totalPages - 1) {
        currentPage = totalPages - 1;
        return;
    }

    loadCurrentPage();
    uiUpdatePaginator();
}

function prevPage() {
    saveCurrentPage();
    currentPage -= 1;

    if (currentPage < 0) {
        currentPage = 0;
        return;
    }

    loadCurrentPage();
    uiUpdatePaginator();
}

function saveCurrentPage() {
    let a1 = $('.ref-coin');
    for (let ii = 0; ii < a1.length; ii++) {
        let ell = a1.eq(ii);
        let u = ell.attr('data-url-p-title');

        for (const i in refsList) {
            if (refsList[i]['u'] === u) {
                let a2 = ell.find('.input-ref');
                for (let i2 = 0; i2 < a2.length; i2++) {
                    let el = a2.eq(i2);
                    let elVal = el.val();
                    let elValName = el.attr('data-type');
                    refsList[i][elValName] = elVal;
                }
            }
        }
    }

}

function loadCurrentPage() {
    if (currentPage < 0) {
        return;
    }

    let a1 = $('.ref-coin');
    let lll = refsList.length - 1;
    for (let ii = 0; ii < a1.length; ii++) {
        let ell = a1.eq(ii);
        let uiii = currentPage * pageSize + ii;

        if (uiii < lll) {

            let u = refsList[uiii]['u'];
            ell.attr('data-url-p-title', u);
            ell.css('display', 'table-row');
            for (const i in refsList) {
                if (refsList[i]['u'] === u) {
                    let a2 = ell.find('.input-ref');
                    for (let i2 = 0; i2 < a2.length; i2++) {
                        let el = a2.eq(i2);
                        let elValName = el.attr('data-type');
                        el.val(refsList[i][elValName]);
                    }
                    a2 = ell.find('.href-ref');
                    for (i2 = 0; i2 < a2.length; i2++) {
                        let el = a2.eq(i2);
                        let elValName = el.attr('data-type');
                        el.attr('href', '/exchange/' + refsList[i][elValName]);
                    }
                    a2 = ell.find('.td-ref');
                    for (i2 = 0; i2 < a2.length; i2++) {
                        let el = a2.eq(i2);
                        let elValName = el.attr('data-type');
                        el.html(refsList[i][elValName]);
                    }
                    a2 = ell.find('.img-ref');
                    for (i2 = 0; i2 < a2.length; i2++) {
                        let el = a2.eq(i2);
                        let elValName = el.attr('data-type');
                        el.attr('src', refsList[i][elValName]);
                    }
                }
            }
        } else {
            ell.css('display', 'none');
        }
    }
}

const pageSize = 50;

function calcNumPages() {
    return ((refsList.length - refsList.length % pageSize) / pageSize) + 1;
}

function dataCompareRefs(a, b) {
    return a['t'] === b['t'] && a['u'] === b['u'] && a['b'] === b['b'] && a['ex'] === b['ex'] && a['bb'] === b['bb'];
}

function dataGenerateRefs() {
    /*
    let a1 = $('.ref-coin');
    for (let ii = 0; ii < a1.length; ii++) {
        let ell = a1.eq(ii);
        let a = {u: ell.attr('data-url-p-title')};

        let a2 = ell.find('.input-ref');
        let dataChanged = false;
        for (let i = 0; i < a2.length; i++) {
            let el = a2.eq(i);

            let elVal = el.val();
            //todo: fix this
            if (elVal !== el.attr('iv')) {
                a[el.attr('data-type')] = elVal;
                dataChanged = true;
            }
        }
        if (dataChanged) {
            d.push(a);
        }
    }*/

    let d = [];
    for (const i in refsList) {
        const ce = refsList[i];
        const pe = prevRefsList[i];
        if (ce['u'] !== pe['u']) {
            l('err!')
        }
        if (!dataCompareRefs(ce, pe)) {
            d.push(ce);
        }
    }

    l('# of modified refs:' + d.length);

    if (d.length < 1) {
        return null;
    }
    return d;
}



// DATA

function dataGenerateSettings() {
    return {
        "coinPageCoinInfoBuyRef": $('#coinPageCoinInfoBuyRef').val(),
        "coinPageCoinInfoSellRef": $('#coinPageCoinInfoSellRef').val(),
        "coinPageCoinInfoExchangeRef": $('#coinPageCoinInfoExchangeRef').val(),

        "coinPageExchangesListBuyRef": $('#coinPageExchangesListBuyRef').val(),
        "coinPageExchangesListSellRef": $('#coinPageExchangesListSellRef').val(),
        "coinPageExchangesListExchangeRef": $('#coinPageExchangesListExchangeRef').val(),

        "exchangeInfoDefaultBuyRef": $('#exchangeInfoDefaultBuyRef').val(),
        "exchangeInfoDefaultSellRef": $('#exchangeInfoDefaultSellRef').val(),
        "exchangeInfoDefaultExchangeRef": $('#exchangeInfoDefaultExchangeRef').val()
    };
}


// NETWORK
function netSaveSettings(d) {
    ajax(API_SITE_SETTINGS, API_POST, d, function (r) {
        elNet(API_SITE_SETTINGS, r);
    }, function () {
        elNet(API_SITE_SETTINGS, null);
    });
}

function netSaveRefs(d) {
    ajax(API_REFS, API_POST, d, function (r) {
        elNet(API_REFS, r);
        copyRefsListToPrev();
    }, function () {
        elNet(API_REFS, null);
    });
}

// NETWORK EVENT LISTENERS

function elNet(apiMethod, response) {
    let s = false;
    if (response !== null) {
        s = response.resultCode == 0;
    }

    switch (apiMethod) {

        case API_REFS:
            uiDisplayMsg(uiMsgTextOpResult(TEXT_OP_SAVE_PREFS, s), s);
            break;

        case API_SITE_SETTINGS:
            uiDisplayMsg(uiMsgTextOpResult(TEXT_OP_SAVE_SITE_SETTINGS, s), s);
            break;

    }
    //l(r, true);
}



// VIEW
function prepareView() {
    uiUpdatePaginator();
}


function uiUpdatePaginator() {
    $('#currentPage').text(currentPage);
    $('#totalPages').text(totalPages - 1);
    $('#pageNumber').attr('max', totalPages - 1)
}


function uiDisplayMsg(msg, success = null) {
    l('uiDisplayMsg > ' + msg + '/' + success);
    //if (success!==null){color = (success)?UI_COLOR_GREEN:UI_COLOR_RED;}
    alertify.set({delay: 7000});
    if (success === true) {
        alertify.success(msg);
    } else if (success === false) {
        alertify.error(msg);
    } else {
        alertify.alert(msg);
    }
}

/*
const UI_COLOR_RED = 'red';
const UI_COLOR_GREEN = 'green';
const UI_COLOR_GREY = 'grey';
*/

/**
 * uiMsgTextOpResult(TEXT_OP_SAVE_PREFS, false) returns "сохранение партнерских ссылок: ошибка"
 * @param result true - success, false - failure
 */
function uiMsgTextOpResult(opTitle, result) {
    opTitle += ': ';
    opTitle += (result) ? TEXT_SUCCESS : TEXT_FAILURE;
    return opTitle;
}

/**
 * partner references
 */
const TEXT_OP_SAVE_PREFS = "Сохранение партнерских ссылок";
const TEXT_OP_SAVE_PREFS_IN_PROGRESS = "Выполняется сохранение партнерских ссылок...";


const TEXT_OP_SAVE_SITE_SETTINGS = "Сохранение настроек сайта";

const TEXT_SUCCESS = "успешно";
const TEXT_FAILURE = "ошибка";

const TEXT_PLEASE_WAIT = "Пожалуйста подождите";


// END OF VIEW


function ajax(url, method, data, success, error = null, contentType = "json") {
    $.ajax({
        url: url, type: method, contentType: "json",
        data: JSON.stringify(data),
        success: function (d) {
            success(JSON.parse(d));
        },
        error: error
    });
}

// OPERATIONS

const API_POST = "POST";
const API_GET = "GET";
const API_DELETE = "DELETE";

const API = "/api/"
const API_REFS = API + "refs"
const API_SITE_SETTINGS = API + "siteSettings"


function l(a, stringify) {
    if (stringify) {
        a = JSON.stringify(a);
    }
    console.log(a);
}


/////////////////////////////// start app
$(function () {
    prepare();
});

/*
setTimeout(function () {
    prepare();
}, 1000);*/
