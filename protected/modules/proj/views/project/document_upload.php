<style type="text/css">
    #son {width:0; height:100%; background-color:#09F; text-align:center; line-height:10px; font-size:20px; font-weight:bold;}
</style>
<?php
$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'focus' => array($rs, 'old_pwd'),
    //'autoValidation' => false,
));
$upload_url = Yii::app()->params['upload_url'];//上传地址
?>
<div>
    <input type="hidden" id="url" value="<?php echo "$upload_url" ?>"/>
    <input type="hidden" id="filebase64"/>
    <input type="hidden" id="suffix"/>
    <input type="hidden" id="name"/>
    <input type="hidden" id="program_id" value="<?php echo "$program_id" ?>"/>
</div>
<!--<div id="select">-->
<!--    <select id="tags" name="tags">-->
<!--        <option value="1">PTW</option>-->
<!--        <option value="2">TBM</option>-->
<!--        <option value="3">TRAIN</option>-->
<!--        <option value="4">WSH</option>-->
<!--        <option value="5">CHECKLIST</option>-->
<!--        <option value="6">RA/SWP</option>-->
<!--    </select>-->
<!--</div>-->
<div class="row">
    <p style="font-size:16px;margin-left: 13px"><?php echo Yii::t('common','upload_documnet_prompt') ?></p>
    <div class="col-md-6"><!--  照片  -->
        <div class="form-group">
            <label for="face_img" class="col-sm-3 control-label padding-lr5" ><?php echo Yii::t('comp_document', 'file'); ?></label>
            <input id="file" class="form-control" check-type="" style="display:none" onchange="dealImage(this)" name="File[file_path]" type="file">
            <div class="input-group col-md-9 padding-lr5">
                <input id="uploadurl" class="form-control" type="text" disabled>
                        <span class="input-group-btn">
                            <a class="btn btn-warning" onclick="$('input[id=file]').click();">
                                <i class="fa fa-folder-open-o"></i> <?php echo Yii::t('common','button_browse'); ?>
                            </a>
                        </span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-12">
            <button type="button" id="sbtn" class="btn btn-primary btn-lg" onclick="sumitImageFile();"><?php echo Yii::t('common', 'button_save'); ?></button>
            <button type="button" class="btn btn-default btn-lg" style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>
        </div>
    </div>
</div>
<!--<div id ="upload">-->
<!--    <input id="file" type="file" onchange="dealImage(this)">-->
<!--</div>-->
<div id="father"></div>
<div id="son"></div>
    <?php $this->endWidget(); ?>
