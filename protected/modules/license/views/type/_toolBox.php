<div class="row" style="margin-left: -20px;">
    <div class="col-xs-9">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">
                <?php
                    $operator_type   =  Yii::app()->user->getState('operator_type');
                    if($operator_type != '00'){
                ?>
                    <div class="col-xs-2 padding-lr5">
                        <select class="form-control input-sm" name="q[type_id]" id="type_id" style="width: 100%;">
                            <option value='1'>System</option>
                            <option value='2'>Company</option>
                        </select>
                    </div>
                <?php } ?>
                <div class="col-xs-2 padding-lr5" >
                    <input type="text" class="form-control input-sm" name="q[type_name]" placeholder="<?php echo Yii::t('license_type', 'type_name');?>">
                </div>
                 <div class="col-xs-2 padding-lr5" >
                    <input type="text" class="form-control input-sm" name="q[type_name_en]" placeholder="<?php echo Yii::t('license_type', 'type_name_en');?>">
                </div>
                <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i><?php echo Yii::t('common', 'search'); ?></a>
            </form>
        </div>
    </div>
    <div class="col-xs-3">
        <div class="dataTables_filter" >
            <label>
                <button class="btn btn-primary btn-sm" onclick="itemAdd()"><?php echo Yii::t('license_type', 'Add Type');?></button>
            </label>
        </div>
    </div>
</div>