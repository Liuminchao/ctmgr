<div id='msgbox' class='alert alert-dismissable ' style="display:none;">
    <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
    <strong id='msginfo'></strong><span id='divMain'></span>
</div>


<ul id="myTab" class="nav nav-tabs">
    <li class="active"><a href="index.php?r=comp/staff/tabs&user_id=<?php echo $user_id; ?>&mode=<?php echo $_mode_; ?>&title=<?php echo $title; ?>" ><?php echo Yii::t('comp_staff','Base Info');?></a></li>
    <li ><a href="index.php?r=comp/staff/pertabs&user_id=<?php echo $user_id; ?>&mode=<?php echo $_mode_; ?>&title=<?php echo $title; ?>"><?php echo Yii::t('comp_staff','Personal Info');?></a></li>
    <li><a href="index.php?r=comp/staff/instabs&user_id=<?php echo $user_id; ?>&mode=<?php echo $_mode_; ?>&title=<?php echo $title; ?>"><?php echo Yii::t('comp_staff','ins');?></a></li>
</ul>
<div id="hide">
    <input id="hid" type="hidden" name="satff" value="<?php echo $id;?>">
</div>
<div id="myTabContent" class="tab-content">
    <div class="tab-pane fade in active" id="base">
        <br/>
        <?php

        if($_mode_ == "insert"){
            $this->renderPartial('_form', array('model' => $model, 'infomodel' => $infomodel, '_mode_' => 'insert','user_id' => $user_id,'msg' => $msg,'roleList' => $roleList, 'myRoleList' => $myRoleList));
        }else{
            $this->renderPartial('_form', array('model' => $model, 'infomodel' => $infomodel, '_mode_' => 'edit','user_id' => $user_id,'msg' => $msg,'roleList' => $roleList, 'myRoleList' => $myRoleList));
        }?>
    </div>
</div>
<script src="js/jquery.1.7.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/AdminLTE/app.js"></script>