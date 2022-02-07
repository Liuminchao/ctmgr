<div class="row" style="margin-left: -20px;">
    <div class="col-xs-12">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">
                <input type="hidden" id="program_id" name="q[program_id]" value="<?php echo $program_id; ?>">
                <div class="col-xs-2 padding-lr5" style="width: 180px;">
                    <select class="form-control input-sm" name="q[con_id]" id="form_con_id" style="width: 100%;">
                        <option value="">--<?php echo Yii::t('comp_routine', 'company'); ?>--</option>
                        <?php
                        $args['program_id'] = $program_id;
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
                //$endDate = Utils::DateToEn(date('Y-m-d',strtotime('-1 day')));
                ?>
                <div class="col-xs-1 padding-lr5" style="width: 40px;">
                    <?php echo Yii::t('license_licensepdf', 'from'); ?>
                </div>
                <div class="col-xs-2 padding-lr5" style="width: 183px;">
                    <div class="input-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control input-sm tool-a-search" name="q[start_date]"
                                    id="q_start_date" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" placeholder="<?php echo Yii::t('common', 'date_of_application'); ?>"/>
                        </div>
                    </div>
                </div>
                <div class="col-xs-1 padding-lr5" style="width: 20px;">
                    <?php echo Yii::t('license_licensepdf', 'to'); ?>
                </div>
                <div class="col-xs-2 padding-lr5" style="width: 183px;">
                    <div class="input-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control input-sm tool-a-search" name="q[end_date]"
                                   id="q_end_date" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" placeholder="<?php echo Yii::t('common', 'date_of_application'); ?>"/>
                        </div>
                    </div>
                </div>
                <div class="col-xs-3 padding-lr5" style="width: 175px;">
                    <select class="form-control input-sm" name="q[type_id]" id="type_id" style="width: 100%;">
                        <option value="">--<?php echo Yii::t('license_licensepdf', 'ptw_type'); ?>--</option>
                        <?php
                        $level_list = PtwType::typeByContractor($program_id);
                        foreach ($level_list as $k => $type) {
                            echo "<option value='{$k}'>{$type}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-xs-3 padding-lr5" style="width:147px">
                    <select class="form-control input-sm" name="q[status]" id="status" style="width: 100%;">
                        <option value="">--<?php echo Yii::t('proj_project', 'status'); ?>--</option>
                        <?php
                        $status_list = ApplyBasic::statusList();
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
            url: "index.php?r=license/licensepdf/querytype",
            data: {program_id:$("#program_id").val()},
            dataType: "json",
            success: function(data){ //console.log(data);

                selOpt.remove();
                if (!data) {
                    return;
                }
                selObj.append("<option value=''>--<?php echo Yii::t('license_licensepdf', 'ptw_type'); ?>--</option>");
                for (var o in data) {//console.log(o);
                    selObj.append("<option value='"+o+"'>"+data[o]+"</option>");
                }
            },
        });
    });
</script>