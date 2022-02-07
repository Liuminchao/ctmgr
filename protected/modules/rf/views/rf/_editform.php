
<style type="text/css">
    th{
        text-align:center;/** 设置水平方向居中 */
        vertical-align:middle/** 设置垂直方向居中 */
    }
    img{vertical-align: middle}
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
            <button type="button" class="btn btn-default" onclick="send()" >Send</button>
            <button type="button" class="btn btn-default"  onclick="cancel('<?php echo $list_list[0]['program_id']; ?>');">Cancel</button>
            <input type="hidden" id="program_id" name="rf[program_id]" value="<?php echo $list_list[0]['program_id']; ?>">
        </div>
    </div>
    <?php
        $program_model = Program::model()->findByPk($list_list[0]['program_id']);
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
                    <?php if($list_list[0]['type'] == '1'){ ?>
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
            <td><h4>Ref No: </h4><input id="check_no" name="rf[check_no]" class="form-control" style="background: #F2F2F2" type="text" value="<?php echo $list_list[0]['check_no']; ?>"><input id="check_id" name="rf[check_id]"  value="<?php echo $list_list[0]['check_id']; ?>"  type="hidden"><input id="type_id" name="rf[type_id]"  value="<?php echo $list_list[0]['type']; ?>"  type="hidden"></td>
        </tr>
    </table>
    <table class="table table-bordered" id="info">
        <tr>
            <td colspan="4"><h3>PROJECT : <?php echo $program_name; ?></h3><input type="hidden" id="program_id" name="rf[program_id]" value="<?php echo $list_list[0]['program_id']; ?>"></td>
        </tr>
        <tr>
            <td width="20%">To:</td>
            <td width="30%">
                <?php
                    $to_user = Staff::model()->findByPk($to_user_str);
                    $to_con_id = $to_user->contractor_id;
                    $to_con = Contractor::model()->findByPk($to_con_id);
                ?>
                <select  name="rf[to_company]" id="to_company" class="form-control" style="background: #F2F2F2;" >
                    <option > Please Select </option>
                    <?php foreach($contractor_list as $contractor_id => $contractor_name){ ?>
                        <?php if($contractor_id == $to_con_id){ ?>
                            <option value="<?php echo $contractor_id ?>" selected><?php echo $contractor_name ?></option>
                        <?php }else{ ?>
                            <option value="<?php echo $contractor_id ?>"><?php echo $contractor_name ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </td>
            <td width="20%">From :</td>
            <?php
                $user_model = Staff::model()->findByPk($list_list[0]['add_user']);
            ?>
            <td width="30%"><input id="from" type="hidden" name="rf[from]" class="form-control" style="background: #F2F2F2"  readonly  value="<?php echo $list_list[0]['add_user']; ?>"><input id="from" type="text"  class="form-control" style="background: #F2F2F2"  readonly  value="<?php echo $user_model->user_name; ?>"></td>
        </tr>
        <tr>
            <td width="20%">Attn :</td>
            <td width="30%">
                <select name="rf[to]" id="to_select" class="form-control" style="background: #F2F2F2;" >
                    <?php
                        $program_user = ProgramUser::UserListByMcProgram($to_con_id,$list_list[0]['program_id']);
                        foreach($program_user as $to_user_id => $to_user_name){
                            if($to_user_id == $to_user_str){
                    ?>
                                <option value="<?php echo $to_user_id ?>" selected><?php echo $to_user_name ?></option>
                    <?php }else{ ?>
                                <option value="<?php echo $to_user_id ?>" ><?php echo $to_user_name ?></option>
                    <?php }} ?>
                </select>
            </td>
            <td width="20%">Date :</td>
            <td width="30%"><input id="date" type="text" name="rf[date]" class="form-control" style="background: #F2F2F2"  readonly  value="<?php echo Utils::DateToEn(substr($list_list[0]['record_time'],0,10)); ?>"></td>
        </tr>
        <tr id="copy_to">
            <td width="10%">Copy to: </td>
            <td colspan="3" width="90%">
                <select name="cc_select" name="rf[cc]" id="cc_select" class="form-control" style="background: #F2F2F2; width: 36%;"  onchange="show_sub(this.options[this.options.selectedIndex])">
                    <?php
                    $program_user = ProgramUser::UserListByMcProgram($to_con_id,$list_list[0]['program_id']);
                    foreach($program_user as $to_user_id => $to_user_name){ ?>
                        <option value="<?php echo $to_user_id ?>" ><?php echo $to_user_name ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <?php
        $cc_user_cnt = 0;
        if(!empty($rf_user_list)) {
            foreach ($rf_user_list as $i => $j) {
                if ($j['type'] != '1') {
                    $cc_user_cnt++;
                    if($j['tag'] == '3'){
                    ?>
                        <tr><td width='30%'><input type='hidden' name='rf[cc][]' id='cc' value='<?php echo $j['user_id'] ?>' disabled><input type='hidden' name='rf[cc][]'  value='<?php echo $j['user_id'] ?>' ><?php echo $j['user_name'] ?></td> <td width='60%' colspan='2' align='right'>- enclosure <input type='radio'  name='rf[select_cc][<?php echo $j['user_id'] ?>]'  value='3'  checked /> YES   <input type='radio'  name='rf[select_cc][<?php echo $j['user_id'] ?>]'  value='4'  /> NO</td><td onclick='del_tr(this)' ><img src='img/delete_rf.png' ></td></tr>
                    <?php }else if($j['tag'] == '4'){ ?>
                        <tr><td width='30%'><input type='hidden' name='rf[cc][]' id='cc' value='<?php echo $j['user_id'] ?>' disabled><input type='hidden' name='rf[cc][]'  value='<?php echo $j['user_id'] ?>' ><?php echo $j['user_name'] ?></td> <td width='60%' colspan='2' align='right'>- enclosure <input type='radio'  name='rf[select_cc][<?php echo $j['user_id'] ?>]'  value='3'  /> YES   <input type='radio'  name='rf[select_cc][<?php echo $j['user_id'] ?>]'  value='4' checked /> NO<?php echo $cc_tag[$j['tag']]; ?></td><td onclick='del_tr(this)' ><img src='img/delete_rf.png' ></td></tr>
                    <?php }else{ ?>
                        <tr><td width='30%'><input type='hidden' name='rf[cc][]' id='cc' value='<?php echo $j['user_id'] ?>' disabled><input type='hidden' name='rf[cc][]'  value='<?php echo $j['user_id'] ?>' ><?php echo $j['user_name'] ?></td> <td width='60%' colspan='2' align='right'>- enclosure <input type='radio'  name='rf[select_cc][<?php echo $j['user_id'] ?>]'  value='3'  /> YES   <input type='radio'  name='rf[select_cc][<?php echo $j['user_id'] ?>]'  value='4'  /> NO<?php echo $cc_tag[$j['tag']]; ?></td><td onclick='del_tr(this)' ><img src='img/delete_rf.png' ></td></tr>
                 <?php  } ?>
                <?php }
            }
        }
        ?>
        <input id="cc_cnt" type="hidden"  class="form-control"  value="<?php echo $cc_user_cnt; ?>">
        <input id="cc_str" type="hidden"  class="form-control" value="<?php echo $cc_user_str; ?>">

        <tr>
            <td width="20%">Subject :</td>
            <td width="30%">
                <input id="cc" class="form-control" name="rf[subject]" style="background: #F2F2F2" type="text"  value="<?php echo $list_list[0]['subject']; ?>">
            </td>
            <td width="20%">Latest Date to Reply :</td>
            <td width="30%"><input id="valid_time" class="form-control b_date_ins" style="background: #F2F2F2" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" check-type="" name="rf[valid_time]" type="text" value="<?php echo Utils::DateToEn($list_list[0]['valid_time']); ?>"></td>
        </tr>
        <tr>
            <td colspan="4" >Description: </td>
        </tr>
        <tr>
            <td width="30%">Particulars of Information (Related to):</td>
            <td width="70%" colspan="3">
                <input id="related" class="form-control" name="rf[related]" style="background: #F2F2F2" type="text"  value="<?php echo $list_list[0]['related_to']; ?>">
            </td>
        </tr>
        <tr>
            <td width="30%">Location, Drawing Ref No:</td>
            <td width="70%" colspan="3">
                <input id="location" class="form-control" name="rf[location]" style="background: #F2F2F2" type="text"  value="<?php echo $list_list[0]['location_ref']; ?>">
            </td>
        </tr>
        <?php if($list_list[0]['type'] == '2'){ ?>
            <tr>
                <td width="30%">Specification Ref (Clause):</td>
                <td width="70%" colspan="3">
                    <div id="checkboxDiv">
                        <?php if($list_list[0]['spec_ref'] == 'Complied'){ ?>
                            <input type="radio" id="complied" name="rf[spec_ref]"  value="Complied" checked   /> Complied
                            <input type="radio" id="partially_complied" name="rf[spec_ref]"  value="Partially Complied" style="padding-left: 4px;"  /> Partially Complied
                            <input type="radio" id="not_complied" name="rf[spec_ref]"  value="Not Complied" style="padding-left: 4px;"  /> Not Complied
                        <?php }else if($list_list[0]['spec_ref'] == 'Partially Complied'){ ?>
                            <input type="radio" id="complied" name="rf[spec_ref]"  value="Complied"   /> Complied
                            <input type="radio" id="partially_complied" name="rf[spec_ref]"  value="Partially Complied" style="padding-left: 4px;" checked  /> Partially Complied
                            <input type="radio" id="not_complied" name="rf[spec_ref]"  value="Not Complied" style="padding-left: 4px;"  /> Not Complied
                        <?php }else{ ?>
                            <input type="radio" id="complied" name="rf[spec_ref]"  value="Complied"   /> Complied
                            <input type="radio" id="partially_complied" name="rf[spec_ref]"  value="Partially Complied" style="padding-left: 4px;"  /> Partially Complied
                            <input type="radio" id="not_complied" name="rf[spec_ref]"  value="Not Complied" style="padding-left: 4px;" checked  /> Not Complied
                        <?php } ?>
                    </div>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td width="30%">Others (Email):</td>
            <td width="70%" colspan="3">
                <input id="others" class="form-control" name="rf[others]" style="background: #F2F2F2" type="text"  value="<?php echo $list_list[0]['others']; ?>">
            </td>
        </tr>
        <tr id="attachment">
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
        <?php
        $attachment_list = RfAttachment::dealListBystep($check_id,$step);
        if(!empty($attachment_list)){
            foreach($attachment_list as $i => $j){
                ?>
                <tr><td colspan="2"><img src='img/pdf_min.png'><?php echo $j['doc_name'] ?></td><td  colspan="2"><button type='button' onclick='preview_attachment("<?php echo $j['doc_path']; ?>")'>Preview</button><button type='button' onclick='del_tr(this)'>Delete</button><input type='hidden' name='rf[attachment][]' value='<?php echo $j['doc_path']; ?>' ></td></tr>
            <?php }
        }?>
        <tr>
            <td ><img  src="img/pic_min.png" > Viewpoint</td>  <td  ><button type="button"  onclick="edit_view()">Edit</button><input type="hidden" id="view" name="rf[view]" ><input type="hidden" id="model_view_id" name="rf[model_view_id]" ></td><td ><img  src="img/component_min.png" >  Component name</td> <td ><button type="button"  onclick="edit_component()">Edit</button><input type="hidden" id="uuid" name="rf[uuid]" ><input type="hidden" id="entityId" name="rf[entityId]" ><input type="hidden" id="model_component_id" name="rf[model_component_id]" ></td>
        </tr>
        <tr>
            <?php if($list_list[0]['type'] == '2'){ ?>
                <td width="30%">Reason(s) for RFA:</td>
            <?php }else{ ?>
                <td width="30%">Reason(s) for RFI:</td>
            <?php } ?>
            <td width="70%" colspan="3">
                <textarea rows="10" cols="95" id="message" name = "rf[message]" ><?php echo $detail_list[0]['remark']; ?></textarea>
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


    function edit_component() {
        var diag = new Dialog();
        diag.Width = 800;
        diag.Height = 770;
        diag.Title = "Model Component";
        diag.URL = "editcomponent&step=<?php echo $step; ?>&check_id=<?php echo $check_id; ?>&program_id=<?php echo $list_list[0]['program_id']; ?>";
        diag.show();
    }
    function edit_view() {
        var diag = new Dialog();
        diag.Width = 800;
        diag.Height = 770;
        diag.Title = "Model View";
        diag.URL = "editview&step=<?php echo $step; ?>&check_id=<?php echo $check_id; ?>&program_id=<?php echo $list_list[0]['program_id']; ?>";
        diag.show();
    }
    // 删除一行
    function del_tr(obj){
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
                    window.location = "index.php?r=rf/rf/list&program_id="+<?php echo $list_list[0]['program_id']; ?>;
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
