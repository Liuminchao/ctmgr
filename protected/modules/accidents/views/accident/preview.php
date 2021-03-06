<?php
    $accident_staff = AccidentStaff::getStaffList($accident_list->apply_id);
    $accident_device = AccidentDevice::getDeviceList($accident_list->apply_id);
    $accident_confession = AccidentConfession::getConfessionList($accident_list->apply_id);
    $accident_sick = AccidentSickLeave::getSickList($accident_list->apply_id);
?>
<table border="1" width="100%">
    <tr >
        <td colspan="3"><h3 class="form-header text-blue"><?php echo Yii::t('comp_accident','accident_info'); ?></h3></td>
    </tr>
    <tr style="background-color: rgb(243, 244, 245);">
        <td><?php echo Yii::t('comp_accident','title'); ?></td>
        <td><?php echo Yii::t('comp_accident','program_name'); ?></td>
        <td><?php echo Yii::t('comp_accident','accident_time'); ?></td>
    </tr>
    <tr>
        <td><?php echo $accident_list->title ?></td>
        <td><?php echo $accident_list->root_proname ?></td>
        <td><?php echo $accident_list->acci_time ?></td>
    </tr>
    <tr style="background-color: rgb(243, 244, 245);">
        <td><?php echo Yii::t('comp_accident','accident_location'); ?></td>
        <td><?php echo Yii::t('comp_accident','accident_process'); ?></td>
        <td><?php echo Yii::t('comp_accident','accident_details'); ?></td>
    </tr>
    <tr>
        <td><?php echo $accident_list->acci_location ?></td>
        <td><?php echo $accident_list->acci_process ?></td>
        <td><?php echo $accident_list->acci_details ?></td>
    </tr>
    <tr style="background-color: rgb(243, 244, 245);">
        <td><?php echo Yii::t('comp_accident','apply_user'); ?></td>
        <td><?php echo Yii::t('comp_accident','apply_company'); ?></td>
        <td><?php echo Yii::t('comp_accident','record_time'); ?></td>
    </tr>
    <tr>
        <td>
            <?php
            $add_operator = Staff::model()->findByPk($accident_list->apply_user_id);//申请人信息
            echo $add_operator->user_name ?>
        </td>
        <td>
            <?php
            $company_list = Contractor::compAllList();//承包商公司列表
            echo $company_list[$accident_list->apply_contractor_id] ?>
        </td>
        <td>
            <?php
            echo $accident_list->record_time ?>
        </td>
    </tr>
    <tr style="background-color: rgb(243, 244, 245);">
        <td ><?php echo Yii::t('comp_accident','accident_photo'); ?></td>
        <td height="50px" colspan="2">
            <?php
            if($accident_list->acci_pic) {
                $pic = explode('|',$accident_list->acci_pic);
                foreach($pic as $cnt => $item) {
//                    $item = 'http://k.sinaimg.cn/n/sports/transform/20170715/Da8Z-fyiavtv7477193.jpg/w140h95z1l0t0q10009b.jpg';
                    ?>

                        <a href="<?php echo $item; ?>" target="_Blank"><img src="<?php echo $item; ?>" width="60px"/></a>	</div>
                    <?php
                }
            }
            ?>
        </td>
    </tr>
    <tr >
        <td colspan="3"><h3 class="form-header text-blue"><?php echo Yii::t('comp_accident','accident_person'); ?></h3></td>
    </tr>
    <?php
    if($accident_staff) {
        foreach($accident_staff as $cnt => $item) {
            ?>
            <tr style="background-color: rgb(243, 244, 245);">
                <td><?php echo Yii::t('comp_accident','members'); ?></td>
                <td><?php echo Yii::t('comp_accident','role'); ?></td>
                <td></td>
            </tr>
            <tr>
                <td>
                    <a href='javascript:void(0)' onclick='itemStatistics(<?php echo $item['user_id']; ?>)'><?php echo $item['user_name'] ?></a>
                </td>
                <td>
                    <?php echo $item['role_name'] ?>
                </td>
                <td></td>
            </tr>
            <?php
            }
        }
    ?>
    <tr>
        <td colspan="3">
            <h3 class="form-header text-blue"><?php echo Yii::t('comp_accident','accident_equipment'); ?></h3>
        </td>
    </tr>

    <?php
    if($accident_device) {
        foreach($accident_device as $cnt => $item) {
            ?>
            <tr style="background-color: rgb(243, 244, 245);">
                <td><?php echo Yii::t('comp_accident','equipment'); ?></td>
                <td><?php echo Yii::t('comp_accident','type'); ?></td>
                <td><?php echo Yii::t('comp_accident','device_no'); ?></td>
            </tr>
            <tr>
                <td>
                    <?php
                    echo $item['device_name'] ?>
                </td>
                <td>
                    <?php
                    echo $item['device_type'] ?>
                </td>
                <td>
                    <?php
                    echo $item['device_id'] ?>
                </td>
            </tr>

            <?php
        }
    }
    ?>
    <tr>
        <td colspan="3">
            <h3 class="form-header text-blue"><?php echo Yii::t('comp_accident','accident_confession'); ?></h3>
        </td>
    </tr>

    <?php
    if($accident_confession) {
        foreach($accident_confession as $cnt => $item) {
            ?>
            <tr style="background-color: rgb(243, 244, 245);">
                <td><?php echo Yii::t('comp_accident','members'); ?></td>
                <td><?php echo Yii::t('comp_accident','role'); ?></td>
                <td><?php echo Yii::t('comp_accident','accident_confession'); ?></td>
            </tr>
            <tr>
                <td>
                    <?php echo $item['user_name'] ?>
                </td>
                <td>
                    <?php
                    echo $item['role_name'] ?>
                </td>
                <td>
                    <?php
                    echo $item['confession'] ?>
                </td>
            </tr>
            <?php
        }
    }
    ?>
    <tr>
        <td colspan="3">
            <h3 class="form-header text-blue"><?php echo Yii::t('comp_accident','leave_info'); ?></h3>
        </td>
    </tr>

    <?php
    if($accident_sick) {
        foreach($accident_sick as $cnt => $item) {
            ?>
            <tr style="background-color: rgb(243, 244, 245);">
                <td><?php echo Yii::t('comp_accident','members'); ?></td>
                <td><?php echo Yii::t('comp_accident','leave_start_date'); ?></td>
                <td><?php echo Yii::t('comp_accident','leave_end_date'); ?></td>
            </tr>
            <tr>
                <td>
                    <?php
                    echo $item['user_name'] ?>
                </td>
                <td>
                    <?php
                    echo $item['start_time'] ?>
                </td>
                <td>
                    <?php
                    echo $item['end_time'] ?>
                </td>
            </tr>
            <tr style="background-color: rgb(243, 244, 245);">
                <td ><?php echo Yii::t('comp_accident','sick_photo'); ?></td>
                <td height="50px" colspan="2">
                    <?php
                    if($item['pic']) {
                        $pic = explode('|',$item['pic']);
                        foreach($pic as $cnt => $pic_item) {
//                            $pic_item = 'http://k.sinaimg.cn/n/sports/transform/20170715/Da8Z-fyiavtv7477193.jpg/w140h95z1l0t0q10009b.jpg';
                            ?>

                        <a href="<?php echo $pic_item; ?>" target="_Blank"><img src="<?php echo $pic_item; ?>" width="60px"/></a>	</div>
                            <?php
                        }
                    }
                    ?>
                </td>
            </tr>
    <?php
        }
    }
    ?>
