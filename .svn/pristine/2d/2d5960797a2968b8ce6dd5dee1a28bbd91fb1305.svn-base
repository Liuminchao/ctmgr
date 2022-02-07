<?php
if ($msg) {
    $class = Utils::getMessageType($msg ['status']);
    echo "<div class='alert {$class[0]} alert-dismissable'>
              <i class='fa {$class[1]}'></i>
              <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
              <b>" . Yii::t('common', 'tip') . "：</b>{$msg['msg']}
          </div>
          <script type='text/javascript'>
          /*{$this->gridId}.refresh();*/
          </script>
          ";
}
$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'enableAjaxSubmit' => false,
    'ajaxUpdateId' => 'content-body',
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
    'focus' => array(
        $model,
        'name'
    ),
    'role' => 'form', // 可省略
    'formClass' => 'form-horizontal', // 可省略 表单对齐样式
    'autoValidation' => false
  ));
?>
<div class="box-body">
    <!--<div class="row">
        <div class="col-md-12">
            <h3 class="form-header text-blue"><?php echo Yii::t('comp_staff', 'Base Info'); ?></h3>
        </div>
    </div>-->
    <div >
        <input type="hidden" id="summary_id" name="PayrollSalary[summary_id]" value="<?php echo "$summary_id"; ?>"/>
        <input type="hidden" id="wage_date" name="PayrollSalary[wage_date]" value="<?php echo "$wage_date"; ?>"/>
    </div>
    <div class="row">
        <div class="col-md-6"><!--  姓名  -->
            <div class="form-group">
                <label for="user_name" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('user_name'); ?></label>
                <div class="col-sm-6 padding-lr5" style="padding-top: 6px">
                    <?php 
                    echo $user_name;
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-6"><!--  工资日期  -->
            <div class="form-group">
                <label for="wage_date" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('wage_date'); ?></label>
                <div class="col-sm-6 padding-lr5" style="padding-top: 6px">
                    <?php 
                    echo Utils::WorkDateToEn($wage_date);
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6"><!--  时薪  -->
            <div class="form-group">
                <label for="wage" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('wage'); ?></label>
                <div class="col-sm-6 padding-lr5" style="padding-top: 6px">
                    <?php 
                    echo $rgs['wage'];
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-6"><!--  基本工时  -->
            <div class="form-group">
                <label for="work_hours" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('work_hours'); ?></label>
                <div class="col-sm-6 padding-lr5" style="padding-top: 6px">
                    <?php 
                    echo $rgs['work_hours'];
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6"><!--  加班时薪  -->
            <div class="form-group">
                <label for="wage_overtime" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('wage_overtime'); ?></label>
                <div class="col-sm-6 padding-lr5" style="padding-top: 6px">
                    <?php 
                    echo $rgs['wage_overtime'];
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-6"><!--  加班工时  -->
            <div class="form-group">
                <label for="overtime_hours" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('overtime_hours'); ?></label>
                <div class="col-sm-6 padding-lr5" style="padding-top: 6px">
                    <?php 
                    echo $rgs['overtime_hours'];
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div  class="row">
        <div class="col-md-6"><!--  补贴金额  -->
            <div class="form-group">
                <label for="allowance"
                       class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('allowance');?></label>
                <div class="col-md-6 padding-lr5" style="padding-top: 6px">
                    <?php 
                    echo $rgs['allowance'];
                    ?>
                 </div>
            </div>
         </div>
        <div class="col-md-6"><!--  补贴明细  -->
            <div class="form-group">
                <label for="allowance_content"
                       class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('allowance_content');?></label>
                <div class="col-md-6 padding-lr5" style="padding-top: 6px">
                    <?php 
                    echo $rgs['allowance_content'];
                    ?>
                 </div>
            </div>
         </div>
    </div>
    <div class="row">
        <div class="col-md-6"><!--  基本工资  -->
            <div class="form-group">
                <label for="basic_wage" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('basic_wage'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($model, 'basic_wage', array('id' => 'basic_wage', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('comp_staff','Error User_phone is null'))); ?>
                </div>
            </div>
        </div>
        <div class="col-md-6"><!--  加班工资  -->
            <div class="form-group">
                <label for="overtime_wage"
                       class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('overtime_wage');?></label>
                    <div class="col-sm-6 padding-lr5">
                        <?php echo $form->activeTextField($model, 'overtime_wage', array('id' => 'overtime_wage', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('comp_staff','Error User_phone is null'))); ?>
                    </div>
            </div>
        </div>
        
     </div>
    
    <div class="row">
        <div class="col-md-6"><!--  邮箱  -->
            <div class="form-group">
                <label for="total_wage"
                       class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('total_wage'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($model, 'total_wage', array('id' => 'total_wage', 'class' => 'form-control', 'check-type' => '', 'required-message' => '')); ?>
                </div>
            </div>
        </div>

    </div>

</div>    
     
<!--<?php //$this->renderpartial('_infoform', array( 'infomodel' => $infomodel, '_mode_' => 'insert')); ?>--> 
    <div class="row">
        <div class="form-group">
        <div class="col-sm-offset-4 col-sm-10">
            <button type="submit" id="sbtn" class="btn btn-primary btn-lg"><?php echo Yii::t('common', 'button_save'); ?></button>
            <button type="button" class="btn btn-default btn-lg"
                    style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>
        </div>
        </div>
    </div>

 
<?php $this->endWidget(); ?>  
<script type="text/javascript">
    jQuery(document).ready(function () {
      
    });
    //返回
    var back = function () {
        var summary_id = document.getElementById("summary_id").value;
        var wage_date = document.getElementById("wage_date").value;
        window.location =  "index.php?r=payroll/salary/detaillist&summary_id="+summary_id+'&start_date='+wage_date;
    }
</script>


    




