<!doctype html>
<html>
<head>
    <title>Test</title>
    <meta charset="utf-8">
</head>
<style type="text/css">
    body { background: #ffffff;}
</style>
<body >
<div id="WindJS" style="position: relative;background: white;">
    <fieldset>
        <legend>Component</legend>
        <select id="modellist">
        </select>
        <select id="setLeftMouseOperation"> </select>
        <!-- 打开模型 -->
        <button id="openModelData">Open</button>
        <!-- 关闭模型 -->
        <button id="closeModelDatas">Close</button>
        <!-- 复位 -->
        <button id="revertHomePosition">Reset</button>
        <!-- 确认 -->
        <button id="confirmModelData" onclick="Dialog.getInstance('0').cancelButton.onclick.apply(Dialog.getInstance('0').cancelButton,[]);">Confirm</button>
        <!-- 保存 -->
        <button id="save">Save</button>
        <?php $component_list = RfModelComponent::dealList($check_id,$step); ?>

        <input type="hidden" id="model_id" value="<?php  echo $component_list[0]['model_id'] ?>">
        <input type="hidden" id="version" value="<?php  echo $component_list[0]['version'] ?>">
        <input type="hidden" id="entityId" value="<?php  echo $component_list[0]['entityId'] ?>">
        <input type="hidden" id="uuid" value="<?php  echo $component_list[0]['uuid'] ?>">
        <input type="hidden" id="program_id" value="<?php echo $program_id ?>">
    </fieldset>
    <canvas id="View" style="top:0;left:0;height:490px;width:775px;"></canvas>
<!--    <fieldset>-->
<!--        <legend>视图</legend>-->
<!--        <button id="screenshot">截图</button>-->
<!--        <button id="cubeSectionSwitch">剖切开关</button>-->
<!--        <button id="cubeSectionShowHide">剖切显隐</button>-->
<!--        <button id="resetCubeSection">剖切重置</button>-->
<!--        <button id="measureSwitch">测量开关</button>-->
<!--        <select id="measureTypeList"> </select>-->
<!--    </fieldset>-->
<!--    <fieldset>-->
<!--        <legend>漫游</legend>-->
<!---->
<!---->
<!--        <button id="zoomInPosition">放大</button>-->
<!--        <button id="zoomOutPosition">缩小</button>-->
<!--        <button id="locateSelectEntities">定位选中</button>-->
<!--    </fieldset>-->
<!--    <img id="screenshotView" style="top:0;left:0;height:200px;width:300px;">-->
</div>
<script src="js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="js/WIND.js"></script>
<script type="text/javascript" src="js/model_component.js"></script>
<script type="text/javascript" src="js/zDrag.js"></script>
<script type="text/javascript" src="js/zDialog.js"></script>
</body>
</html>
<script type="text/javascript">
    window.onload = function () {
        let model_id = $('#model_id').val();
        let uuid = $('#uuid').val();
        let entityId = $('#entityId').val();
        let version = $('#version').val();

        if(!sessionStorage.getItem("model_component_id")){
            sessionStorage.setItem("model_component_id", model_id);
        }
        if(!sessionStorage.getItem("entityId")){
            sessionStorage.setItem("entityId", entityId);
        }
        if(!sessionStorage.getItem("uuid")){
            sessionStorage.setItem("uuid", uuid);
        }
        if(!sessionStorage.getItem("version")){
            sessionStorage.setItem("version", version);
        }
    }
</script>