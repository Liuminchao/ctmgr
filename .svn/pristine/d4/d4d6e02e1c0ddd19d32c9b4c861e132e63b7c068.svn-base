<?php
//var_dump($wage_rows);
//exit;
$t->echo_grid_header();
if (is_array($rows)) {
    
    //$type_list = Role::contractorTypeText();
    $status_list = Role::statusText(); //状态
    $status_css = Role::statusCss();
    $default_wage = 0;
//    $tool = true;
//    //$tool = false;验证权限
//    if (Yii::app()->user->checkAccess('mchtm')) {
//        $tool = true;
//    }

    foreach ($rows as $i => $row) {
        $b = 0;
        $t->begin_row("onclick", "getDetail(this,'{$row['role_id']}');");

        //$t->echo_td($type_list[$row['contractor_type']]); //contractor_type
        //$t->echo_td($row['role_id']); //role_id
        if (Yii::app()->language == 'zh_CN') {
            $t->echo_td($row['role_name']); //role_name
            $t->echo_td($row['team_name']); //team_name
        } else if (Yii::app()->language == 'en_US') {
            $t->echo_td($row['role_name_en']); //role_name_en
            $t->echo_td($row['team_name_en']); //team_name_en
        }
        $t->echo_td($ation_type);
        if(empty($wage_rows))
            goto  end;
        foreach($wage_rows as $j =>$wage_list){
            
            if($row['role_id']==$j){
                $b = 1;
//                $t->echo_td($list['work_hours']);
                $t->echo_td('<a href="#" class="edit_wage" id="wage" data-type="text" data-pk="1" data-url="index.php?r=payroll/wage/edit&role_id='.$row['role_id'].'&ation_type='.$ation_type.'"data-title="时薪" >'.$wage_list['wage'].'</a>');
                $t->echo_td('<a href="#" class="edit_overtime_wage" id="overtime_wage" data-type="text" data-pk="1"  data-url="index.php?r=payroll/wage/edit&role_id='.$row['role_id'].'&ation_type='.$ation_type.'" data-title="加班时薪">'.$wage_list['overtime_wage'].'</a>');
//                $t->echo_td($list['overtime_hours']);
            }
        }
        end:
            if(empty($wage_rows)|| $b==0){
//                var_dump(111111111111111111);
                $t->echo_td('<a href="#" class="edit_wage" id="wage" data-type="text" data-pk="1" data-url="index.php?r=payroll/wage/edit&role_id='.$row['role_id'].'&ation_type='.$ation_type.'" data-title="时薪">'.$default_wage.'</a>');
                $t->echo_td('<a href="#" class="edit_overtime_wage" id="overtime_wage" data-type="text" data-pk="1" data-url="index.php?r=payroll/wage/edit&role_id='.$row['role_id'].'&ation_type='.$ation_type.'" data-title="加班时薪">'.$default_wage.'</a>');
    }
    $t->end_row();
}
}
$t->echo_grid_floor();

$pager = new CPagination($cnt);
$pager->pageSize = $pageSize;
$pager->itemCount = $cnt;
?>

<div class="row">
    <div class="col-xs-3">
        <div class="dataTables_info" id="example2_info">
<?php echo Yii::t('common', 'page_total'); ?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt'); ?>
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
    $('.edit_wage').editable({
        ajaxOptions: {
            dataType: 'json' //assuming json response
        },
        type: 'text',
        title: 'wage',
        validate: function(value) {
            if($.trim(value) == '') {
                return '<?php echo Yii::t('pay_payroll', 'alert_wage'); ?>';
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
    $('.edit_overtime_wage').editable({
        ajaxOptions: {
            dataType: 'json' //assuming json response
        },
        type: 'text',
        title: 'overtime_wage',
        validate: function(value) {
                if($.trim(value) == '') {
                    return '<?php echo Yii::t('pay_payroll', 'alert_overtimewage'); ?>';
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