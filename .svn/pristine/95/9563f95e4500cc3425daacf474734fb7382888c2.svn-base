<style type="text/css">
    .format1{
        list-style:none; padding:0px; margin:0px; width:200px; float: left;
    }
    .format2{ width:50%; display:inline-block; float: left; padding-left: 0}
    #example2 td:nth-child(2){
        display: none;
    }
</style>
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
        $t->echo_td($row['check_id'],'center'); //序号
        $t->echo_td($j,'center'); //序号
        $num = ($curpage - 1) * $this->pageSize + $j++;

        $t->echo_td($row['title'],'center'); //标题
        $t->echo_td($type_list[$row['type_id']],'center');//安全类型
        $t->echo_td($row['findings_name_en'],'center');
        $apply_user =  Staff::model()->findAllByPk($row['apply_user_id']);//申请人
        $t->echo_td($apply_user[0]['user_name'],'center');//发起人姓名
        $person_in_charge = Staff::model()->findAllByPk($row['person_in_charge_id']);//负责人
        $t->echo_td($person_in_charge[0]['user_name'],'center');//发起人姓名
        $record_list = ViolationRecord::recordList($row['check_id']);//违规记录
        $violations_user = '';
        foreach($record_list as $n => $m){
            $violations_user .= $staff_list[$m['user_id']].',';
        }
        if ($violations_user != '')
            $violations_user = substr($violations_user, 0, strlen($violations_user) - 1);

        $t->echo_td($violations_user,'center');//责任人姓名
        //$t->echo_td($company_list[$row['contractor_id']],'center'); //公司
        //$t->echo_td($row['safety_level'],'center'); //安全等级
        //$t->echo_td($row['Violation_record']); //违规记录
        $t->echo_td(Utils::DateToEn($row['apply_time']),'center');//申请时间
        $t->echo_td(Utils::DateToEn($row['stipulation_time']),'center'); //规定时间
        //$t->echo_td(Utils::DateToEn($row['apply_time']));

        $status = '<span class="label ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';

        $t->echo_td($status,'center'); //状态

        //$download_link = "<a style='float: left' href='javascript:void(0)' onclick='itemDownload(\"{$row['check_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>" . Yii::t('license_licensepdf', 'download') . "</a>";
        //$download_link = "<a style='float: left' href='javascript:void(0)' onclick='itemDownload(\"{$row['check_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>" . Yii::t('license_licensepdf', 'download') . "</a>";
        $preview_link = "<a style='float: left' href='javascript:void(0)' onclick='itemPreview(\"{$row['check_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-file-text-o\"></i>" . Yii::t('electronic_contract', 'preview') . "</a>";
        $download_link = "<a style='float: left' href='javascript:void(0)' onclick='itemDownloadView(\"{$row['check_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>" . Yii::t('license_licensepdf', 'download') . "</a>";
        $attachment_link = "<a style='float: left' href='javascript:void(0)' onclick='itemAttachment(\"{$row['check_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>" . Yii::t('proj_project', 'attachment') . "</a>";
        //$detail_link = "<a href='javascript:void(0)' onclick='itemDetail(\"{$row['apply_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-file-text-o\"></i>" . Yii::t('sys_workflow', 'detail') . "</a>";
        //$staff_link ="<a href='javascript:void(0)' onclick='itemStaff(\"{$row['apply_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-users\"></i>" . Yii::t('sys_workflow', 'construction personnel') . "</a>";
        $form_data_list = SafetyDocument::detailList($row['check_id']); //记录
        $link = "";
        if(count($form_data_list) > 0){
            if($row['status'] == 0 ||$row['status'] == 1 || $row['status'] == 2 || $row['status'] == 99){    //完成后
                $link .= "<ul class='format1'><li class='format2'>$download_link</li><li class='format2'>$attachment_link</li><li class='format2'>$preview_link</li></ul>";
            }
        }else{
            if($row['status'] == 0 ||$row['status'] == 1 || $row['status'] == 2 || $row['status'] == 99){    //完成后
                $link .= "<ul class='format1'><li class='format2'>$download_link</li><li class='format2'>$preview_link</li></ul>";
            }
        }

        // else{
        //     $link .=  "<table><tr><td style='white-space: nowrap'>$preview_link</td></tr></table>";
        // }

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
    <div class="col-xs-8">
        <div class="dataTables_info" id="example2_info">
            <?php echo Yii::t('common', 'page_total'); ?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt'); ?>
            <?php if($rows){ ?>
                <button type='button' class='btn btn-primary btn-sm' style='margin-left: 10px' onclick='itemExport()'><?php echo Yii::t('common', 'button_export');?></button>
                <button type='button' class='btn btn-primary btn-sm' style='margin-left: 10px' onclick='itemExportpdf()'><?php echo Yii::t('comp_safety', 'merge_report');?></button>
                <button type='button' class='btn btn-primary btn-sm' style='margin-left: 10px' onclick="itemNcrExport('<?php echo $program_id; ?>')">NCR Report</button>
                <button type='button' class='btn btn-primary btn-sm' style='margin-left: 10px' onclick='itemGoodExcel()'>Good Practice Report</button>
                <?php
                $pro_model = Program::model()->findByPk($program_id);
                $pro_params = $pro_model->params;//项目参数
                if($pro_params != '0') {
                    $pro_params = json_decode($pro_params, true);
                    //判断是否是迁移的
                    if (array_key_exists('ins_mode', $pro_params)) {
                        ?>
                            <button type="button" class="btn btn-primary btn-sm"  onclick="itemShsdMonthExport('<?php echo $program_id; ?>');">Monthly Safety Data Report</button>
                    <?php }
                }?>
            <?php } ?>
        </div>
    </div>
    <div class="col-xs-4">
        <div class="dataTables_paginate paging_bootstrap">
            <?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>
