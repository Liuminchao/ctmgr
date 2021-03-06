<div class="row" style="margin-left: -20px;">
    <div class="col-xs-12">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">
                <div class="col-xs-2 padding-lr5" style="width: 180px;">
                    <select class="form-control input-sm" name="q[program_id]" id="form_program_id" style="width: 100%;">
                        <!--                        <option value="">----><?php //echo Yii::t('comp_statistics', 'Project') ?><!----</option>-->
                        <?php
                        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
                        $args['program_id'] = $program_id;
                        $program_list = Program::McProgramList($args);
                        if($program_list) {
                            foreach ($program_list as $k => $name) {
                                echo "<option value='{$k}'>{$name}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-xs-2 padding-lr5" style="width: 180px;">
                    <?php
                    $contractor_id = Yii::app()->user->getState('contractor_id');
                    $pro_model =Program::model()->findByPk($args['program_id']);
                    //分包项目
                    if($pro_model->main_conid != $args['contractor_id']){
                        ?>
                        <select class="form-control input-sm" name="q[con_id]" id="form_con_id" style="width: 100%;">
                            <!--                        <option value="">----><?php //echo Yii::t('comp_routine', 'company'); ?><!----</option>-->
                            <?php
                            $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
                            $contractor_list = Contractor::Mc_scCompList($args);
                            if($contractor_list) {
                                foreach ($contractor_list as $k => $name) {
                                    echo "<option value='{$k}'>{$name}</option>";
                                }
                            }
                            ?>
                        </select>
                    <?php }else{ ?>
                        <select class="form-control input-sm" name="q[con_id]" id="form_con_id" style="width: 100%;">
                            <option value="">--<?php echo Yii::t('comp_routine', 'company'); ?>--</option>
                            <?php
                            $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
                            $contractor_list = Contractor::Mc_scCompList($args);
                            if($contractor_list) {
                                foreach ($contractor_list as $k => $name) {
                                    echo "<option value='{$k}'>{$name}</option>";
                                }
                            }
                            ?>
                        </select>
                    <?php } ?>
                </div>
                <?php
                $startDate = Utils::DateToEn(date('Y-m-d',strtotime('-1 day')));
                //                $endDate = Utils::DateToEn(date('Y-m-d',strtotime('-1 day')));
                ?>
                <!--                <div class="col-xs-2 padding-lr5" style="width: 145px;">-->
                <!--                    <div class="input-group has-error">-->
                <!--                        <div class="input-group">-->
                <!--                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>-->
                <!--                            <input type="text" class="form-control input-sm tool-a-search" name="q[record_time]"-->
                <!--                                   id="q_start_date" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" placeholder="--><?php //echo Yii::t('common', 'date_of_application'); ?><!--"/>-->
                <!--                        </div>-->
                <!--                    </div>-->
                <!--                </div>-->
                <div class="col-xs-1 padding-lr5" style="width: 50px;">
                    <?php echo Yii::t('license_licensepdf', 'from'); ?>
                </div>
                <div class="col-xs-2 padding-lr5" style="width: 145px;">
                    <div class="input-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control input-sm tool-a-search" name="q[start_date]"
                                   id="q_start_date" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" placeholder="<?php echo Yii::t('common', 'date_of_application'); ?>"/>
                        </div>
                    </div>
                </div>
                <div class="col-xs-1 padding-lr5" style="width: 40px;">
                    <?php echo Yii::t('license_licensepdf', 'to'); ?>
                </div>
                <div class="col-xs-2 padding-lr5" style="width: 145px;">
                    <div class="input-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control input-sm tool-a-search" name="q[end_date]"
                                   id="q_end_date" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" placeholder="<?php echo Yii::t('common', 'date_of_application'); ?>"/>
                        </div>
                    </div>
                </div>
                <div class="col-xs-3 padding-lr5" style="width: 160px;">
                    <select class="form-control input-sm" name="q[type_id]" style="width: 100%;">
                        <option value="">--<?php echo Yii::t('train', 'training_type'); ?>--</option>
                        <?php
                        $level_list = MeetingType::typeText();
                        foreach ($level_list as $k => $type) {
                            echo "<option value='{$k}'>{$type}</option>";
                        }
                        ?>
                    </select>
                </div>
                <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></a>
            </form>
        </div>
    </div>
</div>