<style type="text/css">
    #son {width:0; height:100%; background-color:#09F; text-align:center; line-height:10px; font-size:20px; font-weight:bold;}
</style>
<script type="text/javascript" src="js/ajaxfileupload.js"></script>

<?php

$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'focus' => array($rs, 'old_pwd'),
    //'autoValidation' => false,
    "action" => "javascript:formSubmit()",
));
//var_dump($program_id);
//var_dump($task_id);
//exit;
//echo $form->activeHiddenField($model, 'program_id', array(), '');
?>
<?php
$img_url = $model->certificate_photo;
$upload_url = Yii::app()->params['upload_url'];//上传地址
$pos = strpos($img_url,"attachment");
//var_dump($pos);
$new_img_url = substr($img_url, $pos);
?>
<div class="row">
    <p style="color:red;font-size:16px;">Document name cannot contain special characters and symbols such as . / and etc.</p>
</div>
<div class="box-body">
    <div id='msgbox' class='alert alert-dismissable ' style="display:none;">
        <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
        <strong id='msginfo'></strong><span id='divMain'></span>
    </div>
    <div >
        <input type="hidden" id="chemical_id" name="ChemicalInfo[chemical_id]" value="<?php echo "$chemical_id"; ?>"/>
        <input type="hidden" id="primary_id" name="ChemicalInfo[primary_id]" value="<?php echo "$primary_id"; ?>"/>
        <input type="hidden" id="id" name="ChemicalInfo[id]" value="<?php echo "$id"; ?>"/>
        <input type="hidden" id="filebase64" />
        <input type="hidden" id="tmp_src" name="ChemicalInfo[tmp_src]"/>
        <input type="hidden" id="name" name="ChemicalInfo[aptitude_name]"/>
        <input type="hidden" id="mode" name="ChemicalInfo[mode]" value="<?php echo "$_mode_"; ?>"/>
        <input type="hidden" id="upload_url" value="<?php echo "$upload_url" ?>"/>
        <input type="hidden" id="suffix"/>
        <input type="hidden" id="name"/>
    </div>
    <!--<div class="row">
        <div class="col-md-12">
            <h3 class="form-header text-blue"><?php echo Yii::t('task', 'upload'); ?></h3>
        </div>
    </div>-->

    <?php
        $model->permit_startdate = Utils::DateToEn($model->permit_startdate);
        $model->permit_enddate = Utils::DateToEn($model->permit_enddate);
    ?>
    <div  class="row">
        <div class="col-md-6" >
            <div class="form-group"><!--  许可证起始日期  -->
                <label for="certificate_startdate" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('certificate_startdate'); ?></label>
                <div class="input-group col-sm-6 ">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <?php echo $form->activeTextField($model, 'permit_startdate', array('id' => 'permit_startdate', 'class' =>'form-control b_date_permit', 'onclick' => "WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})", 'check-type' => '')); ?>
                    <i class="input-group-addon" style="color:#FF9966">*</i>
                </div>
            </div>
        </div>

        <div class="col-md-6" >
            <div class="form-group"><!--  许可证起截止日期  -->
                <label for="certificate_enddate" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('certificate_enddate'); ?></label>
                <div class="input-group col-sm-6 ">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <?php echo $form->activeTextField($model, 'permit_enddate', array('id' => 'permit_enddate', 'class' =>'form-control b_date_permit', 'onclick' => "WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})",'check-type' => '')); ?>
                    <i class="input-group-addon" style="color:#FF9966">*</i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-6">
            <div class="form-group">
                <label for="aptitude_content" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('certificate_content'); ?><br>(<?php echo Yii::t('comp_staff','maxlength_text'); ?>)</label>
                <div class="col-sm-9 padding-lr5">
                    <?php
                    echo $form->activeTextArea($model, 'certificate_content', array('id' => 'certificate_content', 'class' =>'form-control ','check-type' => '','onkeyup'=>'WidthCheck(this,40);','check-type' => ''));
                    ?>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-6"><!--  照片  -->
            <div class="form-group">
                <label for="face_img" class="col-sm-3 control-label padding-lr5" ><?php echo $model->getAttributeLabel('certificate_photo');?></label>
                <?php echo $form->activeFileField($model, 'certificate_photo', array('id' => 'certificate_photo', 'class' => 'form-control', "check-type" => "", 'style' => 'display:none', 'onchange' => "dealImage(this)")); ?>
                <!--                <input id="aptitude_photo" class="form-control" multiple="multiple"  check-type="" style="display:none" onchange="dealImage(this)" name="UserAptitude[aptitude_photo]"  type="file">    -->
                <div class="input-group col-md-9 padding-lr5">
                    <input id="uploadurl" class="form-control" type="text" disabled>
                        <span class="input-group-btn">
                            <a class="btn btn-warning" onclick="$('input[id=certificate_photo]').click();">
                                <i class="fa fa-folder-open-o"></i> <?php echo Yii::t('common','button_browse'); ?>
                            </a>
                        </span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div id="father"></div>
        <div id="son"></div>
    </div>

