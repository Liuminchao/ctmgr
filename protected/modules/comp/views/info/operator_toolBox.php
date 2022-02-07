<div class="row" style="margin-left: -20px;">
    <div class="col-xs-10">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">
                <div class="col-xs-2 padding-lr5" >
                    <input type="text" id="operator_id" class="form-control input-sm"  name="q[operator_id]" placeholder="Username" value="<?php echo $args['operator_id']==''?'':$args['operator_id']; ?>">
                </div>
                <div class="col-xs-2 padding-lr5" >
                    <input type="text" id="name" class="form-control input-sm"  name="q[name]" placeholder="Name" value="<?php echo $args['name']==''?'':$args['name']; ?>">
                </div>
                <a class="tool-a-search" href="javascript:<?php echo $this->gridId; ?>.page=0;itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></a>

            </form>
        </div>
    </div>
    <div class="col-xs-2" style="text-align: right">
        <?php if (Yii::app()->user->getState('operator_role') == '00'){ ?>
            <button class="btn btn-primary btn-sm" onclick="add('<?php echo $id ?>','<?php echo $name ?>')"><?php echo Yii::t('proj_project', 'add'); ?></button>
        <?php  } ?>
    </div>
</div>
<script type="application/javascript">

</script>