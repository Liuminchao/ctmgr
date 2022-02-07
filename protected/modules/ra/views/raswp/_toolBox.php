<div class="row" style="margin-left: -20px;">
    <div class="col-xs-11">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">
                <div class="col-xs-2 padding-lr5" style="width: 180px;">
                    <select class="form-control input-sm" name="q[program_id]" id="form_program_id" style="width: 100%;">
                        <!--                        <option value="">----><?php //echo Yii::t('proj_report', 'report_program').'('.Yii::t('common', 'is_requried').')'; ?><!----</option>-->
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
                    <select class="form-control input-sm" name="q[con_id]" id="form_con_id" style="width: 100%;">
                        <option value="">--<?php echo Yii::t('comp_routine', 'company'); ?>--</option>
                        <?php
                        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
                        $args['program_id'] = $program_id;
                        $contractor_list = Contractor::Mc_scCompList($args);
                        if($contractor_list) {
                            foreach ($contractor_list as $k => $name) {
                                echo "<option value='{$k}'>{$name}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <?php
                $startDate = Utils::DateToEn(date('Y-m-d',strtotime('-1 day')));
                //                $endDate = Utils::DateToEn(date('Y-m-d',strtotime('-1 day')));
                ?>
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
                <div class="col-xs-2 padding-lr5" style="width: 160px;">
                    <select class="form-control input-sm" name="q[type_id]" style="width: 100%;">
                        <option value="">--<?php echo Yii::t('comp_ra', 'Worker type'); ?>--</option>
                        <?php
                        $worker_type = WorkerType::getType();//工种列表
                        foreach ($worker_type as $k => $type) {
                            echo "<option value='{$k}'>{$type}</option>";
                        }
                        ?>
                    </select>
                </div>
                <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></a>
            </form>
        </div>
    </div>
    <div class="col-xs-1">
        <div class="dataTables_filter" >
            <label>
<!--                <button class="btn btn-primary btn-sm" onclick="itemDownload()">--><?php //echo Yii::t('comp_ra', 'Batch download'); ?><!--</button>-->
                <button class="btn btn-primary btn-sm" onclick="itemApply('<?php echo $program_id; ?>')"><?php echo Yii::t('comp_ra', 'Batch apply'); ?></button>
                <!--                <button class="btn btn-primary btn-sm" onclick="itemTabs()">图片上传</button>-->
            </label>
        </div>
    </div>
</div>