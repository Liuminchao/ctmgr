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
    'id' => 'form1',
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
        <input type="hidden" id="independent" name="Program[independent]">
        <input type="hidden" id="program_id" name="Program[program_id]" value="<?php echo "$program_id"; ?>"/>
<!--        <input type="hidden" id="ptw_mode" name="Program[ptw_mode]">-->
<!--        <input type="hidden" id="location_require" name="Program[location_require]">-->
        <input type="hidden" id="_mode_"  value="<?php echo "$_mode_"; ?>"/>
        <input type="hidden" id="type" name="Program[TYPE]" value="<?php echo "$ptype"; ?>"/>
    </div>
<!--    <div class="row">-->
<!--        <div class="col-md-12">-->
<!--            <h3 class="form-header text-blue">--><?php //echo Yii::t('proj_project', 'Base Info'); ?><!--</h3>-->
<!--        </div>-->
<!--    </div>-->
    <div class="row">
        <div class="form-group">
            <label for="program_name" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('program_name'); ?></label>
            <div class="col-sm-5 padding-lr5">
                <?php
                    echo $form->activeTextField($model, 'program_name', array('id' => 'program_name', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('proj_project', 'error_proj_name_is_null')));
                ?>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="form-group">
            <label for="program_content" class="col-sm-2 control-label padding-lr5">Project Title</label>
            <div class="col-sm-5 padding-lr5">
                <?php
                    echo $form->activeTextArea($model, 'program_content', array('id' => 'program_content', 'class' => 'form-control'));
                ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="program_address" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('program_address'); ?></label>
            <div class="col-sm-5 padding-lr5">
                <?php
                echo $form->activeTextField($model, 'program_address', array('id' => 'program_address', 'class' => 'form-control'));
                ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-5 padding-lr5" style="padding-left:220px;">
            <div id="google_map"   style="width: 470px; height: 200px">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="program_amount" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('program_amount'); ?></label>
            <div class="col-sm-5 padding-lr5">
                <?php
                echo $form->activeTextField($model, 'program_amount', array('id' => 'program_amount', 'class' => 'form-control'));
                ?>
            </div>
        </div>
    </div>
<!--    <div class="row">-->
<!--        <div class="form-group">-->
<!--            <label for="construction_start" class="col-sm-2 control-label padding-lr5">--><?php //echo $model->getAttributeLabel('construction_start'); ?><!--</label>-->
<!--            <div class="input-group col-sm-5 ">-->
<!--                --><?php //echo $form->activeTextField($model, 'construction_start', array('id' => 'construction_start', 'class' =>'form-control b_date_start', 'onclick' => "WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})", 'check-type' => '')); ?>
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="row">-->
<!--        <div class="form-group">-->
<!--            <label for="construction_end" class="col-sm-2 control-label padding-lr5">--><?php //echo $model->getAttributeLabel('construction_end'); ?><!--</label>-->
<!--            <div class="input-group col-sm-5 ">-->
<!--                --><?php //echo $form->activeTextField($model, 'construction_end', array('id' => 'construction_end', 'class' =>'form-control b_date_end', 'onclick' => "WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})", 'check-type' => '')); ?>
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
    <div class="row">
        <div class="form-group">
            <label for="construction_end" class="col-sm-2 control-label padding-lr5">Project Ref No</label>
            <div class="col-sm-5 padding-lr5">
                <?php echo $form->activeTextField($infomodel, 'ref_no', array('id' => 'ref_no', 'class' =>'form-control')); ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group">
            <label for="construction_end" class="col-sm-2 control-label padding-lr5">Developer/Owner</label>
            <div class="col-sm-5 padding-lr5">
                <?php echo $form->activeTextField($infomodel, 'developer_owner', array('id' => 'developer_owner', 'class' =>'form-control')); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="construction_end" class="col-sm-2 control-label padding-lr5">Architect/QP for Building Plan</label>
            <div class="col-sm-5 padding-lr5">
                <?php echo $form->activeTextField($infomodel, 'build_plan', array('id' => 'build_plan', 'class' =>'form-control')); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="construction_end" class="col-sm-2 control-label padding-lr5">Civil & Structural Engineer</label>
            <div class="col-sm-5 padding-lr5">
                <?php echo $form->activeTextField($infomodel, 'structural_engineer', array('id' => 'structural_engineer', 'class' =>'form-control')); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="construction_end" class="col-sm-2 control-label padding-lr5">Mechanical & Electrical Engineer</label>
            <div class="col-sm-5 padding-lr5">
                <?php echo $form->activeTextField($infomodel, 'me_engineer', array('id' => 'me_engineer', 'class' =>'form-control')); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="construction_end" class="col-sm-2 control-label padding-lr5">Quantity Surveyor</label>
            <div class="col-sm-5 padding-lr5">
                <?php echo $form->activeTextField($infomodel, 'quantity_surveyor', array('id' => 'quantity_surveyor', 'class' =>'form-control')); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="construction_end" class="col-sm-2 control-label padding-lr5">Main Contractor</label>
            <div class="col-sm-5 padding-lr5">
                <?php echo $form->activeTextField($infomodel, 'main_contractor', array('id' => 'main_contractor', 'class' =>'form-control')); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="construction_end" class="col-sm-2 control-label padding-lr5">Commencement Date</label>
            <div class="col-sm-5 padding-lr5">
                <?php echo $form->activeTextField($infomodel, 'commence_date', array('id' => 'commence_date', 'class' =>'form-control')); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="construction_end" class="col-sm-2 control-label padding-lr5">Expected Date of Completion</label>
            <div class="col-sm-5 padding-lr5">
                <?php echo $form->activeTextField($infomodel, 'expected_date_completion', array('id' => 'expected_date_completion', 'class' =>'form-control')); ?>
            </div>
        </div>
    </div>
