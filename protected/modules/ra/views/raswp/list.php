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
    //下载模版
    var itemDownload = function () {
        var modal = new TBModal();
        modal.title = "[Download Template] <?php echo Yii::t('comp_ra', 'Batch download'); ?>";
        modal.url = "index.php?r=ra/raswp/download";
        modal.modal();
    }
    //下载
    var itemAttachment = function(id,app_id) {
        var modal = new TBModal();
        //modal.title = "["+app_id+"] <?php //echo Yii::t('sys_workflow', 'Approval Process'); ?>//";
        modal.title = app_id;
        modal.url = "index.php?r=ra/raswp/downloadpreview&apply_id="+id+'&app_id='+app_id;
        modal.modal();
    }
    //导出PDF
    var itemExport = function (ra_swp_id,app_id) {
        window.location = "index.php?r=ra/raswp/downloadpdf&ra_swp_id="+ra_swp_id+"&app_id="+app_id;
    }
    //批量导出
    var itemExcel = function () {
        var objs = document.getElementById("_query_form").elements;
        var i = 0;
        var cnt = objs.length;
        var obj;
        var url = '';

        for (i = 0; i < cnt; i++) {
            obj = objs.item(i);
            url += '&' + obj.name + '=' + obj.value;
        }
        window.location = "index.php?r=ra/raswp/exportexcel" + url;
    }
   //申请
    var itemApply = function(id) {
        window.location = "index.php?r=ra/raswp/apply&program_id="+id;
    }
    //编辑
    var itemEdit = function(ra_swp_id,app_id) {
        window.location = "index.php?r=ra/raswp/updateapply&ra_swp_id="+ra_swp_id+"&app_id="+app_id;
    }
    //删除
    var itemDelete = function(ra_swp_id,app_id) {

        if (!confirm('<?php echo Yii::t('comp_ra', 'confirm_delete'); ?>' )) {
            return;
        }
        // alert("index.php?r=comp/usersubcomp/logout&confirm=1&id="+id);
        $.ajax({
            data: {ra_swp_id: ra_swp_id,app_id:app_id, confirm: 1},
            url: "index.php?r=ra/raswp/deleteapply",
            dataType: "json",
            type: "POST",
            success: function (data) {

                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_delete'); ?>");
                    itemQuery();
                } else {
                    //alert("<?php echo Yii::t('common', 'error_delete'); ?>");
                    alert(data.msg);
                }
            }
        });
    }
</script>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('_toolBox',array('program_id'=>$program_id)); ?>
                    <div id="datagrid"><?php $this->actionGrid(); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>