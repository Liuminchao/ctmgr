<?php

/**
 * 安全检查
 * @author LiuMinChao
 */
class WshinspectionController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';
    public $pageSize = 20;
    const STATUS_NORMAL = 0; //正常

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('comp_safety', 'contentHeader');
        //$this->contentHeader = '';
        $this->bigMenu = Yii::t('comp_safety', 'bigMenu');
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=wsh/wshinspection/grid';
        $t->updateDom = 'datagrid';
        //$t->set_header(Yii::t('comp_safety', 'check_id'), '', '');
        $t->set_header(Yii::t('comp_safety', 'sn'), '', 'center');
        $t->set_header(Yii::t('comp_safety', 'title'), '', 'center');
        //$t->set_header(Yii::t('comp_safety', 'root_proname'), '', 'center');
        $t->set_header(Yii::t('comp_safety', 'safety_type'), '', 'center');
        $t->set_header(Yii::t('comp_safety', 'safety_finding'), '', 'center');
        $t->set_header(Yii::t('comp_safety', 'Initiator'), '', 'center');
        $t->set_header(Yii::t('comp_safety', 'Person In Charge'), '', 'center');
        $t->set_header(Yii::t('comp_safety', 'Person Responsible'), '', 'center');
        //$t->set_header(Yii::t('comp_safety', 'company'), '', 'center');
        //$t->set_header(Yii::t('comp_safety', 'safety_level'), '', 'center');
        //$t->set_header(Yii::t('comp_safety', 'violation_record'), '', '');
        $t->set_header(Yii::t('comp_safety', 'apply_time'), '', 'center');
        $t->set_header(Yii::t('comp_safety', 'stipulation_time'), '', 'center');
        $t->set_header(Yii::t('comp_safety', 'status'), '', 'center');
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
        if(count($fields) == 4 && $fields[0] != null ) {
            $args['program_id'] = $fields[0];
            $args['user_id'] = $fields[1];
            $args['deal_type'] = $fields[2];
            $args['safety_level'] = $fields[3];
        }
        $t = $this->genDataGrid();
        $this->saveUrl();
        //$args['status'] = ApplyBasic::STATUS_FINISH;
        $args['contractor_id'] = Yii::app()->user->contractor_id;

        $list = SafetyCheck::newqueryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t,'program_id'=>$args['program_id'],'app_id'=>$app_id,'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {
        $user_id = $_REQUEST['user_id'];
        $program_id = $_REQUEST['program_id'];
        $deal_type = $_REQUEST['deal_type'];
        $safety_level = $_REQUEST['safety_level'];
        $this->smallHeader = Yii::t('comp_safety', 'smallHeader List');
        $this->render('list',array('user_id'=> $user_id,'program_id'=> $program_id,'deal_type'=>$deal_type,'safety_level'=>$safety_level));
    }
    /**
     * 表头
     * @return SimpleGrid
     */
    private function genRankingGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=wsh/wshinspection/rankinggrid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('comp_safety', 'date'), '', 'center');
        $t->set_header(Yii::t('comp_safety', 'ranking'), '', 'center');
        $t->set_header(Yii::t('comp_safety', 'user_name'), '', 'center');
        $t->set_header(Yii::t('comp_safety', 'violation_cnt'), '', 'center');
        $t->set_header(Yii::t('comp_safety', 'person_in_charge_name'), '', 'center');
        return $t;
    }
    /**
     * 违规排名列表
     */
    public function actionRankingList() {
        $program_id =$_REQUEST['program_id'];
        $month = $_REQUEST['month'];
        $this->smallHeader = Yii::t('dboard', 'Menu Violation Ranking');
        $this->contentHeader = Yii::t('dboard', 'Menu Violation Ranking');
        $this->render('rankinglist',array('month'=> $month,'program_id'=> $program_id));
    }
    /**
     * 违规排名查询
     */
    public function actionRankingGrid() {
        $fields = func_get_args();

        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        $start_date = $args['start_date'];
        $end_date = $args['end_date'];
        if (!empty($fields)) {
            $args['program_id'] = $fields[0];
        }
        $t = $this->genRankingGrid();
        $this->saveUrl();
        //$args['status'] = ApplyBasic::STATUS_FINISH;
        $args['contractor_id'] = Yii::app()->user->contractor_id;
        $pageSize = 10;
        $list = SafetyCheck::queryRankingList($page, $pageSize, $args);
        $this->renderPartial('ranking_list', array('t' => $t, 'rows_1' => $list['rows_1'],'rows_2' => $list['rows_2'],'max' => $list['max'],'start_date'=>$start_date,'end_date'=>$end_date, 'cnt_1' => $list['total_num_1'],'cnt_2' => $list['total_num_2'], 'curpage' => $list['page_num']));
    }
    /**
     * 导出安全报告界面
     */
    public function actionExport() {
        //$model = new SafetyCheck('create');
        $contractor_id = $_REQUEST['contractor_id'];
        //$args = $_GET['q']; //查询条件
        //$r = SafetyCheckDetail::detailAllList($args);
        $this->renderPartial('export', array('contractor_id' => $contractor_id));
    }

    /**
     * 下载列表
     */
    public function actionDownloadPreview() {
        $check_id = $_REQUEST['check_id'];
        $app_id = $_REQUEST['app_id'];
        $this->renderPartial('download_preview', array('check_id'=>$check_id,'app_id'=>$app_id));
    }

    /**
     * 下载附件列表
     */
    public function actionDownloadAttachment() {
        $check_id = $_REQUEST['check_id'];
        $form_data_list = SafetyDocument::detailList($check_id); //记录
        $this->renderPartial('download_attachment', array('check_id'=>$check_id,'form_data_list'=>$form_data_list));
    }


    /**
     * 导出安全报告
     */
    public function actionDownloadPdf() {
        $check_id = $_REQUEST['check_id'];
        $params['tag'] = $_REQUEST['tag'];
        $params['check_id'] = $check_id;
        $app_id = 'WSH';
        $check_list = SafetyCheck::detailList($check_id);//安全检查单
        $title = $check_list[0]['title'];//标题
        //$title = str_replace(PHP_EOL, ' ', $title);
        $title = str_replace(array("\r\n", "\r", "\n"), " ", $title);//替换空格
        $program_id = $check_list[0]['root_proid'];
        $pro_model = Program::model()->findByPk($program_id);
        $pro_params = $pro_model->params;//项目参数
        if($pro_params != '0') {
            $pro_params = json_decode($pro_params, true);
            //判断是否是迁移的
            if (array_key_exists('wsh_report', $pro_params)) {
                $params['type'] = $pro_params['wsh_report'];
            } else {
                $params['type'] = 'A';
            }
        }else{
            $params['type'] = 'A';
        }
        $params['month_tag'] = 0;
        $filepath = DownloadPdf::transferDownload($params,$app_id);
        Utils::Download($filepath, $title, 'pdf');
    }
    /**
     * 获取所有记录
     */
    /**
     * 根据条数来导出安全报告
     */
    /**
     * 最后下载压缩包
     */
    public function actionDownloadMonthPdf() {
        $program_id = $_REQUEST['program_id'];
        $remark = $_REQUEST['remark'];
        $month = $_REQUEST['month'];
        $app_id = 'WSH';
        $title = 'Shsd Month Report';//标题
        $pro_model = Program::model()->findByPk($program_id);
        $pro_params = $pro_model->params;//项目参数
        if($pro_params != '0') {
            $pro_params = json_decode($pro_params, true);
            //判断是否是迁移的
            if (array_key_exists('wsh_report', $pro_params)) {
                $params['type'] = $pro_params['wsh_report'];
            } else {
                $params['type'] = 'A';
            }
        }else{
            $params['type'] = 'A';
        }
        $params['program_id'] = $program_id;
        $params['remark'] = $remark;
        $params['month'] = $month;
        $params['month_tag'] = 1;
        $filepath = DownloadPdf::transferDownload($params,$app_id);
        Utils::Download($filepath, $title, 'pdf');
    }

    /**
     * 下载PDF{if $_lang.close_reg}style="display:none"{/if}
     */
    public static function actionDownloadPdf2() {

        $check_id = $_REQUEST['check_id'];
        //$a = SafetyCheckDetail::detailAllList();
        $check_list = SafetyCheck::detailList($check_id);//安全检查单
        $detail_list = SafetyCheckDetail::detailList($check_id);//安全检查单详情
        $level_list = SafetyLevel::levelText();//安全等级详情
        $type_list = SafetyCheckType::typeText();//安全类型详情
        $findings_list = SafetyCheckFindings::typeText();//检查类型
        $record_list = ViolationRecord::recordList($check_id);//违规记录
        $record_device_list = ViolationDevice::recordList($check_id);//设备违规记录
        $company_list = Contractor::compAllList();//承包商公司列表
        $deal_list = SafetyCheckDetail::dealList();//处理类型列表
        $device_type = DeviceType::deviceList();//设备类型
        $staff_list = Staff::userAllList();//所有人员列表
        $document_list = SafetyDocument::queryDocument($check_id);//文档列表

        $title = $check_list[0]['title'];//标题
        $contractor_id = $check_list[0]['contractor_id'];
        $contractor_name = $company_list[$contractor_id];//承包商名称
        $root_proname = $check_list[0]['root_proname'];//总包项目名称
        $root_proid = $check_list[0]['root_proid'];//总包项目ID
        $root_company = Program::ProgramCompany();//根据项目ID获取企业名称
        $block = $check_list[0]['block'];//一级区域
        $secondary_region = $check_list[0]['secondary_region'];//二级区域
        $description = $level_list[$check_list[0]['safety_level']];//安全等级描述
        $stipulation_time = $check_list[0]['stipulation_time'];//规定时间
        $person_in_charge_id = $check_list[0]['person_in_charge_id'];//负责人ID
        $person_in_charge = Staff::model()->findAllByPk($person_in_charge_id);//负责人
        $apply_user_id = $check_list[0]['apply_user_id'];//申请人ID
        $apply_user =  Staff::model()->findAllByPk($apply_user_id);//申请人
        $apply_time = $check_list[0]['apply_time'];//申请时间
        $close_time = $check_list[0]['close_time'];//关闭时间
        $violations_user = '';
        foreach($record_list as $n => $m){
            $violations_user .= '  '.$staff_list[$m['user_id']];
        }
        $lang = "_en";
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($apply_time,0,4);//年
        $month = substr($apply_time,5,2);//月
        $day = substr($apply_time,8,2);//日
        $hours = substr($apply_time,11,2);//小时
        $minute = substr($apply_time,14,2);//分钟
        $time = $day.$month.$year.$hours.$minute;
        $program_id = $check_list[0]['root_proid'];
        $filepath = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/'.$program_id.'/wsh/'.$contractor_id.'/WSH' . $check_id . $time .'.pdf';
        $full_dir = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/'.$program_id.'/wsh/'.$contractor_id;
        //$filepath = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/wsh'.'/WSH' . $check_id . '.pdf';
        //$full_dir = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/wsh'.'/WSH';
        if(!file_exists($full_dir))
        {
            umask(0000);
            @mkdir($full_dir, 0777, true);
        }
        SafetyCheck::updatepath($check_id,$filepath);
        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
        require_once($tcpdfPath);
        $pdf = new ReportPdf('P', 'mm', 'A4', true, 'UTF-8', false);
        //Yii::import('application.extensions.tcpdf.TCPDF');
        //$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);
        //$pdf->SetKeywords('PDF, LICEN');
        // 设置页眉和页脚信息
        $main_model = Contractor::model()->findByPk($contractor_id);
        $logo_pic = '/opt/www-nginx/web'.$main_model->remark;
        $_SESSION['title'] = 'WSH Inspection Records No. (安全检查记录编号): ' . $check_id; // 把标题存在$_SESSION['user'] 里面
        //if($logo_pic){
        //$logo = '/opt/www-nginx/web'.$logo_pic;
        //$pdf->SetHeaderData($logo, 20,  'WSH Inspection Records No. (安全检查记录编号:)' . $check_id,  $contractor_name,array(0, 64, 255), array(0, 64, 128));
        //else{
        //$pdf->SetHeaderData('', 0, '', 'WSH Inspection Records No. (安全检查记录编号:)' . $check_id, array(0, 64, 255), array(0, 64, 128));
        //}
        $pdf->Header($logo_pic);
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
        $pdf->setCellPaddings(1,1,1,1);

        // 设置页眉和页脚字体
        $pdf->setHeaderFont(Array('droidsansfallback', '', '30')); //英文

        $pdf->setFooterFont(Array('helvetica', '', '10'));

        //设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');

        //设置间距
        $pdf->SetMargins(15, 23, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        //设置分页
        $pdf->SetAutoPageBreak(TRUE, 25);
        //set image scale factor
        $pdf->setImageScale(1.25);
        //set default font subsetting mode
        $pdf->setFontSubsetting(true);
        //设置字体
        $pdf->SetFont('droidsansfallback', '', 8, '', true); //英文

        $pdf->AddPage();
        $user_list = Staff::allInfo();//员工信息（包括已被删除的）
        $roleList = Role::roleallList();//岗位列表
        $apply_role = $apply_user[0]['role_id'];//发起人角色

        $pro_model = Program::model()->findByPk($root_proid);
        $pro_params = $pro_model->params;//项目参数
        $apply_contractor_id = $apply_user[0]['contractor_id'];//申请人公司
        $pro_contractor_id = $pro_model->contractor_id;//总包公司
        if($pro_params != '0') {
            $pro_params = json_decode($pro_params, true);
            //判断是否是迁移的
            if (array_key_exists('transfer_con', $pro_params)) {
                if($apply_contractor_id == $pro_contractor_id){
                    $main_conid = $pro_params['transfer_con'];
                }else{
                    $main_conid = $pro_model->contractor_id;//总包编号
                }
            } else {
                $main_conid = $pro_model->contractor_id;//总包编号
            }
        }else{
            $main_conid = $pro_model->contractor_id;//总包编号
        }

        //标题(许可证类型+项目)
        $title_html = "<h1 style=\"font-size: 300% \" align=\"center\">{$company_list[$main_conid]}</h1><br/><h2 style=\"font-size: 200%\" align=\"center\">Project (项目) : {$root_proname}</h2>
            <h2 style=\"font-size: 200%\" align=\"center\">WSH Inspection Title (安全检查标题): {$title}</h2><br/>";
        $pdf->writeHTML($title_html, true, false, true, false, '');
        $y = $pdf->GetY();
        //发起人详情
        $apply_time = Utils::DateToEn($apply_time);
        $apply_info_html = "<br/><br/><h2 align=\"center\">Initiator Details (发起人详情)</h2><table style=\"border-width: 1px;border-color:gray gray gray gray\">";
        $apply_info_html .="<tr><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Name (姓名)</td><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Designation (职位)</td><td height=\"20px\" width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">ID Number (身份证号码)</td></tr>";
        $apply_info_html .="<tr><td height=\"50px\" style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$apply_user[0]['user_name']}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$roleList[$apply_role]}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$apply_user[0]['work_no']}</td></tr>";
        $apply_info_html .="<tr><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Company (公司)</td><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Date of Initiation (发起时间)</td><td height=\"20px\" width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Electronic Signature (电子签名)</td></tr>";
        $apply_info_html .="<tr><td height=\"50px\" style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$company_list[$apply_user[0]['contractor_id']]}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$apply_time}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;</td></tr>";
        $apply_info_html .="</table>";
        //判断电子签名是否存在 $add_operator->signature_path
        $content_list = $user_list[$apply_user_id];
        $content = $content_list[0]['signature_path'];
        //$content = '/opt/www-nginx/web/filebase/record/2018/04/sign/pic/sign1523249465052_1.jpg';
        if(file_exists($content)){
            $pdf->Image($content, 150, $y+50, 20, 9, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
        }
        $charge_role = $person_in_charge[0]['role_id'];//发起人角色
        //负责人详情
        $charge_info_html = "<br/><br/><h2 align=\"center\">Person In Charge Details (负责人详情)</h2><table style=\"border-width: 1px;border-color:gray gray gray gray\">";
        $charge_info_html .="<tr><td height=\"20px\" width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Name (姓名)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Designation (职位)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">ID Number (身份证号码)</td><td  width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Company (公司)</td></tr>";
        $charge_info_html .="<tr><td height=\"50px\" style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$person_in_charge[0]['user_name']}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$roleList[$charge_role]}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$person_in_charge[0]['work_no']}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$company_list[$person_in_charge[0]['contractor_id']]}</td></tr>";
        $charge_info_html .="</table>";
        $charge_role = $person_in_charge[0]['role_id'];//发起人角色

        //安全检查详情
        $work_content_html = "<br/><br/><h2 align=\"center\">WSH Inspection Details (安全检查详情)</h2><table style=\"border-width: 1px;border-color:gray gray gray gray\" >";
        $work_content_html .="<tr><td height=\"20px\" width=\"20%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Work Location<br>(工作地点)</td><td width=\"20%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Severity Level<br>(严重性等级)</td><td width=\"20%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Type of Inspection<br>(检查类型)</td><td width=\"20%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Type of Findings<br>(检查类型)</td><td width=\"20%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Expected Completion Time<br>(预计完成时间)</td></tr>";
        $work_content_html .="<tr><td height=\"50px\" style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">".$block.'--'.$secondary_region."</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">".$check_list[0]['safety_level'].'-' .$description."</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">".$type_list[$check_list[0]['type_id']]. "</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">".$findings_list[$check_list[0]['findings_id']]. "</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">".Utils::DateToEn($check_list[0]['stipulation_time']). "</td></tr></table>";

        //责任人详情
        $responsible_info_html = "<br/><br/><h2 align=\"center\">Responsible Person(s) Details (责任人详情)</h2>";
        $responsible_info_html .= "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
        if($record_list) {
            foreach ($record_list as $n => $m) {
                $responsible_userid = $m['user_id'];
                $responsible_user = Staff::model()->findAllByPk($responsible_userid);//责任人
                $responsible_role = $responsible_user[0]['role_id'];//责任人角色
                $responsible_info_html .= "<tr><td height=\"20px\" width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Name (姓名)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Designation (职位)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">ID Number (身份证号码)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Company (公司)</td></tr>";
                $responsible_info_html .= "<tr><td height=\"50px\" style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$responsible_user[0]['user_name']}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$roleList[$responsible_role]}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$responsible_user[0]['work_no']}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$company_list[$responsible_user[0]['contractor_id']]}</td></tr>";
            }
            $responsible_info_html .= "</table>";
        }else{
            $responsible_info_html .= "<tr><td height=\"20px\" width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Name (姓名)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Designation (职位)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">ID Number (身份证号码)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Company (公司)</td></tr>";
            $responsible_info_html .='<tr><td colspan="4" style="text-align: center;border-width: 1px;border-color:gray gray gray gray">Nil</td></tr>';
            $responsible_info_html .="</table>";
        }

        //设备详情
        $primary_list = Device::primaryAllList();
        $device_info_html = "<br/><br/><h2 align=\"center\">Equipment Details (设备详情)</h2>";
        $device_info_html .= "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
        if($record_device_list) {
            foreach ($record_device_list as $n => $m) {
                $device_id = $m['device_id'];
                $device_model = Device::model()->findByPk($device_id);//设备
                $type_no = $device_model->type_no;
                $devicetype_model = DeviceType::model()->findByPk($type_no);//设备类型信息
                $device_type_ch = $devicetype_model->device_type_ch;
                $device_type_en = $devicetype_model->device_type_en;
                $device_info_html .= "<tr><td height=\"20px\" width=\"10%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">S/N<br>(序号)</td><td height=\"20px\" width=\"30%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Registration No.<br>(设备编码)</td><td height=\"20px\" width=\"30%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Equipment Name <br>(设备名称)</td><td width=\"30%\"  nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Equipment Type <br>(设备类型)</td></tr>";
                $device_info_html .= "<tr><td height=\"50px\" style=\"text - align: center;border - width: 1px;border - color:gray gray gray gray\">&nbsp;1</td><td height=\"50px\" style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$primary_list[$device_id]}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$device_model->device_name}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$device_type_ch}<br>{$device_type_en}</td></tr>";
            }
            $device_info_html .= "</table>";
        }else{
            $device_info_html .= "<tr><td height=\"20px\" width=\"10%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">S/N<br>(序号)</td><td height=\"20px\" width=\"30%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Registration No.<br>(设备编码)</td><td height=\"20px\" width=\"30%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Equipment Name <br>(设备名称)</td><td width=\"30%\"  nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Equipment Type <br>(设备类型)</td></tr>";
            $device_info_html .='<tr><td colspan="4" style="text-align: center;border-width: 1px;border-color:gray gray gray gray">Nil</td></tr>';
            $device_info_html .="</table>";
        }

        //文档标签
        $document_html = '<br/><br/><h2 align="center">Attachment(s) (附件)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N (序号)</td><td  width="80%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Document Name (文档名称)</td></tr>';
        if(!empty($document_list)){
            $i =1;
            foreach($document_list as $cnt => $name){
                $document_html .='<tr><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $i . '</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $name . '</td></tr>';
                $i++;
            }
        }else{
            $document_html .='<tr><td colspan="2" style="text-align: center;border-width: 1px;border-color:gray gray gray gray">Nil</td></tr>';
        }
        $document_html .= '</table>';

        $info_x = 44;//X方向距离
        $info_y_1 = 227;//第一页Y方向距离
        $cnt_1 = 0;
        $cnt_2 = 0;

        $html_1 = $apply_info_html . $charge_info_html . $work_content_html;
        $pdf->writeHTML($html_1, true, false, true, false, '');
        $num = count($detail_list);

        if (!empty($detail_list)) {
            $pdf->AddPage();
        }
        $info_x_2 = $pdf->GetX()+2;//17
        $info_y_2 = $pdf->GetY()+13;//33
        $first_html = '<table style="border-width: 1px;border-color:lightslategray lightslategray lightslategray lightslategray"><tr><td><h2 align="center">Photo(s) - Before (照片 - 之前)</h2></td><td><h2 align="center">Photo(s) - After (照片 - 之后)</h2></td></tr><table style="border-width: 1px;border-color:lightslategray lightslategray lightslategray lightslategray"> <tr><td width="50%" border="1px"; height="840px"></td><td width="50%" border="1px"; height="840px"></td></tr></table>';

        // $content_before = ['http://shell.cmstech.sg/opt/www-nginx/web/filebase/record/2019/03/tbm/pic/tbm_1553647797221_1.jpg','http://shell.cmstech.sg/opt/www-nginx/web/filebase/record/2019/03/tbm/pic/tbm_1553647797221_1.jpg','http://shell.cmstech.sg/opt/www-nginx/web/filebase/record/2019/03/tbm/pic/tbm_1553647797221_1.jpg','http://shell.cmstech.sg/opt/www-nginx/web/filebase/record/2019/03/tbm/pic/tbm_1553647797221_1.jpg'];
        // $content_after = ['http://shell.cmstech.sg/opt/www-nginx/web/filebase/record/2019/03/tbm/pic/tbm_1553647797221_1.jpg','http://shell.cmstech.sg/opt/www-nginx/web/filebase/record/2019/03/tbm/pic/tbm_1553647797221_1.jpg'];
        if (!empty($detail_list)) {
            $detail_count = count($detail_list);
            $content_before = explode('|', $detail_list[0]['pic']);//之前
            $content_after  = explode('|', $detail_list[$detail_count-2]['pic']);//之后

            $n=0;
            foreach ($content_before as $key => $value) {
                $list[$n]['before'] = $value;
                $n++;
            }
            $n=0;
            foreach ($content_after as $key => $value) {
                $list[$n]['after'] = $value;
                $n++;
            }
            $i = 1;
            foreach ($list as $k => $v) {
                if(count($v)==2){
                    foreach ($v as $key => $content) {
                        if ($content != '' && $content != 'nil' && $content != '-1') {
                            $type = getimagesize($content);
                            if ($i%2!=0) {
                                if ($key=='before') {
                                    if($type['mime'] == 'image/jpeg'){
                                        $pdf->Image($content, $info_x_2, $info_y_2, 85, 110, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                                    }else{
                                        $pdf->Image($content, $info_x_2, $info_y_2, 85, 110, 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);
                                    }
                                }
                                if ($key=='after') {
                                    if($type['mime'] == 'image/jpeg'){
                                        $pdf->Image($content, $info_x_2+90, $info_y_2, 85, 110, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                                    }else{
                                        $pdf->Image($content, $info_x_2+90, $info_y_2, 85, 110, 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);
                                    }
                                }
                            }else{
                                if ($key=='before') {
                                    if($type['mime'] == 'image/jpeg'){
                                        $pdf->Image($content, $info_x_2, $info_y_2+115, 85, 110, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                                    }else{
                                        $pdf->Image($content, $info_x_2, $info_y_2+115, 85, 110, 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);
                                    }
                                }
                                if($key=='after'){
                                    $pdf->writeHTML($first_html, true, false, true, false, '');
                                    if($type['mime'] == 'image/jpeg'){
                                        $pdf->Image($content, $info_x_2+90, $info_y_2+115, 85, 110, 'JPG', '', '', false, 300, '', false, false, 0, \false, false, false);
                                    }else{
                                        $pdf->Image($content, $info_x_2+90, $info_y_2+115, 85, 110, 'PNG', '', '', false, 300, '', false, false, 0, \false, false, false);
                                    }
                                    $pdf->AddPage();

                                }
                            }
                        }
                    }
                }else{
                    foreach ($list[$i-1] as $key => $content) {
                        if ($content != '' && $content != 'nil' && $content != '-1') {
                            $type = getimagesize($content);
                            if ($key=='before') {
                                if ($i%2!=0) {
                                    if($type['mime'] == 'image/jpeg'){
                                        $pdf->Image($content, $info_x_2, $info_y_2, 85, 110, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                                    }else{
                                        $pdf->Image($content, $info_x_2, $info_y_2, 85, 110, 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);
                                    }

                                }else{
                                    $pdf->writeHTML($first_html, true, false, true, false, '');
                                    if($type['mime'] == 'image/jpeg'){
                                        $pdf->Image($content, $info_x_2, $info_y_2+115, 85, 110, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                                    }else{
                                        $pdf->Image($content, $info_x_2, $info_y_2+115, 85, 110, 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);
                                    }
                                    $pdf->AddPage();
                                }
                            }else{
                                if ($i%2!=0) {
                                    if($type['mime'] == 'image/jpeg'){
                                        $pdf->Image($content, $info_x_2+90, $info_y_2, 85, 110, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                                    }else{
                                        $pdf->Image($content, $info_x_2+90, $info_y_2, 85, 110, 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);
                                    }

                                }else{
                                    $pdf->writeHTML($first_html, true, false, true, false, '');
                                    if($type['mime'] == 'image/jpeg'){
                                        $pdf->Image($content, $info_x_2+90, $info_y_2+115, 85, 110, 'JPG', '', '', false, 300, '', false, false, 0, \false, false, false);
                                    }else{
                                        $pdf->Image($content, $info_x_2+90, $info_y_2+115, 85, 110, 'PNG', '', '', false, 300, '', false, false, 0, \false, false, false);
                                    }
                                    $pdf->AddPage();
                                }
                            }
                        }
                    }
                }
                $i++;
            }
            if (count($list)%2==1) {
                $pdf->writeHTML($first_html, true, false, true, false, '');
                $pdf->AddPage();
            }
        }
        //我注释的2019-04-03
        // if (!empty($detail_list)) {
        //     $pdf->AddPage();
        // }

        $check_detail_html_2 = '<br/><br/><h2 align="center">WSH Inspection Process (安全检查流程)</h2><table style="border-width: 1px;border-color:lightslategray lightslategray lightslategray lightslategray">
            <tr><td  height="20px" width="10%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Step<br>(步骤)</td><td width="45%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Content<br>(内容)</td><td width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Correspondents<br>(对应人)</td><td width="25%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Date & Time<br>(日期&时间)</td></tr>';
        if (!empty($detail_list)){
            foreach ($detail_list as $cnt => $list) {
                if($list['step']%2==0) {
                    $check_detail_html_2 .= '<tr ><td height="80px"  style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $list['step'] . '</td><td style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $list['description'] . '</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' .$person_in_charge[0]['user_name'].'</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . Utils::DateToEn($list['record_time']) . '</td></tr>';
                }else{
                    $check_detail_html_2 .= '<tr ><td height="80px"  style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $list['step'] . '</td><td style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $list['description'] . '</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' .$apply_user[0]['user_name'].'</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . Utils::DateToEn($list['record_time']) . '</td></tr>';
                }
                $info_y_2 +=45;
            }
        }
        $check_detail_html_2 .= '</table>';
        $html_2 = $check_detail_html_2. $responsible_info_html . $device_info_html . $document_html;

        $pdf->writeHTML($html_2, true, false, true, false, '');

        //输出PDF
//        $pdf->Output($filepath, 'I');

        //$pdf->Output($filepath, 'D');
         $pdf->Output($filepath, 'F'); //保存到指定目录
         $title = $check_list[0]['title'];//标题
         Utils::Download($filepath, $title, 'pdf');
         echo $filepath;
//        return $filepath;
        //============================================================+
        // END OF FILE
        //============================================================+
    }


    /**
     * 下载PDF
     */
    public static function actionDownload() {

        $check_id = $_REQUEST['check_id'];
        //$a = SafetyCheckDetail::detailAllList();
        $check_list = SafetyCheck::detailList($check_id);//安全检查单
        $detail_list = SafetyCheckDetail::detailList($check_id);//安全检查单详情
        $level_list = SafetyLevel::levelText();//安全等级详情
        $type_list = SafetyCheckType::typeText();//安全类型详情
        $record_list = ViolationRecord::recordList($check_id);//违规记录
        $record_device_list = ViolationDevice::recordList($check_id);//设备违规记录
        $company_list = Contractor::compAllList();//承包商公司列表
        $program_list =  Program::programAllList();//获取承包商所有项目
        $deal_list = SafetyCheckDetail::dealList();//处理类型列表
        $device_type = DeviceType::deviceList();//设备类型
        $staff_list = Staff::userAllList();//所有人员列表

        $title = $check_list[0]['title'];//标题
        $contractor_id = $check_list[0]['contractor_id'];
        $contractor_name = $company_list[$contractor_id];//承包商名称
        $root_proname = $check_list[0]['root_proname'];//总包项目名称
        $block = $check_list[0]['block'];//一级区域
        $secondary_region = $check_list[0]['secondary_region'];//二级区域
        $description = $level_list[$check_list[0]['safety_level']];//安全等级描述
        $stipulation_time = $check_list[0]['stipulation_time'];//规定时间
        $person_in_charge_id = $check_list[0]['person_in_charge_id'];//负责人ID
        $person_in_charge = Staff::model()->findAllByPk($person_in_charge_id);//负责人
        $apply_user_id = $check_list[0]['apply_user_id'];//申请人ID
        $apply_user =  Staff::model()->findAllByPk($apply_user_id);//申请人
        $apply_time = $check_list[0]['apply_time'];//申请时间
        $close_time = $check_list[0]['close_time'];//关闭时间
        $violations_user = '';
        foreach($record_list as $n => $m){
            $violations_user .= '  '.$staff_list[$m['user_id']];
        }
        $lang = "_en";
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($apply_time,0,4);//年
        $month = substr($apply_time,5,2);//月
//        if($check_list[0]['save_path']){
//            $file_path = $check_list[0]['save_path'];
//            $filepath = '/opt/www-nginx/web'.$file_path;
//        }else{
            $filepath = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/wsh'.'/WSH' . $check_id . '.pdf';
            $full_dir = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/wsh'.'/WSH';
            if(!file_exists($full_dir))
            {
                umask(0000);
                @mkdir($full_dir, 0777, true);
            }
            SafetyCheck::updatepath($check_id,$filepath);
//        }

        // if (file_exists($filepath)) {
        //     $show_name = $title;
        //     $extend = 'pdf';
        //     Utils::Download($filepath, $show_name, $extend);
        //     return;
        // }
        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
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
        $pdf->SetHeaderData('', 0, '', 'WSH Inspection Records No. (安全检查记录编号:)' . $check_id, array(0, 64, 255), array(0, 64, 128));
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        // 设置页眉和页脚字体

        // if (Yii::app()->language == 'zh_CN') {
        //     $pdf->setHeaderFont(Array('stsongstdlight', '', '10')); //中文
        // } else if (Yii::app()->language == 'en_US') {
            $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //英文
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
        // if (Yii::app()->language == 'zh_CN') {
        //     $pdf->SetFont('stsongstdlight', '', 14, '', true); //中文
        // } else if (Yii::app()->language == 'en_US') {
            $pdf->SetFont('droidsansfallback', '', 9, '', true); //英文
        //}

        $pdf->AddPage();
        $user_list = Staff::userInfo();//员工信息
        $roleList = Role::roleallList();//岗位列表
        $apply_role = $apply_user[0]['role_id'];//发起人角色
        //标题(许可证类型+项目)
        $title_html = "<h2 align=\"center\">Project (项目) : {$root_proname}</h2>
            <h2 align=\"center\">WSH Inspection Title (安全检查标题): {$title}</h2><br/>";
        //发起人详情
        $apply_info_html = "<h4 align=\"center\">Initiator Details (发起人详情)</h4><table border=\"1\">";
        $apply_info_html .="<tr><td width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Name (姓名)</td><td width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Designation (职位)</td><td width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">ID Number (身份证号码)</td></tr>";
        $apply_info_html .="<tr><td height=\"50px\" align=\"center\">&nbsp;{$apply_user[0]['user_name']}</td><td align=\"center\">&nbsp;{$roleList[$apply_role]}</td><td align=\"center\">&nbsp;{$apply_user[0]['work_no']}</td></tr>";
        $apply_info_html .="<tr><td width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Company (公司)</td><td width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Date of Initiation (发起时间)</td><td width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Electronic Signature (电子签名)</td></tr>";
        $apply_info_html .="<tr><td height=\"50px\" align=\"center\">&nbsp;{$company_list[$contractor_id]}</td><td align=\"center\">&nbsp;{$apply_time}</td><td align=\"center\">&nbsp;</td></tr>";
        $apply_info_html .="</table>";
        // //判断电子签名是否存在 $add_operator->signature_path
        // $content_list = $user_list[$apply_user_id];
        // $content = $content_list[0]['signature_path'];
        // if($content){
        //     $pdf->Image($content, 150, 76, 20, 9, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
        // }
        $charge_role = $person_in_charge[0]['role_id'];//发起人角色
        //负责人详情
        $charge_info_html = "<h4 align=\"center\">Person In Charge Details (负责人详情)</h4><table border=\"1\">";
        $charge_info_html .="<tr><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Name (姓名)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Designation (职位)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">ID Number (身份证号码)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Company (公司)</td></tr>";
        $charge_info_html .="<tr><td height=\"50px\" align=\"center\">&nbsp;{$person_in_charge[0]['user_name']}</td><td align=\"center\">&nbsp;{$roleList[$charge_role]}</td><td align=\"center\">&nbsp;{$person_in_charge[0]['work_no']}</td><td align=\"center\">&nbsp;{$company_list[$person_in_charge[0]['contractor_id']]}</td></tr>";
        $charge_info_html .="</table>";
        $charge_role = $person_in_charge[0]['role_id'];//发起人角色
        //责任人详情
        $responsible_info_html = "<h4 align=\"center\">Person Responsible Details (责任人详情)</h4>";
        if($record_list) {
            $responsible_info_html .="<table border=\"1\">";
            foreach ($record_list as $n => $m) {
                $responsible_userid = $m['user_id'];
                $responsible_user = Staff::model()->findAllByPk($responsible_userid);//责任人
                $responsible_role = $responsible_user[0]['role_id'];//责任人角色
                $responsible_info_html .= "<tr><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Name (姓名)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Designation (职位)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">ID Number (身份证号码)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Company (公司)</td></tr>";
                $responsible_info_html .= "<tr><td height=\"50px\" align=\"center\">&nbsp;{$responsible_user[0]['user_name']}</td><td align=\"center\">&nbsp;{$roleList[$responsible_role]}</td><td align=\"center\">&nbsp;{$responsible_user[0]['work_no']}</td><td align=\"center\">&nbsp;{$company_list[$responsible_user[0]['contractor_id']]}</td></tr>";
            }
            $responsible_info_html .="</table>";
        }

        //设备详情
        $device_info_html = "<h4 align=\"center\">Equipment(s) Details (设备详情)</h4>";
        if($record_device_list) {
            $device_info_html .="<table border=\"1\">";
            foreach ($record_device_list as $n => $m) {
                $device_id = $m['device_id'];
                $device_model = Device::model()->find('device_id=:device_id', array(':device_id' => $device_id));//设备
                $device_info_html .= "<tr><td width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Registration No. (设备编号)</td><td width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Equipment Type (设备类型)</td><td width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Equipment Name (设备名称)</td></tr>";
                $device_info_html .= "<tr><td height=\"50px\" align=\"center\">&nbsp;{$device_model->device_id}</td><td align=\"center\">&nbsp;{$device_type[$device_model->type_no]}</td><td align=\"center\">&nbsp;{$device_model->device_name}</td></tr>";
            }
            $device_info_html .="</table>";
        }




        //安全检查详情
        $work_content_html = "<h4 align=\"center\">WSH Inspection Details (安全检查详情)</h4><table border=\"1\" >";
        $work_content_html .="<tr><td width=\"10%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Work Location<br>(工作地点)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Zone, Block/Level<br>(区，座/层)</td><td width=\"20%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Severity Level<br>(严重性等级)</td><td width=\"20%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Type of Inspection<br>(检查类型)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Expected Completion Time<br>(预计完成时间)</td></tr>";
        $work_content_html .="<tr><td height=\"50px\" align=\"center\">{$block}</td><td align=\"center\">".$secondary_region. "</td><td align=\"center\">".$check_list[0]['safety_level'].'-' .$description."</td><td align=\"center\">".$type_list[$check_list[0]['type_id']]. "</td><td align=\"center\">".$check_list[0]['stipulation_time']. "</td></tr></table>";

        //$check_html = '
        //<h4 align="center">WSH Inspection(安全检查)</h4><table border="1"><tr><td width="35%" nowrap="nowrap">&nbsp;Title(标题)</td><td width="65%" nowrap="nowrap">&nbsp;'.$title.'</td></tr>
        //  <tr><td width="35%" nowrap="nowrap">&nbsp;Applicant Company(申请公司)</td><td width="65%" nowrap="nowrap">&nbsp;'.$company_list[$contractor_id].'</td></tr>
        //  <tr><td width="35%" nowrap="nowrap">&nbsp;The contractor project(总包项目)</td><td width="65%" nowrap="nowrap">&nbsp;'.$root_proname.'</td></tr>
        //  <tr><td width="35%" nowrap="nowrap">&nbsp;Block(一级区域)</td><td width="65%" nowrap="nowrap">&nbsp;'.$block.'</td></tr>
        //  <tr><td width="35%" nowrap="nowrap">&nbsp;Secondary Region(二级区域)</td><td width="65%" nowrap="nowrap">&nbsp;'.$secondary_region.'</td></tr>
        //  <tr><td width="35%" nowrap="nowrap">&nbsp;Safety Level(安全等级)</td><td width="65%" nowrap="nowrap">&nbsp;'.$check_list[0]['safety_level'].'</td></tr>
        // <tr><td width="35%" nowrap="nowrap">&nbsp;Safety Level Description(安全等级描述)</td><td width="65%" nowrap="nowrap">&nbsp;'.$description.'</td></tr>
        // <tr><td width="35%" nowrap="nowrap">&nbsp;Applicant(申请人)</td><td width="65%" nowrap="nowrap">&nbsp;'.$apply_user[0]['user_name'].'</td></tr>
        // <tr><td width="35%" nowrap="nowrap">&nbsp;Head(负责人)</td><td width="65%" nowrap="nowrap">&nbsp;'.$person_in_charge[0]['user_name'].'</td></tr>
        // <tr><td width="35%" nowrap="nowrap">&nbsp;Time of application(申请时间)</td><td width="65%" nowrap="nowrap">&nbsp;'.$apply_time.'</td></tr>
        // <tr><td width="35%" nowrap="nowrap">&nbsp;Closing time(关闭时间)</td><td width="65%" nowrap="nowrap">&nbsp;'.$close_time.'</td></tr>
        // <tr><td width="35%" height="43px" nowrap="nowrap">&nbsp;Applicant Electronic Signature(申请人电子签名)</td><td width="65%" nowrap="nowrap">&nbsp;</td></tr>
        // <tr><td width="35%" nowrap="nowrap">&nbsp;Violations personnel(违规人员)</td><td width="65%" nowrap="nowrap">&nbsp;'.$violations_user.'</td></tr></table>';
        ////$content = $user_list[$apply_user_id]['signature_path'];
        ////$pic = 'C:\Users\minchao\Desktop\5.png';
        //if($content != '') {
            //$pdf->Image($content, 100, 77, 32, 12, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
        //}


        $info_x = 44;//X方向距离
        $info_y_1 = 227;//第一页Y方向距离
        $info_y_2 = 44;//第二页Y方向距离
        $info_y_3 = 30;//第三页Y方向距离
        $cnt_1 = 0;
        $cnt_2 = 0;
        // $pic = 'C:\Users\minchao\Desktop\5.png';
        // if (!empty($detail_list)){
        //     foreach ($detail_list as $cnt => $list) {
        //         if($list['step'] <= 2){
        //             if($list['pic']) {
        //                 $pdf->Image($list['pic'], $info_x, $info_y_1, 26, 18, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
        //             }
        //             $check_detail_html_1 .= '<tr ><td height="80px">&nbsp;' . $list['step'] . '</td><td >&nbsp;</td><td >&nbsp;' . $list['description'] . '</td><td></td><td >&nbsp;' . Utils::DateToEn($list['record_time']) . '</td></tr>';
        //             $info_y_1 +=22;

        //         }
        //     }
        // }
        //$check_detail_html_1 .= '</table>';
        $html_1 = $title_html . $apply_info_html . $charge_info_html . $responsible_info_html . $device_info_html .$work_content_html ;
        $pdf->writeHTML($html_1, true, false, true, false, '');
        $num = count($detail_list);

        if (!empty($detail_list)) {
            $pdf->AddPage();
        }
        $check_detail_html_2 = '<h4 align="center">WSH Inspection Process (安全检查流程)</h4><table border="1">
            <tr><td  width="10%" nowrap="nowrap" bgcolor="#d9d9d9" align="center">&nbsp;Step<br>(步骤)</td><td  width="25%" nowrap="nowrap" bgcolor="#d9d9d9" align="center">&nbsp;Photo(s)<br>(照片)</td><td width="30%" nowrap="nowrap" bgcolor="#d9d9d9" align="center">&nbsp;Content<br>(内容)</td><td width="15%" nowrap="nowrap" bgcolor="#d9d9d9" align="center">&nbsp;Correspondents<br>(对应人)</td><td width="20%" nowrap="nowrap" bgcolor="#d9d9d9" align="center">&nbsp;Date & Time<br>(日期&时间)</td></tr>';
        if (!empty($detail_list)){
            foreach ($detail_list as $cnt => $list) {
                if($list['step'] < 13){
                    if($list['pic'] && $list['pic'] != -1) {
                        $pdf->Image($list['pic'], $info_x, $info_y_2, 26, 18, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                    }
                    if($list['step']%2==0) {
                        $check_detail_html_2 .= '<tr ><td height="80px" align="center">&nbsp;' . $list['step'] . '</td><td >&nbsp;</td><td >&nbsp;' . $list['description'] . '</td><td align="center">&nbsp;' .$person_in_charge[0]['user_name'].'</td><td align="center">&nbsp;' . Utils::DateToEn($list['record_time']) . '</td></tr>';
                    }else{
                        $check_detail_html_2 .= '<tr ><td height="80px" align="center">&nbsp;' . $list['step'] . '</td><td >&nbsp;</td><td >&nbsp;' . $list['description'] . '</td><td align="center">&nbsp;' .$apply_user[0]['user_name'].'</td><td align="center">&nbsp;' . Utils::DateToEn($list['record_time']) . '</td></tr>';
                    }
                    $info_y_2 +=22;

                }
            }
        }
        $check_detail_html_2 .= '</table>';
        $html_2 = $check_detail_html_2;
        if($num < 13) {
            $pdf->writeHTML($html_2, true, false, true, false, '');
        }

        if($num >= 13) {
            $pdf->AddPage();
        }
        $check_detail_html_3 = '<table border="1">';
        if (!empty($detail_list)){
            foreach ($detail_list as $cnt => $list) {
                if($list['step'] >= 13){
                    if($list['pic'] && $list['pic'] != -1) {
                        $pdf->Image($list['pic'], $info_x, $info_y_2, 26, 18, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                    }
                    if($list['step']%2==0) {
                        $check_detail_html_3 .= '<tr ><td height="80px" align="center">&nbsp;' . $list['step'] . '</td><td >&nbsp;</td><td >&nbsp;' . $list['description'] . '</td><td align="center">&nbsp;' .$person_in_charge[0]['user_name'].'</td><td align="center">&nbsp;' . Utils::DateToEn($list['record_time']) . '</td></tr>';
                    }else{
                        $check_detail_html_3 .= '<tr ><td height="80px" align="center">&nbsp;' . $list['step'] . '</td><td >&nbsp;</td><td >&nbsp;' . $list['description'] . '</td><td align="center">&nbsp;' .$apply_user[0]['user_name'].'</td><td align="center">&nbsp;' . Utils::DateToEn($list['record_time']) . '</td></tr>';
                    }
                    $info_y_3 +=22;

                }
            }
        }
        $check_detail_html_3 .= '</table>';
        $html_3 = $check_detail_html_3;
        if($num >= 13) {
            $pdf->writeHTML($html_3, true, false, true, false, '');
        }

        //输出PDF
        //$pdf->Output($filepath, 'I');

        //$pdf->Output($filepath, 'D');
        $pdf->Output($filepath, 'F'); //保存到指定目录
        Utils::Download($filepath, $title, 'pdf'); //下载pdf
//============================================================+
// END OF FILE
//============================================================+
    }

    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['licensepdf/list'] = str_replace("r=license/licensepdf/grid", "r=license/licensepdf/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }
     /**
     * 预览流程图
     */
    public function actionPreview() {
        $check_id = $_REQUEST['check_id'];
        $check_list = SafetyCheck::detailList($check_id);//安全检查单
        $detail_list = SafetyCheckDetail::detailList($check_id);//安全检查单详情
        $this->renderPartial('preview',array('check_list' => $check_list,'detail_list'=>$detail_list));
    }

    //导出信息表
    public static function actionExportExcel(){

        $fields = func_get_args();

        $args = $_GET['q']; //查询条件
        $args['tag'] = $_GET['tag'];

        if(count($fields) == 4 && $fields[0] != null ) {
            $args['program_id'] = $fields[0];
            $args['user_id'] = $fields[1];
            $args['deal_type'] = $fields[2];
            $args['safety_level'] = $fields[3];
        }
        //$args['status'] = ApplyBasic::STATUS_FINISH;
        $args['contractor_id'] = Yii::app()->user->contractor_id;

        $list = SafetyCheck::queryAllList($args);
        $type_list = SafetyCheckType::typeText();//安全类型详情
        //$staff_list = Staff::userAllList();//所有人员列表
        $company_list = Contractor::compAllList();//承包商公司列表
        $status_list = SafetyCheck::statusText(); //状态text

        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        $objectPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objectPHPExcel->getActiveSheet();
        $objActSheet->setTitle('Sheet1');

        //字体及颜色
        //$objFontA1 = $objStyleA1->getFont();
        //$objFontA1->setSize(20);
        $objectPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('A1',Yii::t('comp_safety', 'sn'));
        $objectPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('B1',Yii::t('comp_safety', 'root_proname'));
        $objectPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('C1',Yii::t('comp_safety', 'title'));
        $objectPHPExcel->getActiveSheet()->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('D1',Yii::t('comp_safety', 'safety_type'));
        $objectPHPExcel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('E1',Yii::t('comp_safety', 'Initiator'));
        $objectPHPExcel->getActiveSheet()->getStyle('F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('F1',Yii::t('comp_safety', 'Person In Charge'));
        $objectPHPExcel->getActiveSheet()->getStyle('G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('G1',Yii::t('comp_safety', 'Person Responsible'));
        $objectPHPExcel->getActiveSheet()->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('H1',Yii::t('comp_safety', 'company'));
        $objectPHPExcel->getActiveSheet()->getStyle('I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('I1',Yii::t('comp_safety', 'safety_level'));
        $objectPHPExcel->getActiveSheet()->getStyle('J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('J1',Yii::t('comp_safety', 'apply_time'));
        $objectPHPExcel->getActiveSheet()->getStyle('K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('K1',Yii::t('comp_safety', 'stipulation_time'));
        $objectPHPExcel->getActiveSheet()->getStyle('L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('L1',Yii::t('comp_safety', 'status'));
        ////设置颜色
        //$objectPHPExcel->getActiveSheet()->getStyle('AP3')->getFill()
        //->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');
        //写入数据
        $e = 1;
        foreach ($list as $k => $v) {
            //static $n = 1;
            /*设置表格高度*/
            $objectPHPExcel->getActiveSheet()->getRowDimension($k+1)->setRowHeight(90);
            $objectPHPExcel->getActiveSheet()->setCellValue(A . ($k + 2),$e);
            $e++;
            $objectPHPExcel->getActiveSheet()->setCellValue(B . ($k + 2),$v['root_proname']);
            $objectPHPExcel->getActiveSheet()->setCellValue(C . ($k + 2),$v['title']);
            $objectPHPExcel->getActiveSheet()->setCellValue(D . ($k + 2),$type_list[$v['type_id']]);
            $apply_user =  Staff::model()->findAllByPk($v['apply_user_id']);//申请人
            $objectPHPExcel->getActiveSheet()->setCellValue(E . ($k + 2),$apply_user[0]['user_name']);
            $person_in_charge = Staff::model()->findAllByPk($v['person_in_charge_id']);//负责人
            $objectPHPExcel->getActiveSheet()->setCellValue(F . ($k + 2),$person_in_charge[0]['user_name']);
            $record_list = ViolationRecord::recordList($v['check_id']);//违规记录
            $violations_user = '';
            foreach($record_list as $n => $m){
                $user_model = Staff::model()->findAllByPk($m['user_id']);
                $violations_user .= $user_model->user_name.',';
            }
            if ($violations_user != '')
                $violations_user = substr($violations_user, 0, strlen($violations_user) - 1);

            $objectPHPExcel->getActiveSheet()->setCellValue(G . ($k + 2),$violations_user);
            $objectPHPExcel->getActiveSheet()->setCellValue(H . ($k + 2),$company_list[$v['contractor_id']]);
            $objectPHPExcel->getActiveSheet()->setCellValue(I . ($k + 2),$v['safety_level']);
            $objectPHPExcel->getActiveSheet()->setCellValue(J . ($k + 2),Utils::DateToEn($v['apply_time']));
            $objectPHPExcel->getActiveSheet()->setCellValue(K . ($k + 2),Utils::DateToEn($v['stipulation_time']));
            $objectPHPExcel->getActiveSheet()->setCellValue(L . ($k + 2),$status_list[$v['status']]);
            //$n++;
        }

        //下载输出
        ob_end_clean();
        //ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.'NCR Report - '.date("d M Y").'.xls"');
        $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter->save('php://output');
    }

    /**
     * 按承包商查询检查类型
     */
    public function actionQueryType() {
        $program_id = $_POST['program_id'];

        $rows = SafetyCheckType::typeByContractor($program_id);

        print_r(json_encode($rows));
    }
    /**
     * 项目统计图表
     */
    public function actionProjectChart() {

        $program_id = $_REQUEST['program_id'];
        $platform = $_REQUEST['platform'];
        $type_id = $_REQUEST['type_id'];
        if(is_null($type_id)) {
            $type_id = '1';
        }
        if($type_id == '1'){
            $this->smallHeader = Yii::t('dboard', 'NCR Type Statistics');
            $this->contentHeader = Yii::t('dboard', 'NCR Type Statistics');
        }else{
            $this->smallHeader = Yii::t('dboard', 'Good Practices Statistics');
            $this->contentHeader = Yii::t('dboard', 'Good Practices Statistics');
        }
        if(!is_null($platform)) {
            $this->layout = '//layouts/main_2';
            $this->render('statistical_project_dash',array('program_id'=>$program_id,'platform'=>$platform,'type_id'=>$type_id));
        }else{
            $this->render('statistical_project',array('program_id'=>$program_id,'type_id'=>$type_id));
        }
    }
    /**
     * 公司统计图表
     */
    public function actionCompanyChart() {
        $platform = $_REQUEST['platform'];
        $program_id = $_REQUEST['program_id'];
        $this->smallHeader = Yii::t('dboard', 'Company Statistical Charts');
        $this->contentHeader = Yii::t('dboard', 'Company Statistical Charts');
        if(!is_null($platform)){
            $this->layout = '//layouts/main_2';
            $this->render('statistical_company_dash',array('program_id'=>$program_id,'platform'=>$platform));
        }else{
            $this->render('statistical_company',array('program_id'=>$program_id));
        }
    }
    /**
     *查询违规次数（项目）
     */
    public function actionCntByProject() {
        $args['program_id'] = $_REQUEST['id'];
        $args['date'] = $_REQUEST['date'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $type_id = $_REQUEST['type_id'];
        if($type_id == '1'){
            $r = SafetyCheck::AllNcrCntList($args);
        }else{
            $r = SafetyCheck::AllGoodCntList($args);
        }

        print_r(json_encode($r));
    }
    /**
     *查询违规次数（公司）
     */
    public function actionCntByCompany() {
        $args['program_id'] = $_REQUEST['id'];
        $args['date'] = $_REQUEST['date'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $r = SafetyCheck::CompanyCntList($args);
        print_r(json_encode($r));
    }
    /**
     * 分析项目统计图
     */
    public function actionAnalyseProgramData() {
        $args['program_id'] = $_REQUEST['id'];
        $args['date'] = $_REQUEST['date'];
        $args['tag'] = $_REQUEST['tag'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        if($args['tag'] == '1'){
            $first_r = SafetyCheck::AllNcrCntList($args);
        }else{
            $first_r = SafetyCheck::AllGoodCntList($args);
        }

        if(!empty($first_r)) {
            $show_data_1 = SafetyCheck::ShowProgramData($first_r, $args['tag'],$args['date']);
            $arr[] = $show_data_1;
        }
        // $args['date'] = $_REQUEST['second_date'];
        // $second_r = SafetyCheck::AllCntList($args);
        // if(!empty($second_r)) {
        //     $show_data_2 = SafetyCheck::ShowProgramData($second_r, $args['date']);
        //     $arr[] = $show_data_2;
        // }
        print_r(json_encode($arr));
    }

    /**
     * 分析公司统计图
     */
    public function actionAnalyseCompanyData() {
        $args['program_id'] = $_REQUEST['id'];
        $args['date'] = $_REQUEST['date'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $first_r = SafetyCheck::CompanyCntList($args);
        if(!empty($first_r)) {
            $show_data_1 = SafetyCheck::ShowCompanyData($first_r, $args['date']);
            $arr[] = $show_data_1;
        }
        // $args['date'] = $_REQUEST['second_date'];
        // $second_r = SafetyCheck::CompanyCntList($args);
        // if(!empty($second_r)) {
        //     $show_data_2 = SafetyCheck::ShowCompanyData($second_r, $args['date']);
        //     $arr[] = $show_data_2;
        // }
        print_r(json_encode($arr));
    }
    //导出信息表
    public static function actionExcel(){

        $fields = func_get_args();

        //$args = $_GET['q']; //查询条件
        $args['tag'] = $_GET['tag'];

        if(count($fields) == 4 && $fields[0] != null ) {
            $args['program_id'] = $fields[0];
            $args['user_id'] = $fields[1];
            $args['deal_type'] = $fields[2];
            $args['safety_level'] = $fields[3];
        }

        $args['program_id'] = $_REQUEST['program_id'];
        $args['month'] = $_REQUEST['month'];

        $args['contractor_id'] = Yii::app()->user->contractor_id;
        $list = SafetyCheck::queryExcelList($args);

        $levellist = SafetyLevel::levelText();

        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();
        $objectPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objectPHPExcel->getActiveSheet();
        $objActSheet->setTitle('Sheet1');

        // 设置宽度
        $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(22);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(22);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(45);
        $objectPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(30);
        $objectPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(45);
        //设置居中
        $objectPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getStyle('J')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getStyle('K')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //设置单元格内容自动换行
        $objectPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setWrapText(true);
        $objectPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setWrapText(true);
        $objectPHPExcel->getActiveSheet()->getStyle('F3')->getAlignment()->setWrapText(true);
        $objectPHPExcel->getActiveSheet()->getStyle('G3')->getAlignment()->setWrapText(true);
        $objectPHPExcel->getActiveSheet()->getStyle('H3')->getAlignment()->setWrapText(true);
        $objectPHPExcel->getActiveSheet()->getStyle('I3')->getAlignment()->setWrapText(true);
        $objectPHPExcel->getActiveSheet()->getStyle('J3')->getAlignment()->setWrapText(true);
        $objectPHPExcel->getActiveSheet()->getStyle('K3')->getAlignment()->setWrapText(true);

        //报表头的输出
        $objectPHPExcel->getActiveSheet()->mergeCells('A1:K1');
        $objectPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        if($args['tag'] == '1'){
            $objectPHPExcel->getActiveSheet()->setCellValue('A1','Safety Non-Conformity Report (NCR)');
        }else{
            $objectPHPExcel->getActiveSheet()->setCellValue('A1','Safety Good Practice Report (NCR)');
        }
        $objStyleA1 = $objActSheet->getStyle('A1');

        //字体及颜色
        $objFontA1 = $objStyleA1->getFont();
        $objFontA1->setSize(23);
        $objectPHPExcel->getActiveSheet()->mergeCells('A2:A3');
        $objectPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2','S/N');
        $objectPHPExcel->getActiveSheet()->mergeCells('B2:B3');
        $objectPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('B2','Location of Work');
        $objectPHPExcel->getActiveSheet()->mergeCells('C2:C3');
        $objectPHPExcel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('C2','Name of lnitiator');
        $objectPHPExcel->getActiveSheet()->mergeCells('D2:D3');
        $objectPHPExcel->getActiveSheet()->getStyle('D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('D2','Name of sub-contractors with safety non-compliances');
        $objectPHPExcel->getActiveSheet()->mergeCells('E2:E3');
        $objectPHPExcel->getActiveSheet()->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('E2','Trade With safety non-compliances');
        $objectPHPExcel->getActiveSheet()->mergeCells('F2:K2');
        $objectPHPExcel->getActiveSheet()->getStyle('F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        if($args['tag'] == '1'){
            $objectPHPExcel->getActiveSheet()->setCellValue('F2','NCR information');
        }else{
            $objectPHPExcel->getActiveSheet()->setCellValue('F2','Good Practice information');
        }
        $objectPHPExcel->getActiveSheet()->getStyle('F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('F3','Type of safety non-compliances');
        $objectPHPExcel->getActiveSheet()->getStyle('G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('G3','Type of safety non-compliances(Others)');
        $objectPHPExcel->getActiveSheet()->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('H3','Time safety non-compliances found');
        $objectPHPExcel->getActiveSheet()->getStyle('I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('I3','Time safety non-compliances closed by sub-contractor');
        $objectPHPExcel->getActiveSheet()->getStyle('J3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('J3','Duration taken to close safety non-compliances');
        $objectPHPExcel->getActiveSheet()->getStyle('K3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('K3','Safety non-compliances Severity Level');

        $company_list = Contractor::compAllList();
        $findings_list = SafetyCheckFindings::typeText();//检查类型
        //写入数据
        $i ='1';
        foreach ($list['rows'] as $k=>$v){

            $level_des = $v['safety_level'];
            //deal_type=2
            $rectified_time_list = SafetyCheckDetail::detailRectifiedTimeList($v['check_id']);
            if(!empty($rectified_time_list)) {
                foreach ($rectified_time_list as $kk=>$vv){
                    $rectified_time = Utils::DateToEn($vv['record_time']);
                }
            }else{
                    $rectified_time = '';
            }
            //deal_type=3
            $finish_time_list = SafetyCheckDetail::detailFinishTimeList($v['check_id']);
            if(!empty($finish_time_list)) {
                foreach ($finish_time_list as $kk=>$vv){
                    $finish_time = Utils::DateToEn($vv['record_time']);
                }
            }else{
                    $finish_time = '';
            }
            $root_model =Program::model()->findByPk($v['root_proid']);
            $root_conid = $root_model->contractor_id;
            $contractor_id = $v['contractor_id'];
            $recordList = ViolationRecord::recordList($v['check_id']);
            if($contractor_id != $root_conid){
                $contractor_name =$company_list[$contractor_id];
                $pro_model = Program::model()->find("root_proid=:root_proid and contractor_id=:contractor_id and status='00'",array(':root_proid'=>$v['root_proid'],'contractor_id'=>$contractor_id));
                $sub_type = $pro_model->sub_type;
            }else{
                $contractor_name='';
                $sub_type = '';
            }


            $num=$k+4;//从第4行开始写入数据，第一行是表名，第二,三行是表头
            //$count=count($rs["rows"])+3;//数组总个数
            if ($v['apply_time'] != '' && $finish_time != '') {
                $time_diff = Utils::getTimeDifference($v['apply_time'],$finish_time);
            }else{
                $time_diff['time_diff']='';
            }
            $user =Staff::model()->findByPk($v['apply_user_id']);
            $apply_user_name = $user->user_name;
            if($findings_list[$v['findings_id']] == 'Others'){
                $findings_name_en = $v['findings_name_en'];
            }else{
                $findings_name_en = '';
            }

            $objectPHPExcel->setActiveSheetIndex(0)
            //Excel的第A列，project_name是你查出数组的键值，下面以此类推
            ->setCellValue('A'.$num, $i++)
            ->setCellValue('B'.$num, $v['block'].' '.$v['secondary_region'])
            ->setCellValue('C'.$num, $apply_user_name)
            ->setCellValue('D'.$num, $contractor_name)
            ->setCellValue('E'.$num, $sub_type)
            ->setCellValue('F'.$num, $findings_list[$v['findings_id']])
            ->setCellValue('G'.$num, $findings_name_en)
            ->setCellValue('H'.$num, Utils::DateToEn($v['apply_time']))
            ->setCellValue('I'.$num, $rectified_time)
            ->setCellValue('J'.$num, $time_diff['time_diff'])
            ->setCellValue('K'.$num, $level_des);
            $objectPHPExcel->getActiveSheet()->getStyle('H'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objectPHPExcel->getActiveSheet()->getStyle('I'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }


        //下载输出
        ob_end_clean();
        header('Content-Type : application/vnd.ms-excel');
        if($args['tag'] == '1'){
            header('Content-Disposition:attachment;filename="'.'NCR Report –'.$args['month'].'.xls"');
        }else{
            header('Content-Disposition:attachment;filename="'.'Good Practice Report –'.$args['month'].'.xls"');
        }

        $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter->save('php://output');
    }

    /**
     * 月度报告
     */
    public function actionNcrReport() {
        $program_id = $_REQUEST['program_id'];
        $this->renderPartial('ncr_report',array('program_id'=>$program_id));
    }

    /**
     * 月度报告
     */
    public function actionMonthReport() {
        $program_id = $_REQUEST['program_id'];
        $this->renderPartial('month_report',array('program_id'=>$program_id));
    }

    /**
     * 读取一个月得记录
     */
    public function actionGetMonthData() {
        $args['id'] = $_REQUEST['id'];
        $args['month'] = $_REQUEST['month'];
        $args['remark'] = $_REQUEST['remark'];
        $r = SafetyCheck::RecordCnt($args);
        print_r(json_encode($r));
    }

    /**
     * 根据数量读取记录,生成pdf
     */
    public function actionReadMonthData()
    {
        $args['startrow'] = $_REQUEST['startrow'];
        $args['per_read_cnt'] = $_REQUEST['per_read_cnt'];
        $args['id'] = $_REQUEST['id'];
        $args['month'] = $_REQUEST['month'];
        $args['remark'] = $_REQUEST['remark'];
        $r = SafetyCheck::RecordPdf($args);
        print_r(json_encode($r));
    }

    /**
     * 下载压缩包
     */
    public function actionDownloadZip()
    {
        $program_id = $_REQUEST['id'];
        $month = Utils::MonthToCn($_REQUEST['month']);
        $year = substr($month,0,4);//年
        $month = substr($month,5,2);//月
        //添加zip压缩包
        $zip = new ZipArchive();//使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释
        $full_dir = Yii::app()->params['upload_tmp_path'] . '/wsh_'.$year.$month.'_'.$program_id.'/';
        $dir = 'wsh_'.$year.$month.'_'.$program_id.'/';
        $zip_path = 'wsh_'.$year.$month.'_'.$program_id.'.zip';
        if ($zip->open($zip_path, ZipArchive::CREATE)!==TRUE) {
            //如果是Linux系统，需要保证服务器开放了文件写权限
            exit("文件打开失败!");
        }
        $handler=opendir($full_dir); //打开当前文件夹由$path指定。
        while(($filename=readdir($handler))!==false){
            if($filename != "." && $filename != ".."){//文件夹文件名字为'.'和‘..'，不要对他们进行操作
                if(is_dir($full_dir."/".$filename)){// 如果读取的某个对象是文件夹，则递归
                    addFileToZip($full_dir."/".$filename, $zip);
                }else{ //将文件加入zip对象
                    $zip->addFile($full_dir."/".$filename);
                }
            }
        }
        @closedir($full_dir);
        //$zip->open($filename,ZipArchive::CREATE);//创建一个空的zip文件
        $zip->close();
        //下载zip
        $file = fopen($zip_path, "r"); // 打开文件
        header('Content-Encoding: none');
        header("Content-type: application/zip");
        header("Accept-Ranges: bytes");
        header("Accept-Length: " . filesize($zip_path));
        header('Content-Transfer-Encoding: binary');
        $name = basename($zip_path);
        header("Content-Disposition: attachment; filename=" . $name); //以真实文件名提供给浏览器下载
        header('Pragma: no-cache');
        header('Expires: 0');
        echo fread($file, filesize($zip_path));
        fclose($file);
        unlink($zip_path);
        //删除文件夹
        Utils::deldir($full_dir);
    }
}
