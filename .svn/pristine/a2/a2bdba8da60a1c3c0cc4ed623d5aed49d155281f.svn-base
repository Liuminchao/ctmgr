<?php
/* @var $this ProgramController */
/* @var $model Program */
/* @var $form CActiveForm */
if ($msg) {
    $class = Utils::getMessageType($msg['status']);
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
    'focus' => array($model, 'program_name'),
    'role' => 'form', //可省略
    'formClass' => 'form-horizontal', //可省略 表单对齐样式
        ));
echo $form->hiddenField('father_proid', $father_proid);
echo $form->activeHiddenField($model, 'program_id', array());
?>
<div class="box-body">


    <div class="row">
        <div class="col-md-12">
            <h3 class="form-header text-blue"><?php echo Yii::t('proj_project', 'Assign SC'); ?></h3>
        </div>
    </div>
    
    <div class="row">
        <div class="form-group">
            <label for="program_content" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('subcomp_sn'); ?></label>
            <div class="input-group col-sm-10">
                <div class="col-sm-6 padding-lr5" style="padding-top: -3px;margin-top: -2px">
                <?php
                    //$compList = Contractor::compList();
                    //echo $form->activeDropDownList($model, 'contractor_id',$compList ,array('id' => 'contractor_id', 'class' => 'form-control', 'check-type' => 'required','required-message'=>Yii::t('proj_project', 'error_proj_name_is_null')));
                    if($_mode_ == 'insert') {
                        echo $form->activeTextField($model, 'subcomp_sn', array('id' => 'subcomp_sn', 'class' => 'form-control', 'check-type' => ''));
                    }else{
                        echo '<h4>'.$model->subcomp_sn.'<h4>';
                    }
                ?>
                </div>
                <div class="col-sm-6 padding-lr5">
                    <span id="first_msg_subcomp" class="help-block" style="display:none"></span>
                </div>
                <input type="hidden" name="Program[contractor_id]" id="contractor_id" value="<?php echo $model->contractor_id ?>">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="program_content" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('subcomp_name'); ?></label>
            <div class="input-group col-sm-10">
                <div class="col-sm-6 padding-lr5" style="padding-top: -3px;margin-top: -2px">
                    <?php
                    //$compList = Contractor::compList();
                    //echo $form->activeDropDownList($model, 'contractor_id',$compList ,array('id' => 'contractor_id', 'class' => 'form-control', 'check-type' => 'required','required-message'=>Yii::t('proj_project', 'error_proj_name_is_null')));
                        if($_mode_ == 'insert') {
                            echo $form->activeTextField($model, 'subcomp_name', array('id' => 'subcomp_name', 'class' => 'form-control', 'check-type' => ''));
                        }else{
                            echo '<h4>'.$model->subcomp_name.'<h4>';
                        }
                    ?>
                </div>
                <div class="col-sm-6 padding-lr5">
                    <span id="second_msg_subcomp" class="help-block" style="display:none"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group">
            <label for="program_content" class="col-sm-2 control-label padding-lr5">Trade</label>
            <div class="input-group col-sm-10">
                <div class="col-sm-6 padding-lr5" style="padding-top: -3px;margin-top: -2px">
                    <?php
                    $type_list = ContractorType::typeList();
                    echo $form->activeDropDownList($model, 'sub_type_id',$type_list ,array('id' => 'sub_type_id', 'class' => 'form-control', 'check-type' => '','onchange'=>'changetype()'));
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php
    if($_mode_ == 'insert') {
        ?>
        <div class="row" style="display: none" id="others">
            <div class="form-group">
                <label for="program_content" class="col-sm-2 control-label padding-lr5">Others</label>
                <div class="col-sm-5 padding-lr5">
                    <?php
                    echo $form->activeTextField($model, 'sub_type', array('id' => 'sub_type', 'class' => 'form-control', 'check-type' => ''));
                    ?>
                </div>
            </div>
        </div>
        <?php
    }
    ?>

    <?php
    if($_mode_ == 'edit') {
        ?>
        <?php
        if($model->sub_type_id == '14') {
            ?>
            <div class="row" style="display: block" id="others">
                <div class="form-group">
                    <label for="program_content" class="col-sm-2 control-label padding-lr5">Others</label>
                    <div class="col-sm-5 padding-lr5">
                        <?php
                        echo $form->activeTextField($model, 'sub_type', array('id' => 'sub_type', 'class' => 'form-control', 'check-type' => ''));
                        ?>
                    </div>
                </div>
            </div>
            <?php
        }else{
            ?>
            <div class="row" style="display: none" id="others">
                <div class="form-group">
                    <label for="program_content" class="col-sm-2 control-label padding-lr5">Others</label>
                    <div class="col-sm-5 padding-lr5">
                        <?php
                        echo $form->activeTextField($model, 'sub_type', array('id' => 'sub_type', 'class' => 'form-control', 'check-type' => ''));
                        ?>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        <?php
    }
    ?>

    <div class="row">
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-12">
                <?php
                    if($_mode_ == 'edit') {
                ?>
                        <button type="submit"  id="sbtn" class="btn btn-primary btn-lg"><?php echo Yii::t('common', 'button_save'); ?></button>
                <?php
                    }else{
                ?>
                        <button type="submit" disabled="disabled" id="sbtn" class="btn btn-primary btn-lg"><?php echo Yii::t('common', 'button_save'); ?></button>
                <?php
                    }
                ?>
                <button type="button" class="btn btn-default btn-lg" style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    //返回
    
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['project/sublist']; ?>";
    }

    function changetype(){
        var type_id = $("#sub_type_id option:selected").val();
        var type_name = $("#sub_type_id option:selected").text();
        $('#sub_type').val("");
        if(type_id == '14'){
            $('#others').css('display','block');
        }else{
            $('#others').css('display','none');
            $('#sub_type').val(type_name);
        }
    }

    jQuery(document).ready(function () {

        $('#subcomp_sn').focus(function(){
            $('#sbtn').attr('disabled',true); 
        });
        $('#subcomp_sn').blur(function(){
            //alert($(this).val());
            $.ajax({
                type: "POST",
                url: "index.php?r=comp/info/querysn",
                data: {compsn:$("#subcomp_sn").val()},
                dataType: "json",
                success: function(data){
                    if(data.status==0) {//alert(data.id);
                        // $('#first_msg_subcomp').html(data.name).show();
                        $('#subcomp_name').val(data.name);
                        $('#contractor_id').val(data.id);
                        $('#sbtn').attr('disabled',false); 
                    }else{
                        $('#first_msg_subcomp').html(data.msg).show();
                        $('#contractor_id').val('');
                        $('#sbtn').attr('disabled',true); 
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    //alert(XMLHttpRequest.status);
                    //alert(XMLHttpRequest.readyState);
                    //alert(textStatus);
                },
            });
        });
        $('#subcomp_name').focus(function(){
            $('#sbtn').attr('disabled',true);
        });
        $('#subcomp_name').blur(function(){
            //alert($(this).val());
            $.ajax({
                type: "POST",
                url: "index.php?r=comp/info/queryname",
                data: {comp_name:$("#subcomp_name").val()},
                dataType: "json",
                success: function(data){
                    if(data.status==0) {//alert(data.id);
                        $('#second_msg_subcomp').html(data.name).show();
                        $('#contractor_id').val(data.id);
                        $('#sbtn').attr('disabled',false);
                    }else{
                        $('#second_msg_subcomp').html(data.msg).show();
                        $('#contractor_id').val('');
                        $('#sbtn').attr('disabled',true);
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

</script>
