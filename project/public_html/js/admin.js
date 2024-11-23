let prepared = false;

function prepare() {
    if (prepared) {
        return;
    }
    prepared = true;


    log("start");
    prepareControl();
}

function prepareControl() {
    $('#saveCacheSettings').click(elBSaveSettings);

    $('#clearCacheCoins').click(function () {
        elBClearCache(0);
    });
    $('#clearCacheExchanges').click(function () {
        elBClearCache(1);
    });

    prepareFileInput("sitemap", elProcessLoadedSitemapFileData);
    prepareFileInput("caeinfo", elProcessLoadedInitialFileData);
}


function prepareFileInput(inputElementID, eventListener) {
    function handleFileSelect(evt) {
        let files = evt.target.files; // FileList object
        // use the 1st file from the list
        let f = files[0];
        let reader = new FileReader();
        // Closure to capture the file information.
        reader.onload = (function(theFile) {
            return function(e) {
                eventListener(e.target.result);
            };
        })(f);
        reader.readAsText(f);
    }
    document.getElementById(inputElementID).addEventListener('change', handleFileSelect, false);
}


// UI

function elBSaveSettings() {
    netSaveSettings(dataGenerateSettings());
}

function elBClearCache(target) {
    netClearCache(target);
}

function elProcessLoadedSitemapFileData(data) {
    uiDisplayMsg(TEXT_UPLOAD_SITEMAP_DATA, true);
    netUploadSitemapData(data);
}

function elProcessLoadedInitialFileData(data) {
    uiDisplayMsg(TEXT_UPLOAD_INITIAL_DATA, true);
    netUploadInitialData(data);
}



// DATA

function dataGenerateSettings() {
    return {
        "scec": $('#cacheCoins').val(),
        "scee": $('#cacheExchange').val()
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

function netClearCache(target) {
    ajax(API_DATA_CACHE, API_DELETE, {target: target}, function (r) {
        elNet(OP_CLEAR_DATA_CACHE, r);
    }, function () {
        elNet(OP_CLEAR_DATA_CACHE, null);
    });
}

function netUploadSitemapData(data) {
    ajax(API_UPLOAD_SITEMAP_DATA, API_POST, data, function (r) {
        elNet(API_UPLOAD_SITEMAP_DATA, r);
    }, function () {
        elNet(API_UPLOAD_SITEMAP_DATA, null);
    }, "text");
}
function netUploadInitialData(data) {
    ajax(API_UPLOAD_INITIAL_DATA, API_POST, data, function (r) {
        elNet(API_UPLOAD_INITIAL_DATA, r);
    }, function () {
        elNet(API_UPLOAD_INITIAL_DATA, null);
    }, "text");
}

// NETWORK EVENT LISTENERS

function elNet(apiMethod, response) {
    let s = false;
    if (response!==null) {
        s = response.resultCode == 0;
    }

    switch (apiMethod) {

        case OP_CLEAR_DATA_CACHE:
            uiDisplayMsg(uiMsgTextOpResult(TEXT_OP_CLEAR_DATA_CACHE, s), s);
            break;

        case API_SITE_SETTINGS:
            uiDisplayMsg(uiMsgTextOpResult(TEXT_OP_SAVE_SITE_SETTINGS, s), s);
            break;

        case API_UPLOAD_SITEMAP_DATA:
            uiDisplayMsg(uiMsgTextOpResult(TEXT_UPLOAD_SITEMAP_DATA, s), s);
            break;

        case API_UPLOAD_INITIAL_DATA:
            uiDisplayMsg(uiMsgTextOpResult(TEXT_UPLOAD_INITIAL_DATA, s), s);
            break;

    }
    //log(r, true);
}

// VIEW
function prepareView() {

}

function uiDisplayMsg(msg, success = null) {
    log('uiDisplayMsg > '+msg+'/'+success);
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

const TEXT_OP_CLEAR_DATA_CACHE = "Очистка кеша данных валют или бирж";
const TEXT_OP_SAVE_SITE_SETTINGS = "Сохранение настроек сайта";
const TEXT_UPLOAD_SITEMAP_DATA = "Загрузка файла sitemap.xml";
const TEXT_UPLOAD_INITIAL_DATA = "Загрузка файла CoinAndExchangeInfo.json";

const TEXT_SUCCESS = "успешно";
const TEXT_FAILURE = "ошибка";


// END OF VIEW


function ajax(url, method, data, success, error = null, contentType="json") {
    if (contentType==="json"){data = JSON.stringify(data);}

    $.ajax({
        url: url, type: method, contentType: contentType,
        data: data,
        success: function (d) {
            success(JSON.parse(d));
        },
        error: error
    });
}

// OPERATIONS
const OP_CLEAR_DATA_CACHE = "OP_CLEAR_DATA_CACHE";

const API_POST = "POST";
const API_GET = "GET";
const API_DELETE = "DELETE";

const API = "/api/"
const API_SITE_SETTINGS = API + "siteSettings"
const API_DATA_CACHE = API + "dataCache"
const API_UPLOAD_INITIAL_DATA = API + "initialData"
const API_UPLOAD_SITEMAP_DATA = API + "uploadSiteMap"


function log(a, stringify) {
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
