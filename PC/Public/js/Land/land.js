function land() {
    var nickname=$('#form input[name="nickname"]').val();
    var passwords=$('#form input[name="passwords"]').val();
    var json= {"nickname": nickname, "passwords": passwords};
    jsonstr= JSON.stringify(json);
    $.ajax({
        url:window.land_url,
        type:"GET",
        data:{
            "Class": "User",
            "Function": "land",
            "Data": jsonstr
        },
        async:true,
        success:function(result){
            var infor=JSON.parse(result);
            if(infor.type_id==0) {
                alert(infor.msg);
            }
            else
            {
                var cookie_obj= new w_cookie();
                var userjson= JSON.stringify(infor.data);
                cookie_obj.setvalue("user_infor",userjson);
                window.location.href="index.php";
            }
        }
    });
}