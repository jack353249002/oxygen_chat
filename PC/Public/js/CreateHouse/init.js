window.House="";
window.cookie_obj=null;
$(document).ready(function () {
    init();
    new_token();
})

function  init() {
    $.getJSON("Public/js/url_conf.json", function(json){
        window.House=json.House;
    });
}
/*获取用户信息*/
function  new_token() {
    window.cookie_obj= new w_cookie();
}