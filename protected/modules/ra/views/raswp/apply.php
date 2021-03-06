<style type="text/css">
    input.task_attach{
        position:absolute;
        right:0px;
        top:0px;
        opacity:0;
        filter:alpha(opacity=0);
        cursor:pointer;
        width:276px;
        height:36px;
        overflow: hidden;
    }
    .rows_wrap {
        position: relative;
    }
    .rows_wrap .rows_title {
        padding-left: 6px;
    }
    .rows_wrap .rows_content {
        float: right;
        padding-bottom: 30px;
        position: relative;
        width: 841px;
        padding-left: 5px;
        left:-230px;
    }
    ul, li {
        list-style: outside none none;
    }
    .clearfix::before, .clearfix::after {
        content: "";
        display: table;
    }
    .clearfix::after {
        clear: both;
        overflow: hidden;
    }
    .clearfix {
    }
    .img_list {
        margin-left: 0;
        width: 544px;
    }
    .img_list .img_box {
        float: left;
        height: 100px;
        margin-bottom: 6px;
        margin-right: 6px;
        position: relative;
        text-align: center;
        width: 130px;
    }
    .img_list .img_box img {
        cursor: move;
        height: 100px;
        width: 130px;
    }
    .img_list .img_box .toolbar {
        bottom: 0;
        height: 20px;
        left: 0;
        position: absolute;
        right: 0;
        width: 100%;
        z-index: 109;
    }
    .img_list .img_box .toolbar_wrap {
        display: none;
    }
    .img_list .img_box .opacity {
        background-color: #000;
        bottom: 0;
        height: 20px;
        left: 0;
        opacity: 0.3;
        position: absolute;
        right: 0;
        width: 100%;
        z-index: 108;
    }
    .img_list .img_box .toolbar a {
        background-image: url("http://img.58cdn.com.cn/ui7/post/pc/imgs/icons_edit.png");
        outline: 0 none;
        position: absolute;
        text-decoration: none;
    }
    .img_list .img_box .toolbar .delete {
        background-image: url("http://img.58cdn.com.cn/ui7/post/pc/imgs/icons_edit.png");
        background-position: -180px -92px;
        height: 20px;
        right: 0;
        top: 0;
        width: 20px;
    }
</style>
<?php

$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'focus' => array($rs, 'old_pwd'),
    //'autoValidation' => false,
    "action" => "javascript:formSubmit()",
    //'enableAjaxSubmit' => false,
    //'ajaxUpdateId' => 'content-body',
    //'role' => 'form', //?????????
    //'formClass' => 'form-horizontal', //????????? ??????????????????
    //'autoValidation' => false,

));
$upload_url = Yii::app()->params['upload_url'];//????????????
?>
<div class="row">
    <p style="font-size:16px;"><?php echo Yii::t('common','upload_documnet_prompt') ?></p>
