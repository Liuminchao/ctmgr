<script type="text/javascript">
    //查询
    var itemQuery = function () {
        var start_date = $('#q_start_date').val();
        var end_date = $('#q_end_date').val();
        var startDateTemp = start_date.split(" ");
        var endDateTemp = end_date.split(" ");
        if(startDateTemp[2] == '2017' && endDateTemp[2]!='2017'){
            alert('<?php echo Yii::t('common', 'alert_across_year'); ?>');
            return;
        }
        if(start_date==''||end_date==''){
            alert('<?php echo Yii::t('common', 'select_time_period'); ?>');
            return;
        }
        if ( $('#form_program_id').val() == '') {
            alert('<?php echo Yii::t('proj_report', 'alert_program_id'); ?>');
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
    var itemBack = function() {
        window.location = "./?<?php echo Yii::app()->session['list_url']['info/list']; ?>";
    }
</script>

<div class="row">
    <div class="col-xs-12">
        
        <div class="box">
            <div class="box-body table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('_toolBox',array('contractor_id'=>$contractor_id,'contractor_name'=>$contractor_name)); ?>
                    <div id="datagrid"><?php $this->actionGrid(); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
