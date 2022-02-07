
<style type="text/css">
    th{
        text-align:center;/** 设置水平方向居中 */
        vertical-align:middle/** 设置垂直方向居中 */
    }
    img{vertical-align: middle}
    #info td{
        border-top: white;
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
    <?php
    $rf_model = RfList::model()->findByPk($check_id);
    $check_no = $rf_model->check_no;
    $program_id = $rf_model->program_id;
    $add_user_id = $rf_model->add_user;
    $add_user = Staff::model()->findByPk($add_user_id);
    $add_user_name = $add_user->user_name;
    $program_model = Program::model()->findByPk($program_id);
    $program_name = $program_model->program_name;
    $con_id = $program_model->contractor_id;
    $con_model = Contractor::model()->findByPk($con_id);
    $con_name = $con_model->contractor_name;
    $con_adr = $con_model->company_adr;
    $con_phone = $con_model->link_phone;
    $to_user_str = $rf_model->to_user;
    ?>
    <!--    <div class="col-xs-2" style="padding-left: 0;display:table-cell;vertical-align:middle;height: 100;">-->
    <!--        <img src="img/1.png" width="100%" height="50" alt="previous">-->
    <!--    </div>-->
    <table class="table " id="info"  style="border: 1px solid #dddddd;">
        <tr>
            <td colspan="4"><h3>Ref No. : <?php echo $check_no; ?></h3><input type="hidden" id="program_id" name="rf[program_id]" value="<?php echo $program_id; ?>"><input id="check_id" name="rf[check_id]"  value="<?php echo $check_id; ?>"  type="hidden"></td>
        </tr>
        <tr>
            <td width="10%">To:</td>
            <td width="40%">
                <?php
                    $to_user = Staff::model()->findByPk($to_user_str);
                    $to_con_id = $to_user->contractor_id;
                    $to_con = Contractor::model()->findByPk($to_con_id);
                ?>
                <select  name="rf[to_company]" id="to_company" class="form-control" style="background: #F2F2F2;" >
                    <option value="<?php echo $con_id ?>" selected><?php echo $con_name ?></option>
                </select>
            </td>
            <td width="10%">From :</td>
            <td width="40%"><input id="from" type="text" name="rf[from]" class="form-control" style="background: #F2F2F2"  readonly  value="<?php echo $to_user->user_name; ?>"></td>
        </tr>
        <tr>
            <td width="10%">Attn :</td>
            <td width="40%">
                <select name="rf[to]" id="to_select" class="form-control" style="background: #F2F2F2;" >
                    <option value="<?php echo $add_user_id ?>" selected><?php echo $add_user_name ?></option>
                </select>
            </td>
            <td width="10%">Date :</td>
            <td width="40%"><input id="date" type="text" name="rf[date]" class="form-control" style="background: #F2F2F2"  readonly  value="<?php echo Utils::DateToEn(date('Y-m-d')); ?>"></td>
        </tr>
        <tr id="copy_to">
            <td width="10%">Copy to: </td>
            <td colspan="3" width="90%">
                <select name="cc_select" name="rf[cc]" id="cc_select" class="form-control" style="background: #F2F2F2; width: 36%;"  onchange="show_sub(this.options[this.options.selectedIndex])">
                    <?php
                    $program_user = ProgramUser::UserListByMcProgram($con_id,$program_id);
                    foreach($program_user as $to_user_id => $to_user_name){ ?>
                        <option value="<?php echo $to_user_id ?>" ><?php echo $to_user_name ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <input id="cc_cnt" type="hidden"  class="form-control"  value="<?php echo $cc_user_cnt; ?>">
        <input id="cc_str" type="hidden"  class="form-control" value="<?php echo $cc_user_str; ?>">

        <tr>
            <td colspan="4">
                Consultant’s Reply: Enclosure  <input type='radio'  name='rf[reply_attachment]'  value='1'  /> YES   <input type='radio'  name='rf[reply_attachment]'  value='2'  /> NO
            </td>
        </tr>
        <tr id="attachment" >
            <td width="30%">Attachment</td>
            <td width="70%"  colspan="3">
                <input id="file" type="hidden" value="" name="RaBasic[ra_path]" /><input id="ra_path" class="form-control" check-type="" style="display:none" onchange="raupload(this)" name="RaBasic[ra_path]" type="file" />                        <div class="input-group col-md-6 padding-lr5">
                    <input id="raurl" class="form-control" type="text" disabled>
                    <span class="input-group-btn">
                                    <a class="btn btn-warning" onclick="$('input[id=ra_path]').click();">
                                <i class="fa fa-folder-open-o"></i> Browse                            </a>
                    </span>
            </td>
        </tr>
        <tr id="model" >
            <td ><img  src="img/pic_min.png" > Viewpoint</td>  <td  ><button type="button"  onclick="edit_view()">Edit</button><input type="hidden" id="view" name="rf[view]" ><input type="hidden" id="model_view_id" name="rf[model_view_id]" ></td><td colspan="2"><img  src="img/component_min.png" >  Component name  <button type="button"  onclick="edit_component()">Edit</button><input type="hidden" id="uuid" name="rf[uuid]" ><input type="hidden" id="entityId" name="rf[entityId]" ><input type="hidden" id="model_component_id" name="rf[model_component_id]" ></td>
        </tr>

    <?php if($deal_type != '4'){ ?>

            <tr>
                <td width="30%">Message:</td>
                <td width="70%" colspan="3">
                    <textarea rows="10" cols="95" id="message" name = "rf[message]" value="<?php echo $detail_list[0]['remark']; ?>"></textarea>
                </td>
            </tr>
        </table>
    <?php }else{ ?>
        <tr>
            <input id="message" name="rf[message]" type="hidden" value="">
        </tr>
        </table>
    <?php } ?>

    <div class="row">
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="button" id="sbtn" class="btn btn-primary"
                        style="margin-left: 5px; background: #44ACB7" onclick="cancel('<?php echo $check_id; ?>');">Cancel</button>
                <?php if($deal_type == '2'){ ?>
                    <button type="button" class="btn btn-primary"
                            style="margin-left: 35px; background: #44ACB7" onclick="send('<?php echo $deal_type; ?>')">Send</button>
                <?php }else if($deal_type == '3'){ ?>
                    <button type="button" class="btn btn-primary"
                            style="margin-left: 35px; background: #44ACB7" onclick="send('<?php echo $deal_type; ?>')">Send</button>
                <?php }else if($deal_type == '6'){ ?>
                    <button type="button" class="btn btn-primary"
                            style="margin-left: 35px; background: #44ACB7" onclick="send('<?php echo $deal_type; ?>')">Reject</button>
                <?php }else if($deal_type == '4'){ ?>
                    <button type="button" class="btn btn-primary"
                            style="margin-left: 35px; background: #44ACB7" onclick="send('<?php echo $deal_type; ?>')">Approve</button>
                <?php }else if($deal_type == '5'){ ?>
                    <button type="button" class="btn btn-primary"
                            style="margin-left: 35px; background: #44ACB7" onclick="send('<?php echo $deal_type; ?>')">Approve</button>
                <?php } ?>
            </div>
        </div>
    </div>
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
        if(cc_str.indexOf(v.value) == -1){
            cc_cnt++;
            cc_str = cc_str + v.value + ',';
//            cc_str = cc_str.substr(0, cc_str.length - 1);//去掉末尾的逗号
            $('#cc_cnt').val(cc_cnt);
            $('#cc_str').val(cc_str);
            if(cc_cnt % 2 == 0){
                $(table).after("<tr><td width='30%'><input type='hidden' name='rf[cc][]' id='cc' value='"+v.value+"' disabled><input type='hidden' name='rf[cc][]'  value='"+v.value+"' >"+v.text+"</td>"+v.text+"</td> <td width='60%' colspan='2' align='right'>Enclosure  <input type='radio'  name='rf[select_cc]["+v.value+"]'  value='3'  /> YES   <input type='radio'  name='rf[select_cc]["+v.value+"]'  value='4'  /> NO</td><td onclick='del_tr(this)' ><img src='img/delete_rf.png' ></td></tr>");
            }else{
                $(table).after("<tr><td width='30%'><input type='hidden' name='rf[cc][]' id='cc' value='"+v.value+"' disabled><input type='hidden' name='rf[cc][]'  value='"+v.value+"' >"+v.text+"</td>"+v.text+"</td> <td width='60%'  colspan='2' align='right'>Enclosure  <input type='radio'  name='rf[select_cc]["+v.value+"]'  value='3'  /> YES   <input type='radio'  name='rf[select_cc]["+v.value+"]'  value='4'  /> NO</td><td onclick='del_tr(this)' ><img src='img/delete_rf.png' ></td></tr>");
            }
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
        diag.URL = "addcomponent";
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
        diag.URL = "addview";
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
    function send(deal_type) {
        var send_url = '';
        if(deal_type == '2'){
            send_url = 'index.php?r=rf/rf/savereply';
        }else if(deal_type == '6'){
            send_url = 'index.php?r=rf/rf/savereject';
        }else if(deal_type == '4'){
            send_url = 'index.php?r=rf/rf/saveapprove';
        }else if(deal_type == '5'){
            send_url = 'index.php?r=rf/rf/saveapprovecomment';
        }
        var view = sessionStorage.getItem("view");
        $('#view').val(view);
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
            url: send_url,
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
                    alert('success');
                    window.location = "index.php?r=rf/rf/info&check_id=<?php echo $check_id; ?>";
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
    function cancel (check_id) {
        window.location = "index.php?r=rf/rf/info&check_id="+check_id;
    }
    // 删除一行
    function del_tr(obj){
        var index = $(obj).parents("tr").index(); //这个可获取当前tr的下标 未使用
        $(obj).parents("tr").remove(); //实现删除tr
    }

    function change_attachment(tag){
        alert(tag);
        var attachment = document.getElementById('attachment');
        var model = document.getElementById('model');
        if(tag == '1'){
            attachment.style.display = "block";
            model.style.display = "block";
        }else{
            attachment.style.display = "none";
            model.style.display = "none";
        }
    }
</script>
