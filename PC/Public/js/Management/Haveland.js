$(document).ready(function () {
    if (window.cookie_obj == null || window.cookie_obj.getvalue("token") == "") {
        window.location.href = "Land.php";
    }
})


