'use strict';
//var callFrame = function () {
//    var postData = {
//        uuid: 'Z_localhost_',
//        head: {
//            routeFlg: 'Z_ROUTE_1',
//            modelFlg: 'Z_MODEL_0',
//            servicesList: ['Z_SRV_0', 'Z_SRV_1'],
//            dataFrom: ['localhost']
//        },
//        entity: {
//            body: '1'
//        }
//    };
////    console.log(JSON.stringify(postData));
//    $.ajax(getSettings('index_.php', postData));
//};

var getSettings = function (target, data) {
    return {
        type: "POST",
        url: target,
        data: JSON.stringify(data),
        //dataType: "json",
        error: function (XHR, textStatus, errorThrown) {
            console.log(XHR);
            alert("XHR=" + XHR + "\ntextStatus=" + textStatus + "\nerrorThrown=" + errorThrown);
        },
//        success: function (data) {
//            $("#content").empty();
//            console.log(data);
//            $("#content").append('<div>' + data + '</div>');
//        },
        headers: {
            "Access-Control-Allow-Origin": "*",
            "Access-Control-Allow-Method": "*"
        }
    };
};

var subPageCallBack = function (data) {
    $("#content").empty();
    $("#content").append(data);
};


var callInitRoles = function () {
    $("#content").hide();
    $("#content").empty();
    $("#content").load('venders/pages/initRoles.html');
    $("#content").fadeIn(1000);
};

var callInitThreshold = function () {
    $("#content").hide();
    $("#content").empty();
    $("#content").load('venders/pages/initThreshold.html');
    $("#content").fadeIn(1000);
};

var callAdmission = function () {
    $("#content").hide();
    $("#content").empty();
    $("#content").load('venders/pages/admission.html');
    $("#content").fadeIn(1000);
};

var callDefensePU = function () {
    $("#content").hide();
    $("#content").empty();
    $("#content").load('venders/pages/defensePU.html');
    $("#content").fadeIn(1000);
}

