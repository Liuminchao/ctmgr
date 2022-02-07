<script type="text/javascript">
     //验证是否跨月查询
//    function verifyDate() {
//        var start_date = document.getElementById("q_start_date").value;
//        var end_date = document.getElementById("q_end_date").value;
//        var new_start_date = start_date.substr(3, 3);
//        var new_end_date = end_date.substr(3, 3);
////        alert(new_start_date);
////        alert(new_end_date);
//        if (new_start_date == new_end_date) {
//            return true;
//        } else {
//            alert('<?php echo Yii::t('sys_optlog','tip_month'); ?>');
//            return false;
//        }
//    }
    
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
        window.location = "index.php?r=payroll/salary/detaillist&summary_id=" + id+'&start_date='+date;
    }
    //工资审核
    var itemStorage = function () {
        var modal = new TBModal();
        modal.title = "<?php echo Yii::t('pay_payroll', 'contentHeader_salary_storage'); ?>";
        modal.url = "index.php?r=payroll/salary/storage";
        modal.modal();
    }
    //返回
    var itemBack = function () {
        window.location = "index.php?r=payroll/salaryquery/list";
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
