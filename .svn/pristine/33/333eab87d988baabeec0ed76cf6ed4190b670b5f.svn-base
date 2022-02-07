<style type="text/css">
    .format1{
        list-style:none; padding:0px; margin:0px; width:200px; float: left;
    }
    .format2{ width:50%; display:inline-block; float: left; padding-left: 0}
</style>
<?php
$t->echo_grid_header();

if (is_array($rows)) {
    $j = 1;

    $status_list = Meeting::statusText(); //状态text
    $status_css = Meeting::statusCss(); //状态css
    $program_list =  Program::programAllList();
    $type_list = TrainType::typeText();
    foreach ($rows as $i => $row) {

        $num = ($curpage - 1) * $this->pageSize + $j++;
        $app_id = 'TRAIN';
        $download_link = "<a href='javascript:void(0)' onclick='itemDownloadView(\"{$row['training_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>" . Yii::t('license_licensepdf', 'download') . "</a>";
        $attachment_link = "<a href='javascript:void(0)' onclick='itemAttachment(\"{$row['training_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>" . Yii::t('proj_project', 'attachment') . "</a>";
        $workflow_link =  "<a href='javascript:void(0)' onclick='itemWorkflow(\"{$row['training_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-bar-chart-o\"></i>" . Yii::t('sys_workflow', 'Approval Process') . "</a>";
        $preview_link =  "<a href='javascript:void(0)' onclick='itemPreview(\"{$row['training_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-file-text-o\"></i>Preview</a>";
        $form_data_list = TrainDocument::detailList($row['training_id']); //记录
        if($row['status'] == 1){    //完成后
            if(count($form_data_list) > 0){
                $link = "<ul class='format1'><li class='format2'>$download_link</li><li class='format2'>$preview_link</li><li class='format2'>$attachment_link</li></ul>";
            }else{
                $link = "<ul class='format1'><li class='format2'>$download_link</li><li class='format2'>$preview_link</li></ul>";
            }
        }else{
            $link = "<ul class='format1'><li class='format2'>$workflow_link</li></ul>";
        }

        $t->echo_td($num,'center');
        $t->echo_td($program_list[$row['main_proid']],'center');
        $t->echo_td($row['title'],'center');
        $t->echo_td($type_list[$row['type_id']],'center');
        //$t->echo_td($row['record_time']); 
        $t->echo_td(Utils::DateToEn($row['record_time']),'center');
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