<div class="row" style="margin-left: -20px;">
    <div class="col-xs-9">

    </div>
    <div class="col-xs-3">
        <div class="dataTables_filter" >
            <label>
                <?php
                $pro_model = Program::model()->findAllByPk($program_id);//项目
                $program_name = $pro_model->program_name;
                ?>
                <button class="btn btn-primary btn-sm" onclick="itemDevice('<?php echo $program_id ?>','<?php echo $program_name ?>')">Back</button>
            </label>
        </div>
    </div>
</div>
<script type="text/javascript">
    //项目组设备
    var itemDevice = function (id,name) {
        window.location = "index.php?r=proj/assignuser/devicelist&ptype=<?php echo Yii::app()->session['project_type'];?>&id=" + id+"&name="+name;
        //window.location = "index.php?r=proj/assignuser/edit&ptype=<?php echo Yii::app()->session['project_type'];?>&id=" + id;
    }
</script>