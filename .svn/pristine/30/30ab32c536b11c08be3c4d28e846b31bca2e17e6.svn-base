<script type="text/javascript">
     //验证是否跨月查询
    function verifyDate() {
        var start_date = document.getElementById("q_start_date").value;
        var end_date = document.getElementById("q_end_date").value;
        var new_start_date = start_date.substr(3, 3);
        var new_end_date = end_date.substr(3, 3);
//        alert(new_start_date);
//        alert(new_end_date);
        if (new_start_date == new_end_date) {
            return true;
        } else {
            alert('<?php echo Yii::t('sys_optlog','tip_month'); ?>');
            return false;
        }
    }
    
    //查询
    var itemQuery = function () {
        if (verifyDate() == false)
            return;
        var start = $('#q_start_date').val();
        var end = $('#q_end_date').val();
        var program_id = $('#form_program_id').val();
        if ( program_id == '') {
            alert('<?php echo Yii::t('proj_report', 'alert_program_id'); ?>');
            return;
        }
//        if(start=='' || end==''){
//            alert('<?php echo Yii::t('pay_payroll', 'query_date_null'); ?>');
//            return;
//        }
        var start_mon = start.substr(2,4);
        var end_mon = end.substr(2,4);
        if(start_mon != end_mon){
            alert('<?php echo Yii::t('sys_optlog','tip_month');?>');
            return;
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
    //添加工时
    var itemSet = function () {
        window.location = "index.php?r=payroll/workhour/set";
    }
    //导出报表
    var itemExport = function () {
        var objs = document.getElementById("_query_form").elements;
        var i = 0;
        var cnt = objs.length;
        var obj;
        var url = '';
        //var order = '';
        //var page = document.getElementsByClassName("page selected")[0];

        for (i = 0; i < cnt; i++) {
            obj = objs.item(i);
            url += '&' + obj.name + '=' + obj.value;
        }
        //if( typeof page == "undefined" ) {
            //page = 1;
       // }else{
           // page = page.innerText;
       // }
        var act = document.getElementById("export");
        act.href = "index.php?r=payroll/workhour/export"+url;
    }
    
</script>

<div class="row">
    <div class="col-xs-12">
        
        <div class="box">
            <div class="box-body table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('_toolBox',array('program_list'=>$program_list)); ?>
                    <div id="datagrid"><?php $this->actionGrid(); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
