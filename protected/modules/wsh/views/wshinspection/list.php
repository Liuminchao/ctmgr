<script type="text/javascript">
    $(function(){
        itemQuery();
    });
    //查询
    var itemQuery = function() {
        var start_date = $("#q_start_date").val();
        var end_date = $("#q_end_date").val();
        if(start_date != '' && end_date == ''){
            alert('<?php echo Yii::t('license_licensepdf','time_period') ?>');
        }
        if(start_date == '' && end_date != ''){
            alert('<?php echo Yii::t('license_licensepdf','time_period') ?>');
        }
        var objs = document.getElementById("_query_form").elements;
        var i = 0;
        var cnt = objs.length;
        var obj;
        var url = '';

        for (i = 0; i < cnt; i++) {
            obj = objs.item(i);
            url += '&' + obj.name + '=' + obj.value;
        }
<?php echo $this->gridId; ?>.condition = url;
<?php echo $this->gridId; ?>.refresh();
    }
    //预览
    var itemPreview = function (id) {
        window.open("index.php?r=wsh/wshinspection/downloadpdf&check_id="+id+"&tag=1","_blank");
    }
    //导出
    var itemPdf = function (id) {
        var modal = new TBModal();
        modal.title = "["+id+"] <?php echo Yii::t('comp_safety', 'Export Pdf'); ?>";
        modal.url = "index.php?r=wsh/wshinspection/export&contractor_id="+id;
        modal.modal();
    }
    //导出Excel
    var itemExport =function() {
        var objs = document.getElementById("_query_form").elements;
        var i = 0;
        var cnt = objs.length;
        var obj;
        var url = '';

        for (i = 0; i < cnt; i++) {
            obj = objs.item(i);
            url += '&' + obj.name + '=' + obj.value;
        }
        window.location = "index.php?r=wsh/wshinspection/exportexcel" + url;
    }
    //危险做法报告
    var itemNcrExcel =function() {
        var objs = document.getElementById("_query_form").elements;
        var i = 0;
        var cnt = objs.length;
        var obj;
        var url = '';

        for (i = 0; i < cnt; i++) {
            obj = objs.item(i);
            url += '&' + obj.name + '=' + obj.value;
        }
        window.location = "index.php?r=wsh/wshinspection/excel&tag=1" + url;
    }
    //月报导出
    var itemNcrExport = function (id) {
        var modal=new TBModal();
        modal.title='NCR Report';
        modal.url="./index.php?r=wsh/wshinspection/ncrreport&program_id="+id;
        modal.modal();
    }
    //良好做法宝报告
    var itemGoodExcel =function() {
        var objs = document.getElementById("_query_form").elements;
        var i = 0;
        var cnt = objs.length;
        var obj;
        var url = '';

        for (i = 0; i < cnt; i++) {
            obj = objs.item(i);
            url += '&' + obj.name + '=' + obj.value;
        }
        window.location = "index.php?r=wsh/wshinspection/excel&tag=2" + url;
    }
    //合并PDF
    var itemExportpdf = function() {
        var objs = document.getElementById("_query_form").elements;
        var i = 0;
        var cnt = objs.length;
        var obj;
        var url = '';

        for (i = 0; i < cnt; i++) {
            obj = objs.item(i);
            url += '&' + obj.name + '=' + obj.value;
        }
        window.location = "index.php?r=wsh/compose/merge" + url;
    }

    //上海隧道月报导出
    var itemShsdMonthExport = function (id) {
        var modal=new TBModal();
        modal.title='Monthly Safety Data Report';
        // modal.url="./index.php?r=wsh/wshinspection/monthreport&program_id="+id;
        modal.url="./index.php?r=license/licensepdf/monthreport&program_id="+id;
        modal.modal();
    }
   //下载
    var itemDownload = function(id) {
        var action = '1';
        window.location = "index.php?r=wsh/wshinspection/downloadpdf&check_id="+id+"&action="+action;
    }
    //下载2
    var itemDownload2 = function(id) {
        window.location = "index.php?r=wsh/wshinspection/downloadpdf2&check_id="+id;
    }
    //人员下载
    var itemStaff = function(id,app_id) {
        window.location = "index.php?r=license/licensepdf/staffdownload&apply_id="+id+"&app_id="+app_id;
    }
    //详情
    var itemDetail= function(id,app_id) {
        window.location = "index.php?r=license/licensepdf/detail&apply_id="+id+"&app_id="+app_id;
    }
    //下载列表
    var itemDownloadView = function(id,name) {
        var modal = new TBModal();
        modal.title = 'WSH';
        modal.url = "index.php?r=wsh/wshinspection/downloadpreview&check_id="+id+'&app_id='+name;
        modal.modal();
    }
    //下载列表
    var itemAttachment = function(id,name) {
        var modal = new TBModal();
        modal.title = 'WSH';
        modal.url = "index.php?r=wsh/wshinspection/downloadattachment&check_id="+id+'&app_id='+name;
        modal.modal();
    }
</script>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('_toolBox',array('program_id'=>$program_id)); ?>
                    <div id="datagrid"><?php $this->actionGrid($program_id,$user_id,$deal_type,$safety_level); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
