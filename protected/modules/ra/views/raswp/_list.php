<?php
$t->echo_grid_header();

if (is_array($rows)) {
    $j = 1;

    $status_list = RaBasic::statusText(); //状态text
    $status_css = RaBasic::statusCss(); //状态css
    $worker_type = WorkerType::getType();
    $company_list = Contractor::compAllList();//承包商公司列表
    foreach ($rows as $i => $row) {

        $num = ($curpage - 1) * $this->pageSize + $j++;
        $app_id = 'RA';
        if($row['status'] == 4){    //审批驳回后
            $link = "<table><tr><td style='white-space: nowrap' align='left'><a href='javascript:void(0)' onclick='itemExport(\"{$row['ra_swp_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>" . Yii::t('common', 'download') . "</a></td><td style='white-space: nowrap' align='left'><a href='javascript:void(0)' onclick='itemAttachment(\"{$row['ra_swp_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-edit\"></i>" . Yii::t('comp_ra', 'attachment') . "</a></td></tr></table>";
        }else if($row['status'] == 2){
            $link = "<table><tr><td style='white-space: nowrap' align='left'><a href='javascript:void(0)' onclick='itemAttachment(\"{$row['ra_swp_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-edit\"></i>" . Yii::t('comp_ra', 'attachment') . "</a></td></tr></table>";
        }else if($row['status'] == 3){
            $link = "<table><tr><td style='white-space: nowrap' align='left'><a href='javascript:void(0)' onclick='itemExport(\"{$row['ra_swp_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>" . Yii::t('common', 'download') . "</a></td><td style='white-space: nowrap' align='left'><a href='javascript:void(0)' onclick='itemAttachment(\"{$row['ra_swp_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-edit\"></i>" . Yii::t('comp_ra', 'attachment') . "</a></td></tr></table>";
        }else if($row['status'] == 1){
            $link = "<table><tr><td style='white-space: nowrap' align='left'><a href='javascript:void(0)' onclick='itemAttachment(\"{$row['ra_swp_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-edit\"></i>" . Yii::t('comp_ra', 'attachment') . "</a></td></tr></table>";
        }else{
            $link = "<table><tr><td style='white-space: nowrap' align='left'><a href='javascript:void(0)' onclick='itemAttachment(\"{$row['ra_swp_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-edit\"></i>" . Yii::t('comp_ra', 'attachment') . "</a></td></tr></table>";
        }
        
        
        $t->echo_td($num,'center');
        $program_info = Program::model()->findByPk($row['program_id']);
        $t->echo_td($program_info->program_name,'center');
        $t->echo_td($company_list[$row['contractor_id']],'center');
        $t->echo_td($worker_type[$row['work_type']],'center');
        $t->echo_td($row['title'],'center');
        $t->echo_td(substr(Utils::DateToEn($row['record_time']),0,11),'center');
        $t->echo_td($row['valid_time'],'center');
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
    <div class="col-xs-6">
        <div class="dataTables_info" id="example2_info">
            <?php echo Yii::t('common', 'page_total'); ?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt'); ?>
            <?php if($rows){ ?>

                <a class="right" style="cursor: pointer;"  id="export"><strong onclick="itemExcel();"><?php echo Yii::t('common', 'button_export');?></strong></a>

            <?php } ?>
        </div>
    </div>
    <div class="col-xs-6">
        <div class="dataTables_paginate paging_bootstrap">
            <?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>

