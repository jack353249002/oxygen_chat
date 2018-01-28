window.insert_user="";
window.upload_url="";
$(document).ready(function () {
    init();
})

function  init() {
    $.getJSON("Public/js/url_conf.json", function(json){
        window.upload_url=json.Upload_Headportrait;
        window.insert_user=json.Insert_user;
        create_upload(json.Porject_url);
    });
}