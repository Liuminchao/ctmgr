<section class="content-header">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <ul class="timeline">
                    <li class="time-label">
                        <span class="bg-aqua"><?php echo Yii::t('sys_workflow', 'Audit Step'); ?></span>
                        <?php
//                        foreach ($progress_list as $i=> $r) {
//                            if($r['step']==1&&$r['status']==0){
//                                echo Yii::t('sys_workflow', 'no_processing');
//                            }
//                            if($r['status']==1||$r['status']==2){
////                                  var_dump($i);
//                                $num = $i;
//                            }
//                        }
//                        if(isset($num)){
//                            $num = $num+1;
//                            echo Yii::t('sys_workflow', 'current_to')."$num".Yii::t('sys_workflow', 'step');
//                        }
                        ?>
                    </li>
                    <?php
                    $i = 0;
                    foreach ($progress_list as $id => $row) {
                        $nbsp = '  ';
                        if($row['status']==1){
                            echo '<li>
                                    <i class="fa fa-user bg-green" style="margin-top: 14px"></i>
                                        <div class="timeline-item">
                                            <h3 class="timeline-header no-border">
                                                '.Yii::t('sys_workflow','the').$row['step'].Yii::t('sys_workflow','step_1').$row['user_name'].$nbsp.($status_css[$row['deal_type']]).'
                                            </h3>
                                        </div>
                                   </li>';
                        }else if($row['status']==2){
                            echo '<li>
                                    <i class="fa fa-user bg-red" style="margin-top: 14px"></i>
                                        <div class="timeline-item">
                                            <h3 class="timeline-header no-border">
                                                '.Yii::t('sys_workflow','the').$row['step'].Yii::t('sys_workflow','step_1').$row['user_name'].$nbsp.($status_css[$row['deal_type']]).'
                                            </h3>
                                        </div>
                                   </li>';
                        }else if($row['status']==0){
                            echo '<li>
                                    <i class="fa fa-user bg-gray" style="margin-top: 14px"></i>
                                        <div class="timeline-item">
                                            <h3 class="timeline-header no-border">
                                                '.Yii::t('sys_workflow','the').$row['step'].Yii::t('sys_workflow','step_1').$row['user_name'].$nbsp.($status_css[$row['deal_type']]).'
                                            </h3>
                                        </div>
                                  </li>';
                        }
                        $i++;
                    }
                    ?>
<!--                    <li>-->
<!--                        <i class="fa fa-clock-o"></i>-->
<!--                    </li>-->
                </ul>
            </div><!-- col-md-12 end -->
        </div><!-- row end -->
    </section>
</section>