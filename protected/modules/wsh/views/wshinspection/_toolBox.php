<div class="row" style="margin-left: -20px;">
    <div class="col-xs-12">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">
                <?php  $contractor_id = Yii::app()->user->contractor_id;   ?>
                <input type="hidden" name="q[program_id]" id="program_id" value="<?php echo $program_id ?>">

                <div class="col-xs-2 padding-lr5" style="width: 245px;">
                    <select class="form-control input-sm" name="q[con_id]" id="form_con_id" style="width: 100%;">
                        <option value="">--<?php echo Yii::t('comp_safety', 'company'); ?>--</option>
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
                <div class="col-xs-2 padding-lr5" style="width: 200px;">
                    <select class="form-control input-sm" name="q[type_id]" style="width: 100%;">
                        <option value="">--<?php echo Yii::t('comp_safety', 'safety_type'); ?>--</option>
                        <?php
                        $type_list = SafetyCheckType::typeText();
                        foreach ($type_list as $k => $type) {
                            echo "<option value='{$k}'>{$type}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-xs-2 padding-lr5" style="width: 160px;">
                    <select class="form-control input-sm" name="q[findings_id]" id="findings_id" style="width: 100%;">
                        <option value="">--<?php echo Yii::t('comp_safety', 'safety_finding'); ?>--</option>
                        <?php
                        $type_list = SafetyCheckFindings::typeText();
                        foreach ($type_list as $k => $type) {
                            echo "<option value='{$k}'>{$type}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-xs-2 padding-lr5" style="width: 160px;">
                    <input type="text" class="form-control input-sm" name="q[initiator]" placeholder="<?php echo Yii::t('comp_safety', 'Initiator'); ?>">
                </div>
                <div class="col-xs-2 padding-lr5" style="width: 160px;">
                    <input type="text" class="form-control input-sm" name="q[person_in_charge]" placeholder="<?php echo Yii::t('comp_safety', 'Person In Charge'); ?>">
                </div>
                <div class="col-xs-2 padding-lr5" style="width: 160px;">
                    <input type="text" class="form-control input-sm" name="q[person_responsible]" placeholder="<?php echo Yii::t('comp_safety', 'Person Responsible'); ?>">
                </div>
                <div class="col-xs-1 padding-lr5" style="width: 40px;">
                    <?php echo Yii::t('license_licensepdf', 'from'); ?>
                </div>
                <div class="col-xs-2 padding-lr5" style="width: 183px;">
                    <div class="input-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control input-sm tool-a-search" name="q[start_date]"
                                   id="start_date" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" placeholder="<?php echo Yii::t('comp_safety', 'apply_time'); ?>"/>
                        </div>
                    </div>
                </div>
                <div class="col-xs-1 padding-lr5" style="width: 40px;">
                    <?php echo Yii::t('license_licensepdf', 'to'); ?>
                </div>
                <div class="col-xs-2 padding-lr5" style="width: 183px;">
                    <div class="input-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control input-sm tool-a-search" name="q[end_date]"
                                   id="end_date" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" placeholder="<?php echo Yii::t('comp_safety', 'apply_time'); ?>"/>
                        </div>
                    </div>
                </div>
                <div class="col-xs-2 padding-lr5" style="width: 140px;">
                    <select class="form-control input-sm" name="q[status]" id="status" style="width: 100%;">
                        <option value="">--Status--</option>
                        <?php
                        $status_list = SafetyCheck::statusText();
                        foreach ($status_list as $k => $type) {
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
<script type="application/javascript">
    //公司和类型半联动
//    $('#program_id').change(function(){
//        //alert($(this).val());
//
//        var selObj = $("#findings_id");
//        var selOpt = $("#findings_id option");
//
//        if ($(this).val() == 0) {
//            selOpt.remove();
//            return;
//        }
//        $.ajax({
//            type: "POST",
//            url: "index.php?r=wsh/wshinspection/querytype",
//            data: {program_id:$("#program_id").val()},
//            dataType: "json",
//            success: function(data){ //console.log(data);
//
//                selOpt.remove();
//                if (!data) {
//                    return;
//                }
//                selObj.append("<option value=''>--<?php //echo Yii::t('comp_safety', 'safety_type'); ?>//--</option>");
//                for (var o in data) {//console.log(o);
//                    selObj.append("<option value='"+o+"'>"+data[o]+"</option>");
//                }
//            },
//        });
//    });
</script>