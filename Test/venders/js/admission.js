$(document).ready(function () {
    $('.coverLay').hide();
    getRolesList();
});
var _m_roles = [];
var getRolesList = function () {
    var postData = {
        uuid: 'Z_localhost_',
        head: {
            routeFlg: 'Z_ROUTE_1',
            modelFlg: 'NULL',
            servicesList: ['Z_SRV_SELECT_ROLES'],
            dataFrom: ['localhost']
        },
        entity: 'ALL'
    };

    var setting = getSettings('index_.php', postData);
    setting.dataType = 'json';
    setting.success = successSelectCallBack;
    $.ajax(setting);
};

var successSelectCallBack = function (resultData) {
    _m_roles = JSON.parse(resultData).entity.resultData;
    var tmpHash = {};
    var frag = document.createDocumentFragment();
    for (var i = 0; i < _m_roles.length; i++) {
        if (!tmpHash.hasOwnProperty(_m_roles[i].ROLE_ID)) {
            tmpHash[_m_roles[i].ROLE_ID] = true;
            var tmpOpt = document.createElement('option');
            tmpOpt.innerHTML = tmpOpt.value = _m_roles[i].ROLE_ID;
            frag.appendChild(tmpOpt);
        }
    }
    $('#selRoles').append(frag);
};

var changeRoles = function () {
    $('#admission_assessment').empty();
    var selRole = $('#selRoles').val();
    var currentCursor = 0;
    var items = [];
    var currentItem = null;
    for (var i = 0; i < _m_roles.length; i++) {
        if (_m_roles[i].ROLE_ID.toString() !== selRole) {
            continue;
        }
        if (_m_roles[i].ITEM_ID !== currentCursor) {
            currentItem = {};
            currentCursor = currentItem.id = _m_roles[i].ITEM_ID;
            currentItem.desc = _m_roles[i].DESCRIPTION;
            currentItem.items = [_m_roles[i].VALUE];
            items.push(currentItem);
        } else {
            currentItem.items.push(_m_roles[i].VALUE);
        }
    }

    var frag = document.createDocumentFragment();
    for (var i = 0; i < items.length; i++) {
        var itemRecord = document.createElement('div');
        var tmpEle = document.createElement('label');
        tmpEle.innerHTML = items[i].desc + ":";
        itemRecord.appendChild(tmpEle);
        tmpEle = document.createElement('select');
        tmpEle.id = items[i].id;
        for (var j = 0; j < items[i].items.length; j++) {
            var tmpOpt = document.createElement('option');
            tmpOpt.innerHTML = tmpOpt.value = items[i].items[j];
            tmpEle.appendChild(tmpOpt);
        }
        itemRecord.appendChild(tmpEle);

        frag.appendChild(itemRecord);
    }
    $('#admission_assessment').append(frag);
    $('#admission_assessment').fadeIn(1000);
};

var callCommitAdmission = function () {
    var patientName = $('#admission_inputPatientName').val();
    var selRole = $('#selRoles') ? $('#selRoles')[0].selectedIndex : null;
    if (patientName === null || patientName.trim() === '') {
        alert('patientName');
        return;
    }
    if (selRole === null || selRole === 0) {
        alert('Select a Role');
        return;
    }

    var requestData = {};
    requestData.patientName = patientName;
    requestData.roleId = $('#selRoles').val();
    requestData.scores = [];
    $('#admission_assessment select').each(function () {
        requestData.scores.push({item: this.id, value: $(this).val()});
    });
    console.log(requestData);


    var postData = {
        uuid: 'Z_localhost_',
        head: {
            routeFlg: 'Z_ROUTE_1',
            modelFlg: 'NULL',
            servicesList: ['Z_SRV_INSERT_SCORE'],
            dataFrom: ['localhost']
        },
        entity: requestData
    };

    var setting = getSettings('index_.php', postData);
    setting.dataType = 'json';
    setting.success = successInsertCallBack;
    $.ajax(setting);
};

var successInsertCallBack = function () {

};
