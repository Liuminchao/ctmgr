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
    //预览流程
    var itemWorkflow = function (apply_id,app_id) {
        var modal = new TBModal();
        modal.title = app_id;
        modal.url = "index.php?r=license/licensepdf/preview&apply_id="+apply_id+'&app_id='+app_id;
        modal.modal();
    }
    //预览文档
    var itemPreview = function(id,app_id) {
        window.open("index.php?r=license/licensepdf/staffdownload&apply_id="+id+'&app_id='+app_id+'&tag=1',"_blank");
    }
    //下载列表
    var itemAttachment = function(id,name) {
        var modal = new TBModal();
        modal.title = 'PTW';
        modal.url = "index.php?r=license/licensepdf/downloadattachment&apply_id="+id+'&app_id='+name;
        modal.modal();
    }
   //下载
    var itemDownload = function(id,app_id) {
        var modal = new TBModal();
//        modal.title = "["+app_id+"] <?php //echo Yii::t('sys_workflow', 'Approval Process'); ?>//";
        modal.title = app_id;
        modal.url = "index.php?r=license/licensepdf/downloadpreview&apply_id="+id+'&app_id='+app_id;
        modal.modal();
    }
    //人员下载
    var itemStaff = function(id,app_id) {
        window.location = "index.php?r=license/licensepdf/staffdownload&apply_id="+id+"&app_id="+app_id;
    }
    //详情
    var itemDetail= function(id,app_id) {
        window.location = "index.php?r=license/licensepdf/detail&apply_id="+id+"&app_id="+app_id;
    }

    //批量导出
    var itemExport = function () {
        var objs = document.getElementById("_query_form").elements;
        var i = 0;
        var cnt = objs.length;
        var obj;
        var url = '';

        for (i = 0; i < cnt; i++) {
            obj = objs.item(i);
            url += '&' + obj.name + '=' + obj.value;
        }
        window.location = "index.php?r=license/upload/export" + url;
    }
    //按月批量导出
    var itemExport_Month = function (id) {
        var modal=new TBModal();
        modal.title='PTW Report';
        modal.url="./index.php?r=license/licensepdf/batchmonthreport&program_id="+id;
        modal.modal();
    }
    //月报导出
    var itemMonthExport = function (id) {
        var modal=new TBModal();
        modal.title='Monthly Safety Data Report';
        modal.url="./index.php?r=license/licensepdf/monthreport&program_id="+id;
        modal.modal();
    }

</script>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('_toolBox',array('program_id'=>$program_id)); ?>
                    <div id="datagrid"><?php $this->actionGrid($program_id,$user_id,$deal_type,$type_id); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
