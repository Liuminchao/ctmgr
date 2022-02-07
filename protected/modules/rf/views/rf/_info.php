
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
    <div class="col-xs-10" >
<!--    <div class="row" style="padding-left: 15px">-->
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
                <?php
                    if ($list_list[0]['type'] == '2') {
                ?>
                        <td width="25%" align="center"><h3>Shopdrawing For Approval (XXX)</h3></td>
                <?php
                    }else {
                ?>
                        <td width="25%" align="center"><h3>Request For Information (XXX)</h3></td>
                <?php
                    }
                ?>
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
                <td><h4>Ref No:  <?php echo $list_list[0]['check_no']; ?></h4></td>
            </tr>
        </table>
<!--    </div>-->
    <table class="table " id="info"  style="border: 1px solid #dddddd;padding-top: 0">
        <tr>
            <td colspan="4"><h3>PROJECT : <?php echo $program_name; ?></h3><input type="hidden" id="program_id" name="rf[program_id]" value="<?php echo $program_id; ?>"></td>
        </tr>
        <tr>
            <td  style="border: 1px solid #dddddd;">To:</td>
            <td >
                <?php
                    $rf_model = RfList::model()->findByPk($check_id);
                    $step = RfDetail::stepByType($list_list[0]['check_id'],'1');
                    $to_user = RfUser::userList($list_list[0]['check_id'],$step,'1');
                    $user_id =  $to_user[0]['user_id'];
                    $user_model = Staff::model()->findByPk($user_id);
                    $contractor_id = $user_model->contractor_id;
                    $con_model = Contractor::model()->findByPk($contractor_id);
                    $add_user_id = $rf_model->add_user;
                    $add_user = Staff::model()->findByPk($add_user_id);
                    $add_user_name = $add_user->user_name;
                    echo $con_model->contractor_name;
                ?>
            </td>
            <td style="border: 1px solid #dddddd;">From :</td>
            <td style="border: 1px solid #dddddd;">
                <?php
                    echo $add_user_name;
                ?>
            </td>
        </tr>
        <tr>
            <td width="20%" style="border: 1px solid #dddddd;">Attn :</td>
            <td width="30%" style="border: 1px solid #dddddd;">
                <?php
                    $to_user_model = Staff::model()->findByPk($list_list[0]['to_user']);
                    echo $to_user_model->user_name;
                ?>
            </td>
            <td width="20%" style="border: 1px solid #dddddd;">Date :</td>
            <td width="30%" style="border: 1px solid #dddddd;"><?php echo Utils::DateToEn(substr($list_list[0]['record_time'],0,10)); ?></td>
        </tr>
        <tr >
            <td width="10%" colspan="4" style="border: 1px solid #dddddd;">Copy to: </td>
        </tr>
        <?php
        $step = RfDetail::stepByType($list_list[0]['check_id'],'1');
        $cc_user = RfUser::userList($list_list[0]['check_id'],$step,'2');
        $cc_usr_str = '';
        $cc_tag['3'] = 'YES';
        $cc_tag['4'] = 'NO';
        $cc_tag['0'] = 'Y/N';
        foreach($cc_user as $i => $j){
            ?>
            <tr><td width='30%' style="border: 1px solid #dddddd;"><input type='hidden' name='rf[cc][]' id='cc' value='<?php echo $j['user_id'] ?>' disabled><?php echo $j['user_name'] ?></td> <td width='70%' colspan='3' align='right'>- enclosure <?php echo $cc_tag[$j['tag']]; ?></td></tr>
        <?php } ?>
        <tr>
            <td width="20%" style="border: 1px solid #dddddd;">Subject :</td>
            <td width="30%" style="border: 1px solid #dddddd;">
                <?php echo $list_list[0]['subject']; ?>
            </td>
            <td width="20%" style="border: 1px solid #dddddd;">Latest Date to Reply :</td>
            <td width="30%" style="border: 1px solid #dddddd;"><?php echo Utils::DateToEn($list_list[0]['valid_time']); ?></td>
        </tr>
        <tr>
            <td colspan="4" style="border: 1px solid #dddddd;">Description: </td>
        </tr>
        <tr>
            <td width="30%" style="border: 1px solid #dddddd;">Particulars of Information (Related to):</td>
            <td width="70%" colspan="3" style="border: 1px solid #dddddd;">
                <?php echo $list_list[0]['related_to']; ?>
            </td>
        </tr>
        <tr>
            <td width="30%" style="border: 1px solid #dddddd;">Location, Drawing Ref No:</td>
            <td width="70%" colspan="3" style="border: 1px solid #dddddd;">
                <?php echo $list_list[0]['location_ref']; ?>
            </td>
        </tr>
        <?php if($list_list[0]['type'] == '2'){ ?>
            <tr>
                <td width="30%" style="border: 1px solid #dddddd;">Specification Ref (Clause):</td>
                <td width="70%" colspan="3" style="border: 1px solid #dddddd;">
                    <?php if($list_list[0]['spec_ref'] == 'Complied'){ ?>
                        <input type="radio" id="complied" name="rf[spec_ref]"  value="Complied" checked  disabled /> Complied
                        <input type="radio" id="partially_complied" name="rf[spec_ref]"  value="Partially Complied" style="padding-left: 4px;" disabled /> Partially Complied
                        <input type="radio" id="not_complied" name="rf[spec_ref]"  value="Not Complied" style="padding-left: 4px;" disabled /> Not Complied
                    <?php }else if($list_list[0]['spec_ref'] == 'Partially Complied'){ ?>
                        <input type="radio" id="complied" name="rf[spec_ref]"  value="Complied" disabled  /> Complied
                        <input type="radio" id="partially_complied" name="rf[spec_ref]"  value="Partially Complied" style="padding-left: 4px;" checked disabled /> Partially Complied
                        <input type="radio" id="not_complied" name="rf[spec_ref]"  value="Not Complied" style="padding-left: 4px;" disabled /> Not Complied
                    <?php }else{ ?>
                        <input type="radio" id="complied" name="rf[spec_ref]"  value="Complied" disabled  /> Complied
                        <input type="radio" id="partially_complied" name="rf[spec_ref]"  value="Partially Complied" style="padding-left: 4px;" checked disabled /> Partially Complied
                        <input type="radio" id="not_complied" name="rf[spec_ref]"  value="Not Complied" style="padding-left: 4px;" checked disabled /> Not Complied
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td width="30%" style="border: 1px solid #dddddd;">Others (Email):</td>
            <td width="70%" colspan="3" style="border: 1px solid #dddddd;">
                <?php echo $list_list[0]['others']; ?>
            </td>
        </tr>
        <?php
            $step = RfDetail::stepByType($list_list[0]['check_id'],'1');
            $attachment = RfAttachment::dealListBystep($list_list[0]['check_id'],$step);
            if(!empty($attachment)) {
        ?>
                <tr><td colspan="4">Attachment</td></tr>
                <?php foreach ($attachment as $i => $j) {
                    ?>
                    <tr>
                        <td colspan="2"><img src='img/pdf_min.png'><?php echo $j['doc_name'] ?></td>
                        <td colspan="2">
                            <button type='button' onclick='preview("<?php echo $j['doc_path']; ?>")'>
                                Preview
                            </button>
                        </td>
                    </tr>
                <?php }
            }else{
        ?>
                <tr><td >Attachment</td><td colspan="3"></td></tr>
        <?php } ?>
        <tr>
            <?php
                $step = RfDetail::stepByType($list_list[0]['check_id'],'1');
                $view_list = RfModelView::dealList($list_list[0]['check_id'],$step);
                $model_view_id = $view_list[0]['model_id'];
                $model_view = $view_list[0]['view'];
                $component_list = RfModelComponent::dealList($list_list[0]['check_id'],$step);
                $model_component_id = $component_list[0]['model_id'];
                $model_uuid = $component_list[0]['uuid'];
            ?>
            <td style="border: 1px solid #dddddd;">
                <img  src="img/pic_min.png" > Viewpoint</td>
            <td  style="border: 1px solid #dddddd;">
                <?php if(!empty($view_list)){ ?>
                    <button type="button"  onclick="show_view('<?php  echo $list_list[0]['check_id']; ?>','<?php  echo $step; ?>')">Preview</button>
                <?php } ?>
            </td>
            <td style="border: 1px solid #dddddd;">
                <img  src="img/component_min.png" >  Component name
            </td>
            <td style="border: 1px solid #dddddd;">
                <?php if(!empty($component_list)){ ?>
                    <button type="button" onclick="show_component('<?php  echo $list_list[0]['check_id']; ?>','<?php  echo $step; ?>')">Preview</button>
                <?php } ?>
            </td>
        </tr>
        <tr style="height: 100px;">
            <?php if($list_list[0]['type'] == '2'){ ?>
                <td width="30%" style="border: 1px solid #dddddd;">Reason(s) for RFA:</td>
            <?php }else{ ?>
                <td width="30%" style="border: 1px solid #dddddd;">Reason(s) for RFI:</td>
            <?php } ?>
            <td width="100%" colspan="3" style="border: 1px solid #dddddd;">
                <?php
                    $step = RfDetail::stepByType($list_list[0]['check_id'],'1');
                    $detail_list = RfDetail::dealListByStep($list_list[0]['check_id'],$step);
                    echo $detail_list[0]['remark'];
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border: 1px solid #dddddd;border-right: white;">
                Coordinator Name/ PM Name <br> Name & Signature of Contractor’s Representative
            </td>
            <td  align="left" colspan="2" style="border: 1px solid #dddddd;border-left: white;">
                <?php
                $step = RfDetail::stepByType($list_list[0]['check_id'],'1');
                $detail_list = RfDetail::dealListByStep($list_list[0]['check_id'],$step);
                $deal_user = Staff::model()->findByPk($detail_list[0]['user_id']);
                $signature_path = $deal_user->signature_path;
                ?>
                <img src="<?php echo $signature_path; ?>" height="50" width="120"  />
            </td>
        </tr>
        <?php if($list_list[0]['status'] == '1' || $list_list[0]['status'] == '2' ||$list_list[0]['status'] == '4' ||  $list_list[0]['status'] == '6'){ ?>
        <?php if($list_list[0]['status'] == '2' ||$list_list[0]['status'] == '4' ||  $list_list[0]['status'] == '6'){ ?>
            <tr>
                <td colspan="4" style="border: 1px solid #dddddd;">
                    Consultant’s Reply : (Enclosure Y / N)
                </td>
            </tr>
            <tr>
                <td  colspan="4" align="center" style="border: 1px solid #dddddd;">
                    <?php if($list_list[0]['status'] == '2'){ ?>
                        <input type="checkbox"  name="rf[status]"  value="2" checked  disabled /> Approved
                        <input type="checkbox"  name="rf[status]"  value="3" style="padding-left: 4px;" disabled /> Approved with Comments
                        <input type="checkbox"  name="rf[status]"  value="4" style="padding-left: 4px;" disabled /> Amend / Reject & Resubmit
                    <?php }else if($list_list[0]['status'] == '6'){ ?>
                        <input type="checkbox"  name="rf[status]"  value="2" disabled  /> Approved
                        <input type="checkbox"  name="rf[status]"  value="3" style="padding-left: 4px;" checked disabled /> Approved with Comments
                        <input type="checkbox"  name="rf[status]"  value="4" style="padding-left: 4px;" disabled /> Amend / Reject & Resubmit
                    <?php }else if($list_list[0]['status'] == '4'){ ?>
                        <input type="checkbox"  name="rf[status]"  value="2" disabled  /> Approved
                        <input type="checkbox"  name="rf[status]"  value="3" style="padding-left: 4px;"  disabled /> Approved with Comments
                        <input type="checkbox"  name="rf[status]"  value="4" style="padding-left: 4px;" checked disabled /> Amend / Reject & Resubmit
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
            <?php
                $rf_model = RfList::model()->findByPk($check_id);
                $step = $rf_model->step;
                $attachment = RfAttachment::dealListBystep($list_list[0]['check_id'],$step);
                if(!empty($attachment)) {
            ?>
                    <tr><td colspan="4">Attachment</td></tr>
                    <?php foreach ($attachment as $i => $j) {
                        ?>
                        <tr>
                            <td colspan="2"><img src='img/pdf_min.png'><?php echo $j['doc_name'] ?></td>
                            <td colspan="2">
                                <button type='button' onclick='preview("<?php echo $j['doc_path']; ?>")'>
                                    Preview
                                </button>
                                <input type='hidden' name='rf[attachment][]' value='<?php echo $j['doc_path']; ?>'></td>
                        </tr>
                    <?php }
                }else{
            ?>
                    <tr><td >Attachment</td><td colspan="3"></td></tr>
                    <?php } ?>
            <tr>
                <?php
                    $rf_model = RfList::model()->findByPk($check_id);
                    $step = $rf_model->step;
                    $view_list = RfModelView::dealList($check_id,$step);
                    $component_list = RfModelComponent::dealList($check_id,$step);
                ?>
                <td  style="border: 1px solid #dddddd;">
                    <img  src="img/pic_min.png" > Viewpoint
                </td>
                <td  style="border: 1px solid #dddddd;">
                    <?php if(!empty($view_list)){ ?>
                        <button type="button"  onclick="show_view('<?php  echo $list_list[0]['check_id']; ?>','<?php  echo $step; ?>')">Preview</button>
                    <?php } ?>
                </td>
                <td style="border: 1px solid #dddddd;">
                    <img  src="img/component_min.png" >  Component name</td>
                <td style="border: 1px solid #dddddd;">
                    <?php if(!empty($component_list)){ ?>
                        <button type="button" onclick="show_component('<?php  echo $list_list[0]['check_id']; ?>','<?php  echo $step; ?>')">Preview</button>
                    <?php } ?>
                </td>
            </tr>
        <tr>
            <td width="30%" style="border: 1px solid #dddddd;height: 100px;" >Message:</td>
            <td width="70%" colspan="3" style="border: 1px solid #dddddd;">
                <?php
                    $rf_model = RfList::model()->findByPk($check_id);
                    $step = $rf_model->step;
                    $detail_list = RfDetail::dealListByStep($list_list[0]['check_id'],$step);
                    echo $detail_list[0]['remark'];
                ?>
            </td>
        </tr>
        <tr>
            <td width="20%" style="border: 1px solid #dddddd;">Consultant Rep’s Signature & Date Received </td>
            <td width="30%" style="border: 1px solid #dddddd;">
                <?php
                    $rf_model = RfList::model()->findByPk($check_id);
                    $step = $rf_model->step -1;
                    $detail_list = RfDetail::dealListByStep($list_list[0]['check_id'],$step);
                    echo Utils::DateToEn(substr($detail_list[0]['record_time'],0,10));
                ?>
            </td>
            <td width="20%" style="border: 1px solid #dddddd;">Consultant Rep’s Signature & Date Replied </td>
            <td width="30%" style="border: 1px solid #dddddd;">
                <?php
                    $rf_model = RfList::model()->findByPk($check_id);
                    $step = $rf_model->step;
                    $detail_list = RfDetail::dealListByStep($list_list[0]['check_id'],$step);
                    echo Utils::DateToEn(substr($detail_list[0]['record_time'],0,10));
                ?>
            </td>
        </tr>
        <tr>
            <td width="10%" colspan="4" style="border: 1px solid #dddddd;">Copy to: </td>
        </tr>
        <?php
            $rf_model = RfList::model()->findByPk($check_id);
            $step = $rf_model->step;
            $cc_user = RfUser::userList($list_list[0]['check_id'],$step,'2');
            $cc_usr_str = '';
            $cc_tag['3'] = 'YES';
            $cc_tag['4'] = 'NO';
            $cc_tag['0'] = 'Y/N';
            foreach($cc_user as $i => $j){
                ?>
                <tr><td width='30%'><input type='hidden' name='rf[cc][]' id='cc' value='<?php echo $j['user_id'] ?>' disabled><?php echo $j['user_name'] ?></td> <td width='70%' colspan='3' align='right'>- enclosure <?php echo $cc_tag[$j['tag']]; ?></td></tr>
            <?php } ?>
        <?php }else {
            if ($list_list[0]['type'] == '2') { ?>
                <tr>
                    <td width="30%">Consultant’s Reply : (Enclosure Y / N)</td>
                    <td width="70%" colspan="3">
                        <input type="checkbox" name="rf[status]" value="2" disabled/> Approved
                        <input type="checkbox" name="rf[status]" value="3" style="padding-left: 4px;" disabled/>
                        Approved with Comments
                        <input type="checkbox" name="rf[status]" value="4" style="padding-left: 4px;" disabled/> Amend /
                        Reject & Resubmit
                    </td>
                </tr>
                <tr>
                    <td width="30%" style="border: 1px solid #dddddd;">Message:</td>
                    <td width="70%" colspan="3" style="border: 1px solid #dddddd;"></td>
                </tr>
                <tr>
                    <td width="20%" style="border: 1px solid #dddddd;">Consultant Rep’s Signature & Date Received</td>
                    <td width="30%" style="border: 1px solid #dddddd;"></td>
                    <td width="20%" style="border: 1px solid #dddddd;">Consultant Rep’s Signature & Date Replied</td>
                    <td width="30%" style="border: 1px solid #dddddd;"></td>
                </tr>
                <tr>
                    <td width="10%" colspan="4">Copy to:</td>
                </tr>
            <?php }
        } ?>
    </table>

    <div class="row">
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="button" id="sbtn" class="btn btn-primary"
                        style="margin-left: 5px; background: #44ACB7" onclick="cancel('<?php echo $list_list[0]['program_id']; ?>');">Cancel</button>
                <?php if($list_list[0]['status'] == '0' ){ ?>
                    <?php
                    $operator_id = Yii::app()->user->id;
                    $user = Staff::userByPhone($operator_id);
                    $user_id = $user[0]['user_id'];
                    $user_model = Staff::model()->findByPk($user_id);
                    $user_id = $user_model->user_id;
                    if($user_id == $list_list[0]['add_user'] && $list_list[0]['type'] == '2'){
                        ?>
                        <button type="button" id="sbtn" class="btn btn-primary"
                                style="margin-left: 25px; background: #44ACB7" onclick="withdraw('<?php echo $list_list[0]['check_id']; ?>');">Whithdraw</button>
                    <?php } ?>
                    <?php
                    if($user_id == $list_list[0]['add_user'] && $list_list[0]['type'] == '1'){
                        ?>
                        <button type="button" id="sbtn" class="btn btn-primary"
                                style="margin-left: 45px; background: #44ACB7" onclick="closelist('<?php echo $list_list[0]['check_id']; ?>');">Close</button>

                        <button type="button" id="sbtn" class="btn btn-primary"
                                style="margin-left: 65px; background: #44ACB7" onclick="withdraw('<?php echo $list_list[0]['check_id']; ?>');">Whithdraw</button>
                    <?php } ?>
                    <?php
                    $operator_id = Yii::app()->user->id;
                    $authority = RfList::permissionsInfo($list_list[0]['check_id'],$operator_id);
                    if($authority['tag'] == '1' || $authority['tag'] == '2'){
                        ?>
                        <button type="button" class="btn btn-primary"
                                style="margin-left: 45px; background: #44ACB7" onclick="confirm_1('<?php echo $list_list[0]['check_id']; ?>');">Confirm</button>
                    <?php } ?>
                <?php } ?>
                <?php if($list_list[0]['status'] == '5' ){ ?>
                    <?php
                    $operator_id = Yii::app()->user->id;
                    $authority = RfList::permissionsInfo($list_list[0]['check_id'],$operator_id);
                    if($authority['tag'] == '2'){
                        ?>
                        <button type="button" class="btn btn-primary"
                                style="margin-left: 45px; background: #44ACB7" onclick="reject('<?php echo $list_list[0]['check_id']; ?>');">Reject</button>
                        <button type="button" class="btn btn-primary"
                                style="margin-left: 65px; background: #44ACB7" onclick="approve('<?php echo $list_list[0]['check_id']; ?>');">Approve</button>
                        <button type="button" class="btn btn-primary"
                                style="margin-left: 85px; background: #44ACB7" onclick="approve_comment('<?php echo $list_list[0]['check_id']; ?>');">Approve with Comment</button>
                    <?php }else if($authority['tag'] == '1'){ ?>
                        <button type="button" class="btn btn-primary"
                                style="margin-left: 45px; background: #44ACB7" onclick="reply('<?php echo $list_list[0]['check_id']; ?>');">Reply</button>
                    <?php }  ?>
                <?php } ?>
            </div>
            </div>
        </div>
    </div>
</div>
<div class="col-xs-1">
</div>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript" src="js/zDrag.js"></script>
<script type="text/javascript" src="js/zDialog.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {
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
    });
    function show_sub(v){
        var cc_cnt = $('#cc_cnt').val();
        var cc_str = $('#cc_str').val();
        var table = document.getElementById("info");//获取对应
        if(cc_str.indexOf(v.value) == -1){
            cc_cnt++;
            cc_str = cc_str + v.value + ',';
//            cc_str = cc_str.substr(0, cc_str.length - 1);//去掉末尾的逗号
            $('#cc_cnt').val(cc_cnt);
            $('#cc_str').val(cc_str);
            if(cc_cnt % 2 == 0){
                $(table).append("<tr><td width='30%'><input type='checkbox' name='rf[cc][]' id='cc' value='"+v.value+"' disabled>"+v.text+"</td> <td width='70%' colspan='3' align='right'>- enclosure Y / N</td></tr>");
            }else{
                $(table).append("<tr><td width='30%'><input type='checkbox' name='rf[cc][]' id='cc' value='"+v.value+"' disabled>"+v.text+"</td> <td width='70%'  colspan='3' align='right'>- enclosure Y / N</td></tr>");
            }
        }
    }

    function show_component(check_id,step) {
        var diag = new Dialog();
        diag.Width = 800;
        diag.Height = 770;
        diag.Title = "Model Component";
        diag.URL = "showcomponent&step="+step+"&check_id="+check_id;
        diag.show();
    }

    function show_view(check_id,step) {
        var diag = new Dialog();
        diag.Width = 800;
        diag.Height = 770;
        diag.Title = "Model View";
        diag.URL = "showview&step="+step+"&check_id="+check_id;
        diag.show();
    }

    //预览
    function preview (path) {
        var tag = path.slice(-3);
        if(tag == 'pdf'){
            window.open("index.php?r=rf/rf/preview&doc_path="+path,"_blank");
        }else{
            window.open('https://shell.cmstech.sg'+path,"_blank");
        }
    }

    //取消
    function cancel (program_id) {
        window.location = "index.php?r=rf/rf/list&program_id="+program_id;
    }
    //确认
    function confirm_1 (check_id) {
        $.ajax({
            data:{check_id:check_id},
            url: "index.php?r=rf/rf/confirm",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                $('#msgbox').addClass('alert-success fa-ban');
                $('#msginfo').html(data.msg);
                $('#msgbox').show();
                window.location = "index.php?r=rf/rf/info&check_id="+check_id;
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
    //回复
    function reply (check_id) {
        window.location = "index.php?r=rf/rf/reply&check_id="+check_id;
    }
    //拒绝
    function reject (check_id) {
        window.location = "index.php?r=rf/rf/reject&check_id="+check_id;
    }
    //批准
    function approve (check_id) {
        if (!confirm('Proceed to approve?')) {
            return;
        }
        window.location = "index.php?r=rf/rf/approve&check_id="+check_id;
    }
    //批准(带评论)
    function approve_comment (check_id) {
        window.location = "index.php?r=rf/rf/approvecomment&check_id="+check_id;
    }

    //撤销
    function withdraw(check_id) {
        $.ajax({
            data:{check_id:check_id},
            url: "index.php?r=rf/rf/withdraw",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                $('#msgbox').addClass('alert-success fa-ban');
                $('#msginfo').html(data.msg);
                $('#msgbox').show();
                window.location = "index.php?r=rf/rf/info&check_id="+check_id;
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
    //关闭
    function closelist(check_id) {
        $.ajax({
            data:{check_id:check_id},
            url: "index.php?r=rf/rf/close",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                $('#msgbox').addClass('alert-success fa-ban');
                $('#msginfo').html(data.msg);
                $('#msgbox').show();
                window.location = "index.php?r=rf/rf/info&check_id="+check_id;
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
</script>
