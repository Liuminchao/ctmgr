
<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label for="working_life"
                   class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('comp_safety', 'wsh_report');?></label>
            <div class="col-sm-6 padding-lr5">
                <button class="btn btn-default" type='button' onclick="downloadwsh('<?php echo $check_id ?>','<?php echo $app_id ?>')"><?php echo Yii::t('common', 'download');?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var downloadwsh =  function(id,app_id){
        window.open("index.php?r=wsh/wshinspection/downloadpdf&check_id="+id+'&app_id='+app_id,"_blank");
//        window.location = "index.php?r=wsh/wshinspection/downloadpdf&check_id="+id+'&app_id='+app_id;
    }
    var downloadcheck =  function(id){
        window.location = "index.php?r=routine/routineinspection/downloadpdf&check_id="+id;
    }
</script>
