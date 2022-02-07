<div class="row" style="margin-left: -20px;">
    <div class="col-xs-12">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">
                <input type="hidden" name="q[worker_type]" value="1">
                <div class="col-xs-2 padding-lr5" style="width: 120px;">
                    <input type="text" id="user_name" class="form-control input-sm"  name="q[user_name]" placeholder="<?php echo Yii::t('comp_staff', 'User_name'); ?>">
                </div>
                <div class="col-xs-2 padding-lr5" style="width: 120px;">
                    <input type="text" id="user_phone" class="form-control input-sm" name="q[user_phone]" placeholder="<?php echo Yii::t('comp_staff', 'User_phone'); ?>">
                </div>                
                <div class="col-xs-2 padding-lr5" style="width: 120px;">
                    <input type="text" id="work_no" class="form-control input-sm"  name="q[work_no]" placeholder="<?php echo Yii::t('comp_staff', 'Work_no'); ?>">
                </div>

                <div class="col-xs-2 padding-lr5" style="width: 140px;">
                    <select class="form-control input-sm" name="q[work_pass_type]" style="width: 100%;">
                        <option value="">-<?php echo Yii::t('comp_staff', 'Work_pass_type'); ?>-</option>
                        <?php
                            $WorkPassType = Staff::WorkPassType();
                            //var_dump($teamlist);
                            foreach ($WorkPassType as $typeid => $typename) {
                                echo "<option value='{$typeid}'>{$typename}</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="col-xs-2 padding-lr5" style="width: 130px;">
                    <select class="form-control input-sm" name="q[nation_type]" style="width: 100%;">
                        <option value="">-<?php echo Yii::t('comp_staff', 'Nation_type'); ?>-</option>
                        <?php
                            $NationType = Staff::NationType();
                            //var_dump($teamlist);
                            foreach ($NationType as $typeid => $typename) {
                                echo "<option value='{$typeid}'>{$typename}</option>";
                            }
                        ?>
                    </select>
                </div>

                <div class="col-xs-2 padding-lr5" style="width: 150px;">
                    <select class="form-control input-sm" name="q[loane_type]" style="width: 100%;">
                        <option value="">-<?php echo Yii::t('comp_staff', 'loane_type'); ?>-</option>                        
                        <option value="1"><?php echo Yii::t('comp_staff', 'loane_type_1'); ?></option>
                        <option value="2"><?php echo Yii::t('comp_staff', 'loane_type_2'); ?></option>
                    </select>
                </div>

                <div class="col-xs-2 padding-lr5" style="width: 120px;">
                    <select class="form-control input-sm" name="q[category]" style="width: 100%;">
                        <option value="">-<?php echo Yii::t('comp_staff', 'category'); ?>-</option>
                        <?php
                        $category = Staff::Category();
                        //var_dump($teamlist);
                        foreach ($category as $typeid => $typename) {
                            echo "<option value='{$typeid}'>{$typename}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-xs-2 padding-lr5" style="width: 130px;">
                    <select class="form-control input-sm" name="q[role_id]" style="width: 100%;">
                        <option value="">-<?php echo Yii::t('comp_staff', 'Role_id'); ?>-</option>
                        <?php
                        $category = Role::roleList();
                        //var_dump($teamlist);
                        foreach ($category as $typeid => $typename) {
                            echo "<option value='{$typeid}'>{$typename}</option>";
                        }
                        ?>
                    </select>
                </div>

                
                <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i><?php echo Yii::t('common', 'search'); ?></a>
            </form>
        </div>
    </div>

    <div class="col-xs-12" style="margin-top: 5px;">
        <div class="dataTables_filter" >
            <label>
                <!--<button class="btn btn-primary btn-sm" onclick="itemExport()"><?php echo Yii::t('comp_staff', 'Batch export'); ?></button>-->
                <button class="btn btn-primary btn-sm" onclick="itemImport()"><?php echo Yii::t('comp_staff', 'Batch import'); ?></button>
                <button class="btn btn-primary btn-sm" onclick="itemAdd()"><?php echo Yii::t('comp_staff', 'Add Staff'); ?></button>
<!--                <button class="btn btn-primary btn-sm" onclick="itemTabs()">图片上传</button>-->
            </label>
        </div>
    </div>
</div>
<script type="text/javascript">
    document.getElementById("user_phone").onkeyup = function() {
        var str=(this.value).replace(/[^\d]/g, "");
        var maxlen = 11;
        if (str.length < maxlen) {
            maxlen = str.length;
        }
        var temp = "";
        for (var i = 0; i < maxlen; i++) {
            temp = temp + str.substring(i, i + 1);
            if (i != 0 && (i + 1) % 4 == 0 ) {
                temp = temp + " ";
            }
        }
        this.value=temp;
    }
    
    //证件号格式
    document.getElementById("work_no").onkeyup = function(evt) {
        evt = (evt) ? evt : ((window.event) ? window.event : "");  
        var key = evt.keyCode?evt.keyCode:evt.which;
        if ( key != 8 ){
            var str=(this.value).replace(/[^\d||-]/g, "");
            var maxlen = 9;
            if (str.length < maxlen) {
                maxlen = str.length;
            }
            var temp = "";
            for (var i = 0; i < maxlen; i++) {
                temp = temp + str.substring(i, i + 1);
                if (i==0 ||(i + 1)==5) {
                    temp = temp + " ";
                }
            }
            this.value=temp;
        }
    }    
</script>