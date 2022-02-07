<?php

$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'focus' => array($rs, 'old_pwd'),
    "action" => "javascript:formSubmit()",
//    'enableAjaxSubmit' => false,
    'ajaxUpdateId' => 'content-body',
    'role' => 'form', //可省略
    'formClass' => 'form-horizontal', //可省略 表单对齐样式
    'autoValidation' => true,
        ));
?>

<div class="box-body">
    <div id='msgbox' class='alert alert-dismissable ' style="display:none;">
    <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
    <strong id='msginfo'></strong><span id='divMain'></span>
</div>
    <div >
        <input type="hidden" id="allowance_id" name="PayrollAllowance[allowance_id]" value="<?php echo "$allowance_id"; ?>"/>
        </div>
    <?php if($_mode_ == 'insert'){ ?>
    <div class="row">
        <div class="col-md-12">
            <h3 class="form-header text-blue"><?php echo Yii::t('pay_payroll', 'allowance_prompt'); ?></h3>
        </div>
    </div>
    <?php } ?>
    <div class="row">
        <div class="form-group">
                <label for="allowance_date" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('allowance_date'); ?></label>
                <div class="input-group col-sm-3">
                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <?php echo $form->activeTextField($model, 'allowance_date', array('id' => 'allowance_date', 'class' =>'form-control b_date_allowance', 'onclick' => "WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})", 'check-type' => '')); ?>
                </div>
        </div>
    </div>
    <?php if($_mode_ == 'edit'){ ?>
    <div class="row">
        <div class="form-group">
            <label for="user_name" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('user_name'); ?></label>
            <div class="col-sm-3 padding-lr5">
                <?php
                    echo $form->activeTextField($model, 'user_name', array('id' => 'user_name', 'class' => 'form-control', 'check-type' => '', 'required-message' => ''));
                ?>
            </div>
        </div>
    </div>
    <?php } ?>
    
    <?php if($_mode_ == 'edit'){ ?>
    <div class="row">
        <div class="form-group">
            <label for="user_phone" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('user_phone'); ?></label>
            <div class="col-sm-3 padding-lr5">
                <?php
                    echo $form->activeTextField($model, 'user_phone', array('id' => 'user_phone', 'class' => 'form-control', 'check-type' => '','required-message' => ''));
                ?>
            </div>
        </div>
    </div>
    <?php }else{ ?>
    <div class="row">
        <div class="form-group">
            <label for="user_phone" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('user_phone'); ?></label>
            <div class="input-group col-sm-6" >    
                <div class="col-sm-6" style="padding-left: 5px">
                    <?php
                        echo $form->activeTextField($model, 'user_phone', array('id' => 'user_phone', 'class' => 'form-control', 'check-type' => '','required-message' => ''));
                    ?>
                </div>
                <div class="col-sm-3 padding-lr5">
                    <span id="msg_user" class="help-block" style="display:none"></span>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    
    <div class="col-md-6"><!--  补贴类型  -->
            <div class="form-group">
                <label for="allowance_type"
                       class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('allownace_type');?></label>
                <div class="input-group col-sm-7 padding-lr5">
                    <div class="col-sm-5 padding-lr5">
                    <?php
                        $teamlist = Role::roleTeamList();
                        array_unshift($teamlist, '-'.Yii::t('sys_role', 'team_name').'-');
                        echo $form->activeDropDownList($model, 'team_id',$teamlist ,array('id' => 'team_id', 'class' => 'form-control'));
                    ?>
                    </div>
                    <div class="col-sm-6 padding-lr5">
                    <?php
                        $roleList = array();
                        if($model->team_id)
                            $roleList = Role::roleListByTeam($model->team_id);

                        echo $form->activeDropDownList($model, 'role_id', $roleList ,array('id' => 'role_id', 'class' => 'form-control', 'check-type' => 'required','required-message'=>Yii::t('comp_staff', 'Error role_id is null')));
                    ?>
                   
                   </div>
                </div>
            </div>
        </div> 
    
    
    <div class="row">           
         <div class="form-group">
            <label for="allowance" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('allowance'); ?></label>
            <div class="col-sm-3 padding-lr5">
                <?php
                    echo $form->activeTextField($model, 'allowance', array('id' => 'allowance', 'class' =>'form-control ','check-type' => 'required','required-message' => ''));
                ?>
            </div>
        </div>
    </div>
    <div class="row">

        <div class="form-group">
            <label for="allowance_content" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('allowance_content'); ?></label>
            <div class="col-sm-3 padding-lr5">
                <?php
                    echo $form->activeTextArea($model, 'allowance_content', array('id' => 'allowance_content', 'class' =>'form-control ','check-type' => ''));
                ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-12">
                <?php if($_mode_ == 'insert'){    ?>
                    <button type="button" onclick="formSubmit()"  id="sbtn" class="btn btn-primary btn-lg"><?php echo Yii::t('common', 'button_save'); ?></button>
                <?php }else{ ?>
                    <button type="button" onclick="formSubmit1()" id="sbtn1" class="btn btn-primary btn-lg"><?php echo Yii::t('common', 'button_save'); ?></button>
                <?php }?>
                <button type="button" class="btn btn-default btn-lg" style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>

<script type="text/javascript">
    jQuery(document).ready(function () {
        $('.b_date_allowance').each(function(){
            a1 = $(this).val();
//            a2 = datetocn(a1);
            var w = new Array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
            var year = a1.substring(0, 4);
            var month = a1.substring(4 , 6);
            var day = a1.substring(6, 8);
            if(month==0){
                var strmonth = a1.substring(5,6);
            }else{
                var strmonth = a1.substring(4,6);
            }
            for(var i=1;i<=w.length;i++){
                if(i==strmonth){
                    var smonth = w[i-1];
                }
            }
            if(day == ''){
                a2 = " "+year+smonth;
            }else{
                a2 = day+" "+smonth+" "+year;
            }
            if(a2!=' undefined'){
                $(this).val(a2);
            }
        });
//        $('#user_phone').focus(function(){
//            $('#sbtn').attr('disabled',false); 
//        });
        $('#user_phone').blur(function(){
            //alert($(this).val());
            $.ajax({
                type: "POST",
                url: "index.php?r=payroll/salary/queryuser",
                data: {user_phone:$("#user_phone").val()},
                dataType: "json",
                success: function(data){
                    if(data.status==0) {//alert(data.id);
                        $('#msg_user').html(data.name).show();
//                        $('#sbtn').attr('disabled',false); 
                    }else{
                        $('#msg_user').html(data.msg).show();
//                        $('#sbtn').attr('disabled',true);
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    //alert(XMLHttpRequest.status);
                    //alert(XMLHttpRequest.readyState);
                    //alert(textStatus);
                },
            });
        });
    });
    
    
    //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['allowance/list']; ?>";
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
 //提交表单（添加）
    var formSubmit = function () {
                  
        var params = $('#form1').serialize();
        $.ajax({
            //data: {program_id: program_id,task_name: task_name,plan_start_time:plan_start_time,plan_end_time:plan_end_time,plan_work_hour:plan_work_hour,plan_amount:plan_amount,amount_unit:amount_unit,task_content:task_content},
            //url: "index.php?r=proj/task/tnew&"+params ,
            data:$('#form1').serialize(),
            url: "index.php?r=payroll/allowance/anew",
            type: "POST",
            dataType: "json",
            beforeSend: function () {
            
            },
            success: function (data) {
                //alert(data);
                $('#msgbox').addClass('alert-success');
                $('#msginfo').html(data.msg);
                $('#msgbox').show();
                showTime(data.refresh);
                itemQuery();
            },
            error: function () {
                //alert('error');
                //alert(data.msg);
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
    //提交表单（修改）
    var formSubmit1 = function () {
        var allowance_id = document.getElementById('allowance_id').value;
        $.ajax({
            data:$('#form1').serialize(),
            url: "index.php?r=payroll/allowance/aedit&allowance_id="+allowance_id,
            type: "POST",
            dataType: "json",
            beforeSend: function () {
      
            },
            success: function (data) {
                //alert(data);
                $('#msgbox').addClass('alert-success');
                $('#msginfo').html(data.msg);
                $('#msgbox').show();
                showTime(data.refresh);
                itemQuery();
            },
            error: function () {
                //alert('error');
//                alert(data.msg);
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
    
//    document.getElementById("plan_work_hour").onkeyup = function() {
//        var str=(this.value).replace(/[^\d]/g, "");
//        this.value=str;
//    }
 
    jQuery(document).ready(function () {
        
        $('.b_date').each(function(){
            a1 = $(this).val();
            a2 = datetocn(a1);
            if(a2!=' undefined'){
                $(this).val(a2);
            }
        });

    });
</script>
