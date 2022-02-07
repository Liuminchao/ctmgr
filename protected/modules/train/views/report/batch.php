<div class="row" style="margin-left: -20px;">
    <div class="col-xs-12">
        <form name="_query_form" id="_query_form" role="form">
            <?php  $contractor_id = Yii::app()->user->contractor_id;   ?>
            <!--                <div class="col-xs-3 padding-lr5" >-->
            <!--                    <input type="text" class="form-control input-sm" name="q[check_id]" placeholder="--><?php //echo Yii::t('comp_safety', 'check_id'); ?><!--">-->
            <!--                </div>-->
            <div style="margin-left: 20px;width:110px">
                <select class="form-control input-sm" name="q[program_id]" id="program_id" style="width: 100%;">
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
            <div style="margin-top: -30px;margin-left: 140px;width:110px">
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
            </div>
            <?php
            $time = Utils::MonthToEn(date('Y-m'));
            ?>

            <div style="margin-top: -30px;margin-left: 260px;width:140px">
                <div class="input-group has-error">
                    <div class="input-group">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type="text" class="form-control input-sm tool-a-search" name="q[start_date]"
                               value="<?php echo $time ?>"  id="q_start_date" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" placeholder="<?php echo Yii::t('comp_routine', 'date_of_application'); ?>"/>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>
<div class="row" style="margin-top: 60px;text-align: center">
    <button class="btn btn-primary btn-sm" onclick="itemExport()"><?php echo Yii::t('comp_staff', 'Batch import'); ?></button>
</div>
    <script type="text/javascript">
    //导出PDF
    var itemExport = function () {
        var objs = document.getElementById("_query_form").elements;
        var i = 0;
        var cnt = objs.length;
        var obj;
        var url = '';

        for (i = 0; i < cnt; i++) {
            obj = objs.item(i);
            url += '&' + obj.name + '=' + obj.value;
        }
        window.location = "index.php?r=train/report/export"+url;
    }
</script>