</div>


<?php if($_mode_ == 'insert'){ ?>
    <div class="row">
        <div class="form-group">
            <label for="independent_content" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('independent'); ?></label>
            <div class="col-sm-5 padding-lr5" style="padding-top: 6px">
                <input type="radio" name="ind_radio" id="ind_no" checked="checked" /><?php echo $model->getAttributeLabel('independent_no'); ?>
                <input type="radio" name="ind_radio" id="ind_yes" ><?php echo $model->getAttributeLabel('independent_yes'); ?>
            </div>
        </div>
    </div>

<?php }else if($_mode_ == 'edit'){ ?>
    <div class="row">
        <div class="form-group">
            <label for="independent_content" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('independent'); ?></label>
            <div class="col-sm-5 padding-lr5" style="padding-top: 6px">
                <?php if($model->independent == '0'){ ?>
                    <input type="radio" id="ind_no" name="ind_radio" checked="checked"  />
                    <?php echo $model->getAttributeLabel('independent_no'); ?>
                    <input type="radio" id="ind_yes" name="ind_radio">
                    <?php echo $model->getAttributeLabel('independent_yes'); ?>
                <?php }else if($model->independent == '1'){ ?>
                    <input type="radio" id="ind_no" name="ind_radio"  />
                    <?php echo $model->getAttributeLabel('independent_no'); ?>
                    <input type="radio" id="ind_yes" checked="checked" name="ind_radio">
                    <?php echo $model->getAttributeLabel('independent_yes'); ?>
                <?php }else{  ?>
                    <input type="radio" name="ind_radio" id="ind_no" checked="checked" /><?php echo $model->getAttributeLabel('independent_no'); ?>
                    <input type="radio" name="ind_radio" id="ind_yes" ><?php echo $model->getAttributeLabel('independent_yes'); ?>
                <?php }  ?>
            </div>
        </div>
    </div>

<?php } ?>
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
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAhcLgIHv_6VXLS9Kyt4GTTPlsZF_srA4o&libraries=places&callback=initMap"></script>
<!--<script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=lS14hFRnEXU07GKiVDqNL4is&s=1"></script>-->
<script type="text/javascript">
    $(document).ready(function(){
        var latlng = new google.maps.LatLng(1.3056160000,103.8234040000);
        var myOptions =
            {
                zoom: 14,
                center: latlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
        //不用Var声明，作为全局变量使用
        map = new google.maps.Map(document.getElementById("google_map"), myOptions);

        //监听鼠标移动、点击的动作，并调用事件函数
//        google.maps.event.addListener(map, 'mousemove', function (event) { getCoordinate(event.latLng); });
        google.maps.event.addListener(map, 'click', function (event) { getPoint(event.latLng); });

//    //鼠标移动时获取的经纬度
//    function getCoordinate(location)
//    {
//        document.getElementById("point_x").value = location;
//    }

        //鼠标点击获取指定点的经纬度
        function getPoint(point)
        {
//        alert(point.lat());
            var lat = point.lat();
            var lng =point.lng();
            document.getElementById("program_address").value = lat+','+lng;
        }

        //定位到指定坐标的位置，并将该点设为地图中心
        function getLocation()
        {
            var pointX = document.getElementById("show_x").value;
            var pointY = document.getElementById("show_y").value;
            var targLatLng = new google.maps.LatLng(pointX, pointY);
            map.setZoom(14);
            map.setCenter(targLatLng);
        }
    });
//    $(document).ready(function(){
//        window.HOST_TYPE='2';
//        var map = new BMap.Map("google_map");
//        var data = document.getElementById("program_address").value;
//        if(data != ''){
//            var arr=data.split(',');
//            lng = arr[0];
//            lat = arr[1];
//            var point = new BMap.Point(lng,lat);
//            var marker = new BMap.Marker(point);  // 创建标注
//            map.addOverlay(marker);              // 将标注添加到地图中
//        }else{
//            lng = 103.865936;
//            lat = 1.358053;
//        }
//        var point = new BMap.Point(lng,lat);
//        map.centerAndZoom(point, 15);
//        function showInfo(e){
//            var point_str = e.point.lng + ", " + e.point.lat;
//            document.getElementById("program_address").value = point_str;
//        }
//        map.addEventListener("click", showInfo);
//
//
//    //定位到指定坐标的位置，并将该点设为地图中心
//    function getLocation()
//    {
//        var pointX = document.getElementById("show_x").value;
//        var pointY = document.getElementById("show_y").value;
//        var targLatLng = new google.maps.LatLng(pointX, pointY);
//        map.setZoom(14);
//        map.setCenter(targLatLng);
//    }
//    });

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
        $("#form1").submit();
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
