function save() {
    var cookie_obj= new w_cookie();
    var name=$('#form input[name="name"]').val();
    var token= cookie_obj.getvalue("token");
    var passwords=$('#form input[name="passwords"]').val();
    var json= {"name": name, "passwords": passwords,"token":token};
    jsonstr= JSON.stringify(json);
    $.ajax({
        url:window.House,
        type:"GET",
        data:{
            "Class": "House",
            "Function": "create_house",
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
            }
        }
    });
}