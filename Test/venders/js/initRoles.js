$(document).ready(function () {
    $('.coverLay').hide();
    $('.initRoles_listPanel').hide();
    $('.initRoles_insertPanel').hide();

    getRolesList();

});

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

var rolesList = [];
var insRole = function () {
    var roleId = $('#initRoles_inputRoleID').val();
    var itemId = $('#initRoles_inputItemID').val();
    var roleDesc = $('#initRoles_inputRoleDescription').val();
    var roleValue = $('#initRoles_inputRoleValue').val();
    if (roleId === null || roleId.trim() === '') {
        alert('Role ID');
        return null;
    }
    if (roleValue === null || roleValue.trim() === '') {
        alert('Role Value');
        return null;
    }
    rolesList.push({roleID: roleId, itemID:itemId, roleDesc: roleDesc, roleValue: roleValue});
    $('#initRoles_inputRoleValue').val('');
    
    var frag = document.createDocumentFragment();
    for(var i=0; i<rolesList.length;i++){
        frag.appendChild(document.createElement('hr'));
        var tmpLab = document.createElement('label');
        tmpLab.innerHTML = 'Role id: ' + rolesList[i].roleID;
        frag.appendChild(tmpLab);
        frag.appendChild(document.createElement('br'));
        tmpLab = null;
        tmpLab = document.createElement('label');
        tmpLab.innerHTML = 'Item id: ' + rolesList[i].itemID;
        frag.appendChild(tmpLab);
        frag.appendChild(document.createElement('br'));
        tmpLab = null;
        tmpLab = document.createElement('label');
        tmpLab.innerHTML = 'Role Description: ' + rolesList[i].roleDesc;
        frag.appendChild(tmpLab);
        frag.appendChild(document.createElement('br'));
        tmpLab = null;
        tmpLab = document.createElement('label');
        tmpLab.innerHTML = 'Role value: ' + rolesList[i].roleValue;
        frag.appendChild(tmpLab);
        tmpLab = null;
        frag.appendChild(document.createElement('br'));
    }    
    
    $('#tmpRoleList').empty();
    $('#tmpRoleList').append(frag);    
};
var commitRoles = function () {
    var insFinish = insRole();
    if (insFinish === null)
        return;
    var postData = {
        uuid: 'Z_localhost_',
        head: {
            routeFlg: 'Z_ROUTE_1',
            modelFlg: 'NULL',
            servicesList: ['Z_SRV_INS_ROLES'],
            dataFrom: ['localhost']
        },
        entity: rolesList
    };

    var setting = getSettings('index_.php', postData);
    setting.dataType = 'json';
    setting.success = successInsertCallBack;
    $.ajax(setting);
};

var successSelectCallBack = function (resultData) {
    var resultMsg = JSON.parse(resultData).entity.resultData;
    $('.initRoles_listPanel').empty();

    var listTable = document.createElement('table');
    var th = document.createElement('th');
    th.innerHTML = 'RoleId';
    listTable.append(th);
    th = null;
    th = document.createElement('th');
    th.innerHTML = 'ItemId';
    listTable.append(th);
    th = null;
    th = document.createElement('th');
    th.innerHTML = 'Value';
    listTable.append(th);
    th = null;
    th = document.createElement('th');
    th.innerHTML = 'Description';
    listTable.append(th);

    listTable.append(th);

    for (var i = 0; i < resultMsg.length; i++) {
        var tr = document.createElement('tr');
        var td = document.createElement('td');
        $(td).addClass('num_td');
        td.innerHTML = resultMsg[i].ROLE_ID;
        tr.append(td);
        td = null;
        td = document.createElement('td');
        $(td).addClass('num_td');
        td.innerHTML = resultMsg[i].ITEM_ID;
        tr.append(td);
        td = null;
        td = document.createElement('td');
        $(td).addClass('num_td');
        td.innerHTML = resultMsg[i].VALUE;
        tr.append(td);
        td = null;
        td = document.createElement('td');
        $(td).addClass('text_td');
        td.innerHTML = resultMsg[i].DESCRIPTION;
        tr.append(td);
        listTable.append(tr);
    }

    $('.initRoles_listPanel').append(listTable);
    
    $('.initRoles_listPanel').fadeIn();
    $('.initRoles_insertPanel').fadeIn();
};

var successInsertCallBack = function (resultData) {
    var resultMsg = JSON.parse(resultData).entity.resultData;
    rolesList.length = 0;
    $('.coverLay').empty();
    $(".coverLay").append('<div>' + resultMsg + '</div>');
    $('.coverLay').show();
    getRolesList();
    $('#initRoles_inputRoleID').val('');
    $('#initRoles_inputRoleDescription').val('');
    $('#tmpRoleList').empty();
    $('.coverLay').fadeOut();
};

