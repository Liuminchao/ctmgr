<script type="text/javascript">
    //查询
    var itemQuery = function () {
        var start = $('#q_start_date').val();
        if ( $('#form_program_id').val() == '') {
            alert('<?php echo Yii::t('proj_report', 'alert_program_id'); ?>');
            return;
        }
        if(start==''){
            alert('<?php echo Yii::t('pay_payroll', 'query_date_null'); ?>');
            return;
        }
//        var start_mon = start.substr(2,4);
//        var end_mon = end.substr(2,4);
//        if(start_mon != end_mon){
//            alert('<?php echo Yii::t('sys_optlog','tip_month');?>');
//            return;
//        }
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
    
    //返回
    var itemBack = function () {
        window.location = "index.php?r=payroll/workhour/list";
    }
</script>

<div class="row">
    <div class="col-xs-12">
        
        <div class="box">
            <div class="box-body table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('workhour_toolBox',array('program_list'=>$program_list)); ?>
                    <div id="datagrid"><?php $this->actionQuery(); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
