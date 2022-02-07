
<link rel="stylesheet" href="css/layui.css" />
<link rel="stylesheet" href="css/formSelects-v4.css" />
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
    .layui-table-cell .layui-input.layui-unselect{height: 33px; line-height: 33px;}

    /*设置 layui 表格中单元格内容溢出可见样式*/
    .table-overlay .layui-table-view,
    .table-overlay .layui-table-box,
    .table-overlay .layui-table-body{overflow: visible;}
    .table-overlay .layui-table-cell{height: auto; }

    /*文本对齐方式*/
    .text-center{text-align: center;}

    .layui-table-cell{
        height:auto;
        overflow:visible;
        text-overflow:inherit;
        white-space:normal;
        padding: 0 1px;
    }
    textarea.layui-textarea.layui-table-edit {
        min-width: 500px;
        min-height: 150px;
        z-index: 2;
    }
</style>

<section class="layui-col-md12" style="float: none;">
    <div>
        <input type="hidden" id="type_id" value="<?php echo $type_id; ?>">
        <input type="hidden" id="mode" value="<?php echo $mode; ?>">
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label" style="width: 120px"><?php echo Yii::t('routine_type','type_id') ?></label>
        <div class="layui-input-inline">
            <?php if($mode == 'copy'){ ?>
                <input type="text" id="type" name="Type[type_id]" lay-verify="title" autocomplete="off" value="<?php echo $type_model->type_id ?>" class="layui-input">
                <input type="hidden" id="devicetype" name="Type[devicetype]" lay-verify="title" autocomplete="off" value="<?php echo $type_model->device_type ?>" class="layui-input">
            <?php }else{ ?>
                <input type="text" id="type" name="Type[type_id]" lay-verify="title" autocomplete="off" placeholder="<?php echo Yii::t('routine_type','type_id') ?>" class="layui-input">
                <input type="hidden" id="devicetype" name="Type[devicetype]" lay-verify="title" autocomplete="off"  class="layui-input">
            <?php } ?>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label" style="width: 120px"><?php echo Yii::t('routine_type','type_name') ?></label>
        <div class="layui-input-inline">
            <?php if($mode == 'copy'){ ?>
                <input type="text" id="type_name" name="Type[type_name]" lay-verify="title" autocomplete="off" value="<?php echo $type_model->type_name ?>" class="layui-input">
            <?php }else{ ?>
                <input type="text" id="type_name" name="Type[type_name]" lay-verify="title" autocomplete="off" placeholder="<?php echo Yii::t('routine_type','type_name') ?>" class="layui-input">
            <?php } ?>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label" style="width: 120px"><?php echo Yii::t('routine_type','type_name_en') ?></label>
        <div class="layui-input-inline">
            <?php if($mode == 'copy'){ ?>
                <input type="text" id="type_name_en" name="Type[type_name_en]" lay-verify="title" autocomplete="off" value="<?php echo $type_model->type_name_en ?>" class="layui-input">
            <?php }else{ ?>
                <input type="text" id="type_name_en" name="Type[type_name_en]" lay-verify="title" autocomplete="off" placeholder="<?php echo Yii::t('routine_type','type_name_en') ?>" class="layui-input">
            <?php } ?>
        </div>
    </div>



    <div class="layui-form-item">
        <label class="layui-form-label" style="width: 120px">类型</label>
        <div class="layui-input-inline">
            <select id="modules" style="width: 190px;height: 38px"  onchange="gradeChange()">
                <?php
                    $module = $type_model->module;
                    if($mode == 'copy'){
                        if($module == 1) {
                ?>
                            <option value="1" selected>设备检查类型</option>
                            <option value="2">非设备检查类型</option>
                            <option value="3">培训检查类型</option>
                    <?php
                        }else if($module == 2) {
                            ?>
                            <option value="1">设备检查类型</option>
                            <option value="2" selected>非设备检查类型</option>
                            <option value="3">培训检查类型</option>
                            <?php
                        }else{
                                ?>
                            <option value="1">设备检查类型</option>
                            <option value="2">非设备检查类型</option>
                            <option value="3" selected>培训检查类型</option>
                                <?php
                            }
                        }else{
                ?>
                <option value="1">设备检查类型</option>
                <option value="2">非设备检查类型</option>
                <option value="3">培训检查类型</option>
                <?php } ?>
            </select>
        </div>
    </div>

    <?php if($mode == 'copy'){
        if($module == '1') {
            ?>
            <div id="device_type" class="layui-form-item">
                <label class="layui-form-label"
                       style="width: 120px"><?php echo Yii::t('device', 'device_type') ?></label>
                <div class="layui-col-md9">
                    <select name="device_type" xm-select="example6_1" style="width: 150px">
                        <option value=""><?php echo Yii::t('device', 'device_type') ?></option>
                    </select>
                </div>
            </div>
            <?php
        }else{
    ?>
    <div id="device_type" class="layui-form-item" style="display: none">
        <label class="layui-form-label"
               style="width: 120px"><?php echo Yii::t('device', 'device_type') ?></label>
        <div class="layui-col-md9">
            <select name="device_type" xm-select="example6_1" style="width: 150px">
                <option value=""><?php echo Yii::t('device', 'device_type') ?></option>
            </select>
        </div>
    </div>
    <?php }}else{ ?>
        <div id="device_type" class="layui-form-item" >
            <label class="layui-form-label"
                   style="width: 120px"><?php echo Yii::t('device', 'device_type') ?></label>
            <div class="layui-col-md9">
                <select name="device_type" xm-select="example6_1" style="width: 150px">
                    <option value=""><?php echo Yii::t('device', 'device_type') ?></option>
                </select>
            </div>
        </div>
    <?php } ?>

    <div class="layui-card">
        <div class="layui-card-header">PTW CONDITION</div>
        <div class="layui-card-body layui-text">
            <div id="toolbar">
                <div>
                    <button type="button" class="layui-btn layui-btn-sm" data-type="addRow" title="添加一行">
                        <i class="layui-icon layui-icon-add-1"></i> <?php echo Yii::t('proj_project','add'); ?>
                    </button>
                </div>
            </div>
            <div id="tableRes" class="table-overlay">
                <table id="dataTable" lay-filter="dataTable" class="layui-hide"></table>
            </div>
            <div id="action" class="text-center">
                <button type="button" name="btnSave" class="layui-btn" data-type="save"><i class="layui-icon layui-icon-ok-circle"></i><?php echo Yii::t('common','button_save') ?></button>
                <button type="reset" name="btnReset" class="layui-btn layui-btn-primary"  data-type="back"><?php echo Yii::t('common','button_back') ?></button>
            </div>
        </div>
    </div>

    <!--保存结果输出-->
    <!--    <div class="layui-card">-->
    <!--        <div class="layui-card-header">保存结果输出</div>-->
    <!--        <div class="layui-card-body layui-text">-->
    <!--            <blockquote class="layui-elem-quote layui-quote-nm">-->
    <!--                <pre id="jsonResult"><span class="layui-word-aux">请点击“保存”后查看输出信息……</span></pre>-->
    <!--            </blockquote>-->
    <!--        </div>-->
    <!--    </div>-->
