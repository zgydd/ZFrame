'use strict';
$(document).ready(function () {
    $('.coverLay').hide();
    getPatientsList();
});

var getPatientsList = function () {
    var postData = {
        uuid: 'Z_localhost_',
        head: {
            routeFlg: 'Z_ROUTE_1',
            modelFlg: 'Z_MODEL_PU_SELECT_PATIENTS',
            servicesList: [],
            dataFrom: ['localhost']
        },
        entity: 'ALL'
    };

    var setting = getSettings('index_.php', postData);
    //setting.dataType = 'json';
    setting.success = successSelectCallBack;
    $.ajax(setting);
};


var successSelectCallBack = function (resultData) {
    var resultMsg = JSON.parse(resultData).entity.resultData;
    console.log(resultMsg);
};
