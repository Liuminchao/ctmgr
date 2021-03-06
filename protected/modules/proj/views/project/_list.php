<link rel="stylesheet" type="text/css" href="css/docs.css">
<link rel="stylesheet" type="text/css" href="css/postbirdAlertBox.css">
<style type="text/css">
    /*.omit{
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }*/
</style>
<?php
$t->echo_grid_header();

if (is_array($rows)) {
    $j = 1;

    $tool = true;
    //$tool = false;验证权限
    if (Yii::app()->user->checkAccess('mchtm')) {
        $tool = true;
    }
    $status_list = Program::statusText(); //状态text
    $status_css = Program::statusCss(); //状态css
    $compList = Contractor::compAllList(); //所有承包商
    $operator_id = Yii::app()->user->id;
    $authority_list = OperatorProject::authorityList($operator_id);

    foreach ($rows as $i => $row) {
        //判断项目权限
        $value = $authority_list[$row['program_id']];
        $program_id = $row['program_id'];
        //查找项目设置得模块
        $app = '2';
        $pro_model = Program::model()->findByPk($program_id);
        $root_proid = $pro_model->root_proid;
        $my_app = ProgramApp::myAppList($root_proid,$app);
        $program_name = $row['program_name'];
        $default_program_type = $row['default_program_type'];
        $num = ($curpage - 1) * $this->pageSize + $j++;
        if(strpos($row['program_name'],"'")){
            $row['program_name'] = str_replace("'","&apos;",$row['program_name']);//html对特殊字符转义
        }
        // $edit_link = "<a href='javascript:void(0)' onclick='itemEdit(\"{$row['program_id']}\")'><i class=\"fa fa-fw fa-edit\"></i>" . Yii::t('common', 'edit') . "</a>&nbsp;";
        // $report_link = "<a href='javascript:void(0)' onclick='itemReport(\"{$row['program_id']}\")'><i class=\"fa fa-fw fa-file\"></i>" . Yii::t('proj_project', 'report') . "</a>&nbsp;";
        // $params_link = "<a href='javascript:void(0)' onclick='itemParams(\"{$row['program_id']}\")'><i class=\"fa fa-fw fa-edit\"></i>Modules</a>&nbsp;";
        //$start_link = "<a href='javascript:void(0)' onclick='itemStart(\"{$row['program_id']}\",\"{$row['program_name']}\")'><i class=\"fa fa-fw fa-check\"></i>" . Yii::t('common', 'start') . "</a>&nbsp;";
        $del_link = "<a href='javascript:void(0)' onclick='itemDel(\"{$row['program_id']}\",\"{$row['program_name']}\")'><i class=\"fa fa-fw fa-times\"></i>" . Yii::t('common', 'delete1') . "</a>&nbsp;";

        $stop_link = "<a href='javascript:void(0)' onclick='itemStop(\"{$row['program_id']}\",\"{$row['program_name']}\")'><i class=\"fa fa-fw fa-gear\"></i>" . Yii::t('proj_project', 'STATUS_STOP') . "</a>&nbsp;";
        //$struct_link = "<a href='javascript:void(0)' onclick='itemSublist(\"{$row['program_id']}\")'><i class=\"fa fa-fw fa-gear\"></i>" . Yii::t('proj_project', 'project_struct') . "</a>";
        $team_link = "<a href='javascript:void(0)' onclick='itemTeam(\"{$row['program_id']}\",\"{$row['program_name']}\",\"{$row['default_program_type']}\")'><i class=\"fa fa-fw fa-user\"></i>" . Yii::t('proj_project_user', 'project_team') . "</a>&nbsp;";
        $device_link = "<a href='javascript:void(0)' onclick='itemDevice(\"{$row['program_id']}\",\"{$row['program_name']}\",\"{$ptype}\")'><i class=\"fa fa-fw fa-gear\"></i>". Yii::t('proj_project_user', 'device') . "</a>&nbsp;";
        $sub_link = "<a  href='javascript:void(0)' onclick='itemSublist(\"{$row['program_id']}\")'><i class=\"fa fa-fw fa-gear\"></i>". Yii::t('proj_project', 'project_sub_click') . "</a>";
        // $app_link = "<a  href='javascript:void(0)' onclick='itemApp(\"{$row['program_id']}\")'><i class=\"fa fa-fw fa-gear\"></i>". Yii::t('proj_project', 'Module Settings') . "</a>";
        $struct_link = "<a href='javascript:void(0)' onclick='itemStruct(\"{$row['program_id']}\",\"{$row['program_name']}\",\"{$ptype}\")'><i class=\"fa fa-fw fa-gear\"></i>". Yii::t('proj_project', 'struct') . "</a>";
        $document_link = "<a href='javascript:void(0)' onclick='itemDocument(\"{$row['program_id']}\",\"{$row['program_name']}\",\"{$ptype}\")'><i class=\"fa fa-fw fa-gear\"></i>". Yii::t('proj_project', 'document') . "</a>";
        $epss_link = "<a href='javascript:void(0)' onclick='itemEpss(\"{$row['program_id']}\",\"{$row['program_name']}\")'><i class=\"fa fa-fw fa-clock-o\"></i>EPSS</a>";
        $ptype = Yii::app()->session['project_type'];
        $attendance_link = "<a href='javascript:void(0)' onclick='itemAttendance(\"{$row['program_id']}\")'><i class=\"fa fa-fw fa-times\"></i>". Yii::t('proj_project', 'set_attendance') . "</a>";
        
        $link = '';
        if ($row['status'] == Program::STATUS_NORMAL) {
            if(array_key_exists('SAF',$my_app)){
                if($value == '0'){
                    //建立得默认项目
                    if($row['default_program_type'] == 1){
                        $link .= "
                    <table>
                        <tr><td style='white-space: nowrap' align='left'>$team_link
                            </td>
                        </tr>
                        <tr><td style='white-space: nowrap' align='left'>$device_link
                            </td>
                        </tr>
                        <tr><td style='white-space: nowrap' align='left'>$epss_link
                            </td>
                        </tr>
                    </table>";
                    }else{
                        if($row['contractor_id'] == Yii::app()->user->contractor_id && $row['add_conid'] != Yii::app()->user->contractor_id){   //指向本公司的项目
                            $link .= "
                    <table>
                        <tr><td style='white-space: nowrap' align='left'>$team_link</td>
                        </tr>
                        <tr><td style='white-space: nowrap' align='left'>$device_link
                            </td>
                        </tr>
                    </table>";
                        }
                        if($row['add_conid'] == Yii::app()->user->contractor_id && $row['node_level']== 1){   //本公司建立的项目
                            //是否是独立分包
                            if($row['independent'] == 0) {
                                $link .= "
                    <table>
                        <tr><td style='white-space: nowrap' align='left'>$team_link</td>
                        </tr>
                        <tr><td style='white-space: nowrap' align='left'>$sub_link</td>
                        </tr>
                        <tr><td style='white-space: nowrap' align='left'>$device_link</td>
                        </tr>
                        <tr><td style='white-space: nowrap' align='left'>$document_link</td>
                        </tr>
                        <tr><td style='white-space: nowrap' align='left'>$epss_link</td>
                        </tr>
                    </table>";
                            }else{
                                $link .= "
                    <table>
                        <tr><td style='white-space: nowrap' align='left'>$team_link</td>
                        </tr>
                        <tr><td style='white-space: nowrap' align='left'>$device_link</td>
                        </tr>
                        <tr><td style='white-space: nowrap' align='left'>$document_link</td>
                        </tr>
                    </table>";
                            }
                        }
                        if($row['add_conid'] == Yii::app()->user->contractor_id && $row['node_level']!= 1){   //本公司建立的项目(分包项目)
                            $link .= "
                    <table>
                        <tr><td style='white-space: nowrap' align='left'>$team_link</td>
                        </tr>
                        <tr><td style='white-space: nowrap' align='left'>$device_link</td>
                        </tr>
                        <tr><td style='white-space: nowrap' align='left'>$document_link</td>
                        </tr>
                    </table>";
                        }
                    }
                }else if($value == '1'){
                    if($row['default_program_type'] == 1){
                        $link .= "
                    <table>
                        <tr><td style='white-space: nowrap' align='left'>$team_link</td>
                        </tr>
                        <tr><td style='white-space: nowrap' align='left'>$device_link</td>
                        <tr>
                        <tr><td style='white-space: nowrap' align='left'>$epss_link</td>
                        </tr>
                    </table>";
                    }else{
                        if($row['contractor_id'] == Yii::app()->user->contractor_id && $row['add_conid'] != Yii::app()->user->contractor_id){   //指向本公司的项目
                            $link .= "
                    <table>
                        <tr><td style='white-space: nowrap' align='left'>$team_link</td>
                        </tr>
                        <tr><td style='white-space: nowrap' align='left'>$device_link</td>
                        </tr>
                    </table>";
                        }
                        if($row['add_conid'] == Yii::app()->user->contractor_id && $row['node_level']== 1){   //本公司建立的项目
                            //是否是独立分包
                            if($row['independent'] == 0) {
                                $link .= "
                <table>
                    <tr><td style='white-space: nowrap' align='left'>$team_link</td>
                    </tr>
                    <tr><td style='white-space: nowrap' align='left'>$device_link</td>
                    </tr>
                    <tr><td style='white-space: nowrap' align='left'>$sub_link</td>
                    </tr>
                    <tr><td style='white-space: nowrap' align='left'>$document_link</td>
                    </tr>
                    <tr><td style='white-space: nowrap' align='left'>$epss_link</td>   
                    </tr>
                </table>";
                            }else{
                                $link .= "
                <table>
                    <tr><td style='white-space: nowrap' align='left'>$team_link</td>
                    </tr>
                    <tr><td style='white-space: nowrap' align='left'>$device_link</td>
                    </tr>
                    <tr><td style='white-space: nowrap' align='left'>$document_link</td>
                    </tr>
                </table>";
                            }
                        }
                        if($row['add_conid'] == Yii::app()->user->contractor_id && $row['node_level']!= 1){   //本公司建立的项目(分包项目)
                            $link .= "
                <table>
                    <tr><td style='white-space: nowrap' align='left'>$team_link</td>
                    </tr>
                    <tr><td style='white-space: nowrap' align='left'>$device_link</td>
                    </tr>
                    <tr><td style='white-space: nowrap' align='left'>$document_link</td>
                    </tr>
                </table>";
                        }
                    }
                }
            }
            if(array_key_exists('EPSS',$my_app)){
                if($value == '0'){
                    //建立得默认项目
                    if($row['default_program_type'] == 1){
                        $link .= "
                <table>
                    <tr><td style='white-space: nowrap' align='left'>$team_link</td>
                    </tr>
                    <tr><td style='white-space: nowrap' align='left'>$device_link</td>
                    </tr>
                    <tr><td style='white-space: nowrap' align='left'>$epss_link</td>
                    </tr>
                </table>";
                    }else{
                        if($row['contractor_id'] == Yii::app()->user->contractor_id && $row['add_conid'] != Yii::app()->user->contractor_id){   //指向本公司的项目
                            $link .= "
                <table>
                    <tr><td style='white-space: nowrap' align='left'>$team_link</td>
                    </tr>
                    <tr><td style='white-space: nowrap' align='left'>$device_link</td>
                    </tr>
                </table>";
                        }
                        if($row['add_conid'] == Yii::app()->user->contractor_id && $row['node_level']== 1){   //本公司建立的项目
                            //是否是独立分包
                            if($row['independent'] == 0) {
                                $link .= "
                <table>
                    <tr><td style='white-space: nowrap' align='left'>$team_link</td>
                    </tr>
                    <tr><td style='white-space: nowrap' align='left'>$sub_link</td>
                    </tr>
                    <tr><td style='white-space: nowrap' align='left'>$device_link</td>
                    </tr>
                    <tr><td style='white-space: nowrap' align='left'>$document_link</td>
                    </tr>
                    <tr><td style='white-space: nowrap' align='left'>$epss_link</td>
                    </tr>
                </table>";
                            }else{
                                $link .= "
                <table>
                    <tr><td style='white-space: nowrap' align='left'>$team_link</td>
                    </tr>
                    <tr><td style='white-space: nowrap' align='left'>$device_link</td>
                    </tr>
                    <tr><td style='white-space: nowrap' align='left'>$document_link</td>
                    </tr>
                </table>";
                            }
                        }
                        if($row['add_conid'] == Yii::app()->user->contractor_id && $row['node_level']!= 1){   //本公司建立的项目(分包项目)
                            $link .= "
                <table>
                    <tr><td style='white-space: nowrap' align='left'>$team_link</td>
                    </tr>
                    <tr><td style='white-space: nowrap' align='left'>$device_link</td>
                    </tr>
                    <tr><td style='white-space: nowrap' align='left'>$document_link</td>
                    </tr>
                </table>";
                        }
                    }
                }else if($value == '1'){
                    if($row['default_program_type'] == 1){
                        $link .= "
                <table>
                    <tr><td style='white-space: nowrap' align='left'>$team_link</td>
                    </tr>
                    <tr><td style='white-space: nowrap' align='left'>$device_link</td>
                    </tr>
                    <tr><td style='white-space: nowrap' align='left'>$epss_link</td>
                    </tr>
                </table>";
                    }else{
                        if($row['contractor_id'] == Yii::app()->user->contractor_id && $row['add_conid'] != Yii::app()->user->contractor_id){   //指向本公司的项目
                            $link .= "
                <table>
                    <tr><td style='white-space: nowrap' align='left'>$team_link</td>
                    </tr>
                    <tr><td style='white-space: nowrap' align='left'>$device_link</td>
                    </tr>
                </table>";
                        }
                        if($row['add_conid'] == Yii::app()->user->contractor_id && $row['node_level']== 1){   //本公司建立的项目
                            //是否是独立分包
                            if($row['independent'] == 0) {
                                $link .= "
                <table>
                    <tr><td style='white-space: nowrap' align='left'>$team_link</td>
                    </tr>
                    <tr><td style='white-space: nowrap' align='left'>$device_link</td>
                    </tr>
                    <tr><td style='white-space: nowrap' align='left'>$sub_link</td>
                    </tr>
                    <tr><td style='white-space: nowrap' align='left'>$document_link</td>
                    </tr>
                    <tr><td style='white-space: nowrap' align='left'>$epss_link</td>
                    </tr>
                </table>";
                            }else{
                                $link .= "
                <table>
                    <tr><td style='white-space: nowrap' align='left'>$team_link</td>
                    </tr>
                    <tr><td style='white-space: nowrap' align='left'>$device_link</td>
                    </tr>
                    <tr><td style='white-space: nowrap' align='left'>$document_link</td>
                    </tr>
                </table>";
                            }
                        }
                        if($row['add_conid'] == Yii::app()->user->contractor_id && $row['node_level']!= 1){   //本公司建立的项目(分包项目)
                            $link .= "
                <table>
                    <tr><td style='white-space: nowrap' align='left'>$team_link</td>
                    </tr>
                    <tr><td style='white-space: nowrap' align='left'>$device_link</td>
                    </tr>
                    <tr><td style='white-space: nowrap' align='left'>$document_link</td>
                    </tr>
                </table>";
                        }
                    }
                }
            }
        }
        
        $t->begin_row("onclick", "getDetail(this,'{$row['program_id']}');");
        //$t->echo_td($num); //序号
        $t->echo_td($row['program_id']); //项目编号
        $t->echo_td($row['program_name']); //Program Name
        $t->echo_td($compList[$row['main_conid']]); //Contractor
        
        //项目类型
        if($row['father_proid'] == 0){ //总包项目
            $t->echo_td('<span style="color:blue">'.Yii::t('dboard', 'Menu Project MC').'</span>');
        }
        else{//分包项目
            $t->echo_td('<span style="color:red">'.Yii::t('dboard', 'Menu Project SC').'</span>');
        }
        
        //分包项目&&项目分解判断
        if($row['father_proid'] == 0){
            if($row['default_program_type']== 0){
                $t->echo_td($row['child_cnt']);
            }else{
                $t->echo_td($row['child_cnt']);
            }
            //Project Area list
            $list = ProgramRegion::regionShow($row['program_id']);
            $content_str = "";
            if($list != '' && $list != 'NULL') {
                foreach ($list as $block => $region) {
                    $cnt = count($region);
                    if ($cnt > 1) {
                        $str = implode(",", $region);
                        $new_str = $block . ' ' . $str . '<br/>';
                    } else {
                        $new_str = $block . ' ' . $region[0] . '<br/>';
                    }
                    $content_str .= $new_str;
                }
            }else{
                $content_str = "No";
            }

            $attr['style'] = 'word-wrap:break-word;word-break:break-all;';
            if($value == '0'){
                $t->echo_td($content_str,'',$attr);
            }else if($value == '1'){
                $t->echo_td("<a class='omit' title='".Yii::t('proj_project','project_region')."' href='javascript:void(0)' onclick='itemRegionMc(\"{$row['program_id']}\")'>".$content_str."</a>",'',$attr);
            }else{
                $t->echo_td($content_str,'',$attr);
            }

        }
        //        else{
        // //            $t->echo_td("<a title='".Yii::t('proj_project', 'project_dec_click')."' href='javascript:void(0)' onclick='itemTask(\"{$row['program_id']}\")'>".Task::model()->count('program_id=:program_id', array('program_id' => $row['program_id']))."</a>");
        //            $list = ProgramRegion::regionShow($row['program_id']);
        //            $mc_list = ProgramRegion::regionShow($row['root_proid']);
        //            $content_str = "";
        //            if($list) {
        //                foreach ($list as $block => $region) {
        //                    $cnt = count($region);
        //                    if($cnt > 1) {
        //                        $str = implode(",", $region);
        //                        $new_str = $block . ':' . $str . '<br/>';
        //                    }else{
        //                        $new_str = $block . ':' . $region[0] . '<br/>';
        //                    }
        //                    $content_str .= $new_str;
        //                }
        // //                $t->echo_td("<a class='omit' title='".Yii::t('proj_project','project_region')."' href='javascript:void(0)' onclick='itemRegionSc(\"{$row['root_proid']}\",\"{$row['program_id']}\")'>".$content_str."</a>");
        //                $t->echo_td($content_str);
        //            }else{
        //                if($mc_list){
        //                    $content_str = "No";
        //                    $t->echo_td($content_str);
        // //                    $t->echo_td("<a class='omit' title='".Yii::t('proj_project','project_region')."' href='javascript:void(0)' onclick='itemRegionSc(\"{$row['root_proid']}\",\"{$row['program_id']}\")'>".$content_str."</a>");
        //                }else{
        //                    $content_str = "No";
        //                    $t->echo_td($content_str);
        //                }
        //            }
        // //            var_dump($content_str);
        // //            exit;

        //        }
        //$t->echo_td(substr($row['record_time'],0,10)); //Record Time
        $t->echo_td(substr(Utils::DateToEn($row['record_time']),0,11));
        
        $status = '<span class="label ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
        $t->echo_td($status); //状态

        $t->echo_td($link); //操作
        $t->end_row();
    }
}

$t->echo_grid_floor();

$pager = new CPagination($new_cnt);
$pager->pageSize = $this->pageSize;
//var_dump($cnt);
$pager->itemCount = $new_cnt;
?>

<div class="row">
    <div class="col-xs-3">
        <div class="dataTables_info" id="example2_info">
            <?php echo Yii::t('common', 'page_total'); ?> <?php echo $new_cnt; ?> <?php echo Yii::t('common', 'page_cnt'); ?>
        </div>
    </div>
    <div class="col-xs-9">
        <div class="dataTables_paginate paging_bootstrap">
            <?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>


