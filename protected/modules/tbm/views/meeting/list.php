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
    var itemWorkflow = function (metting_id,app_id,name) {
        var modal = new TBModal();
        //modal.title = "["+name+"] <?php //echo Yii::t('sys_workflow', 'Approval Process'); ?>//";
        modal.title = app_id;
        modal.url = "index.php?r=tbm/meeting/preview&app_id=" + app_id+'&apply_id='+metting_id;
        modal.modal();
    }
    //预览文档
    var itemPreview = function(id,name) {
        window.open("index.php?r=tbm/meeting/downloadpdf&apply_id="+id+'&app_id='+name+'&tag=1',"_blank");
    }
    //下载
    var itemDownload = function(id,name) {
        window.location = "index.php?r=tbm/meeting/downloadpdf&apply_id="+id+'&app_id='+name+'&tag=0';
    }
    //下载列表
    var itemDownloadView = function(id,name) {
        var modal = new TBModal();
        modal.title = 'TBM';
        modal.url = "index.php?r=tbm/meeting/downloadpreview&meeting_id="+id+'&app_id='+name;
        modal.modal();
    }
    //下载列表
    var itemAttachment = function(id,name) {
        var modal = new TBModal();
        modal.title = 'TBM';
        modal.url = "index.php?r=tbm/meeting/downloadattachment&meeting_id="+id+'&app_id='+name;
        modal.modal();
    }
</script>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('_toolBox',array('program_id'=>$program_id)); ?>
                    <div id="datagrid"><?php $this->actionGrid($program_id,$user_id,$deal_type); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>