</section>
<!--recommended script position-->
<script src="js/jquery.json-2.4.min.js" charset="utf-8"></script>
<script src="js/layui.js?v=201805080202" charset="utf-8"></script>
<script src="js/lay/modules/formSelects-v4.js?v=201805080202" charset="utf-8"></script>
<script type="text/javascript">

    //准备视图对象
    window.viewObj = {

        tbData: [{
            tempId: new Date().valueOf(),
            condition_name: '',
            condition_name_en: '',
        }],
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

    $.ajax({
        url: "index.php?r=routine/type/devicetype",
        type: "GET",
        dataType: "json",
        async: false,
        success: function(data) {
            result = data;
            console.log(result);
        }
    });

    var arr = [];
    $.getJSON("index.php?r=routine/type/devicetype",function(result){
        var j = 0;
        $.each(result, function(i, field){
//            alert(i);
//            alert(field);
            arr[j] = {
                name:field,
                value:i
            }
            j++;
        });
        layui.formSelects.data('example6_1', 'local', {
            arr: arr
        });
        var mode = $('#mode').val();
        var devicetype = $('#devicetype').val();
        if(mode == 'copy'){
            var device_arr = devicetype.split("|");
//            alert(device_arr);
            layui.formSelects.value('example6_1', device_arr);
        }

    });

    function gradeChange(){
        var objS = document.getElementById("modules");
        var grade = objS.options[objS.selectedIndex].value;
        if(grade == '1'){
            document.getElementById("device_type").style.display="";//显示
        }else{
            document.getElementById("device_type").style.display="none";//隐藏
        }
    }

    //layui 模块化引用
    layui.use(['jquery', 'table', 'layer'], function(){
        var $ = layui.$, table = layui.table, form = layui.form, layer = layui.layer;
        var result = '';
        var mode = $('#mode').val();
        var type_id = $('#type_id').val();
        if(mode == 'copy'){
            $.ajax({
                url: "index.php?r=routine/type/demodata",
                data:{id:type_id},
                type: "GET",
                dataType: "json",
                async: false,
                success: function(data) {
                    result = data;
                    console.log(result);
                }
            });
        }else{
            result = viewObj.tbData;
        }

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
                {field: 'condition_name', title: 'Condition Name', edit: 'textarea',width:'40%'},
                {field: 'condition_name_en', title: 'Condition Nanme En', edit: 'textarea',width:'40%'},
                {field: 'tempId', title: 'Action',align: 'center', templet: function(d){
                    return '<a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del" lay-id="'+ d.tempId +'"><i class="layui-icon layui-icon-delete"></i><?php echo Yii::t('common','delete1') ?></a>';
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
                var newRow = {tempId: new Date().valueOf(), condition_name: null,condition_name_en: null};
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
                var objS = document.getElementById("modules");
                var module = objS.options[objS.selectedIndex].value;
                if(module == '1'){
                    var formSelects = layui.formSelects;
                    var device_type=JSON.stringify(formSelects.value('example6_1', 'valStr'));//值为2,4（选中为上海，深圳）
                    var reg_1 = new RegExp('"',"g");
                    var reg_2 = new RegExp(',',"g");
                    device_type = device_type.replace(reg_1, "");
                    device_type = device_type.replace(reg_2, "|");
                }else{
                    device_type = '';
                }

                var oldData = table.cache[layTableId];
                console.log(oldData);
                for(var i=0, row; i < oldData.length; i++){
                    row = oldData[i];
                }
                var a = JSON.stringify(table.cache[layTableId]);	//使用JSON.stringify() 格式化输出JSON字符串
//                var json_data = json_data.substring(1,json_data.length);
//                var json_data = json_data.substring(0,json_data.length-1);
                console.log(table);
//                document.getElementById("jsonResult").innerHTML = JSON.stringify(table.cache[layTableId], null, 2);	//使用JSON.stringify() 格式化输出JSON字符串
                var type = $('#type').val();
                var type_name = $('#type_name').val();
                var type_name_en = $('#type_name_en').val();

                $.ajax({
                    data:{json_data: a,type:type,type_name:type_name,type_name_en:type_name_en,device_type:device_type,module:module},
                    url: "index.php?r=routine/type/savemethod",
                    type: "POST",
                    dataType: "json",
                    beforeSend: function () {

                    },
                    success: function (data) {
                        if(data.status == '-1'){
                            layer.msg(data.msg, { icon: 5 }); //提示
                        }else{
                            layer.msg(data.msg, { icon: 5 }); //提示
                            window.location = "index.php?r=routine/type/list";
                        }
                    },
                    error: function () {
                        //alert('error');
                        //alert(data.msg);
                        $('#msgbox').addClass('alert-danger fa-ban');
                        $('#msginfo').html('系统错误');
                        $('#msgbox').show();
                    }
                });
            },
            back: function(){
                window.location = "index.php?r=routine/type/list";
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

        //监听工具条
        table.on('tool(dataTable)', function (obj) {
            var data = obj.data, event = obj.event, tr = obj.tr; //获得当前行 tr 的DOM对象;
            console.log(data);
            switch(event){
                case "del":
                    layer.confirm('<?php echo Yii::t('common','confirm_delete') ?>', function(index){
                        obj.del(); //删除对应行（tr）的DOM结构，并更新缓存
                        layer.close(index);
                        activeByType('removeEmptyTableCache');
                    });
                    break;
            }
        });
    });
</script>
