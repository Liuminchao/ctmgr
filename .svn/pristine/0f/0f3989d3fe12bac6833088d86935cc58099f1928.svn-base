<?php
$t->echo_grid_header();
$status_list = PayrollSalary::statusText();//状态
$status_css = PayrollSalary::statusCss();

if (is_array($rows)) {
    $j = 1;
    $tool = true;
    //$tool = false;验证权限
    if (Yii::app()->user->checkAccess('mchtm')) {
        $tool = true;
    }
//    $roleList = Role::roleList();
    
    foreach ($rows as $i => $row) {

        $t->begin_row("onclick", "getDetail(this,'{$row['user_id']}');");
//        $t->begin_row();
        $num = ($curpage - 1) * $this->pageSize + $j++;
        
        if ($row['status'] == PayrollSalarysummary::STATUS_DISABLE) { //状态是未入库的 可以编辑详细工资
            
            $link = "<a href='javascript:void(0)' onclick='itemEdit(\"{$row['summary_id']}\",\"{$row['user_id']}\",\"{$row['wage_date']}\",\"{$row['user_name']}\")'><i class=\"fa fa-fw fa-edit\"></i>".Yii::t('pay_payroll', 'edit_wage')."</a>";    //详细工资
        }
        else {
//            $link = "<a ><i class=\"fa fa-fw fa-edit\"></i>".Yii::t('common', 'edit')."</a>";
            $link = '';
        }
        
        $t->echo_td(Utils::WorkDateToEn($row['wage_date']));
        $t->echo_td($row['user_name']);
        $t->echo_td($row['wage']);
        $t->echo_td($row['work_hours']);
        $t->echo_td($row['basic_wage']);
        $t->echo_td($row['wage_overtime']);
        $t->echo_td($row['overtime_hours']);
        $t->echo_td($row['overtime_wage']);
        $t->echo_td($row['allowance']);
        $t->echo_td($row['allowance_content']);
//        $t->echo_td($row['deduction_wage']);
        $t->echo_td($row['total_wage']);
        $t->echo_td(Utils::DateToEn(substr($row['record_time'],0,10)));
//       $t->echo_td("<div class='edit_work_hours' id='work_hours'>".$row['work_hours'].'</div>');
        
//       $t->echo_td("<div class='edit_overtime_hours' id='overtime_hours'>".$row['overtime_hours'].'</div>');
        $status = '<span class="label ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';

        $t->echo_td($status); //状态
//        $t->echo_td($link); //操作
       //$t->echo_td($row['record_time']);
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
<!--<script type="text/javascript" src="js/jquery.jeditable.mini.js"></script>-->
<script type="text/javascript">
jQuery(document).ready(function () {
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