</div>
<div class="box-body">
    <div id='msgbox' class='alert alert-dismissable ' style="display:none;">
        <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>??</button>
        <strong id='msginfo'></strong><span id='divMain'></span>
    </div>
    <div >
        <input type="hidden" id="upload_url" name="File[ra_src]" />
        <input type="hidden" id="url" value="<?php echo "$upload_url" ?>"/>
        <input type="hidden" id="tag" name="ra[tag]" />
        <input type="hidden" id="ra_swp_id"  name="ra[ra_swp_id]"  value="<?php echo $ra_swp_id ?>"/>
        <input type="hidden" id="ra_index" value="<?php if($ra_swp_id == ''){
            echo 1;
        }else{
            $ra_file_url = $model->ra_path;
            $ra_arg = explode("|",$ra_file_url);
            $count = count($ra_arg)+1;
            echo $count;
        }?>">
        <input type="hidden" id="swp_index" value="<?php if($ra_swp_id == ''){
            echo 1;
        }else{
            $swp_file_url = $model->swp_path;
            $swp_arg = explode("|",$swp_file_url);
            $count = count($swp_arg)+1;
            echo $count;
        }?>">
        <input type="hidden" id="lp_index" value="<?php if($ra_swp_id == ''){
            echo 1;
        }else{
            $lp_file_url = $model->lp_path;
            $lp_arg = explode("|",$lp_file_url);
            $count = count($lp_arg)+1;
            echo $count;
        }?>">
        <input type="hidden" id="fp_index" value="<?php if($ra_swp_id == ''){
            echo 1;
        }else{
            $fp_file_url = $model->fp_path;
            $fp_arg = explode("|",$fp_file_url);
            $count = count($fp_arg)+1;
            echo $count;
        }?>">
        <input type="hidden" id="ms_index" value="<?php if($ra_swp_id == ''){
            echo 1;
        }else{
            $ms_file_url = $model->ms_path;
            $ms_arg = explode("|",$ms_file_url);
            $count = count($ms_arg)+1;
            echo $count;
        }?>">
        <input type="hidden" id="other_index" value="<?php if($ra_swp_id == ''){
            echo 1;
        }else{
            $other_file_url = $model->other_path;
            $other_arg = explode("|",$other_file_url);
            $count = count($other_arg)+1;
            echo $count;
        }?>">
    </div>

    <div class="row">
        <div class="form-group">
            <label for="title" class="col-sm-2 control-label padding-lr5"><?php echo Yii::t('comp_ra', 'title'); ?></label>
            <div class="col-sm-3 padding-lr5">
                <?php echo $form->activeTextField($model, 'title', array('id' => 'title', 'class' => 'form-control', 'check-type' => '', 'required-message' => '')); ?>
            </div>
        </div>

        <div class="form-group">
            <label for="worker_type" class="col-sm-2 control-label padding-lr5"><?php echo Yii::t('comp_ra', 'program'); ?></label>
            <div class="col-sm-3 padding-lr5">
                <?php if($ra_swp_id != ''){  ?>
                <select disabled="disabled" class="form-control input-sm" id="program_id" name="ra[programid]" style="width: 100%;">
                    <?php }else{  ?>
                    <select class="form-control input-sm" id="program_id" name="ra[programid]" style="width: 100%;" onchange="gradeChange()">
                        <?php }  ?>
                        <?php
                        foreach ($program_list as $id => $name) {
                            if($id == $program_id){
                                echo "<option value='{$id}' selected='selected'>{$name}</option>";
                            }
                            //else{
                                //echo "<option value='{$id}'>{$name}</option>";
                            //}
                        }
                        ?>
                    </select>
            </div>
        </div>
    </div>
    <!--<div class="row">-->
    <!--</div>-->
    <div class="row">
        <div class="form-group">
            <label for="worker_type" class="col-sm-2 control-label padding-lr5"><?php echo Yii::t('comp_ra', 'Worker type'); ?></label>
            <div id="add" class="col-sm-3 padding-lr5">
                <select class="form-control input-sm" id="program_id" name="ra[worker_type]" style="width: 100%;" >
                    <!--<option value=""></option>-->
                    <?php
                    foreach ($worker_type as $type_id => $type_name) {
                        if($type_id == $ra_list['work_type']){
                            echo "<option value='{$type_id}' selected='selected'>{$type_name}</option>";
                        }else{
                            echo "<option value='{$type_id}'>{$type_name}</option>";
                        }
                    }
                    ?>
                </select>
                <!--<a onclick='add()'>--><?php //echo Yii::t('comp_ra','input_workertype') ?><!--</a>-->
            </div>
        </div>
    </div>
    <div  id="ra_1" class="row">
        <div class="form-group">
            <label for="worker_type" class="col-sm-2 control-label padding-lr5"><?php echo Yii::t('comp_ra', 'ra_approver'); ?></label>
            <div id="ra_approver" class="col-sm-9 padding-lr5">
                <?php
                    if($ra_swp_id != '') {
                        $contractor_id = Yii::app()->user->getState('contractor_id');
                        $user_list = ProgramUser::UserListByRa($ra_list['program_id'], $contractor_id);
                        if (count($user_list[1]) > 0) {
                            foreach ($user_list[1] as $cnt => $list) {
                                if ($ra_user_list[$list['user_id']]['worker_name'] != '') {
                                    echo "<input style='margin-top: 10px' type='checkbox'  checked='checked'  value='{$list['user_id']}' name='ra_approver[]'/>&nbsp;{$list['user_name']}&nbsp;&nbsp;";
                                } else {
                                    echo "<input style='margin-top: 10px' type='checkbox' value='{$list['user_id']}' name='ra_approver[]'/>&nbsp;{$list['user_name']}&nbsp;&nbsp;";
                                }
                            }
                        }
                    }
                ?>
            </div>
        </div>
    </div>
    <div  id="ra_2" class="row">
        <div class="form-group">
            <label for="worker_type" class="col-sm-2 control-label padding-lr5"><?php echo Yii::t('comp_ra', 'ra_leader'); ?></label>
            <div id="ra_leader" class="col-sm-9 padding-lr5">
                <?php
                if($ra_swp_id != '') {
                    $contractor_id = Yii::app()->user->getState('contractor_id');
                    $user_list = ProgramUser::UserListByRa($ra_list['program_id'], $contractor_id);
                    if (count($user_list[2]) > 0) {
                        foreach ($user_list[2] as $cnt => $list) {
                            if ($ra_user_list[$list['user_id']]['worker_name'] != '') {
                                echo "<input style='margin-top: 10px' type='checkbox'  checked='checked'  value='{$list['user_id']}' name='ra_leader[]'/>&nbsp;{$list['user_name']}&nbsp;&nbsp;";
                            } else {
                                echo "<input style='margin-top: 10px' type='checkbox' value='{$list['user_id']}' name='ra_leader[]'/>&nbsp;{$list['user_name']}&nbsp;&nbsp;";
                            }
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <div  id="ra_3" class="row">
        <div class="form-group">
            <label for="worker_type" class="col-sm-2 control-label padding-lr5"><?php echo Yii::t('comp_ra', 'ra_user'); ?></label>
            <div id="ra_user" class="col-sm-9 padding-lr5">
                <?php
                if($ra_swp_id != '') {
                    $contractor_id = Yii::app()->user->getState('contractor_id');
                    $user_list = ProgramUser::UserListByRa($ra_list['program_id'], $contractor_id);
                    if (count($user_list[3]) > 0) {
                        foreach ($user_list[3] as $cnt => $list) {
                            if ($ra_user_list[$list['user_id']]['worker_name'] != '') {
                                echo "<input style='margin-top: 10px' type='checkbox'  checked='checked'  value='{$list['user_id']}' name='ra_user[]'/>&nbsp;{$list['user_name']}&nbsp;&nbsp;";
                            } else {
                                echo "<input style='margin-top: 10px' type='checkbox' value='{$list['user_id']}' name='ra_user[]'/>&nbsp;{$list['user_name']}&nbsp;&nbsp;";
                            }
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="valid_time" class="col-sm-2 control-label padding-lr5"><?php echo Yii::t('comp_ra', 'by_time'); ?></label>
                <div class="input-group col-sm-3">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <?php echo $form->activeTextField($model, 'valid_time', array('id' => 'valid_time', 'class' =>'form-control b_date_ins','onclick' => "WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})", 'check-type' => '')); ?>
                </div>
        </div>
    </div>
    <div class="row"><!--  RA????????????  -->
        <div class="form-group">
            <label for="file" class="col-sm-2 control-label padding-lr5" ><?php echo Yii::t('comp_ra', 'ra_upload'); ?></label>
                <div class="col-sm-5 padding-lr5">
                    <?php echo $form->activeFileField($model, 'ra_path', array('id' => 'ra_path', 'class' => 'form-control', "check-type" => "", 'style' => 'display:none', 'onchange' => "raupload(this)")); ?>
                        <div class="input-group col-md-6 padding-lr5">
                            <input id="raurl" class="form-control" type="text" disabled>
                                <span class="input-group-btn">
                                    <a class="btn btn-warning" onclick="$('input[id=ra_path]').click();">
                                <i class="fa fa-folder-open-o"></i> <?php echo Yii::t('common','button_browse'); ?>
                            </a>
                        </span>
                </div>
            </div>
        </div>
    </div>
    <?php if($ra_swp_id == ''){    ?>
        <div class="row">
            <div class="rows_wrap clearfix" style="width:1200px">
                <div class="rows_content">
                    <ul id="ul_ra" class="clearfix  img_list">

                    </ul>
                </div>
        
            </div>
        </div>
    <?php } ?>
    <?php if($ra_swp_id != ''){    ?>

        <div class="row">
            <div class="rows_wrap clearfix" style="width:1200px">
                <div class="rows_content">
                    <ul id="ul_ra" class="clearfix  img_list">
                        <?php
                        $ra_file_url = $model->ra_path;
                        $ra_arg = explode("|",$ra_file_url);
                        if($ra_arg != ''){
                            foreach($ra_arg as $cnt => $src){
                                $cnt++;
                                echo '<li id="RA_'.$cnt.'" class="img_box">
                                        <img  src="img/pdf.png" >
                                            <div  class="toolbar_wrap">
                                                <div class="opacity"><input type="hidden" name="RaFile[]" value="'.$src.'" ></div>
                                                    <div class="toolbar">
                                                        <a class="delete" href="javascript:void(0);" onclick="raremove('.$cnt.')"></a>
                                                    </div>
                                            </div>
                                </li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
                <div class="rows_title">
                    <label for="face_img"
                           class=" control-label padding-lr5 img_class" style="width:11.2%">RA</label>
                </div>
            </div>
        </div>
    <?php } ?>
    <!--SWP???????????? -->
    <div class="row">
        <div class="form-group">
            <label for="file" class="col-sm-2 control-label padding-lr5" ><?php echo Yii::t('comp_ra', 'swp_upload'); ?></label>
            <div class="col-sm-5 padding-lr5">
                <?php echo $form->activeFileField($model, 'swp_path', array('id' => 'swp_path', 'class' => 'form-control', "check-type" => "", 'style' => 'display:none', 'onchange' => "swpupload(this)")); ?>
                <div class="input-group col-md-6 padding-lr5">
                    <input id="swpurl" class="form-control" type="text" disabled>
                    <span class="input-group-btn">
                        <a class="btn btn-warning" onclick="$('input[id=swp_path]').click();">
                            <i class="fa fa-folder-open-o"></i> <?php echo Yii::t('common','button_browse'); ?>
                        </a>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <?php if($ra_swp_id == ''){    ?>
        <div class="row">
            <div class="rows_wrap clearfix" style="width:1200px">
                <div class="rows_content">
                    <ul id="ul_swp" class="clearfix  img_list">

                    </ul>
                </div>
        
            </div>
        </div>
    <?php } ?>
    <?php if($ra_swp_id != ''){    ?>

        <div class="row">
            <div class="rows_wrap clearfix" style="width:1200px">
                <div class="rows_content">
                    <ul id="ul_swp" class="clearfix  img_list">
                        <?php
                        $swp_file_url = $model->swp_path;
                        $ra_arg = explode("|",$swp_file_url);
                        if($ra_arg != ''){
                            foreach($ra_arg as $cnt => $src){
                                $cnt++;
                                echo '<li id="SWP_'.$cnt.'" class="img_box">
                                        <img  src="img/pdf.png" >
                                        <div  class="toolbar_wrap">
                                            <div class="opacity"><input type="hidden" name="SwpFile[]" value="'.$src.'" >
                                            </div>
                                            <div class="toolbar">
                                                <a class="delete" href="javascript:void(0);" onclick="swpremove('.$cnt.')"></a>
                                            </div>
                                        </div>
                                </li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
                <div class="rows_title">
                    <label for="face_img"
                           class=" control-label padding-lr5 img_class" style="width:11.2%">SWP</label>
                </div>
            </div>
        </div>
    <?php } ?>
    <!-- 2021-09-07 ?????? -->
    <!--lp???????????? -->
    <div class="row">
        <div class="form-group">
            <label for="file" class="col-sm-2 control-label padding-lr5" ><?php echo Yii::t('comp_ra', 'lifting_plan_upload'); ?></label>
            <div class="col-sm-5 padding-lr5">
                <?php echo $form->activeFileField($model, 'lp_path', array('id' => 'lp_path', 'class' => 'form-control', "check-type" => "", 'style' => 'display:none', 'onchange' => "lpupload(this)")); ?>
                <div class="input-group col-md-6 padding-lr5">
                    <input id="lpurl" class="form-control" type="text" disabled>
                    <span class="input-group-btn">
                        <a class="btn btn-warning" onclick="$('input[id=lp_path]').click();">
                            <i class="fa fa-folder-open-o"></i> <?php echo Yii::t('common','button_browse'); ?>
                        </a>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <?php if($ra_swp_id == ''){    ?>
        <div class="row">
            <div class="rows_wrap clearfix" style="width:1200px">
                <div class="rows_content">
                    <ul id="ul_lp" class="clearfix  img_list">

                    </ul>
                </div>
        
            </div>
        </div>
    <?php } ?>
    <?php if($ra_swp_id != ''){    ?>

        <div class="row">
            <div class="rows_wrap clearfix" style="width:1200px">
                <div class="rows_content">
                    <ul id="ul_lp" class="clearfix  img_list">
                        <?php
                        $lp_file_url = $model->lp_path;
                        $ra_arg = explode("|",$lp_file_url);
                        if($ra_arg != ''){
                            foreach($ra_arg as $cnt => $src){
                                $cnt++;
                                echo '<li id="LP_'.$cnt.'" class="img_box">
                                        <img  src="img/pdf.png" >
                                        <div  class="toolbar_wrap">
                                            <div class="opacity"><input type="hidden" name="LpFile[]" value="'.$src.'" >
                                            </div>
                                            <div class="toolbar">
                                                <a class="delete" href="javascript:void(0);" onclick="lpremove('.$cnt.')"></a>
                                            </div>
                                        </div>
                                </li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
                <div class="rows_title">
                    <label for="face_img"
                           class=" control-label padding-lr5 img_class" style="width:11.2%">LP</label>
                </div>
            </div>
        </div>
    <?php } ?>
    <!-- 2021-09-06 ?????? -->
    <!--fP???????????? -->
    <div class="row">
        <div class="form-group">
            <label for="file" class="col-sm-2 control-label padding-lr5" ><?php echo Yii::t('comp_ra', 'fp_plan_upload'); ?></label>
            <div class="col-sm-5 padding-lr5">
                <?php echo $form->activeFileField($model, 'fp_path', array('id' => 'fp_path', 'class' => 'form-control', "check-type" => "", 'style' => 'display:none', 'onchange' => "fpupload(this)")); ?>
                <div class="input-group col-md-6 padding-lr5">
                    <input id="fpurl" class="form-control" type="text" disabled>
                    <span class="input-group-btn">
                        <a class="btn btn-warning" onclick="$('input[id=fp_path]').click();">
                            <i class="fa fa-folder-open-o"></i> <?php echo Yii::t('common','button_browse'); ?>
                        </a>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <?php if($ra_swp_id == ''){    ?>
        <div class="row">
            <div class="rows_wrap clearfix" style="width:1200px">
                <div class="rows_content">
                    <ul id="ul_fp" class="clearfix  img_list">

                    </ul>
                </div>
        
            </div>
        </div>
    <?php } ?>
    <?php if($ra_swp_id != ''){    ?>

        <div class="row">
            <div class="rows_wrap clearfix" style="width:1200px">
                <div class="rows_content">
                    <ul id="ul_fp" class="clearfix  img_list">
                        <?php
                        $fp_file_url = $model->fp_path;
                        $ra_arg = explode("|",$fp_file_url);
                        if($ra_arg != ''){
                            foreach($ra_arg as $cnt => $src){
                                $cnt++;
                                echo '<li id="FP_'.$cnt.'" class="img_box">
                                        <img  src="img/pdf.png" >
                                        <div  class="toolbar_wrap">
                                            <div class="opacity"><input type="hidden" name="FpFile[]" value="'.$src.'" >
                                            </div>
                                            <div class="toolbar">
                                                <a class="delete" href="javascript:void(0);" onclick="fpremove('.$cnt.')"></a>
                                            </div>
                                        </div>
                                </li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
                <div class="rows_title">
                    <label for="face_img"
                           class=" control-label padding-lr5 img_class" style="width:11.2%">FP</label>
                </div>
            </div>
        </div>
    <?php } ?>
    <!-- 2021-09-06 ?????? -->
    <!--ms???????????? -->
    <div class="row">
        <div class="form-group">
            <label for="file" class="col-sm-2 control-label padding-lr5" ><?php echo Yii::t('comp_ra', 'ms_upload'); ?></label>
            <div class="col-sm-5 padding-lr5">
                <?php echo $form->activeFileField($model, 'ms_path', array('id' => 'ms_path', 'class' => 'form-control', "check-type" => "", 'style' => 'display:none', 'onchange' => "msupload(this)")); ?>
                <div class="input-group col-md-6 padding-lr5">
                    <input id="msurl" class="form-control" type="text" disabled>
                    <span class="input-group-btn">
                        <a class="btn btn-warning" onclick="$('input[id=ms_path]').click();">
                            <i class="fa fa-folder-open-o"></i> <?php echo Yii::t('common','button_browse'); ?>
                        </a>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <?php if($ra_swp_id == ''){    ?>
        <div class="row">
            <div class="rows_wrap clearfix" style="width:1200px">
                <div class="rows_content">
                    <ul id="ul_ms" class="clearfix  img_list">

                    </ul>
                </div>
        
            </div>
        </div>
    <?php } ?>
    <?php if($ra_swp_id != ''){    ?>

        <div class="row">
            <div class="rows_wrap clearfix" style="width:1200px">
                <div class="rows_content">
                    <ul id="ul_ms" class="clearfix  img_list">
                        <?php
                        $ms_file_url = $model->ms_path;
                        $ra_arg = explode("|",$ms_file_url);
                        if($ra_arg != ''){
                            foreach($ra_arg as $cnt => $src){
                                $cnt++;
                                echo '<li id="MS_'.$cnt.'" class="img_box">
                                        <img  src="img/pdf.png" >
                                        <div  class="toolbar_wrap">
                                            <div class="opacity"><input type="hidden" name="MsFile[]" value="'.$src.'" >
                                            </div>
                                            <div class="toolbar">
                                                <a class="delete" href="javascript:void(0);" onclick="msremove('.$cnt.')"></a>
                                            </div>
                                        </div>
                                </li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
                <div class="rows_title">
                    <label for="face_img"
                           class=" control-label padding-lr5 img_class" style="width:11.2%">MS</label>
                </div>
            </div>
        </div>
    <?php } ?>
    <!--other???????????? -->
    <div class="row">
        <div class="form-group">
            <label for="file" class="col-sm-2 control-label padding-lr5" ><?php echo Yii::t('comp_ra', 'other_upload'); ?></label>
            <div class="col-sm-5 padding-lr5">
                <?php echo $form->activeFileField($model, 'other_path', array('id' => 'other_path', 'class' => 'form-control', "check-type" => "", 'style' => 'display:none', 'onchange' => "otherupload(this)")); ?>
                <div class="input-group col-md-6 padding-lr5">
                    <input id="otherurl" class="form-control" type="text" disabled>
                    <span class="input-group-btn">
                        <a class="btn btn-warning" onclick="$('input[id=other_path]').click();">
                            <i class="fa fa-folder-open-o"></i> <?php echo Yii::t('common','button_browse'); ?>
                        </a>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <?php if($ra_swp_id == ''){    ?>
        <div class="row">
            <div class="rows_wrap clearfix" style="width:1200px">
                <div class="rows_content">
                    <ul id="ul_other" class="clearfix  img_list">

                    </ul>
                </div>
        
            </div>
        </div>
    <?php } ?>
    <?php if($ra_swp_id != ''){    ?>

        <div class="row">
            <div class="rows_wrap clearfix" style="width:1200px">
                <div class="rows_content">
                    <ul id="ul_other" class="clearfix  img_list">
                        <?php
                        $other_file_url = $model->other_path;
                        $ra_arg = explode("|",$other_file_url);
                        if($ra_arg != ''){
                            foreach($ra_arg as $cnt => $src){
                                $cnt++;
                                echo '<li id="OTHER_'.$cnt.'" class="img_box">
                                        <img  src="img/pdf.png" >
                                        <div  class="toolbar_wrap">
                                            <div class="opacity"><input type="hidden" name="OtherFile[]" value="'.$src.'" >
                                            </div>
                                            <div class="toolbar">
                                                <a class="delete" href="javascript:void(0);" onclick="otherremove('.$cnt.')"></a>
                                            </div>
                                        </div>
                                </li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
                <div class="rows_title">
                    <label for="face_img"
                           class=" control-label padding-lr5 img_class" style="width:11.2%">OTHER</label>
                </div>
            </div>
        </div>
    <?php } ?>
    <!-- 2021-09-07 ?????? -->

    <div class="row">
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-12">
                  <button type="button" id="sbtn" class="btn btn-primary btn-lg" onclick="btnsubmit();"><?php echo Yii::t('common', 'button_post'); ?></button>
                  <button type="button" class="btn btn-default btn-lg" style="margin-left: 10px" onclick="back('<?php echo $program_id; ?>');"><?php echo Yii::t('common', 'button_back'); ?></button>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>

<script type="text/javascript" src="js/ajaxfileupload.js"></script>
<script type="text/javascript" src="js/loading_old.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("li").mouseover(function()
        {
            $(this).children("div").removeClass("toolbar_wrap");
        });
        $("li").mouseout(function()
        {
            $(this).children("div").addClass("toolbar_wrap");
        });
        gradeChange();
    });
    //?????????????????????
    var add = function () {
        $('#add').append("<input id='type_add' class='form-control' check-type='' required-message='' name='ra[type_add]' type='text'>");
        $('a').removeAttr('onclick');
    }
    //??????
    var back = function (id) {
        // window.location = "./?<?php //echo Yii::app()->session['list_url']['ra/list']; ?>//";
        window.location = "index.php?r=ra/raswp/list&program_id="+id;
    }
    //???????????????????????????
    var gradeChange =function () {
        var objS = document.getElementById("program_id");
        var id = objS.options[objS.selectedIndex].value;
        $.ajax({
            data: {id: id},
            url: "index.php?r=ra/raswp/rauser",
            dataType: "json",
            type: "POST",
            success: function (data) {
                $("#ra_user").empty();
                $("#ra_approver").empty();
                $("#ra_leader").empty();
                var a =0;
                for (var o in data) {
                    if(o == '1'){
                        for(var i in data[o]) {
                            $('#ra_approver').append("<input style='margin-top: 10px; '  type='checkbox' value='"+data[o][i].user_id+"' name='ra_approver[]'/>&nbsp;"+data[o][i].user_name+"&nbsp;&nbsp;");
                        }
                    }else if(o == '2'){
                        for(var i in data[o]) {
                            $('#ra_leader').append("<input style='margin-top: 10px; '  type='checkbox' value='"+data[o][i].user_id+"' name='ra_leader[]'/>&nbsp;"+data[o][i].user_name+"&nbsp;&nbsp;");
                        }
                    }else if(o == '3'){
                        for(var i in data[o]) {
                            $('#ra_user').append("<input style='margin-top: 10px; '  type='checkbox' value='"+data[o][i].user_id+"' name='ra_user[]'/>&nbsp;"+data[o][i].user_name+"&nbsp;&nbsp;");
                        }
                    }
                }
            }
        });
    }
    //?????????????????????
    function RndNum(n){
        var rnd="";
        for(var i=0;i<n;i++)
            rnd+=Math.floor(Math.random()*10);
        return rnd;
    }
    var raupload = function(file){
        ra_tag = $('#ra_path')[0].files[0].name.lastIndexOf(".");
        ra_name = $('#ra_path')[0].files[0].name.substr(0,ra_tag);
        document.getElementById('raurl').value=file.value;
        containSpecial = new RegExp(/[(\~)(\%)(\^)(\&)(\*)(\()(\))(\[)(\])(\{)(\})(\|)(\\)(\;)(\:)(\')(\")(\,)(\.)(\/)(\?)(\)]+/);
        status = containSpecial.test(ra_name);
        if(status == 'true'){
                alert('File name contains special characters, please check before uploading');
                return false;
        }

        if (!/\.(pdf)$/.test(file.value)) {
            alert('<?php echo Yii::t('comp_ra','Error Upload');?>');
            return false;
        }
        var upload_url = $("#url").val();
        var form = document.forms[0];
        var formData = new FormData();   //????????????form????????????????????????????????????,?????????????????????????????????????????????FormData????????????????????????
        var oDate = new Date(); //???????????????????????????
        var min = oDate.getMinutes(); //???
        var sec = oDate.getSeconds(); //???
        var num = RndNum(3);
        formData.append("file1", $('#ra_path')[0].files[0]);
        $.ajax({
            url: upload_url,
            type: "POST",
            data: formData,
            dataType: "json",
            processData: false,         // ??????jQuery??????????????????????????????
            contentType: false,        // ??????jQuery???????????????Content-Type?????????
            beforeSend: function () {
                var length= $("#ra_index").val();
                var html = '<li id="RA_'+length+'" class="img_box"></li>';
                $("#ul_ra").append(html);
                var ra_variable = 'RA_'+length;
                addcloud(ra_variable);
            },
            success: function (data) {
                $.each(data, function (name, value) {
                    if (name == 'data') {
                        moveRaFile(value.file1);
                    }
                });
            }
        });
    }
    var swpupload = function(file){
        swp_tag = $('#swp_path')[0].files[0].name.lastIndexOf(".");
        swp_name = $('#swp_path')[0].files[0].name.substr(0,swp_tag);
        document.getElementById('swpurl').value=file.value;
        containSpecial = new RegExp(/[(\~)(\%)(\^)(\&)(\*)(\()(\))(\[)(\])(\{)(\})(\|)(\\)(\;)(\:)(\')(\")(\,)(\.)(\/)(\?)(\)]+/);
        status = containSpecial.test(swp_name);
        if(status == 'true'){
            alert('File name contains special characters, please check before uploading');
            return false;
        }

        if (!/\.(pdf)$/.test(file.value)) {
            alert('<?php echo Yii::t('comp_ra','Error Upload');?>');
            return false;
        }
        var upload_url = $("#url").val();
        var form = document.forms[0];
        var formData = new FormData();   //????????????form????????????????????????????????????,?????????????????????????????????????????????FormData????????????????????????
        //alert($('#save_path')[0].files[0]);
        formData.append("file1", $('#swp_path')[0].files[0]);
        //alert(params);
        $.ajax({
            url: upload_url,
            type: "POST",
            data: formData,
            dataType: "json",
            processData: false,         // ??????jQuery??????????????????????????????
            contentType: false,        // ??????jQuery???????????????Content-Type?????????
            beforeSend: function () {
                var length= $("#swp_index").val();
                var html = '<li id="SWP_'+length+'" class="img_box"></li>';
                $("#ul_swp").append(html);
                var swp_variable = 'SWP_'+length;
                addcloud(swp_variable);
            },
            success: function (data) {
                $.each(data, function (name, value) {
                    if (name == 'data') {
                        moveSwpFile(value.file1);
                    }
                });
            }
        });
    }
    //fp_path
    var fpupload = function(file){
        fp_tag = $('#fp_path')[0].files[0].name.lastIndexOf(".");
        fp_name = $('#fp_path')[0].files[0].name.substr(0,fp_tag);
        document.getElementById('fpurl').value=file.value;
        containSpecial = new RegExp(/[(\~)(\%)(\^)(\&)(\*)(\()(\))(\[)(\])(\{)(\})(\|)(\\)(\;)(\:)(\')(\")(\,)(\.)(\/)(\?)(\)]+/);
        status = containSpecial.test(fp_name);
        if(status == 'true'){
            alert('File name contains special characters, please check before uploading');
            return false;
        }

        if (!/\.(pdf)$/.test(file.value)) {
            alert('<?php echo Yii::t('comp_ra','Error Upload');?>');
            return false;
        }
        var upload_url = $("#url").val();
        var form = document.forms[0];
        var formData = new FormData();   //????????????form????????????????????????????????????,?????????????????????????????????????????????FormData????????????????????????
        //alert($('#save_path')[0].files[0]);
        formData.append("file1", $('#fp_path')[0].files[0]);
        //alert(params);
        $.ajax({
            url: upload_url,
            type: "POST",
            data: formData,
            dataType: "json",
            processData: false,         // ??????jQuery??????????????????????????????
            contentType: false,        // ??????jQuery???????????????Content-Type?????????
            beforeSend: function () {
                var length= $("#fp_index").val();
                var html = '<li id="FP_'+length+'" class="img_box"></li>';
                $("#ul_fp").append(html);
                var fp_variable = 'FP_'+length;
                addcloud(fp_variable);
            },
            success: function (data) {
                $.each(data, function (name, value) {
                    if (name == 'data') {
                        moveFpFile(value.file1);
                    }
                });
            }
        });
    }
    //lp_path
    var lpupload = function(file){
        lp_tag = $('#lp_path')[0].files[0].name.lastIndexOf(".");
        lp_name = $('#lp_path')[0].files[0].name.substr(0,lp_tag);
        document.getElementById('lpurl').value=file.value;
        containSpecial = new RegExp(/[(\~)(\%)(\^)(\&)(\*)(\()(\))(\[)(\])(\{)(\})(\|)(\\)(\;)(\:)(\')(\")(\,)(\.)(\/)(\?)(\)]+/);
        status = containSpecial.test(lp_name);
        if(status == 'true'){
            alert('File name contains special characters, please check before uploading');
            return false;
        }

        if (!/\.(pdf)$/.test(file.value)) {
            alert('<?php echo Yii::t('comp_ra','Error Upload');?>');
            return false;
        }
        var upload_url = $("#url").val();
        var form = document.forms[0];
        var formData = new FormData();   //????????????form????????????????????????????????????,?????????????????????????????????????????????FormData????????????????????????
        //alert($('#save_path')[0].files[0]);
        formData.append("file1", $('#lp_path')[0].files[0]);
        //alert(params);
        $.ajax({
            url: upload_url,
            type: "POST",
            data: formData,
            dataType: "json",
            processData: false,         // ??????jQuery??????????????????????????????
            contentType: false,        // ??????jQuery???????????????Content-Type?????????
            beforeSend: function () {
                var length= $("#lp_index").val();
                var html = '<li id="LP_'+length+'" class="img_box"></li>';
                $("#ul_lp").append(html);
                var lp_variable = 'LP_'+length;
                addcloud(lp_variable);
            },
            success: function (data) {
                $.each(data, function (name, value) {
                    if (name == 'data') {
                        moveLpFile(value.file1);
                    }
                });
            }
        });
    }
    //ms_path
    var msupload = function(file){
        ms_tag = $('#ms_path')[0].files[0].name.lastIndexOf(".");
        ms_name = $('#ms_path')[0].files[0].name.substr(0,ms_tag);
        document.getElementById('msurl').value=file.value;
        containSpecial = new RegExp(/[(\~)(\%)(\^)(\&)(\*)(\()(\))(\[)(\])(\{)(\})(\|)(\\)(\;)(\:)(\')(\")(\,)(\.)(\/)(\?)(\)]+/);
        status = containSpecial.test(ms_name);
        if(status == 'true'){
            alert('File name contains special characters, please check before uploading');
            return false;
        }

        if (!/\.(pdf)$/.test(file.value)) {
            alert('<?php echo Yii::t('comp_ra','Error Upload');?>');
            return false;
        }
        var upload_url = $("#url").val();
        var form = document.forms[0];
        var formData = new FormData();   //????????????form????????????????????????????????????,?????????????????????????????????????????????FormData????????????????????????
        //alert($('#save_path')[0].files[0]);
        formData.append("file1", $('#ms_path')[0].files[0]);
        //alert(params);
        $.ajax({
            url: upload_url,
            type: "POST",
            data: formData,
            dataType: "json",
            processData: false,         // ??????jQuery??????????????????????????????
            contentType: false,        // ??????jQuery???????????????Content-Type?????????
            beforeSend: function () {
                var length= $("#ms_index").val();
                var html = '<li id="MS_'+length+'" class="img_box"></li>';
                $("#ul_ms").append(html);
                var ms_variable = 'MS_'+length;
                addcloud(ms_variable);
            },
            success: function (data) {
                $.each(data, function (name, value) {
                    if (name == 'data') {
                        moveMsFile(value.file1);
                    }
                });
            }
        });
    }
    //other_path
    var otherupload = function(file){
        other_tag = $('#other_path')[0].files[0].name.lastIndexOf(".");
        other_name = $('#other_path')[0].files[0].name.substr(0,other_tag);
        document.getElementById('otherurl').value=file.value;
        containSpecial = new RegExp(/[(\~)(\%)(\^)(\&)(\*)(\()(\))(\[)(\])(\{)(\})(\|)(\\)(\;)(\:)(\')(\")(\,)(\.)(\/)(\?)(\)]+/);
        status = containSpecial.test(other_name);
        if(status == 'true'){
            alert('File name contains special characters, please check before uploading');
            return false;
        }

        if (!/\.(pdf)$/.test(file.value)) {
            alert('<?php echo Yii::t('comp_ra','Error Upload');?>');
            return false;
        }
        var upload_url = $("#url").val();
        var form = document.forms[0];
        var formData = new FormData();   //????????????form????????????????????????????????????,?????????????????????????????????????????????FormData????????????????????????
        //alert($('#save_path')[0].files[0]);
        formData.append("file1", $('#other_path')[0].files[0]);
        //alert(paraother);
        $.ajax({
            url: upload_url,
            type: "POST",
            data: formData,
            dataType: "json",
            processData: false,         // ??????jQuery??????????????????????????????
            contentType: false,        // ??????jQuery???????????????Content-Type?????????
            beforeSend: function () {
                var length= $("#other_index").val();
                var html = '<li id="OTHER_'+length+'" class="img_box"></li>';
                $("#ul_other").append(html);
                var other_variable = 'OTHER_'+length;
                addcloud(other_variable);
            },
            success: function (data) {
                $.each(data, function (name, value) {
                    if (name == 'data') {
                        moveOtherFile(value.file1);
                    }
                });
            }
        });
    }
    /**
     * FP????????????????????????fp_path
     */
    function msupload(file_src) {
        var tag = 'fp';
        $.ajax({
            type: "POST",
            url: "index.php?r=ra/raswp/movefile",
            data: {file_src:file_src,tag:tag},
            dataType: "json",
            beforeSend: function () {
            },
            success: function(data){
                var length= $("#fp_index").val();
                var img_str = '<img  src="img/pdf.png"><div class="toolbar_wrap"><div class="opacity"><input type="hidden" name="FpFile[]" value="'+data.src+'" ></div><div class="toolbar"><a class="delete" href="javascript:void(0);" onclick="fpremove('+length+')"></a></div></div>';
                removecloud();//????????????
                $("#FP_"+length).append(img_str);
                length++;
                $("#fp_index").val(length);
                $("li").mouseover(function()
                {
                    $(this).children("div").removeClass("toolbar_wrap");
                });
                $("li").mouseout(function()
                {
                    $(this).children("div").addClass("toolbar_wrap");
                });
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                //alert(XMLHttpRequest.status);
                //alert(XMLHttpRequest.readyState);
                //alert(textStatus);
            },
        });
    }
    

    /**
     * RA????????????????????????
     */
    function moveRaFile(file_src) {
        var tag = 'ra';
        $.ajax({
            type: "POST",
            url: "index.php?r=ra/raswp/movefile",
            data: {file_src:file_src,tag:tag},
            dataType: "json",
            beforeSend: function () {
            },
            success: function(data){
                var length= $("#ra_index").val();
                var img_str = '<img  src="img/pdf.png"><div class="toolbar_wrap"><div class="opacity"><input type="hidden" name="RaFile[]" value="'+data.src+'" ></div><div class="toolbar"><a class="delete" href="javascript:void(0);" onclick="raremove('+length+')"></a></div></div>';
                removecloud();//????????????
                $("#RA_"+length).append(img_str);
                length++;
                $("#ra_index").val(length);
                $("li").mouseover(function()
                {
                    $(this).children("div").removeClass("toolbar_wrap");
                });
                $("li").mouseout(function()
                {
                    $(this).children("div").addClass("toolbar_wrap");
                });
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                //alert(XMLHttpRequest.status);
                //alert(XMLHttpRequest.readyState);
                //alert(textStatus);
            },
        });
    }

    /**
     * SWP????????????????????????
     */
    function moveSwpFile(file_src) {
        var tag = 'swp';
        $.ajax({
            type: "POST",
            url: "index.php?r=ra/raswp/movefile",
            data: {file_src:file_src,tag:tag},
            dataType: "json",
            beforeSend: function () {

            },
            success: function(data){
                var length= $("#swp_index").val();
                var img_str = '<img  src="img/pdf.png"><div class="toolbar_wrap"><div class="opacity"><input type="hidden" name="SwpFile[]" value="'+data.src+'" ></div><div class="toolbar"><a class="delete" href="javascript:void(0);" onclick="swpremove('+length+')"></a></div></div>';
                removecloud();//????????????
                $("#SWP_"+length).append(img_str);
                length++;
                $("#swp_index").val(length);
                $("li").mouseover(function()
                {
                    $(this).children("div").removeClass("toolbar_wrap");
                });
                $("li").mouseout(function()
                {
                    $(this).children("div").addClass("toolbar_wrap");
                });
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                //alert(XMLHttpRequest.status);
                //alert(XMLHttpRequest.readyState);
                //alert(textStatus);
            },
        });
    }
    /**
     * FP????????????????????????
     */
    function moveFpFile(file_src) {
        var tag = 'fp';
        $.ajax({
            type: "POST",
            url: "index.php?r=ra/raswp/movefile",
            data: {file_src:file_src,tag:tag},
            dataType: "json",
            beforeSend: function () {

            },
            success: function(data){
                var length= $("#fp_index").val();
                var img_str = '<img  src="img/pdf.png"><div class="toolbar_wrap"><div class="opacity"><input type="hidden" name="FpFile[]" value="'+data.src+'" ></div><div class="toolbar"><a class="delete" href="javascript:void(0);" onclick="fpremove('+length+')"></a></div></div>';
                removecloud();//????????????
                $("#FP_"+length).append(img_str);
                length++;
                $("#fp_index").val(length);
                $("li").mouseover(function()
                {
                    $(this).children("div").removeClass("toolbar_wrap");
                });
                $("li").mouseout(function()
                {
                    $(this).children("div").addClass("toolbar_wrap");
                });
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                //alert(XMLHttpRequest.status);
                //alert(XMLHttpRequest.readyState);
                //alert(textStatus);
            },
        });
    }
    /**
     * LP????????????????????????
     */
    function moveLpFile(file_src) {
        var tag = 'lp';
        $.ajax({
            type: "POST",
            url: "index.php?r=ra/raswp/movefile",
            data: {file_src:file_src,tag:tag},
            dataType: "json",
            beforeSend: function () {

            },
            success: function(data){
                var length= $("#lp_index").val();
                var img_str = '<img  src="img/pdf.png"><div class="toolbar_wrap"><div class="opacity"><input type="hidden" name="LpFile[]" value="'+data.src+'" ></div><div class="toolbar"><a class="delete" href="javascript:void(0);" onclick="lpremove('+length+')"></a></div></div>';
                removecloud();//????????????
                $("#LP_"+length).append(img_str);
                length++;
                $("#lp_index").val(length);
                $("li").mouseover(function()
                {
                    $(this).children("div").removeClass("toolbar_wrap");
                });
                $("li").mouseout(function()
                {
                    $(this).children("div").addClass("toolbar_wrap");
                });
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                //alert(XMLHttpRequest.status);
                //alert(XMLHttpRequest.readyState);
                //alert(textStatus);
            },
        });
    }
    /**
     * MS????????????????????????
     */
    function moveMsFile(file_src) {
        var tag = 'ms';
        $.ajax({
            type: "POST",
            url: "index.php?r=ra/raswp/movefile",
            data: {file_src:file_src,tag:tag},
            dataType: "json",
            beforeSend: function () {

            },
            success: function(data){
                var length= $("#ms_index").val();
                var img_str = '<img  src="img/pdf.png"><div class="toolbar_wrap"><div class="opacity"><input type="hidden" name="MsFile[]" value="'+data.src+'" ></div><div class="toolbar"><a class="delete" href="javascript:void(0);" onclick="msremove('+length+')"></a></div></div>';
                removecloud();//????????????
                $("#MS_"+length).append(img_str);
                length++;
                $("#ms_index").val(length);
                $("li").mouseover(function()
                {
                    $(this).children("div").removeClass("toolbar_wrap");
                });
                $("li").mouseout(function()
                {
                    $(this).children("div").addClass("toolbar_wrap");
                });
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                //alert(XMLHttpRequest.status);
                //alert(XMLHttpRequest.readyState);
                //alert(textStatus);
            },
        });
    }
    /**
     * OTHER????????????????????????
     */
    function moveOtherFile(file_src) {
        var tag = 'other';
        $.ajax({
            type: "POST",
            url: "index.php?r=ra/raswp/movefile",
            data: {file_src:file_src,tag:tag},
            dataType: "json",
            beforeSend: function () {

            },
            success: function(data){
                var length= $("#other_index").val();
                var img_str = '<img  src="img/pdf.png"><div class="toolbar_wrap"><div class="opacity"><input type="hidden" name="OtherFile[]" value="'+data.src+'" ></div><div class="toolbar"><a class="delete" href="javascript:void(0);" onclick="otherremove('+length+')"></a></div></div>';
                removecloud();//????????????
                $("#OTHER_"+length).append(img_str);
                length++;
                $("#other_index").val(length);
                $("li").mouseover(function()
                {
                    $(this).children("div").removeClass("toolbar_wrap");
                });
                $("li").mouseout(function()
                {
                    $(this).children("div").addClass("toolbar_wrap");
                });
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                //alert(XMLHttpRequest.status);
                //alert(XMLHttpRequest.readyState);
                //alert(textStatus);
            },
        });
    }
    //??????RA??????
    function raremove(n) {
        var src = $("#RA_"+n).find("input").attr('value');
        $.ajax({
            type: "POST",
            url: "index.php?r=ra/raswp/delfile",
            data: {src:src},
            dataType: "json",
            success: function(data){
                $("#RA_"+n).remove();
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                //alert(XMLHttpRequest.status);
                //alert(XMLHttpRequest.readyState);
                //alert(textStatus);
            },
        });
    };
    //??????SWP??????
    function swpremove(n) {
        var src = $("#SWP_"+n).find("input").attr('value');
        $.ajax({
            type: "POST",
            url: "index.php?r=ra/raswp/delfile",
            data: {src:src},
            dataType: "json",
            success: function(data){
                $("#SWP_"+n).remove();
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                //alert(XMLHttpRequest.status);
                //alert(XMLHttpRequest.readyState);
                //alert(textStatus);
            },
        });
    };
    //??????FP??????fp_path
    function fpremove(n) {
        var src = $("#FP_"+n).find("input").attr('value');
        $.ajax({
            type: "POST",
            url: "index.php?r=ra/raswp/delfile",
            data: {src:src},
            dataType: "json",
            success: function(data){
                $("#FP_"+n).remove();
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                //alert(XMLHttpRequest.status);
                //alert(XMLHttpRequest.readyState);
                //alert(textStatus);
            },
        });
    };
    //??????LP??????lp_path
    function lpremove(n) {
        var src = $("#LP_"+n).find("input").attr('value');
        $.ajax({
            type: "POST",
            url: "index.php?r=ra/raswp/delfile",
            data: {src:src},
            dataType: "json",
            success: function(data){
                $("#LP_"+n).remove();
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                //alert(XMLHttpRequest.status);
                //alert(XMLHttpRequest.readyState);
                //alert(textStatus);
            },
        });
    };
    //??????MS??????ms_path
    function msremove(n) {
        var src = $("#MS_"+n).find("input").attr('value');
        $.ajax({
            type: "POST",
            url: "index.php?r=ra/raswp/delfile",
            data: {src:src},
            dataType: "json",
            success: function(data){
                $("#MS_"+n).remove();
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                //alert(XMLHttpRequest.status);
                //alert(XMLHttpRequest.readyState);
                //alert(textStatus);
            },
        });
    };
    //??????Other??????other_path
    function otherremove(n) {
        var src = $("#OTHER_"+n).find("input").attr('value');
        $.ajax({
            type: "POST",
            url: "index.php?r=ra/raswp/delfile",
            data: {src:src},
            dataType: "json",
            success: function(data){
                $("#OTHER_"+n).remove();
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                //alert(XMLHttpRequest.status);
                //alert(XMLHttpRequest.readyState);
                //alert(textStatus);
            },
        });
    };
    //????????????
    var btnsubmit = function () {
        var ra_swp_id = $("#ra_swp_id").val();

        var raurl = $("#raurl").val();
        var swpurl = $("#swpurl").val();
        var fpurl = $("#fpurl").val();
        var lpurl = $("#lpurl").val();
        var msurl = $("#msurl").val();
        var otherurl = $("#otherurl").val();
        //$("#title").val(uploadurl);
        //if(raurl == '' || swpurl == ''){
        //  alert('???????????????');
        //  return false;
        //}
        if($("input:checkbox[name='ra_approver[]']:checked").length <= 0){
            alert('Please select RA Approver');
            return false;
        }
        if(ra_swp_id == '') {
            insertsubmit();
        }else{
            updatesubmit();
        }
    }

    var n = 4;
    //??????????????????
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
    //????????????????????????
    function insertsubmit() {
        if($("#ra").prop("checked")){
            $("#tag").val(0);
        }
        if($("#swp").prop("checked")){
            $("#tag").val(1);
        }
        if($("#fp").prop("checked")){
            $("#tag").val(2);
        }
        if($("#lp").prop("checked")){
            $("#tag").val(3);
        }
        if($("#ms").prop("checked")){
            $("#tag").val(4);
        }
        if($("#other").prop("checked")){
            $("#tag").val(5);
        }
        $.ajax({
            data:$('#form1').serialize(),
            url: "index.php?r=ra/raswp/insert",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                removecloud();
                if(data.status == 1) {
                    $('#msgbox').addClass('alert-success fa-ban');
                    $('#msginfo').html(data.msg);
                    $('#msgbox').show();
                    showTime(data.refresh);
                    back('<?php echo $program_id; ?>');
                }else{
                    $('#msgbox').addClass('alert-danger fa-ban');
                    $('#msginfo').html(data.msg);
                    $('#msgbox').show();
                }
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('????????????');
                $('#msgbox').show();
            }
        });
    }
    //????????????????????????
    function updatesubmit() {
        if($("#ra").prop("checked")){
            $("#tag").val(0);
        }
        if($("#swp").prop("checked")){
            $("#tag").val(1);
        }
        if($("#lp").prop("checked")){
            $("#tag").val(2);
        }
        if($("#fp").prop("checked")){
            $("#tag").val(3);
        }
        if($("#ms").prop("checked")){
            $("#tag").val(4);
        }
        if($("#other").prop("checked")){
            $("#tag").val(5);
        }
        $.ajax({
            data:$('#form1').serialize(),
            url: "index.php?r=ra/raswp/update",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                removecloud();
                if(data.status == 1) {
                    $('#msgbox').addClass('alert-success fa-ban');
                    $('#msginfo').html(data.msg);
                    $('#msgbox').show();
                }else{
                    $('#msgbox').addClass('alert-danger fa-ban');
                    $('#msginfo').html(data.msg);
                    $('#msgbox').show();
                }
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('????????????');
                $('#msgbox').show();
            }
        });
    }
</script>
