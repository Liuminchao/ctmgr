
<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label for="working_life"
                   class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('tbm_meeting', 'tbm_report');?></label>
            <div class="col-sm-6 padding-lr5">
                <button class="btn btn-default" type='button' onclick="downloadtbm('<?php echo $meeting_id ?>','<?php echo $app_id ?>')"><?php echo Yii::t('common', 'download');?></button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var downloadtbm =  function(id,app_id){
        window.location = "index.php?r=tbm/meeting/downloadpdf&apply_id="+id+'&app_id='+app_id;
    }
    var downloadcheck =  function(id){
        window.location = "index.php?r=routine/routineinspection/downloadpdf&check_id="+id;
    }
</script>
