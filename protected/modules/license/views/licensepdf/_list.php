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

    $status_list = ApplyBasic::statusText(); //状态text
    $status_css = ApplyBasic::statusCss(); //状态css
    $no_list = ApplyBasic::statusNo();//拒绝&&驳回text
    $company_list = Contractor::compAllList();//承包商公司列表
    $typeList = ApplyBasic::typeList();
    $ptw_type = ApplyBasic::typelanguageList();
    $detail_statustext = CheckApplyDetail::statusText();
    $app_id = 'PTW';
    foreach ($rows as $i => $row) {
        // $program_model = Program::model()->findByPk($row['program_id']);
        // if($program_model->params){
        //     $params = json_decode($program_model->params,true);
        // }else{
        //     $params['ptw_mode'] = 'A';
        // }
        // $t->begin_row("onclick", "getDetail(this,'{$row['apply_id']}');");
        $sql = "select count(*) as cnt from ptw_apply_basic where program_id = '".$row['program_id']."' and apply_id < '".$row['apply_id']."'";
        $command = Yii::app()->db->createCommand($sql);
        $s = $command->queryAll();
        $serial_no = $row['program_name'].'/'.'PTW'.'/'.$ptw_type[$row['type_id']]['short_type'].'/'.$s[0]['cnt'];

        $num = ($curpage - 1) * $this->pageSize + $j++;
        $t->echo_td($row['apply_id'],'center'); //Apply
        $t->echo_td($num,'center'); //Apply
        $t->echo_td($serial_no,'center'); //Program Name
        $t->echo_td($company_list[$row['apply_contractor_id']],'center');//Company Name
        $t->echo_td($row['title'],'center'); //title
        $t->echo_td($typeList[$row['type_id']],'center'); //title
        //$t->echo_td($row['add_con_name']); //Contractor Name
        //$t->echo_td($row['record_time']); //Apply Date
        $t->echo_td(Utils::DateToEn($row['record_time']),'center');
        //$t->echo_td($row['status']); //Status
        $ptw = ApplyBasic::model()->findByPk($row['apply_id']);
        $add_operator = $ptw->add_operator;
        $data_list = explode('|',$add_operator);
        $step = $data_list[0];
        $year = substr($ptw->record_time,0,4);
        $deal_type = CheckApplyDetail::dealtypeList($app_id, $row['apply_id'], $step,$year);
        if ($row['status'] == '2') {
            $status = '<span class="label ' . $status_css[$row['status']] . '">' . $no_list[$deal_type[0]['deal_type']] . '</span>';
        } else {
            if($deal_type[0]['status'] == 2){
                $status = '<span class="label ' . $status_css[$deal_type[0]['status']] . '">' . $no_list[$deal_type[0]['deal_type']] . '</span>';
            }else {
                $status = '<span class="label ' . $status_css[$row['status']] . '">' . $detail_statustext[$deal_type[0]['deal_type']] . '</span>';
            }
            // $status = '<span class="label ' . $status_css[$row['status']] . '">' . $no_list[$deal_type[0]['deal_type']] . '</span>';
        }

        $t->echo_td($status,'center'); //状态

        $download_link = "<a style='float: left' href='javascript:void(0)' onclick='itemDownload(\"{$row['apply_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>" . Yii::t('license_licensepdf', 'download') . "</a>";
        $preview_link = "<a style='float: left' href='javascript:void(0)' onclick='itemPreview(\"{$row['apply_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-file-text-o\"></i>Preview</a>";
        $workflow_link = "<a style='float: left' href='javascript:void(0)' onclick='itemWorkflow(\"{$row['apply_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-bar-chart-o\"></i>" . Yii::t('sys_workflow', 'Approval Process') . "</a>";
        $attachment_link = "<a style='float: left' href='javascript:void(0)' onclick='itemAttachment(\"{$row['apply_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>" . Yii::t('proj_project', 'attachment') . "</a>";
        //        $detail_link = "<a href='javascript:void(0)' onclick='itemDetail(\"{$row['apply_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-file-text-o\"></i>" . Yii::t('sys_workflow', 'detail') . "</a>";
        // $staff_link ="<a href='javascript:void(0)' onclick='itemStaff(\"{$row['apply_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-users\"></i>" . Yii::t('sys_workflow', 'construction personnel') . "</a>";
        $form_data_list = ApplyDocument::detailList($row['apply_id']); //记录
        $link = "";
        if(count($form_data_list) > 0){
            if ($row['status'] == 4) {    //完成后
                $link .= "<ul class='format1'><li class='format2'>$download_link</li><li class='format2'>$preview_link</li><li class='format2'>$attachment_link</li></ul>";
            }else if($row['status'] == 2){
                $app_id = 'PTW';
                $link .=  "<ul class='format1'><li class='format2'>$download_link</li><li class='format2'>$preview_link</li><li class='format2'>$workflow_link</li><li class='format2'>$attachment_link</li></ul>";
            }else{
                if($deal_type[0]['status'] == 2){
                    $app_id = 'PTW';
                    $link .=  "<ul class='format1'><li class='format2'>$download_link</li><li class='format2'>$preview_link</li><li class='format2'>$workflow_link</li><li class='format2'>$attachment_link</li></ul>";
                }else if($deal_type[0]['deal_type'] >= 2 && $deal_type[0]['deal_type']!=8) {
                    $app_id = 'PTW';
                    $link .=  "<ul class='format1'><li class='format2'>$download_link</li><li class='format2'>$preview_link</li><li class='format2'>$workflow_link</li><li class='format2'>$attachment_link</li></ul>";
                }else{
                    $app_id = 'PTW';
                    $link .=  "<ul class='format1'><li class='format2'>$workflow_link</li><li class='format2'>$preview_link</li></ul>";
                }

            }
        }else{
            if ($row['status'] == 4) {    //完成后
                $link .= "<ul class='format1'><li class='format2'>$download_link</li><li class='format2'>$preview_link</li></ul>";
            }else if($row['status'] == 2){
                $app_id = 'PTW';
                $link .=  "<ul class='format1'><li class='format2'>$download_link</li><li class='format2'>$preview_link</li><li class='format2'>$workflow_link</li></ul>";
            }else{
                if($deal_type[0]['status'] == 2){
                    $app_id = 'PTW';
                    $link .=  "<ul class='format1'><li class='format2'>$download_link</li><li class='format2'>$preview_link</li><li class='format2'>$workflow_link</li></ul>";
                }else if($deal_type[0]['deal_type'] >= 2 && $deal_type[0]['deal_type']!=8) {
                    $app_id = 'PTW';
                    $link .=  "<ul class='format1'><li class='format2'>$download_link</li><li class='format2'>$preview_link</li><li class='format2'>$workflow_link</li><li class='format2'>$disclaimer_link</li></ul>";
                }else{
                    $app_id = 'PTW';
                    $link .=  "<ul class='format1'><li class='format2'>$workflow_link</li><li class='format2'>$preview_link</li></ul>";
                }

            }
        }
        $disclaimer_link = "";
        if($ptw_mode == 'A' || $ptw_mode == 'B') {
            if ($status_tag == '0' && $operator_tag == '2') {
                $disclaimer_link = "<li class='format2'><a style='float: left' href='javascript:void(0)' onclick='itemDisclaimerOnly(\"1\",\"{$program_id}\",\"{$row['apply_id']}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>Disclaimer</a></li>";
            }
        }

        if($ptw_mode == 'B') {
            if ($status_tag == '1' && $operator_tag == '4') {
                $disclaimer_link = "<a style='float: left' href='javascript:void(0)' onclick='itemDisclaimerOnly(\"1\",\"{$program_id}\",\"{$row['apply_id']}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>Disclaimer</a>";
            }
        }

        if($ptw_mode == 'A') {
            if ($status_tag == '1' && $operator_tag == '3') {
                $disclaimer_link = "<a style='float: left' href='javascript:void(0)' onclick='itemDisclaimerOnly(\"2\",\"{$program_id}\",\"{$row['apply_id']}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>Disclaimer</a>";
            }
        }

        if($ptw_mode == 'B') {
            if ($status_tag == '11' && $operator_tag == '3') {
                $disclaimer_link = "<a style='float: left' href='javascript:void(0)' onclick='itemDisclaimerOnly(\"2\",\"{$program_id}\",\"{$row['apply_id']}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>Disclaimer</a>";
            }
        }

        if($ptw_mode == 'A') {
            if ($status_tag == '3' && $operator_tag == '2') {
                $disclaimer_link = "<a style='float: left' href='javascript:void(0)' onclick='itemDisclaimerOnly(\"3\",\"{$program_id}\",\"{$row['apply_id']}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>Disclaimer</a>";
            }
        }

        if($ptw_mode == 'A') {
            if ($status_tag == '4' && $operator_tag == '3') {
                $disclaimer_link = "<a style='float: left' href='javascript:void(0)' onclick='itemDisclaimerOnly(\"4\",\"{$program_id}\",\"{$row['apply_id']}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>Disclaimer</a>";
            }
        }

        if($ptw_mode == 'B') {
            if ($status_tag == '3' && $operator_tag == '3' || $operator_tag == '4') {
                $disclaimer_link = "<a style='float: left' href='javascript:void(0)' onclick='itemDisclaimerOnly(\"5\",\"{$program_id}\",\"{$row['apply_id']}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>Disclaimer</a>";
            }
        }

        // $link .= $disclaimer_link;
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
    <div class="col-xs-5">
        <div class="dataTables_info" id="example2_info">
            <?php echo Yii::t('common', 'page_total'); ?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt'); ?>
            <?php if($rows){ ?>
                <?php echo "<button type='button' class='btn btn-primary btn-sm' style='margin-left: 10px' onclick='compress(\"{$program_id}\",\"{$curpage}\");'>".Yii::t('proj_project_user', 'compress')."</button>" ?>
                <?php echo "<button type='button' class='btn btn-primary btn-sm' style='margin-left: 10px' onclick='itemQr(\"{$program_id}\",\"{$curpage}\");'>PTW QR Code</button>" ?>
                <button type="button" class="btn btn-primary btn-sm"  onclick="itemExport_Month('<?php echo $program_id; ?>');"><?php echo Yii::t('license_licensepdf', 'Ptw_record_export');?></button>
                <button type="button" class="btn btn-primary btn-sm"  onclick="itemMonthExport('<?php echo $program_id; ?>');">Monthly Safety Data Report</button>
            <?php }?>
            <!--            A类型或B类型下  提交状态下  审批人员-->
            <?php
                if($ptw_mode == 'A' || $ptw_mode == 'B'){
                    if($status_tag == '0' && $operator_tag == '2'){
                        ?>
                        <button type="button" class="btn btn-primary btn-sm"  onclick="itemDisclaimer('1','<?php echo $program_id ?>');">Multiple Assessed</button>
                    <?php } ?>
            <?php } ?>
            <!--            B类型下  审批1状态下  审批2人员-->
            <?php
            if($ptw_mode == 'B'){
                if($status_tag == '1' && $operator_tag == '4'){
                    ?>
                    <button type="button" class="btn btn-primary btn-sm"  onclick="itemDisclaimer('1','<?php echo $program_id ?>');">Multiple Assess 2</button>
                <?php } ?>
            <?php } ?>
            <!--            A类型  审批状态下  批准人员-->
            <?php
            if($ptw_mode == 'A'){
                if($status_tag == '1' && $operator_tag == '3'){
                    ?>
                    <button type="button" class="btn btn-primary btn-sm"  onclick="itemDisclaimer('2','<?php echo $program_id ?>');">Multiple Approve</button>
                <?php } ?>
            <?php } ?>
            <!--            B类型下  审批2状态下  批准人员-->
            <?php
            if($ptw_mode == 'B'){
                if($status_tag == '11' && $operator_tag == '3'){
                    ?>
                    <button type="button" class="btn btn-primary btn-sm"  onclick="itemDisclaimer('2','<?php echo $program_id ?>');">Multiple Approve</button>
                <?php } ?>
            <?php } ?>
            <!--            A类型下  关闭状态下  审批人员-->
            <?php if($ptw_mode == 'A'){
                if($status_tag == '3' && $operator_tag == '2'){
                    ?>
                    <button type="button" class="btn btn-primary btn-sm"  onclick="itemDisclaimer('3','<?php echo $program_id ?>');">Multiple Closure Approved</button>
                <?php } ?>
            <?php } ?>
            <!--            A类型下  关闭审批状态下  批准人员-->
            <?php if($ptw_mode == 'A'){
                if($status_tag == '4' && $operator_tag == '3'){
                    ?>
                    <button type="button" class="btn btn-primary btn-sm"  onclick="itemDisclaimer('4','<?php echo $program_id ?>');">Multiple Closure Accepted</button>
                <?php } ?>
            <?php } ?>
            <!--            B类型下  关闭状态下  批准人员和审批2人员-->
            <?php if($ptw_mode == 'B'){
            if($status_tag == '3' && $operator_tag == '3' || $operator_tag == '4'){
            ?>
                <button type="button" class="btn btn-primary btn-sm"  onclick="itemDisclaimer('5','<?php echo $program_id ?>');">Multiple Closure Approved</button>
            <?php } ?>
                <?php } ?>
        </div>
    </div>
    <div class="col-xs-7">
        <div class="dataTables_paginate paging_bootstrap">
            <?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>

<script src="js/loading.js"></script>
<script>

    var per_read_cnt = 5;
    //批量压缩
    var compress = function (id,curpage) {
        var tbodyObj = document.getElementById('example2');
        var tag = '';
        rowcnt= 0 ;
        $("table :checkbox").each(function(key,value){
            if(key != 0) {
                if ($(value).prop('checked')) {
                    var apply_id = tbodyObj.rows[key].cells[1].innerText;
                    tag += apply_id + '|';
                    rowcnt++;
                }
            }
        })
        if(tag.length == 0){
            alert('Please select record.');
            return false;
        }
        tag=(tag.substring(tag.length-1)=='|')?tag.substring(0,tag.length-1):tag;
        alert('<?php echo Yii::t('proj_project_user', 'confirm_compress'); ?>');
        addcloud(); //为页面添加遮罩
        ajaxReadData(id, curpage, tag, rowcnt, 0);
    }

    /*
     * 加载数据
     */
    var ajaxReadData = function (id, curpage, tag, rowcnt, startrow){//alert('aa');

        $.ajax({
            data: {id: id, tag:tag, startrow: startrow, per_read_cnt:per_read_cnt},
            url: "index.php?r=license/licensepdf/userbatch",
            type: "POST",
            dataType: "json",
            beforeSend: function () {
            },
            success: function (data) {
                if (rowcnt > startrow) {
                    ajaxReadData(id, curpage, tag, rowcnt, startrow+per_read_cnt);
                }else{
                    clearCache(curpage);
                }
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });

        return false;
    }

    /*
     * 加载数据
     */
    var clearCache = function(curpage){//alert('aa');
        removecloud();
        window.location = "index.php?r=license/licensepdf/compress&curpage="+curpage;
    }

    //批量导出
    var itemQr = function (program_id) {
        var objs = document.getElementById("_query_form").elements;
        var i = 0;
        var cnt = objs.length;
        var obj;
        var url = '';

        var tbodyObj = document.getElementById('example2');
        var tag = '';
        $("table :checkbox").each(function(key,value){
            if(key != 0) {
                if ($(value).prop('checked')) {
                    var id = tbodyObj.rows[key].cells[1].innerHTML;
                    tag += id + '|';
                    i++;
                }
            }
        })
        if(tag != ''){
            tag = tag.substr(0,tag.length-1);
        }else{
            alert('Please select Record.');
            return false;
        }
        addcloud();
        ajaxReadQr(tag,i,0,program_id);
    }
    var per_read_cnt = 20;
    /*
    * 加载数据
    */
    var ajaxReadQr = function (tag, rowcnt, startrow, program_id){
        jQuery.ajax({
            data: {tag:tag,startrow: startrow, per_read_cnt:per_read_cnt, program_id:program_id},
            type: 'post',
            url: './index.php?r=license/licensepdf/createqrpdf',
            dataType: 'json',
            success: function (data, textStatus) {
                if (rowcnt > startrow) {
                    ajaxReadQr(tag,rowcnt, startrow+per_read_cnt, program_id);
                }else{
                    clearQr();
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                alert(XMLHttpRequest);
                alert(textStatus);
                alert(errorThrown);
            },
        });
        return false;
    }
    /*
    * 清除缓存，下载压缩包
    */
    var clearQr = function(){//alert('aa');
        removecloud();
        window.location = "index.php?r=license/licensepdf/downloadqrzip";
    }

    //提醒
    var itemDisclaimer = function(status,program_id) {
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
        tag = tag.substr(0,tag.length-1);
        if(tag.length == 0){
            alert('<?php echo Yii::t('comp_safety', 'error_tag_is_null'); ?>');
            return false;
        }
        if(!confirm("Confirm to proceed?")){
            return;
        }

        if(status == '1'){
            var app_id = 'Multiple Assess';
        }else if(status == '2'){
            var app_id = 'Multiple Approve';
        }else if(status == '3'){
            var app_id = 'Multiple CloseA';
        }else if(status == '4'){
            var app_id = 'Multiple CloseB';
        }else if(status == '5'){
            var app_id = 'Multiple CloseC';
        }else if(status == '6'){
            var app_id = 'Multiple Assess2';
        }

        var modal = new TBModal();
        // modal.title = "["+app_id+"] <?php //echo Yii::t('sys_workflow', 'Approval Process'); ?>//";
        modal.title = app_id;
        modal.url = "index.php?r=license/licensepdf/disclaimer&tag="+tag+"&status="+status+"&program_id="+program_id+"&app=1";
        modal.modal();
    }

    var itemDisclaimerOnly = function(status,program_id,tag) {

        var app_id = 'Disclaimer';

        var modal = new TBModal();
        // modal.title = "["+app_id+"] <?php //echo Yii::t('sys_workflow', 'Approval Process'); ?>//";
        modal.title = app_id;
        modal.url = "index.php?r=license/licensepdf/disclaimer&tag="+tag+"&status="+status+"program_id="+program_id+"&app=2";
        modal.modal();
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