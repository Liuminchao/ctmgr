<?php
/* @var $this ProgramController */
/* @var $model Program */
/* @var $form CActiveForm */

if (Yii::app()->user->hasFlash('success')) {
    $msg['msg'] = Yii::t('common','success_insert');
    $msg['status'] = 1;
    $msg['refresh'] = true;
    $class = Utils::getMessageType($msg['status']);
    echo "<div class='alert {$class[0]} alert-dismissable'>
              <i class='fa {$class[1]}'></i>
              <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
              <b>" . Yii::t('common', 'tip') . "：</b>{$msg['msg']}
          </div>
          ";
}
if (Yii::app()->user->hasFlash('error')) {
    $msg['status'] = -1;
    $msg['msg'] = Yii::t('common','error_insert');
    $msg['refresh'] = false;
    $class = Utils::getMessageType($msg['status']);
    echo "<div class='alert {$class[0]} alert-dismissable'>
              <i class='fa {$class[1]}'></i>
              <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
              <b>" . Yii::t('common', 'tip') . "：</b>{$msg['msg']}
          </div>
          ";
}
$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'enableAjaxSubmit' => false,
    'ajaxUpdateId' => 'content-body',
    'focus' => array($model, 'program_name'),
    'role' => 'form', //可省略
    'formClass' => 'form-horizontal', //可省略 表单对齐样式
));
echo $form->activeHiddenField($model, 'program_id', array());
//var_dump($regionlist);
?>
<input type="hidden" name="Program[]" id="program_id" value="<?php echo $program_id ?>"/>
<input type="hidden"  id="block_cnt" value="<?php echo $block_cnt ?>"/>

