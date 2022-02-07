<script type="text/javascript">
    //返回
    var itemBack = function () {
//        window.location = "./?<?php echo Yii::app()->session['list_url']['salary/list']; ?>";
        window.location = "index.php?r=payroll/salary/list";
    }
    //修改
    var itemEdit = function (summary_id,id,date,name) {
        window.location = "index.php?r=payroll/salary/edit&summary_id="+summary_id+"&user_id="+id+'&wage_date='+date+'&user_name='+name;
    }

</script>

<div class="row">
    <div class="col-xs-12">
        
        <div class="box">
            <div class="box-body table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('detail_toolBox',array('summary_id' => $summary_id)); ?>
                    <div id="datagrid"><?php $this->actionDetailGrid($summary_id,$start_date); ?></div>
                </div>
             </div>
        </div>
    </div>
</div>
