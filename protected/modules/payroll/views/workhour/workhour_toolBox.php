<div class="row" style="margin-left: -20px;">
    <div class="col-xs-9">
        <div class="dataTables_length">
            <?php
            $q = $_REQUEST['q'];
            
            ?>
            <form name="_query_form" id="_query_form" role="form">
                
                <div class="col-xs-2 padding-lr5" style="width: 180px;">
                    <select class="form-control input-sm" name="q[program_id]" id="form_program_id" style="width: 100%;">
                        <option value="">--<?php echo Yii::t('proj_report', 'report_program').'('.Yii::t('common', 'is_requried').')'; ?>--</option>
                        <?php
                        foreach ($program_list as $k => $name) {
                            echo "<option value='{$k}'>{$name}</option>";
                        }
                        ?>
                    </select>
                </div>

<!--                <div class="col-xs-2 padding-lr5">
                    <input type="text" id="user_name" class="form-control input-sm" style="width: 120px" name="q[user_name]" placeholder="<?php echo Yii::t('comp_staff', 'User_name'); ?>">
                </div>-->
                <?php
                $startDate = Utils::DateToEn(date('Y-m-d',strtotime('now')));
//                $endDate = Utils::DateToEn(date('Y-m-d',strtotime('-1 day')));
                ?>
                <div class="col-xs-2 padding-lr5" style="width: 145px;">
                    <div class="input-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control input-sm tool-a-search" name="q[start_date]" 
                                   value="<?php echo $startDate; ?>" id="q_start_date" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" placeholder="操作开始日期"/>
                        </div>
                    </div>
                </div>
<!--                <div class="col-xs-2 padding-lr5" style="width: 145px;">
                    <div class="input-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control input-sm tool-a-search" name="q[end_date]" 
                                   value="<?php echo $endDate; ?>" id="q_end_date" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" placeholder="操作结束日期 "/>
                        </div>
                    </div>
                </div>-->
                
                <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search');?></a>
                             
<!--                <a class="right" id="addhour"><strong onclick="addHour();">添加</strong></a>                    -->
               
            </form>
        </div>
    </div>
    <div class="col-xs-3">
        <div class="dataTables_filter" >
            <label>          
                <button class="btn btn-primary btn-sm" onclick="itemBack()"><?php echo Yii::t('common', 'button_back');?></button>
            </label>
        </div>
    </div>
</div>
<script type="text/javascript" src="js/JQdate/WdatePicker.js"></script>
<script type="text/javascript">

</script>