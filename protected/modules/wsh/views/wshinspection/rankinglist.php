<script type="text/javascript">
    //查询
    var itemQuery = function() {
        var Month = new Array();
        Month['Jan'] = 1;
        Month['Feb'] = 2;
        Month['Mar'] = 3;
        Month['Apr'] = 4;
        Month['May'] = 5;
        Month['Jun'] = 6;
        Month['Jul'] = 7;
        Month['Aug'] = 8;
        Month['Sep'] = 9;
        Month['Oct'] = 10;
        Month['Nov'] = 11;
        Month['Dec'] = 12;
        var date = $("#q_start_date").val();
        var end_date = $("#q_end_date").val();
        var a = date.split(' ');
        var month = Month[a[0]];
        var year = a[1];
        if(end_date){
            var b = end_date.split(' ');
            var end_month = Month[b[0]];
            var end_year = b[1];
            if(year > end_year){
                alert('<?php echo Yii::t('comp_safety','date_alert'); ?>');
                return;
            }else if(year == end_year){
                if(month > end_month){
                    alert('<?php echo Yii::t('comp_safety','date_alert'); ?>');
                    return;
                }
            }

        }

        if(date == ''){
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

</script>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('ranking_toolBox',array('program_id'=>$program_id)); ?>
                    <div id="datagrid"><?php $this->actionRankingGrid($program_id); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>