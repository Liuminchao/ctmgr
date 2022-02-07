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
    //预览
    var itemPreview = function (apply_id,app_id,name) {
        window.location = "index.php?r=accidents/accident/preview&app_id=" + app_id+'&apply_id='+apply_id;
    }
    //下载
    var itemDownload = function(id,name) {
        window.location = "index.php?r=accidents/accident/downloadpdf&apply_id="+id+'&app_id='+name;
    }
</script>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('_shsdtoolBox',array('program_id'=>$program_id)); ?>
                    <div id="datagrid"><?php $this->actionShsdGrid($program_id,$user_id,$deal_type,$type_id); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>