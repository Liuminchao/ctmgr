<?php

/**
 * 意外
 * @author LiuMinchao
 */
class AccidentController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';
    const STATUS_NORMAL = 0; //正常

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('comp_accident', 'contentHeader');
        $this->bigMenu = Yii::t('comp_accident', 'bigMenu');
    }

    /**
     * 上海隧道查询
     */
    public function actionShsdGrid() {
        $fields = func_get_args();

        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件

        if(count($fields) == 4 && $fields[0] != null ) {
            $args['program_id'] = $fields[0];
            $args['user_id'] = $fields[1];
            $args['deal_type'] = $fields[2];
            $args['type_id'] = $fields[3];
        }

        $t = $this->genShsdDataGrid();
        $this->saveUrl();
//        $args['status'] = Meeting::STATUS_FINISH;

//        if (Yii::app()->user->getState('role_id') == Operator::ROLE_MC) {//总包


//        } else if (Yii::app()->user->getState('role_id') == Operator::ROLE_SC) {//分包
//            $args['program_id'] = ProgramContractor::getProgramId();
//        }
        $app_id = 'ACCI';
        $args['contractor_id'] = Yii::app()->user->contractor_id;
        $rs = Workflow::contractorflowList(self::STATUS_NORMAL,$args['contractor_id'],$app_id);
        $list = AccidentBasic::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_shsdlist', array('t' => $t, 'rows' => $list['rows'],'rs' =>$rs, 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genShsdDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=accidents/accident/shsdgrid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('comp_accident', 'seq'), '', 'center');
//        $t->set_header(Yii::t('comp_accident', 'program_name'), '', 'center');
        $t->set_header(Yii::t('comp_accident', 'company'), '', 'center');
        $t->set_header(Yii::t('comp_accident', 'title'), '', 'center');
//        $t->set_header(Yii::t('comp_accident','type'),'','');
        $t->set_header(Yii::t('comp_accident','type'),'','center');
        $t->set_header(Yii::t('comp_accident', 'record_time'), '', 'center');
        $t->set_header(Yii::t('comp_accident', 'status'), '', 'center');
        $t->set_header(Yii::t('common', 'action'), '15%', 'center');
        return $t;
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=accidents/accident/grid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('comp_accident', 'seq'), '', 'center');
        $t->set_header(Yii::t('comp_accident', 'program_name'), '', 'center');
        $t->set_header(Yii::t('comp_accident', 'company'), '', 'center');
        $t->set_header(Yii::t('comp_accident', 'title'), '', 'center');
//        $t->set_header(Yii::t('comp_accident','type'),'','');
        $t->set_header(Yii::t('comp_accident','accident_details'),'','center');
        $t->set_header(Yii::t('comp_accident', 'record_time'), '', 'center');
        $t->set_header(Yii::t('comp_accident', 'status'), '', 'center');
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
            $args['type_id'] = $fields[3];
        }

        $t = $this->genDataGrid();
        $this->saveUrl();
//        $args['status'] = Meeting::STATUS_FINISH;

//        if (Yii::app()->user->getState('role_id') == Operator::ROLE_MC) {//总包


