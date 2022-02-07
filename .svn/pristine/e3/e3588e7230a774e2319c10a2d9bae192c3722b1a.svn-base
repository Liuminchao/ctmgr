<div class="row" style="margin-left: -20px;">
    <div class="col-xs-9">
        <div class="dataTables_length">
            <form name="query_form" id="query_form" role="form">
                
                <input type="hidden" name="q[father_proid]" value="<?php echo $father_proid;    ?>">
                
                <div class="col-xs-2 padding-lr5" style="width:200px">
                    <input type="text" class="form-control input-sm" name="q[subcon_name]" placeholder="<?php echo Yii::t('proj_project', 'sub_contractor_name'); ?>">
                </div>
<!--                <div class="col-xs-2 padding-lr5" >
                    <input type="text" class="form-control input-sm" name="q[contractor_id]" placeholder="<?php echo Yii::t('proj_project', 'contractor_id'); ?>">
                </div>-->
                <div class="col-xs-2 padding-lr5" style="width: 90px;">
                    <select class="form-control input-sm" name="q[status]" style="width: 100%;">
                        <option value="">-<?php echo Yii::t('proj_project', 'status'); ?>-</option>
                        <?php
                        $status_list = Program::statusText();
                        foreach ($status_list as $k => $source) {
                            echo "<option value='{$k}'>{$source}</option>";
                        }
                        ?>
                    </select>
                </div>
                <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search');?></a>
            </form>
        </div>
    </div>
    <div class="col-xs-3">
        <div class="dataTables_filter" >
            <label>
            <?php if($father_model->status == Program::STATUS_NORMAL):  ?>
                <?php
                $operator_id = Yii::app()->user->id;
                $authority_list = OperatorProject::authorityList($operator_id);
                $value = $authority_list[$father_proid];
                if($value == '1') {
                ?>
                    <button class="btn btn-primary btn-sm" onclick="itemAdd('<?php echo $father_proid; ?>')"><?php echo Yii::t('proj_project', 'Add Sub Proj');   ?></button>
                <?php } ?>
                <?php endif ?>
                <button class="btn btn-primary btn-sm" onclick="back('<?php echo $father_proid; ?>')"><?php echo Yii::t('common', 'button_back');  ?></button>
            </label>
        </div>
    </div>
</div>

<script type="text/javascript">
    //返回
    var back = function (id) {
        window.location = "index.php?r=proj/project/list&ptype=<?php echo Yii::app()->session['project_type'];?>&program_id="+id;
    }


</script>