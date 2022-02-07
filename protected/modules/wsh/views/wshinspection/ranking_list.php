<?php
$t->echo_grid_header();
if (is_array($rows_2)) {
    $j = 1;
    $company_list = Contractor::compAllList();//承包商公司列表
    $type_list = SafetyCheckType::typeText();//安全类型详情
    $staff_list = Staff::userAllList();//所有人员列表
    foreach ($rows_2 as $i => $row) {
        $person_in_charge_name=Staff::userById($row['person_in_charge_id']);
        // $t->begin_row("onclick", "getDetail(this,'{$row['apply_id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;
        $t->echo_td($end_date,'center'); //日期
        $t->echo_td($num,'center'); //序号
        if(is_array($max)) {
            if (in_array($row['user_name'], $max)) {
                $t->echo_td($row['user_name'] . '  ['.Yii::t('comp_safety','rank_alert').']', 'center'); //员工姓名
            } else {
                $t->echo_td($row['user_name'], 'center'); //员工姓名
            }
        }else{
            $t->echo_td($row['user_name'], 'center'); //员工姓名
        }
        $t->echo_td($row['count'],'center'); //违规次数
        $t->echo_td($person_in_charge_name, 'center'); //负责人姓名

        $t->end_row();
    }

}

$t->echo_grid_floor();

$pager = new CPagination($cnt_2);
$pager->pageSize = $this->pageSize;
$pager->itemCount = $cnt_2;
?>
<?php
$t->echo_grid_header();
if (is_array($rows_1)) {
    $j = 1;

    $company_list = Contractor::compAllList();//承包商公司列表
    $type_list = SafetyCheckType::typeText();//安全类型详情
    $staff_list = Staff::userAllList();//所有人员列表
    foreach ($rows_1 as $i => $row) {
        $person_in_charge_name=Staff::userById($row['person_in_charge_id']);
        // $t->begin_row("onclick", "getDetail(this,'{$row['apply_id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;
        $t->echo_td($start_date,'center'); //日期
        $t->echo_td($num,'center'); //序号
        if(is_array($max)) {
            if (in_array($row['user_name'], $max)) {
                $t->echo_td($row['user_name'], 'center'); //员工姓名
            } else {
                $t->echo_td($row['user_name'], 'center'); //员工姓名
            }
        }else{
            $t->echo_td($row['user_name'], 'center'); //员工姓名
        }
        $t->echo_td($row['count'],'center'); //违规次数
        $t->echo_td($person_in_charge_name, 'center'); //负责人姓名
        $t->end_row();
    }

}

$t->echo_grid_floor();

$pager = new CPagination($cnt_1);
$pager->pageSize = $this->pageSize;
$pager->itemCount = $cnt_1;
?>

<!--<div class="row">-->
<!--    <div class="col-xs-3">-->
<!--        <div class="dataTables_info" id="example2_info">-->
<!--            --><?php //echo Yii::t('common', 'page_total'); ?><!-- --><?php //echo $cnt; ?><!-- --><?php //echo Yii::t('common', 'page_cnt'); ?>
<!--        </div>-->
<!--    </div>-->
<!--    <div class="col-xs-9">-->
<!--        <div class="dataTables_paginate paging_bootstrap">-->
<!--            --><?php //$this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

