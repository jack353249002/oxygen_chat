window.Socket_ip="";  //scoketip地址
window.token="";
window.cookie_obj=null;
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
            window.Socket_ip=json.Socket_ip;
        }
    });
}
/*连接socket*/
function landtosocket() {
    window.ws = new WebSocket(window.Socket_ip);
    window.ws.onopen = function() {
        alert("连接成功");
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
            case 1:
                alert(JSON.stringify(infor.data));
                break;
        }
    };
}
// 假设服务端ip为127.0.0.1，测试时请改成实际服务端ip
function  send() {
    var data2={
        "type_id":2,
        "house_id":"10",
        "chat_infor": $('#infor').val()
    };
    var json2=JSON.stringify(data2);
    window.ws.send(json2);
}