//        } else if (Yii::app()->user->getState('role_id') == Operator::ROLE_SC) {//分包
//            $args['program_id'] = ProgramContractor::getProgramId();
//        }
        $app_id = 'ACCI';
        $args['contractor_id'] = Yii::app()->user->contractor_id;
        $rs = Workflow::contractorflowList(self::STATUS_NORMAL,$args['contractor_id'],$app_id);
        $list = AccidentBasic::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'],'rs' =>$rs, 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {
        $user_id = $_REQUEST['user_id'];
        $program_id = $_REQUEST['program_id'];
        $deal_type = $_REQUEST['deal_type'];
        $type_id = $_REQUEST['type_id'];
        $this->smallHeader = Yii::t('comp_accident', 'smallHeader List');
        $pro_model = Program::model()->findByPk($program_id);
        $pro_params = $pro_model->params;//项目参数
        if($pro_params != '0') {
            $pro_params = json_decode($pro_params, true);
            //判断是否是迁移的
            if (array_key_exists('acci_mode', $pro_params)) {
                $params['type'] = $pro_params['acci_mode'];
            } else {
                $params['type'] = 'A';
            }
        }else{
            $params['type'] = 'A';
        }
        if($params['type'] == 'A'){
            $this->render('list',array('user_id'=> $user_id,'program_id'=> $program_id,'deal_type'=>$deal_type,'type_id'=>$type_id));
        }else{
            $this->render('shsdlist',array('user_id'=> $user_id,'program_id'=> $program_id,'deal_type'=>$deal_type,'type_id'=>$type_id));
        }

    }
    /*
     * 下载PDF（新）
     */
    public static function actionDownloadPdf() {

        $id = $_REQUEST['apply_id'];
        $app_id = $_REQUEST['app_id'];
        $params['id'] = $id;
        $accident_list = AccidentBasic::model()->find('apply_id=:apply_id',array(':apply_id'=>$id));
        $title = $accident_list->title;

        //报告定制化
        $program_id = $accident_list->root_proid;
        $pro_model = Program::model()->findByPk($program_id);
        $pro_params = $pro_model->params;//项目参数
        if($pro_params != '0') {
            $pro_params = json_decode($pro_params, true);
            //判断是否是迁移的
            if (array_key_exists('acci_report', $pro_params)) {
                $params['type'] = $pro_params['acci_report'];
            } else {
                $params['type'] = 'A';
            }
        }else{
            $params['type'] = 'A';
        }
        $filepath = DownloadPdf::transferDownload($params,$app_id);
        Utils::Download($filepath, $title, 'pdf');
    }
    /**
     * 下载PDF
     */
    public static function actionDownload() {

        $id = $_REQUEST['apply_id'];
        $app_id = $_REQUEST['app_id'];
        $accident_list = AccidentBasic::model()->find('apply_id=:apply_id',array(':apply_id'=>$id));
        $accident_staff = AccidentStaff::getStaffList($id);//意外人员
        $accident_device = AccidentDevice::getDeviceList($id);//意外设备
        $accident_confession = AccidentConfession::getConfessionList($id);//意外口供
        $accident_sick = AccidentSickLeave::getSickList($id);//请假单
        $company_list = Contractor::compAllList();//承包商公司列表
        $program_list =  Program::programAllList();//获取承包商所有项目
        $programdetail_list = Program::getProgramDetail($accident_list->root_proid);//根据项目id得到总包商和根节点项目
        $lang = "_en";

        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($accident_list->record_time,0,4);//年
        $month = substr($accident_list->record_time,5,2);//月
//        if($accident_list->save_path){
//            $file_path = $accident_list->save_path;
//            $filepath = '/opt/www-nginx/web'.$file_path;
//        }else{
            $filepath = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/incident'.'/ACCIDENT' . $id . '.pdf';
            $full_dir = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/incident';
            if(!file_exists($full_dir))
            {
                umask(0000);
                @mkdir($full_dir, 0777, true);
            }
            AccidentBasic::updatepath($id,$filepath);
//        }
//        $filepath = Yii::app()->params['upload_file_path'] .'/tbm/'.$meeting->add_conid.'/TBM' . $id . '.pdf';
//        $filepath = '/opt/www-nginx/web/ctmgr/webuploads'.'/tbm/'.$meeting->add_conid.'/TBM' . $id . '.pdf';
//        $filepath = '/opt/www-nginx/web/ctmgr/webuploads'.'/tbm/'.$meeting->add_conid.'/TBM' . $id . '.pdf';
//        $full_dir = Yii::app()->params['upload_file_path'] .'/tbm/'.$meeting->add_conid;
//        if($full_dir == ''){
//            return false;
//        }

        $title = $accident_list->title;

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
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);
        //$pdf->SetKeywords('PDF, LICEN');
        // 设置页眉和页脚信息
        $pdf->SetHeaderData('', 0, 'Incident No.(意外编号): ' . $accident_list->apply_id, $title, array(0, 64, 255), array(0, 64, 128));
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        // 设置页眉和页脚字体

