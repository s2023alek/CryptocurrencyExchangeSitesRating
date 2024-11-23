let prepared = false

function prepare() {
    if (prepared) {
        return
    }
    prepared = true


    l("start")
    prepareData()
    prepareControl()
    prepareView()
    startApp()
}

function prepareControl() {
    $('.save-data').click(elBSave)
}

function startApp() {
}

// UI

function elBSave() {
    uiDisplayMsg(TEXT_OP_SAVE_MDS_IN_PROGRESS + '<br>' + TEXT_PLEASE_WAIT, true)
    let d = getListPageMeta()
    netSaveMetaItems(d)
}

// DATA

let helpTextForPages = [
    ['exchange', '%exchangeTitle% - название биржи']
    ,['coin', '%coinTitle% - название валюты\n %coinCode% - код валюты']
    ,['coin-does-not-exist', '%coinTitle% - название валюты']
    ,['exchange-does-not-exist', '%exchangeTitle% - название биржы']
]

function prepareData() {
    let pagesList = []
    let namesList = []
    for (const i in pageMetaList) {
        let el = pageMetaList[i]
        if (pagesList.indexOf(el.p) == -1) {
            pagesList.push(el.p)
        }
        if (namesList.indexOf(el.n) == -1) {
            namesList.push(el.n)
        }
    }


    for (const i in pagesList) {
        let page = pagesList[i]
        let elPM = getPageMetaWithHelpText(page, namesList, pageMetaList, helpTextForPages)
        let mt = []
        for (const iii in elPM.meta) {
            let metaItem = elPM.meta[iii]
            mt.push([metaItem.name, metaItem.value])
        }
        mt.sort(sortCompareFunctionForMetaItems)

        pagesDataList.push({
            page: elPM.page
            ,help: elPM.help.split('\n').join('<br>')
            ,meta: mt
        })

    }

    pagesDataList.sort(sortCompareFunctionForPages)

}

function sortCompareFunctionForMetaItems (a, b) {
    var textA = a[0].toUpperCase();
    var textB = b[0].toUpperCase();
    return (textA < textB) ? -1 : (textA > textB) ? 1 : 0;
}
function sortCompareFunctionForPages (a, b) {
    var textA = a.page.toUpperCase();
    var textB = b.page.toUpperCase();
    return (textA < textB) ? -1 : (textA > textB) ? 1 : 0;
}

function getPageMetaWithHelpText(page, namesList, pageMetaList, helpTextForPages) {
    let helpText = ''//TEXT_NO_HELP_AVAILABLE
    for (const iii in helpTextForPages) {
        if (helpTextForPages[iii][0] == page) {
            helpText = helpTextForPages[iii][1]
            break
        }
    }

    let result = {page: page, help:helpText, meta: []}

    for (const i in pageMetaList) {
        let elPM = pageMetaList[i]
        for (const ii in namesList) {
            let name = namesList[ii]
            if (page == elPM.p && name == elPM.n) {
                result.meta.push({name: name, value: elPM.t})
            }
        }
    }
    return result
}


function dataCompareRefs(a, b) {
    return a['t'] === b['t'] && a['u'] === b['u'] && a['b'] === b['b'] && a['ex'] === b['ex'] && a['bb'] === b['bb']
}

function dataGenerateRefs() {
    let d = [];
    for (const i in refsList) {
        const ce = refsList[i]
        const pe = prevRefsList[i]
        if (ce['u'] !== pe['u']) {
            l('err!')
        }
        if (!dataCompareRefs(ce, pe)) {
            d.push(ce)
        }
    }

    l('# of modified refs:' + d.length)

    if (d.length < 1) {
        return null
    }
    return d
}

function getListPageMeta(){
    let result = []
    let els = $('#container-list').find('input.meta-text').each(function() {
        let t = $(this)
        //l(t.attr('data-page')+'/'+t.attr('data-name')+'/'+t.val())
        result.push({p:t.attr('data-page'), n:t.attr('data-name'), t:t.val()})
    })
    return result
}

let pagesDataList = []

// NETWORK

