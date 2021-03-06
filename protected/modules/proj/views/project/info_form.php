<?php
/* @var $this ProgramController */
/* @var $model Program */
/* @var $form CActiveForm */
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
    'id' => 'info_form',
    'enableAjaxSubmit' => false,
    'ajaxUpdateId' => 'content-body',
    'focus' => array($model, 'program_name'),
    'role' => 'form', //可省略
    'formClass' => 'form-horizontal', //可省略 表单对齐样式
));
echo $form->activeHiddenField($model, 'program_id', array());
?>
<div class="box-body">
    <div>
        <!--        <input type="hidden" id="start_sign" name="Program[start_sign]" />-->
        <input type="hidden" id="program_id" name="ProgramInfo[program_id]" value="<?php echo "$program_id"; ?>"/>
        <!--        <input type="hidden" id="ptw_mode" name="Program[ptw_mode]">-->
        <!--        <input type="hidden" id="location_require" name="Program[location_require]">-->
        <input type="hidden" id="_mode_"  value="<?php echo "$_mode_"; ?>"/>
        <input type="hidden" id="type" name="Program[TYPE]" value="<?php echo "$ptype"; ?>"/>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="program_construction_no" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('program_construction_no'); ?></label>
            <div class="col-sm-5 padding-lr5">
                <?php
                echo $form->activeTextField($infomodel, 'program_construction_no', array('id' => 'program_construction_no', 'class' => 'form-control'));
                ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="program_construction_no" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('program_bp_no'); ?></label>
            <div class="col-sm-5 padding-lr5">
                <?php
                echo $form->activeTextField($infomodel, 'program_bp_no', array('id' => 'program_bp_no', 'class' => 'form-control'));
                ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="construction_end" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('struct_progress'); ?></label>
            <div class="input-group col-sm-5 ">
                <?php echo $form->activeTextField($infomodel, 'struct_progress', array('id' => 'struct_progress', 'class' =>'form-control')); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="construction_end" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('arch_progress'); ?></label>
            <div class="input-group col-sm-5 ">
                <?php echo $form->activeTextField($infomodel, 'arch_progress', array('id' => 'arch_progress', 'class' =>'form-control')); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="construction_end" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('me_progress'); ?></label>
            <div class="input-group col-sm-5 ">
                <?php echo $form->activeTextField($infomodel, 'me_progress', array('id' => 'me_progress', 'class' =>'form-control')); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="construction_end" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('program_gfa'); ?></label>
            <div class="input-group col-sm-5 ">
                <?php echo $form->activeTextField($infomodel, 'program_gfa', array('id' => 'program_gfa', 'class' =>'form-control')); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="construction_end" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('developer_client'); ?></label>
            <div class="input-group col-sm-5 ">
                <?php echo $form->activeTextField($infomodel, 'developer_client', array('id' => 'developer_client', 'class' =>'form-control')); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="construction_end" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('developer_client'); ?></label>
            <div class="input-group col-sm-5 ">
                <?php echo $form->activeTextField($infomodel, 'developer_client', array('id' => 'developer_client', 'class' =>'form-control')); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="construction_end" class="col-sm-2 control-label padding-lr5">Builder</label>
            <div class="input-group col-sm-5 ">
                <?php echo $form->activeTextField($infomodel, 'builder', array('id' => 'builder', 'class' =>'form-control')); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="construction_end" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('developer_client'); ?></label>
            <div class="input-group col-sm-5 ">
                <?php echo $form->activeTextField($infomodel, 'developer_client', array('id' => 'developer_client', 'class' =>'form-control')); ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-12">
            <button type="button" id="sbtn" onclick="btnsubmit()" class="btn btn-primary btn-lg"  ><?php echo Yii::t('common', 'button_save'); ?></button>
            <button type="button" class="btn btn-default btn-lg" style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>
        </div>
    </div>
</div>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    $(document).ready(function(){

    });

    //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['project/list']; ?>";
    }

    var btnsubmit = function (){
        $("#sbtn").attr('disabled','true');

        if($("#ind_no").prop("checked")){
            $("#independent").val(0);
        }
        if($("#ind_yes").prop("checked")){
            $("#independent").val(1);
        }
//        var A = 'A';
//        var B = 'B';
//        var C = 'C';
//        if($("#ptw_mode_a").prop("checked")){
//            $("#ptw_mode").val(A);
//        }
//        if($("#ptw_mode_b").prop("checked")){
//            $("#ptw_mode").val(B);
//        }
//        if($("#ptw_mode_c").prop("checked")){
//            $("#ptw_mode").val(C);
//        }
//        //location_require_no
//        if($("#location_require_no").prop("checked")){
//            $("#location_require").val(0);
//        }
//        if($("#location_require_yes").prop("checked")){
//            $("#location_require").val(1);
//        }
        var mode = $("#_mode_").val();
        if(mode == 'insert'){
            url = "index.php?r=proj/project/newprogram";
        }else{
            url = "index.php?r=proj/project/editprogram";
        }
        $("#info_form").submit();
        // $.ajax({
        //     data:$('#form1').serialize(),
        //     url: url,
        //     type: "POST",
        //     dataType: "json",
        //     beforeSend: function () {
        //
        //     },
        //     success: function (data) {
        //         if(data.status == '-1'){
        //             $('#msgbox').addClass('alert-danger fa-ban');
        //         }else{
        //             $('#msgbox').addClass('alert-success fa-ban');
        //         }
        //         $('#msginfo').html(data.msg);
        //         $('#msgbox').show();
        //         $('#sbtn ').removeAttr('disabled');
        //     },
        //     error: function () {
        //         $('#msgbox').addClass('alert-danger fa-ban');
        //         $('#msginfo').html('系统错误');
        //         $('#msgbox').show();
        //     }
        // });
    }
</script>
