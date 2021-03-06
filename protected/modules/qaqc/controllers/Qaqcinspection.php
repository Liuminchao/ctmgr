<?php

/**
 * QA/QC安全检查
 * @author LiuMinChao
 */
class QaqcinspectionController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';
    const STATUS_NORMAL = 0; //正常

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('comp_qaqc', 'contentHeader');
//        $this->contentHeader = '';
        $this->bigMenu = Yii::t('comp_qaqc', 'bigMenu');
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=wsh/wshinspection/grid';
        $t->updateDom = 'datagrid';
//        $t->set_header(Yii::t('comp_qaqc', 'check_id'), '', '');
        $t->set_header(Yii::t('comp_qaqc', 'sn'), '', '');
        $t->set_header(Yii::t('comp_qaqc', 'root_proname'), '', '');
        $t->set_header(Yii::t('comp_qaqc', 'title'), '', '');
        $t->set_header(Yii::t('comp_qaqc', 'safety_type'), '', '');
        $t->set_header(Yii::t('comp_qaqc', 'Initiator'), '', '');
        $t->set_header(Yii::t('comp_qaqc', 'Person In Charge'), '', '');
        $t->set_header(Yii::t('comp_qaqc', 'Person Responsible'), '', '');
        $t->set_header(Yii::t('comp_qaqc', 'company'), '', '');
        $t->set_header(Yii::t('comp_qaqc', 'safety_level'), '', '');
//        $t->set_header(Yii::t('comp_safety', 'violation_record'), '', '');
        $t->set_header(Yii::t('comp_qaqc', 'apply_time'), '', '');
        $t->set_header(Yii::t('comp_qaqc', 'stipulation_time'), '', '');
        $t->set_header(Yii::t('comp_qaqc', 'status'), '', '');
        $t->set_header(Yii::t('common', 'action'), '15%', '');
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

//        var_dump($start_time);
//        exit;
        if(count($fields) == 4 && $fields[0] != null ) {
            $args['program_id'] = $fields[0];
            $args['user_id'] = $fields[1];
            $args['deal_type'] = $fields[2];
            $args['safety_level'] = $fields[3];
        }
        $t = $this->genDataGrid();
        $app_id = 'QA/QC';
        $this->saveUrl();
//        $args['status'] = ApplyBasic::STATUS_FINISH;
        $args['contractor_id'] = Yii::app()->user->contractor_id;

        $list = SafetyCheck::queryList($page, $this->pageSize, $args);
