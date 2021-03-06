<div class="row">
    <div class="col-xs-2 padding-lr5" style="width: 180px;padding-left: 15px">
        <select class="form-control input-sm" name="q[con_id]" id="program_id" style="width: 100%;" >
<!--            <option value="">----><?php //echo Yii::t('comp_statistics', 'Project');?><!----</option>-->
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
    <div class="input-group has-error" style="float:left;">
        <div class="input-group">
            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
            <?php
            $second_time = Utils::MonthToEn(date('Y-m',strtotime('-2 month')));
            ?>
            <input type="text" class="form-control input-sm tool-a-search" name="q[month]"
                   value="<?php echo $second_time ?>" id="q_start_date" onclick="WdatePicker({lang:'en',dateFmt:'MMM yyyy'})" placeholder="<?php echo Yii::t('common', 'date_of_application'); ?>" width="100px"/>
        </div>
    </div>
    <a class="tool-a-search" style="padding-left:8px"  href="javascript:itemQuery_first();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search');?></a>
</div>
<div class="row" style="padding-top: 8px;">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header">

                <div class="box-body chart-responsive">
                    <div class="chart" id="first-chart" style="height: 400px; position: relative;"></div>
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
<script src="https://img.highcharts.com.cn/highcharts/highcharts.js"></script>
<script src="https://img.highcharts.com.cn/highcharts/modules/exporting.js"></script>
<script src="https://img.highcharts.com.cn/highcharts/modules/oldie.js"></script>
<script src="https://img.hcharts.cn/highcharts-plugins/highcharts-zh_CN.js"></script>
<script src="https://img.highcharts.com.cn/highcharts/modules/exporting.js"></script>
<script type="text/javascript">
    var itemQuery_first = function () {
        var id=$("#program_id").find("option:selected").val();
        if(id == ''){
            alert('<?php echo Yii::t('common','select_program'); ?>');
            return;
        }
        var date = $("#q_start_date").val();
        var arr = [];
        var placeholder = $("#first-chart");
        var datasets = [];
        placeholder.unbind();
        $.getJSON("index.php?r=license/licensepdf/cntbytest&id="+id+'&date='+date, function (result) {
            var j = 0;
            if(result != null){
                $.each(result, function (i, field) {

                    arr[j] = [
                        field.status,
                        Number(field.cnt)
                    ]
                    j++;
                });
                var dataset = [{
                    type: 'pie',
                    innerSize: '70%',
                    name: 'Status',
                    data:arr
                }];
                var chart = Highcharts.chart('first-chart', {
                    chart: {
                        spacing : [40, 0 , 40, 0]
                    },
                    title: {
                        floating:true,
                        text: 'Status'
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
                                format: '<b>{point.name}</b>:{point.percentage:.1f}%',
                                style: {
                                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                }
                            },
                            point: {
                                events: {
                                    mouseOver: function(e) {  // ?????????????????????????????????
                                        // ?????????????????????API ?????????https://api.hcharts.cn/highcharts#Chart.setTitle
//                                        alert(e.target);
                                        chart.setTitle({
                                            text: e.target.name+ '\t'+ e.target.y
                                        });
                                    }
                                    //,
                                    // click: function(e) { // ???????????????????????????????????????
                                    //     chart.setTitle({
                                    //         text: e.point.name+ '\t'+ e.point.y + ' %'
                                    //     });
                                    // }
                                }
                            },
                        }
                    },
                    series: dataset
                }, function(c) { // ???????????????????????????????????????
                    // ???????????????
                    var centerY = c.series[0].center[1],
                        titleHeight = parseInt(c.title.styles.fontSize);
                    // ????????????????????????
                    c.setTitle({
                        y:centerY + titleHeight/2
                    });
                });
            }else{
                placeholder.empty();
            }

        });
    }

    function labelFormatter(label, series) {
        return "<div style='font-size:8pt; text-align:center;padding:2px;color:black;'>" +  Math.round(series.percent) + "%</div>";
    }
    // function labelFormatter(label, series) {
    //     return "<div style='font-size:8pt; text-align:center; padding:22px; color:black;'>" +  Math.round(series.percent) + "%</div>";
    // }
</script>
