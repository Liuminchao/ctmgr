<style type="text/css">
    .form-group span.required {
        color: #FF0000;
        font-size: 150%;
    }
</style>
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
    'id' => 'per_form',
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
    <div >
        <input type="hidden" id="user_id" name="StaffInfo[user_id]" value="<?php echo "$user_id"; ?>"/>
        <input type="hidden" id="tag_id" name="Tag[tag_id]" value="per"/>
        <input type="hidden" id="home_id_src" name="File[home_id_src]" />
        <input type="hidden" id="filebase64">
        <input type="hidden" id="upload_url" value="<?php echo "$upload_url" ?>"/>
    </div>
<!--<div class="row">
        <div class="col-md-12">
            <h3 class="form-header text-blue"><?php echo Yii::t('comp_staff', 'Aptitude Info'); ?></h3>
        </div>
    </div>-->
    
    <div class="row">
<!--        <div class="col-md-6">  姓名中文  
            <div class="form-group">
                <label for="name_cn" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('name_cn'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($infomodel, 'name_cn', array('id' => 'name_cn', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>-->
        <div class="col-md-6" ><!--  姓英文  -->
            <div class="form-group">
                <label for="family_name" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('family_name'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($infomodel, 'family_name', array('id' => 'family_name', 'class' =>'form-control','check-type' => '')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-6" ><!--  名英文  -->
            <div class="form-group">
                <label for="first_name" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('first_name'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($infomodel, 'first_name', array('id' => 'first_name', 'class' =>'form-control','check-type' => '')); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6" ><!--  婚姻情况  -->
            <div class="form-group">
                <label for="nationality" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('Marital Status'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php
                    $marital = Staff::Marital();
                    echo $form->activeDropDownList($infomodel, 'marital',$marital ,array('id' => 'marital', 'class' => 'form-control', 'check-type' => '','required-message' => ''));
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-6" ><!--  出生日期  -->
            <div class="form-group">
                <label for="birth_date" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('birth_date'); ?></label>
                <div class="input-group col-sm-6">
                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <?php echo $form->activeTextField($infomodel, 'birth_date', array('id' => 'birth_date', 'class' =>'form-control b_date_per', 'onclick' => "WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})", 'check-type' => '')); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6" ><!--  国籍  -->
            <div class="form-group">
                <label for="nationality" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('nationality'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($infomodel, 'nationality', array('id' => 'nationality', 'class' =>'form-control','check-type' => '')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-6"><!--  税  -->
            <div class="form-group">
                <label for="home_address" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('Race'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php
                    $race = Staff::Race();
                    echo $form->activeDropDownList($infomodel, 'race',$race ,array('id' => 'race', 'class' => 'form-control', 'check-type' => '','required-message' => ''));
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6"><!--  邮政编码  -->
            <div class="form-group">
                <label for="sg_postal_code" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('sg_postal_code'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($infomodel, 'sg_postal_code', array('id' => 'sg_postal_code', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-6"><!--  曾任职位  -->
            <div class="form-group">
                <label for="home_address" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('Previous Industry Experience & Designation'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($infomodel, 'previous_designation', array('id' => 'previous_designation', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row"> 
        <div class="col-md-6"><!--  国内第二联系人姓名  -->
            <div class="form-group">
                <label for="home_contact" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('home_contact'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($infomodel, 'home_contact', array('id' => 'home_contact', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-6"><!--  国内家庭地址  -->
            <div class="form-group">
                <label for="home_address" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('home_address'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($infomodel, 'home_address', array('id' => 'home_address', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6"><!--  联系电话  -->
            <div class="form-group">
                <label for="home_phone" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('home_phone'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($infomodel, 'home_phone', array('id' => 'home_phone', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-6"><!--  关系  -->
            <div class="form-group">
                <label for="relationship" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('relationship'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($infomodel, 'relationship', array('id' => 'relationship', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-6"><!--  新加坡联系方式  -->
            <div class="form-group">
                <label for="sg_phone" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('sg_phone'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($infomodel, 'sg_phone', array('id' => 'sg_phone', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-6"><!--  新加坡住址  -->
            <div class="form-group">
                <label for="sg_address" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('sg_address'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($infomodel, 'sg_address', array('id' => 'sg_address', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
    </div>
<!--    <div class="row">-->
<!--        <div class="col-md-6"><!--  身份证号  -->
<!--            <div class="form-group">-->
<!--                <label for="home_id" class="col-sm-3 control-label padding-lr5">--><?php //echo $infomodel->getAttributeLabel('home_id'); ?><!--</label>-->
<!--                <div class="col-sm-6 padding-lr5">-->
<!--                    --><?php //echo $form->activeTextField($infomodel, 'home_id', array('id' => 'home_id', 'class' => 'form-control', 'check-type' => '')); ?>
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="col-md-6"><!--  身份证照片  -->
<!--            <div class="form-group">-->
<!--                 <label for="home_id_photo" class="col-sm-3 control-label padding-lr5" >--><?php //echo $infomodel->getAttributeLabel('home_id_photo');?><!--</label>-->
<!--                 --><?php //echo $form->activeFileField($infomodel, 'home_id_photo', array('id' => 'home_id_photo', 'class' => 'form-control', "check-type" => "", 'style' => 'display:none', 'onchange' => "dealImage(this);")); ?>
<!--                    <div class="input-group col-md-6 padding-lr5">-->
<!--                        <input id="uploadurl_per" class="form-control" type="text" disabled>-->
<!--                        <span class="input-group-btn">-->
<!--                            <a class="btn btn-warning" onclick="$('input[id=home_id_photo]').click();">-->
<!--                            <i class="fa fa-folder-open-o"></i> --><?php //echo Yii::t('common','button_browse'); ?>
<!--                            </a>-->
<!--                        </span>-->
<!--                    </div>-->
<!--            </div>-->
<!--        </div>-->
<!--</div>-->
<!--    --><?php //if($_mode_ == 'insert'){    ?>
<!--        <div class="row">-->
<!--            <div class="col-md-6">-->
<!--                <div class="form-group">-->
<!--                    <label for="face_img"-->
<!--                           class="col-sm-3 control-label padding-lr5 img_class">--><?php //echo $model->getAttributeLabel('face_img'); ?><!--</label>-->
<!--                    <div class="col-sm-3 padding-lr5 img_class">-->
<!---->
<!--                        <img width="90" id="photo" src=""/>-->
<!--                        <!--                    <div id="preview" style="width: 40px;height: 30px"></div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    --><?php //} ?>
<!--</div>-->
<?php //if($_mode_ == 'edit'&& $infomodel->home_id_photo!=''){    ?>
<!---->
<!--    <div class="row">-->
<!--        <div class="col-md-6">-->
<!--            <div class="form-group">-->
<!--                <label for="home_id_photo"-->
<!--                       class="col-sm-3 control-label padding-lr5 img_home">--><?php //echo $infomodel->getAttributeLabel('home_id_photo'); ?><!--</label>-->
<!--                <div class="col-sm-6 padding-lr5 img_home">-->
<!--                    --><?php
//                        $new_img_url = $infomodel->home_id_photo;
//                    ?>
<!--                    <img width="90" id="photo" src="--><?php //echo $new_img_url; ?><!--"/>-->
<!--                      -->
<!---->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--    --><?php //}else if($_mode_ == 'edit'&& $infomodel->home_id_photo==''){ ?>
<!--    <div class="row">-->
<!--        <div class="col-md-6">-->
<!--            <div class="form-group">-->
<!--                <label for="face_img"-->
<!--                       class="col-sm-3 control-label padding-lr5 img_class">--><?php //echo $model->getAttributeLabel('face_img'); ?><!--</label>-->
<!--                <div class="col-sm-3 padding-lr5 img_class">-->
<!---->
<!--                    <img width="90" id="photo" src=""/>-->
<!--                    <!--                    <div id="preview" style="width: 40px;height: 30px"></div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--    --><?php //} ?>
</div>
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
        
        $('.b_date_per').each(function(){
            a1 = $(this).val();
            a2 = datetocn(a1);
            if(a2!=' undefined'){
                $(this).val(a2);
            }
        });

    });

    /**
     * @param base64Codes
     *            图片的base64编码
     */
    function sumitImageFile(base64Codes){
        if(base64Codes) {
            var form = document.forms[0];

            var formData = new FormData();   //这里连带form里的其他参数也一起提交了,如果不需要提交其他参数可以直接FormData无参数的构造函数

            //convertBase64UrlToBlob函数是将base64编码转换为Blob
            formData.append("file1", convertBase64UrlToBlob(base64Codes), 'per.png');  //append函数的第一个参数是后台获取数据的参数名,和html标签的input的name属性功能相同

            //formData.append("file2", $("#orifile")[0].files[0]);
            console.log(form);
            var upload_url = $("#upload_url").val();
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
                            $("#home_id_src").val(value.file1);
                            $('#home_id_photo').attr("disabled","disabled");
                            $('#sbtn').attr("disabled","disabled");
                            addcloud(); //为页面添加遮罩
                            document.onreadystatechange = subSomething; //监听加载状态改变
                            $("#per_form").submit();
                        }
                    });
                },
            });
        }else{
            $("#per_form").submit();
        }
    }

    //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['staff/list']; ?>";
        //window.location = "index.php?r=comp/usersubcomp/list";
    }
    
     //照片显示方法   
    $('.img_home').mouseout (function(){
        $("#attend_home_Img").hide();
    });
    
    $('.img_home').mousemove (function(){
        img_url = $(this).attr("src");
        set_per_Img(img_url,this);
        $("#attend_home_Img").show();
     });
    
    function set_per_Img(img_url,obj){
        var src,h;
        src=document.getElementById("photo").src;
//        alert(src);
        $("#attend_home_Photo").attr("src",src);
        h=$("#attend_home_Img").innerHeight();
        //document.getElementById("attendImg").style.top=($(obj).position().top-h+253)+"px";
        //document.getElementById("attendImg").style.left=($(obj).position().left-350)+"px";
        $("#attend_home_Img").css('top', ($(obj).position().top-h+700)+"px");
        $("#attend_home_Img").css('left', ($(obj).position().left+300)+"px");
    }
</script>
<div id="attend_home_Img" class="popDiv">
    <div class="popDiv_top">
            <div class="popDiv_body"><img id="attend_home_Photo" src="" width="240"/></div>
    </div>
    <div class="popDiv_bottom"></div>
    <script type="text/javascript">
        $("#attend_home_Img").hide();
    </script>
</div>



