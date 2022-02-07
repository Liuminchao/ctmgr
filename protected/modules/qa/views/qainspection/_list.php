<?php
$t->echo_grid_header();
if (is_array($rows)) {
    $j = 1;

    $status_list = QaCheck::statusText(); //状态text
    $status_css = QaCheck::statusCss(); //状态css
    $check_type = RoutineCheckType::checkType();//检查类型列表
    $check_kind = RoutineCheckType::checkKind();//检查种类列表
    $company_list = Contractor::compAllList();//承包商公司列表
    $staff_list = Staff::userAllList();//所有人员列表
    $form_list = QaChecklist::formList();//form集合
    $type_list = QaCheckType::checkType();//type集合
    $args = array();
    $program_list = Program::programList($args);
    foreach ($rows as $i => $row) {

        // $t->begin_row("onclick", "getDetail(this,'{$row['apply_id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;

        $t->echo_td($row['check_id']); //检查单编号
        $t->echo_td($program_list[$row['project_id']]); //总包名称
//        $form_model = QaFormDataReal::model()->findByPk($row['form_data_id']);
//        $form_id = $form_model->form_id;
//        $type_id = $form_model->type_id;

//        $t->echo_td($type_list[$type_id]); //检查类型
//        $t->echo_td($form_list[$form_id]); //表单类型

        $apply_user =  Staff::model()->findAllByPk($row['apply_user_id']);//申请人
        $t->echo_td($apply_user[0]['user_name']);//发起人姓名
        $t->echo_td($row['title']); //公司
//        $t->echo_td(Utils::DateToEn($row['apply_date']));//申请时间
        $t->echo_td(Utils::DateToEn($row['apply_time']),'center'); //规定时间
//        $t->echo_td(Utils::DateToEn($row['apply_time']));

        $status = '<span class="label ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
        
        $t->echo_td($status,'center'); //状态
       
        $download_link = "<a href='javascript:void(0)' onclick='itemDownload(\"{$row['check_id']}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>" . Yii::t('license_licensepdf', 'download') . "</a>";
        $attachment_link = "<a href='javascript:void(0)' onclick='itemDownloadAttachment(\"{$row['check_id']}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>" . Yii::t('comp_qa', 'attachment') . "</a>";
        $upload_link = "<a href='javascript:void(0)' onclick='itemUpload(\"{$row['check_id']}\",\"{$program_id}\")'><i class=\"fa fa-fw fa-upload\"></i>" . Yii::t('task', 'upload') . "</a>";
        //        $detail_link = "<a href='javascript:void(0)' onclick='itemDetail(\"{$row['apply_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-file-text-o\"></i>" . Yii::t('sys_workflow', 'detail') . "</a>";
//        $staff_link ="<a href='javascript:void(0)' onclick='itemStaff(\"{$row['apply_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-users\"></i>" . Yii::t('sys_workflow', 'construction personnel') . "</a>";
        $link = "";
//        if($row['status'] === '1'){    //完成后
            $link .= "<table><tr><td style='white-space: nowrap'>$download_link</td><td style='white-space: nowrap'>$attachment_link</td></tr><tr><td style='white-space: nowrap'>$upload_link</td></tr></table>";
//        }
//        else{
//            $link .=  "<table><tr><td style='white-space: nowrap'>$preview_link</td></tr></table>";
//        }

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

