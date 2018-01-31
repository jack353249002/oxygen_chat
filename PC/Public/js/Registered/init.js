window.User="";
window.upload_url="";
$(document).ready(function () {
    init();
})

function  init() {
    $.getJSON("Public/js/url_conf.json", function(json){
        window.upload_url=json.Upload_Headportrait;
        window.User=json.User;
        create_upload(json.Porject_url);
    });
}