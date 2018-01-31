function loadmenu() {
    $.ajax({
        url:window.House,
        dataType:"json",
        data:{
            "Class": "House",
            "Function": "get_house",
            "Data": ""
        },
        async:true,
        success:function(json){
            var infor=json.data;
            new Vue({
                el: '#house-list',
                data: {
                    infor: infor
                }
            })
        }
    });
}