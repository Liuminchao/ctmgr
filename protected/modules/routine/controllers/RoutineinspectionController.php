<?php

/**
 * 例行检查
 * @author LiuMinChao
 */
class RoutineinspectionController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';
    const STATUS_NORMAL = 0; //正常

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('comp_routine', 'contentHeader');
        $this->bigMenu = Yii::t('comp_routine', 'bigMenu');
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=routine/routineinspection/grid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('comp_routine', 'check_id'), '', 'center');
        $t->set_header(Yii::t('comp_routine', 'root_proname'), '', 'center');
        $t->set_header(Yii::t('comp_routine', 'check_type'), '', 'center');
        $t->set_header(Yii::t('comp_routine', 'applicant_name'), '', 'center');
        $t->set_header(Yii::t('comp_routine', 'company'), '', 'center');
        //$t->set_header(Yii::t('comp_routine', 'check_kind'), '', '');
        //$t->set_header(Yii::t('comp_routine', 'violation_record'), '', '');
        //$t->set_header(Yii::t('comp_routine', 'apply_date'), '', '');
        $t->set_header(Yii::t('comp_routine', 'date_of_application'), '', 'center');
        $t->set_header(Yii::t('comp_routine', 'status'), '', 'center');
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

        $this->saveUrl();
        //$args['status'] = ApplyBasic::STATUS_FINISH;
        $args['contractor_id'] = Yii::app()->user->contractor_id;
        if($fields[0]){
            $args['program_id'] = $fields[0];
        }
        $t = $this->genDataGrid();
        $list = RoutineCheck::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genContractorGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=routine/routineinspection/contractorgrid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('comp_routine', 'check_id'), '', '');
        //$t->set_header(Yii::t('comp_routine', 'root_proname'), '', '');
        $t->set_header(Yii::t('comp_routine', 'check_type'), '', '');
        //$t->set_header(Yii::t('comp_routine', 'violation_record'), '', '');
        //$t->set_header(Yii::t('comp_routine', 'apply_date'), '', '');
        $t->set_header(Yii::t('comp_routine', 'date_of_application'), '', '');
        $t->set_header(Yii::t('comp_routine', 'status'), '', '');
        $t->set_header(Yii::t('common', 'action'), '15%', '');
        return $t;
    }

    /**
     * 查询
     */
    public function actionContractorGrid() {
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件

        $t = $this->genContractorGrid();
        $this->saveUrl();
        //$args['status'] = ApplyBasic::STATUS_FINISH;
        $args['contractor_id'] = Yii::app()->user->contractor_id;

        $list = RoutineCheck::queryContractorList($page, $this->pageSize, $args);
        $this->renderPartial('contractor_list', array('t' => $t,'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {

        // $src_img = "/opt/www-nginx/web/filebase/data/face/146/0000000013_face.png";  //原图片完整路径和名称，带图片扩展名
        // $dst_img = "/opt/www-nginx/web/filebase/data/face/146/thumb_0000000013_face.png"; //生成的缩略图存放的完整路径和名称
        // /* 生成宽300px，高200px的缩略图，不进行裁切，空白部分将会使用背景色填充 */
        // $stat = Utils::img2thumb($src_img, $dst_img, $width = 100, $height = 100, $cut = 0, $proportion = 0);
        // if($stat){
        //     echo 'Resize Image Success!<br />';
        //     echo '<img src="'.$src_img.'" /><br />';
        //     echo '<img src="'.$dst_img.'" />';
        // }else{
        //     echo 'Resize Image Fail!';
        // }
        $program_id = $_REQUEST['program_id'];
        $this->smallHeader = Yii::t('comp_routine', 'smallHeader List');
        $this->render('list',array('program_id'=>$program_id));
    }
    /**
     * 导出安全报告界面
     */
    public function actionExport() {
        // $model = new SafetyCheck('create');
        $contractor_id = $_REQUEST['contractor_id'];
        // $args = $_GET['q']; //查询条件
        // $r = SafetyCheckDetail::detailAllList($args);
        $this->renderPartial('export', array('contractor_id' => $contractor_id));
    }
    /**
     * 下载PDF
     */
    public static function actionDownloadPdf() {
        $check_id = $_REQUEST['check_id'];
        $tag = $_REQUEST['tag'];
        $params['check_id'] = $check_id;
        $params['tag'] = $tag;
        $app_id = 'ROUTINE';
        $check_list = RoutineCheck::detailList($check_id);//例行检查单
        $title = $app_id.$check_id;//文件名
        // if($check_list[0]['save_path']){
        //     $file_path = $check_list[0]['save_path'];
        //     $filepath = '/opt/www-nginx/web'.$file_path;
        // }else{
        //     $filepath = DownloadPdf::transferDownload($params,$app_id);
        // }
        $filepath = DownloadPdf::transferDownload($params,$app_id);
        Utils::Download($filepath, $title, 'pdf');
    }
    /**
     * 下载PDF
     */
    public static function actionDownload() {

        $check_id = $_REQUEST['check_id'];
        //$a = SafetyCheckDetail::detailAllList();
        $check_list = RoutineCheck::detailList($check_id);//例行检查单
        $detail_list = RoutineCheckDetail::detailList($check_id);//例行检查单详情
        $check_type = RoutineCheckType::checkType();//检查类型列表
        $check_kind = RoutineCheckType::checkKind();//检查种类列表
        $level_list = SafetyLevel::levelText();//安全等级详情
        $record_list = ViolationRecord::recordList($check_id);//违规记录
        $company_list = Contractor::compAllList();//承包商公司列表
        $program_list =  Program::programAllList();//获取承包商所有项目
        $deal_list = SafetyCheckDetail::dealList();//处理类型列表
        $staff_list = Staff::userAllList();//所有人员列表
      
        $title = $check_list[0]['title'];//标题
        $contractor_id = $check_list[0]['contractor_id'];
        $contractor_name = $company_list[$contractor_id];//承包商名称
        $root_proname = $check_list[0]['root_proname'];//总包项目名称
        $person_in_charge_id = $detail_list[1]['deal_user_id'];//负责人ID
        $person_in_charge = Staff::model()->findAllByPk($person_in_charge_id);//负责人
        $apply_user_id = $check_list[0]['apply_user_id'];//申请人ID
        $apply_user =  Staff::model()->findAllByPk($apply_user_id);//申请人
        $apply_time = $check_list[0]['apply_time'];//申请时间
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
            $filepath = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/routine'.'/Routine' . $check_id . '.pdf';
            $full_dir = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/routine';
            if(!file_exists($full_dir))
            {
                umask(0000);
                @mkdir($full_dir, 0777, true);
            }
            RoutineCheck::updatepath($check_id,$filepath);
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
        $pdf->SetHeaderData('', 0,  '', 'Checklist No.(检查清单编号) : ' . $check_id, array(0, 64, 255), array(0, 64, 128));
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

        $operator_id = $apply_user[0]['user_id'];//申请人ID
        $add_operator = Staff::model()->findByPk($operator_id);//申请人信息
        $add_role = $add_operator->role_id;
        $roleList = Role::roleallList();//岗位列表
        $apply_first_time = Utils::DateToEn(substr($apply_time,0,10));
        $apply_second_time = substr($apply_time,11,18);
        //标题(许可证类型+项目)
        $title_html = "<h2 align=\"center\">Project (项目) : {$check_list[0]['root_proname']}</h2><br/>";
        //申请人资料
        $apply_info_html = "<h4 align=\"center\"> Applicant Details (申请人详情)</h4><table border=\"1\">";
        $apply_info_html .="<tr><td width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Name of Applicant (申请人姓名)</td><td width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Designation of Applicant (申请人职位)</td><td width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">ID No.of Applicant (申请人身份证号码)</td></tr>";
        $apply_info_html .="<tr><td height=\"50px\" align=\"center\">&nbsp;{$add_operator->user_name}</td><td align=\"center\">&nbsp;{$roleList[$add_role]}</td><td align=\"center\">&nbsp;{$add_operator->work_no}</td></tr>";
        $apply_info_html .="<tr><td width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Applicant Company (申请人公司)</td><td width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Date of Application (申请时间)</td><td width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Electronic Signature (电子签名)</td></tr>";
        $apply_info_html .="<tr><td height=\"50px\" align=\"center\">&nbsp;{$company_list[$apply_user[0]['contractor_id']]}</td><td align=\"center\">&nbsp;{$apply_first_time}  {$apply_second_time}</td><td align=\"center\">&nbsp;</td></tr>";
        $apply_info_html .="</table>";

        //工作内容
        $work_content_html = "<h4 align=\"center\">Inspection Details (检查详情)</h4><table border=\"1\" >";
        //$work_content_html .="<tr><td width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Title (标题)</td><td width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Device name (设备名称)</td></tr>";
        //$work_content_html .="<tr><td height=\"120px\" align=\"center\">{$title}</td><td align=\"center\">{$check_list[0]['device_name']}</td></tr>";
        $work_content_html .="<tr><td width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Checklist Type (清单类型)</td><td width=\"50%\"  nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Location (位置)</td></tr>";
        $work_content_html .="<tr><td height=\"20px\" align=\"center\">{$check_type[$check_list[0]['type_id']]}</td><td align=\"center\">{$check_kind[$check_list[0]['address']]}</td></tr>";
        $work_content_html .="<tr><td width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Received By (接收人)</td><td width=\"50%\"  nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Received Date (接收日期)</td></tr>";
        $work_content_html .="<tr><td height=\"20px\" align=\"center\">{$person_in_charge[0]['user_name']}</td><td align=\"center\">{$check_list[0]['receive_time']}</td></tr></table>";

        // $content = $user_list[$person_in_charge_id]['signature_path'];
        // $pic = 'C:\Users\minchao\Desktop\5.png';
        $content_list = $user_list[$person_in_charge_id];
        $content = $content_list[0]['signature_path'];
        if($content != '') {
            $pdf->Image($content, 140, 65, 32, 10, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
        }
        ////现场照片
        //$pic_html = '<h4 align="center">Site Photo(s) (现场照片)</h4><table border="1">
        //      <tr><td width ="100%" height="107px"></td></tr>';
        //      $pic = $check_list->pic;
        //      if($pic != '') {
        //      $pic = explode('|', $pic);
        //      $info_x = 40;
        //      $info_y = 71;
        //      foreach ($pic as $key => $content) {
        //              $content = $content;
        //              $pdf->Image($content, $info_x, $info_y, 30, 28, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
        //              $info_x += 50;
        //      }
        //}
        //$pic_html .= '</table>';

        $condition_html = '<h4 align="center">SAFETY CONDITIONS(安全规则)</h4><table border="1"><tr><td width="10%" nowrap="nowrap" align="center">&nbsp;S/N(序号)</td><td width="75%" nowrap="nowrap" align="center">&nbsp;SAFETY CONDITIONS(安全规则)</td><td width="15%" nowrap="nowrap" align="center">√/×/*NA</td></tr>';
        $condition_set = json_decode($check_list[0]['condition_list'], true);
        $resultText = RoutineCheck::resultText();
        foreach ($condition_set as $key => $row) {
            $condition_name = $row['condition_name'].'<br>'.$row['condition_name_en'];
            $condition_html .= '<tr><td align="center">&nbsp;' . ($key + 1) . '</td><td>&nbsp;' . $condition_name . '</td><td align="center">&nbsp;' . $resultText[$row['flatStatus']] . '</td></tr>';
        }
        $condition_html .= '</table>';

        $html = $title_html . $apply_info_html . $work_content_html  .$condition_html;
        $pdf->writeHTML($html, true, false, true, false, '');

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
     * 按承包商查询PTW类型
     */
    public function actionQueryType() {
        $program_id = $_POST['program_id'];

        $rows = RoutineCheckType::typeByContractor($program_id);

        print_r(json_encode($rows));
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
        $check_list = RoutineCheck::detailList($check_id);//例行检查单
        $detail_list = RoutineCheckDetail::detailList($check_id);//例行检查单详情
        $status_list = RoutineCheck::statusText(); //状态text
        $this->renderPartial('preview',array('check_list' => $check_list,'detail_list'=>$detail_list,'status_list'=>$status_list));
    }

    /**
     * 项目统计图表
     */
    public function actionProjectChart() {
        $program_id = $_REQUEST['program_id'];
        $platform = $_REQUEST['platform'];
        $this->smallHeader = Yii::t('dboard', 'Project Statistical Charts');
        if(!is_null($platform)) {
            $this->layout = '//layouts/main_2';
            $this->render('statistical_project_dash', array('program_id' => $program_id,'platform'=>$platform));
        }else{
            $this->render('statistical_project', array('program_id' => $program_id));
        }
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
     *查询违规次数（项目）
     */
    public function actionCntByProject() {
        $args['program_id'] = $_REQUEST['id'];
        $args['date'] = $_REQUEST['date'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $r = RoutineCheck::AllCntList($args);
        print_r(json_encode($r));
    }
    /**
     *查询违规次数（公司）
     */
    public function actionCntByCompany() {
        $args['program_id'] = $_REQUEST['id'];
        $args['date'] = $_REQUEST['date'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $r = RoutineCheck::CompanyCntList($args);
        print_r(json_encode($r));
    }

}
