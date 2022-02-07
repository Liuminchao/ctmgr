<script type="text/javascript">
    $(function(){
        itemQuery();
    });
    //查询
    var itemQuery = function () {
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
    //添加
    var itemAdd = function (program_id) {
        window.location = "index.php?r=qa/import/view&program_id="+program_id;
    }
    //详情
    var itemDetail = function (form_id,program_id) {
        window.location = "index.php?r=qa/import/detaillist&form_id="+form_id+"&program_id="+program_id;
    }
    //修改
    var itemEdit = function (id) {
        var modal = new TBModal();
        modal.title = "<?php echo Yii::t('sys_role', 'RoleEdit'); ?>";
        modal.url = "index.php?r=comp/role/edit&id=" + id;
        modal.modal();
        itemQuery();
    }

    //启用
    var itemStart = function (id, name) {
        if (!confirm("<?php echo Yii::t('common', 'confirm_start_1'); ?>" + name + "<?php echo Yii::t('common', 'confirm_start_2'); ?>")) {
            return;
        }
        $.ajax({
            data: {id: id, confirm: 1},
            url: "index.php?r=comp/role/start",
            dataType: "json",
            type: "POST",
            success: function (data) {

                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_start'); ?>");
                    itemQuery();
                } else {
                    alert("<?php echo Yii::t('common', 'error_start'); ?>");
                }
            }
        });
    }
    //停用
    var itemStop = function (id, name) {
        if (!confirm("<?php echo Yii::t('common', 'confirm_stop_1'); ?>" + name + "<?php echo Yii::t('common', 'confirm_stop_2'); ?>")) {
            return;
        }
        $.ajax({
            data: {id: id, confirm: 1},
            url: "index.php?r=qa/import/stop",
            dataType: "json",
            type: "POST",
            success: function (data) {

                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_stop'); ?>");
                    itemQuery();
                } else {
                    alert("<?php echo Yii::t('common', 'error_stop'); ?>");
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
                    <div id="datagrid"><?php $this->actionGrid($program_id); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>