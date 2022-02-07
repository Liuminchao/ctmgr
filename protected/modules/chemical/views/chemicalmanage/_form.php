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
    'autoValidation' => true
  ));
$upload_url = Yii::app()->params['upload_url'];
?>
<div class="row">
    <p style="color:red;font-size:16px;">Document name cannot contain special characters and symbols such as . / and etc.</p>
</div>
<div class="box-body">
    <div class="row">
        <div class="col-md-12">
            <h3 class="form-header text-blue"><?php echo Yii::t('comp_staff', 'Base Info'); ?></h3>
        </div>
    </div>
   <div >
       <input type="hidden" id="chemical_src" name="File[chemical_src]" />
       <input type="hidden" id="filebase64">
       <input type="hidden" id="upload_url" value="<?php echo "$upload_url" ?>"/>
    </div>
    <div class="row">
        <div class="col-md-6"><!--  化学物品编码  -->
            <div class="form-group">
                <label for="chemical_id" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('chemical_id'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($model, 'chemical_id', array('id' => 'chemical_id', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('chemical','Error Chemical_id is null'))); ?>
                </div>
            </div>
        </div>
        <div class="col-md-6"><!--  化学物品名称  -->
            <div class="form-group">
                <label for="chemical_name" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('chemical_name'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($model, 'chemical_name', array('id' => 'chemical_name', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('chemical','Error Chemical_name is null'))); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6"><!--  有害物质/化合物  -->
            <div class="form-group">
                <label for="chemical_id" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('compound'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($model, 'compound', array('id' => 'compound', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('chemical','Error Chemical_id is null'))); ?>
                </div>
            </div>
        </div>
        <div class="col-md-6"><!--  用法  -->
            <div class="form-group">
                <label for="chemical_name" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('usage'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($model, 'usage', array('id' => 'usage', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('chemical','Error Chemical_name is null'))); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6"><!--  危险特性  -->
            <div class="form-group">
                <label for="chemical_id" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('properties'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($model, 'properties', array('id' => 'properties', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('chemical','Error Chemical_id is null'))); ?>
                </div>
            </div>
        </div>
        <div class="col-md-6"><!--  保管条件  -->
            <div class="form-group">
                <label for="chemical_name" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('storage_require'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($model, 'storage_require', array('id' => 'storage_require', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('chemical','Error Chemical_name is null'))); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6"><!--  个人防护  -->
            <div class="form-group">
                <label for="chemical_id" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('personal_protection'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($model, 'personal_protection', array('id' => 'personal_protection', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('chemical','Error Chemical_id is null'))); ?>
                </div>
            </div>
        </div>
        <div class="col-md-6"><!--  健康危害和急救措施  -->
            <div class="form-group">
                <label for="chemical_name" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('first_aid_measures'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($model, 'first_aid_measures', array('id' => 'first_aid_measures', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('chemical','Error Chemical_name is null'))); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6"><!--  个人防护  -->
            <div class="form-group">
                <label for="chemical_id" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('other_measures'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($model, 'other_measures', array('id' => 'other_measures', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('chemical','Error Chemical_id is null'))); ?>
                </div>
            </div>
        </div>
        <div class="col-md-6"><!--  负责人  -->
            <div class="form-group">
                <label for="chemical_name" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('person_in_charge'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($model, 'person_in_charge', array('id' => 'person_in_charge', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('chemical','Error Chemical_name is null'))); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6"><!--  化学物品类型  -->
            <div class="form-group">
                <label for="type_no" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('type_no'); ?></label>
                <div class="col-sm-6 padding-lr5">
                        <?php
                            $chemicallist = ChemicalType::chemicalList();
//                            array_unshift($chemicallist, Yii::t('chemical', 'chemical_type'));
                            echo $form->activeDropDownList($model, 'type_no',$chemicallist ,array('id' => 'type_no', 'class' => 'form-control','check-type' => ''));
                        ?>

                </div>
                <i class="help-block" style="color:#FF9966">*</i>
            </div>
        </div>
        <div class="col-md-6"><!--  化学物品内容  -->
            <div class="form-group">
                <label for="chemical_content" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('chemical_content'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextArea($model, 'chemical_content', array('id' => 'chemical_id', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
     </div>
    <div class="row">
        <div class="col-md-6"><!--  照片  -->
        <div class="form-group">
                 <label for="chemical_img" class="col-sm-3 control-label padding-lr5" ><?php echo $model->getAttributeLabel('chemical_img');?></label>
                 <?php
                 echo $form->activeFileField($model, 'chemical_img', array('id' => 'chemical_img', 'class' => 'form-control', "check-type" => "", 'style' => 'display:none', 'onchange' => "dealImage(this)"));
//                 echo '<div class="col-sm-6 padding-lr5"><input id="chemical_img" class="form-control" type="file"  name="S[chemical_img]" style="display:none"  onchange="a(this)" ></div>';
                 ?>
                    <div class="input-group col-md-6 padding-lr5">
                        <input id="chemical_url" class="form-control" type="text" disabled>
                        <span class="input-group-btn">
                            <a class="btn btn-warning" onclick="$('input[id=chemical_img]').click();">
                            <i class="fa fa-folder-open-o"></i> <?php echo Yii::t('common','button_browse'); ?>
                            </a>
                        </span>
                        <i class="help-block" style="color:#FF9966">*</i>
                    </div>
            </div>
        </div>

        </div>

    <?php if($_mode_ == 'insert'){    ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="face_img"
                           class="col-sm-3 control-label padding-lr5 img_class"><?php echo $model->getAttributeLabel('chemical_img');?></label>
                    <div class="col-sm-3 padding-lr5 img_class">

                        <img width="90" id="photo" src=""/>
                        <!--                    <div id="preview" style="width: 40px;height: 30px"></div>-->
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
<?php if($_mode_ == 'edit'){    ?>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="face_img"
                       class="col-sm-3 control-label padding-lr5 chemical_img_class"><?php echo $model->getAttributeLabel('chemical_img');?></label>
                <div class="col-sm-3 padding-lr5 chemical_img_class">
                    <?php
                    $new_img_url = $model->chemical_img;
                    ?>
                    <img width="90" id="photo" src="<?php echo $new_img_url; ?>"/>
                    <!--                    <div id="preview" style="width: 40px;height: 30px"></div>-->
                </div>
            </div>
        </div>
    </div>
<?php } ?>
    <div class="row"> </div>

</div>    
     
<!--<?php //$this->renderpartial('_infoform', array( 'infomodel' => $infomodel, '_mode_' => 'insert')); ?>--> 
    <div class="row">
        <div class="form-group">
        <div class="col-sm-offset-4 col-sm-10">
            <button type="button" id="sbtn" class="btn btn-primary btn-lg" onclick="btnsubmit();"><?php echo Yii::t('common', 'button_save'); ?></button>
            <button type="button" class="btn btn-default btn-lg"
                    style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>
        </div>
        </div>
    </div>

 
<?php $this->endWidget(); ?>
<script src="js/compress.js"></script>
<script src="js/loading.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        $('.b_date_permit').each(function(){
            a1 = $(this).val();
            a2 = datetocn(a1);
            if(a2!=' undefined'){
                $(this).val(a2);
            }
        });
    });
  function a(obj){
    $('#chemical_url').val(obj.value);
  }
  function b(obj){
    $('#permit_url').val(obj.value);
  }
    //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['chemical/list']; ?>";
        //window.location = "index.php?r=comp/usersubcomp/list";
    }
    /**
     * @param base64Codes
     *            图片的base64编码
     */
    function sumitImageFile(base64Codes){
        if(base64Codes) {
            var form = document.forms[0];

            var formData = new FormData();   //这里连带form里的其他参数也一起提交了,如果不需要提交其他参数可以直接FormData无参数的构造函数

            //convertBase64UrlToBlob函数是将base64编码转换为Blob
            formData.append("file1", convertBase64UrlToBlob(base64Codes), 'chemical.png');  //append函数的第一个参数是后台获取数据的参数名,和html标签的input的name属性功能相同

            //formData.append("file2", $("#orifile")[0].files[0]);
            var upload_url = $("#upload_url").val();
            console.log(form);
            //ajax 提交form
            $.ajax({
                url: upload_url,
                type: "POST",
                data: formData,
                dataType: "json",
                processData: false,         // 告诉jQuery不要去处理发送的数据
                contentType: false,        // 告诉jQuery不要去设置Content-Type请求头
                success: function (data) {
                    $.each(data, function (name, value) {
                        if (name == 'data') {
                            $("#chemical_src").val(value.file1);
                            $('#chemical_img').attr("disabled","disabled");
                            $('#sbtn').attr("disabled","disabled");
                            addcloud(); //为页面添加遮罩
                            document.onreadystatechange = subSomething; //监听加载状态改变
                            $("#form1").submit();
                        }
                    });
                },

            });
        }else{
            $("#form1").submit();
        }
    }
    //化学物品照片显示方法   
    $('.chemical_img_class').mouseout (function(){
        $("#chemical_attendImg").hide();
    });
    
    $('.chemical_img_class').mousemove (function(){

        img_url = $(this).attr("src");
//        alert(img_url);
        set_ChemicalImg(img_url,this);
        $("#chemical_attendImg").show();
     });

     
    function set_ChemicalImg(img_url,obj){
        var src,h;
        src=document.getElementById("photo").src;
//        alert(src);
        $("#chemical_attendPhoto").attr("src",src);
        h=$("#chemical_attendImg").innerHeight();
//        alert($(obj).position().top);
        //document.getElementById("attendImg").style.top=($(obj).position().top-h+253)+"px";
        //document.getElementById("attendImg").style.left=($(obj).position().left-350)+"px";
        $("#chemical_attendImg").css('top', ($(obj).position().top-h+550)+"px");
        $("#chemical_attendImg").css('left', ($(obj).position().left+320)+"px");
    }

</script>
<div id="chemical_attendImg" class="popDiv">
    <div class="popDiv_top">
            <div class="popDiv_body"><img id="chemical_attendPhoto" src="" width="340"/></div>
    </div>
    <div class="popDiv_bottom"></div>
    <script type="text/javascript">
        $("#chemical_attendImg").hide();
    </script>
</div>
    




