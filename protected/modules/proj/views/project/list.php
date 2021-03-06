<script src="js/postbirdAlertBox.min.js"></script>
<script type="text/javascript">
    //查询
    var itemQuery = function () {
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
        return;
    }
    //考勤设置
    var itemAttendance = function (id,ptype) {
        var modal=new TBModal();
        modal.title='<?php echo Yii::t('proj_project','set_attendance');?>';
        modal.url="./index.php?r=proj/project/setattendance&program_id="+id+"&ptype="+ptype;
        modal.modal();
        //window.location = "index.php?r=proj/project/updatefaceset&ptype=<?php //echo Yii::app()->session['project_type'];?>//&program_id="+id;
    }
    //EPSS下载
    var itemEpss = function (id,name) {
        var modal=new TBModal();
        modal.title=name;
        modal.url="./index.php?r=proj/project/downloadepss&program_id="+id;
        modal.modal();
        //window.location = "index.php?r=proj/project/updatefaceset&ptype=<?php //echo Yii::app()->session['project_type'];?>//&program_id="+id;
    }
    //权限
    var itemApp = function (program_id) {
        var modal = new TBModal();
        modal.title = 'Functions';
        modal.url = "index.php?r=proj/project/applist&program_id="+program_id;
        modal.modal();
        // window.location = "index.php?r=proj/project/applist&program_id="+program_id;
    }
    //添加
    var itemAdd = function () {
        window.location = "index.php?r=proj/project/new&ptype=<?php echo Yii::app()->session['project_type'];?>";
    }
    //分包项目
    var itemSublist = function (id) {
        window.location = "index.php?r=proj/project/sublist&ptype=<?php echo Yii::app()->session['project_type'];?>&father_proid=" + id;
    }
    //项目分解
    var itemTask = function (id) {
        window.location = "index.php?r=proj/task/tasklist&ptype=<?php echo Yii::app()->session['project_type'];?>&program_id=" + id;
    }
    //项目区域（总包）
    var itemRegionMc = function (program_id) {
        window.location = "index.php?r=proj/project/setmcregion&ptype=<?php echo Yii::app()->session['project_type'];?>&program_id=" + program_id;
    }
    //项目区域（分包）
    var itemRegionSc = function (root_proid,program_id) {
        window.location = "index.php?r=proj/project/setscregion&ptype=<?php echo Yii::app()->session['project_type'];?>&program_id="+program_id+"&root_proid=" + root_proid;
    }
    //项目组成员
    var itemTeam = function (id,name,type) {
        window.location = "index.php?r=proj/assignuser/authoritylist&ptype=<?php echo Yii::app()->session['project_type'];?>&id=" + id+"&name="+name;
        //window.location = "index.php?r=proj/assignuser/edit&ptype=<?php echo Yii::app()->session['project_type'];?>&id=" + id;       
    }
    //项目组设备
    var itemDevice = function (id,name) {
        window.location = "index.php?r=proj/assignuser/devicelist&ptype=<?php echo Yii::app()->session['project_type'];?>&id=" + id+"&name="+name;
        //window.location = "index.php?r=proj/assignuser/edit&ptype=<?php echo Yii::app()->session['project_type'];?>&id=" + id;       
    }
    //项目组织图
    var itemStruct = function (id,name) {
        window.location = "index.php?r=proj/project/struct&id="+id+"&name="+name;
    }
    //参数设置
    var itemParams = function (program_id) {
        var modal = new TBModal();
        modal.title = '<?php echo Yii::t('proj_project','params');?>';
        modal.url = "index.php?r=proj/project/setparams&program_id="+program_id;
        modal.modal();
        // window.location = "index.php?r=proj/project/applist&program_id="+program_id;
    }
    //报告设置
    var itemReport = function (program_id) {
        var modal = new TBModal();
        modal.title = '<?php echo Yii::t('proj_project','params');?>';
        modal.url = "index.php?r=proj/project/setreport&program_id="+program_id;
        modal.modal();
        // window.location = "index.php?r=proj/project/applist&program_id="+program_id;
    }
    //修改
    var itemEdit = function (id) {
        var title = '2';
        window.location = "index.php?r=proj/project/tabs&program_id=" + id+"&mode=edit&title="+title;
        //window.location = "index.php?r=proj/project/edit&ptype=<?php //echo Yii::app()->session['project_type'];?>//&id=" + id;
    }
    //附件列表
    var itemDocument = function (id) {
        window.location = "index.php?r=proj/project/attachmentlist&id=" + id;
    }
    //启用
    var itemStart = function (id, name) {
        
        if (!confirm("<?php echo Yii::t('common', 'confirm_start_1'); ?>" + name + "<?php echo Yii::t('common', 'confirm_start_2'); ?>")) {
            return;
        }
        $.ajax({
            data: {id: id, confirm: 1},
            url: "index.php?r=proj/project/start",
            dataType: "json",
            type: "POST",
            success: function (data) {

                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_start'); ?>");
                    itemQuery();
                } else {
                    alert("<?php echo Yii::t('common', 'error_start'); ?>");
                }
            }
        });
    }
    //停用
    var itemStop = function (id, name) {
        PostbirdAlertBox.confirm({
            'title': 'Confirm',
            'content': "<?php echo Yii::t('proj_project', 'mc_confirm_stop_1'); ?>" + name + "<?php echo Yii::t('proj_project', 'mc_confirm_stop_2'); ?>",
            'okBtn': '确定',
            'contentColor': 'red',
            'onConfirm': function () {
                console.log("OK - 回调触发后隐藏提示框");
                $.ajax({
                    data: {id: id, confirm: 1},
                    url: "index.php?r=proj/project/stop",
                    dataType: "json",
                    type: "POST",
                    success: function (data) {
                        if (data.refresh == true) {
                            alert("<?php echo Yii::t('common', 'success_stop'); ?>");
                            itemQuery();
                        } else {
                            alert(data.msg);
                        }
                    }
                });
            },
            'onCancel': function () {
                console.log("Cancel-回调触发后隐藏提示框");
            }
        });
        //if (!confirm("<?php //echo Yii::t('proj_project', 'mc_confirm_stop_1'); ?>//" + name + "<?php //echo Yii::t('proj_project', 'mc_confirm_stop_2'); ?>//")) {
        //    return;
        //}
    }
    //删除
    var itemDel = function (id, name) {
        if (!confirm("<?php echo Yii::t('common', 'confirm_delete_1'); ?>" + name + "<?php echo Yii::t('common', 'confirm_delete_2'); ?>")) {
            return;
        }
        $.ajax({
            data: {id: id, confirm: 1},
            url: "index.php?r=proj/project/delete",
            dataType: "json",
            type: "POST",
            success: function (data) {

                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_delete'); ?>");
                    itemQuery();
                } else {
                    alert(data.msg);
                }
            }
        });
    }
    
    //折叠详细
    var showDetail = function (obj, desc, show) {
        $("#row_desc").remove();
        if (c_Note) {
            $(c_Note).removeClass("towfocus");
        }
        if (show && c_Note == obj) {
            c_Note = null;
            return;
        }
        $(obj).after("<tr id='row_desc' class='towfocus'><td colspan='" + obj.cells.length + "'>" + desc + "</td></tr>");
        c_Note = obj;
        $(c_Note).addClass("towfocus");
    }

    var c_Note = null;
    var detailobj = {};

    var getDetail = function (obj, id) {
        if (detailobj[id]) {
            showDetail(obj, detailobj[id], true);
            return;
        }
        var detail = "";
        $.ajax({
            data: {id: id},
            url: "index.php?r=proj/project/detail",
            type: "POST",
            dataType: "json",
            beforeSend: function () {
                detail = "<?php echo Yii::t('common', 'loading'); ?>";
                showDetail(obj, detail, false);
            },
            success: function (data) {
                detail = data.detail;
                if (data.status) {
                    detailobj[id] = detail;
                }
                showDetail(obj, detail, false);
            }
        })
    }
</script>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="<?php echo $this->gridId; ?>_wrapper">
<!--                    --><?php //$this->renderPartial('_toolBox'); ?>
                    <div id="datagrid"><?php $this->actionGrid(); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>