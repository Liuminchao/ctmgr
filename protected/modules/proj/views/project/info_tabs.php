<div id='msgbox' class='alert alert-dismissable ' style="display:none;">
    <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
    <strong id='msginfo'></strong><span id='divMain'></span>
</div>


<ul id="myTab" class="nav nav-tabs">
    <li ><a href="index.php?r=proj/project/tabs&program_id=<?php echo $program_id; ?>&mode=<?php echo $_mode_; ?>&title=<?php echo $title; ?>" ><?php echo Yii::t('common', 'Base Info');?></a></li>
    <li class="active"><a href="index.php?r=proj/project/pertabs&program_id=<?php echo $program_id; ?>&mode=<?php echo $_mode_; ?>&title=<?php echo $title; ?>"><?php echo Yii::t('proj_project', 'Additional Information');?></a></li>
</ul>
<div id="hide">
    <input id="hid" type="hidden" name="satff" value="<?php echo $id;?>">
</div>
<div id="myTabContent" class="tab-content">
    <div class="tab-pane fade in active" id="personal">
        <br/>
        <?php
        //var_dump($msg);
        if($_mode_ == "insert"){
            $this->renderPartial('info_form', array('model' => $model, 'infomodel' => $infomodel, '_mode_' => 'insert','program_id' => $program_id,'msg' => $msg));
        }else{
            $this->renderPartial('info_form', array('model' => $model, 'infomodel' => $infomodel, '_mode_' => 'edit','program_id' => $program_id,'msg' => $msg));
        }?>
    </div>
    <!--    <div class="tab-pane fade" id="ind">
        <br/>
            <?php
    //            if($_mode_ == "insert"){
    //                $this->renderPartial('_indform', array('infomodel' =>$infomodel,'user_id' => $user_id,'_mode_' => 'insert','msg' => $ins_msg));
    //            }else{
    //                $this->renderPartial('_indform', array('infomodel' =>$infomodel,'user_id' => $user_id,'_mode_' => 'edit','msg' => $ins_msg));
    //            }
    ?>
    </div>-->
</div>
<script src="js/jquery.1.7.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/AdminLTE/app.js"></script>
<script type="text/javascript">

</script>