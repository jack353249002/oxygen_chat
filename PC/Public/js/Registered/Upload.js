function  create_upload(porject_url) {;
    var uploader = WebUploader.create({
        // 选完文件后，是否自动上传。
        auto: true,

        // swf文件路径
        swf: '../../dist/Uploader.swf',
        formData: {
            'Class':'Upload',
            'Function':'upload_portrait'
        },
        // 文件接收服务端。
        server: window.upload_url,

        // 选择文件的按钮。可选。
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.
        pick: '#filePicker',

        // 只允许选择图片文件。
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes: 'image/*'
        }
    });
    uploader.on( 'uploadSuccess', function( file,response ) { //上传成功时触发

        var json=JSON.parse(response._raw);
        $('#user_img').attr("src",json.imgurl);
        $('#form input[name="headportrait"]').val(json.dburl);
    });
}