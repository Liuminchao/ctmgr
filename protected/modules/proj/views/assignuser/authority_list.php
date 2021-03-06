<style type="text/css">
    .format1{
        list-style:none; padding:0px; margin:0px; width:200px; float: left;
    }
    .format2{ width:50%; display:inline-block; float: left; padding-left: 0}
</style>
<?php
    $t->echo_grid_header();
    $no_submit = Yii::t('proj_project_user', 'no_submit');
    $people = Yii::t('proj_project_user','people');
    if (is_array($rows)|| is_array($arry)) {
        $i = 0;
        $tag = '';
        foreach($arry as $k => $value){
            if($value['check_status']==ProgramUser::ENTRANCE_APPLY){
                $i++;
                $arr[$i] = $value['user_id'];
            }
        }
        foreach($rows as $n => $val){
            if($val['check_status']==ProgramUser::ENTRANCE_SUCCESS){
                $tag.= $val['user_id'].'|';
            }
        }
        if($i>0){
            $button = "Submit All";
            $customize_button = 'Submit Selected';
            $json = implode("|",$arr);
            echo   "<div class='alert alert-success alert-dismissable'>
                        <i class='fa fa-check'></i>
                        <b>{$no_submit}:{$i}</b>&nbsp;&nbsp;&nbsp;
                        <button class='btn btn-primary btn-sm' type='button' style='margin-left: 10px' onclick='itemCustomizeEntrance(\"{$program_id}\");'>{$customize_button}
                        </button>
                        <button class='btn btn-primary btn-sm' type='button' style='margin-left: 10px' onclick='itemEntrance(\"{$json}\",\"{$program_id}\");'>{$button}
                        </button>
                    </div>";
        }else{
            echo   "<div class='alert alert-success alert-dismissable'>
                        <i class='fa fa-check'></i>
                        <b>  {$no_submit} :{$i} $people</b>
                    </div>";
        }
        $j = 1;
        $status_list = ProgramUser::statusText(); //状态text
        $status_css = ProgramUser::statusCss(); //状态css
        $program_model = Program::model()->findByPk($program_id);
        $contractor_id = $program_model->contractor_id;
        $authority_list = ProgramUser::AllRoleList($contractor_id);//角色列表
        $roleList = Role::roleList();//岗位列表
        $operator_id = Yii::app()->user->id;
        $value_list = OperatorProject::authorityList($operator_id);
        //判断项目权限
        $value = $value_list[$program_id];
        $pro_model = Program::model()->findByPk($program_id);
        $params = $pro_model->params;
        if($params != '0'){
            $params = json_decode($params,true);
            if (array_key_exists('ptw_mode', $params)) {
                $ptw_mode = $params['ptw_mode'];
            }
        }else{
            $ptw_mode = 'A';
        }
        if (Yii::app()->language == 'zh_CN') {
            $roleList['null'] = '否';
        }else{
            $roleList['null'] = 'No';
        }
        $attr['style'] = 'display:none';
        foreach ($rows as $i => $row) {
            $num = ($curpage - 1) * $this->pageSize + $j++;
            $t->echo_td($row['user_id'],'center',$attr);//员工编号
            $user_model = Staff::model()->findByPk($row['user_id']);
            $user_name = $user_model->user_name;
            $t->echo_td($user_name); //姓名
            $program_role = explode('|',$row['program_role']);
            if($row['check_status'] == ProgramUser::ENTRANCE_APPLY){
                $t->echo_td('<a href="#" class="editable editable-click ra_role" id="ra_role" data-value=""  data-type="select" data-url="index.php?r=proj/assignuser/setauthority&user_id='.$row['user_id'].'&program_id='.$program_id.'"data-title="Risk Assessment Role/风险评估角色">'.$authority_list['ra_role'][$row['ra_role']].'</a>','center');
                $t->echo_td('<a href="#" class="editable editable-click ptw_role" id="ptw_role" data-type="select" data-pk="1" data-url="index.php?r=proj/assignuser/setauthority&user_id='.$row['user_id'].'&program_id='.$program_id.'"data-title="Permit to work Role/许可证角色" >'.$authority_list['ptw_role'][$row['ptw_role']].'</a>','center');
                $t->echo_td('<a href="#" class="editable editable-click wsh_mbr_flag" id="wsh_mbr_flag" data-type="select" data-pk="1" data-url="index.php?r=proj/assignuser/setauthority&user_id='.$row['user_id'].'&program_id='.$program_id.'"data-title="WSH Committee Member/安全委员会委员" >'.$authority_list['wsh_mbr_flag'][$row['wsh_mbr_flag']].'</a>','center');
                $t->echo_td('<a href="#" class="editable editable-click meeting_flag" id="meeting_flag" data-type="select" data-pk="1" data-url="index.php?r=proj/assignuser/setauthority&user_id='.$row['user_id'].'&program_id='.$program_id.'"data-title="Conducting of toolbox meeting/举行会议人" >'.$authority_list['meeting_flag'][$row['meeting_flag']].'</a>','center');
                $t->echo_td('<a href="#" class="editable editable-click training_flag" id="training_flag" data-type="select" data-pk="1" data-url="index.php?r=proj/assignuser/setauthority&user_id='.$row['user_id'].'&program_id='.$program_id.'"data-title="Conducting of internal training/举行培训人" >'.$authority_list['training_flag'][$row['training_flag']].'</a>','center');
                $t->echo_td('<a href="#" class="editable editable-click first_role" data-type="select"  id="first_role"  data-pk="1"  data-url="index.php?r=proj/assignuser/setauthority&user_id='.$row['user_id'].'&program_id='.$program_id.'" data-title="Select First Role" >'.$roleList[$program_role[0]].'</a>','center');
                $t->echo_td('<a href="#" class="editable editable-click second_role" data-type="select"  id="second_role"  data-pk="1"  data-url="index.php?r=proj/assignuser/setauthority&user_id='.$row['user_id'].'&program_id='.$program_id.'" data-title="Select Second Role" >'.$roleList[$program_role[1]].'</a>','center');
                $t->echo_td('<a href="#" class="editable editable-click third_role" data-type="select"  id="third_role"  data-pk="1"  data-url="index.php?r=proj/assignuser/setauthority&user_id='.$row['user_id'].'&program_id='.$program_id.'" data-title="Select Third Role" >'.$roleList[$program_role[2]].'</a>','center');
            }else{
                $t->echo_td($authority_list['ra_role'][$row['ra_role']],'center');
                $t->echo_td($authority_list['ptw_role'][$row['ptw_role']],'center');
                $t->echo_td($authority_list['wsh_mbr_flag'][$row['wsh_mbr_flag']],'center');
                $t->echo_td($authority_list['meeting_flag'][$row['meeting_flag']],'center');
                $t->echo_td($authority_list['training_flag'][$row['training_flag']],'center');
                //$t->echo_td($authority_list['program_role'][$row['program_role']]);
                $t->echo_td($roleList[$program_role[0]],'center');
                $t->echo_td($roleList[$program_role[1]],'center');
                $t->echo_td($roleList[$program_role[2]],'center');
            }
            $status = '<span class="label ' . $status_css[$row['check_status']] . '">' . $status_list[$row['check_status']] . '</span>';
            $t->echo_td($status,'center'); //状态
            $link = "<ul class='format1'><li class='format2'><a style='float: left' href='javascript:void(0)' onclick='itemDownload(\"{$program_id}\",\"{$row['user_id']}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>" . Yii::t('proj_project_user', 'user_pdf') . "</a></li>
            <li class='format2'><a style='float: left' href='javascript:void(0)' onclick='itemPhoto(\"{$row['user_id']}\")'><i class=\"fa fa-fw fa-camera\"></i>".Yii::t('comp_staff', 'Qualification Certificate')."</a></li></ul>"; //导出人员PDF
            if ($row['check_status'] == ProgramUser::ENTRANCE_SUCCESS ) { 
                //状态是入场审批成功
                //出场
                $link .= "<ul class='format1'><li class='format2'><a style='float: left;margin-left:5px' href='javascript:void(0)' onclick='itemLeave(\"{$program_id}\",\"{$row['user_id']}\",\"{$user_name}\")'><i class=\"fa fa-fw fa-random\"></i>".Yii::t('proj_project_user', 'leave')."</a></li>";
                //如果这个人设置EPSS角色，就用不同颜色区分 判断NA
                if(($row['build_role_id'] !='' && $row['build_role_id'] !='EP0001') || ($row['rail_role_id'] != '' && $row['rail_role_id'] != 'EP0058') || ($row['road_role_id'] != '' && $row['road_role_id'] != 'EP0160')){
                    $link .= "<li class='format2'><a style='float: left;color: red' href='javascript:void(0)' onclick='itemEpssSet(\"{$program_id}\",\"{$row['user_id']}\",\"{$user_name}\")'><i class=\"fa fa-fw fa-user\"></i>EPSS</a></li>";
                }else{
                    $link .= "<li class='format2'><a style='float: left;' href='javascript:void(0)' onclick='itemEpssSet(\"{$program_id}\",\"{$row['user_id']}\",\"{$user_name}\")'><i class=\"fa fa-fw fa-user\"></i>EPSS</a></li>";
                }
                $link .= "</ul>";
                //$link .= "<ul class='format1'><li class='format2'><a style='float: left;margin-left:5px' href='javascript:void(0)' onclick='itemLeave(\"{$program_id}\",\"{$row['user_id']}\",\"{$user_list[$row['user_id']]}\")'><i class=\"fa fa-fw fa-random\"></i>".Yii::t('proj_project_user', 'leave')."</a></li></ul>";    //出场
            }else if($row['check_status'] == ProgramUser::LEAVE_PENDING || $row['check_status'] == ProgramUser::LEAVE_FAIL){
                //$link .= "<ul class='format1'><li class='format2'><a style='float: left;margin-left:5px' href='javascript:void(0)' onclick='itemEye(\"{$program_id}\",\"{$row['user_id']}\",\"{$user_list[$row['user_id']]}\")'><i class=\"fa fa-fw fa-eye\"></i>可见</a></li></ul>";    //可见按钮
            } else if($row['check_status'] == ProgramUser::ENTRANCE_APPLY || $row['check_status'] == ProgramUser::ENTRANCE_PENDING || $row['check_status'] == ProgramUser::ENTRANCE_FAIL){
                //状态是进入申请名单-1，入场待审批10，入场审批失败12的人
                //0-查看 1-编辑 2-屏蔽
                if($value == '1'){
                    $link .= "<ul class='format1'><li class='format2'><a style='float: left;margin-left:5px' href='javascript:void(0)' onclick='itemDelete(\"{$program_id}\",\"{$row['user_id']}\",\"{$user_name}\")'><i class=\"fa fa-fw fa-times\"></i>".Yii::t('proj_project_user', 'delete')."</a></li>";    //删除
                    //如果这个人设置EPSS角色，就用不同颜色区分 判断NA
                    if(($row['build_role_id'] !='' && $row['build_role_id'] !='EP0001') || ($row['rail_role_id'] != '' && $row['rail_role_id'] != 'EP0058') || ($row['road_role_id'] != '' && $row['road_role_id'] != 'EP0160')){
                        $link .= "<li class='format2'><a style='float: left;color: red' href='javascript:void(0)' onclick='itemEpssSet(\"{$program_id}\",\"{$row['user_id']}\",\"{$user_name}\")'><i class=\"fa fa-fw fa-user\"></i>EPSS</a></li>";
                    }else{
                        $link .= "<li class='format2'><a style='float: left;' href='javascript:void(0)' onclick='itemEpssSet(\"{$program_id}\",\"{$row['user_id']}\",\"{$user_name}\")'><i class=\"fa fa-fw fa-user\"></i>EPSS</a></li>";
                    }
                    $link .= "</ul>";
                }
            }else {
                $link .= '';
            }
            $t->echo_td($link);
            $t->end_row();
        }
    }

    $t->echo_grid_floor();

    $pager = new CPagination($cnt);
    $pager->pageSize = $this->pageSize;
    $pager->itemCount = $cnt;
