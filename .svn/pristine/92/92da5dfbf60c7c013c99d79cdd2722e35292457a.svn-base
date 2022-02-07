<div class="row" style="margin-left: -20px;">
    <div class="col-xs-9">
        <div class="dataTables_length">
            <?php
            $q = $_REQUEST['q'];
            
            ?>
            <form name="_query_form" id="_query_form" role="form">
                
                <div class="col-xs-2 padding-lr5">
                    <input type="text" id="user_name" class="form-control input-sm" style="width: 120px" name="q[user_name]" placeholder="<?php echo Yii::t('comp_staff', 'User_name'); ?>">
                </div>
                <?php
//                $month = Utils::DateToEn(date('m',strtotime('+1 day')));
                ?>
                <div class="col-xs-2 padding-lr5" style="width: 145px;">
                    <div class="input-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control input-sm tool-a-search" name="q[month]" 
                                   value="<?php echo $month; ?>" id="q_month" onclick="WdatePicker({lang:'en',dateFmt:'MMM yyyy'})" placeholder="月份"/>
                        </div>
                    </div>
                </div>
                
                <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search');?></a>
                                            
            </form>
        </div>
    </div>
    <div class="col-xs-3">
        <div class="dataTables_filter" >
            <label>
                <button class="btn btn-primary btn-sm" onclick="itemBack()"><?php echo Yii::t('common', 'button_back');?></button>
                <button class="btn btn-primary btn-sm" onclick="itemCalculate()"><?php echo Yii::t('pay_payroll', 'contentHeader_salary_calculate');?></button>
                <button class="btn btn-primary btn-sm" onclick="itemStorage()"><?php echo Yii::t('pay_payroll', 'contentHeader_salary_storage');?></button>
            </label>
        </div>
    </div>
</div>
<script type="text/javascript" src="js/JQdate/WdatePicker.js"></script>
<script type="text/javascript">
//    start = $('#q_start_date').val();
//    end = $('#q_end_date').val();
//    if(start!=null||end!=null){
//        alert('查询日期不能为空');
//        return;
//    }
//    days = start.substring(5,7);
//    alert(days);
</script>