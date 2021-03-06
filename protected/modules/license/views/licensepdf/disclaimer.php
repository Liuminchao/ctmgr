<div>
    <input id="tag" type="hidden" value="<?php echo $tag; ?>">
</div>
<div class="row">
    You agree that all PTW submissions have been carefully vetted, including PTW attached checklists, if any. You have also ensured that safety control measures are duly checked by the person responsible until work completion.
</div>

<div class="row" style="margin-top: 10px;">
    By using the ‘Multiple Assess’ or ‘Multiple Approve’ function on CMS SHELL webpage, you acknowledge that CMS Data Technology Pte. Ltd. will not be liable for any special, direct, indirect, consequential, or incidental damages or any damages whatsoever, whether in an action of contract, negligence or other tort, arising out of or in connection with the use of the said functions.
</div>

<div class="row" style="margin-top: 10px;">
    <?php
        if($app == '2'){
    ?>
    <table width="100%">
        <?php
        $tag_list  = explode('|',$tag);
        $tag_cnt = count($tag_list);
        if($tag_cnt == 1){
            $apply_id = $tag_list[0];
            $app_id = 'PTW';
            $apply = ApplyBasic::model()->findByPk($apply_id);//许可证基本信息表
            $program_id = $apply->program_id;
            $pro = Program::model()->findByPk($program_id);//许可证基本信息表
            $contractor_id = $apply->apply_contractor_id;
            $contractor_id = $pro->contractor_id;
            $progress_list = CheckApplyDetail::progressList($app_id,$apply_id);//审批进度流程
            foreach($progress_list as $i => $j){
                $user_id = $j['deal_user_id'];
                $user_name = $j['user_name'];
                $self_info = ProgramUser::SelfByPro($contractor_id,$user_id,$program_id);
                $ptw_role = $self_info['ptw_role'];
                $record_time = Utils::DateToEn($j['deal_time']);
                echo "<tr>
                        <td>Agreed By: $user_name</td>
                        <td>Role: $ptw_role</td>
                        <td>Agreed On: $record_time</td>
                      </tr>";
            }
        }
        ?>
    </table>
    <?php
        }
    ?>
</div>

<div class="row" style="text-align: center;margin-top: 10px;">
    <?php
        if($app != '2'){
    ?>
        <button type="button" class="btn btn-default btn-lg" style="margin-left: 10px" onclick="back();">Cancel</button>
        <button type="button" id="sbtn" class="btn btn-primary btn-lg" onclick="agree('<?php echo $status; ?>');">I Agree</button>
    <?php
        }
    ?>
</div>

<script>
    //返回
    var back = function () {
        $("#modal-close").click();
        itemQuery();
        //window.location = "index.php?r=license/licensepdf/list&program_id=<?php //echo $program_id; ?>//";
    }
    var agree = function (status) {
        var tag = $('#tag').val();
        if(status == '1'){
            itemBatchAssessed(tag);
        }else if(status == '2'){
            itemBatchApproved(tag);
        }else if(status == '3'){
            itemBatchCloseA(tag);
        }else if(status == '4'){
            itemBatchCloseB(tag);
        }else if(status == '5'){
            itemBatchCloseC(tag);
        }else if(status == '6'){
            itemBatchAssessed2(tag);
        }
    }
    //审批A类型或者B类型
    var itemBatchAssessed = function (tag) {

        $.ajax({
            data: {tag: tag,program_id: <?php echo $program_id; ?>},
            url: "index.php?r=license/licensepdf/batchassessed",
            dataType: "json",
            type: "POST",
            success: function (data) {
                if(data.errno == 0){
                    alert('Operate Successfully!');
                    $("#modal-close").click();
                }
                itemQuery();
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
    //审批2 B类型
    var itemBatchAssessed2 = function (tag) {

        $.ajax({
            data: {tag: tag,program_id: <?php echo $program_id; ?>},
            url: "index.php?r=license/licensepdf/batchassessed2",
            dataType: "json",
            type: "POST",
            success: function (data) {
                if(data.errno == 0){
                    alert('Operate Successfully!');
                    $("#modal-close").click();
                }
                itemQuery();
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
    //批准A类型或者B类型
    var itemBatchApproved = function (tag) {

        $.ajax({
            data: {tag: tag,program_id: <?php echo $program_id; ?>},
            url: "index.php?r=license/licensepdf/batchapproved",
            dataType: "json",
            type: "POST",
            success: function (data) {
                if(data.errno == 0){
                    alert('Operate Successfully!');
                    $("#modal-close").click();
                }
                itemQuery();
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
    //批量关闭
    var itemBatchCloseA = function (tag) {

        $.ajax({
            data: {tag: tag,program_id: <?php echo $program_id; ?>},
            url: "index.php?r=license/licensepdf/batchoperatea",
            dataType: "json",
            type: "POST",
            success: function (data) {
                if(data.errno == 0){
                    alert('Operate Successfully!');
                    $("#modal-close").click();
                }
                itemQuery();
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
    var itemBatchCloseB = function (tag) {

        $.ajax({
            data: {tag: tag,program_id: <?php echo $program_id; ?>},
            url: "index.php?r=license/licensepdf/batchoperateb",
            dataType: "json",
            type: "POST",
            success: function (data) {
                if(data.errno == 0){
                    alert('Operate Successfully!');
                    $("#modal-close").click();
                }
                itemQuery();
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
    var itemBatchCloseC = function (tag) {

        $.ajax({
            data: {tag: tag,program_id: <?php echo $program_id; ?>},
            url: "index.php?r=license/licensepdf/batchoperatec",
            dataType: "json",
            type: "POST",
            success: function (data) {
                if(data.errno == 0){
                    alert('Operate Successfully!');
                    $("#modal-close").click();
                }
                itemQuery();
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

</script>