?>

<div class="row">
    <div class="col-xs-5">
        <div class="dataTables_info" id="example2_info">
            <input type="hidden" id ="program_id" value="<?php echo $program_id ?>">
            <?php echo Yii::t('common', 'page_total'); ?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt'); ?>
        </div>
    </div>
    <div class="col-xs-7">
        <div class="dataTables_paginate paging_bootstrap">
            <?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="dataTables_info" id="example2_info">
            <?php  if($value == '1'){  ?>
                <button type="button" class="btn btn-primary btn-sm" style="margin-left: 10px" onclick="related(<?php echo $program_id; ?>);"><?php echo Yii::t('proj_project_user', 'related_people'); ?></button>
            <?php } ?>&nbsp;&nbsp;&nbsp;
            <button type="button" class="btn btn-primary btn-sm" style="margin-left: 10px" onclick="back(<?php echo $program_id ?>);"><?php echo Yii::t('proj_project_user', 'back_program'); ?></button>
            <?php echo "<button type='button' class='btn btn-primary btn-sm' style='margin-left: 10px' onclick='compress(\"{$program_id}\",\"{$tag}\",\"{$curpage}\");'>".Yii::t('proj_project_user', 'compress')."</button>" ?>
            <?php echo "<button type='button' class='btn btn-primary btn-sm' style='margin-left: 10px' onclick='itemExport(\"{$program_id}\")'>".Yii::t('proj_project_user', 'export')."</button>" ?>
            <?php echo "<button type='button' class='btn btn-primary btn-sm' style='margin-left: 10px' onclick='itemExportEpss(\"{$program_id}\")'>Export Epss Excel</button>" ?>
            <?php echo "<button type='button' class='btn btn-primary btn-sm' style='margin-left: 10px' onclick='itemImportEpss(\"{$program_id}\")'>Import Epss Excel</button>" ?>
            <?php echo "<button type='button' class='btn btn-primary btn-sm' style='margin-left: 10px' onclick='itemExportQr(\"{$program_id}\")'>Export Qrcode</button>" ?>
