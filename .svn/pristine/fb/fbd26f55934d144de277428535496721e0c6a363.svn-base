<?php
$t->echo_grid_header();
if (is_array($rows)) {
    $j = 1;

    $status_list = SafetyCheck::statusText(); //状态text
    $status_css = SafetyCheck::statusCss(); //状态css
    $company_list = Contractor::compAllList();//承包商公司列表
    $type_list = SafetyCheckType::typeText();//安全类型详情
    $staff_list = Staff::userAllList();//所有人员列表
    foreach ($rows as $i => $row) {

        // $t->begin_row("onclick", "getDetail(this,'{$row['apply_id']}');");
        $t->echo_td($j); //序号
        $num = ($curpage - 1) * $this->pageSize + $j++;

        $t->echo_td($row['root_proname']); //总包名称
        $t->echo_td($row['title']); //标题
        $t->echo_td($type_list[$row['type_id']]);//安全类型
        $apply_user =  Staff::model()->findAllByPk($row['apply_user_id']);//申请人
        $t->echo_td($apply_user[0]['user_name']);//发起人姓名
        $person_in_charge = Staff::model()->findAllByPk($row['person_in_charge_id']);//负责人
        $t->echo_td($person_in_charge[0]['user_name']);//发起人姓名
        $record_list = ViolationRecord::recordList($row['check_id']);//违规记录
        $violations_user = '';
        foreach($record_list as $n => $m){
            $violations_user .= $staff_list[$m['user_id']].',';
        }
        if ($violations_user != '')
            $violations_user = substr($violations_user, 0, strlen($violations_user) - 1);

        $t->echo_td($violations_user);//责任人姓名
        $t->echo_td($company_list[$row['contractor_id']]); //公司
        $t->echo_td($row['safety_level']); //安全等级
//        $t->echo_td($row['Violation_record']); //违规记录
        $t->echo_td(Utils::DateToEn($row['apply_time']));//申请时间
        $t->echo_td(Utils::DateToEn($row['stipulation_time'])); //规定时间
//        $t->echo_td(Utils::DateToEn($row['apply_time']));

        $status = '<span class="label ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
        
        $t->echo_td($status); //状态
        $download_link = "<a href='javascript:void(0)' onclick='itemDownload(\"{$row['check_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>" . Yii::t('license_licensepdf', 'download') . "</a>";
        $preview_link = "<a href='javascript:void(0)' onclick='itemPreview(\"{$row['check_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-bar-chart-o\"></i>" . Yii::t('sys_workflow', 'Approval Process') . "</a>";
//        $detail_link = "<a href='javascript:void(0)' onclick='itemDetail(\"{$row['apply_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-file-text-o\"></i>" . Yii::t('sys_workflow', 'detail') . "</a>";
//        $staff_link ="<a href='javascript:void(0)' onclick='itemStaff(\"{$row['apply_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-users\"></i>" . Yii::t('sys_workflow', 'construction personnel') . "</a>";
        $link = "";
        if($row['status'] == 1 || $row['status'] == 2){    //完成后
            $link .= "<table><tr><td style='white-space: nowrap'>$download_link</td></tr></table>";
        }


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

