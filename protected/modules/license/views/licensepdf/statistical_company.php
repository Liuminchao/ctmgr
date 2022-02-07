<div class="row">
    
    <div class="col-xs-2 padding-lr5" style="width: 180px;padding-left: 15px">
        <select class="form-control input-sm" name="q[pic_type]" id="pic_type" style="width: 100%;" >
            <option value="">--<?php echo Yii::t('comp_statistics', 'Chart_type');?>--</option>
            <option value="1"><?php echo Yii::t('comp_statistics', 'Pie_Chart');?></option>
            <option value="2"><?php echo Yii::t('comp_statistics', 'Bar_Chart');?></option>

        </select>
    </div>
</div>
<div class="row" style="padding-top: 8px;">
    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header">
                <div class="col-xs-10">
                    <div class="dataTables_length">

                        <div class="input-group has-error" style="float:left;padding-top:8px">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                <?php
                                $second_time = Utils::MonthToEn(date('Y-m',strtotime('-2 month')));
                                ?>
                                <input type="text" class="form-control input-sm tool-a-search" name="q[month]"
                                       value="<?php echo $second_time ?>" id="q_start_date" onclick="WdatePicker({lang:'en',dateFmt:'MMM yyyy'})" placeholder="<?php echo Yii::t('common', 'date_of_application'); ?>" width="100px"/>
                            </div>
                        </div>
                        <a class="tool-a-search" style="padding-top: 8px"  href="javascript:itemQuery_first();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search');?></a>
                    </div>
                </div>
                <div class="box-body chart-responsive">
                    <div class="chart" id="first-chart" style="height: 500px; position: relative;"></div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>

    <div class="col-md-6">
        <div class="box box-success">
            <div class="box-header">
                <div class="col-xs-10">
                    <div class="dataTables_length">
                        <div class="input-group has-error" style="float:left;padding-top: 8px" >
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                <?php
                                $first_time = Utils::MonthToEn(date('Y-m',strtotime('-1 month')));
                                ?>
                                <input type="text" class="form-control input-sm tool-a-search" name="q[month]"
                                       value="<?php echo $first_time ?>" id="q_end_date" onclick="WdatePicker({lang:'en',dateFmt:'MMM yyyy'})" placeholder="<?php echo Yii::t('common', 'date_of_application'); ?>" width="100px"/>
                            </div>
                        </div>
                        <a class="tool-a-search" style="padding-top: 8px" href="javascript:itemQuery_second();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search');?></a>
                    </div>
                </div>
                <div class="box-body chart-responsive">
                    <div class="chart" id="second-chart" style="height: 500px; position: relative;"></div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>

</div>
<div class="row">
    <div class="col-md-12">
        <div class="box box-danger">
            <div class="box-success">

            </div><!-- /.box -->
        </div>
    </div>
</div>
<script src="js/jquery.flot.js" type="text/javascript"></script>
<script src="js/jquery.flot.pie.js" type="text/javascript"></script>
<script src="js/jquery.flot.pie.min.js" type="text/javascript"></script>

