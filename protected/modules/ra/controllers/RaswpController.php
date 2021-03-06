<?php

/**
 * 安全检查
 * @author LiuMinChao
 */
class RaswpController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';
    const STATUS_NORMAL = 0; //正常

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('comp_ra', 'contentHeader');
        $this->bigMenu = Yii::t('comp_ra', 'bigMenu');
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=ra/raswp/grid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('comp_ra', 'ra_id'), '', 'center');
        $t->set_header(Yii::t('comp_ra', 'root_proname'), '', 'center');
        $t->set_header(Yii::t('comp_ra', 'Submitted_By'), '', 'center');
        $t->set_header(Yii::t('comp_ra', 'Worker type'), '', 'center');
        $t->set_header(Yii::t('comp_ra', 'title'), '', 'center');
        $t->set_header(Yii::t('comp_ra', 'apply_time'), '', 'center');
        $t->set_header(Yii::t('comp_ra', 'by_time'), '', 'center');
        $t->set_header(Yii::t('comp_ra', 'status'), '', 'center');
        $t->set_header(Yii::t('common', 'action'), '15%', 'center');
        return $t;
    }

    /**
     * 查询
     */
    public function actionGrid() {
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        $app_id = 'RA';
        $t = $this->genDataGrid();
        $this->saveUrl();
        //$args['status'] = ApplyBasic::STATUS_FINISH;
        $args['contractor_id'] = Yii::app()->user->contractor_id;
        $list = RaBasic::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t, 'app_id'=>$app_id,'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {

        $program_id = $_REQUEST['program_id'];
        $this->smallHeader = Yii::t('comp_ra', 'smallHeader List');
        $this->render('list',array('program_id'=>$program_id));
    }
    /**
     * 下载模版界面
     */
    public function actionDownload() {
        $worker_type = WorkerType::getType();
        $model = new WorkerType();
        $this->renderPartial('download',array('model' => $model,'worker_type' => $worker_type));
    }

    /**
     * Method Statement with Risk Assessment
     */
    public function actionMethod() {
        $this->render('method_statement');
    }

    /**
     * Method Statement with Risk Assessment
     */
    public function actionSaveMethod() {
        $json = $_REQUEST['json_data'];
        $str = '[
  {
    "tempId": 1545298559831,
    "type": 2,
    "name": "测试项名称",
    "state": 1,
    "LAY_TABLE_INDEX": 0
  },
  {
    "tempId": 1545298559831,
    "type": 2,
    "name": "测试项名称",
    "state": 1,
    "LAY_TABLE_INDEX": 1
  }
]';
        $data = json_decode($json);
        var_dump($data);
        exit;
    }

    /**
     * json数据
     */
    public function actionDemoData() {
        //$res['code'] = "0";
        //$res['msg'] = '';
        //$res['count'] = 100;
        $res = array();
        for($i=0;$i<=3;$i++){
            $fileinfo['date'] = 'DD/MM/YYYY';
            $fileinfo['time'] = '00:00';
            $fileinfo['location'] = '济南';
            $fileinfo['leader'] = '李博辰';
            $fileinfo['attendees'] = '好家伙';
            $fileinfo['tempId'] = time();
            array_push($res, $fileinfo);
        }
        //$res_data = json_encode($res);
        //$res_data = json_decode($res_data,false);
        print_r(json_encode($res));
    }

    /**
     * 添加申请记录
     */
    public function actionInsert() {
        $ra = $_REQUEST['ra'];
        $ra_basic = $_REQUEST['RaBasic'];
        $ra['valid_time'] = $ra_basic['valid_time'];
        $ra['title'] = $ra_basic['title'];
        $ra_user = $_REQUEST['ra_user'];
        $ra_leader = $_REQUEST['ra_leader'];
        $ra_approver = $_REQUEST['ra_approver'];
        $ra_files  = $_REQUEST['RaFile'];
        $swp_files = $_REQUEST['SwpFile'];
        $fp_files  = $_REQUEST['FpFile'];
        $lp_files  = $_REQUEST['LpFile'];
        $ms_files  = $_REQUEST['MsFile'];
        $other_files = $_REQUEST['OtherFile'];
        $worker_type = WorkerType::getType();//工种列表
        $worker_type_name = $worker_type[$ra['worker_type']];//工种名称
        $args['contractor_id'] = Yii::app()->user->contractor_id;

        //判断文件是不是为空
        if ($ra_files=='' || $swp_files==''){
            $r['status'] = '-1';
            $r['msg'] = Yii::t('comp_ra','Error Upload_document is null');
            $r['refresh'] = false;
            goto end;
        }
        //循环RA文件路径
        foreach($ra_files as $cnt => $src){
            if($ra['ra_path'] == ''){
                $ra['ra_path'] = $src;
            }else{
                $ra['ra_path'] .='|'.$src;
            }
        }
        //循环SWP文件路径
        foreach($swp_files as $cnt => $src){
            if($ra['swp_path'] == ''){
                $ra['swp_path'] = $src;
            }else{
                $ra['swp_path'] .='|'.$src;
            }
        }
        if($fp_files !=''){
            //循环FP文件路径
            foreach($fp_files as $cnt => $src){
                if($ra['fp_path'] == ''){
                    $ra['fp_path'] = $src;
                }else{
                    $ra['fp_path'] .='|'.$src;
                }
            }
        }
        if($lp_files !=''){
            //循环LP文件路径
            foreach($lp_files as $cnt => $src){
                if($ra['lp_path'] == ''){
                    $ra['lp_path'] = $src;
                }else{
                    $ra['lp_path'] .='|'.$src;
                }
            }
        }
        if ($ms_files !='') {
            //循环MS文件路径
            foreach($ms_files as $cnt => $src){
                if($ra['ms_path'] == ''){
                    $ra['ms_path'] = $src;
                }else{
                    $ra['ms_path'] .='|'.$src;
                }
            }
        }
        if ($other_files !='') {
            //循环OTHER文件路径
            foreach($other_files as $cnt => $src){
                if($ra['other_path'] == ''){
                    $ra['other_path'] = $src;
                }else{
                    $ra['other_path'] .='|'.$src;
                }
            }
        }
        $r = RaBasic::insertBasic($ra,$ra_user,$ra_leader,$ra_approver);
        end:
        print_r(json_encode($r));
    }
    /**
     * 编辑申请记录
     */
    public function actionUpdate() {
        $ra = $_REQUEST['ra'];
        $ra_basic = $_REQUEST['RaBasic'];
        $ra['valid_time'] = $ra_basic['valid_time'];
        $ra['title'] = $ra_basic['title'];
        $ra_user = $_REQUEST['ra_user'];
        $ra_files = $_REQUEST['RaFile'];
        $swp_files = $_REQUEST['SwpFile'];
        $args['contractor_id'] = Yii::app()->user->contractor_id;
        //判断文件是不是为空
        if ($ra_files=='' || $swp_files==''){
            $r['status'] = '-1';
            $r['msg'] = Yii::t('comp_ra','Error Upload_document is null');
            $r['refresh'] = false;
            goto end;
        }
        //循环RA文件路径
        foreach($ra_files as $cnt => $src){
            if($ra['ra_path'] == ''){
                $ra['ra_path'] = $src;
            }else{
                $ra['ra_path'] .='|'.$src;
            }
        }
        //循环SWP文件路径
        foreach($swp_files as $cnt => $src){
            if($ra['swp_path'] == ''){
                $ra['swp_path'] = $src;
            }else{
                $ra['swp_path'] .='|'.$src;
            }
        }
        $r = RaBasic::updateBasic($ra,$ra_user);
        end:
        print_r(json_encode($r));
    }
    /**
     * 申请
     */
    public function actionApply() {
        $this->contentHeader = Yii::t('comp_ra', 'new_application');
        $this->smallHeader = Yii::t('comp_ra', 'header');
        $program_id = $_REQUEST['program_id'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $program_list = Program::programList($args);
        $worker_type = WorkerType::getType();
        $model = new RaBasic();
        $this->render('apply',array('model'=>$model,'program_id'=>$program_id,'program_list'=>$program_list,'worker_type' => $worker_type));
    }
    /**
     * 修改申请记录
     */
    public function actionUpdateapply() {
        $this->smallHeader = Yii::t('comp_ra', 'header');
        $ra_swp_id = $_REQUEST['ra_swp_id'];
        $ra_list = RaBasic::model()->findByPk($ra_swp_id);
        $ra_user_list = RaWorker::getMembersName($ra_list['ra_swp_id']);
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $program_list = Program::programList($args);
        $worker_type = WorkerType::getType();
        $model = new RaBasic('modify');
        $model->_attributes = RaBasic::model()->find('ra_swp_id=:ra_swp_id', array(':ra_swp_id' => $ra_swp_id));
        $this->render('apply',array('model'=>$model,'ra_swp_id'=>$ra_swp_id,'ra_user_list'=>$ra_user_list,'ra_list'=>$ra_list,'program_list'=>$program_list,'worker_type' => $worker_type));
    }
    /**
     * 删除申请记录
     */
    public function actionDeleteapply() {
        $ra_swp_id = trim($_REQUEST['ra_swp_id']);
        $app_id = trim($_REQUEST['app_id']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = RaBasic::deleteApply($ra_swp_id);
        }
        echo json_encode($r);
    }
    /**
    /**
     * RA成员展示
     */
    public function actionRauser() {
        $program_id = $_REQUEST['id'];
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $ra_list = ProgramUser::UserListByRa($program_id,$contractor_id);
        print_r(json_encode($ra_list));
    }
    /**
     * 工种列表
     */
    public function actionWorkertype() {
        $tag = $_REQUEST['tag'];
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $worker_type = WorkerType::findType($tag);
        print_r(json_encode($worker_type));
    }
    /**
     * 下载模版
     */
    public function actionDownloadtemplate() {
        $type_id = $_REQUEST['type_id'];
        $type_list = WorkerType::model()->findByPk($type_id);
        $doc_path = $type_list['doc_path'];
        $file_name='/opt/www-nginx/web'.$doc_path;
        if (file_exists($file_name) == false) {
            header("Content-type:text/html;charset=utf-8");
            echo "<script>alert('".Yii::t('common','Document not found')."');</script>";
            return;
        }
        $file = fopen($file_name, "r"); // 打开文件
        header('Content-Encoding: none');
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Accept-Length: " . filesize($file_name));
        header('Content-Transfer-Encoding: binary');
        //$name = "员工信息表导入模版".date('YmdHis').".xls";
        $name = "RA_SWP".".doc";
        header("Content-Disposition: attachment; filename=" . $name); //以真实文件名提供给浏览器下载
        header('Pragma: no-cache');
        header('Expires: 0');
        echo fread($file, filesize($file_name));
        fclose($file);
    }

    /**
     * 将上传的文件移动到正式路径下
     */
    public function actionMoveFile() {
        $file_src = $_REQUEST['file_src'];
        $tag = $_REQUEST['tag'];
        $r = RaBasic::moveFile($file_src,$tag);
        print_r(json_encode($r));
    }
    /**
     * 删除上传图片
     */
    public function actionDelFile() {
        $src = $_REQUEST['src'];
        $r = RaBasic::deleteFile($src);
        print_r(json_encode($r));
    }
    /**
     * 导出PDF(新)
     */
    public function actionDownloadPdf() {
        $ra_swp_id = $_REQUEST['ra_swp_id'];
        $params['ra_swp_id'] = $ra_swp_id;
        $app_id = $_REQUEST['app_id'];
        $ra_list = RaBasic::model()->findByPk($ra_swp_id);//详细信息
        $title = 'RA_SWP';
        //if($ra_list['save_path']){
        //  $file_path = $ra_list['save_path'];
        //  $filepath = '/opt/www-nginx/web'.$file_path;
        //]}else{
        //  $filepath = DownloadPdf::transferDownload($params,$app_id);
        //}
        $filepath = DownloadPdf::transferDownload($params,$app_id);
        Utils::Download($filepath, $title, 'pdf');
    }
    /**
     * 导出PDF
     */
    public function actionExport() {
        $ra_swp_id = $_REQUEST['ra_swp_id'];
        $app_id = $_REQUEST['app_id'];
        $ra_list = RaBasic::model()->findByPk($ra_swp_id);//详细信息
        $company_list = Contractor::compAllList();//承包商公司列表
        $program_list = Program::programAllList();//获取承包商所有项目
        $programdetail_list = Program::getProgramDetail($ra_list['program_id']);//根据项目id得到总包商和根节点项目
        $contractor_id = $ra_list['contractor_id'];
        $user_list = ProgramUser::UserListByRa($ra_list['program_id'], $contractor_id);//RA成员信息
        $lang = "_en";

        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($ra_list->record_time, 0, 4);//年
        $month = substr($ra_list->record_time, 5, 2);//月
//        if ($ra_list['save_path']) {
//            $filepath = '/opt/www-nginx/web' . $ra_list['save_path'];
//        } else {
            $filepath = Yii::app()->params['upload_data_path'] . '/cert/' . $contractor_id . '/ra' . '/ra_swp' . $ra_swp_id . '.pdf';
            RaBasic::updatepath($ra_swp_id, $filepath);
//        }
        $full_dir = Yii::app()->params['upload_data_path'] . '/cert/' . $contractor_id . '/ra';
        if($full_dir == ''){
            return false;
        }
        if(!file_exists($full_dir))
        {
            umask(0000);
            @mkdir($full_dir, 0777, true);
        }
        $title = 'RA_SWP';

        //if (file_exists($filepath)) {
        //  $show_name = $title;
        //  $extend = 'pdf';
        //  Utils::Download($filepath, $show_name, $extend);
        //  return;
        //}
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
        $pdf->SetHeaderData('', 0, 'RA/SWP No.(风险评估编号): ' . $ra_swp_id, $title, array(0, 64, 255), array(0, 64, 128));
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        // 设置页眉和页脚字体

        //if (Yii::app()->language == 'zh_CN') {
        //  $pdf->setHeaderFont(Array('stsongstdlight', '', '10')); //中文
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
        $pdf->SetFont('droidsansfallback', '', 9, '', true); //英文OR中文


        $pdf->AddPage();

        $apply_html = "
            Project(项目) : {$program_list[$ra_list['program_id']]}<br/>
            Applicant Company(申请公司) : {$company_list[$contractor_id]}<br/>
            The contractor project(总包项目) : {$ra_list['program_name']}<br/>
            Set Time(规定时间) : {$ra_list['valid_time']}";

        $worker_html = '<h4 align="center">RA/SWP Members(RA/SWP 参会成员)</h4><table border="1">
                <tr><td  width="35%">&nbsp;Group(组)</td><td  width="65%">&nbsp;Members(成员)</td></tr>';
        if (!empty($user_list)) {
            $i = 1;
            $ra_leader = '';
            $ra_approver = '';
            $ra_user = '';
            foreach ($user_list as $cnt => $r) {
                if($cnt == 1){
                    foreach($r as $i => $leader){
                        $ra_leader.= $leader['user_name'].' ';
                    }
                    $worker_html .= '<tr><td>RA Leader(风险评估批准人)</td><td>&nbsp;' . $ra_leader . '</td></tr>';
                }
                if($cnt == 2){
                    foreach($r as $i => $approver){
                        $ra_approver.= $approver['user_name'].' ';
                    }
                    $worker_html .= '<tr><td>RA Approver(风险评估领队)</td><td>&nbsp;' . $ra_approver . '</td></tr>';
                }
                if($cnt == 3){
                    foreach($r as $i => $user){
                        $ra_user.= $user['user_name'].' ';
                    }
                    $worker_html .= '<tr><td>RA User(风险评估成员)</td><td>&nbsp;' . $ra_user . '</td></tr>';
                }
            }
        }
        $worker_html .= '</table>';

        $progress_list = CheckApplyDetailRa::progressList($app_id, $ra_swp_id);//TBM审批结果(快照)
        $user_list = Staff::userInfo();//员工信息
        $audit_html = '<h4 align="center">Approval Step(审批步骤)</h4><table border="1">
                <tr><td  nowrap="nowrap" width="5%">&nbsp;S/N(编号)</td><td  nowrap="nowrap" width="20%">&nbsp;Approver(审批人)</td><td  nowrap="nowrap" width="20%"> Approval Result(审批意见)</td><td  nowrap="nowrap" width="10%"> Approval Address(审批地点)</td><td  nowrap="nowrap" width="20%"> Approval Date(审批日期)</td><td width="30%">Electronic Signature(电子签名)</td></tr>';
        $progress_result = CheckApplyDetailRa::resultTxt();
        $info_xx = 156;//X方向距离
        $info_yy = 64;//Y方向距离
        $j = 1;
        if (!empty($progress_list))
            foreach ($progress_list as $key => $row) {

                $audit_html .= '<tr><td height="75px">&nbsp;' . $j . '</td><td>&nbsp;' . $row['user_name'] . '</td><td>&nbsp;' . $progress_result[$row['status']] . '</td><td>&nbsp;' . $row['address'] . '</td><td>&nbsp;'.Utils::DateToEn($row['deal_time']).'</td><td></td>';
                //$pic = 'img/avatar04.png';
                //$content = $user_list[$row['deal_user_id']]['signature_path'];
                $content_list = $user_list[$row['deal_user_id']];
                $content = $content_list[0]['signature_path'];
                if($content != '') {
                    $pdf->Image($content, $info_xx, $info_yy, 32, 18, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                    $info_yy += 24;
                }
                $audit_html .= '</tr>';
                $j++;
            }
        $audit_html .= '</table>';

        //$html = $apply_html . $worker_html . $audit_html;
        $html = $apply_html . $audit_html  .$worker_html;

        $pdf->writeHTML($html, true, false, true, false, '');

        //输出PDF
        //$pdf->Output($filepath, 'I');

        $pdf->Output($filepath, 'F'); //保存到指定目录
        Utils::Download($filepath, $title, 'pdf'); //下载pdf
//============================================================+
// END OF FILE
//============================================================+
    }
    //导出记录
    public function actionExportExcel(){
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件

        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $list = RaBasic::exportList($args);
        $rows = $list['rows'];
        $status_list = RaBasic::statusText(); //状态text
        $program_list =  Program::programAllList();
        $worker_type = WorkerType::getType();
        $company_list = Contractor::compAllList();//承包商公司列表
        $current_date = date('Y-m-d');

        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        $objectPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objectPHPExcel->getActiveSheet();
        $objActSheet->setTitle('Sheet1');

        //报表头的输出
        $objectPHPExcel->getActiveSheet()->mergeCells('A1'.':'.'H1');
        $objectPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->setCellValue('A1',Yii::t('comp_ra', 'smallHeader List'));
        $objStyleA1 = $objActSheet->getStyle('A1');
        //字体及颜色
        $objFontA1 = $objStyleA1->getFont();
        $objFontA1->setSize(20);
        $objectPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->mergeCells('A2'.':'.'H2');
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2',date("d M Y"));
        $objectPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('A3',Yii::t('comp_ra', 'ra_id'));
        $objectPHPExcel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('B3',Yii::t('comp_ra', 'root_proname'));
        $objectPHPExcel->getActiveSheet()->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('C3',Yii::t('comp_ra', 'Submitted_By'));
        $objectPHPExcel->getActiveSheet()->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('D3',Yii::t('comp_ra', 'Worker type'));
        $objectPHPExcel->getActiveSheet()->getStyle('E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('E3',Yii::t('comp_ra', 'title'));
        $objectPHPExcel->getActiveSheet()->getStyle('F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('F3',Yii::t('comp_ra', 'apply_time'));
        $objectPHPExcel->getActiveSheet()->getStyle('G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('G3',Yii::t('comp_ra', 'by_time'));
        $objectPHPExcel->getActiveSheet()->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('H3',Yii::t('comp_ra', 'status'));
        ////设置颜色
        //$objectPHPExcel->getActiveSheet()->getStyle('AP3')->getFill()
        //->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');
        //写入数据
        if (is_array($rows)) {
            foreach ($rows as $k => $v) {
                $k =$k +1;
                /*设置表格高度*/
                $objectPHPExcel->getActiveSheet()->getRowDimension($k+4)->setRowHeight(90);
                $objectPHPExcel->getActiveSheet()->setCellValue(A . ($k + 4),$k);
                $objectPHPExcel->getActiveSheet()->setCellValue(B . ($k + 4),$program_list[$v['program_id']]);
                $objectPHPExcel->getActiveSheet()->setCellValue(C . ($k + 4),$company_list[$v['contractor_id']]);
                $objectPHPExcel->getActiveSheet()->setCellValue(D . ($k + 4),$worker_type[$v['work_type']]);
                $objectPHPExcel->getActiveSheet()->setCellValue(E . ($k + 4),$v['title']);
                $objectPHPExcel->getActiveSheet()->setCellValue(F . ($k + 4),substr(Utils::DateToEn($v['record_time']),0,11));
                $objectPHPExcel->getActiveSheet()->setCellValue(G . ($k + 4),$v['valid_time']);
                $objectPHPExcel->getActiveSheet()->setCellValue(H . ($k + 4),$status_list[$v['status']]);
                //$n++;
            }
        }


        //下载输出
        ob_end_clean();
        //ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.'Staff Certificate Expiration Reminder-'.date("d M Y").'.xls"');
        $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter->save('php://output');
    }
    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['ra/list'] = str_replace("r=ra/raswp/grid", "r=ra/raswp/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

    /**
     * 下载列表
     */
    public function actionDownloadPreview() {
        $ra_swp_id = $_REQUEST['apply_id'];
        $app_id = $_REQUEST['app_id'];
        $ra_list = RaBasic::model()->findByPk($ra_swp_id);//详细信息
        $swp_path = $ra_list['swp_path'];
        $ra_path = $ra_list['ra_path'];
        $fp_path = $ra_list['fp_path'];
        $lp_path = $ra_list['lp_path'];
        $ms_path = $ra_list['ms_path'];
        $other_path = $ra_list['other_path'];

        $this->renderPartial('preview', array('swp_path'=> $swp_path,
                                              'ra_path' => $ra_path,
                                              'fp_path' => $fp_path,
                                              'lp_path' => $lp_path,
                                              'ms_path' => $ms_path,
                                              'other_path' => $other_path,
                                              'app_id'=>$app_id));
    }
    /**
     * 下载附件
     */
    public function actionDownloadAttachment() {
        $path = $_REQUEST['path'];
        $name = $_REQUEST['name'];
        $file = explode('.',$name);
        $tag = strrpos($name,'.')+1;
        $name_1= substr($name,0,50);
        $name_2= substr($name,$tag);
        Utils::Download($path, $name_1, $name_2); //下载pdf
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
            $this->render('statistical_project_dash',array('program_id'=>$program_id,'platform'=>$platform));
        }else{
            $this->render('statistical_project',array('program_id'=>$program_id));
        }
    }
    /**
     * 公司统计图表
     */
    public function actionCompanyChart() {
        $platform = $_REQUEST['platform'];
        $program_id = $_REQUEST['program_id'];
        $this->smallHeader = Yii::t('dboard', 'Company Statistical Charts');
        if(!is_null($platform)) {
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
        $r = RaBasic::AllCntList($args);
        print_r(json_encode($r));
    }
    /**
     *查询违规次数（公司）
     */
    public function actionCntByCompany() {
        $args['program_id'] = $_REQUEST['id'];
        $args['date'] = $_REQUEST['date'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $r = RaBasic::CompanyCntList($args);
        print_r(json_encode($r));
    }
}
