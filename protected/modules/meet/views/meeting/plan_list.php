<?php
$t->echo_grid_header();

if (is_array($rows)) {
    $j = 1;
    $status_list = TrainSafetyPromote::statusText();//状态
    $status_css = TrainSafetyPromote::statusCss();
    $program_list =  Program::programAllList();
    foreach ($rows as $i => $row) {

        $num = ($curpage - 1) * $this->pageSize + $j++;
        if ($row['status'] == Device::STATUS_NORMAL ) { //状态正常

            $link = "<a href='javascript:void(0)' onclick='itemEdit(\"{$row['id']}\")'><i class=\"fa fa-fw fa-edit\"></i>".Yii::t('common', 'edit')."</a>";    //编辑
            $link .= "&nbsp;<a href='javascript:void(0)' onclick='itemDelete(\"{$row['id']}\")'><i class=\"fa fa-fw fa-random\"></i>".Yii::t('common', 'delete')."</a>";   //删除
        }
        else {
            $link = '';
        }
        
        
        $t->echo_td($num,'center');
        $t->echo_td($program_list[$row['program_id']],'center');
        $t->echo_td($row['items'],'center');
        $t->echo_td($row['schedule']);
        $t->echo_td($row['actual']);
        $t->echo_td(Utils::DateToEn($row['record_time']));
        $status = '<span class="label ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
        $t->echo_td($status,'center'); //状态
        $t->echo_td($link,'center'); //操作
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
            <?php echo Yii::t('common', 'page_total'); ?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt'); ?>
        </div>
    </div>
    <div class="col-xs-9">
        <div class="dataTables_paginate paging_bootstrap">
            <?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>

