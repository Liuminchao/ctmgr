<style type="text/css">
    .xAxis >.tickLabel
    {
        font-size: 10px;
        margin-top: 20px;
    }
</style>
<div class="row" style="margin-left: -20px;">
    <div class="col-xs-9">
        <div class="dataTables_length">
            <?php
            $q = $_REQUEST['q'];

            ?>
            <form name="_query_form" id="_query_form" role="form">
                <?php 
                    $proj_id = Yii::app()->user->getState('program_id');
                    $program_app = ProgramApp::getIslite($proj_id);
                ?>
                <input type="hidden" id="program_app" value="<?= $program_app['is_lite']?>"/>
                <div class="col-xs-2 padding-lr5" style="width: 180px;">
                    <select class="form-control input-sm" name="q[program_id]" id="program_id" style="width: 100%;">
                        <option value="">--<?php echo Yii::t('proj_report', 'report_program').'('.Yii::t('common', 'is_requried').')'; ?>--</option>
                        <?php
                        $args['contractor_id'] = Yii::app()->user->contractor_id;
                        $program_list = Program::McProgramList($args);
                        if($program_list) {
                            foreach ($program_list as $k => $name) {
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
                            <?php
                                $month = Utils::MonthToEn(date('Y-m'));
                            ?>
                            <input type="text" class="form-control input-sm tool-a-search" name="q[month]"
                                   value="<?php echo $month ?>"   id="month" onclick="WdatePicker({lang:'en',dateFmt:'MMM yyyy'})" placeholder="<?php echo Yii::t('common', 'date_of_application'); ?>"/>
                        </div>
                    </div>
                </div>

                <a class="tool-a-search" href="javascript:itemQuery_first();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search');?></a>
                <!--<a class="btn btn-primary btn-sm" href="javascript:itemExport()"><?php echo Yii::t('proj_report', 'export'); ?></a>-->
            </form>
        </div>
    </div>
</div>
<div class="row" style="padding-top: 8px;">
    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header">
                <div class="box-body chart-responsive">
                    <div class="chart" id="first-chart" style="height: 300px; position: relative;"></div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>

    <div class="col-md-6">
        <div class="box box-success">
            <div class="box-header">
                <div class="box-body chart-responsive">
                    <div class="chart" id="second-chart" style="height: 300px; position: relative;">

                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>

</div>
<div class="row">
    <div class="col-md-12">
        <div class="box box-danger">
            <div id="tag" class="box-success">
                <div class="box-body table-responsive">
                    <div role="grid" class="dataTables_wrapper form-inline" id="<?php echo $this->gridId; ?>_wrapper">
                        <div id="datagrid"><?php $this->actionMonthAppGrid(); ?></div>
                    </div>
                </div>
            </div><!-- /.box -->
        </div>
    </div>
</div>

<script type="text/javascript" src="js/JQdate/WdatePicker.js"></script>
<script src="js/morris.min.js" type="text/javascript"></script>
<script src="js/raphael.js" type="text/javascript"></script>
<script src="js/jquery.flot.js" type="text/javascript"></script>
<script src="js/morris.js" type="text/javascript"></script>
<script src="js/jquery.rotate.min.js" type="text/javascript"></script>
<script type="text/javascript">
    //????????????
    function getRandomColor(){
        return "#"+("00000"+((Math.random()*16777215+0.5)>>0).toString(16)).slice(-6);
    }
    var itemQuery_first = function () {
        //0-????????? 1-lite??????lite????????????TBM, PTW,Inspection???Incident?????????
        var program_app = $("#program_app").val();
        var id=$("#program_id").find("option:selected").val();
        var program_name = $("#program_id").find("option:selected").text();
        if(id == ''){
            alert('<?php echo Yii::t('common','select_program'); ?>');
            return;
        }
        var month = $("#month").val();
        $.ajax({
            data: {id: id,month:month},
            url: "index.php?r=statistics/module/modulemonthcnt",
            type: "POST",
            dataType: "json",
            success: function(data) {
                $('#bar-chart').empty();
                $("#second-chart").empty();
                var j = 0;
                var data1 = [];
                var data2 = [];
                var data3 = [];
                var table=$("<table class=\"table table-striped\">");
                table.appendTo($("#second-chart"));
                var label_str = '';
                var data_str = '';
                if(data != null) {
                    if (program_app == '1') {
                        $.each(data, function (i, field) {
                        switch(field.label_table){
                            case "PTW Participants":
                                label_str = ' / '+field.label_table;
                                data_str = ' / '+field.data;
                                $('#ptw_cnt_label').append(label_str);
                                $('#ptw_cnt_data').append(data_str);
                                break;
                            case "TBM Participants":
                                 label_str = ' / '+field.label_table;
                                 data_str = ' / '+field.data;
                                $('#tbm_cnt_label').append(label_str);
                                $('#tbm_cnt_data').append(data_str);
                                break;
                            default:
                                var temp1 = [
                                    j,
                                    field.data,
                                ];
                                var temp2 = [
                                    j,
                                    field.label,
                                ];
                                var color = getRandomColor();
                                var temp3 = [
                                    j,
                                    color,
                                ]
                                data1.push(temp1);
                                data2.push(temp2);
                                data3.push(temp3);
                                var tr=$("<tr><td id="+field.label_id+">"+field.label_table+"</td><td id="+field.data_id+">"+field.data+"</td></tr>");
                                tr.appendTo(table);
                                j++;
                                break;
                        }

                    });
                    }else{
                        $.each(data, function (i, field) {
                        switch(field.label_table){
                            case "PTW Participants":
                                label_str = ' / '+field.label_table;
                                data_str = ' / '+field.data;
                                $('#ptw_cnt_label').append(label_str);
                                $('#ptw_cnt_data').append(data_str);
                                break;
                            case "TBM Participants":
                                 label_str = ' / '+field.label_table;
                                 data_str = ' / '+field.data;
                                $('#tbm_cnt_label').append(label_str);
                                $('#tbm_cnt_data').append(data_str);
                                break;
                            case "Meeting Participants":
                                 label_str = ' / '+field.label_table;
                                 data_str = ' / '+field.data;
                                $('#mee_cnt_label').append(label_str);
                                $('#mee_cnt_data').append(data_str);
                                break;
                            case "Training Participants":
                                 label_str = ' / '+field.label_table;
                                 data_str = ' / '+field.data;
                                $('#tra_cnt_label').append(label_str);
                                $('#tra_cnt_data').append(data_str);
                                break;
                            default:
                                var temp1 = [
                                    j,
                                    field.data,
                                ];
                                var temp2 = [
                                    j,
                                    field.label,
                                ];
                                var color = getRandomColor();
                                var temp3 = [
                                    j,
                                    color,
                                ]
                                data1.push(temp1);
                                data2.push(temp2);
                                data3.push(temp3);
                                var tr=$("<tr><td id="+field.label_id+">"+field.label_table+"</td><td id="+field.data_id+">"+field.data+"</td></tr>");
                                tr.appendTo(table);
                                j++;
                                break;
                        }

                    });
                    }
                    $("#second-chart").append("</table>");
                    var d1 = [{label:program_name,data:data1,color: data3}];
                }else{
                    if (program_app == '1') {
                        data1 = [
                            {"label":"PTW","label_table":"PTW Cnt","label_id":"ptw_cnt_label","data_id":"ptw_cnt_data","data":0},
                            {"label":"TBM","label_table":"TBM Cnt","label_id":"tbm_cnt_label","data_id":"tbm_cnt_data","data":0},
                            {"label":"TBM Participants","label_table":"TBM Participants","label_id":"tbm_pcnt_label","data_id":"tbm_pcnt_data","data":0},
                            
                            {"label":"Inspection","label_table":"Inspection Cnt","label_id":"ins_cnt_label","data_id":"ins_cnt_data","data":0},
                            {"label":"Incident","label_table":"Incident Cnt","label_id":"inc_cnt_label","data_id":"inc_cnt_data","data":0}
                        ];
                    }else{
                        
                        data1 = [{"label":"PTW","label_table":"PTW Cnt","label_id":"ptw_cnt_label","data_id":"ptw_cnt_data","data":0},{"label":"TBM","label_table":"TBM Cnt","label_id":"tbm_cnt_label","data_id":"tbm_cnt_data","data":0},{"label":"TBM Participants","label_table":"TBM Participants","label_id":"tbm_pcnt_label","data_id":"tbm_pcnt_data","data":0},{"label":"Checklist","label_table":"Checklist Cnt","label_id":"che_cnt_label","data_id":"che_cnt_data","data":0},{"label":"Inspection","label_table":"Inspection Cnt","label_id":"ins_cnt_label","data_id":"ins_cnt_data","data":0},{"label":"Meeting","label_table":"Meeting Cnt","label_id":"mee_cnt_label","data_id":"mee_cnt_data","data":0},{"label":"Meeting Participants","label_table":"Meeting Participants","label_id":"mee_pcnt_label","data_id":"mee_pcnt_data","data":0},{"label":"Training","label_table":"Training Cnt","label_id":"tra_cnt_label","data_id":"tra_cnt_data","data":0},{"label":"Training Participants","label_table":"Training Participants","label_id":"tra_pcnt_label","data_id":"tra_pcnt_data","data":0},{"label":"RA","label_table":"RA Cnt","label_id":"ra_cnt_label","data_id":"ra_cnt_data","data":0},{"label":"Incident","label_table":"Incident Cnt","label_id":"inc_cnt_label","data_id":"inc_cnt_data","data":0}];
                    }
                    $.each(data1, function (i, field) {
                        switch(field.label_table){
                            case "PTW Participants":
                                label_str = ' / '+field.label_table;
                                data_str = ' / '+field.data;
                                $('#ptw_cnt_label').append(label_str);
                                $('#ptw_cnt_data').append(data_str);
                                break;
                            case "TBM Participants":
                                 label_str = ' / '+field.label_table;
                                 data_str = ' / '+field.data;
                                $('#tbm_cnt_label').append(label_str);
                                $('#tbm_cnt_data').append(data_str);
                                break;
                            case "Meeting Participants":
                                 label_str = ' / '+field.label_table;
                                 data_str = ' / '+field.data;
                                $('#mee_cnt_label').append(label_str);
                                $('#mee_cnt_data').append(data_str);
                                break;
                            case "Training Participants":
                                 label_str = ' / '+field.label_table;
                                 data_str = ' / '+field.data;
                                $('#tra_cnt_label').append(label_str);
                                $('#tra_cnt_data').append(data_str);
                                break;
                            default:
                                var temp1 = [
                                    j,
                                    field.data,
                                ];
                                var temp2 = [
                                    j,
                                    field.label,
                                ];
                                var color = getRandomColor();
                                var temp3 = [
                                    j,
                                    color,
                                ]
                                data1.push(temp1);
                                data2.push(temp2);
                                data3.push(temp3);
                                var tr=$("<tr><td id="+field.label_id+">"+field.label_table+"</td><td id="+field.data_id+">"+field.data+"</td></tr>");
                                tr.appendTo(table);
                                j++;
                                break;
                        }
                    });
                    $("#second-chart").append("</table>");
                    var d1 = [{label:program_name,data:data1,color: data3}];
                }
                var i = 0;
                $.plot($("#first-chart"), d1, {
                    series: {
                        bars: {
                            show: true,
                            align: "left",
                            barWidth: 0.3,
                        }
                    },
                    xaxis: {
                        //show: true,
                        ticks: data2,
                        tickSize: 8,
                        //axisLabelUseCanvas: true,
                        axisLabelFontSizePixels: 12,
                        axisLabelFontFamily: 'Verdana, Arial',
                        axisLabelPadding: 8

                    },
                });
                var objs = document.getElementById("_query_form").elements;
                var i = 0;
                var cnt = objs.length;
                var obj;
                var url = '';

                for (i = 0; i < cnt; i++) {
                    obj = objs.item(i);
                    url += '&' + obj.name + '=' + obj.value;
                }

                <?php echo $this->gridId; ?>.condition = url;
                <?php echo $this->gridId; ?>.refresh();
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(XMLHttpRequest.responseText);
                alert(textStatus);
            },
        });

    }
</script>