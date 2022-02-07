
<div class="row">
     <div class="col-md-12">
         <h3 class="form-header text-blue">RA</h3>
     </div>
</div>
<?php if(count($ra_path) > 0){
    $ra = explode('|',$ra_path);
    ?>
    <?php foreach($ra as $count => $list){
        $path = substr($list,22);
        $ra_file = explode(',',$path);
        $name = str_replace("&","%26",$ra_file[0]);
        $list = '/opt/www-nginx/web'.str_replace("&","%26",$list);
        ?>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="working_life"
                           class="col-sm-6 control-label padding-lr5"><?php echo $name ?></label>
                    <div class="col-sm-6 padding-lr5">
                        <button class="btn btn-default" type='button' onclick="download('<?php echo $list ?>','<?php echo $name ?>')"><?php echo Yii::t('common', 'download');?></button>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
<?php } ?>
<div class="row">
    <div class="col-md-12">
        <h3 class="form-header text-blue">SWP</h3>
    </div>
</div>
<?php if(count($swp_path) > 0){
    $swp = explode('|',$swp_path);
    ?>
    <?php foreach($swp as $count => $list){
        $path = substr($list,23);
        $swp_file = explode(',',$path);
        $name = str_replace("&","%26",$swp_file[0]);
        $list = '/opt/www-nginx/web'.str_replace("&","%26",$list);
        ?>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="working_life"
                           class="col-sm-6 control-label padding-lr5"><?php echo $name ?></label>
                    <div class="col-sm-6 padding-lr5">
                        <button class="btn btn-default" type='button' onclick="download('<?php echo $list ?>','<?php echo $name ?>')"><?php echo Yii::t('common', 'download');?></button>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
<?php } ?>
<!-- 2021-09-08 开始 -->
<div class="row">
    <div class="col-md-12">
        <h3 class="form-header text-blue">Lifting Plan</h3>
    </div>
</div>
<?php if(count($lp_path) > 0){
    $lp = explode('|',$lp_path);
    ?>
    <?php foreach($lp as $count => $list){
        $path = substr($list,23);
        $lp_file = explode(',',$path);
        $name = str_replace("&","%26",$lp_file[0]);
        $list = '/opt/www-nginx/web'.str_replace("&","%26",$list);
        ?>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="working_life"
                           class="col-sm-6 control-label padding-lr5"><?php echo $name ?></label>
                    <div class="col-sm-6 padding-lr5">
                        <button class="btn btn-default" type='button' onclick="download('<?php echo $list ?>','<?php echo $name ?>')"><?php echo Yii::t('common', 'download');?></button>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
<?php } ?>
<div class="row">
    <div class="col-md-12">
        <h3 class="form-header text-blue">Fall Prevention Plan</h3>
    </div>
</div>
<?php if(count($fp_path) > 0){
    $fp = explode('|',$fp_path);
    ?>
    <?php foreach($fp as $count => $list){
        $path = substr($list,23);
        $fp_file = explode(',',$path);
        $name = str_replace("&","%26",$fp_file[0]);
        $list = '/opt/www-nginx/web'.str_replace("&","%26",$list);
        ?>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="working_life"
                           class="col-sm-6 control-label padding-lr5"><?php echo $name ?></label>
                    <div class="col-sm-6 padding-lr5">
                        <button class="btn btn-default" type='button' onclick="download('<?php echo $list ?>','<?php echo $name ?>')"><?php echo Yii::t('common', 'download');?></button>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
<?php } ?>
<div class="row">
    <div class="col-md-12">
        <h3 class="form-header text-blue">Method Statement</h3>
    </div>
</div>
<?php if(count($ms_path) > 0){
    $ms = explode('|',$ms_path);
    ?>
    <?php foreach($ms as $count => $list){
        $path = substr($list,23);
        $ms_file = explode(',',$path);
        $name = str_replace("&","%26",$ms_file[0]);
        $list = '/opt/www-nginx/web'.str_replace("&","%26",$list);
        ?>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="working_life"
                           class="col-sm-6 control-label padding-lr5"><?php echo $name ?></label>
                    <div class="col-sm-6 padding-lr5">
                        <button class="btn btn-default" type='button' onclick="download('<?php echo $list ?>','<?php echo $name ?>')"><?php echo Yii::t('common', 'download');?></button>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
<?php } ?>
<div class="row">
    <div class="col-md-12">
        <h3 class="form-header text-blue">Other (Organization Chart, Bizsafe Certificate, Layout Plan)</h3>
    </div>
</div>
<?php if(count($other_path) > 0){
    $other = explode('|',$other_path);
    ?>
    <?php foreach($other as $count => $list){
        $path = substr($list,23);
        $other_file = explode(',',$path);
        $name = str_replace("&","%26",$other_file[0]);
        $list = '/opt/www-nginx/web'.str_replace("&","%26",$list);
        ?>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="working_life"
                           class="col-sm-6 control-label padding-lr5"><?php echo $name ?></label>
                    <div class="col-sm-6 padding-lr5">
                        <button class="btn btn-default" type='button' onclick="download('<?php echo $list ?>','<?php echo $name ?>')"><?php echo Yii::t('common', 'download');?></button>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
<?php } ?>
<!-- 2021-09-08 结束 -->
<script type="text/javascript">
    var download =  function(path,name){
        window.location = "index.php?r=ra/raswp/downloadattachment&path="+path+"&name="+name;
    }
</script>
