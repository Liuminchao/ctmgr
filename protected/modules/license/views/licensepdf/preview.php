<section class="content-header">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <ul class="timeline">
                    <li class="time-label">
                        <span class="bg-aqua"><?php echo Yii::t('sys_workflow', 'Audit Step'); ?></span>
<!--                        --><?php //
//                          foreach ($progress_list as $i=> $r) {
//                              if($r['step']==1&&$r['status']==0){
//                                  echo Yii::t('sys_workflow', 'no_processing');
//                              }
//                              if($r['status']==1||$r['status']==2){
//                                  var_dump($i);
//                                  $num = $i;
//                              }
//                          }
//                          if(isset($num)){
//                            $num = $num+1;
//                            echo Yii::t('sys_workflow', 'current_to')."$num".Yii::t('sys_workflow', 'step');
//                          }
//                        ?>
                    </li>
                    <?php
                    $i = 0;
                    foreach ($progress_list as $id => $row) {
                        $nbsp = '  ';
                        if($row['status']==1){
                            $status = $row['status'];
                            echo '<li>
                                    <i class="fa fa-user bg-green" style="margin-top: 14px"></i>
                                        <div class="timeline-item">
                                            <h3 class="timeline-header no-border">
                                                '.Yii::t('sys_workflow','the').$row['step'].Yii::t('sys_workflow','step_1').$row['user_name'].$nbsp.($status_css[$row['deal_type']]).'
                                            </h3>
                                        </div>
                                   </li>'; 
                        }else if($row['status']==2){
                            $status = $row['status'];
                            if($row['deal_type'] ==6){
                                echo '<li>
                                    <i class="fa fa-user bg-red" style="margin-top: 14px"></i>
                                        <div class="timeline-item">
                                            <h3 class="timeline-header no-border">
                                                '.Yii::t('sys_workflow','the').$row['step'].Yii::t('sys_workflow','step_1').$row['user_name'].$nbsp.Yii::t('license_licensepdf','STATUS_REVOKED').'
                                            </h3>
                                        </div>
                                   </li>';
                            }else{
                                echo '<li>
                                    <i class="fa fa-user bg-red" style="margin-top: 14px"></i>
                                        <div class="timeline-item">
                                            <h3 class="timeline-header no-border">
                                                '.Yii::t('sys_workflow','the').$row['step'].Yii::t('sys_workflow','step_1').$row['user_name'].$nbsp.Yii::t('license_licensepdf','rejected').'
                                            </h3>
                                        </div>
                                   </li>';
                            }
                        }else if($row['status']==0){
                            $status = $row['status'];
                            echo '<li>
                                    <i class="fa fa-user bg-gray" style="margin-top: 14px"></i>
                                        <div class="timeline-item">
                                            <h3 class="timeline-header no-border">
                                                '.Yii::t('sys_workflow','the').$row['step'].Yii::t('sys_workflow','step_1').$row['user_name'].$nbsp.($status_css[$row['deal_type']]).$nbsp.($result_text[$row['status']]).'
                                            </h3>
                                        </div>
                                  </li>';
                            }
                            $i++;
                        }
                        if($status == 1){
                            $i = $i+1;
                            if($pending_text[$i] != ''){
                                echo '<li>
                                    <i class="fa fa-user bg-gray" style="margin-top: 14px"></i>
                                        <div class="timeline-item">
                                            <h3 class="timeline-header no-border">
                                                '.Yii::t('sys_workflow','the').$i.Yii::t('sys_workflow','step_1').$nbsp.$pending_text[$i].'
                                            </h3>
                                        </div>
                                  </li>';
                            }
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