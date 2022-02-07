
<div class="row" style="margin-left: -20px;">
    <div class="col-xs-12">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">

                <?php
                //                $startDate = Utils::DateToEn(date('Y-m-d',strtotime('-1 day')));
                $second_time = Utils::MonthToEn(date('Y-m',strtotime('-1 month')));
                //                $endDate = Utils::DateToEn(date('Y-m-d',strtotime('-1 day')));
                ?>
                <div class="row">
                    <div class="col-xs-2 padding-lr5" style="width: 300px;">
                        <label for="program_id" class="col-sm-3 control-label padding-lr5">Month</label>
                        <div class="input-group has-error">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                <input type="text" class="form-control input-sm tool-a-search" name="q[start_date]"
                                       value="<?php echo $second_time; ?>"    id="month" onclick="WdatePicker({lang:'en',dateFmt:'MMM yyyy'})" placeholder="<?php echo Yii::t('common', 'date_of_application'); ?>"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <label for="program_id" class="col-sm-1 control-label padding-lr5">Remark</label>
                    <div class="col-xs-2 padding-lr5" style="width: 200px;">
                        <textarea id="remark" ></textarea>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-10">
            <button type="button" id="sbtn" onclick="allrecord()" class="btn btn-primary btn-lg"><?php echo Yii::t('proj_report', 'export'); ?></button>
        </div>
    </div>
</div>

<script type="text/javascript">
    //导出
    var itemMonthExport = function () {
        var month = $('#month').val();
//        var end_date = $('#end_date').val();
        var id = $('#program_id').val();
        var remark = $('#remark').val();
        if(id == ''){
            alert('Please Select Program');
            return;
        }
//        alert(start_date);
//        alert(end_date);
        window.location = "index.php?r=wsh/wshinspection/downloadmonthpdf&program_id="+id+"&month="+month+"&remark="+remark;
        //window.location = "index.php?r=proj/assignuser/edit&ptype=<?php echo Yii::app()->session['project_type'];?>&id=" + id;
    }

    var per_read_cnt = 50;
    //读取全部记录
    async function allrecord() {
        var month = $('#month').val();
//        var end_date = $('#end_date').val();
        var id = $('#program_id').val();
        var remark = $('#remark').val();
        if(id == ''){
            alert('Please Select Program');
            return;
        }
        addcloud();
        jQuery.ajax({
            data: {id:id, month:month, remark:remark},
            type: 'post',
            url: './index.php?r=wsh/wshinspection/getmonthdata',
            dataType: 'json',
            success: function (data, textStatus) {
//                $('#qr_table').append("</br>Loading...");
                ajaxReadData(id,month,remark, data.cnt, 0);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                alert(XMLHttpRequest);
                alert(textStatus);
                alert(errorThrown);
            },
        });
    }

    /*
     * 加载数据
     */
    var ajaxReadData = function (id,month,remark, rowcnt, startrow){//alert('aa');

        jQuery.ajax({
            data: {id:id, month:month, remark:remark, startrow: startrow, per_read_cnt:per_read_cnt},
            type: 'post',
            url: './index.php?r=wsh/wshinspection/readmonthdata',
            dataType: 'json',
            success: function (data, textStatus) {
                if (rowcnt > startrow) {
                    ajaxReadData(id,month,remark, rowcnt, startrow+per_read_cnt);
                }else{
                    clearCache(id,month);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                //alert(XMLHttpRequest);
                //alert(textStatus);
                //alert(errorThrown);
            },
        });
        return false;
    }

    /*
     * 清除缓存，下载压缩包
     */
    var clearCache = function(id,month){//alert('aa');
        removecloud();
        window.location = "index.php?r=wsh/wshinspection/downloadzip&id="+id+"&month="+month;
    }
</script>
