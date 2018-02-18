function save() {
    var nickname=$('#form input[name="nickname"]').val();
    var headportrait=$('#form input[name="headportrait"]').val();
    var passwords=$('#form input[name="passwords"]').val();
    if(nickname=="")
    {
        alert("昵称不能为空");
        return false;
    }
    if(headportrait=="")
    {
        alert("头像不能为空");
        return false;
    }
    if(passwords=="")
    {
        alert("密码不能为空");
        return false;
    }
    var json= {"nickname": nickname, "headportrait": headportrait, "passwords": passwords};
    jsonstr= JSON.stringify(json);
    $.ajax({
            url:window.User,
            type:"GET",
            data:{
                "Class": "User",
                "Function": "insert",
                "Data": jsonstr
            },
            async:true,
            success:function(result){
                 var infor=JSON.parse(result);
                 alert(infor.msg);
                 if(infor.type_id==1) {
                     window.location.href = "Land.php";
                 }
           }
        });
}