</table>
<!--<div class="row">-->
<!--    <div class="col-md-12">-->
<!--        <h3 class="form-header text-blue">意外信息</h3>-->
<!--    </div>-->
<!--</div>-->
<!--<div class="row">-->
<!--    <div class="col-md-4">-->
<!--        <div class="form-group">-->
<!--            <label class="control-label col-md-5">标题</label>-->
<!--            <div class="col-md-7">-->
<!--                <p class='form-control-static'>--><?php //echo $accident_list->title ?><!--</p>	</div>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="col-md-4">-->
<!--        <div class="form-group">-->
<!--            <label class="control-label col-md-5">项目</label>-->
<!--            <div class="col-md-7">-->
<!--                <p class='form-control-static'>--><?php //echo $accident_list->root_proname ?><!--</p>	</div>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="col-md-4">-->
<!--        <div class="form-group">-->
<!--            <label class="control-label col-md-5">意外时间</label>-->
<!--            <div class="col-md-7">-->
<!--                <p class='form-control-static'>--><?php //echo $accident_list->acci_time ?><!--</p>	</div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<!--<div class="row">-->
<!--    <div class="col-md-4">-->
<!--        <div class="form-group">-->
<!--            <label class="control-label col-md-5">意外地点</label>-->
<!--            <div class="col-md-7">-->
<!--                <p class='form-control-static'>--><?php //echo $accident_list->acci_location ?><!--</p>	</div>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="col-md-4">-->
<!--        <div class="form-group">-->
<!--            <label class="control-label col-md-5">意外过程</label>-->
<!--            <div class="col-md-7">-->
<!--                <p class='form-control-static'>--><?php //echo $accident_list->acci_process ?><!--</p>	</div>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="col-md-4">-->
<!--        <div class="form-group">-->
<!--            <label class="control-label col-md-5">意外详情</label>-->
<!--            <div class="col-md-7">-->
<!--                <p class='form-control-static'>--><?php //echo $accident_list->acci_details ?><!--</p>	</div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<!--<div class="row">-->
<!--    <div class="col-md-4">-->
<!--        <div class="form-group">-->
<!--            <label class="control-label col-md-5">意外图片</label>-->
<!--            <div class="col-md-7">-->
<!--    --><?php
//        if($accident_list->acci_pic) {
//            $pic = explode('|',$accident->acci_pic);
//            foreach($pic as $cnt => $item) {
//                $item = 'http://k.sinaimg.cn/n/sports/transform/20170715/Da8Z-fyiavtv7477193.jpg/w140h95z1l0t0q10009b.jpg';
//    ?>
<!---->
<!--                <img src="--><?php //echo $item; ?><!--" width="60px"/>	</div>-->
<!--    --><?php
//            }
//        }
//    ?>
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<!--<div class="row">-->
<!--    <div class="col-md-4">-->
<!--        <div class="form-group">-->
<!--            <label class="control-label col-md-5">申请人</label>-->
<!--            <div class="col-md-7">-->
<!--                <p class='form-control-static'>--><?php
//                    $add_operator = Staff::model()->findByPk($accident_list->apply_user_id);//申请人信息
//                    echo $add_operator->user_name ?><!--</p>	</div>-->
<!--        </div>-->
<!--    </div>-->
<!---->
<!--    <div class="col-md-4">-->
<!--        <div class="form-group">-->
<!--            <label class="control-label col-md-5">申请人公司</label>-->
<!--            <div class="col-md-7">-->
<!--                <p class='form-control-static'>--><?php
//                    $company_list = Contractor::compAllList();//承包商公司列表
//                    echo $company_list[$accident_list->apply_contractor_id] ?><!--</p>	</div>-->
<!--        </div>-->
<!--    </div>-->
<!---->
<!--    <div class="col-md-4">-->
<!--        <div class="form-group">-->
<!--            <label class="control-label col-md-5">记录时间</label>-->
<!--            <div class="col-md-7">-->
<!--                <p class='form-control-static'>--><?php
//                    echo $accident_list->record_time ?><!--</p>	</div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

