<div id='msgbox' class='alert alert-dismissable ' style="display:none;">
    <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
    <strong id='msginfo'></strong><span id='divMain'></span>
</div>


<ul id="myTab" class="nav nav-tabs">
    <li class="active"><a href="index.php?r=statistics/module/tabs&id=<?php echo $id; ?>&name=<?php echo $name; ?>"><?php echo Yii::t('comp_statistics','Business daily statistics') ?></a></li>
    <li ><a href="index.php?r=statistics/module/monthtabs&id=<?php echo $id; ?>&name=<?php echo $name; ?>"><?php echo Yii::t('comp_statistics','Business monthly statistics') ?></a></li>
</ul>
<div id="hide">
    <input id="hid" type="hidden" name="contractor_id" value="<?php echo $id;?>">
</div>
<div id="myTabContent" class="tab-content">
    <div class="tab-pane fade in active" id="day">
        <br/>
        <?php $this->actionList($id,$name); ?>
    </div>
<!--    <div class="tab-pane fade" id="month">-->
<!--        <br/>-->
<!--        --><?php //$this->actionMonthList(); ?>
<!--    </div>-->
</div>
<script src="js/jquery.1.7.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/AdminLTE/app.js"></script>
<script type="text/javascript">

    $(function () {
//        $('#myTab a:first').tab('show');//初始化显示哪个tab
//
//        $('#myTab a').click(function (e) {
//            e.preventDefault();//阻止a链接的跳转行为
//            $(this).tab('show');//显示当前选中的链接及关联的content
//        })
    })
</script>