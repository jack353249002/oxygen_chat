window.land_url="";
$(document).ready(function () {
    init();
})

function  init() {
    $.getJSON("Public/js/url_conf.json", function(json){
        window.land_url=json.land_user;
    });
}