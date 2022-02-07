<?php
$t->echo_grid_header();
$status_list = PayrollWorkHour::statusText();//状态
$status_css = PayrollWorkHour::statusCss();

if (is_array($rows)) {
    $j = 1;
    $sum_work_hours = 0;
    $sum_overtime_hours = 0;
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
        
        if ($row['status'] != PayrollWorkHour::STATUS_CONFIRM ) { //状态是未确认或者异议 可以修改或添加
            
            $link = "<a href='javascript:void(0)' onclick='itemEdit(\"{$row['user_id']}\")'><i class=\"fa fa-fw fa-edit\"></i>".Yii::t('common', 'edit')."</a>";    //编辑
        }
        else {
//            $link = "<a ><i class=\"fa fa-fw fa-edit\"></i>".Yii::t('common', 'edit')."</a>";
            $link = '';
        }
        
        $t->echo_td(Utils::WorkDateToEn($row['working_date'])); 
        $t->echo_td($program_list[$row['program_id']]); 
        $t->echo_td($row['user_name']);
//       $t->echo_td("<div class='edit_work_hours' id='work_hours'>".$row['work_hours'].'</div>');
        
//       $t->echo_td("<div class='edit_overtime_hours' id='overtime_hours'>".$row['overtime_hours'].'</div>');
        $status = '<span class="label ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
        $t->echo_td(Utils::DateToEn(substr($row['record_time'],0,10)));
        $t->echo_td($status); //状态
        $t->echo_td($row['work_hours']);
        $t->echo_td($row['overtime_hours']);
//       $t->echo_td($link); //操作
       //$t->echo_td($row['record_time']);
       $t->end_row();
       $sum_work_hours +=$row['work_hours'];
       $sum_overtime_hours +=$row['overtime_hours'];
    }
    if($sum_work_hours!=0 || $sum_overtime_hours!=0){         
        $t->echo_td(Yii::t('pay_payroll', 'attend_hour_total'),'center',array('colspan'=>'5'));
        $t->echo_td($sum_work_hours);
        $t->echo_td($sum_overtime_hours);
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
            <?php if($rows){ ?>
            
                <a class="right" style="cursor: pointer;"  id="export"><strong onclick="itemExport();"><?php echo Yii::t('pay_payroll', 'Staff_workhour_export');?></strong></a>
            
            <?php } ?>
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

