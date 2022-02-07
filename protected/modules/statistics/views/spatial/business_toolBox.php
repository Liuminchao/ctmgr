<div class="row" style="margin-left: -20px;">
    <div class="col-xs-10">
        <div class="dataTables_length">
            <?php
            $q = $_REQUEST['q'];

            ?>
            <form name="_query_form" id="_query_form" role="form">

                <div class="col-xs-2 padding-lr5" style="width: 145px;">
                    <div class="input-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <?php
                                $date = Utils::DateToEn(date('Y-m-d',strtotime('-1 day')));
                            ?>
                            <input type="text" class="form-control input-sm tool-a-search" name="q[date]"
                              value="<?php  echo $date; ?>"     id="q_start_date" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" placeholder="<?php echo Yii::t('common', 'date_of_application'); ?>"/>
                        </div>
                    </div>
                </div>

                <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search');?></a>
                <!--<a class="btn btn-primary btn-sm" href="javascript:itemExport()"><?php echo Yii::t('proj_report', 'export'); ?></a>-->

                <!--<a class="right" id="export"><strong onclick="itemExport();">导出Excel</strong></a>-->

            </form>
        </div>
    </div>
    <div class="col-xs-2">
        <div class="dataTables_filter" >

        </div>
    </div>
</div>
<script type="text/javascript" src="js/JQdate/WdatePicker.js"></script>