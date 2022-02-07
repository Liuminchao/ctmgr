<div class="row" style="margin-left: -20px;">
    <div class="col-xs-9">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">
                <div style="padding-top: -20px;padding-left: 5px;width:120px">
                    <?php echo $contractor_name; ?>
                    <input type="hidden" name="q[program_id]" value="<?php echo $program_id; ?>">
                    <input type="hidden" name="q[contractor_id]" value="<?php echo $contractor_id; ?>">
                </div>
                <div style="margin-left: 125px">
                    <input type="text" name="q[user_name]" style="width: 120px;" placeholder="<?php echo Yii::t('comp_staff', 'User_name'); ?>">
                </div>
                <div style="margin-top: -30px;margin-left: 255px">
                    <select class="form-control input-sm" name="q[status]" >
                        <option value="">-<?php echo Yii::t('proj_project_user','status');?>-</option>
                        <?php
                        $status_list = ProgramUser::statusSubText(); //状态text
                        //var_dump($teamlist);
                        foreach ($status_list as $type_no => $status) {
                            echo "<option value='{$type_no}'>{$status}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div style="margin-top: -30px;margin-left: 420px;width:110px">
                    <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></a>
                </div>
            </form>
        </div>
    </div>
    <!--    <div class="col-xs-3">
        <div class="dataTables_filter" >
            <label>
                <button class="btn btn-primary btn-sm" onclick="itemAdd()"><?php echo Yii::t('sys_role', 'AddRole'); ?></button>
            </label>
        </div>
    </div>-->
</div>