<!--            --><?php //echo "<button type='button' class='btn btn-primary btn-sm' style='margin-left: 10px' onclick='batchleave(\"{$program_id}\",\"{$tag}\")'>".Yii::t('proj_project_user', 'batch_live')."</button>" ?>
        </div>
    </div>
</div>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script type="text/javascript" src="js/bootstrap-editable.js"></script>
<script type="text/javascript" src="js/select2.js"></script>
<script src="js/loading.js"></script>
<script type="text/javascript">
    //返回
    var back = function (id) {
        window.location = "index.php?r=proj/project/list&ptype=<?php echo Yii::app()->session['project_type'];?>&program_id="+id;
    }

    //导入EPSS
    var itemImportEpss = function (id) {
        window.location = "index.php?r=proj/import/view&program_id="+id;
    }

    //checkbox
    function test(o) {
    }
    $('.select_all').click(function(){
    });
    //批量出场
    var batchleave = function (id,tag) {
        var tbodyObj = document.getElementById('example2');
        var tag = '';
        $("table :checkbox").each(function(key,value){
            if(key != 0) {
                if ($(value).prop('checked')) {
                    var user_id = tbodyObj.rows[key].cells[1].innerHTML;
                    tag += user_id + '|';
                }
            }
        })
        if(tag.length == 0){
            alert('<?php echo Yii::t('proj_project_user', 'error_tag_is_null'); ?>');
            return false;
        }
        tag=(tag.substring(tag.length-1)=='|')?tag.substring(0,tag.length-1):tag;
        alert('<?php echo Yii::t('proj_project_user', 'confirm_batch_live'); ?>');
        $.ajax({
            data: {id: id,tag:tag},
            url: "index.php?r=proj/assignuser/batchleaveuser",
            type: "POST",
            dataType: "json",
            beforeSend: function () {
                addcloud(); //为页面添加遮罩
            },
            success: function (data) {
                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_remove'); ?>");
                    removecloud();//去遮罩
                    <?php echo $this->gridId; ?>.refresh();
                } else {
                    //alert("<?php echo Yii::t('common', 'error_remove'); ?>");
                    alert(data.msg);
                }
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
    
    //批量压缩
    var compress = function (id,tag,curpage) {
        var tbodyObj = document.getElementById('example2');
        var tag = '';
        $("table :checkbox").each(function(key,value){
            if(key != 0) {
                if ($(value).prop('checked')) {
                    var user_id = tbodyObj.rows[key].cells[1].innerText;
                    tag += user_id + '|';
                }
            }
        })
        if(tag.length == 0){
            alert('<?php echo Yii::t('proj_project_user', 'error_tag_is_null'); ?>');
            return false;
        }
        tag=(tag.substring(tag.length-1)=='|')?tag.substring(0,tag.length-1):tag;
        alert('<?php echo Yii::t('proj_project_user', 'confirm_compress'); ?>');
        $.ajax({
            data: {id: id,tag:tag,curpage:curpage},
            url: "index.php?r=proj/assignuser/userbatch",
            type: "POST",
            dataType: "json",
            beforeSend: function () {
                addcloud(); //为页面添加遮罩
            },
            success: function (data) {
                var form = $("<form>");   //定义一个form表单
                form.attr('style', 'display:none');   //在form表单中添加查询参数
                form.attr('target', '');
                form.attr('method', 'post');
                form.attr('action', "index.php?r=proj/assignuser/compress");

                var input1 = $('<input>');
                input1.attr('type', 'hidden');
                input1.attr('name', 'filename');
                input1.attr('value', data.filename);
                $('body').append(form);  //将表单放置在web中
                form.append(input1);   //将查询参数控件提交到表单上
                removecloud();//去遮罩
                form.submit();
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
    //导出excel
    var itemExport = function(id){

        var tbodyObj = document.getElementById('example2');
        var tag = '';
        $("table :checkbox").each(function(key,value){
            if(key != 0) {
                if ($(value).prop('checked')) {
                    var user_id = tbodyObj.rows[key].cells[1].innerText;
                    tag += user_id + '|';
                }
            }
        })
        tag=(tag.substring(tag.length-1)=='|')?tag.substring(0,tag.length-1):tag;

        $('#tag').val(tag);

        var objs = document.getElementById("_query_form").elements;
        var i = 0;
        var cnt = objs.length;
        var obj;
        var url = '';

        for (i = 0; i < cnt; i++) {
            obj = objs.item(i);
            url += '&' + obj.name + '=' + obj.value;
        }

        $.ajax({
            data:$('#_query_form').serialize(),
            url: "index.php?r=proj/assignuser/staffinfo",
            type: "POST",
            dataType: "json",
            success: function (data) {
                if(data.count > 0 || tag.length > 0){
                    window.location = "index.php?r=proj/assignuser/staffexport"+url;
                }else{
                    alert('<?php echo Yii::t('proj_project_user','error_project_user_null'); ?>');
                }
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('System Error');
                $('#msgbox').show();
            }
        });

    }
    //导出EPSS excel
    var itemExportEpss = function(id){

        var tbodyObj = document.getElementById('example2');
        var tag = '';
        $("table :checkbox").each(function(key,value){
            if(key != 0) {
                if ($(value).prop('checked')) {
                    var user_id = tbodyObj.rows[key].cells[1].innerText;
                    tag += user_id + '|';
                }
            }
        })
        tag=(tag.substring(tag.length-1)=='|')?tag.substring(0,tag.length-1):tag;

        $('#tag').val(tag);

        var objs = document.getElementById("_query_form").elements;
        var i = 0;
        var cnt = objs.length;
        var obj;
        var url = '';

        for (i = 0; i < cnt; i++) {
            obj = objs.item(i);
            url += '&' + obj.name + '=' + obj.value;
        }

        $.ajax({
            data:$('#_query_form').serialize(),
            url: "index.php?r=proj/assignuser/staffinfo",
            type: "POST",
            dataType: "json",
            success: function (data) {
                if(data.count > 0 || tag.length > 0){
                    window.location = "index.php?r=proj/assignuser/epssexport"+url;
                }else{
                    alert('<?php echo Yii::t('proj_project_user','error_project_user_null'); ?>');
                }
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('System Error');
                $('#msgbox').show();
            }
        });

    }

    //批量导出
    var itemExportQr = function (program_id) {
        var objs = document.getElementById("_query_form").elements;
        var i = 0;
        var cnt = objs.length;
        var obj;
        var url = '';

        var tbodyObj = document.getElementById('example2');
        var tag = '';
        $("table :checkbox").each(function(key,value){
            if(key != 0) {
                if ($(value).prop('checked')) {
                    var id = tbodyObj.rows[key].cells[1].innerHTML;
                    tag += id + '|';
                    i++;
                }
            }
        })
        if(tag != ''){
            tag = tag.substr(0,tag.length-1);
        }else{
            alert('Please select Record.');
            return false;
        }
        addcloud();
        ajaxReadQr(tag,i,0,program_id);
    }
    var per_read_cnt = 20;
    /*
    * 加载数据
    */
    var ajaxReadQr = function (tag, rowcnt, startrow, program_id){
        jQuery.ajax({
            data: {tag:tag,startrow: startrow, per_read_cnt:per_read_cnt, program_id:program_id},
            type: 'post',
            url: './index.php?r=proj/assignuser/createqrpdf',
            dataType: 'json',
            success: function (data, textStatus) {
                if (rowcnt > startrow) {
                    ajaxReadQr(tag,rowcnt, startrow+per_read_cnt, program_id);
                }else{
                    clearQr();
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                alert(XMLHttpRequest);
                alert(textStatus);
                alert(errorThrown);
            },
        });
        return false;
    }
    /*
    * 清除缓存，下载压缩包
    */
    var clearQr = function(){//alert('aa');
        removecloud();
        window.location = "index.php?r=proj/assignuser/downloadqrzip";
    }

    //人员关联
    var related = function(id){
        window.location = "index.php?r=proj/assignuser/userapply&ptype=<?php echo Yii::app()->session['project_type'];?>&id=" + id;
    }
    //2021-09-09开始
    //自选提交入场申请
    var itemCustomizeEntrance = function (program_id) {
        if (!confirm('Proceed to submit personnel into the project？')) {
            return;
        }
        var tbodyObj = document.getElementById('example2');
        var tag = '';
        $("table :checkbox").each(function(key,value){
            if(key != 0) {
                if ($(value).prop('checked')) {
                    var user_id = tbodyObj.rows[key].cells[1].innerHTML;
                    tag += user_id + '|';
                }
            }
        })
        if(tag.length == 0){
            alert('<?php echo Yii::t('proj_project_user', 'error_tag_is_null'); ?>');
            return false;
        }
        list=(tag.substring(tag.length-1)=='|')?tag.substring(0,tag.length-1):tag;
        $.ajax({
            data: {list: list,program_id: program_id,confirm: 1},
            url: "index.php?r=proj/assignuser/entranceuser",
            dataType: "json",
            type: "POST",
            beforeSend: function () {
                addcloud(); //为页面添加遮罩
            },
            success: function (data) {
                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_apply'); ?>");
                    removecloud();//去遮罩
                    <?php echo $this->gridId; ?>.refresh();
                } else {
                    //alert("<?php echo Yii::t('common', 'error_apply'); ?>");
                    alert(data.msg);
                }
                //var start_cnt = data.start_cnt;
                //var cnt = data.cnt;
                //itemEntranceFace(list,program_id,start_cnt,cnt);
            }
        });
    }
    //2021-09-09结束
    jQuery(document).ready(function () {
        function initTableCheckbox() {
            var $thr = $('#example2 thead tr');
            var $checkAllTh = $('<th><input type="checkbox" id="checkAll" name="checkAll" /></th>');
            /*将全选/反选复选框添加到表头最前，即增加一列*/
            $thr.prepend($checkAllTh);
            /*“全选/反选”复选框*/
            var $checkAll = $thr.find('input');
            $checkAll.click(function(event){
                /*将所有行的选中状态设成全选框的选中状态*/
                $tbr.find('input').prop('checked',$(this).prop('checked'));
                /*并调整所有选中行的CSS样式*/
                if ($(this).prop('checked')) {
                    $tbr.find('input').parent().parent().addClass('warning');
                } else{
                    $tbr.find('input').parent().parent().removeClass('warning');
                }
                /*阻止向上冒泡，以防再次触发点击操作*/
                event.stopPropagation();
            });
            /*点击全选框所在单元格时也触发全选框的点击操作*/
            $thr.click(function(){
                $(this).find('input').click();
            });
            var $tbr = $('#example2 tbody tr');
            var $checkItemTd = $('<td><input type="checkbox" name="checkItem" value=""/></td>');
            /*每一行都在最前面插入一个选中复选框的单元格*/
            $tbr.prepend($checkItemTd);
            /*点击每一行的选中复选框时*/
            $tbr.find('input').click(function(event){
                /*调整选中行的CSS样式*/
                $(this).parent().parent().toggleClass('warning');
                /*如果已经被选中行的行数等于表格的数据行数，将全选框设为选中状态，否则设为未选中状态*/
                $checkAll.prop('checked',$tbr.find('input:checked').length == $tbr.length ? true : false);
                /*阻止向上冒泡，以防再次触发点击操作*/
                event.stopPropagation();
            });
            /*点击每一行时也触发该行的选中操作*/
            $tbr.click(function(){
                $(this).find('input').click();
            });
        }
        initTableCheckbox();

            //$.fn.editable.defaults.mode = 'popup';
        //$.fn.editable.defaults.mode = 'inline';
        var n = 4;
        function showTime(flag) {
            if (flag == false)
                return;
            n--;
            $('.popover fade top in editable-container editable-popup').html(n + ' <?php echo Yii::t('common', 'tip_close'); ?>');
            if (n == 0)
                $("#modal-close").click();
            else
                setTimeout('showTime()', 1000);
        }
        $('.ra_role').editable({
            ajaxOptions: {
                dataType: 'json' //assuming json response
            },
            pk: 1,
            source: function () {
                var result = [];
                $.ajax({
                    url: 'index.php?r=proj/assignuser/getrasource',
                    async: false,
                    type: "get",
                    dataType: 'json',
                    success: function (data, status) {
                        $.each(data, function (key, value) {
                            result.push({ value: key, text: value });
                        });
                    }
                });
                return result; } ,
            validate: function(newValue) {
                if($.trim(newValue) == '') {
                    return '<?php echo Yii::t('proj_project_user', 'alert_authority'); ?>';
                }
            },
            success: function(response, newValue) {
                //$('#ra_role').editable('option', 'source', sources[newValue]);  
                //$('#ra_role').editable('setValue', null);
                //return '设置成功';
                //showTime(response.refresh);
                //return response.msg;
            },
            error: function(response, newValue) {
                if(response.status === 500) {
                    return 'Service unavailable. Please try later.';
                }else{
                    return '未知错误';
                }
            },
        });
        var program_id = $("#program_id").val();
        $('.ptw_role').editable({ 
            ajaxOptions: {
                dataType: 'json' //assuming json response
            },
            pk: 1,
            source: function () {
                var result = [];
                $.ajax({
                    url: 'index.php?r=proj/assignuser/getptwsource&program_id='+program_id,
                    async: false,
                    type: "get",
                    dataType: 'json',
                    success: function (data, status) {
                        $.each(data, function (key, value) {
                            result.push({ value: key, text: value });
                        });
                    }
                });
                return result; } ,
            validate: function(newValue) {
                if($.trim(newValue) == '') {
                    return '<?php echo Yii::t('proj_project_user', 'alert_authority'); ?>';
                }
            },
            success: function(response, newValue) {
                //$('#ra_role').editable('option', 'source', sources[newValue]);  
                //$('#ra_role').editable('setValue', null);
                //return '设置成功';
                //showTime(response.refresh);
                //return response.msg;
            },
            error: function(response, newValue) {
                if(response.status === 500) {
                    return 'Service unavailable. Please try later.';
                }else{
                    return '未知错误';
                }
            },
        });
        $('.first_ptw_role').editable({
            ajaxOptions: {
                dataType: 'json' //assuming json response
            },
            pk: 1,
            source: function () {
                var result = [];
                $.ajax({
                    url: 'index.php?r=proj/assignuser/getptwsource&program_id='+program_id,
                    async: false,
                    type: "get",
                    dataType: 'json',
                    success: function (data, status) {
                        $.each(data, function (key, value) {
                            result.push({ value: key, text: value });
                        });
                    }
                });
                return result; } ,
            validate: function(newValue) {
                if($.trim(newValue) == '') {
                    return '<?php echo Yii::t('proj_project_user', 'alert_authority'); ?>';
                }
            },
            success: function(response, newValue) {
                //$('#ra_role').editable('option', 'source', sources[newValue]);
                //$('#ra_role').editable('setValue', null);
                //return '设置成功';
                //showTime(response.refresh);
                //return response.msg;
            },
            error: function(response, newValue) {
                if(response.status === 500) {
                    return 'Service unavailable. Please try later.';
                }else{
                    return '未知错误';
                }
            },
        });
        $('.second_ptw_role').editable({
            ajaxOptions: {
                dataType: 'json' //assuming json response
            },
            pk: 1,
            source: function () {
                var result = [];
                $.ajax({
                    url: 'index.php?r=proj/assignuser/getptwsource&program_id='+program_id,
                    async: false,
                    type: "get",
                    dataType: 'json',
                    success: function (data, status) {
                        $.each(data, function (key, value) {
                            result.push({ value: key, text: value });
                        });
                    }
                });
                return result; } ,
            validate: function(newValue) {
                if($.trim(newValue) == '') {
                    return '<?php echo Yii::t('proj_project_user', 'alert_authority'); ?>';
                }
            },
            success: function(response, newValue) {
                //$('#ra_role').editable('option', 'source', sources[newValue]);
                //$('#ra_role').editable('setValue', null);
                //return '设置成功';
                //showTime(response.refresh);
                //return response.msg;
            },
            error: function(response, newValue) {
                if(response.status === 500) {
                    return 'Service unavailable. Please try later.';
                }else{
                    return '未知错误';
                }
            },
        });
        $('.wsh_mbr_flag').editable({ 
            ajaxOptions: {
                dataType: 'json' //assuming json response
            },
            pk: 1,
            source: function () {
                var result = [];
                $.ajax({
                    url: 'index.php?r=proj/assignuser/getwshsource',
                    async: false,
                    type: "get",
                    dataType: 'json',
                    success: function (data, status) {
                        $.each(data, function (key, value) {
                            result.push({ value: key, text: value });
                        });
                    }
                });
                return result; } ,
            validate: function(newValue) {
                if($.trim(newValue) == '') {
                    return '<?php echo Yii::t('proj_project_user', 'alert_authority'); ?>';
                }
            },
            success: function(response, newValue) {
                //$('#ra_role').editable('option', 'source', sources[newValue]);  
                //$('#ra_role').editable('setValue', null);
                //return '设置成功';
                //showTime(response.refresh);
                //return response.msg;
            },
            error: function(response, newValue) {
                if(response.status === 500) {
                    return 'Service unavailable. Please try later.';
                }else{
                    return '未知错误';
                }
            },
        });
        $('.meeting_flag').editable({ 
            ajaxOptions: {
                dataType: 'json' //assuming json response
            },
            pk: 1,
            validate: function(newValue) {
                if($.trim(newValue) == '') {
                    return '<?php echo Yii::t('proj_project_user', 'alert_authority'); ?>';
                }
            },
            source: function () {
                var result = [];
                $.ajax({
                    url: 'index.php?r=proj/assignuser/getmeetingsource',
                    async: false,
                    type: "get",
                    dataType: 'json',
                    success: function (data, status) {
                        $.each(data, function (key, value) {
                            result.push({ value: key, text: value });
                        });
                    }
                });
                return result; } ,
            success: function(response, newValue) {
                //$('#ra_role').editable('option', 'source', sources[newValue]);  
                //$('#ra_role').editable('setValue', null);
                //return '设置成功';
                //showTime(response.refresh);
                //return response.msg;
            },
            error: function(response, newValue) {
                if(response.status === 500) {
                    return 'Service unavailable. Please try later.';
                }else{
                    return '未知错误';
                }
            },
        });
        $('.training_flag').editable({ 
            ajaxOptions: {
                dataType: 'json' //assuming json response
            },
            pk: 1,
            source: function () {
                var result = [];
                $.ajax({
                    url: 'index.php?r=proj/assignuser/gettrainsource',
                    async: false,
                    type: "get",
                    dataType: 'json',
                    success: function (data, status) {
                        $.each(data, function (key, value) {
                            result.push({ value: key, text: value });
                        });
                    }
                });
                return result; } ,
            validate: function(newValue) {
                if($.trim(newValue) == '') {
                    return '<?php echo Yii::t('proj_project_user', 'alert_authority'); ?>';
                }
            },
            success: function(response, newValue) {
                // $('#ra_role').editable('option', 'source', sources[newValue]);
                // $('#ra_role').editable('setValue', null);
                // return '设置成功';
                // showTime(response.refresh);
                // return response.msg;
            },
            error: function(response, newValue) {
                if(response.status === 500) {
                    return 'Service unavailable. Please try later.';
                }else{
                    return '未知错误';
                }
            },
        });


        $('.first_role').editable({
            ajaxOptions: {
                dataType: 'json' //assuming json response
            },
            pk: 1,
            validate: function(newValue) {
                if($.trim(newValue) == '') {
                    return '<?php echo Yii::t('proj_project_user', 'alert_authority'); ?>';
                }
            },
            source: function () {
                var result = [];
                $.ajax({
                    url: 'index.php?r=proj/assignuser/getsource',
                    async: false,
                    type: "get",
                    dataType: 'json',
                    success: function (data, status) {
                        $.each(data, function (key, value) {
                            result.push({ value: value.id, text: value.name });
                        });
                    }
                });
                return result; } ,
            success: function(response, newValue) {
                //$('#ra_role').editable('option', 'source', sources[newValue]);
                //$('#ra_role').editable('setValue', null);
                //return '设置成功';
                //showTime(response.refresh);
                //return response.msg;
            },
            error: function(response, newValue) {
                if(response.status === 500) {
                    return 'Service unavailable. Please try later.';
                }else{
                    return '未知错误';
                }
            },
        });




//        $('.first_role').editable({
//            field: "first_role",
//            type: 'select2',
//            name: 'first_role',
//            placement: 'top',
//            validate: function(newValue) {
//                if($.trim(newValue) == '') {
//                    return '<?php //echo Yii::t('proj_project_user', 'alert_authority'); ?>//';
//                }
//            },
//            success: function (response, newValue) {
////                debugger;
//            },
//            error: function(response, newValue) {
////                debugger;
//            },
//            source: function () {
//                var result = [];
//                    $.ajax({
//                        url: 'index.php?r=proj/assignuser/getsource',
//                        async: false,
//                        type: "get",
//                        dataType: 'json',
//                        success: function (data, status) {
//                            $.each(data, function (key, value) {
//                                result.push({ value: value.id, text: value.name });
//                            });
//                        }
//                    });
//                return result; } ,
//            select2: {
//                placeholder:'请选择项目第一角色',//文本框的提示信息
//                allowClear: true,//是否允许用户清除文本信息
//                multiple: false,
//            }
//        });

        $('.second_role').editable({
            ajaxOptions: {
                dataType: 'json' //assuming json response
            },
            pk: 1,
            validate: function(newValue) {
                if($.trim(newValue) == '') {
                    return '<?php echo Yii::t('proj_project_user', 'alert_authority'); ?>';
                }
            },
            source: function () {
                var result = [];
                $.ajax({
                    url: 'index.php?r=proj/assignuser/getsource',
                    async: false,
                    type: "get",
                    dataType: 'json',
                    success: function (data, status) {
                        $.each(data, function (key, value) {
                            result.push({ value: value.id, text: value.name });
                        });
                    }
                });
                return result; } ,
            success: function(response, newValue) {
                //$('#ra_role').editable('option', 'source', sources[newValue]);
                //$('#ra_role').editable('setValue', null);
                //return '设置成功';
                //showTime(response.refresh);
                //return response.msg;
            },
            error: function(response, newValue) {
                if(response.status === 500) {
                    return 'Service unavailable. Please try later.';
                }else{
                    return '未知错误';
                }
            },
        });

        $('.third_role').editable({
            ajaxOptions: {
                dataType: 'json' //assuming json response
            },
            pk: 1,
            validate: function(newValue) {
                if($.trim(newValue) == '') {
                    return '<?php echo Yii::t('proj_project_user', 'alert_authority'); ?>';
                }
            },
            source: function () {
                var result = [];
                $.ajax({
                    url: 'index.php?r=proj/assignuser/getsource',
                    async: false,
                    type: "get",
                    dataType: 'json',
                    success: function (data, status) {
                        $.each(data, function (key, value) {
                            result.push({ value: value.id, text: value.name });
                        });
                    }
                });
                return result; } ,
            success: function(response, newValue) {
                //$('#ra_role').editable('option', 'source', sources[newValue]);
                //$('#ra_role').editable('setValue', null);
                //return '设置成功';
                //showTime(response.refresh);
                //return response.msg;
            },
            error: function(response, newValue) {
                if(response.status === 500) {
                    return 'Service unavailable. Please try later.';
                }else{
                    return '未知错误';
                }
            },
        });

//        $('.second_role').editable({
//            field: "second_role",
//            type: 'select2',
//            name: 'second_role',
//            placement: 'top',
//            validate: function(newValue) {
//                if($.trim(newValue) == '') {
//                    return '<?php //echo Yii::t('proj_project_user', 'alert_authority'); ?>//';
//                }
//            },
//            success: function (response, newValue) {
////                debugger;
//            },
//            error: function(response, newValue) {
////                debugger;
//            },
//            source: function () {
//                var result = [];
//                    $.ajax({
//                        url: 'index.php?r=proj/assignuser/getsource',
//                        async: false,
//                        type: "get",
//                        dataType: 'json',
//                        success: function (data, status) {
//                            $.each(data, function (key, value) {
//                                result.push({ value: value.id, text: value.name });
//                            });
//                        }
//                    });
//                return result; } ,
//            select2: {
//                placeholder:'请选择项目第二角色',//文本框的提示信息
//                allowClear: true,//是否允许用户清除文本信息
//                multiple: false,
//            }
//        });
//        $('.third_role').editable({
//            field: "third_role",
//            type: 'select2',
//            name: 'third_role',
//            placement: 'top',
//            validate: function(newValue) {
//                if($.trim(newValue) == '') {
//                    return '<?php //echo Yii::t('proj_project_user', 'alert_authority'); ?>//';
//                }
//            },
//            success: function (response, newValue) {
////                debugger;
//            },
//            error: function(response, newValue) {
////                debugger;
//            },
//            source: function () {
//                var result = [];
//                $.ajax({
//                    url: 'index.php?r=proj/assignuser/getsource',
//                    async: false,
//                    type: "get",
//                    dataType: 'json',
//                    success: function (data, status) {
//                        $.each(data, function (key, value) {
//                            result.push({ value: value.id, text: value.name });
//                        });
//                    }
//                });
//                return result; } ,
//            select2: {
//                placeholder:'请选择项目第三角色',//文本框的提示信息
//                allowClear: true,//是否允许用户清除文本信息
//                multiple: false,
//            }
//        });
    });

</script>

