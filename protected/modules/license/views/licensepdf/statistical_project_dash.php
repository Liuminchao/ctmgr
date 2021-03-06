<div class="row">
    <div class="col-xs-2 padding-lr5" style="width: 180px;padding-left: 15px">

        <select class="form-control input-sm" name="q[con_id]" id="program_id" style="width: 100%;" >
            <!--<option value="">--<?php echo Yii::t('comp_statistics', 'Project');?>--</option>-->
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
                    <div class="chart" id="first-chart" style="height: 400px; position: relative;"></div>
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
                    <div class="chart" id="second-chart" style="height: 400px; position: relative;"></div>
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
<!-- ?????? -->
<!--<script src="js/jquery.flot.js" type="text/javascript"></script>-->
<!--<script src="js/jquery.flot.pie.js" type="text/javascript"></script>-->
<!--<script src="js/jquery.flot.pie.min.js" type="text/javascript"></script>-->
<!--  ??????  -->
<script src="https://img.highcharts.com.cn/highcharts/highcharts.js"></script>
<script src="https://img.highcharts.com.cn/highcharts/modules/exporting.js"></script>
<script src="https://img.highcharts.com.cn/highcharts/modules/oldie.js"></script>
<script src="https://img.hcharts.cn/highcharts-plugins/highcharts-zh_CN.js"></script>
<script src="https://img.highcharts.com.cn/highcharts/modules/exporting.js"></script>

<script type="text/javascript">
    var itemQuery_first = function () {
        var id   =$("#program_id").find("option:selected").val();
        var type =$("#pic_type").find("option:selected").val();

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
        $.getJSON("index.php?r=license/licensepdf/cntbyproject&id="+id+'&date='+date, function (result) {
            var j = 0;
            if (type==1) {//?????????
                if(result != null){
                    $.each(result, function (i, field) {
                        arr[j] = {
                            name: field.type_name,
                            y:  Number(field.cnt)
                        }
                        j++;
                    });
                    var dataset = [{
                        name: 'Brands',
                        colorByPoint: true,
                        data:arr
                    }];
                    var chart = Highcharts.chart('first-chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: null,
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                    style: {
                                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                    }
                                }
                            }
                        },
                        series: dataset,
                        exporting: {
                            chartOptions: {
                                plotOptions: {
                                    series: {
                                        dataLabels: {
                                            enabled: true
                                        }
                                    }
                                }
                            }
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
                        }],
                        exporting: {
                            chartOptions: {
                                plotOptions: {
                                    series: {
                                        dataLabels: {
                                            enabled: true
                                        }
                                    }
                                }
                            }
                        }
                    });
                }else{
                    placeholder.empty();
                }
            }
        });
    }
    var itemQuery_second = function () {
        var id=$("#program_id").find("option:selected").val();
        var type =$("#pic_type").find("option:selected").val();
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
        $.getJSON("index.php?r=license/licensepdf/cntbyproject&id="+id+'&date='+date, function (result) {
            var j = 0;
            if (type==1) {
                if(result != null) {
                    $.each(result, function (i, field) {
                        arr[j] = {
                            name: field.type_name,
                            y:  Number(field.cnt)
                        }
                        j++;
                    });
                    var dataset = [{
                        name: 'Brands',
                        colorByPoint: true,
                        data:arr
                    }];
                    var chart = Highcharts.chart('second-chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: null,
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                    style: {
                                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                    }
                                }
                            }
                        },
                        series: dataset,
                        exporting: {
                            chartOptions: {
                                plotOptions: {
                                    series: {
                                        dataLabels: {
                                            enabled: true
                                        }
                                    }
                                }
                            }
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
                    // var h = divCanvans.style.height = 150 + ((xMaxCount + 1) * 25);
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
                        }],
                        exporting: {
                            chartOptions: {
                                plotOptions: {
                                    series: {
                                        dataLabels: {
                                            enabled: true
                                        }
                                    }
                                }
                            }
                        }
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
    function savepng() {
        //1.???div??????svg
        var divContent = document.getElementById("first-chart").innerHTML;
        alert(divContent);
        var data = "data:image/svg+xml," +
            "<svg xmlns='http://www.w3.org/2000/svg' width='200' height='200'>" +
            "<foreignObject width='100%' height='100%'>" +
            "<div xmlns='http://www.w3.org/1999/xhtml' style='font-size:16px;font-family:Helvetica'>" +
            divContent +
            "</div>" +
            "</foreignObject>" +
            "</svg>";
        var img = new Image();
        img.src = data;
        document.getElementsByTagName('body')[0].appendChild(img);


        //2.svg??????canvas
        var canvas = document.createElement('canvas');  //???????????????
        canvas.width = img.width;
        canvas.height = img.height;

        var context = canvas.getContext('2d');  //???????????????2d???????????????
        context.drawImage(img, 0, 0);


        //var a = document.createElement('a');
        //a.href = canvas.toDataURL('image/png');  //??????????????????????????????png????????????
        //a.download = "MapByMathArtSys";  //??????????????????
        //a.click(); //??????????????????


        //3. ??????????????? png ??????
        var type = 'png';
        var imgData = canvas.toDataURL(type);

        /**
         * ??????mimeType
         * @param  {String} type the old mime-type
         * @return the new mime-type
         */
        var _fixType = function (type) {
            type = type.toLowerCase().replace(/jpg/i, 'jpeg');
            var r = type.match(/png|jpeg|bmp|gif/)[0];
            return 'image/' + r;
        };

        // ??????image data?????????mime type
        imgData = imgData.replace(_fixType(type), 'image/octet-stream');


        /**
         * ???????????????????????????
         * @param  {String} data     ?????????????????????????????????
         * @param  {String} filename ?????????
         */
        var saveFile = function (data, filename) {
            var save_link = document.createElementNS('http://www.w3.org/1999/xhtml', 'a');
            save_link.href = data;
            save_link.download = filename;

            var event = document.createEvent('MouseEvents');
            event.initMouseEvent('click', true, false, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
            save_link.dispatchEvent(event);
        };

        // ?????????????????????
        var filename = 'baidufe_' + (new Date()).getTime() + '.' + type;
        // download
        saveFile(imgData, filename);
    }
</script>
