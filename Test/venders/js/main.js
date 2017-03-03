'use strict';
var callFrame = function () {
    var postData = {
        uuid: 'Z_localhost_',
        head: {
            routeFlg: 'Z_ROUTE_1',
            modelFlg: 'Z_MODEL_0',
            servicesList: ['Z_SRV_0', 'Z_SRV_1'],
            dataFrom: ['localhost']
        },
        entity: {
            body: '1'
        }
    };
//    console.log(JSON.stringify(postData));
    var settings = {
        type: "POST",
        url: 'index_.php',
        data: JSON.stringify(postData),
        //dataType: "json",
        error: function (XHR, textStatus, errorThrown) {
            console.log(XHR);
            alert("XHR=" + XHR + "\ntextStatus=" + textStatus + "\nerrorThrown=" + errorThrown);
        },
        success: function (data) {
            $("#content").empty();
            $("#content").append(data);
        },
        headers: {
            "Access-Control-Allow-Origin": "*",
            "Access-Control-Allow-Method": "*"
        }
    };
    $.ajax(settings);
};

