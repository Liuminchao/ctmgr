
<div class="row" style="margin-left: -20px;">
    <div class="col-xs-12">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">
                <?php  $contractor_id = Yii::app()->user->contractor_id;   ?>
                <!--                <div class="col-xs-3 padding-lr5" >-->
                <!--                    <input type="text" class="form-control input-sm" name="q[check_id]" placeholder="--><?php //echo Yii::t('comp_safety', 'check_id'); ?><!--">-->
                <!--                </div>-->
                <div style="padding-left: 5px;width:95px">
                    <input type="text" class="form-control input-sm" name="q[apply_name]" placeholder="<?php echo Yii::t('comp_routine', 'applicant_name'); ?>">
                </div>
                <div style="margin-top: -30px;margin-left: 105px;width:110px">
                    <select class="form-control input-sm" name="q[program_id]" id="program_id" style="width: 100%;">
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
                <div style="margin-top: -30px;margin-left: 225px;width:110px">
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
                $startDate = Utils::DateToEn(date('Y-m-d',strtotime('-1 day')));
                //                $endDate = Utils::DateToEn(date('Y-m-d',strtotime('-1 day')));
                ?>
<!--                <div style="margin-top: -30px;margin-left: 345px;width:110px">-->
<!--                    <div class="input-group has-error">-->
<!--                        <div class="input-group">-->
<!--                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>-->
<!--                            <input type="text" class="form-control input-sm tool-a-search" name="q[record_time]"-->
<!--                                   id="q_start_date" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" placeholder="--><?php //echo Yii::t('comp_routine', 'date_of_application'); ?><!--"/>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
                <div style="margin-top: -30px;margin-left: 345px;width:40px;height:30px;">
                    <?php echo Yii::t('license_licensepdf', 'from'); ?>
                </div>
                <div style="margin-top: -30px;margin-left: 395px;width:110px">
                    <div class="input-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control input-sm tool-a-search" name="q[start_date]"
                                   id="q_start_date" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" placeholder="<?php echo Yii::t('comp_routine', 'date_of_application'); ?>"/>
                        </div>
                    </div>
                </div>
                <div style="margin-top: -30px;margin-left: 515px;width:40px;height:30px;">
                    <?php echo Yii::t('license_licensepdf', 'to'); ?>
                </div>
                <div style="margin-top: -30px;margin-left: 565px;width:110px">
                    <div class="input-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control input-sm tool-a-search" name="q[end_date]"
                                   id="q_end_date" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" placeholder="<?php echo Yii::t('comp_routine', 'date_of_application'); ?>"/>
                        </div>
                    </div>
                </div>
                <div style="margin-top: -30px;margin-left: 685px;width:110px">
                    <select class="form-control input-sm" name="q[type_id]" id="type_id" style="width: 100%;">
                        <option value="">--<?php echo Yii::t('comp_qa', 'check_type'); ?>--</option>
                        <?php
                        $r = array_keys($program_list);
                        $type_list = QaCheckType::typeByContractor($r[0]);
                        foreach ($type_list as $k => $type) {
                            echo "<option value='{$k}'>{$type}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div style="margin-top: -30px;margin-left: 805px;width:110px">
                    <select class="form-control input-sm" id="form_id" name="q[form_id]" style="width: 100%;">
                        <option value="">--<?php echo Yii::t('comp_qa', 'form_type'); ?>--</option>
                    </select>
                </div>
                <div style="margin-top: -30px;margin-left: 925px;width:110px">
                    <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></a>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="application/javascript">

    //form初始化
    function FormInit(node) {
        return node.html('<option value="">--<?php echo Yii::t('comp_qa', 'form_type'); ?>--</option>');
    }

    //公司和类型半联动
    $('#program_id').change(function(){
        //alert($(this).val());

        var selObj = $("#type_id");
        var selOpt = $("#type_id option");

        if ($(this).val() == 0) {
            selOpt.remove();
            return;
        }
        $.ajax({
            type: "POST",
            url: "index.php?r=qa/qainspection/querytype",
            data: {program_id:$("#program_id").val()},
            dataType: "json",
            success: function(data){ //console.log(data);

                selOpt.remove();
                if (!data) {
                    return;
                }
                selObj.append("<option value=''>--<?php echo Yii::t('comp_routine', 'check_type'); ?>--</option>");
                for (var o in data) {//console.log(o);
                    selObj.append("<option value='"+o+"'>"+data[o]+"</option>");
                }
            },
        });
    });
    //类型和表单类型半联动
    $('#type_id').change(function(){

        var formObj = $("#form_id");
        var formOpt = $("#form_id option");
        FormInit(formObj);

        $.ajax({
            type: "POST",
            url: "index.php?r=qa/qainspection/queryform",
            data: {type_id:$("#type_id").val()},
            dataType: "json",
            success: function(data){ //console.log(data);
                if (!data) {
                    return;
                }
                for (var o in data) {//console.log(o);
                    formObj.append("<option value='"+o+"'>"+data[o]+"</option>");
                }
            },
        });
    });
</script>