window.User="";
window.House="";
$(document).ready(function () {
    init();
})

function  init() {
    $.getJSON("Public/js/url_conf.json", function(json){
        window.User=json.User;
        window.House=json.House;
    });
}