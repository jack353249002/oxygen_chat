window.user_infor=null;
window.cookie_obj=null;
$(document).ready(function () {
    get_user();
    init();
})
/*获取用户信息*/
function  get_user() {
   window.cookie_obj= new w_cookie();
   var json_obj=JSON.parse(cookie_obj.getvalue("user_infor"));
   window.user_infor=json_obj;
}
/*初始化*/
function  init() {
    $('#user_name').html(window.user_infor.nickname);
}
/*退出登录*/
function  exit() {
    cookie_obj.setvalue("user_infor","",-1);
    window.location.href="Land.php";
}