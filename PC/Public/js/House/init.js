window.Socket_ip="";  //scoketip地址
window.token="";
window.cookie_obj=null;
window.User=null;
$(document).ready(function () {
        init();
        landtosocket();
})
function init() {
    window.cookie_obj= new w_cookie();
    window.token=window.cookie_obj.getvalue("token");
    /*获取配置文件*/
    $.ajax({
        url:"Public/js/url_conf.json",
        dataType:"json",
        async:false,
        success:function(json){
            window.User=json.User;
            window.Socket_ip=json.Socket_ip;
        }
    });
}
/*连接socket*/
function landtosocket() {
    window.ws = new WebSocket(window.Socket_ip);
    window.ws.onopen = function() {
        var data={
            "type_id":1,
            "house_id":$('#houseid').val(),
            "token":window.token
        };
        var json=JSON.stringify(data);
        window.ws.send(json);
    };
    /*相应服务器端返回的指令*/
    window.ws.onmessage = function(e) {
        var infor=JSON.parse(e.data);
        switch(infor.type_id)
        {
            /*显示新加入用户信息*/
            case 1:
                var userobj=JSON.parse(infor.data);
                add_user_list(userobj[0]);
                break;
            /*退出登录时触发*/
            case 0:
                var id=infor.data;
                $('div[data-serverid="'+id+'"]').remove();
                break;
            /*加载当前房间用户*/
            case 2:
                var list=JSON.parse(infor.data);
                for(var i=0;i<list.length;i++)
                {
                    var info=JSON.parse(list[i].infor);
                    add_user_list(info[0]);
                }
                break;
            case 3:
                var infor=JSON.parse(infor.data);
                show_infor(infor);
                break;
        }
    };
    window.onclose=function (e) {
        
    }
}
// 假设服务端ip为127.0.0.1，测试时请改成实际服务端ip
function  send() {
    if(validation_token()) {
        var data2 = {
            "type_id": 2,
            "house_id": $('#houseid').val(),
            "chat_infor": $('#infor').val(),
            "token": window.token
        };
        var json2 = JSON.stringify(data2);
        window.ws.send(json2);
    }
}
//添加用户列表
function add_user_list(data) {
    var name=data.nickname;
    var server_id=data.server_id;
    var head=data.headportrait;
    $('#right').append('<div data-serverid="'+server_id+'" class="infor-row"> <div class="head-left"><img src="'+head+'"/></div><div class="text-infor-left">'+name+'</div> </div>');
}
/*显示消息*/
function show_infor(data) {
    var chat_infor=data.chat_infor;
    var head=data.user_infor[0].headportrait;
    $('#list').append('<div class="infor-row"> <div class="head-left"><img src="'+head+'"/></div><div class="text-infor-left">'+chat_infor+'</div> </div>');
    $('#infor').val("");
}
/*token验证*/
function validation_token() {
    var token=window.cookie_obj.getvalue("token");
     bool=true;
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
                bool=false;
                alert("令牌错误!");
                window.location.href = "Land.php";
            }
        }
    });
    return bool;
}