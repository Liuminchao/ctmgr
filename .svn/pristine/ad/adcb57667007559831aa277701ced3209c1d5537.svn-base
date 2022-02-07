<div class="row" style="margin-left: -20px;">
    <div class="col-xs-9">
        <div class="dataTables_length">
            <?php
            $q = $_REQUEST['q'];

            ?>
            <form name="_query_form" id="_query_form" role="form">

                <div class="col-xs-2 padding-lr5" style="width: 180px;">
                    <?php echo $contractor_name;  ?>
                    <input type="hidden" name="q[contractor_id]" id="contractor_id" value="<?php echo $contractor_id;  ?>">
                </div>

                <div class="col-xs-2 padding-lr5" style="width: 180px;">
                    <select class="form-control input-sm" name="q[program_id]" id="form_program_id" style="width: 100%;">
                        <option value="">--<?php echo Yii::t('proj_report', 'report_program').'('.Yii::t('common', 'is_requried').')'; ?>--</option>
                        <?php
                        $args['contractor_id'] = $contractor_id;
                        $program_list = Program::McProgramList($args);
                        if($program_list) {
                            foreach ($program_list as $k => $name) {
                                echo "<option value='{$k}'>{$name}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

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
<!--                <div class="col-xs-2 padding-lr5" style="width: 145px;">-->
<!--                    <div class="input-group has-error">-->
<!--                        <div class="input-group">-->
<!--                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>-->
<!--                            <input type="text" class="form-control input-sm tool-a-search" name="q[end_date]"-->
<!--                                   value="--><?php //echo $endDate; ?><!--" id="q_end_date" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" placeholder="操作结束日期 "/>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->

                <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search');?></a>
                <!--<a class="btn btn-primary btn-sm" href="javascript:itemExport()"><?php echo Yii::t('proj_report', 'export'); ?></a>-->

                <!--<a class="right" id="export"><strong onclick="itemExport();">导出Excel</strong></a>-->

            </form>
        </div>
    </div>
    <div class="col-xs-3">
        <div class="dataTables_filter" >
            <label>
              <?php
                $operator_type   =  Yii::app()->user->getState('operator_type');
                if($operator_type == '00'){
              ?>
                <button class="btn btn-primary btn-sm" onclick="itemBack()"><?php echo Yii::t('common', 'button_back'); ?></button>
                <?php  } ?>
            </label>
        </div>
    </div>
</div>
<script type="text/javascript" src="js/JQdate/WdatePicker.js"></script>