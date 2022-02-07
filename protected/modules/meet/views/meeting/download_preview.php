
<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label for="working_life"
                   class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('meeting', 'meeting_report');?></label>
            <div class="col-sm-6 padding-lr5">
                <button class="btn btn-default" type='button' onclick="downloadmeeting('<?php echo $train_id ?>','<?php echo $app_id ?>')"><?php echo Yii::t('common', 'download');?></button>
            </div>
        </div>
    </div>
</div>
<?php if(count($check_list) > 0){ ?>
    <?php for($i=0;$i<=count($check_list)-1;$i++){
        ?>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="working_life"
                        class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('license_licensepdf', 'checklist_report');?><?php echo $i+1 ?></label>
                    <div class="col-sm-6 padding-lr5">
                        <button class="btn btn-default" type='button' onclick="downloadcheck('<?php echo $check_list[$i]['check_id'] ?>')"><?php echo Yii::t('common', 'download');?></button>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
<?php } ?>
<script type="text/javascript">
    var downloadmeeting =  function(id,app_id){
        window.location = "index.php?r=meet/meeting/downloadpdf&apply_id="+id+'&app_id='+app_id;
    }
    var downloadcheck =  function(id){
        window.location = "index.php?r=routine/routineinspection/downloadpdf&check_id="+id;
    }
</script>
