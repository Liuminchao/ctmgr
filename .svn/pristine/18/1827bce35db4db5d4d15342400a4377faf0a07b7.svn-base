
<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label for="working_life"
                   class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('license_licensepdf', 'ptw_report');?></label>
            <div class="col-sm-6 padding-lr5">
                <button class="btn btn-default" type='button' onclick="downloadptw('<?php echo $apply_id ?>','<?php echo $app_id ?>')"><?php echo Yii::t('common', 'download');?></button>
            </div>
        </div>
    </div>
</div>
<?php if(count($check_list) > 0){
        $check_list_str = '';
    ?>
    <?php for($i=0;$i<=count($check_list)-1;$i++){
            $check_list_str .= $check_list[$i]['check_id'].'|';
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
<?php
    $check_list_str = substr($check_list_str, 0, strlen($check_list_str) - 1);
} ?>
<?php
    $apply = ApplyBasic::model()->findByPk($apply_id);//许可证基本信息表
    //报告定制化
    $program_id = $apply->program_id;
    $pro_model = Program::model()->findByPk($program_id);
    $pro_params = $pro_model->params;//项目参数
    if($pro_params != '0') {
        $pro_params = json_decode($pro_params, true);
        //判断是否是迁移的
        if (array_key_exists('ptw_report', $pro_params)) {
            $params['type'] = $pro_params['ptw_report'];
        } else {
            $params['type'] = 'A';
        }
    }else{
        $params['type'] = 'A';
    }
//    if($params['type'] == 'C') {
        ?>
        <div class="row">
            <button type='button' class="btn btn-default"
                    onclick="itemMergepdf('<?php echo $apply_id ?>','<?php echo $check_list_str ?>')"><?php echo Yii::t('comp_qa', 'merge_report'); ?></button>
        </div>

<script type="text/javascript">
    var downloadptw =  function(id,app_id){
        window.location = "index.php?r=license/licensepdf/staffdownload&apply_id="+id+"&app_id="+app_id;
    }
    var downloadcheck =  function(id){
        window.location = "index.php?r=routine/routineinspection/downloadpdf&check_id="+id;
    }
    var itemMergepdf =  function(id,str){
        window.location = "index.php?r=license/licensepdf/mergereport&apply_id="+id+"&checklist_str="+str;
    }
</script>
