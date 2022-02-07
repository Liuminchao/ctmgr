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
    //计算工资
    var itemCalculate = function () {
        var modal = new TBModal();
        modal.title = "<?php echo Yii::t('pay_payroll', 'contentHeader_salary_calculate'); ?>";
        modal.url = "index.php?r=payroll/salary/calculate";
        modal.modal();
    }
    //详细工资
    var itemDetail = function (id,date) {
        window.location = "index.php?r=payroll/salaryquery/detaillist&summary_id=" + id+'&start_date='+ date;
    }
    //设置工资
    var itemSet = function () {
        window.location = "index.php?r=payroll/salary/list";
    }
    //导出工资报表
    var itemExport = function () {
        var modal = new TBModal();
        modal.title = "<?php echo Yii::t('pay_payroll', 'contentHeader_export_salary'); ?>";
        modal.url = "index.php?r=payroll/salaryquery/salaryexport";
        modal.modal();
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