//        if (Yii::app()->language == 'zh_CN') {
//            $pdf->setHeaderFont(Array('stsongstdlight', '', '10')); //中文
//        } else if (Yii::app()->language == 'en_US') {
        $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //英文OR中文
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
//            $pdf->SetFont('droidsansfallback', '', 14, '', true); //中文
//        } else if (Yii::app()->language == 'en_US') {
        $pdf->SetFont('droidsansfallback', '', 9, '', true); //英文OR中文
//        }

        $pdf->AddPage();

        //标题(许可证类型+项目)
        $title_html = "<h2 align=\"center\">Project (项目) : {$program_list[$accident_list->root_proid]}</h2><br/>";

        $apply_user =  Staff::model()->findAllByPk($accident_list->apply_user_id);//申请人
        $roleList = Role::roleallList();//岗位列表
        $apply_role = $apply_user[0]['role_id'];//发起人角色
        $contractor_id = $apply_user[0]['contractor_id'];//发起人公司
        $user_list = Staff::userInfo();//员工信息
        $status_css = CheckApplyDetail::statusTxt();//执行类型
        //发起人详情
        $apply_info_html = "<h4 align=\"center\">Initiator Details (发起人详情)</h4><table border=\"1\">";
        $apply_info_html .="<tr><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Name (姓名)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Designation (职位)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">ID Number (身份证号码)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Company (公司)</td></tr>";
        $apply_info_html .="<tr><td height=\"50px\" align=\"center\">&nbsp;{$apply_user[0]['user_name']}</td><td align=\"center\">&nbsp;{$roleList[$apply_role]}</td><td align=\"center\">&nbsp;{$apply_user[0]['work_no']}</td><td  align=\"center\">&nbsp;{$company_list[$contractor_id]}</td></tr>";
        $apply_info_html .="</table>";

        //意外详情
        $work_content_html = "<h4 align=\"center\">Incident Details (意外详情)</h4><table border=\"1\">";
        $work_content_html .="<tr><td width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Incident Process (意外过程)</td><td width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Incident Details (意外详情)</td><td width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Analysis Summary (分析总结)</td></tr>";
        $work_content_html .="<tr><td height=\"140px\">{$accident_list->acci_process}</td><td >{$accident_list->acci_details}</td><td align=\"center\">{$accident_list->description}</td></tr>";
        $work_content_html .="<tr><td width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Incident Time (意外时间)</td><td width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Incident Location (意外地点)</td><td width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Recording Time (记录时间)</td></tr>";
        $work_content_html .="<tr><td height=\"70px\">{$accident_list->acci_time}</td><td >{$accident_list->acci_location}</td><td align=\"center\">{$accident_list->record_time}</td></tr></table>";

        //意外照片
        $pic_html = '<h4 align="center">The scene photos(现场照片)</h4><table border="1">
                <tr><td width ="100%" height="103px"></td></tr>';
        $pic = $accident_list->acci_pic;
        if($pic != '') {
            $pic = explode('|', $pic);
//            var_dump($pic);
//            exit;
            $info_x = 40;
            $info_y = 146;
            foreach ($pic as $key => $content) {
//                $pic = 'C:\Users\minchao\Desktop\5.png';
//                $content = 'http://k.sinaimg.cn/n/sports/transform/20170715/Da8Z-fyiavtv7477193.jpg/w140h95z1l0t0q10009b.jpg';
                if ($content != '-1'){
                    $pdf->Image($content, $info_x, $info_y, 30, 28, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                    $info_x += 50;
                }
            }
        }
        $pic_html .= '</table>';


        //发生意外人员
        $worker_html = '<h4 align="center">Incident Participants(s) (意外成员)</h4><table border="1">
                <tr><td  width="10%" bgcolor="#d9d9d9" align="center">&nbsp;S/N<br>(序号)</td><td  width="20%" bgcolor="#d9d9d9" align="center">&nbsp;ID Number<br>(身份证号码)</td><td  width="35%" bgcolor="#d9d9d9" align="center">&nbsp;Company<br>(公司)</td><td width="15%" bgcolor="#d9d9d9" align="center">&nbsp;Name<br>(姓名)</td><td width="20%" bgcolor="#d9d9d9" align="center">&nbsp;Designation<br>(职务)</td></tr>';

        if (!empty($accident_staff)){
            $i = 1;
            foreach ($accident_staff as $cnt => $r) {
                $apply_user =  Staff::model()->findAllByPk($r['user_id']);
                $worker_html .= '<tr><td align="center">&nbsp;' . $i . '</td><td align="center">&nbsp;' . $apply_user[0]['work_no'] . '</td><td align="center">&nbsp;'.$company_list[$apply_user[0]['contractor_id']].'</td><td align="center">&nbsp;' . $r['user_name'] . '</td><td align="center">&nbsp;'.$roleList[$apply_user[0]['role_id']].'</td></tr>';
                $i++;
            }
        }
        $worker_html .= '</table>';

        //发生意外设备
        $devices_html = '<h4 align="center">Incident Equipment(s) (意外设备)</h4><table border="1">
                <tr><td  width="5%" bgcolor="#d9d9d9" align="center">&nbsp;S/N<br>(序号)</td><td nowrap="nowrap" bgcolor="#d9d9d9" width="23%" align="center">&nbsp;Registration No.<br>(设备编码)</td><td nowrap="nowrap" bgcolor="#d9d9d9" width="22%" align="center">&nbsp;Equipment Name<br>(设备名称)</td><td nowrap="nowrap" bgcolor="#d9d9d9" width="25%" align="center">&nbsp;License Start Date<br>(许可证起始日期)</td><td nowrap="nowrap" bgcolor="#d9d9d9" width="25%" align="center">&nbsp;License End Date<br>(许可证截至日期)</td></tr>';
        if (!empty($accident_device)){
            $j =1;
            foreach ($accident_device as $cnt => $list) {
//                var_dump($device_list);
//                exit;
                $devices_html .= '<tr><td align="center">&nbsp;' . $j . '</td><td align="center">&nbsp;' . $id . '</td><td align="center">&nbsp;' . $list['device_name'] . '</td><td align="center">&nbsp;' . Utils::DateToEn($list['permit_startdate']) . '</td><td align="center">&nbsp;' . Utils::DateToEn($list['permit_enddate']) . '</td></tr>';
                $j++;
            }
        }
        $devices_html .= '</table>';

        //意外口供
        $confession_html = '<h4 align="center">Incident Confession(s) (意外口供)</h4><table border="1">
                <tr><td  width="30%" bgcolor="#d9d9d9" align="center">&nbsp;Name<br>(姓名)</td><td  width="20%" bgcolor="#d9d9d9" align="center">&nbsp;Designation<br>(职务)</td><td width="50%" bgcolor="#d9d9d9" align="center">&nbsp;Confession<br>(口供)</td></tr>';

        if (!empty($accident_confession)){
            $i = 1;
            foreach ($accident_confession as $cnt => $r) {
                $confession_html .= '<tr><td align="center" height="140px">&nbsp;' . $r['user_name'] . '</td><td align="center">&nbsp;'.$r['role_name'].'</td><td align="center">&nbsp;' . $r['confession'] . '</td></tr>';
                $i++;
            }
        }
        $confession_html .= '</table>';
        $progress_list = AccidentBasicDetail::progressList( $app_id,$id);//审批步骤详情
        $j = 1;
        $y = 1;
        $info_xx = 170;
        $info_yy = 194;
        if (!empty($progress_list))
            $num = count($progress_list);
//        if($num < 5) {
            //审批流程
            //                $pic = 'C:\Users\minchao\Desktop\5.png';
            $audit_html_1 = '<h4 align="center">Approval Process (审批流程)</h4><table border="1">
                <tr><td  nowrap="nowrap" width="20%" bgcolor="#d9d9d9" align="center">&nbsp;Person in Charge<br>(执行人)</td><td  nowrap="nowrap" width="20%" bgcolor="#d9d9d9" align="center"> Status<br>(状态)</td><td  nowrap="nowrap" width="20%" bgcolor="#d9d9d9" align="center"> Date & Time<br>(日期&时间)</td><td  nowrap="nowrap" width="20%" bgcolor="#d9d9d9" align="center">&nbsp;Remark<br>(备注)</td><td  nowrap="nowrap" width="20%" bgcolor="#d9d9d9" align="center">&nbsp;Electronic Signature<br>(电子签名)</td></tr>';
            $count = count($progress_list);
            //流程少于5步
            if($count <= 4) {
                foreach ($progress_list as $key => $row) {
                    $content_list = $user_list[$row['deal_user_id']];
                    $content = $content_list[0]['signature_path'];
//                    $content = 'http://k.sinaimg.cn/n/sports/transform/20170715/Da8Z-fyiavtv7477193.jpg/w140h95z1l0t0q10009b.jpg';
                    //$p = '/opt/www-nginx/appupload/4/0000002314_TBMMEETINGPHOTO.jpg';
                    if ($content != '') {
                        $pdf->Image($content, $info_xx, $info_yy, 21, 10, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                    }
                    $audit_html_1.= '<tr><td height="55px" align="center">&nbsp;' . $row['user_name'] . '</td><td align="center">&nbsp;' . $status_css[$row['deal_type']] . '</td><td align="center">&nbsp;' . Utils::DateToEn($row['deal_time']) . '</td><td align="center">&nbsp;' . $row['remark'] . '</td><td align="center"></td></tr>';
                    $j++;
                    $info_yy += 15;
                }
                $audit_html_1 .= '</table>';

                $html_1 = $title_html . $apply_info_html . $work_content_html . $pic_html . $audit_html_1;
                $pdf->writeHTML($html_1, true, false, true, false, '');
                $pdf->AddPage();
                $html_2 = $worker_html . $devices_html .$confession_html;
                $pdf->writeHTML($html_2, true, false, true, false, '');
            }else{
                foreach ($progress_list as $key => $row) {
                    if ($key < 5) {
                        $content_list = $user_list[$row['deal_user_id']];
                        $content = $content_list[0]['signature_path'];
//                        $content = 'http://k.sinaimg.cn/n/sports/transform/20170715/Da8Z-fyiavtv7477193.jpg/w140h95z1l0t0q10009b.jpg';
                        //$p = '/opt/www-nginx/appupload/4/0000002314_TBMMEETINGPHOTO.jpg';
                        if ($content != '') {
                            $pdf->Image($content, $info_xx, $info_yy, 21, 10, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                        }
                        $audit_html_1 .= '<tr><td height="55px" align="center">&nbsp;' . $row['user_name'] . '</td><td align="center">&nbsp;' . $status_css[$row['deal_type']] . '</td><td align="center">&nbsp;' . Utils::DateToEn($row['deal_time']) . '</td><td align="center">&nbsp;' . $row['remark'] . '</td><td align="center"></td></tr>';
                        $j++;
                        $info_yy += 15;
                    }
                }
                $audit_html_1 .= '</table>';
                $html_1 = $title_html . $apply_info_html . $work_content_html . $pic_html . $audit_html_1;
                $pdf->writeHTML($html_1, true, false, true, false, '');
                $pdf->AddPage();
                $audit_html_2 = "<table border='1'>";
                foreach ($progress_list as $key => $row) {
                    if ($key > 4) {
                        $content_list = $user_list[$row['deal_user_id']];
                        $content = $content_list[0]['signature_path'];
//                        $content = 'http://k.sinaimg.cn/n/sports/transform/20170715/Da8Z-fyiavtv7477193.jpg/w140h95z1l0t0q10009b.jpg';
                        //$p = '/opt/www-nginx/appupload/4/0000002314_TBMMEETINGPHOTO.jpg';
                        if ($content != '') {
                            $pdf->Image($content, $info_xx, $info_yy, 21, 10, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                        }
                        $audit_html_2 .= '<tr><td height="55px" align="center">&nbsp;' . $row['user_name'] . '</td><td align="center">&nbsp;' . $status_css[$row['deal_type']] . '</td><td align="center">&nbsp;' . Utils::DateToEn($row['deal_time']) . '</td><td align="center">&nbsp;' . $row['remark'] . '</td><td align="center"></td></tr>';
                        $j++;
                        $info_yy += 15;
                    }
                }
                $html_2 = $audit_html_2 .$worker_html . $devices_html .$confession_html;
                $pdf->writeHTML($html_2, true, false, true, false, '');
            }

            $pdf->AddPage();
            //请假单
            $sick_html = '<h4 align="center">Leave Record (请假记录)</h4><table border="1">
                <tr><td  nowrap="nowrap" width="10%" bgcolor="#d9d9d9" align="center">&nbsp;Name<br>(姓名)</td><td  nowrap="nowrap" width="10%" bgcolor="#d9d9d9" align="center"> Start Date<br>(开始日期)</td><td  nowrap="nowrap" width="10%" bgcolor="#d9d9d9" align="center"> End Date<br>(结束日期)</td><td  nowrap="nowrap" width="70%" bgcolor="#d9d9d9" align="center">&nbsp;Pic<br>(图片)</td></tr>';
            foreach ($accident_sick as $key => $row) {

                //$p = '/opt/www-nginx/appupload/4/0000002314_TBMMEETINGPHOTO.jpg';
                if ($row['pic'] != '') {
                    $sick_pic = explode('|', $row['pic']);
                    foreach ($sick_pic as $key => $content) {
//                        $content = 'http://k.sinaimg.cn/n/sports/transform/20170715/Da8Z-fyiavtv7477193.jpg/w140h95z1l0t0q10009b.jpg';
                        $pdf->Image($content, 70, 45, 21, 10, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                        $info_xx = $info_xx+20;
                    }
                }
                $sick_html .= '<tr><td height="55px" align="center">&nbsp;' . $row['user_name'] . '</td><td align="center">&nbsp;' . $row['start_time'] . '</td><td align="center">&nbsp;' . $row['end_time'] . '</td><td align="center"></td></tr>';
                $j++;
                $info_yy += 15;

            }
            $sick_html .= '</table>';

            $html_3 = $sick_html;

            $pdf->writeHTML($html_3, true, false, true, false, '');
//        }
//        else{
//            $audit_html = '<h4 align="center">Approval Process (审批流程)</h4><table border="1">
//                <tr><td  nowrap="nowrap" width="20%" bgcolor="#d9d9d9" align="center">&nbsp;Person in Charge<br>(执行人)</td><td  nowrap="nowrap" width="20%" bgcolor="#d9d9d9" align="center"> Status<br>(状态)</td><td  nowrap="nowrap" width="20%" bgcolor="#d9d9d9" align="center"> Date & Time<br>(日期&时间)</td><td  nowrap="nowrap" width="20%" bgcolor="#d9d9d9" align="center">&nbsp;Remark<br>(备注)</td><td  nowrap="nowrap" width="20%" bgcolor="#d9d9d9" align="center">&nbsp;Electronic Signature<br>(电子签名)</td></tr>';
//            foreach ($progress_list as $key => $row) {
//                if($row['step'] < 5) {
//                    $content_list = $user_list[$row['deal_user_id']];
//                    $content = $content_list[0]['signature_path'];
//                    //$content = '/opt/www-nginx/appupload/4/0000002314_TBMMEETINGPHOTO.jpg';
//                    if ($content != '') {
//                        $pdf->Image($content, $info_xx, $info_yy, 21, 10, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
//                    }
//                    $audit_html .= '<tr><td height="55px" align="center">&nbsp;' . $row['user_name'] . '</td><td align="center">&nbsp;' . $status_css[$row['deal_type']] . '</td><td align="center">&nbsp;' . Utils::DateToEn($row['deal_time']) . '</td><td align="center">&nbsp;' . $row['remark'] . '</td><td align="center"></td></tr>';
//                    $j++;
//                    $info_yy += 15;
//                }
//            }
//            $audit_html .= '</table>';
//            $html = $title_html . $apply_info_html . $work_content_html . $work_date_html . $pic_html  .$audit_html ;
//            $pdf->writeHTML($html, true, false, true, false, '');
//            $pdf->AddPage();
//            $audit_html2 = "<table border=\"1\">";
//            foreach ($progress_list as $key => $row) {
//                if($row['step'] >= 5) {
//                    $content_list = $user_list[$row['deal_user_id']];
//                    $content = $content_list[0]['signature_path'];
//                    //$p = '/opt/www-nginx/appupload/4/0000002314_TBMMEETINGPHOTO.jpg';
//                    if ($content != '') {
//                        $pdf->Image($content, $info_xx, 30, 21, 10, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
//                    }
//                    $audit_html2 .= '<tr><td height="55px" align="center">&nbsp;' . $row['user_name'] . '</td><td align="center">&nbsp;' . $status_css[$row['deal_type']] . '</td><td align="center">&nbsp;' . Utils::DateToEn($row['deal_time']) . '</td><td align="center">&nbsp;' . $row['remark'] . '</td><td align="center"></td></tr>';
//                    $j++;
//                    $info_yy += 15;
//                }
//            }
//            //参与人员
//            $worker_html = '<h4 align="center">Participants(s) (参与成员)</h4><table border="1">
//                <tr><td  width="10%" bgcolor="#d9d9d9" align="center">&nbsp;S/N<br>(序号)</td><td  width="20%" bgcolor="#d9d9d9" align="center">&nbsp;ID Number<br>(身份证号码)</td><td  width="35%" bgcolor="#d9d9d9" align="center">&nbsp;Company<br>(公司)</td><td width="15%" bgcolor="#d9d9d9" align="center">&nbsp;Name<br>(姓名)</td><td width="20%" bgcolor="#d9d9d9" align="center">&nbsp;Designation<br>(职务)</td></tr>';
//
//            if (!empty($members)){
//                $i = 1;
//                foreach ($members as $user_id => $r) {
//                    $worker_html .= '<tr><td align="center">&nbsp;' . $i . '</td><td align="center">&nbsp;' . $r['work_no'] . '</td><td align="center">&nbsp;'.$company_list[$r['contractor_id']].'</td><td align="center">&nbsp;' . $r['worker_name'] . '</td><td align="center">&nbsp;'.$roleList[$r['role_id']].'</td></tr>';
//                    $i++;
//                }
//            }
//            $worker_html .= '</table>';
//
//            $html2 = $audit_html2 . $worker_html;
//            $pdf->writeHTML($html2, true, false, true, false, '');
//        }


        //输出PDF
//        $pdf->Output($filepath, 'I');

        $pdf->Output($filepath, 'F'); //保存到指定目录
        Utils::Download($filepath, $title, 'pdf'); //下载pdf
//============================================================+
// END OF FILE
//============================================================+
    }

    /**
     * 意外详情
     */
    public function actionPreview() {
        $flow_id = $_REQUEST['flow_id'];
        $apply_id = $_REQUEST['apply_id'];
        $app_id = $_REQUEST['app_id'];
//        var_dump($apply_id);
//        var_dump($flow_id);
//        exit;
        $this->smallHeader = '意外详情';
        $app_id = 'ACCI';
        $accident_list = AccidentBasic::model()->find('apply_id=:apply_id',array(':apply_id'=>$apply_id));
//        var_dump($accident_list);
//        exit;
        $this->render('preview', array('accident_list' => $accident_list));
    }

    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['accident/list'] = str_replace("r=accidents/accident/grid", "r=accidents/accident/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

    /**
     * 公司统计图表
     */
    public function actionCompanyChart() {
        $program_id = $_REQUEST['program_id'];
        $platform = $_REQUEST['platform'];
        $this->smallHeader = Yii::t('dboard', 'Company Statistical Charts');
        if(!is_null($platform)) {
            $this->layout = '//layouts/main_2';
            $this->render('statistical_company_dash', array('program_id' => $program_id,'platform'=>$platform));
        }else{
            $this->render('statistical_company',array('program_id'=>$program_id));
        }
    }
    /**
     *查询违规次数（公司）
     */
    public function actionCntByCompany() {
        $args['program_id'] = $_REQUEST['id'];
        $args['date'] = $_REQUEST['date'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $args['module_type'] = '1';
        $r = AccidentBasic::CompanyCntList($args);
        print_r(json_encode($r));
    }
}
