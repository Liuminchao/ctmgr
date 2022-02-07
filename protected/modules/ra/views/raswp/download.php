<?php

$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'focus' => array($rs, 'old_pwd'),
    //'autoValidation' => false,
    "action" => "javascript:formSubmit()",
//    'enableAjaxSubmit' => false,
//    'ajaxUpdateId' => 'content-body',
//    'role' => 'form', //可省略
//    'formClass' => 'form-horizontal', //可省略 表单对齐样式
//    'autoValidation' => false,

));
//echo $form->activeHiddenField($model, 'program_id', array(), '');
//var_dump($program_id);
//exit;
?>

<div class="box-body">
    <div id='msgbox' class='alert alert-dismissable ' style="display:none;">
        <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
        <strong id='msginfo'></strong><span id='divMain'></span>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3 class="form-header text-blue"><?php echo Yii::t('comp_ra', 'Batch download'); ?></h3>
        </div>
    </div>
    <div class="row">

        <div class="form-group">
            <label for="worker_type" class="col-sm-2 control-label padding-lr5">类型</label>
            <div class="col-sm-5 padding-lr5">
                <label><input name="Tag" class="radioItem" type="radio" value="1" />RA </label>
                <label><input name="Tag" class="radioItem" type="radio" value="2" />SWP </label>
            </div>
        </div>

        <div class="form-group">
            <label for="worker_type" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('worker_type'); ?></label>
            <div class="col-sm-5 padding-lr5" id="workerlist">
                <?php
//                echo $form->activeDropDownList($model, 'type_id', $worker_type ,array('id' => 'type_id', 'class' => 'form-control', 'check-type' => ''));
                ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-12">
                <button type="button" class="btn btn-default btn-lg" style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>
                <button type="button" class="btn btn-default btn-lg" style="margin-left: 10px" onclick="download();"><?php echo Yii::t('common', 'button_download'); ?></button>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>

<script type="text/javascript">

    $(document).ready(function(){
        $(".radioItem").change(function(){
            var tag = $("input[name='Tag']:checked").val();
            $.ajax({
                data: {tag: tag},
                url: "index.php?r=ra/raswp/workertype",
                dataType: "json",
                type: "POST",
                success: function (data) {
                    $("#workerlist").empty();
                    $('#workerlist').html("<select id='select'></select>");
                    var optionstring = "";
                    for (var o in data) {
                        optionstring += "<option value='"+ o+"'>" + data[o] + "</option>";
                    }
                    $("#select").html(optionstring);
                }
            });
        });
    });
    //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['ra/list']; ?>";
    }

    //下载
    var download = function () {
        var val = $('#select option:selected').val();
//        alert(val);
        window.location = "index.php?r=ra/raswp/downloadtemplate&type_id="+val;
    }

</script>
