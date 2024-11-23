let prepared = false;

function prepare() {
    if (prepared) {
        return;
    }
    prepared = true;


    l("start");
    prepareData();
}

/// DATA
function prepareData() {
    loadSitePublicSettings();
}

function processSitePublicSettings(d) {
    sitePublicSettings = d
}
function getSiteSetting(settingName) {
    for (const i in sitePublicSettings) {
        const a = sitePublicSettings[i];
        if(a.hasOwnProperty(settingName)){
            return a[settingName];
        }
    }
    return null;
}


function getUrlsForUrlPTitle(urlPTitle) {
    for (const i in refsList) {
        let el = refsList[i]
        if (el.urlPTitle == urlPTitle) {
            return el
        }
    }
    return null
}

function getUrlsForUrlPTitle(urlPTitle) {
    for (const i in refsList) {
        let el = refsList[i]
        if (el.urlPTitle == urlPTitle) {
            return el
        }
    }
    return null
}


let sitePublicSettings = null;
let refsList = [];


/// NET

function loadSitePublicSettings() {
    $.ajax({
        url: '/sitePublicSettings.json?r=' + new Date().getTime(), type: 'GET', contentType: 'json',
        success: function (d) {
            l('sitePublicSettings loaded');
            processSitePublicSettings(d);
            loadRefsList();
        },
        error: function (d) {
            l('error loading sitePublicSettings');
            setTimeout(loadSitePublicSettings(), 1000);
        }
    });
}

function loadRefsList() {
    $.ajax({
        url: '/refs.json?r='+new Date().getTime(), type: 'GET', contentType: 'json',
        success: function (d) {
            l('refs loaded');
            refsList = d;
            refreshButtons();
            prepareTable();
        },
        error: function (d) {
            l('error loading refs');
            setTimeout(loadRefsList(), 1000);
        }
    });
}

function refreshButtons(){
    const dbr = getSiteSetting("exchangeInfoDefaultBuyRef")
    const dsr = getSiteSetting("exchangeInfoDefaultSellRef")
    const der = getSiteSetting("exchangeInfoDefaultExchangeRef")

    let urls = getUrlsForUrlPTitle(urlPTitle)
    if (urls === null) {
        //default urls
        urls = {b: dbr, ex: der, bb: dsr}
    }

    //todo: ? separate ref link for official website ?
    $('#cardBVisitOfficialWebSite').attr('href', (urls.b.length > 1) ? urls.b : dbr)

    $('tr.anExchange').each(function () {
        el = $(this)

        el.find('[data-action="b"]').attr('href', (urls.b.length > 1) ? urls.b : dbr)
        el.find('[data-action="ex"]').attr('href', (urls.ex.length > 1) ? urls.ex : der)
        el.find('[data-action="bb"]').attr('href', (urls.bb.length > 1) ? urls.bb : dsr)

    })
}


function prepareTable() {
    new TableActions(".bottom-table", {
        sortable: true,
        searchable: false,
        paginable: "buttons",
        rowsPerPage: 20
    });
}

//log

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

