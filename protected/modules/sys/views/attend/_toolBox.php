<div class="row" style="margin-left: -20px;">
    <div class="col-xs-9">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">
                 <?php
                //$startDate = date('Y-m-01', strtotime(date("Y-m-d")));
                //$endDate = date('Y-m-d', strtotime("$startDate +1 month -1 day"));
                $startDate = date("Y-m-01");
                $endDate = date("Y-m-d");
                ?>
                <div class="col-xs-2 padding-lr5" style="width: 145px;">
                    <div class="input-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control input-sm tool-a-search" name="q[start_date]" 
                                   value="<?php echo $startDate; ?>" id="q_start_date" placeholder="操作开始日期"/>
                        </div>
                    </div>
                </div>
                <div class="col-xs-2 padding-lr5" style="width: 145px;">
                    <div class="input-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control input-sm tool-a-search" name="q[end_date]" 
                                   value="<?php echo $endDate; ?>" id="q_end_date" placeholder="操作结束日期 "/>
                        </div>
                    </div>
                </div>
                <div class="col-xs-2 padding-lr5" >
                    <select class="form-control input-sm" name="q[record_status]" style="width: 100%;">
                        <option value="">--<?php echo Yii::t('sys_attend','card_result');?>--</option>
                        <?php
                        $attendRs = ProjectAttend::getRusult();
                        foreach ($attendRs as $value => $name) {
                            echo "<option value=" . $value . ">" . $name . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></a>
            </form>
        </div>
    </div>
    
</div>