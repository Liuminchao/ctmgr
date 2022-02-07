<?php
$t->echo_compound_header();
$status_list = Staff::statusText();//状态
$status_css = Staff::statusCss();
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
    $ptw_staffs = 0;
    $tbm_staffs = 0;
    $default = (string)0;
    foreach ($rows as $i => $row) {

        $t->begin_row("onclick", "getDetail(this,'{$row['contractor_id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;
        $mc_sum++;
//            $record_time = date('Y-m-d',strtotime('-1 day',strtotime($row['record_time'])));
            $t->echo_td(Utils::DateToEn($row['record_time']));
            $t->echo_td($row['contractor_name'],'left');
            $t->echo_td($row['ptw_sum']);
            $ptw_sum += $row['ptw_sum'];
            $t->echo_td($row['ptw_staffs']);
            $ptw_staffs += $row['ptw_staffs'];
            $t->echo_td($row['tbm_sum']);
            $tbm_sum += $row['tbm_sum'];
            $t->echo_td($row['tbm_staffs']);
            $tbm_staffs += $row['tbm_staffs'];
//            $t->echo_td($row['ins_sum']);
//            $ins_sum += $row['ins_sum'];
//            $t->echo_td($row['ins_staffs']);
//            $ins_staffs += $row['ins_staffs'];
//            $t->echo_td($row['met_sum']);
//            $met_sum += $row['met_sum'];
//            $t->echo_td($row['met_staffs']);
//            $met_staffs += $row['met_staffs'];
//            $t->echo_td($row['train_sum']);
//            $train_sum += $row['train_sum'];
//            $t->echo_td($row['train_staffs']);
//            $train_staffs += $row['train_staffs'];
//            $t->echo_td($row['ra_sum']);
//            $ra_sum += $row['ra_sum'];
//            $t->echo_td($row['ra_staffs']);
//            $ra_staffs += $row['ra_staffs'];
//            $t->echo_td($row['checklist_sum']);
//            $checklist_sum += $row['checklist_sum'];
//            $t->echo_td($row['checklist_staffs']);
//            $checklist_staffs += $row['checklist_staffs'];
//            $t->echo_td($row['incident_sum']);
//            $incident_sum += $row['incident_sum'];
//            $t->echo_td($row['incident_staffs']);
//            $incident_staffs += $row['incident_staffs'];
//            $t->echo_td($row['qa_sum']);
//            $qa_sum += $row['qa_sum'];
//            $t->echo_td($row['qa_staffs']);
//            $qa_staffs += $row['qa_staffs'];
            $t->echo_td($default);
            $t->echo_td($default);
            $t->echo_td($default);
            $t->echo_td($default);
            $t->echo_td($default);
            $t->echo_td($default);
            $t->echo_td($default);
            $t->echo_td($default);
            $t->echo_td($default);
            $t->echo_td($default);
            $t->echo_td($default);
            $t->echo_td($default);
            $t->echo_td($default);
            $t->echo_td($default);
            $t->end_row();


    }
    if($mc_sum>0) {
        $t->echo_td(Yii::t('comp_statistics', 'day_toatl'));
        $t->echo_td($mc_sum);
        $t->echo_td($ptw_sum);
        $t->echo_td($ptw_staffs);
        $t->echo_td($tbm_sum);
        $t->echo_td($tbm_staffs);
//        $t->echo_td($ins_sum);
//        $t->echo_td($ins_staffs);
//        $t->echo_td($met_sum);
//        $t->echo_td($met_staffs);
//        $t->echo_td($train_sum);
//        $t->echo_td($train_staffs);
//        $t->echo_td($ra_sum);
//        $t->echo_td($ra_staffs);
//        $t->echo_td($checklist_sum);
//        $t->echo_td($checklist_staffs);
//        $t->echo_td($incident_sum);
//        $t->echo_td($incident_staffs);
//        $t->echo_td($qa_sum);
//        $t->echo_td($qa_staffs);
        $t->echo_td($default);
        $t->echo_td($default);
        $t->echo_td($default);
        $t->echo_td($default);
        $t->echo_td($default);
        $t->echo_td($default);
        $t->echo_td($default);
        $t->echo_td($default);
        $t->echo_td($default);
        $t->echo_td($default);
        $t->echo_td($default);
        $t->echo_td($default);
        $t->echo_td($default);
        $t->echo_td($default);
    }
}

$t->echo_grid_floor();

//$pager = new CPagination($cnt);
//$pager->pageSize = $this->pageSize;
//$pager->itemCount = $cnt;
?>
<div class="row">
    <div class="col-xs-6">
        <?php if (count($rows)>0) { ?>
            <a class="right" id="export"><strong onclick="itemExport();"><?php echo Yii::t('comp_statistics', 'export');  ?></strong></a>
        <?php } ?>
    </div>
</div>
<!--<div class="row">-->
<!--    <div class="col-xs-6">-->
<!--        <div class="dataTables_info" id="example2_info">-->
<!--            --><?php //echo Yii::t('common', 'page_total');?><!-- --><?php //echo $cnt; ?><!-- --><?php //echo Yii::t('common', 'page_cnt');?>
<!--        </div>-->
<!--    </div>-->
<!--    <div class="col-xs-6">-->
<!--        <div class="dataTables_paginate paging_bootstrap">-->
<!--            --><?php //$this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
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
//导出PDF
var itemExport = function () {
    var objs = document.getElementById("_query_form").elements;
    var i = 0;
    var cnt = objs.length;
    var obj;
    var url = '';

    for (i = 0; i < cnt; i++) {
        obj = objs.item(i);
        url += '&' + obj.name + '=' + obj.value;
    }
    window.location = "index.php?r=statistics/conmodule/export"+url;
}
</script>

