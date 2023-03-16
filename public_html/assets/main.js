/* http://v4-alpha.getbootstrap.com/getting-started/browsers-devices/#internet-explorer-10-in-windows-phone-8 */

// Copyright 2014-2017 The Bootstrap Authors
// Copyright 2014-2017 Twitter, Inc.
// Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
    var msViewportStyle = document.createElement('style')
    msViewportStyle.appendChild(
        document.createTextNode(
            '@-ms-viewport{width:auto!important}'
        )
    )
    document.head.appendChild(msViewportStyle)
}

/* http://v4-alpha.getbootstrap.com/getting-started/browsers-devices/#android-stock-browser */

$(function () {
    var nua = navigator.userAgent
    var isAndroid = (nua.indexOf('Mozilla/5.0') > -1 && nua.indexOf('Android ') > -1 && nua.indexOf('AppleWebKit') > -1 && nua.indexOf('Chrome') === -1)
    if (isAndroid) {
        $('select.form-control').removeClass('form-control').css('width', '100%')
    }
})

NProgress.start();

$(document).ready(function () {
    NProgress.done();
});

$(document).ajaxStart(function () {
    NProgress.start();
});

$(document).ajaxStop(function () {
    NProgress.done();
});