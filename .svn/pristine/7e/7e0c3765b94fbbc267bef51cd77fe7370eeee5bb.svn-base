<script type="text/javascript">
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
        var modal = new TBModal();
        modal.title = "["+id+"] <?php echo Yii::t('sys_workflow', 'Approval Process'); ?>";
        modal.url = "index.php?r=quality/qualityinspection/preview&check_id="+id;
        modal.modal();
    }
    //导出
    var itemPdf = function (id) {
        var modal = new TBModal();
        modal.title = "["+id+"] <?php echo Yii::t('comp_safety', 'Export Pdf'); ?>";
        modal.url = "index.php?r=quality/qualityinspection/export&contractor_id="+id;
        modal.modal();
    }
   //下载
    var itemDownload = function(id) {
        window.location = "index.php?r=quality/qualityinspection/downloadpdf&check_id="+id;
    }
</script>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('_toolBox'); ?>
                    <div id="datagrid"><?php $this->actionGrid($program_id,$user_id,$deal_type,$safety_level); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>