<?php
$t->echo_grid_header();

if (is_array($rows)) {
    $j = 1;

    $status_list = AccidentBasic::statusTxt(); //状态text
    $status_css = AccidentBasic::statusCss(); //状态css
    $program_list =  Program::programAllList();
    $type_list = AccidentType::typeList();//全部工种列表
    $company_list = Contractor::compAllList();//承包商公司列表
    foreach ($rows as $i => $row) {

        $num = ($curpage - 1) * $this->pageSize + $j++;
        $app_id = 'ACCI';
        if($row['status'] == 1){    //完成后
            $link = "<a href='javascript:void(0)' onclick='itemDownload(\"{$row['apply_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>" . Yii::t('license_licensepdf', 'download') . "</a>";
        }else{
            $link =  "<a href='javascript:void(0)' onclick='itemPreview(\"{$row['apply_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-bar-chart-o\"></i>" . Yii::t('comp_accident', 'detail') . "</a>&nbsp;";
            $link.= "<a href='javascript:void(0)' onclick='itemDownload(\"{$row['apply_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>" . Yii::t('license_licensepdf', 'download') . "</a>";
        }


        $t->echo_td($num,'center');
//        $t->echo_td($program_list[$row['root_proid']],'center');
        $t->echo_td($company_list[$row['apply_contractor_id']],'center');//Company Name
        $t->echo_td($row['title'],'center');
//        $t->echo_td($type_list[$row['type_id']]);
        $t->echo_td($type_list[$row['type_id']],'center');
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