<!--  横柱  -->
<script src="https://img.highcharts.com.cn/highcharts/highcharts.js"></script>
<script src="https://img.highcharts.com.cn/highcharts/modules/exporting.js"></script>
<script src="https://img.highcharts.com.cn/highcharts/modules/oldie.js"></script>
<script src="https://img.hcharts.cn/highcharts-plugins/highcharts-zh_CN.js"></script>
<script type="text/javascript">
    var itemQuery_first = function () {
        var id   = $("#program_id").find("option:selected").val();
        var type = $("#pic_type").find("option:selected").val();
        if(id == ''){
            alert('<?php echo Yii::t('common','select_program'); ?>');
            return;
        }
        if(type == ''){
            alert('<?php echo Yii::t('common','select_type'); ?>');
            return;
        }
        var date = $("#q_start_date").val();
        var arr = [];
        var datasets = [];
        var ylists = [];
        var xlists = [];
        var placeholder = $("#first-chart");
        placeholder.unbind();
        $.getJSON("index.php?r=license/licensepdf/cntbycompany&id="+id+'&date='+date, function (result) {
            var j = 0;
            if (type==1) {//饼状图
                $('#first-chart').html("");
                if(result != null){
                    $.each(result, function (i, field) {
                        arr[j] = {
                            label: field.contractor_name,
                            data: field.cnt
                        }
                        j++;
                    });
                    $.plot(placeholder, arr, {
                        series: {
                            pie: {
                                show: true,
                                label: {
                                    show: true,
                                    formatter:  labelFormatter
                                },
                                combine: {
                                    color: "#999",
                                    threshold: 0.01
                                }
                            }
                        },
                        legend: {
                            show: true,
                            position: "sw",
                        }
                    });
                }else{
                    placeholder.empty();
                }
            }else{
                $('#first-chart').html("");
                if(result != null) {
                    //最大数量
                    // var xMaxCount = result.length - 1;
                    // var divCanvans = document.getElementById("first-chart");
                    // var h = divCanvans.style.height = 150 + ((xMaxCount + 1) * 25);
                    // $('#first-chart').height(h);
                    $.each(result, function (i, field) {
                        datasets[j] = field;
                        ylists.push(datasets[j].contractor_name);
                        xlists.push(parseInt(datasets[j].cnt));
                    });
                    var chart = Highcharts.chart('first-chart', {
                        chart: {
                            type: 'bar'
                        },
                        title: {
                            text: null,
                        },
                        xAxis: {
                            categories: ylists,
                            title: {
                                text: 'contractor_name'
                            },
                        },
                        yAxis: {
                            min: 0,
                            labels: {
                                overflow: 'justify'
                            },
                            title: {//标题
                                text: 'cnt'
                            }
                        },
                        plotOptions: {
                            bar: {
                                dataLabels: {
                                    enabled: true,
                                    allowOverlap: true // 允许数据标签重叠
                                }
                            }
                        },
                        legend: {
                            enabled: false
                        },
                        credits: {//版权信息
                            enabled: false
                        },
                        series: [{
                            data:xlists
                        }]
                    });
                }else{
                    placeholder.empty();
                }
            }
        });
    }
    var itemQuery_second = function () {
        var id=$("#program_id").find("option:selected").val();
        var type = $("#pic_type").find("option:selected").val();
        if(id == ''){
            alert('<?php echo Yii::t('common','select_program'); ?>');
            return;
        }
        if(type == ''){
            alert('<?php echo Yii::t('common','select_type'); ?>');
            return;
        }
        var date = $("#q_end_date").val();
        var arr = [];
        var datasets = [];
        var ylists = [];
        var xlists = [];
        var placeholder = $("#second-chart");
        placeholder.unbind();
        $.getJSON("index.php?r=license/licensepdf/cntbycompany&id="+id+'&date='+date, function (result) {
            var j = 0;
            if (type==1) {
                if(result != null) {
                    $.each(result, function (i, field) {
                        arr[j] = {
                            label: field.contractor_name,
                            data: field.cnt
                        }
                        j++;
                    });
                    $.plot(placeholder, arr, {
                        series: {
                            pie: {
                                show: true,
                                label: {
                                    show: true,
                                    formatter:  labelFormatter
                                },
                                combine: {
                                    color: "#999",
                                    threshold: 0.01
                                }
                            }
                        },
                        legend: {
                            show: true,
                            position: "sw",
                        }
                    });
                }else{
                    placeholder.empty();
                }
            }else{
                if(result != null) {
                    //最大数量
                    // var xMaxCount = result.length - 1;
                    // var divCanvans = document.getElementById("second-chart");
                    // var h = divCanvans.style.height = 150 + ((xMaxCount + 1) * 25);
                    // $('#second-chart').height(h);
                    $.each(result, function (i, field) {
                        datasets[j] = field;
                        ylists.push(datasets[j].contractor_name);
                        xlists.push(parseInt(datasets[j].cnt));
                    });
                    var chart = Highcharts.chart('second-chart', {
                        chart: {
                            type: 'bar'
                        },
                        title: {
                            text: null,
                        },
                        xAxis: {
                            categories: ylists,
                            title: {
                                text: 'contractor_name'
                            },
                        },
                        yAxis: {
                            min: 0,
                            labels: {
                                overflow: 'justify'
                            },
                            title: {//标题
                                text: 'cnt'
                            }
                        },
                        plotOptions: {
                            bar: {
                                dataLabels: {
                                    enabled: true,
                                    allowOverlap: true // 允许数据标签重叠
                                }
                            }
                        },
                        legend: {
                            enabled: false
                        },
                        credits: {//版权信息
                            enabled: false
                        },
                        series: [{
                            data:xlists
                        }]
                    });
                }else{
                    placeholder.empty();
                }
            }
        });
    }
    function labelFormatter(label, series) {
        return "<div style='font-size:8pt; text-align:center;padding:2px;color:black;'>" +  Math.round(series.percent) + "%</div>";
    }
</script>
