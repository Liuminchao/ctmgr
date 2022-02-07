<script type="text/javascript">
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
    //添加员工补贴
    var itemAdd = function (id) {
        //alert(id);
        var modal = new TBModal();
        modal.title = "<?php echo Yii::t('pay_payroll', 'contentHeader_allowance'); ?>";
        modal.url = "index.php?r=payroll/allowance/new";
        modal.modal();
        //window.location = "index.php?r=proj/task/new&ptype=<?php echo Yii::app()->session['project_type'];?>&program_id=" + id;
    }
    //编辑员工补贴
    var itemEdit = function (id) {
        //alert(id);
        var modal = new TBModal();
        modal.title = "<?php echo Yii::t('pay_payroll', 'contentHeader_allowance'); ?>";
        modal.url = "index.php?r=payroll/allowance/edit&allowance_id="+id;
        modal.modal();
        //window.location = "index.php?r=proj/task/new&ptype=<?php echo Yii::app()->session['project_type'];?>&program_id=" + id;
    }
    //注销
    var itemDelete = function (id) {

        if (!confirm('<?php echo Yii::t('pay_payroll', 'confirm_delete'); ?>')) {
            return;
        }
        $.ajax({
            data: {id: id, confirm: 1},
            url: "index.php?r=payroll/allowance/delete",
            dataType: "json",
            type: "GET",
            success: function (data) {
                if(data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_logout'); ?>");
                    itemQuery();
                }
                else{
                    alert("<?php echo Yii::t('common', 'error_logout'); ?>");
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert(XMLHttpRequest.status);
                        alert(XMLHttpRequest.readyState);
                        alert(textStatus);
                    },
        });
    }
</script>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('_toolBox'); ?>
                    <div id="datagrid"><?php $this->actionGrid(); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>