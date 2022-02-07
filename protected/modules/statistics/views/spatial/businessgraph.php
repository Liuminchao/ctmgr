
<div class="row" style="margin-left: -20px;">
    <div class="col-xs-9">
        <div class="dataTables_length">
            <?php
            $q = $_REQUEST['q'];

            ?>
            <form name="_query_form" id="_query_form" role="form">

                <div class="col-xs-2 padding-lr5" style="width: 180px;">
                    <select class="form-control input-sm" name="q[module]" id="module_first" style="width: 100%;">
                        <option value="">--<?php echo Yii::t('common', 'module'); ?>--</option>
                        <?php
                        $module_list = StatsDateApp::ModuleList();
                        if($module_list) {
                            foreach ($module_list as $k => $name) {
                                echo "<option value='{$k}'>{$name}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="col-xs-2 padding-lr5" style="width: 180px;">
                    <select class="form-control input-sm" name="q[module]" id="module_second" style="width: 100%;">
                        <option value="">--<?php echo Yii::t('common', 'module'); ?>--</option>
                        <?php
                        $module_list = StatsDateApp::ModuleList();
                        if($module_list) {
                            foreach ($module_list as $k => $name) {
                                echo "<option value='{$k}'>{$name}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="col-xs-2 padding-lr5" style="width: 145px;">
                    <div class="input-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control input-sm tool-a-search" name="q[start_date]"
                                   id="q_start_date" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" placeholder="<?php echo Yii::t('common', 'date_of_application'); ?>"/>
                        </div>
                    </div>
                </div>

                <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search');?></a>
                <!--<a class="btn btn-primary btn-sm" href="javascript:itemExport()"><?php echo Yii::t('proj_report', 'export'); ?></a>-->
            </form>
        </div>
    </div>
</div>
<div id="container_first" style="min-width: 500px; height: 350px; margin: 0 auto"></div>
<div id="container_second" style="min-width: 500px; height: 350px; margin: 0 auto"></div>

<script src="js/highstock.js" type="text/javascript"></script>
<script type="text/javascript">
    var itemQuery = function() {
        var first_module=$("#module_first").find("option:selected").val();
        var first_tag = $("#module_first").find("option:selected").text();
        var second_module=$("#module_second").find("option:selected").val();
        var second_tag = $("#module_second").find("option:selected").text();
        if(first_module == '' &&  second_module==''){
            alert('<?php echo Yii::t('common','select_module'); ?>');
            return;
        }
        var date = $("#q_start_date").val();
        if(first_module != ''){
            var container = 'container_first';
            showdetail(first_module,first_tag,date,container);
        }
        if(second_module != ''){
            var container = 'container_second';
            showdetail(second_module,second_tag,date,container);
        }

    }
    var showdetail = function (module,tag,date,container) {
//		alert(id);
//		alert(date);
        var arr = [];
        var category  = [];
        var cnt = [];
        var y_max = 0;
        var tmp;
        $.ajax({
            url: "index.php?r=statistics/spatial/modulebyday&module="+module+'&date='+date,
            type: "GET",
            dataType: "json",
            success: function(data) {
                var j = 0;
                if(data != null) {
                    $.each(data, function (i, field) {
                        category[j] =field.program_name;
                        tmp = Number(field.cnt);
                        if(y_max < tmp){
                        	y_max = tmp;
                        }
                        cnt[j] = Number(field.cnt); //强制转换为数字类型
                        j++;
                    });
//                    for(i=0;i<=13;i++){
//                        cnt[i] = i;
//                    }
//                    var cnt = [0,0,10,4,10,1,0,5,0,0,5,1,2];
//                    alert(cnt[0]);
//                    console.log(cnt);
                    Highcharts.chart(container, {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'Statistical charts'
                        },
                        subtitle: {
                            text: 'Source: CMS'
                        },
                        xAxis: {
                            categories: category,
                            crosshair: true,
                            min:0,
                            max:9
                        },
                        yAxis: {
                            min: 0,
                            max: y_max,
                            title: {
                                text: 'Total (times)'
                            }
                        },
                        scrollbar : {
                            enabled:true
                        },
                        tooltip: {
                            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>{point.y:.1f} times</b></td></tr>',
                            footerFormat: '</table>',
                            shared: true,
                            useHTML: true
                        },
                        plotOptions: {
                            column: {
                                pointPadding: 0.2,
                                borderWidth: 0
                            }
                        },
                        credits:{
                            enabled: false // 禁用版权信息
                        },
                        series: [
                            {
                                name: tag,
                                data: cnt
                            }
                        ]
                    });
                }
            }
        });
    }

</script>