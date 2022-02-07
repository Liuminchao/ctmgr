<?php
$t->echo_compound_header();
$status_list = Staff::statusText();//状态
$status_css = Staff::statusCss();
$contractor_list = Contractor::compList();//承包商列表
if (is_array($rows)) {
    $j = 1;

    $tool = true;
    //$tool = false;验证权限
    if (Yii::app()->user->checkAccess('mchtm')) {
        $tool = true;
    }
    $roleList = Role::roleList();
    $mc_sum = 0;
    $ptw_sum = 0;
    $tbm_sum =0;
    $ins_sum =0;
    $met_sum=0;
    $ra_sum=0;
    $checklist_sum=0;
    $train_sum=0;
    $incident_sum=0;
//    $file_sum=0;
    $tbm_staffs = 0;
    $met_staffs =0;
    $train_staffs =0;
//    $file_size=0;
    $default = (string)0;
    foreach ($rows as $i => $row) {

        $t->begin_row("onclick", "getDetail(this,'{$row['con_id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;
        $mc_sum++;
        $t->echo_td($row['program_name']);
        $t->echo_td($contractor_list[$row['con_id']],'left');
        $t->echo_td($row['ptw_cnt']);
        $ptw_sum += $row['ptw_cnt'];
        $t->echo_td($row['ptw_pcnt']);
        $ptw_staffs += $row['ptw_pcnt'];
        $t->echo_td($row['tbm_cnt']);
        $tbm_sum += $row['tbm_cnt'];
        $t->echo_td($row['tbm_pcnt']);
        $tbm_staffs += $row['tbm_pcnt'];
        $t->echo_td($row['che_cnt']);
        $checklist_sum += $row['che_cnt'];
        $t->echo_td($row['ins_cnt']);
        $ins_sum += $row['ins_cnt'];
        $t->echo_td($row['mee_cnt']);
        $met_sum += $row['mee_cnt'];
        $t->echo_td($row['mee_pcnt']);
        $met_staffs += $row['mee_pcnt'];
        $t->echo_td($row['tra_cnt']);
        $train_sum += $row['tra_cnt'];
        $t->echo_td($row['tra_pcnt']);
        $train_staffs += $row['tra_pcnt'];
        $t->echo_td($row['ra_cnt']);
        $ra_sum += $row['ra_cnt'];
        $t->echo_td($row['inc_cnt']);
        $incident_sum += $row['inc_cnt'];
//        $t->echo_td($row['file_cnt']);
//        $file_sum += $row['file_cnt'];
//        $t->echo_td($row['file_size']);
//        $file_size += $row['file_size'];
        $t->end_row();


    }
    if($mc_sum>0) {
        $t->echo_td(Yii::t('comp_statistics', 'day_toatl'));
        $t->echo_td($mc_sum);
        $t->echo_td($ptw_sum);
        $t->echo_td($ptw_staffs);
        $t->echo_td($tbm_sum);
        $t->echo_td($tbm_staffs);
        $t->echo_td($checklist_sum);
        $t->echo_td($ins_sum);
        $t->echo_td($met_sum);
        $t->echo_td($met_staffs);
        $t->echo_td($train_sum);
        $t->echo_td($train_staffs);
        $t->echo_td($ra_sum);
        $t->echo_td($incident_sum);
//        $t->echo_td($file_sum);
//        $t->echo_td($file_size);
    }
}

$t->echo_grid_floor();

$pager = new CPagination($cnt);
$pager->pageSize = $this->pageSize;
$pager->itemCount = $cnt;
?>

<div class="row">

    <div class="col-xs-6">
        <div class="dataTables_info" id="example2_info">
            <?php echo Yii::t('common', 'page_total');?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt');?>
        </div>
    </div>
<!--    <div class="col-xs-6">-->
<!--        <div class="dataTables_paginate paging_bootstrap">-->
<!--            --><?php //$this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
<!--        </div>-->
<!--    </div>-->
</div>
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

