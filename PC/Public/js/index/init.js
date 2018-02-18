window.user_infor=null;
window.cookie_obj=null;
window.User=null;
window.House=null;
$(document).ready(function () {
    init();
    get_user();
})
/*获取用户信息*/
function  get_user() {
   window.cookie_obj= new w_cookie();
   var token=cookie_obj.getvalue("token");
   get_user_infor(token);
}
/*初始化*/
function  init() {
    $.ajax({
        url:"Public/js/url_conf.json",
        dataType:"json",
        async:false,
        success:function(json){
            window.User=json.User;
            window.House=json.House;
            loadmenu();
        }
    });
}
/*退出登录*/
function  exit() {
    var token=cookie_obj.getvalue("token");
    cookie_obj.setvalue("token","",-1);
    delete_reids_token(token);
    window.location.href="Land.php";
}
/*获取用户信息*/
function  get_user_infor(token) {
    $.ajax({
        url:window.User,
        dataType:"json",
        type:"GET",
        data:{
            "Class": "User",
            "Function": "get_userinfor",
            "Data": token
        },
        async:false,
        success:function(result){
            if(result.type_id==0) {
                alert(result.msg);
            }
            else if(result.type_id==1)
            {
                var user_infor=JSON.parse(result.data);
                $('#user_name').html(user_infor.nickname);
            }
        }
    });
}
/*删除reids信息*/
function   delete_reids_token(token) {
    $.ajax({
        url:window.User,
        type:"GET",
        data:{
            "Class": "User",
            "Function": "delete_reids_token",
            "Data": token
        },
        async:true,
        success:function(result){

        }
    });
}
/*进入房间*/
function  movein_house(obj) {
    var house_id=$(obj).attr("data-id");
    window.location.href="House.php?house_id="+house_id;
}