<script type="text/javascript">
    //返回
    var back = function () {
        var id = $("#program_id").val();
        window.location = "index.php?r=proj/project/attachmentlist&id=" + id;
    }
    var n = 4;
    //定时关闭弹窗
    function showTime(flag) {
        if (flag == false)
            return;
        n--;
        $('#divMain').html(n + ' <?php echo Yii::t('common', 'tip_close'); ?>');
        if (n == 0)
            $("#modal-close").click();
        else
            setTimeout('showTime()', 1000);
    }
    //压缩图像转base64
    function dealImage(file)
    {
        var video_src_file = $("#file").val();
        if (!/\.(gif|jpg|jpeg|png|GIF|JPG|PNG|pdf|txt)$/.test(file.value)) {
            alert("Please upload document in either .gif, .jpeg, .jpg, .png or .pdf format.");
            return false;
        }
        $("#uploadurl").val(video_src_file);
        var newFileName = video_src_file.split('.');
        $("#name").val(newFileName[0]);
        if(newFileName[1] != 'pdf' && newFileName[1] != 'txt'){
            document.getElementById('uploadurl').value=file.value;
            var URL = window.URL || window.webkitURL;
            var blob = URL.createObjectURL(file.files[0]);
            var img = new Image();
//        alert(blob);
            img.src = blob;
            img.onload = function() {
                var that = this;
                //生成比例
                var w = that.width, h = that.height, scale = w / h;
                new_w = 300;
                new_h = new_w / scale;

                //生成canvas
                var canvas = document.createElement('canvas');
                var ctx = canvas.getContext('2d');
                $(canvas).attr({
                    width: new_w,
                    height: new_h
                });
                ctx.drawImage(that, 0, 0, new_w, new_h);
                // 图像质量
                quality = 0.8;
                // quality值越小，所绘制出的图像越模糊
                var base64 = canvas.toDataURL('image/jpeg', quality);
                // 生成结果
                var result = {
                    base64: base64,
                    clearBase64: base64.substr(base64.indexOf(',') + 1)
                };
                $("#filebase64").val(result.base64);
                document.getElementById("suffix").value= '1';
//                $("#suffix").val('1');
//                alert($("suffix").val());
//                btnsubmit(result.base64,newFileName[0]);
            }
        }else{
//                var formData = new FormData();   //这里连带form里的其他参数也一起提交了,如果不需要提交其他参数可以直接FormData无参数的构造函数
//                formData.append("file1", $('#file')[0].files[0]);
//                sumitImageFile(formData);
            $("#suffix").val('2');
        }
    }

    /**
     * 将以base64的图片url数据转换为Blob
     * @param urlData
     *            用url方式表示的base64图片数据
     */
    function convertBase64UrlToBlob(urlData){

        var bytes=window.atob(urlData.split(',')[1]);        //去掉url的头，并转换为byte

        //处理异常,将ascii码小于0的转换为大于0
        var ab = new ArrayBuffer(bytes.length);
        var ia = new Uint8Array(ab);
        for (var i = 0; i < bytes.length; i++) {
            ia[i] = bytes.charCodeAt(i);
        }

        return new Blob( [ab] , {type : 'image/png'});
    }

    function btnsubmit(base64Codes){
//        var form = document.forms[0];
        var formData = new FormData();   //这里连带form里的其他参数也一起提交了,如果不需要提交其他参数可以直接FormData无参数的构造函数
        //convertBase64UrlToBlob函数是将base64编码转换为Blob
        formData.append("file1", convertBase64UrlToBlob(base64Codes), name+'.png');  //append函数的第一个参数是后台获取数据的参数名,和html标签的input的name属性功能相同
//        sumitImageFile(formData);
    }

    var index = 0;
    function sumitImageFile(){
        var suffix = document.getElementById("suffix").value;
//        alert(suffix);
        if(suffix == '2'){
            var formData = new FormData();   //这里连带form里的其他参数也一起提交了,如果不需要提交其他参数可以直接FormData无参数的构造函数
            formData.append("file1", $('#file')[0].files[0]);
        }else{
            var formData = new FormData();   //这里连带form里的其他参数也一起提交了,如果不需要提交其他参数可以直接FormData无参数的构造函数
            var new_name = $('#name').val();
            //convertBase64UrlToBlob函数是将base64编码转换为Blob
            formData.append("file1", convertBase64UrlToBlob($('#filebase64').val()), new_name+'.png');  //append函数的第一个参数是后台获取数据的参数名,和html标签的input的name属性功能相同
        }
        var form = document.forms[0];
        var upload_url = $("#url").val();
        console.log(form);
        //ajax 提交form
        $.ajax({
            url: upload_url,
            type: "POST",
            data: formData,
            dataType: "json",
            processData: false,         // 告诉jQuery不要去处理发送的数据
            contentType: false,        // 告诉jQuery不要去设置Content-Type请求头
            beforeSend: function () {
                $("#father").html( "Uploading..." );
            },
            success: function (data) {
                $.each(data, function (name, value) {
                    if (name == 'data') {
                        $("#upload_url").val(value.file1);
                        moveFile(value.file1);
                    }
                });
            },
        });
    }
    /**
     * 上传至正式服务器
     */
    function moveFile(file_src) {
        var program_id = $("#program_id").val();
        $.ajax({
            type: "POST",
            url: "index.php?r=proj/project/move",
            data: {file_src:file_src,program_id:program_id},
            dataType: "json",
            beforeSend: function () {

            },
            xhr: function(){
                var xhr = $.ajaxSettings.xhr();
                if(onprogress && xhr.upload) {
                    xhr.upload.addEventListener("progress" , onprogress, false);
                    return xhr;
                }
            },
            success: function(data){
                $("#father").empty();
                showTime(data.refresh);
                back();
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                //alert(XMLHttpRequest.status);
                //alert(XMLHttpRequest.readyState);
                //alert(textStatus);
            },
        });
    }
    /**
     * 侦查附件上传情况 ,这个方法大概0.05-0.1秒执行一次
     */
    function onprogress(evt){
        var loaded = evt.loaded;     //已经上传大小情况
        var tot = evt.total;      //附件总大小
        var per = Math.floor(100*loaded/tot);  //已经上传的百分比
        $("#son").html( per +"%" );
        $("#son").css("width" , per +"%");
    }
    </script>