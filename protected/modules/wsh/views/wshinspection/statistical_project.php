<style type="text/css">
    .program {
        font-size: 21px;
        font-weight: bold;
    }
</style>
    <div class="row">
        <div class="col-xs-2 padding-lr5" style="width: 180px;padding-left: 15px">
            <input type="hidden" id="type_id" value="<?php echo $type_id; ?>">
            <select class="form-control input-sm" name="q[con_id]" id="program_id" style="width: 100%;" >
                <option value="">--<?php echo Yii::t('comp_statistics', 'Project');?>--</option>
                <?php
                    $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
                    $args['program_id'] = $program_id;
                    $program_list = Program::McProgramList($args);
                    if($program_list) {
                        foreach ($program_list as $k => $name) {
                            echo "<option value='{$k}'>{$name}</option>";
                        }
                    }
                ?>
            </select>
        </div>
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
                <div id="tag" class="box-success">
                    <div id="first" class="program"></div>
                    <div id="second" class="program"></div>
                </div><!-- /.box -->
            </div>
        </div>
    </div>
<script src="js/jquery.flot.js" type="text/javascript"></script>
<script src="js/jquery.flot.pie.js" type="text/javascript"></script>
<script src="js/jquery.flot.pie.min.js" type="text/javascript"></script>
<!--  ??????  -->
<script src="https://img.highcharts.com.cn/highcharts/highcharts.js"></script>
<script src="https://img.highcharts.com.cn/highcharts/modules/exporting.js"></script>
<script src="https://img.highcharts.com.cn/highcharts/modules/oldie.js"></script>
<script src="https://img.hcharts.cn/highcharts-plugins/highcharts-zh_CN.js"></script>
<script type="text/javascript">
    var Month = new Array();
    Month['Jan'] = 1;
    Month['Feb'] = 2;
    Month['Mar'] = 3;
    Month['Apr'] = 4;
    Month['May'] = 5;
    Month['Jun'] = 6;
    Month['Jul'] = 7;
    Month['Aug'] = 8;
    Month['Sep'] = 9;
    Month['Oct'] = 10;
    Month['Nov'] = 11;
    Month['Dec'] = 12;
    var itemQuery_first = function () {
        var id=$("#program_id").find("option:selected").val();
        if(id == ''){
            alert('<?php echo Yii::t('common','select_program'); ?>');
            return;
        }
        var type =$("#pic_type").find("option:selected").val();
        if(type == ''){
            alert('<?php echo Yii::t('common','select_type'); ?>');
            return;
        }
        var arr = [];
        var datasets = [];
        var ylists = [];
        var xlists = [];
        var date = $("#q_start_date").val();
        var placeholder = $("#first-chart");
        var tag = $("#type_id").val();
        placeholder.unbind();
        $.getJSON("index.php?r=wsh/wshinspection/cntbyproject&id="+id+'&date='+date+'&type_id='+tag, function (result) {
            var j = 0;
            if (type==1) {
                if(result != null){
                    $.each(result, function (i, field) {
                        arr[j] = {
                            label: field.type_name,
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
                    //????????????
                    // var xMaxCount = result.length - 1;
                    // var divCanvans = document.getElementById("first-chart");
                    // var h = divCanvans.style.height = 200 + ((xMaxCount + 1) * 45);
                    // $('#first-chart').height(h);
                    $.each(result, function (i, field) {
                        datasets[j] = field;
                        ylists.push(datasets[j].type_name);
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
                                text: 'type_name'
                            },
                        },
                        yAxis: {
                            min: 0,
                            labels: {
                                overflow: 'justify'
                            },
                            title: {//??????
                                text: 'cnt'
                            }
                        },
                        plotOptions: {
                            bar: {
                                dataLabels: {
                                    enabled: true,
                                    allowOverlap: true // ????????????????????????
                                }
                            }
                        },
                        legend: {
                            enabled: false
                        },
                        credits: {//????????????
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
        var content = $("#second-chart").html();
        var end_date = $("#q_end_date").val();
        var tag = $("#type_id").val();
        var a = date.split(' ');
        var month = Month[a[0]];
        var year = a[1];
        if(end_date){
            var b = end_date.split(' ');
            var end_month = Month[b[0]];
            var end_year = b[1];
//            alert(year);
//            alert(end_year);
//            if(year > end_year){
//                alert('?????????????????????????????????????????????!');
//            }else if(year == end_year){
//                if(month > end_month){
//                    alert('?????????????????????????????????????????????!');
//                }
                $.ajax({
                    data: {id: id,date:date,tag:tag},
                    url: "index.php?r=wsh/wshinspection/analyseprogramdata",
                    dataType: "json",
                    type: "POST",
                    success: function (data) {
                        $('#first').empty();
                        $.each(data, function (k, value) {
                            var newElement = document.createElement("p"); //?????? P ??????
                            var newText = document.createTextNode(value.tag); //??????????????????
                            newElement.appendChild(newText); //??????????????????P?????????
                            document.getElementById("first").appendChild(newElement);//???p????????????div???
                        });
                    }
                });
//            }
        }

    }
    var itemQuery_second = function () {
        var id=$("#program_id").find("option:selected").val();
        if(id == ''){
            alert('<?php echo Yii::t('common','select_program'); ?>');
            return;
        }
        var type =$("#pic_type").find("option:selected").val();
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
        var tag = $("#type_id").val();
        placeholder.unbind();
        $.getJSON("index.php?r=wsh/wshinspection/cntbyproject&id="+id+'&date='+date+'&type_id='+tag, function (result) {
            var j = 0;
            if (type==1) {
                if(result != null) {
                    $.each(result, function (i, field) {
                        arr[j] = {
                            label: field.type_name,
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
                    //????????????
                    // var xMaxCount = result.length - 1;
                    // var divCanvans = document.getElementById("second-chart");
                    // var h = divCanvans.style.height = 200 + ((xMaxCount + 1) * 45);
                    // $('#second-chart').height(h);
                    $.each(result, function (i, field) {
                        datasets[j] = field;
                        ylists.push(datasets[j].type_name);
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
                                text: 'type_name'
                            },
                        },
                        yAxis: {
                            min: 0,
                            labels: {
                                overflow: 'justify'
                            },
                            title: {//??????
                                text: 'cnt'
                            }
                        },
                        plotOptions: {
                            bar: {
                                dataLabels: {
                                    enabled: true,
                                    allowOverlap: true // ????????????????????????
                                }
                            }
                        },
                        legend: {
                            enabled: false
                        },
                        credits: {//????????????
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
        var start_date = $("#q_start_date").val();
        var a = date.split(' ');
        var month = Month[a[0]];
        var year = a[1];
        if(start_date){
            var b = start_date.split(' ');
            var start_month = Month[b[0]];
            var start_year = b[1];
//            if(start_year > year){
//                alert('?????????????????????????????????????????????!');
//            }else if(year == start_year){
//                if(start_month > month){
//                    alert('?????????????????????????????????????????????!');
//                }
                $.ajax({
                    data: {id: id,date:date},
                    url: "index.php?r=wsh/wshinspection/analyseprogramdata",
                    dataType: "json",
                    type: "POST",
                    success: function (data) {
                        $('#second').empty();
                        $.each(data, function (k, value) {
                            var newElement = document.createElement("p"); //?????? P ??????
                            var newText = document.createTextNode(value.tag); //??????????????????
                            newElement.appendChild(newText); //??????????????????P?????????
                            document.getElementById("second").appendChild(newElement);//???p????????????div???
                        });
                    }
                });
//            }
        }
    }
    function labelFormatter(label, series) {
        return "<div style='font-size:8pt; text-align:center;padding:2px;color:black;'>" +  Math.round(series.percent) + "%</div>";
    }
</script>
