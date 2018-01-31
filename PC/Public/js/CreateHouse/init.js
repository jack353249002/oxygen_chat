window.House="";
$(document).ready(function () {
    init();
})

function  init() {
    $.getJSON("Public/js/url_conf.json", function(json){
        window.House=json.House;
    });
}