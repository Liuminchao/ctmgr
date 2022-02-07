

<?php if(count($form_data_list) > 0){ ?>
    <?php foreach($form_data_list as $k => $list){
        ?>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="working_life"  style="word-wrap:break-word;word-break:break-all;"
                           class="col-sm-6 control-label padding-lr5"><?php echo $list['doc_name'];?></label>
                    <div class="col-sm-6 padding-lr5">
                        <button class="btn btn-default" type='button' onclick="downloadattachment('<?php echo $list['record_id'] ?>')"><?php echo Yii::t('common', 'download');?></button>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
<?php } ?>
<script type="text/javascript">
    var downloadattachment =  function(id){
        window.location = "index.php?r=qa/qainspection/download&record_id="+id;
    }
</script>
