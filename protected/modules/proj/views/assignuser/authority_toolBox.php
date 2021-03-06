<div class="row" style="margin-left: -20px;">
    <div class="col-xs-8">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">
                <input type="text" style="display: none;">
                <input type="hidden" name="q[program_id]" value="<?php echo $program_id; ?>">
                <div style="padding-left: 5px;width:95px">
                    <input type="text" class="form-control input-sm" name="q[user_name]" style="width: 120px;" placeholder="<?php echo Yii::t('comp_staff', 'User_name'); ?>">
                </div>
                
                <div style="margin-top: -30px;margin-left: 135px">
                    <select class="form-control input-sm" name="q[status]" >
                        <option value="">-<?php echo Yii::t('proj_project_user','status');?>-</option>
                        <?php
                        $status_list = ProgramUser::statusText(); //状态text
                        foreach ($status_list as $type_no => $status) {
                            echo "<option value='{$type_no}'>{$status}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div style="margin-top: -30px;margin-left: 310px">
                    <select class="form-control input-sm" name="q[epss_status]" >
                        <option value="">-Epss-</option>
                        <option value='1'>Exists</option>
                        <option value='2'>Not Exists</option>
                    </select>
                </div>
                <input id="tag" name="q[tag]" type="hidden">
                <div style="margin-top: -30px;margin-left: 450px;width:110px">
                    <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-xs-4">
        <div class="dataTables_filter" >
            <label>
                <?php
                    $pro_model = Program::model()->findByPk($program_id);
                    $faceapp_sign = $pro_model->faceapp_sign;
                    if($faceapp_sign == '1'){
                ?>
                        <button class="btn btn-primary btn-sm" onclick="itemUserSync('<?php echo $program_id; ?>')">Sync (Biometric)</button>
                <?php
                    }
                ?>
                <button class="btn btn-primary btn-sm" onclick="itemWorkforceSync('<?php echo $program_id; ?>')">Sync (Manpower)</button>
            </label>
        </div>
    </div>
</div>