<!--<div class="row">-->
<!--    <div class="col-md-12">-->
<!--        <h3 class="form-header text-blue">意外人员</h3>-->
<!--    </div>-->
<!--</div>-->
<!---->
<!--    --><?php
//    if($accident_staff) {
//        foreach($accident_staff as $cnt => $item) {
//            ?>
<!--        <div class="row">-->
<!--            <div class="col-md-4">-->
<!--                <div class="form-group">-->
<!--                    <label class="control-label col-md-5">人员</label>-->
<!--                    <div class="col-md-7">-->
<!--                        <p class='form-control-static'>-->
<!--                            <a href='javascript:void(0)' onclick='itemStatistics(--><!--<?php //echo $item['user_id']; ?>//)'><?php //echo $item['user_name'] ?><!--</a></p>	</div>-->
<!--                </div>-->
<!--            </div>-->
<!---->
<!--            <div class="col-md-4">-->
<!--                <div class="form-group">-->
<!--                    <label class="control-label col-md-5">岗位</label>-->
<!--                    <div class="col-md-7">-->
<!--                        <p class='form-control-static'>--><?php
//                            echo $item['role_name'] ?><!--</p>	</div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--            --><?php
//        }
//    }
//    ?>

<!--<div class="row">-->
<!--    <div class="col-md-12">-->
<!--        <h3 class="form-header text-blue">意外设备</h3>-->
<!--    </div>-->
<!--</div>-->
<!---->
<?php
//if($accident_staff) {
//    foreach($accident_device as $cnt => $item) {
//        ?>
<!--        <div class="row">-->
<!--            <div class="col-md-4">-->
<!--                <div class="form-group">-->
<!--                    <label class="control-label col-md-5">设备</label>-->
<!--                    <div class="col-md-7">-->
<!--                        <p class='form-control-static'>--><?php
//                            echo $item['device_name'] ?><!--</p>	</div>-->
<!--                </div>-->
<!--            </div>-->
<!---->
<!--            <div class="col-md-4">-->
<!--                <div class="form-group">-->
<!--                    <label class="control-label col-md-5">类型</label>-->
<!--                    <div class="col-md-7">-->
<!--                        <p class='form-control-static'>--><?php
//                            echo $item['device_type'] ?><!--</p>	</div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        --><?php
//    }
//}
//?>

