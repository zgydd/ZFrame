var rolesList = [];
var insRole = function () {
    var roleId = $('#initRoles_inputRoleID').val();
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
    rolesList.push({roleID: roleId, roleDesc: roleDesc, roleValue: roleValue});
    $('#initRoles_content').fadeOut();
    $('#initRoles_inputRoleID').val('');
    $('#initRoles_inputRoleDescription').val('');
    $('#initRoles_inputRoleValue').val('');
    $('#initRoles_content').fadeIn();
};
var commitRoles = function () {
    var insFinish = insRole();
    if (insFinish === null)
        return;
    var postData = {
        uuid: 'Z_localhost_',
        head: {
            routeFlg: 'Z_ROUTE_1',
            modelFlg: '',
            servicesList: ['Z_SRV_INS_ROLES'],
            dataFrom: ['localhost']
        },
        entity: rolesList
    };

    var setting = getSettings('venders/servers/insertRoles.php', postData);
    //setting.dataType = 'json';
    setting.success = successAjaxCallBack;
    $.ajax(setting);
};

var successAjaxCallBack = function (resultData) {
    subPageCallBack('<div>' + resultData + '</div>');
}