//        var_dump($list['rows']);
        $this->renderPartial('_list', array('t' => $t, 'app_id'=>$app_id,'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {
        $user_id = $_REQUEST['user_id'];
        $program_id = $_REQUEST['program_id'];
        $deal_type = $_REQUEST['deal_type'];
        $safety_level = $_REQUEST['safety_level'];
        $this->smallHeader = Yii::t('comp_qaqc', 'smallHeader List');
        $this->render('list',array('user_id'=> $user_id,'program_id'=> $program_id,'deal_type'=>$deal_type,'safety_level'=>$safety_level));
    }
    /**
     * 导出安全报告界面
     */
    public function actionExport() {
//        $model = new SafetyCheck('create');
        $contractor_id = $_REQUEST['contractor_id'];
//        $args = $_GET['q']; //查询条件
//        $r = SafetyCheckDetail::detailAllList($args);
//        var_dump($args);
//        var_dump($contractor_id);
//        exit;
        $this->renderPartial('export', array('contractor_id' => $contractor_id));
    }
    /**
     * 导出安全报告
     */
    public function actionDownloadPdf() {
        $check_id = $_REQUEST['check_id'];
        $params['check_id'] = $check_id;
        $app_id = 'WSH';
        $check_list = SafetyCheck::detailList($check_id);//安全检查单
        $title = $check_list[0]['title'];//标题
//        if($check_list[0]['save_path']){
//            $file_path = $check_list[0]['save_path'];
//            $filepath = '/opt/www-nginx/web'.$file_path;
//        }else{
//            $filepath = DownloadPdf::transferDownload($params,$app_id);
//        }
        $filepath = DownloadPdf::transferDownload($params,$app_id);
        Utils::Download($filepath, $title, 'pdf');
    }

    /**
     * 下载PDF
     */
    public static function actionDownload() {

        $check_id = $_REQUEST['check_id'];
//        $a = SafetyCheckDetail::detailAllList();
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
//        var_dump($violations_user);
//        exit;
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
            $filepath = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/QA'.'/QA' . $check_id . '.pdf';
            $full_dir = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/QA'.'/QA';
            if(!file_exists($full_dir))
            {
                umask(0000);
                @mkdir($full_dir, 0777, true);
            }
            SafetyCheck::updatepath($check_id,$filepath);
//        }

//        if (file_exists($filepath)) {
//            $show_name = $title;
//            $extend = 'pdf';
//            Utils::Download($filepath, $show_name, $extend);
//            return;
//        }
        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
        require_once($tcpdfPath);
//        Yii::import('application.extensions.tcpdf.TCPDF');
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
//        var_dump($pdf);
//        exit;
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

//        if (Yii::app()->language == 'zh_CN') {
//            $pdf->setHeaderFont(Array('stsongstdlight', '', '10')); //中文
//        } else if (Yii::app()->language == 'en_US') {
        $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //英文
//        }

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
//        if (Yii::app()->language == 'zh_CN') {
//            $pdf->SetFont('stsongstdlight', '', 14, '', true); //中文
//        } else if (Yii::app()->language == 'en_US') {
        $pdf->SetFont('droidsansfallback', '', 9, '', true); //英文
//        }

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
        //判断电子签名是否存在 $add_operator->signature_path
        $content_list = $user_list[$apply_user_id];
        $content = $content_list[0]['signature_path'];
        if($content){
            $pdf->Image($content, 150, 76, 20, 9, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
        }
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

//        $check_html = '
//            <h4 align="center">WSH Inspection(安全检查)</h4><table border="1"><tr><td width="35%" nowrap="nowrap">&nbsp;Title(标题)</td><td width="65%" nowrap="nowrap">&nbsp;'.$title.'</td></tr>
//                <tr><td width="35%" nowrap="nowrap">&nbsp;Applicant Company(申请公司)</td><td width="65%" nowrap="nowrap">&nbsp;'.$company_list[$contractor_id].'</td></tr>
//                <tr><td width="35%" nowrap="nowrap">&nbsp;The contractor project(总包项目)</td><td width="65%" nowrap="nowrap">&nbsp;'.$root_proname.'</td></tr>
//                <tr><td width="35%" nowrap="nowrap">&nbsp;Block(一级区域)</td><td width="65%" nowrap="nowrap">&nbsp;'.$block.'</td></tr>
//                <tr><td width="35%" nowrap="nowrap">&nbsp;Secondary Region(二级区域)</td><td width="65%" nowrap="nowrap">&nbsp;'.$secondary_region.'</td></tr>
//                <tr><td width="35%" nowrap="nowrap">&nbsp;Safety Level(安全等级)</td><td width="65%" nowrap="nowrap">&nbsp;'.$check_list[0]['safety_level'].'</td></tr>
//                <tr><td width="35%" nowrap="nowrap">&nbsp;Safety Level Description(安全等级描述)</td><td width="65%" nowrap="nowrap">&nbsp;'.$description.'</td></tr>
//                <tr><td width="35%" nowrap="nowrap">&nbsp;Applicant(申请人)</td><td width="65%" nowrap="nowrap">&nbsp;'.$apply_user[0]['user_name'].'</td></tr>
//                <tr><td width="35%" nowrap="nowrap">&nbsp;Head(负责人)</td><td width="65%" nowrap="nowrap">&nbsp;'.$person_in_charge[0]['user_name'].'</td></tr>
//                <tr><td width="35%" nowrap="nowrap">&nbsp;Time of application(申请时间)</td><td width="65%" nowrap="nowrap">&nbsp;'.$apply_time.'</td></tr>
//                <tr><td width="35%" nowrap="nowrap">&nbsp;Closing time(关闭时间)</td><td width="65%" nowrap="nowrap">&nbsp;'.$close_time.'</td></tr>
//                <tr><td width="35%" height="43px" nowrap="nowrap">&nbsp;Applicant Electronic Signature(申请人电子签名)</td><td width="65%" nowrap="nowrap">&nbsp;</td></tr>
//                <tr><td width="35%" nowrap="nowrap">&nbsp;Violations personnel(违规人员)</td><td width="65%" nowrap="nowrap">&nbsp;'.$violations_user.'</td></tr></table>';
////        $content = $user_list[$apply_user_id]['signature_path'];
//
////        $pic = 'C:\Users\minchao\Desktop\5.png';
//        if($content != '') {
//            $pdf->Image($content, 100, 77, 32, 12, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
//        }


        $info_x = 44;//X方向距离
        $info_y_1 = 227;//第一页Y方向距离
        $info_y_2 = 44;//第二页Y方向距离
        $info_y_3 = 30;//第三页Y方向距离
        $cnt_1 = 0;
        $cnt_2 = 0;
//        $pic = 'C:\Users\minchao\Desktop\5.png';
//        var_dump($detail_list);
//        exit;
//        if (!empty($detail_list)){
//            foreach ($detail_list as $cnt => $list) {
////                var_dump($device_list);
////                exit;
//                if($list['step'] <= 2){
//                    if($list['pic']) {
//                        $pdf->Image($list['pic'], $info_x, $info_y_1, 26, 18, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
//                    }
//                    $check_detail_html_1 .= '<tr ><td height="80px">&nbsp;' . $list['step'] . '</td><td >&nbsp;</td><td >&nbsp;' . $list['description'] . '</td><td></td><td >&nbsp;' . Utils::DateToEn($list['record_time']) . '</td></tr>';
//                    $info_y_1 +=22;
//
//                }
//            }
//        }
//        $check_detail_html_1 .= '</table>';
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
//                var_dump($device_list);
//                exit;
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
//                var_dump($device_list);
//                exit;
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
//        $pdf->Output($filepath, 'I');

//        $pdf->Output($filepath, 'D');
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
//        var_dump($detail_list);
//        exit;
        $this->renderPartial('preview',array('check_list' => $check_list,'detail_list'=>$detail_list));
    }

}
