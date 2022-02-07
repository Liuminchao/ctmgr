<script type="text/javascript" src="js/ajaxfileupload.js"></script>
<?php
if ($msg) {
    $class = Utils::getMessageType($msg['status']);
    echo "<div class='alert {$class[0]} alert-dismissable'>
	<i class='fa {$class[1]}'></i>
	<button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
	<b>提示：</b>{$msg['msg']}
	</div>
	";
//    echo "<script type='text/javascript'>
//     	{$this->gridId}.refresh();
//     	</script>";
//        var_dump($msg['success']);
}

$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'enableAjaxSubmit' => true,
    'ajaxUpdateId' => 'content-body',
    'focus' => array($model, 'position_name'),
    'role' => 'form', //可省略
    'formClass' => 'form-horizontal', //可省略 表单对齐样式
    'autoValidation' => true,
));

?>

<div class="box-body">
    <form enctype="multipart/form-data">
        <div class="box-body">
            <div id='msgbox' class='alert alert-dismissable ' style="display:none;">
                <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
                <strong id='msginfo'></strong><span id='divMain'></span>
            </div>

            <div class="row"><!--  form_id  -->
                <div class="form-group" style="margin-left: -4px">
                    <label for="form_id" class="col-sm-3 control-label padding-lr5">Form Id</label>
                    <div class="col-sm-3 padding-lr5">
                        <?php echo $form->activeTextField($model, 'form_id', array('id' => 'form_id', 'class' => 'form-control', 'check-type' => '')); ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-group" style="margin-left: -4px">
                    <label for="form_type" class="col-sm-3 control-label padding-lr5">Program</label>
                    <div class="col-sm-3 padding-lr5" >
                        <select class="form-control input-sm" name="QaChecklist[program_id]" id="program_id" style="width: 100%;">
                            <!--                        <option value="">----><?php //echo Yii::t('proj_report', 'report_program').'('.Yii::t('common', 'is_requried').')'; ?><!----</option>-->
                            <option value=''>System</option>
                            <?php
                                $pro_model = Program::model()->findByPk($program_id);
                                $root_proid = $pro_model->root_proid;
                                $root_model = Program::model()->findByPk($root_proid);
                                $root_proid_name = $root_model->program_name;
                                echo "<option value='{$root_proid}'>{$root_proid_name}</option>";
                            ?>
                        </select>
                    </div>
                    <span id="valierr" class="help-block" style="color:#FF9966">*</span>
                </div>
            </div>

            <div class="row">
                <div class="form-group" style="margin-left: -4px">
                    <label for="form_type" class="col-sm-3 control-label padding-lr5">Form Type</label>
                    <div class="col-sm-3 padding-lr5" >
                        <select class="form-control input-sm" name="QaChecklist[form_type]" id="form_type" style="width: 100%;">
                            <option value=''>---Please Select---</option>
                            <option value='A'>Site</option>
                            <option value='B'>PPVC factory</option>
                            <option value='C'>PBU</option>
                            <option value='C'>General</option>
                        </select>
                    </div>
                    <span id="valierr" class="help-block" style="color:#FF9966">*</span>
                </div>
            </div>

            <div class="row"><!--  form_name  -->
                <div class="form-group" style="margin-left: -4px">
                    <label for="form_name" class="col-sm-3 control-label padding-lr5">Form Nmae</label>
                    <div class="col-sm-3 padding-lr5">
                        <?php echo $form->activeTextField($model, 'form_name', array('id' => 'form_name', 'class' => 'form-control', 'check-type' => '')); ?>
                    </div>
                </div>
            </div>

            <div class="row"><!--  form_name_en  -->
                <div class="form-group" style="margin-left: -4px">
                    <label for="form_name_en" class="col-sm-3 control-label padding-lr5">Form Nmae En</label>
                    <div class="col-sm-3 padding-lr5">
                        <?php echo $form->activeTextField($model, 'form_name_en', array('id' => 'form_name_en', 'class' => 'form-control', 'check-type' => '')); ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-group" style="margin-left: -4px">
                    <label for="type_id" class="col-sm-3 control-label padding-lr5">Type Id</label>
                    <div class="col-sm-3 padding-lr5">
                        <select class="form-control input-sm" name="QaChecklist[type_id]" id="type_id" >
                            <option>---Please Select---</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2"></div>
                <div class="col-sm-2">
                    <a href="javascript:void(0)" onclick="downloadFile()"><input type="button" class="btn btn-primary btn-sm" value="<?php echo Yii::t('comp_staff','Template Download');?>" /></a>
                </div>
                <div class="col-sm-4 control-label" style="text-align: left"><?php echo Yii::t('comp_staff', 'Prompt_Template'); ?></div>
            </div>
            <hr/>


            <div class="form-group">
                <label for="group_name" class="col-sm-2 control-label"><?php echo Yii::t('comp_staff', 'Select file'); ?></label>
                <div class="col-sm-6">
                    <input class="form-control" name="file" id="file" type="file" style="display:none" onchange="$('#uploadurl').val($(this).val())">
                    <div class="input-group ">
                        <input type="hidden" id="project_id" value="<?php echo $project_id; ?>">
                        <input type="text" name="file" class="form-control" id="uploadurl" onclick="$('#file').click();"  readonly>
                        <span class="input-group-btn">
                    <a class="btn btn-warning" onclick="$('input[id=file]').click();">
                        <i class="fa fa-folder-open-o"></i> <?php echo Yii::t('common','button_browse'); ?>
                    </a>
                </span>
                    </div>
                </div>
            </div>


            <div class="box-footer">
                <a class="btn btn-primary btn-lg col-sm-offset-2" id="submit" onclick="post();"><?php echo Yii::t('common', 'button_ok'); ?></a>
                <button type="button" class="btn btn-primary btn-lg col-sm-offset-2" style="margin-left: 10px" onclick="back('<?php echo $program_id ?>');"><?php echo Yii::t('common', 'button_back'); ?></button>

            </div>
    </form>

    <hr>
    <div class="form-group">
        <label for="group_name" class="col-sm-2 control-label"><?php echo Yii::t('comp_staff', 'upload result'); ?></label>
        <div class="col-sm-6" id="prompt"></div>
    </div>
