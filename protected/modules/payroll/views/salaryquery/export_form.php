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
<!--    <div >
        <input type="hidden" id="allowance_id" name="PayrollAllowance[allowance_id]" value="<?php echo "$allowance_id"; ?>"/>
        </div>-->
    <div class="row">
        <div class="form-group">
                <label for="start_date" class="col-sm-2 control-label padding-lr5"><?php echo Yii::t('pay_payroll','start_date'); ?></label>
                <div class="input-group col-sm-6">
                    <div class="input-group col-sm-6">
                <div class="input-group-addon"><i class="fa fa-calendar"></i></div> 
                    <input id="start_date" class="form-control b_date_allowance" type="text" name="Export[start_date]" check-type="" onclick="WdatePicker({lang:'en',dateFmt:'01 MMM yyyy'})">
                    </div>
                    <div style="margin-top: -35px;margin-left: 240px">
                    <span id="msg_start_date" class="help-block" style="display:none"></span>
                    </div>
                </div>
                
        </div>
    </div>
    <div class="row">
        <div class="form-group">
                <label for="end_date" class="col-sm-2 control-label padding-lr5"><?php echo Yii::t('pay_payroll','end_date'); ?></label>
                <div class="input-group col-sm-6">
                    <div class="input-group col-sm-6">
                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    
                    <input id="end_date" class="form-control b_date_allowance" type="text" name="Export[end_date]" check-type="" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})">
                    </div>
                </div>
                <div style="margin-top: -35px;margin-left: 395px">
                    <span id="msg_end_date" class="help-block" style="display:none"></span>
                    </div>
        </div>
    </div>
    
    <div class="row">
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-12">
                    <button type="button" onclick="formSubmit()"  id="sbtn" class="btn btn-primary btn-lg"><?php echo Yii::t('common', 'button_export'); ?></button>
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
            a2 = datetocn(a1);
            if(a2!=' undefined'){
                $(this).val(a2);
            }
        });
    });
    
    
    //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['salaryquery/list']; ?>";
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
 //导出日报表
    var formSubmit = function () {
        var start_date = document.getElementById("start_date").value;
        var end_date = document.getElementById("end_date").value;
        
        if(start_date =='' || end_date==''){
            alert('<?php echo Yii::t('pay_payroll','date_null');?>');
            return false;
        }
        var new_start_date = start_date.substr(3, 3);
        var new_end_date = end_date.substr(3, 3);
//        alert(new_start_date);
//        alert(new_end_date);
        if (new_start_date != new_end_date) {             
            alert('<?php echo Yii::t('sys_optlog','tip_month'); ?>');
            return false;
        }
        var params = $('#form1').serialize();
        window.location = "index.php?r=payroll/salaryquery/export&"+params;
    }
    
//    document.getElementById("plan_work_hour").onkeyup = function() {
//        var str=(this.value).replace(/[^\d]/g, "");
//        this.value=str;
//    }
 
    jQuery(document).ready(function () {
        
        $('#start_date').focus(function(){
            $('#sbtn').attr('disabled',true); 
        });
        $('#start_date').blur(function(){
            $('#sbtn').attr('disabled',false); 
            document.getElementById('msg_start_date').innerText="开始日期默认为每个月的1号";
            $('#msg_start_date').show();
        });
        
        $('#end_date').focus(function(){
            $('#sbtn').attr('disabled',true); 
        });
        $('#end_date').blur(function(){
            $('#sbtn').attr('disabled',false); 
            document.getElementById('msg_end_date').innerText="结束日期在当月只能选择当日之前的一天";
            $('#msg_end_date').show();
        });

    });
</script>
