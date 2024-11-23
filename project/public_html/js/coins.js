let prepared = false;

function prepare() {
    if (prepared) {
        return;
    }
    prepared = true;


    l("start");
    prepareData();
    prepareControl();
    prepareView();
    startApp();
}


function prepareControl() {
    $('#nextPage').click(nextPage);
    $('#prevPage').click(prevPage);
    $('#gotoPage').click(elBGotoPage);
}

function startApp() {
    gotoPage(0);
}


// UI

function elBGotoPage() {
    gotoPage($('#pageNumber').val()-1);
}


/// DATA
let refsList = null;
let totalPages = 0;
let currentPage = 0;

function prepareData() {
    refsList = coinInfoList;
    totalPages = calcNumPages();
}

function gotoPage(a) {
    currentPage = Math.min(totalPages, Math.max(0, a));
    loadCurrentPage();
    uiUpdatePaginator();
}

function nextPage() {
    currentPage += 1;
    if (currentPage > totalPages - 1) {
        currentPage = totalPages - 1;
        return;
    }

    loadCurrentPage();
    uiUpdatePaginator();
}

function prevPage() {
    currentPage -= 1;

    if (currentPage < 0) {
        currentPage = 0;
        return;
    }

    loadCurrentPage();
    uiUpdatePaginator();
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
        let i = uiii;

        if (uiii < lll) {
            ell.css('display', 'table-row');

            a2 = ell.find('.href-ref');
            for (i2 = 0; i2 < a2.length; i2++) {
                let el = a2.eq(i2);
                let elValName = el.attr('data-type');
                el.attr('href', '/coin/' + refsList[i][elValName]);
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


        } else {
            ell.css('display', 'none');
        }
    }
}

const pageSize = 20;

function calcNumPages() {
    return ((refsList.length - refsList.length % pageSize) / pageSize) + 1;
}


/// NET

/// VIEW


function prepareView() {
    uiUpdatePaginator();
}


function uiUpdatePaginator() {
    $('#currentPage').text(currentPage+1);
    $('#totalPages').text(totalPages);
    $('#pageNumber').attr('max', totalPages);

    if (currentPage == 0) {
        $('#prevPage').attr('disabled','');
    } else {
        $('#prevPage').removeAttr('disabled');
    }
    if(currentPage == totalPages-1) {
        $('#nextPage').attr('disabled','');
    } else {
        $('#nextPage').removeAttr('disabled');
    }
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