</div>


<div class="row">
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-12">
            <?php if($_mode_ == 'insert'){ ?>
                <button type="button" id="sbtn" class="btn btn-primary btn-lg" onclick="sumitImageFile();"><?php echo Yii::t('common', 'button_save'); ?></button>
            <?php }else{ ?>
                <button type="button" id="sbtn" class="btn btn-primary btn-lg" onclick="edit();"><?php echo Yii::t('common', 'button_save'); ?></button>
            <?php } ?>
            <button type="button" class="btn btn-default btn-lg" style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>
        </div>
    </div>
</div>

</div>
<?php $this->endWidget(); ?>
<!--<script src="js/compress_old.js"></script>-->
<script src="js/loading_old.js"></script>
<script type="text/javascript">

    function WidthCheck(str, maxLen){
        var w = 0;
        var tempCount = 0;
        //length 获取字数数，不区分汉子和英文
        for (var i=0; i<str.value.length; i++) {
        //charCodeAt()获取字符串中某一个字符的编码
            var c = str.value.charCodeAt(i);
        //单字节加1
            if ((c >= 0x0001 && c <= 0x007e) || (0xff60<=c && c<=0xff9f)) {
                w++;
            } else {
                w+=2;
            }
            if (w > maxLen) {
                str.value = str.value.substr(0,i);
                break;
            }
        }
    }

    //返回
    var back = function () {
        var chemical_id = document.getElementById('chemical_id').value;
        var primary_id = document.getElementById('primary_id').value;
        window.location = "index.php?r=chemical/chemicalmanage/attachlist&chemical_id="+chemical_id+"&primary_id="+primary_id;
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
        document.getElementById('uploadurl').value=file.value;
        if (!/\.(gif|jpg|jpeg|png|GIF|JPG|PNG|pdf)$/.test(file.value)) {
            alert("File types must be GIF, JPEG, JPG, PNG, PDF.");
            return false;
        }

        var video_src_file = $("#certificate_photo").val();
        var newFileName = video_src_file.split('.');
        $("#name").val(newFileName[0]);
//        alert(newFileName[1]);
        if(newFileName[1] == 'pdf'){
//            alert('pdf');
            document.getElementById("suffix").value= '2';
        }else {
            var URL = window.URL || window.webkitURL;
            var blob = URL.createObjectURL(file.files[0]);
            var img = new Image();
//        alert(blob);
            img.src = blob;
            img.onload = function () {
                var that = this;
                //生成比例
                var w = that.width, h = that.height, scale = w / h;
                new_w = 900;
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
                quality = 0.9;
                // quality值越小，所绘制出的图像越模糊
                var base64 = canvas.toDataURL('image/jpeg', quality);
                // 生成结果
                var result = {
                    base64: base64,
                    clearBase64: base64.substr(base64.indexOf(',') + 1)
                };
                $("#filebase64").val(result.base64);
                document.getElementById("suffix").value= '1';
//                btnsubmit(result.base64);
            }
        }
    }

    /**
     * 将以base64的图片url数据转换为Blob
     * @param urlData
     *            用url方式表示的base64图片数据
     */
    function convertBase64UrlToBlob(urlData){
        var bytes=window.atob(urlData.split(',')[1]);        //去掉url的头，并转换为byte
//        alert(5);
        //处理异常,将ascii码小于0的转换为大于0
        var ab = new ArrayBuffer(bytes.length);
        var ia = new Uint8Array(ab);
        for (var i = 0; i < bytes.length; i++) {
            ia[i] = bytes.charCodeAt(i);
        }

        return new Blob( [ab] , {type : 'image/png'});
    }

    function sumitImageFile(){
        var suffix = document.getElementById("suffix").value;
//        alert(suffix);
        if(suffix == '2'){
            var formData = new FormData();   //这里连带form里的其他参数也一起提交了,如果不需要提交其他参数可以直接FormData无参数的构造函数
            formData.append("file1", $('#certificate_photo')[0].files[0]);
        }else if(suffix == '1'){
            var formData = new FormData();   //这里连带form里的其他参数也一起提交了,如果不需要提交其他参数可以直接FormData无参数的构造函数
            var new_name = $('#name').val();
            //convertBase64UrlToBlob函数是将base64编码转换为Blob
            formData.append("file1", convertBase64UrlToBlob($("#filebase64").val()), new_name+'.png');  //append函数的第一个参数是后台获取数据的参数名,和html标签的input的name属性功能相同
        }else{
            alert('Please upload certificate');
            return false;
        }
        var form = document.forms[0];
        var upload_url = $("#upload_url").val();
//        console.log(form);
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
                        $("#tmp_src").val(value.file1);
                        movePic(value.file1);
                    }
                });
            },
        });
    }
    /**
     * 上传至正式服务器
     */
    function movePic(file_src) {

        $.ajax({
            type: "POST",
            url: "index.php?r=chemical/chemicalmanage/movepic",
            data:$('#form1').serialize(),
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
                if(data.status==-1){
                    $('#msgbox').addClass('alert-success');
                    $('#msginfo').html(data.msg);
                    $('#msgbox').show();
                }else{
                    $("#father").empty();
                    showTime(data.refresh);
                    back();
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                //alert(XMLHttpRequest.status);
                //alert(XMLHttpRequest.readyState);
                //alert(textStatus);
            },
        });
    }


    /**
     * 将以base64的图片url数据转换为Blob
     * @param urlData
     *            用url方式表示的base64图片数据
     */
    function convertBase64UrlToBlob(urlData){
        var bytes=window.atob(urlData.split(',')[1]);        //去掉url的头，并转换为byte
//        alert(5);
        //处理异常,将ascii码小于0的转换为大于0
        var ab = new ArrayBuffer(bytes.length);
        var ia = new Uint8Array(ab);
        for (var i = 0; i < bytes.length; i++) {
            ia[i] = bytes.charCodeAt(i);
        }

        return new Blob( [ab] , {type : 'image/png'});
    }
    var edit = function () {
        var file = $("#certificate_photo").val();
        if(file != ''){
            sumitImageFile();
        }else{
            $.ajax({
                type: "POST",
                url: "index.php?r=chemical/chemicalmanage/editaptitude",
                data:$('#form1').serialize(),
                dataType: "json",
                beforeSend: function () {

                },
                success: function(data){
                    if(data.status==-1){
                        $('#msgbox').addClass('alert-success');
                        $('#msginfo').html(data.msg);
                        $('#msgbox').show();
                    }else{
                        $("#father").empty();
                        showTime(data.refresh);
                        back();
                    }

                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    //alert(XMLHttpRequest.status);
                    //alert(XMLHttpRequest.readyState);
                    //alert(textStatus);
                },
            });
        }
    }
    //提交表单（添加）
    var formSubmit = function () {
        var user_id = document.getElementById('user_id').value;
        var params = $('#form1').serialize();
        //alert(params);
        $.ajax({
            //data: {program_id: program_id,task_name: task_name,plan_start_time:plan_start_time,plan_end_time:plan_end_time,plan_work_hour:plan_work_hour,plan_amount:plan_amount,amount_unit:amount_unit,task_content:task_content},
            //url: "index.php?r=proj/task/tnew&"+params ,
            data:$('#form1').serialize(),
            url: "kindex.php?r=chemical/chemicalmanage/newaptitude",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                if(data.status == 1) {
                    $('#msgbox').addClass('alert-success');
                    $('#msginfo').html(data.msg);
                    $('#msgbox').show();
//                    window.location = "index.php?r=comp/staff/attachlist&user_id=" + user_id;
                }else{
                    $('#msgbox').addClass('alert-danger');
                    $('#msginfo').html(data.msg);
                    $('#msgbox').show();
                }
            },
            error: function () {

                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
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