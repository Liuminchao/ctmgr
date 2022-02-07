<?php

/**
 * 工具箱会议
 * @author LiuMinchao
 */
class MeetingController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';
    const STATUS_NORMAL = 0; //正常
    
    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('tbm_meeting', 'contentHeader');
        $this->bigMenu = Yii::t('tbm_meeting', 'bigMenu');
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=tbm/meeting/grid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('tbm_meeting', 'seq'), '', 'center');
        $t->set_header(Yii::t('tbm_meeting', 'program_name'), '', 'center');
        $t->set_header(Yii::t('tbm_meeting', 'title'), '', 'center');
        $t->set_header(Yii::t('license_licensepdf', 'company'), '', 'center');
        $t->set_header(Yii::t('tbm_meeting', 'apply_date'), '', 'center');
        $t->set_header(Yii::t('tbm_meeting', 'status'), '', 'center');
        $t->set_header(Yii::t('common', 'action'), '15%', 'center');
        return $t;
    }

    /**
     * 查询
     */
    public function actionGrid() {
        $fields = func_get_args();
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        if(count($fields) == 3 && $fields[0] != null ) {
            $args['program_id'] = $fields[0];
            $args['user_id'] = $fields[1];
            $args['deal_type'] = $fields[2];
        }

        $t = $this->genDataGrid();
        $this->saveUrl();
        //$args['status'] = Meeting::STATUS_FINISH;
        
        /*if (Yii::app()->user->getState('role_id') == Operator::ROLE_MC) {//总包
            $args['program_id'] = Program::getProgramId();
            
        } else if (Yii::app()->user->getState('role_id') == Operator::ROLE_SC) {//分包
            $args['program_id'] = ProgramContractor::getProgramId();
        }*/
        $app_id = 'TBM';
        $args['contractor_id'] = Yii::app()->user->contractor_id;
        $rs = Workflow::contractorflowList(self::STATUS_NORMAL,$args['contractor_id'],$app_id);
        $list = Meeting::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'],'rs' =>$rs, 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {
        //SftpConnection::uploadFile('212','PTW','123');
        $user_id = $_REQUEST['user_id'];
        $program_id = $_REQUEST['program_id'];
        $deal_type = $_REQUEST['deal_type'];
        $this->smallHeader = Yii::t('tbm_meeting', 'smallHeader List');
        $this->render('list',array('user_id'=> $user_id,'program_id'=> $program_id,'deal_type'=>$deal_type));
    }

    /**
     * 下载列表
     */
    public function actionDownloadPreview() {
        $meeting_id = $_REQUEST['meeting_id'];
        $app_id = $_REQUEST['app_id'];
        $this->renderPartial('download_preview', array('meeting_id'=>$meeting_id,'app_id'=>$app_id));
    }

    /**
     * 下载附件列表
     */
    public function actionDownloadAttachment() {
        $meeting_id = $_REQUEST['meeting_id'];
        $form_data_list = MeetingDocument::detailList($meeting_id); //记录
        $this->renderPartial('download_attachment', array('meeting_id'=>$meeting_id,'form_data_list'=>$form_data_list));
    }


    /**
     *  下载PDF（新）tbm
     */
    public static function actionDownloadPdf(){
        $id = $_REQUEST['apply_id'];
        $app_id = $_REQUEST['app_id'];
        $tag = $_REQUEST['tag'];
        $params['id'] = $id;
        $params['tag'] = $_REQUEST['tag'];
        $meeting = Meeting::model()->findByPk($id);
        $title = $meeting->title;
        // if ($meeting->save_path) {
        //     $file_path = $meeting->save_path;
        //     $filepath = '/opt/www-nginx/web'.$file_path;
        // } else {
        //     $filepath = DownloadPdf::transferDownload($params,$app_id);
        // }

        //报告定制化
        $program_id = $meeting->program_id;
        $pro_model = Program::model()->findByPk($program_id);
        $pro_params = $pro_model->params;//项目参数
        if($pro_params != '0') {
            $pro_params = json_decode($pro_params, true);
            //判断是否是迁移的
            if (array_key_exists('tbm_report', $pro_params)) {
                $params['type'] = $pro_params['tbm_report'];
            } else {
                $params['type'] = 'A';
            }
        }else{
            $params['type'] = 'A';
        }
        $filepath = DownloadPdf::transferDownload($params,$app_id);
        Utils::Download($filepath, $id, 'pdf');
    }
    /**
     * 下载PDF
     */
    public static function actionDownload()
    {

        $id = $_REQUEST['apply_id'];
        $app_id = $_REQUEST['app_id'];
        $meeting = Meeting::model()->findByPk($id);
        $company_list = Contractor::compAllList();//承包商公司列表
        $program_list = Program::programAllList();//获取承包商所有项目
        $programdetail_list = Program::getProgramDetail($meeting->program_id);//根据项目id得到总包商和根节点项目
        $lang = "_en";

        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($meeting->record_time, 0, 4);//年
        $month = substr($meeting->record_time, 5, 2);//月
//        if ($meeting->save_path) {
//            $file_path = $meeting->save_path;
//            $filepath = '/opt/www-nginx/web' . $file_path;
//        } else {
            $filepath = Yii::app()->params['upload_report_path'] . '/' . $year . '/' . $month . '/tbm' . '/TBM' . $id . '.pdf';
            Meeting::updatepath($id, $filepath);
//        }
        // $filepath = Yii::app()->params['upload_file_path'] .'/tbm/'.$meeting->add_conid.'/TBM' . $id . '.pdf';
        // $filepath = '/opt/www-nginx/web/ctmgr/webuploads'.'/tbm/'.$meeting->add_conid.'/TBM' . $id . '.pdf';
        // $filepath = '/opt/www-nginx/web/ctmgr/webuploads'.'/tbm/'.$meeting->add_conid.'/TBM' . $id . '.pdf';
        // $full_dir = Yii::app()->params['upload_file_path'] .'/tbm/'.$meeting->add_conid;
        // if($full_dir == ''){
        //     return false;
        // }
        $full_dir = Yii::app()->params['upload_report_path'] . '/' . $year . '/' . $month . '/tbm';
        if(!file_exists($full_dir))
        {
            umask(0000);
            @mkdir($full_dir, 0777, true);
        }
        $title = $meeting->title;

        // if (file_exists($filepath)) {
        //     $show_name = $title;
        //     $extend = 'pdf';
        //     Utils::Download($filepath, $show_name, $extend);
        //     return;
        // }
        $tcpdfPath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . 'tcpdf' . DIRECTORY_SEPARATOR . 'tcpdf.php';
        require_once($tcpdfPath);
        //Yii::import('application.extensions.tcpdf.TCPDF');
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        // 设置文档信息   
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);
        //$pdf->SetKeywords('PDF, LICEN');
        // 设置页眉和页脚信息   
        $pdf->SetHeaderData('', 0, 'Meeting No.(会议编号): ' . $meeting->meeting_id, $title, array(0, 64, 255), array(0, 64, 128));
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        // 设置页眉和页脚字体   

        //if (Yii::app()->language == 'zh_CN') {
            //$pdf->setHeaderFont(Array('stsongstdlight', '', '10')); //中文
        //} else if (Yii::app()->language == 'en_US') {
        $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //英文OR中文
        //}

        $pdf->setFooterFont(Array('helvetica', '', '10'));

        //设置默认等宽字体   
        $pdf->SetDefaultMonospacedFont('courier');

        //设置间距   
        $pdf->SetMargins(15, 27, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        //设置分页   
        $pdf->SetAutoPageBreak(TRUE, 25);
        //set image scale factor
        $pdf->setImageScale(1.25);
        //set default font subsetting mode   
        $pdf->setFontSubsetting(true);
        //设置字体   
        //if (Yii::app()->language == 'zh_CN') {
            //$pdf->SetFont('droidsansfallback', '', 14, '', true); //中文
        //} else if (Yii::app()->language == 'en_US') {
        $pdf->SetFont('droidsansfallback', '', 9, '', true); //英文OR中文
        //}

        $pdf->AddPage();

        $members = MeetingWorker::getMembersName($meeting->meeting_id);

        $meeting_date = date('Y-m-d', strtotime($meeting->meeting_date));

        $operator_id = $meeting->add_user;//申请人ID
        $add_operator = Staff::model()->findByPk($operator_id);//申请人信息
        $add_role = $add_operator->role_id;
        $roleList = Role::roleallList();//岗位列表
        $apply_date = str_replace('-', ' ', $meeting->meeting_date);//申请日期
        //标题(许可证类型+项目)
        $title_html = "<h3 align=\"center\">Project (项目) : {$program_list[$meeting->program_id]}</h3><br/>";
        //申请人资料
        $apply_info_html = "<h4 align=\"center\">Applicant Details (申请人详情)</h4><table border=\"1\">";
        $apply_info_html .="<tr><td width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Name of Applicant (申请人姓名)</td><td width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Designation of Applicant (申请人职位)</td><td width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">ID No.of Applicant (申请人身份证号码)</td></tr>";
        $apply_info_html .="<tr><td height=\"50px\" align=\"center\">&nbsp;{$add_operator->user_name}</td><td align=\"center\">&nbsp;{$roleList[$add_role]}</td><td align=\"center\">&nbsp;{$add_operator->work_no}</td></tr>";
        $apply_info_html .="<tr><td width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Applicant Company (申请人公司)</td><td width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Date of Application (申请时间)</td><td width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Electronic Signature (电子签名)</td></tr>";
        $apply_info_html .="<tr><td height=\"50px\" align=\"center\">&nbsp;{$company_list[$meeting->add_conid]}</td><td align=\"center\">&nbsp;{$apply_date} {$meeting->from_time} - {$meeting->to_time}</td><td align=\"center\">&nbsp;</td></tr>";
        $apply_info_html .="</table>";

        //判断电子签名是否存在 $add_operator->signature_path
        $path = '/opt/www-nginx/appupload/4/0000002314_TBMMEETINGPHOTO.jpg';
        if($add_operator->signature_path){
            $pdf->Image($add_operator->signature_path, 150, 65, 20, 9, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
        }

        //工作内容
        $work_content_html = "<h4 align=\"center\">Meeting Content (会议内容)</h4><table border=\"1\"  >";
        $work_content_html .="<tr><td width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Title (标题)</td><td width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Description (描述)</td></tr>";
        $work_content_html .="<tr><td height=\"140px\">{$meeting->title}</td><td >{$meeting->content}</td></tr></table>";

        $progress_list = CheckApplyDetail::progressList($app_id, $meeting->meeting_id,$year);//TBM审批结果(快照)

        $status_css = CheckApplyDetail::statusTxt();//执行类型
        //审批流程
        $audit_html = '<h4 align="center">Approval Process (审批流程)</h4><table border="1">
                <tr><td  nowrap="nowrap" width="20%" bgcolor="#d9d9d9" align="center">&nbsp;Person in Charge<br>(执行人)</td><td  nowrap="nowrap" width="20%" bgcolor="#d9d9d9" align="center"> Status<br>(状态)</td><td  nowrap="nowrap" width="20%" bgcolor="#d9d9d9" align="center"> Date & Time<br>(日期&时间)</td><td  nowrap="nowrap" width="20%" bgcolor="#d9d9d9" align="center">&nbsp;Remark<br>(备注)</td><td  nowrap="nowrap" width="20%" bgcolor="#d9d9d9" align="center">&nbsp;Electronic Signature<br>(电子签名)</td></tr>';

        $progress_result = CheckApplyDetail::resultTxt();
        $user_list = Staff::userInfo();//员工信息
        $info_xx = 164;//X方向距离
        $info_yy = 148;//Y方向距离
        $j = 1;
        if (!empty($progress_list))
            foreach ($progress_list as $key => $row) {

                $audit_html .= '<tr><td height="55px" align="center">&nbsp;' . $row['user_name'] . '</td><td align="center">&nbsp;'.$status_css[$row['deal_type']].'</td><td align="center">&nbsp;'.Utils::DateToEn($row['deal_time']).'</td><td align="center">&nbsp;'.$row['remark'].'</td><td></td>';
                //$pic = 'C:\Users\minchao\Desktop\5.png';
                $content_list = $user_list[$row['deal_user_id']];
                $content = $content_list[0]['signature_path'];
                //$path = '/opt/www-nginx/appupload/5/0000001595_TBMMEETINGPHOTO.jpg';
                if($content != '') {
                    $pdf->Image($content, $info_xx, $info_yy, 25, 9, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                }
                $audit_html .= '</tr>';
                $j++;
                $info_yy += 16;
            }
        $audit_html .= '</table>';

        $pic_html = '<h4 align="center">Site Photo(s) (现场照片)</h4><table border="1">
                <tr><td width ="100%" height="107px"></td></tr>';
        if (!empty($progress_list)){
            foreach ($progress_list as $key => $row) {
                if($row['pic'] != '') {
                    $pic = explode('|', $row['pic']);
                    $info_x = 40;
                    $info_y = 187;
                    foreach ($pic as $key => $content) {
                        if($content != '-1') {
                            $pdf->Image($content, $info_x, $info_y, 30, 28, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                            $info_x += 50;
                        }
                    }
                }
            }
        }
        $pic_html .= '</table>';

        $worker_html = '<h4 align="center">Member(s) (参会成员)</h4><table border="1">
                <tr><td  width="10%" bgcolor="#d9d9d9" align="center">&nbsp;S/N<br>(序号)</td><td  width="20%" bgcolor="#d9d9d9" align="center">&nbsp;WP No.<br>(工作准证号码)</td><td  width="35%" bgcolor="#d9d9d9" align="center">&nbsp;Name of Workers<br>(工人姓名)</td><td width="15%" bgcolor="#d9d9d9" align="center">&nbsp;Employee ID<br>(员工编号)</td><td width="20%" bgcolor="#d9d9d9" align="center">&nbsp;Designation<br>(职务)</td></tr>';

        if (!empty($members)) {
            $i = 1;
            foreach ($members as $user_id => $r) {
                $worker_html .= '<tr><td height="20px" align="center">&nbsp;' . $i . '</td><td align="center">&nbsp;' . $r['wp_no'] . '</td><td align="center">&nbsp;' . $r['worker_name'] . '</td><td align="center">&nbsp;' . $user_id . '</td><td align="center">&nbsp;' . $roleList[$r['role_id']] . '</td></tr>';
                $i++;
            }
        }
        $worker_html .= '</table>';

        // $apply_html = "
        //     Project(项目) : {$program_list[$meeting->program_id]}<br/>
        //     Applicant Company(申请公司) : {$company_list[$meeting->add_conid]}<br/>
        //     The contractor project(总包项目) : {$program_list[$meeting->main_proid]}<br/>
        //     Total package company(总包公司) : {$company_list[$programdetail_list[$meeting->program_id]['main_conid']]}<br/>
        //     Title(标题) : {$meeting->title}<br/>
        //     Content(内容) : {$meeting->content}<br/>
        //     Date Of Application(申请日期) : " . str_replace('-', ' ', $meeting->meeting_date) . " {$meeting->from_time} - {$meeting->to_time}";

        $html_1 = $title_html . $apply_info_html . $work_content_html .  $audit_html  . $pic_html . $worker_html  ;

        $pdf->writeHTML($html_1, true, false, true, false, '');

        //输出PDF   
        //$pdf->Output($filepath, 'I');

        $pdf->Output($filepath, 'F');  //保存到指定目录
        Utils::Download($filepath, $title, 'pdf'); //下载pdf
//============================================================+
// END OF FILE
//============================================================+
    }
    
     /**
     * 预览流程图
     */
    public function actionPreview() {
        $flow_id = $_REQUEST['flow_id'];
        $apply_id = $_REQUEST['apply_id'];
        $app_id = $_REQUEST['app_id'];
        $meeting = Meeting::model()->findByPk($apply_id);
        $app_id = 'TBM';
        $step_list = WorkflowDetail::stepList($flow_id);
        $year = substr($meeting->record_time, 0, 4);//年
        $progress_list = CheckApplyDetail::progressList($app_id,$apply_id,$year);//审批进度流程
        $status_css = CheckApplyDetail::statusText();
        $result_text = CheckApplyDetail::resultText();
        $pending_text = CheckApplyDetail::pendingText();
        $this->renderPartial('preview', array('step_list' => $step_list,'result_text' => $result_text,'progress_list'=>$progress_list,'pending_text'=>$pending_text,'status_css'=>$status_css));
    }
    
    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['meeting/list'] = str_replace("r=tbm/meeting/grid", "r=tbm/meeting/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

    /**
     * 公司统计图表
     */
    public function actionCompanyChart() {
        //ExcelExport::updateInfo();
        $platform = $_REQUEST['platform'];
        $program_id = $_REQUEST['program_id'];
        $this->smallHeader = Yii::t('dboard', 'Company Statistical Charts');
        if(!is_null($platform)) {
            $this->layout = '//layouts/main_2';
            $this->render('statistical_company_dash', array('program_id' => $program_id,'platform'=>$platform));
        }else{
            $this->render('statistical_company', array('program_id' => $program_id));
        }
    }
    /**
     *查询违规次数（公司）
     */
    public function actionCntByCompany() {
        $args['program_id'] = $_REQUEST['id'];
        $args['date'] = $_REQUEST['date'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $r = Meeting::CompanyCntList($args);
        print_r(json_encode($r));
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genPlanGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=tbm/meeting/plangrid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('tbm_meeting', 'seq'), '', 'center');
        $t->set_header(Yii::t('tbm_meeting', 'program_name'), '', 'center');
        $t->set_header(Yii::t('license_licensepdf', 'company'), '', 'center');
        $t->set_header(Yii::t('tbm_meeting', 'title'), '', 'center');
        $t->set_header(Yii::t('tbm_meeting', 'apply_date'), '', 'center');
        $t->set_header(Yii::t('tbm_meeting', 'status'), '', 'center');
        $t->set_header(Yii::t('common', 'action'), '15%', 'center');
        return $t;
    }

    /**
     * 查询
     */
    public function actionPlanGrid() {
        $fields = func_get_args();
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件

        // if(count($fields) == 3 && $fields[0] != null ) {
        //     $args['program_id'] = $fields[0];
        //     $args['user_id'] = $fields[1];
        //     $args['deal_type'] = $fields[2];
        // }
        $t = $this->genPlanGrid();
        $this->saveUrl();

        $args['contractor_id'] = Yii::app()->user->contractor_id;
        $list = MeetingPlan::queryList($page, $this->pageSize, $args);
        $this->renderPartial('plan_list', array('t' => $t, 'rows' => $list['rows'],'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionPlanList() {

        $this->smallHeader = Yii::t('dboard', 'Menu Meetingdown Plan');
        $this->render('planlist');
    }

    /**
     * 添加计划
     */
    public function actionNewPlan() {

        $this->smallHeader = Yii::t('tbm_meeting', 'add_plan');
        $model = new MeetingPlan('create');
        $r = array();
        if (isset($_POST['MeetingPlan'])) {
            $args = $_POST['MeetingPlan'];
            $r = MeetingPlan::InsertList($args);

        }

        $this->render('plan_form', array('model' => $model,'msg' => $r,'_mode_'=>'insert'));
    }
    /**
     * 修改
     */
    public function actionEditPlan() {
        $this->smallHeader = Yii::t('tbm_meeting', 'edit_plan');
        $model = new MeetingPlan('modify');
        $r = array();
        $plan_id = $_REQUEST['plan_id'];
        $plan_model = MeetingPlan::model()->findByPk($plan_id);
        if (isset($_POST['MeetingPlan'])) {
            $args = $_POST['MeetingPlan'];

            $r = MeetingPlan::UpdateList($plan_id,$args);
            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['MeetingPlan'];
            }
        }

        $model->_attributes = MeetingPlan::model()->findByPk($plan_id);

        $this->render('plan_form', array('plan_id'=>$plan_id,'model' => $model, 'msg' => $r,'_mode_'=>'edit'));
    }

    /**
     * 注销
     */
    public function actionDelPlan() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = MeetingPlan::DeleteList($id);
        }
        echo json_encode($r);
    }

    //导出员工信息表
    public function actionExport(){
        $args = $_GET['q'];
        $pro_model = Program::model()->findByPk($args['program_id']);
        $program_name = $pro_model->program_name;
        $list = MeetingPlan::exportList($args);

        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        $objectPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objectPHPExcel->getActiveSheet();
        $objActSheet->setTitle('Sheet1');

        //报表头的输出
        $objectPHPExcel->getActiveSheet()->mergeCells('A1:N1');
        $objectPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('A1',$program_name.'--'.$args['plan_date']);
        $objStyleA1 = $objActSheet->getStyle('A1');
        //字体及颜色
        $objFontA1 = $objStyleA1->getFont();
        $objFontA1->setSize(20);
        $objectPHPExcel->getActiveSheet()->mergeCells('A2:B2');
        $objectPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('A2','SUNDAY');

        $objectPHPExcel->getActiveSheet()->mergeCells('C2:D2');
        $objectPHPExcel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('C2','MONDAY');

        $objectPHPExcel->getActiveSheet()->mergeCells('E2:F2');
        $objectPHPExcel->getActiveSheet()->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('E2','TUESDAY');

        $objectPHPExcel->getActiveSheet()->mergeCells('G2:H2');
        $objectPHPExcel->getActiveSheet()->getStyle('G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('G2','WEDNESDAY');

        $objectPHPExcel->getActiveSheet()->mergeCells('I2:J2');
        $objectPHPExcel->getActiveSheet()->getStyle('I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('I2','THURSDAY');

        $objectPHPExcel->getActiveSheet()->mergeCells('K2:L2');
        $objectPHPExcel->getActiveSheet()->getStyle('K2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('K2','FRIDAY');

        $objectPHPExcel->getActiveSheet()->mergeCells('M2:N2');
        $objectPHPExcel->getActiveSheet()->getStyle('M2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('M2','SATURDAY');

//            $objectPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth(20);

        //写入数据
        $plan_date = Utils::MonthToCn($args['plan_date']);
        $a = substr($plan_date,2,2);
        $b = substr($plan_date,5,2);
        $noe=mktime(0,0,0,$b,1,$a); //获取当前的月的一号
        $year=date("Y",$noe); //当前的年
        $month=date("m",$noe); //当前的月
        $week=date("w",$noe); // 每个月的一号是星期几
        $days=date("t",$noe); //每个月的总天数
        $day=date("d"); //获取今天是几号
        //var_dump($a);
        //var_dump($b);
        //var_dump($year);
        //var_dump($month);
        //var_dump($week);
        //var_dump($days);
        //var_dump($day);
        //exit;
        $head = array('A','C','E','G','I','K','M');
        $row =3;
        $tag =$week;
        for($i=0;$i<$week;$i++){
            $num = ord($head[$i]);
            $num = $num +1 ;
            $tag_last = chr($num);
            $row_last = $row+1;
            $objectPHPExcel->getActiveSheet()->mergeCells($head[$i].$row_last.':'.$tag_last.$row_last);
            if($head[$i] == 'A'){
                $objectPHPExcel->getActiveSheet()->getStyle($head[$i].$row_last)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FF00FF');
            }
        }
        for($k=1;$k<=$days;$k++){
            $k_str = str_pad($k,2,0,STR_PAD_LEFT);
            $date = $plan_date.'-'.$k_str;
            if($k==$day){
                $objectPHPExcel->getActiveSheet()->setCellValue($head[$tag].$row,$k);
                $num = ord($head[$tag]);
                $num = $num +1 ;
                $tag_last = chr($num);
                $row_last = $row+1;
                $objectPHPExcel->getActiveSheet()->mergeCells($head[$tag].$row_last.':'.$tag_last.$row_last);
                $objectPHPExcel->getActiveSheet()->getRowDimension($row_last)->setRowHeight(40);
                if($head[$tag] == 'A'){
                    $objectPHPExcel->getActiveSheet()->getStyle($head[$tag].$row_last)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FF00FF');
                }else{
                    $objectPHPExcel->getActiveSheet()->setCellValue($head[$tag].$row_last,$list[$date]);
                }

            }else{
                $objectPHPExcel->getActiveSheet()->setCellValue($head[$tag].$row,$k);
                $num = ord($head[$tag]);
                $num = $num +1 ;
                $row_last = $row+1;
                $tag_last = chr($num);
                $objectPHPExcel->getActiveSheet()->mergeCells($head[$tag].$row_last.':'.$tag_last.$row_last);
                $objectPHPExcel->getActiveSheet()->getRowDimension($row_last)->setRowHeight(40);
                if($head[$tag] == 'A'){
                    $objectPHPExcel->getActiveSheet()->getStyle($head[$tag].$row_last)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FF00FF');
                }else{
                    $objectPHPExcel->getActiveSheet()->setCellValue($head[$tag].$row_last,$list[$date]);
                }
            }
            if(($k+$week)%7==0){
                $tag = -1;
                $row = $row+2;
            }
            $tag++;
        }
        //下载输出
        ob_end_clean();
        //ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.'TBM Plan table-'.date("d M Y").'.xls"');
        $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter->save('php://output');
    }
}
