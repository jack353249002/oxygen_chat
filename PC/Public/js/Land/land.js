function land() {
    var nickname=$('#form input[name="nickname"]').val();
    var passwords=$('#form input[name="passwords"]').val();
    var json= {"nickname": nickname, "passwords": passwords};
    jsonstr= JSON.stringify(json);
    $.ajax({
        url:window.User,
        type:"GET",
        dataType:"json",
        data:{
            "Class": "User",
            "Function": "land",
            "Data": jsonstr
        },
        async:true,
        success:function(result){
            if(result.type_id==0) {
                alert(result.msg);
            }
            else
            {
                var cookie_obj= new w_cookie();
                var token= result.data;
                cookie_obj.setvalue("token",token);
                window.location.href="index.php";
            }
        }
    });
}