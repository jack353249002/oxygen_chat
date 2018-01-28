function save() {
    var nickname=$('#form input[name="nickname"]').val();
    var headportrait=$('#form input[name="headportrait"]').val();
    var passwords=$('#form input[name="passwords"]').val();
    var json= {"nickname": nickname, "headportrait": headportrait, "passwords": passwords};
    jsonstr= JSON.stringify(json);
    $.ajax({
            url:window.insert_user,
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
           }
        });
}