</div>


<?php $this->endWidget(); ?>

<script type="text/javascript">
    //初始化
    function Init(node) {
        return node.html("<option>---Please Select---</option>");
    }
    //处理work_pass_type
    $('#form_type').change(function(){
        //alert($(this).val());

        var typeObj = $("#type_id");
        var typeOpt = $("#type_id option");
        Init(typeObj);
        var val = $(this).val() ;

        // if ($(this).val() == 0) {
        //     blockOpt.remove();
        //     regionOpt.remove();
        //     return;
        // }
        $.ajax({
            type: "POST",
            url: "index.php?r=qa/import/formtype",
            data: {form_type:$("#form_type").val()},
            dataType: "json",
            success: function(data){ //console.log(data);

                // blockOpt.remove();
                if (!data) {
                    return;
                }
                for (var o in data) {//console.log(o);
                    typeObj.append("<option value='"+o+"'>"+data[o]+"</option>");
                }
            },
        })
    })

    //模板下载
    var downloadFile = function () {
        var url = './index.php?r=qa/import/download';
        window.location.href=url;
    }
    //上传是否为空
    var validate = function () {
        //var file = $("#file").attr("value");
        var file = document.getElementById("uploadurl").value;
        if(file == ''){
            alert('<?php echo Yii::t('comp_staff', 'Error Upload_file is null'); ?>');
            return false;
        }
    }
    var back = function (id) {
        window.location = "index.php?r=qa/import/list&program_id="+id;
    }

    /*
     * 保存表单类型
     */
    var post = function(){
        $.ajax({
            data:$('#form1').serialize(),
            url: "index.php?r=qa/import/save",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                if(data.status == '1'){
                    ajaxFileUpload();
                }else if(data.status == '-2'){
                    $('#msgbox').addClass('alert-danger fa-ban');
                    $('#msginfo').html('Are you sure to replace the existing Form ID?');
                    $('#msgbox').show();
                    ajaxFileUpload()
//                    setTimeout(ajaxFileUpload(),1000);
                }else{
                    $('#msgbox').addClass('alert-danger fa-ban');
                    $('#msginfo').html('系统错误');
                    $('#msgbox').show();
                }
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }

    /*
     * 上传文件
     */
    var per_read_cnt = 1;

    var ajaxFileUpload = function (){

        var file = document.getElementById("uploadurl").value;
        if(file == ''){
            alert('<?php echo Yii::t('comp_staff', 'Error Upload_file is null'); ?>');
            return false;
        }
        $('#prompt').html('<?php echo Yii::t('comp_staff', 'export_loding'); ?>');
        jQuery.ajaxFileUpload({
            url: './index.php?r=qa/import/upload',
            secureuri: false,
            fileElementId: 'file',
            dataType: 'json',
            success: function (data, status) {
                $('#prompt').append("</br><?php echo Yii::t('comp_staff', 'total'); ?> "+data.rowcnt+" <?php echo Yii::t('comp_staff', 'begin_import'); ?>").show();

                ajaxReadData(data.filename, data.rowcnt, 1);

            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                //alert(XMLHttpRequest);
                //alert(textStatus);
                //alert(errorThrown);
            },

        });
        return false;
    }

    /*
     * 数据导入
     */
    var ajaxReadData = function (filename, rowcnt, startrow){//alert('aa');
        var id = $("#project_id").val();
        jQuery.ajax({
            data: {filename:filename, startrow: startrow, per_read_cnt:per_read_cnt, id:id},
            type: 'post',
            url: './index.php?r=qa/import/readdata',
            dataType: 'json',
            success: function (data, textStatus) {
                for (var o in data) {
                    $('#prompt').append("</br>Row "+o+" : "+data[o].msg);
                }
                if (rowcnt > startrow) {
                    ajaxReadData(filename, rowcnt, startrow+per_read_cnt);
                }else{
                    $('#prompt').append("</br><?php echo Yii::t('comp_staff', 'end_import'); ?>");
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                //alert(XMLHttpRequest);
                //alert(textStatus);
                //alert(errorThrown);
            },
        });
        return false;
    }

</script>



