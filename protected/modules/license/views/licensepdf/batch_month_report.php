
<div class="row" style="margin-left: -20px;">
    <div class="col-xs-12">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">
                <!--                <div class="col-xs-3 padding-lr5" >-->
                <!--                    <input type="text" class="form-control input-sm" name="q[title]" placeholder="--><?php //echo Yii::t('tbm_meeting', 'title'); ?><!--">-->
                <!--                </div>-->

                <?php
                //                $startDate = Utils::DateToEn(date('Y-m-d',strtotime('-1 day')));
                $second_time = Utils::MonthToEn(date('Y-m'));
                //                $endDate = Utils::DateToEn(date('Y-m-d',strtotime('-1 day')));
                ?>
                <!--                <div class="col-xs-1 padding-lr5" style="width: 50px;">-->
                <!--                    --><?php //echo Yii::t('license_licensepdf', 'from'); ?>
                <!--                </div>-->
                <div class="col-xs-2 padding-lr5" style="width: 145px;">
                    <div class="input-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control input-sm tool-a-search" name="q[start_date]"
                                   value="<?php echo $second_time; ?>"    id="month" onclick="WdatePicker({lang:'en',dateFmt:'MMM yyyy'})" placeholder="<?php echo Yii::t('common', 'date_of_application'); ?>"/>
                        </div>
                    </div>
                </div>
                <!--                <div class="col-xs-1 padding-lr5" style="width: 40px;">-->
                <!--                    --><?php //echo Yii::t('license_licensepdf', 'to'); ?>
                <!--                </div>-->
                <!--                <div class="col-xs-2 padding-lr5" style="width: 145px;">-->
                <!--                    <div class="input-group has-error">-->
                <!--                        <div class="input-group">-->
                <!--                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>-->
                <!--                            <input type="text" class="form-control input-sm tool-a-search" name="q[end_date]"-->
                <!--                                   id="end_date" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" placeholder="--><?php //echo Yii::t('common', 'date_of_application'); ?><!--"/>-->
                <!--                        </div>-->
                <!--                    </div>-->
                <!--                </div>-->
            </form>
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-10">
            <button type="button" id="sbtn" onclick="itemExcelExport()" class="btn btn-primary btn-lg"><?php echo Yii::t('proj_report', 'export'); ?></button>
        </div>
    </div>
</div>

<script type="text/javascript">
    //导出
    var itemExcelExport = function () {
        var month = $('#month').val();
//        var end_date = $('#end_date').val();
        var id = $('#program_id').val();
        if(id == ''){
            alert('Please Select Program');
            return;
        }
//        alert(start_date);
//        alert(end_date);
        window.location = "index.php?r=license/upload/export&program_id="+id+"&month="+month;
        //window.location = "index.php?r=proj/assignuser/edit&ptype=<?php echo Yii::app()->session['project_type'];?>&id=" + id;
    }
</script>
