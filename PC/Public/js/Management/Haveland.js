$(document).ready(function () {
    if (window.cookie_obj == null || window.cookie_obj.getvalue("user_infor") == "") {
        window.location.href = "Land.php";
    }
})


