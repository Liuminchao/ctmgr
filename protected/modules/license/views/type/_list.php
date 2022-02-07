<?php
$t->echo_grid_header();

if (is_array($rows)) {
    $j = 1;


    $status_list = PtwType::statusText(); //状态
    $status_css = PtwType::statusCss();


    foreach ($rows as $i => $row) {

        $t->begin_row("onclick", "getDetail(this,'{$row['type_id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;

        $copy_link = "<a href='javascript:void(0)' onclick='itemCopy(\"{$row['type_id']}\")'><i class=\"fa fa-fw fa-edit\"></i>" . Yii::t('common', 'copy') . "</a>&nbsp;";
        $start_link = "<a href='javascript:void(0)' onclick='itemStart(\"{$row['type_id']}\",\"{$row['type_name']}\")'><i class=\"fa fa-fw fa-check\"></i>" . Yii::t('common', 'start') . "</a>&nbsp;";
        $stop_link = "<a href='javascript:void(0)' onclick='itemStop(\"{$row['type_id']}\",\"{$row['type_name']}\")'><i class=\"fa fa-fw fa-times\"></i>" . Yii::t('common', 'stop') . "</a>&nbsp;";
        $detail_link = "<a href='javascript:void(0)' onclick='itemDetail(\"{$row['type_id']}\")'><i class=\"fa fa-fw fa-list-alt\"></i>" . Yii::t('sys_workflow', 'detail') . "</a>&nbsp;";

        if ($row['status'] == PtwType::STATUS_NORMAL) {
            $operator_type   =  Yii::app()->user->getState('operator_type');
            if($operator_type == '00'){
                if($row['contractor_id'] == 0){
                    $link =  "<table><tr><td style='white-space: nowrap' align='left'>$detail_link</td><td style='white-space: nowrap' align='left'>$stop_link</td></tr><tr><td style='white-space: nowrap' align='left'>$copy_link</td></tr></table>";
                }
            }else{
                if($row['contractor_id'] == 0){
//                $link = $copy_link;
                    $link =  "<table><tr><td style='white-space: nowrap' align='left'>$copy_link</td><td style='white-space: nowrap' align='left'>$detail_link</td></tr></table>";
                }else{
//                $link = $detail_link;
                    $link =  "<table><tr><td style='white-space: nowrap' align='left'>$detail_link</td><td style='white-space: nowrap' align='left'>$stop_link</td></tr><tr><td style='white-space: nowrap' align='left'>$copy_link</td></tr></table>";
                }
            }
        }
        else if ($row['status'] == PtwType::STATUS_STOP) {
            $link = $start_link;
        }

        $t->echo_td($row['type_id']); //Type
        $t->echo_td($row['type_name']); //Type Name
        $t->echo_td($row['type_name_en']); //Type Name En
        $status = '<span class="label ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
        $t->echo_td($status); //状态
        $t->echo_td(Utils::DateToEn($row['record_time'])); //Record Time
        $t->echo_td($link); //操作
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
<script type="text/javascript">
    //详情
    var itemDetail = function (id) {
        window.location = "index.php?r=license/condition/list&id=" + id;
    }
</script>

