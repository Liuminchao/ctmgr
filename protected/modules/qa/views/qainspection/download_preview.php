
<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label for="working_life"
                   class="col-sm-6 control-label padding-lr5"><?php echo Yii::t('comp_qa', 'qa_report');?></label>
            <div class="col-sm-6 padding-lr5">
                <button class="btn btn-default" type='button' onclick="downloadreport('<?php echo $check_id ?>')"><?php echo Yii::t('common', 'download');?></button>
            </div>
        </div>
    </div>
</div>
<?php if(count($form_data_list) > 0){ ?>
    <?php foreach($form_data_list as $k => $list){
        ?>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="working_life"
                        class="col-sm-6 control-label padding-lr5"><?php echo $list['form_title'];?></label>
                    <div class="col-sm-6 padding-lr5">
                        <button class="btn btn-default" type='button' onclick="downloadexcel('<?php echo $check_id ?>','<?php echo $list['data_id'] ?>')"><?php echo Yii::t('common', 'download');?></button>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
<?php } ?>
<script type="text/javascript">
    var downloadexcel =  function(check_id,data_id){
        window.location = "index.php?r=qa/qainspection/qaexport&check_id="+check_id+"&data_id="+data_id;
    }
    var downloadreport =  function(id){
        window.location = "index.php?r=qa/qainspection/downloadpdf&check_id="+id;
    }
</script>