<div class="box-body" id="area-body">
    <div class="row">
        <div class="col-md-12">
            <h3 class="form-header text-blue"><?php echo Yii::t('proj_project', 'project_region'); ?></h3>
        </div>
    </div>
    <?php
    $index = 0;
    if(!empty($regionlist)){
        foreach($regionlist as $block => $region){
            $index++;
            echo "<div id='row_{$index}' calss='row' style='margin-top: 10px;'>";
            echo "<div class='col-xs-12'>";
            echo "<div id='div_region_{$index}'>";
            echo "<a class='a_logo'  onclick='delete_row({$index});' title=\"Delete\"><i class=\"fa fa-fw fa-times\"></i></a>";
            echo "<a class='a_logo'  onclick='copy({$index});' title=\"Copy\"><i class=\"fa fa-fw fa-copy\"></i></a>";
            echo "<input name='block[{$index}][]' value='{$block}'><a class='a_logo' style='margin-left: 6px' onclick='Add({$index})'><i class='fa fa-fw fa-plus'></i></a>";
            foreach($region as $cnt => $region){
                if(is_numeric($cnt)) {
                    echo "<input  style='margin-left: 13px;margin-bottom: 10px;width: 120px;' name='level[{$index}][]'  value='{$region}'  type='text'><a href='#' class='remove'><img style='margin-left: 3px;' width='16' src='img/delete.png' /></a>";
                }
            }
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
    }else{
        echo "<div id='row_{$index}' calss='row' style='margin-top: 10px;'>";
        echo "<div class='col-xs-12'>";
        echo "<div id='div_region_{$index}'>";
        echo "<a class='a_logo'  onclick='delete_row({$index});' title=\"Delete\"><i class=\"fa fa-fw fa-times\"></i></a>";
        echo "<a class='a_logo'  onclick='copy({$index});' title=\"Copy\"><i class=\"fa fa-fw fa-copy\"></i></a>";
        echo "<input name='block[{$index}][]' value='' placeholder='Block'><a class='a_logo' style='margin-left: 6px' onclick='Add({$index})'><i class='fa fa-fw fa-plus'></i></a>";
        echo "<input  style='margin-left: 13px;margin-bottom: 10px;width: 120px;' name='level[{$index}][]'  value='' placeholder='Level' type='text'><a href='#' class='remove'><img style='margin-left: 3px;' width='16' src='img/delete.png' /></a>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }
    ?>
</div>
<div class="row">
    <div class="col-xs-12" style="text-align: left;margin-left: 10px;">
        <button type="button" class="btn btn-primary btn-sm"  onclick="create();">New Area</button>
    </div>
</div>
<div class="row" style="margin-top: 10px;margin-bottom: 10px">
    <div class='col-xs-12' style="text-align: center">
        <button type="button" class="btn btn-primary"  onclick="save();">Save</button>
        <button type="button" class="btn btn-default" style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>
    </div>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $("body").on("click",".removeclass", function(e){ //user click on remove text
            $(this).parent('div').remove(); //remove text box
        })
        $("body").on("click",".remove", function(e){ //user click on remove text
            $(this).prev().remove();//remove text box
            $(this).remove();
        })
    })

    window.addEventListener('message', function (messageEvent) {
        var data = messageEvent.data;
        console.info('message from child:', data);
        var obj = eval('(' + data + ')');
        console.info(obj.file_list[0].file_id);
    }, false);

    var delete_row = function (index) {
        var my = document.getElementById("row_"+index);
        console.log(my);
        if (my != null)
            my.parentNode.removeChild(my);
    }

    var copy = function(index) {
        var d = document.getElementById("div_region_"+index);  //获取tag名称为div的html元素们

        // if(d[i].className=="q2")                                     //获取tag名称为div的html元素们中，calss名称为q2的html元素

        var a = d.getElementsByTagName("input");              //获取tag名称为div的html元素们中，calss名称为q2的html元素中，tag名称为a的html元素们

        for (i = 0; i < a.length; i++){                              //遍历tag名称为div的html元素们中，calss名称为q2的html元素中，tag名称为a的html元素们
            // var s =a[i].innerHTML;
            console.log(a[i].name);//遍历tag名称为div的html元素们
        }
        var block_cnt = $('#block_cnt').val();
        new_index = parseInt(block_cnt)+1;
        var html = '<div id="row_'+new_index+'" calss="row" style="margin-top: 10px;">\n' +
            '           <div class="col-xs-12">\n' +
            '           <div id="div_region_'+new_index+'">\n';
        html += '<a class="a_logo"  onclick="delete_row('+new_index+');" title="Delete"><i class="fa fa-fw fa-times"></i></a>';
        html += '<a class="a_logo"  onclick="copy('+new_index+');" title="Copy"><i class="fa fa-fw fa-copy"></i></a>';
        for(i=0;i<a.length;i++){
            console.log(a[i].name);
            if(a[i].name == 'block['+index+'][]'){
                html += '<input name="block['+new_index+'][]" value="'+a[i].value+'"><a class="a_logo" style="margin-left: 6px" onclick="Add('+new_index+')"><i class="fa fa-fw fa-plus"></i></a>';
            }
            if(a[i].name == 'level['+index+'][]'){
                html+= '<input style="margin-left: 13px;margin-bottom: 10px;width: 120px;" name="level['+new_index+'][]" value="'+a[i].value+'" type="text"><a href="#" class="remove"><img style="margin-left: 3px;" width="16" src="img/delete.png" /></a>';
            }
        }
        html += '</div>\n';
        html +=  '</div>\n' +
            '         </div>';
        $("#area-body").append(html);
        $('#block_cnt').val(new_index);
    }

    var create = function() {
        var block_cnt = $('#block_cnt').val();
        new_index = parseInt(block_cnt)+1;
        var html = '<div id="row_'+new_index+'" calss="row" style="margin-top: 10px;">\n' +
            '           <div class="col-xs-12">\n' +
            '           <div id="div_region_'+new_index+'">\n';
        html += '<a class="a_logo"  onclick="delete_row('+new_index+');" title="Delete"><i class="fa fa-fw fa-times"></i></a>';
        html += '<a class="a_logo"  onclick="copy('+new_index+');" title="Copy"><i class="fa fa-fw fa-copy"></i></a>';
        html += '<input name="block['+new_index+'][]" value="" placeholder="Block"><a class="a_logo" style="margin-left: 6px" onclick="Add('+new_index+')"><i class="fa fa-fw fa-plus"></i></a>';

        html+= '<input style="margin-left: 13px;margin-bottom: 10px;width: 120px;" name="level['+new_index+'][]" value="" type="text" placeholder="Level"><a href="#" class="remove"><img style="margin-left: 3px;" width="16" src="img/delete.png" /></a>';
        html += '</div>\n';
        html +=  '</div>\n' +
            '         </div>';
        $("#area-body").append(html);
        $('#block_cnt').val(new_index);
    }
    var save = function () {
        $.ajax({
            data:$('#form1').serialize(),                 //将表单数据序列化，格式为name=value
            url: "index.php?r=proj/project/setregion",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                if(data.status == 1) {
                    alert('<?php echo Yii::t('common','success_submit'); ?>');
                }else{
                    alert('<?php echo Yii::t('common','error_submit'); ?>');
                }

            },
            error: function () {
//                $('#msgbox').addClass('alert-danger fa-ban');
//                $('#msginfo').html('系统错误');
//                $('#msgbox').show();
                alert('System Error!');
            }
        });
    }

    //测试
    var save_test = function (location,b) {
        var program_id = $("#program_id").val();
        var region = [];
        var j = 0;
        $("input[name='"+location+"']").each(function(index,item){
            region[j] = $(this).val();
            j++;
        })
        var str = region.join(",");
//        alert(str);
//        return;
        var tag = $("input[name='"+b+"']").val();
//        alert(tag);
//        var lcoation = 'A';
        $.ajax({
            data:{program_id:program_id,str:str,tag:tag,location:location},
            url: "index.php?r=proj/project/setregion",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                if(data.status == 1) {
                    alert('<?php echo Yii::t('common','success_submit'); ?>');
                }else{
                    alert(data.msg);
                }

            },
            error: function () {
//                $('#msgbox').addClass('alert-danger fa-ban');
//                $('#msginfo').html('系统错误');
//                $('#msgbox').show();
                alert('System Error!');
            }
        });
    }
    //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['project/list']; ?>";
    }

    var Add = function (new_index) {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px;width: 120px;'  name='level["+new_index+"][]'  value='' placeholder='Level'  type='text'><a href='#' class='remove'><img style='margin-left: 3px;' width='16' src='img/delete.png' /></a>";
        $("#div_region_"+new_index).append(html);
    }
    //添加A区二级区域
    var AddA = function () {
//        var n = document.getElementById(cnt_A).value();
//        n = n+1;
//        alert(n);
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='A' value='' type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_A").append(html);
    }
    //添加B区二级区域
    var AddB = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='B'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_B").append(html);
    }
    //添加C区二级区域
    var AddC = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px' name='C'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_C").append(html);
    }
    //添加D区二级区域
    var AddD = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='D'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_D").append(html);
    }
    //添加E区二级区域
    var AddE = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='E'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_E").append(html);
    }
    //添加F区二级区域
    var AddF = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='F'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_F").append(html);
    }
    //添加G区二级区域
    var AddG = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='G'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_G").append(html);
    }
    //添加H区二级区域
    var AddH = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='H'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_H").append(html);
    }
    //添加I区二级区域
    var AddI = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='I'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_I").append(html);
    }
    //添加J区二级区域
    var AddJ = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='J'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_J").append(html);
    }
    //添加K区二级区域
    var AddK = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='K'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_K").append(html);
    }
    //添加L区二级区域
    var AddL = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='L'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_L").append(html);
    }
    //添加M区二级区域
    var AddM = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='M'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_M").append(html);
    }
    //添加N区二级区域
    var AddN = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='N'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_N").append(html);
    }
    //添加O区二级区域
    var AddO = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='O'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_O").append(html);
    }
    //添加P区二级区域
    var AddP = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='P'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_P").append(html);
    }
    //添加Q区二级区域
    var AddQ = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='Q'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_Q").append(html);
    }
    //添加R区二级区域
    var AddR = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='R'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_R").append(html);
    }
    //添加S区二级区域
    var AddS = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='S'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_S").append(html);
    }
    //添加T区二级区域
    var AddT = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='T'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_T").append(html);
    }
    //添加S区二级区域
    var AddU = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='U'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_U").append(html);
    }
    //添加V区二级区域
    var AddV = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='V'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_V").append(html);
    }
    //添加区域
    var AddRegion = function () {
        var modal = new TBModal();
        modal.title = "<?php echo Yii::t('proj_project', 'Add Region'); ?>";
        modal.url = "index.php?r=proj/project/selectregion";
        modal.modal();
    }
    //删除节点
    var DelRegion = function (cnt) {
        var block = String.fromCharCode(cnt);
        $("#div_region_" + block).remove();
    }
</script>