function netSaveMetaItem(d) {
    ajax(API_MDS, API_POST, d, function (r) {
        elNet(API_MDS, r)
    }, function () {
        elNet(API_MDS, null)
    })
}

function netSaveMetaItems(d) {
    ajax(API_MDSL, API_POST, d, function (r) {
        elNet(API_MDS, r)
    }, function () {
        elNet(API_MDS, null)
    })
}

// NETWORK EVENT LISTENERS

function elNet(apiMethod, response) {
    let s = false
    if (response !== null) {
        s = response.resultCode == 0
    }

    switch (apiMethod) {

        case API_MDS:
            uiDisplayMsg(uiMsgTextOpResult(TEXT_OP_SAVE_MDS, s), s)
            break;

    }
    //l(r, true)
}


// VIEW
function prepareView() {
    //pagesDataList {page:"page-name" ,help:"help" , meta: [[title, "a title"], ... ]}

    let uiElementPageTemplate = $('#pageItem').html()
    let uiElementMetaElementTemplate = $('#pageMetaItem').html()

    let pageMetaList = []

    for (const i in pagesDataList) {
        let pd = pagesDataList[i]

        let uiElementPage = uiElementPageTemplate
        uiElementPage = uiElementPage.split('%pageName%').join(pd.page)
            .split('%helpText%').join(pd.help)

        let metaElementsList = []
        for (const ii in pd.meta) {
            let metaItem = pd.meta[ii]
            metaElementsList.push(
                uiElementMetaElementTemplate.split('%metaItemName%').join(metaItem[0])
                    .split('%metaItemValue%').join(metaItem[1])
                    .split('%pageName%').join(pd.page)
            )
        }
        uiElementPage = uiElementPage.split('%metaElementsList%').join(metaElementsList.join('\n'))
        pageMetaList.push(uiElementPage)
    }

    $('#container-list').html(pageMetaList.join(''))
}

function savePageMetaItem(target) {
    let inp = $(target).parent().find('input')
    let a = {
        page:inp.attr('data-page')
        ,name:inp.attr('data-name')
        ,text:inp.val()
    }
    //l(l(a,true))
    netSaveMetaItem(a)

}

function uiDisplayMsg(msg, success = null) {
    l('uiDisplayMsg > ' + msg + '/' + success)
    //if (success!==null){color = (success)?UI_COLOR_GREEN:UI_COLOR_RED;}
    alertify.set({delay: 7000})
    if (success === true) {
        alertify.success(msg)
    } else if (success === false) {
        alertify.error(msg)
    } else {
        alertify.alert(msg)
    }
}

/**
 * uiMsgTextOpResult(TEXT_OP_SAVE_PREFS, false) returns "сохранение партнерских ссылок: ошибка"
 * @param result true - success, false - failure
 */
function uiMsgTextOpResult(opTitle, result) {
    opTitle += ': '
    opTitle += (result) ? TEXT_SUCCESS : TEXT_FAILURE
    return opTitle
}

/**
 * partner references
 */
const TEXT_OP_SAVE_MDS = "Сохранение настроек метаданных"
const TEXT_OP_SAVE_MDS_IN_PROGRESS = "Выполняется сохранение настроек метаданных..."

const TEXT_SUCCESS = "успешно"
const TEXT_FAILURE = "ошибка"

const TEXT_PLEASE_WAIT = "Пожалуйста подождите"

const TEXT_NO_HELP_AVAILABLE = 'подсказка по этой странице отсутствует'


// END OF VIEW


function ajax(url, method, data, success, error = null, contentType = "json") {
    $.ajax({
        url: url, type: method, contentType: "json",
        data: JSON.stringify(data),
        success: function (d) {
            success(JSON.parse(d))
        },
        error: error
    });
}

// OPERATIONS

const API_POST = "POST"
const API_GET = "GET"
const API_DELETE = "DELETE"

const API = "/api/"
const API_MDS = API + "meta-data-item"
const API_MDSL = API + "meta-data"


function l(a, stringify) {
    if (stringify) {
        a = JSON.stringify(a)
    }
    console.log(a)
}


/////////////////////////////// start app
$(function () {
    prepare()
});
