<?php
$t->echo_grid_header();
$contractor_list = Contractor::compList();//承包商列表
if (is_array($rows)) {
    $j = 1;
    $len = count($rows);
//    for($k=0;$k<=$len;$k++)
//    {
//        for($j=$len-1;$j>$k;$j--){
//            if($rows[$j]['total_size']<$rows[$j-1]['total_size']){
//                $temp = $rows[$j];
//                $rows[$j] = $rows[$j-1];
//                $rows[$j-1] = $temp;
//            }
//        }
//    }

    foreach ($rows as $i => $row) {

        $t->begin_row("onclick", "getDetail(this,'{$row['contractor_id']}');");
        $t->echo_td($contractor_list[$row['contractor_id']]);
        $t->echo_td($row['flow_pic_size']);
        $t->echo_td($row['attribute_size']);
        $t->echo_td($row['document_size']);
        $t->echo_td($row['flow_pic_size']+$row['attribute_size']+$row['document_size']);
//        $t->echo_td($row['max_size']);
        $t->echo_td(Utils::DateToEn($row['statistics_date']));
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

