<?php
$operator_role = Operator::roleText();
$t->echo_grid_header();

if (is_array($rows)) {
    $j = 1;

    foreach ($rows as $i => $row) {

        $t->begin_row("onclick", "getDetail(this,'{$row['contractor_id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;

        $set_link = "<a href='javascript:void(0)' onclick='itemSet(\"{$row['operator_id']}\",\"{$row['contractor_id']}\",\"{$name}\")' ><i class=\"fa fa-fw fa-eye\"></i>" . Yii::t('comp_staff', 'Binding') . "</a>&nbsp;";
        $delete_link = "<a href='javascript:void(0)' onclick='itemDelete(\"{$row['operator_id']}\",\"{$row['contractor_id']}\",\"{$name}\")'><i class=\"fa fa-fw fa-times\"></i>" . Yii::t('common', 'delete') . "</a>";
        $pro_link = "<a href='javascript:void(0)' onclick='itemPro(\"{$row['operator_id']}\",\"{$row['contractor_id']}\",\"{$name}\")'><i class=\"fa fa-fw fa-cog\"></i>". Yii::t('proj_project','Project Set') ."</a>";

        $t->echo_td($row['operator_id'],'center');
        $t->echo_td($row['name'],'center');
        $t->echo_td($operator_role[$row['operator_role']],'center');
        $t->echo_td(Utils::DateToEn(substr($row['record_time'],0,10)),'center');
        if($row['operator_role'] == '01'){
            $link = "<table><tr><td style='white-space: nowrap'>$set_link</td><td style='white-space: nowrap'>$pro_link</td></tr><tr><td style='white-space: nowrap'>$delete_link</td></tr></table>";
        }else{
            $link = "<table><tr><td style='white-space: nowrap'>$set_link</td><td style='white-space: nowrap'>$pro_link</td></tr></table>";
        }

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

