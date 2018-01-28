function w_cookie() {
    /*设置cookie值,time为保存的分钟数*/
    this.setvalue=function (cname,cvalue,time) {
        var exp = new Date();
        exp.setTime(exp.getTime() + 60 * time*1000);
        document.cookie=cname+"="+cvalue+";expires= "+exp.toGMTString();
    };
    this.getvalue=function (cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i=0; i<ca.length; i++)
        {
            var c = ca[i].trim();
            if (c.indexOf(name)==0) return c.substring(name.length,c.length);
        }
        return "";
    };
    /*删除cookie*/
    this.delcookie=function () {
        
    }
}