<!--<div class="row">-->
<!--    <div class="col-md-12">-->
<!--        <h3 class="form-header text-blue">意外口供</h3>-->
<!--    </div>-->
<!--</div>-->
<!---->
<?php
//if($accident_staff) {
//    foreach($accident_confession as $cnt => $item) {
//        ?>
<!--        <div class="row">-->
<!--            <div class="col-md-4">-->
<!--                <div class="form-group">-->
<!--                    <label class="control-label col-md-5">人员</label>-->
<!--                    <div class="col-md-7">-->
<!--                        <p class='form-control-static'>-->
<!--                            --><?php //echo $item['user_name'] ?><!--</p>	</div>-->
<!--                </div>-->
<!--            </div>-->
<!---->
<!--            <div class="col-md-4">-->
<!--                <div class="form-group">-->
<!--                    <label class="control-label col-md-5">岗位</label>-->
<!--                    <div class="col-md-7">-->
<!--                        <p class='form-control-static'>--><?php
//                            echo $item['role_name'] ?><!--</p>	</div>-->
<!--                </div>-->
<!--            </div>-->
<!---->
<!--            <div class="col-md-4">-->
<!--                <div class="form-group">-->
<!--                    <label class="control-label col-md-5">口供</label>-->
<!--                    <div class="col-md-7">-->
<!--                        <p class='form-control-static'>--><?php
//                            echo $item['confession'] ?><!--</p>	</div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        --><?php
//    }
//}
//?>

<!--<div class="row">-->
    <!--    <div class="col-md-12">-->
    <!--        <h3 class="form-header text-blue">请假信息</h3>-->
    <!--    </div>-->
    <!--</div>-->
    <!---->
    <?php
    //if($accident_sick) {
    //    foreach($accident_sick as $cnt => $item) {
    //        ?>
    <!--        <div class="row">-->
    <!--            <div class="col-md-4">-->
    <!--                <div class="form-group">-->
    <!--                    <label class="control-label col-md-5">人员</label>-->
    <!--                    <div class="col-md-7">-->
    <!--                        <p class='form-control-static'>--><?php
    //                            echo $item['user_name'] ?><!--</p>	</div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!---->
    <!--            <div class="col-md-4">-->
    <!--                <div class="form-group">-->
    <!--                    <label class="control-label col-md-5">请假时间开始</label>-->
    <!--                    <div class="col-md-7">-->
    <!--                        <p class='form-control-static'>--><?php
    //                            echo $item['start_time'] ?><!--</p>	</div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!---->
    <!--            <div class="col-md-4">-->
    <!--                <div class="form-group">-->
    <!--                    <label class="control-label col-md-5">请假时间结束</label>-->
    <!--                    <div class="col-md-7">-->
    <!--                        <p class='form-control-static'>--><?php
    //                            echo $item['end_time'] ?><!--</p>	</div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!---->
    <!--            <div class="col-md-4">-->
    <!--                <div class="form-group">-->
    <!--                    <label class="control-label col-md-5">图片</label>-->
    <!--                    <div class="col-md-7">-->
    <!--                        <p class='form-control-static'>--><?php
    //                            if($item['pic']) {
    //                            $pic = explode('|',$item['pic']);
    //                            foreach($pic as $cnt => $pic_item) {
    //                                $pic_item = 'http://k.sinaimg.cn/n/sports/transform/20170715/Da8Z-fyiavtv7477193.jpg/w140h95z1l0t0q10009b.jpg';
    //                             ?>
    <!--                            <img src="--><?php //echo $pic_item ?><!--" width ="60px" />-->
    <!--                        --><?php //}
    //                            } ?><!--</p>	</div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        --><?php
    //    }
    //}
    //?>
<div class="row">
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-10">
            <button type="button" class="btn btn-default btn-lg"
                    style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>
        </div>
    </div>
</div>
<script type="text/javascript">
    //统计信息
    var itemStatistics = function(id) {
        window.location = "index.php?r=comp/staff/statistics&user_id="+id;
    }
    //返回
    var back = function () {
        window.history.back();
        //window.location = "index.php?r=comp/usersubcomp/list";
    }
</script>
