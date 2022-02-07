<div class="row" style="margin-left: -20px;">
    <div class="col-xs-12">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">
                <?php  $contractor_id = Yii::app()->user->contractor_id;   ?>
<!--                <div class="col-xs-3 padding-lr5" >-->
<!--                    <input type="text" class="form-control input-sm" name="q[check_id]" placeholder="--><?php //echo Yii::t('comp_safety', 'check_id'); ?><!--">-->
<!--                </div>-->
                <div style="padding-left: 5px;width:95px">
                    <input type="text" class="form-control input-sm" name="q[initiator]" placeholder="<?php echo Yii::t('comp_quality', 'Initiator'); ?>">
                </div>
                <div style="margin-top: -30px;margin-left: 105px;width:95px">
                    <input type="text" class="form-control input-sm" name="q[person_in_charge]" placeholder="<?php echo Yii::t('comp_quality', 'Person In Charge'); ?>">
                </div>
<!--                <div style="margin-top: -30px;margin-left: 105px;width:95px">-->
<!--                    <input type="text" class="form-control input-sm" name="q[person_responsible]" placeholder="--><?php //echo Yii::t('comp_quality', 'Person Responsible'); ?><!--">-->
<!--                </div>-->
                <div style="margin-top: -30px;margin-left: 205px;width:110px">
                    <select class="form-control input-sm" name="q[program_id]" id="form_program_id" style="width: 100%;">
                        <!--                        <option value="">----><?php //echo Yii::t('proj_report', 'report_program').'('.Yii::t('common', 'is_requried').')'; ?><!----</option>-->
                        <?php
                        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
                        $program_list = Program::McProgramList($args);
                        if($program_list) {
                            foreach ($program_list as $k => $name) {
                                echo "<option value='{$k}'>{$name}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
<!--                <div style="margin-top: -30px;margin-left: 325px;width:110px">-->
<!--                    <select class="form-control input-sm" name="q[con_id]" id="form_con_id" style="width: 100%;">-->
<!--                        <option value="">----><?php //echo Yii::t('comp_quality', 'company'); ?><!----</option>-->
<!--                        --><?php
//                        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
//                        $contractor_list = Contractor::Mc_scCompList($args);
//                        if($contractor_list) {
//                            foreach ($contractor_list as $k => $name) {
//                                echo "<option value='{$k}'>{$name}</option>";
//                            }
//                        }
//                        ?>
<!--                    </select>-->
<!--                </div>-->
                <?php
                $startDate = Utils::DateToEn(date('Y-m-d',strtotime('-1 day')));
                //                $endDate = Utils::DateToEn(date('Y-m-d',strtotime('-1 day')));
                ?>
<!--                <div style="margin-top: -30px;margin-left: 545px;width:110px">-->
<!--                    <div class="input-group has-error">-->
<!--                        <div class="input-group">-->
<!--                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>-->
<!--                            <input type="text" class="form-control input-sm tool-a-search" name="q[record_time]"-->
<!--                                    id="q_start_date" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" placeholder="--><?php //echo Yii::t('comp_safety', 'date_of_initiation'); ?><!--"/>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
                <div style="margin-top: -30px;margin-left: 325px;width:30px;height:30px">
                    <?php echo Yii::t('license_licensepdf', 'from'); ?>
                </div>
                <div style="margin-top: -30px;margin-left: 365px;width:110px">
                    <div class="input-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control input-sm tool-a-search" name="q[start_date]"
                                   id="q_start_date" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" placeholder="<?php echo Yii::t('common', 'date_of_application'); ?>"/>
                        </div>
                    </div>
                </div>
                <div style="margin-top: -30px;margin-left: 485px;width:30px;height:30px">
                    <?php echo Yii::t('license_licensepdf', 'to'); ?>
                </div>
                <div style="margin-top: -30px;margin-left: 525px;width:110px">
                    <div class="input-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control input-sm tool-a-search" name="q[end_date]"
                                   id="q_end_date" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" placeholder="<?php echo Yii::t('common', 'date_of_application'); ?>"/>
                        </div>
                    </div>
                </div>
                <div style="margin-top: -30px;margin-left: 645px;width:110px">
                    <select class="form-control input-sm" name="q[assess_id]" style="width: 100%;">
                        <option value="">--<?php echo Yii::t('comp_quality', 'assess_name'); ?>--</option>
                        <?php
                        $type_list = QualityAssessType::allText();
                        foreach ($type_list as $k => $type) {
                            echo "<option value='{$k}'>{$type}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div style="margin-top: -30px;margin-left: 765px;width:110px">
                    <select class="form-control input-sm" name="q[item_id]" style="width: 100%;">
                        <option value="">--<?php echo Yii::t('comp_quality', 'item_name'); ?>--</option>
                        <?php
                        $type_list = QualityItemType::allText();
                        foreach ($type_list as $k => $type) {
                            echo "<option value='{$k}'>{$type}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div style="margin-top: -30px;margin-left: 885px;width:110px">
                    <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></a>
                </div>
            </form>
        </div>
    </div>
<!--    <div class="col-xs-1">-->
<!--        <div class="dataTables_filter" >-->
<!--            <label>-->
<!--                -->
<!--<!--                <button class="btn btn-primary btn-sm" onclick="itemPdf(--><?php ////echo $contractor_id; ?><!----><?php ////echo Yii::t('comp_safety', 'export'); ?><!--<!--</button>-->
<!--            </label>-->
<!--        </div>-->
<!--    </div>-->
</div>