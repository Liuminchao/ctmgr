<script type="text/javascript">
    //查询
    var itemQuery = function() {
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
    var itemAdd = function(id) {
        window.location = "index.php?r=routine/condition/new&type_id="+id;
    }
    //返回
    var itemBack = function() {
        window.location = "index.php?r=routine/type/list";
    }
    //修改
    var itemEdit = function(id,type_id) {
        var modal = new TBModal();
        modal.title = "<?php echo Yii::t('common', 'edit'); ?>";
        modal.url = "index.php?r=routine/condition/edit&id="+id;
        modal.modal();
    }
    //删除
    var itemDelete = function(id,type_id) {
        if (!confirm('<?php echo Yii::t('common', 'confirm_delete'); ?>')) {
            return;
        }
        $.ajax({
            data: {id: id,confirm:1},
            url: "index.php?r=routine/condition/delete",
            dataType: "json",
            type: "POST",
            success: function(data) {
                
                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_delete'); ?>");
                    itemQuery();
                } else {
                    alert("<?php echo Yii::t('common', 'error_delete'); ?>");
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
                    <?php $this->renderPartial('_toolBox',array('type_id'=>$id)); ?>
                    <div id="datagrid"><?php $this->actionGrid($id); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>