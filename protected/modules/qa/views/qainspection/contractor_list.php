<?php
$t->echo_grid_header();
if (is_array($rows)) {
    $j = 1;

    $status_list = RoutineCheck::statusText(); //״̬text
    $status_css = RoutineCheck::statusCss(); //״̬css
    $check_type = RoutineCheckType::checkType();//��������б�
    foreach ($rows as $i => $row) {

        // $t->begin_row("onclick", "getDetail(this,'{$row['apply_id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;

        $t->echo_td($row['check_id']); //��鵥���
        //$t->echo_td($row['root_proname']); //�ܰ�����
        $t->echo_td($check_type[$row['type_id']]); //�������
//        $t->echo_td(Utils::DateToEn($row['apply_date']));//����ʱ��
        $t->echo_td(Utils::DateToEn($row['apply_time'])); //�涨ʱ��
//        $t->echo_td(Utils::DateToEn($row['apply_time']));

        $status = '<span class="label ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
        
        $t->echo_td($status); //״̬
       
        $download_link = "<a href='javascript:void(0)' onclick='itemDownload(\"{$row['check_id']}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>" . Yii::t('license_licensepdf', 'download') . "</a>";
        $preview_link = "<a href='javascript:void(0)' onclick='itemPreview(\"{$row['check_id']}\")'><i class=\"fa fa-fw fa-bar-chart-o\"></i>" . Yii::t('sys_workflow', 'Approval Process') . "</a>";
//        $detail_link = "<a href='javascript:void(0)' onclick='itemDetail(\"{$row['apply_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-file-text-o\"></i>" . Yii::t('sys_workflow', 'detail') . "</a>";
//        $staff_link ="<a href='javascript:void(0)' onclick='itemStaff(\"{$row['apply_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-users\"></i>" . Yii::t('sys_workflow', 'construction personnel') . "</a>";
        $link = "";
        if($row['status'] == 1 || $row['status'] == 2){    //��ɺ�
            $link .= "<table><tr><td style='white-space: nowrap'>$download_link</td></tr></table>";
        }
        else{
            $link .=  "<table><tr><td style='white-space: nowrap'>$preview_link</td></tr></table>";
        }

        $t->echo_td($link); //����
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

