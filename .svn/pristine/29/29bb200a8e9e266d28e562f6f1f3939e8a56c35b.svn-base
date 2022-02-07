
<link rel="stylesheet" href="css/layui.css" />
<!--[if lt IE 9]>
<script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
<script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<style type="text/css">
    /*您可以将下列样式写入自己的样式表中*/
    /*.childBody{padding: 15px;}*/

    /*layui 元素样式改写*/
    .layui-btn-sm{line-height: normal; font-size: 12.5px;}
    .layui-table-view .layui-table-body{min-height: 256px;}
    .layui-table-cell .layui-input.layui-unselect{height: 30px; line-height: 30px;}

    /*设置 layui 表格中单元格内容溢出可见样式*/
    .table-overlay .layui-table-view,
    .table-overlay .layui-table-box,
    .table-overlay .layui-table-body{overflow: visible;}
    .table-overlay .layui-table-cell{height: auto; overflow: hidden;}

    /*文本对齐方式*/
    .text-center{text-align: center;}
</style>

<section class="layui-col-md10" style="margin: 0 auto; float: none;">
    <div class="row">
        <div class="col-md-6"><!--  项目  -->
            <div class="form-group">
                <label for="type_no" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('tbm_meeting','program_name'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <select  id="program_id" name="MeetingPlan[program_id]" style="width: 100%;" >
                        <?php
                        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
                        $program_list = Program::McProgramList($args);
                        $program_id = $model->program_id;
                        foreach ($program_list as $id => $name) {
                            if($id == $program_id){
                                echo "<option value='{$id}' selected='selected'>{$name}</option>";
                            }else{
                                echo "<option value='{$id}'>{$name}</option>";
                            }
                        }
                        ?>
                    </select>

                </div>
                <i class="help-block" style="color:#FF9966">*</i>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6"><!--  公司  -->
            <div class="form-group">
                <label for="type_no" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('proj_project_user','contractor_name'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <select  id="contractor_id" name="MeetingPlan[contractor_id]" style="width: 100%;" >
                        <?php
                        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
                        $contractor_list = Contractor::Mc_scCompList($args);
                        $contractor_id = $model->contractor_id;
                        foreach ($contractor_list as $id => $name) {
                            if($id == $contractor_id){
                                echo "<option value='{$id}' selected='selected'>{$name}</option>";
                            }else{
                                echo "<option value='{$id}'>{$name}</option>";
                            }
                        }
                        ?>
                    </select>

                </div>
                <i class="help-block" style="color:#FF9966">*</i>
            </div>
        </div>
    </div>
    <div class="layui-card">
        <div class="layui-card-header">Risk Register / Assessment review meetings</div>
        <div class="layui-card-body layui-text">
            <div id="toolbar">
                <div>
                    <button type="button" class="layui-btn layui-btn-sm" data-type="addRow" title="添加一行">
                        <i class="layui-icon layui-icon-add-1"></i> 添加一行
                    </button>
                </div>
            </div>
            <div id="tableRes" class="table-overlay">
                <table id="dataTable" lay-filter="dataTable" class="layui-hide"></table>
            </div>
            <div id="action" class="text-center">
                <button type="button" name="btnSave" class="layui-btn" data-type="save"><i class="layui-icon layui-icon-ok-circle"></i>保存</button>
                <button type="reset" name="btnReset" class="layui-btn layui-btn-primary">取消</button>
            </div>
        </div>
    </div>

    <!--保存结果输出-->
    <div class="layui-card">
        <div class="layui-card-header">保存结果输出</div>
        <div class="layui-card-body layui-text">
            <blockquote class="layui-elem-quote layui-quote-nm">
                <pre id="jsonResult"><span class="layui-word-aux">请点击“保存”后查看输出信息……</span></pre>
            </blockquote>
        </div>
    </div>
</section>
<!--recommended script position-->
<script src="js/jquery.json-2.4.min.js" charset="utf-8"></script>
<script src="js/layui.js?v=201805080202" charset="utf-8"></script>
<script type="text/javascript">

    //准备视图对象
    window.viewObj = {

        tbData: [{
            tempId: new Date().valueOf(),
            type: 2,
            name: '测试项名称',
            state: 1
        },{
            tempId: new Date().valueOf(),
            type: 2,
            name: '测试项名称',
            state: 1
        }],
        typeData: [
            {id: 1, name: '分类一'},
            {id: 2, name: '分类二'},
            {id: 3, name: '分类三'},
            {id: 4, name: '分类四'}
        ],
        renderSelectOptions: function(data, settings){
            settings =  settings || {};
            var valueField = settings.valueField || 'value',
                textField = settings.textField || 'text',
                selectedValue = settings.selectedValue || "";
            var html = [];
            for(var i=0, item; i < data.length; i++){
                item = data[i];
                html.push('<option value="');
                html.push(item[valueField]);
                html.push('"');
                if(selectedValue && item[valueField] == selectedValue ){
                    html.push(' selected="selected"');
                }
                html.push('>');
                html.push(item[textField]);
                html.push('</option>');
            }
            return html.join('');
        }
    };

    //layui 模块化引用
    layui.use(['jquery', 'table', 'layer'], function(){
        var $ = layui.$, table = layui.table, form = layui.form, layer = layui.layer;
        var result = '';
        $.ajax({
            url: "index.php?r=ra/raswp/demodata",
            type: "GET",
            dataType: "json",
            async: false,
            success: function(data) {
                result = data;
                console.log(result);
            }
        });
        //数据表格实例化
        var tbWidth = $("#tableRes").width();
        var layTableId = "layTable";
        var tableIns = table.render({
            elem: '#dataTable',
            id: layTableId,
            data: result,
//            url: "index.php?r=ra/raswp/demodata",
            width: tbWidth,
            page: false,
            limit:1000,
            loading: true,
            even: false, //不开启隔行背景
            cols: [[
                {title: 'SN', type: 'numbers'},
                {field: 'date', title: 'Date', edit: 'text'},
                {field: 'time', title: 'Time', edit: 'text'},
                {field: 'location', title: 'Location', edit: 'text'},
                {field: 'leader', title: 'RA Team Leader', edit: 'text'},
                {field: 'attendees', title: 'No. of Attendees', edit: 'text'},
                {field: 'tempId', title: 'Action', templet: function(d){
                    return '<a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del" lay-id="'+ d.tempId +'"><i class="layui-icon layui-icon-delete"></i>移除</a>';
                }}
            ]],
            done: function(res, curr, count){
                viewObj.tbData = res.data;
            }
        });

        //定义事件集合
        var active = {
            addRow: function(){	//添加一行
                var oldData = table.cache[layTableId];
                console.log(oldData);
                var newRow = {tempId: new Date().valueOf(), date: 'DD/MM/YYYY',time: '00:00', location: null, leader: null,attendees: null};
                oldData.push(newRow);
                console.log(oldData);
                tableIns.reload({
                    data : oldData
                });
            },
            updateRow: function(obj){
                var oldData = table.cache[layTableId];
                console.log(oldData);
                for(var i=0, row; i < oldData.length; i++){
                    row = oldData[i];
                    if(row.tempId == obj.tempId){
                        $.extend(oldData[i], obj);
                        return;
                    }
                }
                tableIns.reload({
                    data : oldData
                });
            },
            removeEmptyTableCache: function(){
                var oldData = table.cache[layTableId];
                for(var i=0, row; i < oldData.length; i++){
                    row = oldData[i];
                    if(!row || !row.tempId){
                        oldData.splice(i, 1);    //删除一项
                    }
                    continue;
                }
                tableIns.reload({
                    data : oldData
                });
            },
            save: function(){
                var oldData = table.cache[layTableId];
                console.log(oldData);
                for(var i=0, row; i < oldData.length; i++){
                    row = oldData[i];
                    if(!row.location){
                        layer.msg("填写Location！", { icon: 5 }); //提示
                        return;
                    }
                }
                var a = JSON.stringify(table.cache.table);	//使用JSON.stringify() 格式化输出JSON字符串
//                var json_data = json_data.substring(1,json_data.length);
//                var json_data = json_data.substring(0,json_data.length-1);
                console.log(table);
                document.getElementById("jsonResult").innerHTML = JSON.stringify(table.cache[layTableId], null, 2);	//使用JSON.stringify() 格式化输出JSON字符串
                $.ajax({
                    data:{json_data: a},
                    url: "index.php?r=ra/raswp/savemethod",
                    type: "POST",
                    dataType: "json",
                    beforeSend: function () {

                    },
                    success: function (data) {
                        alert('成功了');
                    },
                    error: function () {
                        //alert('error');
                        //alert(data.msg);
                        $('#msgbox').addClass('alert-danger fa-ban');
                        $('#msginfo').html('系统错误');
                        $('#msgbox').show();
                    }
                });
            }
        }

        //激活事件
        var activeByType = function (type, arg) {
            if(arguments.length === 2){
                active[type] ? active[type].call(this, arg) : '';
            }else{
                active[type] ? active[type].call(this) : '';
            }
        }

        //注册按钮事件
        $('.layui-btn[data-type]').on('click', function () {
            var type = $(this).data('type');
            activeByType(type);
        });

        //监听select下拉选中事件
        form.on('select(type)', function(data){
            var elem = data.elem; //得到select原始DOM对象
            $(elem).prev("a[lay-event='type']").trigger("click");
        });

        //监听工具条
        table.on('tool(dataTable)', function (obj) {
            var data = obj.data, event = obj.event, tr = obj.tr; //获得当前行 tr 的DOM对象;
            console.log(data);
            switch(event){
                case "type":
                    //console.log(data);
                    var select = tr.find("select[name='type']");
                    if(select){
                        var selectedVal = select.val();
                        if(!selectedVal){
                            layer.tips("请选择一个分类", select.next('.layui-form-select'), { tips: [3, '#FF5722'] }); //吸附提示
                        }
                        console.log(selectedVal);
                        $.extend(obj.data, {'type': selectedVal});
                        activeByType('updateRow', obj.data);	//更新行记录对象
                    }
                    break;
                case "state":
                    var stateVal = tr.find("input[name='state']").prop('checked') ? 1 : 0;
                    $.extend(obj.data, {'state': stateVal})
                    activeByType('updateRow', obj.data);	//更新行记录对象
                    break;
                case "del":
                    layer.confirm('真的删除行么？', function(index){
                        obj.del(); //删除对应行（tr）的DOM结构，并更新缓存
                        layer.close(index);
                        activeByType('removeEmptyTableCache');
                    });
                    break;
            }
        });
    });
</script>
