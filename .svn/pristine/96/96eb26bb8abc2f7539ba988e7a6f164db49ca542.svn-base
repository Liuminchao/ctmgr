
<style type="text/css">
    th{
        text-align:center;/** 设置水平方向居中 */
        vertical-align:middle/** 设置垂直方向居中 */
    }
    img{vertical-align: middle}
    #info td{
        border-top: white;
    }
    td{
        word-wrap:break-word;
    }
</style>
<?php

$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'focus' => array($rs, 'old_pwd'),
    'autoValidation' => false,
//    "action" => "javascript:formSubmit()",
//    'enableAjaxSubmit' => false,
//    'ajaxUpdateId' => 'content-body',
//    'role' => 'form', //可省略
//    'formClass' => 'form-horizontal', //可省略 表单对齐样式

));
?>
<div class="box-body">
    <div class="col-xs-1">
    </div>
    <div class="col-xs-10">
    <div id='msgbox' class='alert alert-dismissable ' style="display:none;">
        <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
        <strong id='msginfo'></strong><span id='divMain'></span>
    </div>
    <div class="row" >
        <div class ="col-md-4 col-md-offset-9">
            <button type="button" class="btn btn-default" onclick="savedraft()" >Save as Draft</button>
            <button type="button" class="btn btn-default" onclick="send()" >Send</button>
            <button type="button" class="btn btn-default"  onclick="cancel('<?php echo $program_id; ?>');">Cancel</button>
            <input type="hidden" id="program_id" name="rf[program_id]" value="<?php echo $program_id; ?>">
            <input type="hidden" id="filebase64"/>
        </div>
    </div>
    <?php
        $program_model = Program::model()->findByPk($program_id);
        $program_name = $program_model->program_name;
        $con_id = $program_model->contractor_id;
        $con_model = Contractor::model()->findByPk($con_id);
        $con_name = $con_model->contractor_name;
        $con_adr = $con_model->company_adr;
        $con_phone = $con_model->link_phone;
    ?>
    <table class="table" style="border: 1px solid #dddddd;margin-bottom: 0px;">
        <tr>
            <td width="25%" align="center">
                <h3>
                    <?php if($type == '1'){ ?>
                        Request For Information (XXX)
                    <?php }else{ ?>
                        Shopdrawiing For Approvall (XXX)
                    <?php } ?>
                </h3>
            </td>
            <td rowspan="2" width="50%" style="border: 1px solid #dddddd;border-right: white;">
                <h3>
                    <?php echo $con_name; ?><br>
                    <?php echo $con_adr; ?><br>
                    <?php echo $con_phone; ?><br>
                </h3>
            </td>
            <td rowspan="2" width="25%" >
                <?php
                    $logo = $con_model->remark;
                    $logo_pic = 'img/RF.jpg';
                    if($logo_pic != ''){
                        //                $logo_pic = '/opt/www-nginx/web'.$con_model->remark;
                ?>
                    <img src="<?php echo $logo_pic; ?>" width="100%"  alt="previous" style="padding-top: 23px;">
                <?php }  ?>
            </td>
        </tr>
        <tr>
            <td><h4>Ref No: </h4><input id="check_no" name="rf[check_no]" class="form-control" style="background: #F2F2F2" type="text"><input id="type_id" name="rf[type_id]"  value="<?php echo $type; ?>"  type="hidden"><input id ="data_id"  name="rf[data_id]" type="hidden" value="<?php echo $data_id; ?>"></td>
        </tr>
    </table>

    <table class="table " id="info"  style="border: 1px solid #dddddd;">
        <tr>
            <td colspan="4"><h3>PROJECT : <?php echo $program_name; ?></h3><input type="hidden" id="program_id" name="rf[program_id]" value="<?php echo $program_id; ?>"></td>
        </tr>
        <tr >
            <td width="20%" >To:</td>
            <td width="30%" >
                <select  name="rf[to_company]" id="to_company" class="form-control" style="background: #F2F2F2;" >
                    <option > Please Select </option>
                    <?php foreach($contractor_list as $contractor_id => $contractor_name){ ?>
                        <option value="<?php echo $contractor_id ?>"><?php echo $contractor_name ?></option>
                    <?php } ?>
                </select>
            </td>
            <td width="20%">From :</td>
            <?php
            $user_phone = Yii::app()->user->id; $user = Staff::userByPhone($user_phone); $user_id = $user[0]['user_id']; $user_model = Staff::model()->findByPk($user_id);
            ?>
            <td width="30%"><input id="from" type="hidden" name="rf[from]" class="form-control" style="background: #F2F2F2"  readonly  value="<?php  echo $user_id; ?>"><input id="from" type="text" class="form-control" style="background: #F2F2F2"  readonly  value="<?php  echo $user_model->user_name; ?>"></td>
        </tr>
        <tr>
            <td width="20%" style="border-bottom: white;">Attn :</td>
            <td width="30%">
                <select name="rf[to]" id="to_select" class="form-control" style="background: #F2F2F2;" >
                </select>
            </td>
            <td width="20%">Date :</td>
            <td width="30%"><input id="date" type="text" name="rf[date]" class="form-control" style="background: #F2F2F2"  readonly  value="<?php echo Utils::DateToEn(date('Y-m-d')); ?>"></td>
        </tr>
        <tr id="copy_to">
            <td width="10%">Copy to: </td>
            <td colspan="3" width="90%">
                <select name="cc_select" name="rf[cc]" id="cc_select" class="form-control" style="background: #F2F2F2; width: 36%;"  onchange="show_sub(this.options[this.options.selectedIndex])">
                </select>
                <input id="cc_cnt" type="hidden"  class="form-control"  value="0">
                <input id="cc_str" type="hidden"  class="form-control" value="">
            </td>
        </tr>

        <tr>
            <td width="20%">Subject :</td>
            <td width="30%">
                <input id="cc" class="form-control" name="rf[subject]" style="background: #F2F2F2" type="text"  value="">
            </td>
            <td width="20%">Latest Date to Reply :</td>
            <td width="30%"><input id="valid_time" class="form-control b_date_ins" style="background: #F2F2F2" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" check-type="" name="rf[valid_time]" type="text"></td>
        </tr>
        <tr>
            <td colspan="4" >Description: </td>
        </tr>
        <tr>
            <td width="20%">Particulars of Information (Related to):</td>
            <td width="80%"  colspan="3">
                <input id="related" class="form-control" name="rf[related]" style="background: #F2F2F2" type="text"  value="">
            </td>
        </tr>
        <tr>
            <td width="20%">Location, Drawing Ref No:</td>
            <td width="80%"  colspan="3">
                <input id="location" class="form-control" name="rf[location]" style="background: #F2F2F2" type="text"  value="">
            </td>
        </tr>
        <?php if($type == '2'){ ?>
            <tr>
                <td width="20%">Specification Ref (Clause):</td>
                <td width="80%" colspan="3">
                    <div id="checkboxDiv">
                        <input type="radio" id="complied" name="rf[spec_ref]"  value="Complied"  /> Complied
                        <input type="radio" id="partially_complied" name="rf[spec_ref]"  value="Partially Complied" style="padding-left: 4px;" /> Partially Complied
                        <input type="radio" id="not_complied" name="rf[spec_ref]"  value="Not Complied" style="padding-left: 4px;" /> Not Complied
                    </div>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td width="20%">Others (Email):</td>
            <td width="80%"  colspan="3">
                <input id="others" class="form-control" name="rf[others]" style="background: #F2F2F2" type="text"  value="">
            </td>
        </tr>
        <tr id="attachment">
            <td width="20%">Attachment</td>
            <td width="80%"  colspan="3">
                <input id="file" type="hidden" value="" name="RaBasic[ra_path]" /><input id="ra_path" class="form-control" check-type="" style="display:none" onchange="raupload(this)" name="RaBasic[ra_path]" type="file" />                        <div class="input-group col-md-6 padding-lr5">
                    <input id="raurl" class="form-control" type="text" disabled>
                    <span class="input-group-btn">
                                    <a class="btn btn-warning" onclick="$('input[id=ra_path]').click();">
                                <i class="fa fa-folder-open-o"></i> Browse                            </a>
                    </span>
            </td>
        </tr>
        <tr>
            <td ><img  src="img/pic_min.png" > Viewpoint</td>  <td  ><button type="button"  onclick="edit_view()">Edit</button><input type="hidden" id="view" name="rf[view]" ><input type="hidden" id="model_view_id" name="rf[model_view_id]" ></td><td ><img  src="img/component_min.png" >  Component name</td> <td ><button type="button"  onclick="edit_component()">Edit</button><input type="hidden" id="uuid" name="rf[uuid]" ><input type="hidden" id="entityId" name="rf[entityId]" ><input type="hidden" id="model_component_id" name="rf[model_component_id]" ></td>
        </tr>
        <tr>
            <td width="20%">Message:</td>
            <td width="80%" colspan="3">
                <textarea rows="10" style="width: 100%;" id="message" name = "rf[message]"></textarea>
            </td>
        </tr>
    </table>
</div>
    <div class="col-xs-1">
    </div>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript" src="js/ajaxfileupload.js"></script>
<script type="text/javascript" src="js/loading.js"></script>
<script type="text/javascript" src="js/zDrag.js"></script>
<script type="text/javascript" src="js/zDialog.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        //处理角色及角色分组
        $('#to_company').change(function(){

            var toObj = $("#to_select");
            var toOpt = $("#to_select option");
            var ccObj = $("#cc_select");
            var ccOpt = $("#cc_select option");

            if ($(this).val() == 0) {
                toOpt.remove();
                ccOpt.remove();
                return;
            }
            $.ajax({
                type: "POST",
                url: "index.php?r=rf/rf/queryuser",
                data: {from:$("#to_company").val(),program_id:$("#program_id").val()},
                dataType: "json",
                success: function(data){ //console.log(data);

                    toOpt.remove();
                    ccOpt.remove();
                    if (!data) {
                        return;
                    }
                    ccObj.append("<option value='0'> Please Select </option>");
                    for (var o in data) {//console.log(o);
                        toObj.append("<option value='"+o+"'>"+data[o]+"</option>");
                        ccObj.append("<option value='"+o+"'>"+data[o]+"</option>");
                    }
                },
            });
        });

        $(function(){
            $("#checkboxDiv").find(":checkbox").each(function(){
                $(this).click(function(){
                    if($(this).is(':checked')){
                        $(this).attr('checked',true).siblings().attr('checked',false);
                    }else{
                        $(this).attr('checked',false).siblings().attr('checked',false);
                    }
                });
            });
        });
    });
    function show_sub(v){
        var cc_cnt = $('#cc_cnt').val();
        var cc_str = $('#cc_str').val();
        var table = document.getElementById("copy_to");//获取对应
        if(cc_str.indexOf(v.value) == -1 && v.value != '0'){
            cc_cnt++;
            cc_str = cc_str + v.value + ',';
//            cc_str = cc_str.substr(0, cc_str.length - 1);//去掉末尾的逗号
            $('#cc_cnt').val(cc_cnt);
            $('#cc_str').val(cc_str);
            $(table).after("<tr><td  align='right' ><input type='hidden' name='rf[cc][]'  value='"+v.value+"' >"+v.text+"</td> <td   align='center' colspan='2'>Enclosure  <input type='radio'  name='rf[select_cc]["+v.value+"]'  value='3'  /> YES   <input type='radio'  name='rf[select_cc]["+v.value+"]'  value='4'  /> NO</td><td onclick='del_tr(this)' ><img src='img/delete_rf.png' ></td></tr>");
        }
    }

    function show_component() {
        var diag = new Dialog();
        diag.Width = 800;
        diag.Height = 770;
        diag.Title = "Model Component";
        diag.URL = "showcomponent";
        diag.show();
    }
    function edit_component() {
        var diag = new Dialog();
        diag.Width = 800;
        diag.Height = 770;
        diag.Title = "Model Component";
        diag.URL = "addcomponent&program_id="+<?php echo $program_id; ?>;
        diag.show();
    }
    function show_view() {
        var diag = new Dialog();
        diag.Width = 800;
        diag.Height = 770;
        diag.Title = "Model View";
        diag.URL = "showview";
        diag.show();
    }
    function edit_view() {
        var diag = new Dialog();
        diag.Width = 800;
        diag.Height = 770;
        diag.Title = "Model View";
        diag.URL = "addview&program_id="+<?php echo $program_id; ?>;
        diag.show();
    }
    // 删除一行
    function del_attachment(obj){
        var index = $(obj).parents("tr").index(); //这个可获取当前tr的下标
        $(obj).parents("tr").remove();
    }
    //浏览PDF
    function preview_attachment(path){
        var tag = path.slice(-3);
        if(tag == 'pdf'){
            window.open("index.php?r=rf/rf/preview&doc_path="+path,"_blank");
        }else{
            window.open('https://shell.cmstech.sg'+path,"_blank");
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

    var raupload = function(file){
        if (!/\.(gif|jpg|jpeg|png|GIF|JPG|PNG|pdf|doc)$/.test(file.value)) {
            alert("Please upload document in either .gif, .jpeg, .jpg, .png or .pdf format.");
            return false;
        }
        ra_tag = $('#ra_path')[0].files[0].name.lastIndexOf(".");
        ra_name = $('#ra_path')[0].files[0].name.substr(0,ra_tag);
        var video_src_file = $("#ra_path").val();
        document.getElementById('raurl').value=file.value;
        containSpecial = new RegExp(/[(\~)(\%)(\^)(\&)(\*)(\()(\))(\[)(\])(\{)(\})(\|)(\\)(\;)(\:)(\')(\")(\,)(\.)(\/)(\?)(\)]+/);
        status = containSpecial.test(ra_name);
        if(status == 'true'){
            alert('File name contains special characters, please check before uploading');
            return false;
        }
        var newFileName = video_src_file.split('.');

        var formData = new FormData();   //这里连带form里的其他参数也一起提交了,如果不需要提交其他参数可以直接FormData无参数的构造函数
        formData.append("file1", $('#ra_path')[0].files[0]);

        $.ajax({
            url: "https://shell.cmstech.sg/appupload",
            type: "POST",
            data: formData,
            dataType: "json",
            processData: false,         // 告诉jQuery不要去处理发送的数据
            contentType: false,        // 告诉jQuery不要去设置Content-Type请求头
            beforeSend: function () {
                addcloud();
            },
            success: function (data) {
                removecloud();//去遮罩
                $.each(data, function (name, value) {
                    if (name == 'data') {
                        //<tr><td width="20%"><img  src="img/pdf_min.png" ></td> <td  width="30%">drawing</td> <td  width="20%">Preview</td> <td  width="20%">Delete</td></tr>
//                        var $tr = $("<tr>"+
//                            "<td>"+name+"</td>"+
//                            "<td>"+email+"</td>"+
//                            "<td>"+tel+"</td>"+
//                            "<td><a href='deleteEmp?id="+name+"'>删除</a></td>"
//                            +"</tr>");
//                        alert(value.file1);
                        var $tr = $("<tr> <td  colspan='2'><img  src='img/pdf_min.png' >"+video_src_file+"</td> <td  colspan='2'>"+"<button type='button' onclick='preview_attachment(\""+value.file1+"\")'>Preview</button>"+"<button type='button' onclick='del_attachment(this)'>Delete</button><input type='hidden' name='rf[attachment][]' value='"+value.file1+"' ></td></tr>");
                        var $table = $("#attachment");
                        $table.after($tr);
                    }
                });
            }
        });
    }

    //添加表单其他元素
    function send() {
        var check_id = $('#check_id').val();
        var binary = sessionStorage.getItem("view");
        $('#view').val(binary);
        var model_view_id = sessionStorage.getItem("model_view_id");
        $('#model_view_id').val(model_view_id);
        var uuid = sessionStorage.getItem("uuid");
        $('#uuid').val(uuid);
        var entityId = sessionStorage.getItem("entityId");
        $('#entityId').val(entityId);
        var model_component_id = sessionStorage.getItem("model_component_id");
        $('#model_component_id').val(model_component_id);
        $.ajax({
            data:$('#form1').serialize(),
            url: "index.php?r=rf/rf/send",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                if(data.status == '-1'){
                    $('#msgbox').addClass('alert-danger fa-ban');
                    $('#msginfo').html(data.msg);
                    $('#msgbox').show();
                }
                if(data.status == '1'){
                    $('#msgbox').addClass('alert-success fa-ban');
                    $('#msginfo').html(data.msg);
                    $('#msgbox').show();
                    sessionStorage.clear();
                    alert('success');
                    window.location = "index.php?r=rf/rf/list&program_id=<?php echo $program_id; ?>";
                }
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }

    //添加表单其他元素
    function savedraft() {
        var check_id = $('#check_id').val();
        var binary = sessionStorage.getItem("view");
        $('#view').val(binary);
        var model_view_id = sessionStorage.getItem("model_view_id");
        $('#model_view_id').val(model_view_id);
        var uuid = sessionStorage.getItem("uuid");
        $('#uuid').val(uuid);
        var entityId = sessionStorage.getItem("entityId");
        $('#entityId').val(entityId);
        var model_component_id = sessionStorage.getItem("model_component_id");
        $('#model_component_id').val(model_component_id);
        $.ajax({
            data:$('#form1').serialize(),
            url: "index.php?r=rf/rf/savedraft",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                if(data.status == '-1'){
                    $('#msgbox').addClass('alert-danger fa-ban');
                    $('#msginfo').html(data.msg);
                    $('#msgbox').show();
                }
                if(data.status == '1'){
                    $('#msgbox').addClass('alert-success fa-ban');
                    $('#msginfo').html(data.msg);
                    $('#msgbox').show();
                    sessionStorage.clear();
                    alert('success');
                    window.location = "index.php?r=rf/rf/list&program_id=<?php echo $program_id; ?>";
                }
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }

    //取消
    function cancel (program_id) {
        window.location = "index.php?r=rf/rf/list&program_id="+program_id;
    }
    // 删除一行
    function del_tr(obj){
        var index = $(obj).parents("tr").index(); //这个可获取当前tr的下标 未使用
        $(obj).parents("tr").remove(); //实现删除tr
    }
</script>