<script src="js/loading.js"></script>
<script type="text/javascript">
    //批量导PDF
    var itemExportpdf = function () {
        var tbodyObj = document.getElementById('example2');
        var tag = '';
        $("table :checkbox").each(function(key,value){
            if(key != 0) {
                if ($(value).prop('checked')) {
                    var check_id = tbodyObj.rows[key].cells[1].innerHTML;
                    tag += check_id + '|';
                }
            }
        })
        if(tag.length == 0){
            alert('<?php echo Yii::t('comp_safety', 'error_tag_is_null'); ?>');
            return false;
        }
        tag=(tag.substring(tag.length-1)=='|')?tag.substring(0,tag.length-1):tag;
        alert('<?php echo Yii::t('comp_safety', 'confirm_batch_export'); ?>');
        $.ajax({
            data: {tag:tag},
            url: "index.php?r=wsh/compose/merge",
            type: "POST",
            dataType: "json",
            beforeSend: function () {
                addcloud(); //为页面添加遮罩
            },
            success: function (data) {
                var form = $("<form>");   //定义一个form表单
                form.attr('style', 'display:none');   //在form表单中添加查询参数
                form.attr('target', '');
                form.attr('method', 'post');
                form.attr('action', "index.php?r=wsh/compose/download");

                var input1 = $('<input>');
                input1.attr('type', 'hidden');
                input1.attr('name', 'filename');
                input1.attr('value', data.filename);
                $('body').append(form);  //将表单放置在web中
                form.append(input1);   //将查询参数控件提交到表单上
                removecloud();//去遮罩
                form.submit();
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
    jQuery(document).ready(function () {

        function initTableCheckbox() {
            var $thr = $('#example2 thead tr');
            var $checkAllTh = $('<th><input type="checkbox" id="checkAll" name="checkAll" /></th>');
            /*将全选/反选复选框添加到表头最前，即增加一列*/
            $thr.prepend($checkAllTh);
            /*“全选/反选”复选框*/
            var $checkAll = $thr.find('input');
            $checkAll.click(function (event) {
                /*将所有行的选中状态设成全选框的选中状态*/
                $tbr.find('input').prop('checked', $(this).prop('checked'));
                /*并调整所有选中行的CSS样式*/
                if ($(this).prop('checked')) {
                    $tbr.find('input').parent().parent().addClass('warning');
                } else {
                    $tbr.find('input').parent().parent().removeClass('warning');
                }
                /*阻止向上冒泡，以防再次触发点击操作*/
                event.stopPropagation();
            });
            /*点击全选框所在单元格时也触发全选框的点击操作*/
            $thr.click(function () {
                $(this).find('input').click();
            });
            var $tbr = $('#example2 tbody tr');
            var $checkItemTd = $('<td><input type="checkbox" name="checkItem" /></td>');
            /*每一行都在最前面插入一个选中复选框的单元格*/
            $tbr.prepend($checkItemTd);
            /*点击每一行的选中复选框时*/
            $tbr.find('input').click(function (event) {
                /*调整选中行的CSS样式*/
                $(this).parent().parent().toggleClass('warning');
                /*如果已经被选中行的行数等于表格的数据行数，将全选框设为选中状态，否则设为未选中状态*/
                $checkAll.prop('checked', $tbr.find('input:checked').length == $tbr.length ? true : false);
                /*阻止向上冒泡，以防再次触发点击操作*/
                event.stopPropagation();
            });
            /*点击每一行时也触发该行的选中操作*/
            $tbr.click(function () {
                $(this).find('input').click();
            });
        }

        initTableCheckbox();
    });
</script>

