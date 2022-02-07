<?php
$t->echo_grid_header();
$status_list = PayrollWorkHour::statusText();//状态
$status_css = PayrollWorkHour::statusCss();
$default_hour = 0;
if (is_array($rs)) {
    $j = 1;

    $tool = true;
    //$tool = false;验证权限
    if (Yii::app()->user->checkAccess('mchtm')) {
        $tool = true;
  }
//    $roleList = Role::roleList();
  
    foreach ($rs as $i => $row) {
        $b = 0;
        $t->begin_row("onclick", "getDetail(this,'{$i}');");
//        $t->begin_row();
        $num = ($curpage - 1) * $this->pageSize + $j++;
        $t->echo_td('<div id="working_date">'.$working_date.'</div>'); 
        $t->echo_td($program_list[$row['program_id']]); 
        $t->echo_td($row['user_name']);
        if(empty($r))
            goto end;
        foreach($r as $j =>$list){
            
            if($row['user_id']==$list['user_id'] && $row['program_id']==$list['program_id']){
                $b = 1;
//                $t->echo_td($list['work_hours']);
                $t->echo_td('<a href="#" class="edit_work_hours" id="work_hours" data-type="text" data-pk="1" data-url="index.php?r=payroll/workhour/edit&working_date='.$working_date.'&user_id='.$list['user_id'].'&program_id='.$row['program_id'].'"data-title="基本工时" >'.$list['work_hours'].'</a>');
                $t->echo_td('<a href="#" class="edit_overtime_hours" id="overtime_hours" data-type="text" data-pk="1"  data-url="index.php?r=payroll/workhour/edit&working_date='.$working_date.'&user_id='.$list['user_id'].'&program_id='.$row['program_id'].'" data-title="加班工时">'.$list['overtime_hours'].'</a>');
//                $t->echo_td($list['overtime_hours']);
            }
        }
        end:
            if(empty($r)|| $b==0){
//                var_dump(111111111111111111);
                $t->echo_td('<a href="#" class="edit_work_hours" id="work_hours" data-type="text" data-pk="1" data-url="index.php?r=payroll/workhour/edit&working_date='.$working_date.'&user_id='.$row['user_id'].'&program_id='.$row['program_id'].'" data-title="基本工时">'.$default_hour.'</a>');
                $t->echo_td('<a href="#" class="edit_overtime_hours" id="overtime_hours" data-type="text" data-pk="1" data-url="index.php?r=payroll/workhour/edit&working_date='.$working_date.'&user_id='.$row['user_id'].'&program_id='.$row['program_id'].'" data-title="加班工时">'.$default_hour.'</a>');
            }
       $t->end_row();
    }
}

$t->echo_grid_floor();

$pager = new CPagination($cnt);
$pager->pageSize = $this->pageSize;
$pager->itemCount = $cnt;
?>

<div class="row">
    <div class="col-xs-3">
        <div class="dataTables_info" id="example2_info">
            <?php echo Yii::t('common', 'page_total');?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt');?>
        </div>
    </div>
    <div class="col-xs-9">
        <div class="dataTables_paginate paging_bootstrap">
            <?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script type="text/javascript" src="js/bootstrap-editable.js"></script>
<!--<script type="text/javascript" src="js/bootstrap-editable.min.js"></script>-->	
<script type="text/javascript">
jQuery(document).ready(function () {
    
    $.fn.editable.defaults.mode = 'popup';
//    $.fn.editable.defaults.mode = 'inline';
    var n = 4;
    //定时关闭弹窗
    function showTime(flag) {
        if (flag == false)
            return;
        n--;
//        $('#divMain').html(n + ' <?php echo Yii::t('common', 'tip_close'); ?>');
        if (n == 0)
            $("#working_date").click();
        else
            setTimeout('showTime()', 1000);
    }
    $('.edit_work_hours').editable({
        ajaxOptions: {
            dataType: 'json' //assuming json response
        },
        type: 'text',
        title: 'work_hours',
        validate: function(value) {
            if($.trim(value) == '') {
                return '<?php echo Yii::t('proj_report', 'alert_hours'); ?>';
            }
        },
        success: function(response, newValue) {
            if(response.status == 1) {
                $(this).html("<b>"+newValue+"</b>");
//                return response.msg;
            }else{
                return response.msg;
            }
        },
        error: function(response, newValue) {
            if(response.status === 500) {
                return 'Service unavailable. Please try later.';
            }else{
//                    return response.responseText;
                return '未知错误';
            }
        },
        display: function(value, sourceData) {
            var escapedValue = $("<div>").text(value).html();
            $(this).html("<b>"+escapedValue+"</b>");
        },
    });
    $('.edit_overtime_hours').editable({
        ajaxOptions: {
            dataType: 'json' //assuming json response
        },
        type: 'text',
        title: 'overtime_hours',
        validate: function(value) {
                if($.trim(value) == '') {
                    return '<?php echo Yii::t('proj_report', 'alert_hours'); ?>';
                }
        },
        success: function(response, newValue) {
            if(response.status == 1) {
                $(this).html("<b>"+newValue+"</b>");
//                return response.msg;
            }else{
                return response.msg;
            }
        },
        error: function(response, newValue) {
            if(response.status === 500) {
                return 'Service unavailable. Please try later.';
            } else {
//                    return response.responseText;
                return '未知错误';
            }
        },
        display: function(value, sourceData) {
                var escapedValue = $("<div>").text(value).html();
                $(this).html("<b>"+escapedValue+"</b>");
        }
    });
        $('.td_userphone').each(function(){
            v = $(this).text();
            v1 = see(v);
            $(this).text(v1);
        });
        $('.td_workno').each(function(){
            b = $(this).text();
            b1 = play(b);
            $(this).text(b1);
        });
    });
</script>

