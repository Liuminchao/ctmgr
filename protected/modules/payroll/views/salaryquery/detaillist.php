<script type="text/javascript">
    //返回
    var itemBack = function () {
//        window.location = "./?<?php echo Yii::app()->session['list_url']['salary/list']; ?>";
        window.location = "index.php?r=payroll/salaryquery/list";
    }
</script>

<div class="row">
    <div class="col-xs-12">
        
        <div class="box">
            <div class="box-body table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('detail_toolBox'); ?>
                    <div id="datagrid"><?php $this->actionDetailGrid($summary_id,$start_date); ?></div>
                </div>
             </div>
        </div>
    </div>
</div>
