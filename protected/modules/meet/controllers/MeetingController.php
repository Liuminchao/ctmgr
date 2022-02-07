<?php

/**
 * 会议
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
        $this->contentHeader = Yii::t('meeting', 'contentHeader');
        $this->bigMenu = Yii::t('meeting', 'bigMenu');
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=meet/meeting/grid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('meeting', 'seq'), '', 'center');
        $t->set_header(Yii::t('meeting', 'program_name'), '', 'center');
        $t->set_header(Yii::t('meeting', 'title'), '', 'center');
        $t->set_header(Yii::t('meeting','meeting_type'),'','center');
        $t->set_header(Yii::t('meeting', 'apply_date'), '', 'center');
        $t->set_header(Yii::t('meeting', 'status'), '', 'center');
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

        if(count($fields) == 5 && $fields[0] != null ) {
            $args['program_id'] = $fields[0];
            $args['user_id'] = $fields[1];
            $args['deal_type'] = $fields[2];
            $args['type_id'] = $fields[3];
            $args['module_type'] = $fields[4];
        }
        if(!$args['module_type']){
            $args['module_type'] = 2;
        }
//        var_dump($args);
        $t = $this->genDataGrid();
        $this->saveUrl();
//        $args['status'] = Meeting::STATUS_FINISH;

//        if (Yii::app()->user->getState('role_id') == Operator::ROLE_MC) {//总包


//        } else if (Yii::app()->user->getState('role_id') == Operator::ROLE_SC) {//分包
//            $args['program_id'] = ProgramContractor::getProgramId();
//        }
        $app_id = 'Meeting';
        $args['contractor_id'] = Yii::app()->user->contractor_id;
        $rs = Workflow::contractorflowList(self::STATUS_NORMAL,$args['contractor_id'],$app_id);
        $list = Train::queryList($page, $this->pageSize, $args);
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
        $module_type = $_REQUEST['module_type'];
//        var_dump($module_type);
//        var_dump(11111);
        $this->smallHeader = Yii::t('meeting', 'smallHeader List');
        $this->render('list',array('user_id'=> $user_id,'program_id'=> $program_id,'deal_type'=>$deal_type,'type_id'=>$type_id,'module_type'=>$module_type));
    }

    /**
     * 下载列表
     */
    public function actionDownloadPreview() {
        $train_id = $_REQUEST['train_id'];
        $app_id = $_REQUEST['app_id'];
        $check_list = TrainChecklist::queryDocument($train_id);
        $this->renderPartial('download_preview', array('train_id'=>$train_id,'check_list' => $check_list,'app_id'=>$app_id));
    }

    /**
     * 下载附件列表
     */
    public function actionDownloadAttachment() {
        $train_id = $_REQUEST['train_id'];
        $form_data_list = TrainDocument::detailList($train_id); //记录
        $this->renderPartial('download_attachment', array('train_id'=>$train_id,'form_data_list'=>$form_data_list));
    }

    /**
     * 下载PDF（新）
     */
    public static function actionDownloadPdf() {
        $id = $_REQUEST['apply_id'];
        $tag = $_REQUEST['tag'];
        $params['id'] = $id;
        $params['tag'] = $tag;
        $app_id = $_REQUEST['app_id'];
        $meeting = Train::model()->findByPk($id);
        $title = $meeting->title;
        //报告定制化
        $program_id = $meeting->program_id;
        $pro_model = Program::model()->findByPk($program_id);
        $pro_params = $pro_model->params;//项目参数
        if($pro_params != '0') {
            $pro_params = json_decode($pro_params, true);
            //判断是否是迁移的
            if (array_key_exists('train_report', $pro_params)) {
                $params['type'] = $pro_params['train_report'];
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

        $id = $_REQUEST['train_id'];
        $app_id = $_REQUEST['app_id'];
        $meeting = Train::model()->findByPk($id);
        $document_list = TrainDocument::queryDocument($id);//文档列表
        $worker_temp = TrainWorkerTemp::queryWorker($id);//临时人员列表
        $company_list = Contractor::compAllList();//承包商公司列表
        $program_list =  Program::programAllList();//获取承包商所有项目
        $programdetail_list = Program::getProgramDetail($meeting->program_id);//根据项目id得到总包商和根节点项目
        $lang = "_en";

        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($meeting->record_time,0,4);//年
        $month = substr($meeting->record_time,5,2);//月
//        if($meeting->save_path){
//            $file_path = $meeting->save_path;
//            $filepath = '/opt/www-nginx/web'.$file_path;
//        }else{
            $filepath = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/train'.'/TRAIN' . $id . '.pdf';
            $full_dir = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/train';
            if(!file_exists($full_dir))
            {
                umask(0000);
                @mkdir($full_dir, 0777, true);
            }
            Train::updatepath($id,$filepath);
//        }
//        $filepath = Yii::app()->params['upload_file_path'] .'/tbm/'.$meeting->add_conid.'/TBM' . $id . '.pdf';
//        $filepath = '/opt/www-nginx/web/ctmgr/webuploads'.'/tbm/'.$meeting->add_conid.'/TBM' . $id . '.pdf';
//        $filepath = '/opt/www-nginx/web/ctmgr/webuploads'.'/tbm/'.$meeting->add_conid.'/TBM' . $id . '.pdf';
//        $full_dir = Yii::app()->params['upload_file_path'] .'/tbm/'.$meeting->add_conid;
//        if($full_dir == ''){
//            return false;
//        }

        $title = $meeting->title;

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
        $main_conid = Yii::app()->user->getState('contractor_id');//总包编号
        $main_model = Contractor::model()->findByPk($main_conid);
        $main_conid_name = $main_model->contractor_name;
        $logo_pic = $main_model->remark;
        if($logo_pic){
            $logo = '/opt/www-nginx/web'.$logo_pic;
            $pdf->SetHeaderData($logo, 20, 'Meeting Records No. (会议记录编号): ' . $meeting->training_id, $main_conid_name, array(0, 64, 255), array(0, 64, 128));
        }else{
            $pdf->SetHeaderData('', 0,  'Meeting Records No. (会议记录编号): ' . $meeting->training_id, $main_conid_name,array(0, 64, 255), array(0, 64, 128));
        }

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

        $members = TrainWorker::getMembersName($meeting->training_id);

//        $start_date = date('Y-m-d',strtotime($meeting->from_date));
//        $end_date = date('Y-m-d',strtotime($meeting->end_date));
        $from_date = Utils::DateToEn(substr($meeting->start_time,0,10));//起始日期
        $end_date = Utils::DateToEn(substr($meeting->end_time,0,10));//截止日期
        $from_time = substr($meeting->start_time,11,19);//起始时间
        $end_time = substr($meeting->end_time,11,19);//起始时间
        //标题(许可证类型+项目)
        $title_html = "<h2 align=\"center\">Project (项目) : {$program_list[$meeting->program_id]}</h2><br/>";

        $apply_user =  Staff::model()->findAllByPk($meeting->add_user);//申请人
        $roleList = Role::roleallList();//岗位列表
        $apply_role = $apply_user[0]['role_id'];//发起人角色
        $contractor_id = $apply_user[0]['contractor_id'];//发起人公司
        $user_list = Staff::userInfo();//员工信息
        $status_css = CheckApplyDetailTrain::statusTxt();//执行类型
        //发起人详情
        $apply_info_html = "<h4 align=\"center\">Initiator Details (发起人详情)</h4><table border=\"1\">";
        $apply_info_html .="<tr><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Name (姓名)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Designation (职位)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">ID Number (身份证号码)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Company (公司)</td></tr>";
        $apply_info_html .="<tr><td height=\"50px\" align=\"center\">&nbsp;{$apply_user[0]['user_name']}</td><td align=\"center\">&nbsp;{$roleList[$apply_role]}</td><td align=\"center\">&nbsp;{$apply_user[0]['work_no']}</td><td  align=\"center\">&nbsp;{$company_list[$contractor_id]}</td></tr>";
        $apply_info_html .="</table>";

        //培训详情
        $work_content_html = "<h4 align=\"center\">Meeting Details (会议详情)</h4><table border=\"1\">";
        $work_content_html .="<tr><td width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Title (标题)</td><td width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Content (内容)</td><td width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Meeting Type (会议类型)</td></tr>";
        $work_content_html .="<tr><td height=\"140px\">{$meeting->title}</td><td >{$meeting->content}</td><td align=\"center\">{$meeting->type_name_en}<br>{$meeting->type_name}</td></tr></table><br>";

        //培训日期
        $work_date_html = "<br><table border=\"1\">";
        $work_date_html .="<tr><td width=\"10%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">S/N (序号)</td><td width=\"30%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Date of Meeting (培训日期)</td><td width=\"30%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Start Time (开始时间)</td><td width=\"30%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">End Time (结束时间)</td></tr>";
        $work_date_html .="<tr><td height=\"50px\" align=\"center\">1</td><td align=\"center\">{$from_date}</td><td align=\"center\">{$from_time}</td><td align=\"center\">{$end_time}</td></tr></table>";

        $progress_list = CheckApplyDetailTrain::progressList( $app_id,$meeting->training_id);//审批步骤详情
        $progress_result = CheckApplyDetailTrain::resultTxt();

        $pic_html = '<h4 align="center">Photo(s) (现场照片)</h4><table border="1">
                <tr><td width ="100%" height="103px"></td></tr>';
        $pic = $meeting->pic;
        if($pic != '' && $pic != 'nil' && $pic != '-1') {
            $pic = explode('|', $pic);
            $info_x = 40;
            $info_y = 144;
            foreach ($pic as $key => $content) {
//                $pic = 'C:\Users\minchao\Desktop\5.png';
                if ($content != '-1'){
                    $pdf->Image($content, $info_x, $info_y, 30, 28, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                    $info_x += 50;
                }
            }
        }else{
            foreach ($progress_list as $m => $n) {
                if($n['deal_type'] == '7' ){
                    if($n['pic'] !='' && $n['pic'] !='nil' && $n['pic'] !='-1'){
                        $pic = $n['pic'];
                    }
                }
            }
            $pic = explode('|', $pic);

            $info_x = 40;
            $info_y = 144;
            foreach ($pic as $key => $content) {
//                $pic = 'C:\Users\minchao\Desktop\5.png';
                if ($content != '-1'){
                    $pdf->Image($content, $info_x, $info_y, 30, 28, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                    $info_x += 50;
                }
            }
        }
        $pic_html .= '</table>';


        $j = 1;
        $y = 1;
        $info_xx = 170;
        $info_yy = 192;
        if (!empty($progress_list))
            $num = count($progress_list);
            if($num < 5) {
                //审批流程
                //                $pic = 'C:\Users\minchao\Desktop\5.png';
                $audit_html = '<h4 align="center">Approval Process (审批流程)</h4><table border="1">
                <tr><td  nowrap="nowrap" width="20%" bgcolor="#d9d9d9" align="center">&nbsp;Person in Charge<br>(执行人)</td><td  nowrap="nowrap" width="20%" bgcolor="#d9d9d9" align="center"> Status<br>(状态)</td><td  nowrap="nowrap" width="20%" bgcolor="#d9d9d9" align="center"> Date & Time<br>(日期&时间)</td><td  nowrap="nowrap" width="20%" bgcolor="#d9d9d9" align="center">&nbsp;Remark<br>(备注)</td><td  nowrap="nowrap" width="20%" bgcolor="#d9d9d9" align="center">&nbsp;Electronic Signature<br>(电子签名)</td></tr>';
                foreach ($progress_list as $key => $row) {

                        $content_list = $user_list[$row['deal_user_id']];
                        $content = $content_list[0]['signature_path'];
                        //$p = '/opt/www-nginx/appupload/4/0000002314_TBMMEETINGPHOTO.jpg';
                        if ($content != '') {
                            $pdf->Image($content, $info_xx, $info_yy, 21, 10, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                        }
                        $audit_html .= '<tr><td height="55px" align="center">&nbsp;' . $row['user_name'] . '</td><td align="center">&nbsp;' . $status_css[$row['deal_type']] . '</td><td align="center">&nbsp;' . Utils::DateToEn($row['deal_time']) . '</td><td align="center">&nbsp;' . $row['remark'] . '</td><td align="center"></td></tr>';
                        $j++;
                        $info_yy += 15;

                }
                $audit_html .= '</table>';

                //文档标签
                $document_html = '<h4 align="center">Document(s) (标签)</h4><table border="1">
                <tr><td  width="20%" bgcolor="#d9d9d9" align="center">&nbsp;S/N<br>(序号)</td><td  width="80%" bgcolor="#d9d9d9" align="center">&nbsp;Document Name<br>(文档名称)</td></tr>';
                if(!empty($document_list)){
                    $i =1;
                    foreach($document_list as $cnt => $name){
                        $document_html .='<tr><td align="center">&nbsp;' . $i . '</td><td align="center">&nbsp;' . $name . '</td>';
                        $i++;
                    }
                }
                $document_html .= '</table>';
                //参与人员
                $worker_html = '<h4 align="center">Participant(s) (参与成员)</h4><table border="1">
                <tr><td  width="10%" bgcolor="#d9d9d9" align="center">&nbsp;S/N<br>(序号)</td><td  width="20%" bgcolor="#d9d9d9" align="center">&nbsp;ID Number<br>(身份证号码)</td><td  width="35%" bgcolor="#d9d9d9" align="center">&nbsp;Company<br>(公司)</td><td width="15%" bgcolor="#d9d9d9" align="center">&nbsp;Name<br>(姓名)</td><td width="20%" bgcolor="#d9d9d9" align="center">&nbsp;Designation<br>(职务)</td></tr>';

                if (!empty($members)){
                    $i = 1;
                    foreach ($members as $user_id => $r) {
                        $worker_html .= '<tr><td align="center">&nbsp;' . $i . '</td><td align="center">&nbsp;' . $r['work_no'] . '</td><td align="center">&nbsp;'.$company_list[$r['contractor_id']].'</td><td align="center">&nbsp;' . $r['worker_name'] . '</td><td align="center">&nbsp;'.$roleList[$r['role_id']].'</td></tr>';
                        $i++;
                    }
                }
                if(!empty($worker_temp)){
                    foreach ($worker_temp as $k => $u) {
                        $worker_html .= '<tr><td align="center">&nbsp;' . $i . '</td><td align="center">&nbsp;Temp</td><td align="center">&nbsp;'.$u['contractor_name'].'</td><td align="center">&nbsp;' . $u['user_name'] . '</td><td align="center">&nbsp;'.$u['role_name'].'</td></tr>';
                        $i++;
                    }
                }
                $worker_html .= '</table>';

                $html = $title_html . $apply_info_html . $work_content_html . $work_date_html . $pic_html  .$audit_html . $document_html  .$worker_html;

                $pdf->writeHTML($html, true, false, true, false, '');
            }else{
                $audit_html = '<h4 align="center">Approval Process (审批流程)</h4><table border="1">
                <tr><td  nowrap="nowrap" width="20%" bgcolor="#d9d9d9" align="center">&nbsp;Person in Charge<br>(执行人)</td><td  nowrap="nowrap" width="20%" bgcolor="#d9d9d9" align="center"> Status<br>(状态)</td><td  nowrap="nowrap" width="20%" bgcolor="#d9d9d9" align="center"> Date & Time<br>(日期&时间)</td><td  nowrap="nowrap" width="20%" bgcolor="#d9d9d9" align="center">&nbsp;Remark<br>(备注)</td><td  nowrap="nowrap" width="20%" bgcolor="#d9d9d9" align="center">&nbsp;Electronic Signature<br>(电子签名)</td></tr>';
                foreach ($progress_list as $key => $row) {
                    if($row['step'] < 5) {
                        $content_list = $user_list[$row['deal_user_id']];
                        $content = $content_list[0]['signature_path'];
                        //$content = '/opt/www-nginx/appupload/4/0000002314_TBMMEETINGPHOTO.jpg';
                        if ($content != '') {
                            $pdf->Image($content, $info_xx, $info_yy, 21, 10, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                        }
                        $audit_html .= '<tr><td height="55px" align="center">&nbsp;' . $row['user_name'] . '</td><td align="center">&nbsp;' . $status_css[$row['deal_type']] . '</td><td align="center">&nbsp;' . Utils::DateToEn($row['deal_time']) . '</td><td align="center">&nbsp;' . $row['remark'] . '</td><td align="center"></td></tr>';
                        $j++;
                        $info_yy += 15;
                    }
                }
                $audit_html .= '</table>';
                $html = $title_html . $apply_info_html . $work_content_html . $work_date_html . $pic_html  .$audit_html ;
                $pdf->writeHTML($html, true, false, true, false, '');
                $pdf->AddPage();
                $audit_html2 = "<table border=\"1\">";
                foreach ($progress_list as $key => $row) {
                    if($row['step'] >= 5) {
                        $content_list = $user_list[$row['deal_user_id']];
                        $content = $content_list[0]['signature_path'];
                        //$p = '/opt/www-nginx/appupload/4/0000002314_TBMMEETINGPHOTO.jpg';
                        if ($content != '') {
                            $pdf->Image($content, $info_xx, 30, 21, 10, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                        }
                        $audit_html2 .= '<tr><td height="55px" align="center">&nbsp;' . $row['user_name'] . '</td><td align="center">&nbsp;' . $status_css[$row['deal_type']] . '</td><td align="center">&nbsp;' . Utils::DateToEn($row['deal_time']) . '</td><td align="center">&nbsp;' . $row['remark'] . '</td><td align="center"></td></tr>';
                        $j++;
                        $info_yy += 15;
                    }
                }
                $audit_html2 .= '</table>';
                //文档标签
                $document_html = '<h4 align="center">Document(s) (标签)</h4><table border="1">
                <tr><td  width="20%" bgcolor="#d9d9d9" align="center">&nbsp;S/N<br>(序号)</td><td  width="80%" bgcolor="#d9d9d9" align="center">&nbsp;Document Name<br>(文档名称)</td></tr>';
                if(!empty($document_list)){
                    $i =1;
                    foreach($document_list as $cnt => $name){
                        $document_html .='<tr><td align="center">&nbsp;' . $i . '</td><td align="center">&nbsp;' . $name . '</td>';
                        $i++;
                    }
                }
                $document_html .= '</table>';
                //参与人员
                $worker_html = '<h4 align="center">Participants(s) (参与成员)</h4><table border="1">
                <tr><td  width="10%" bgcolor="#d9d9d9" align="center">&nbsp;S/N<br>(序号)</td><td  width="20%" bgcolor="#d9d9d9" align="center">&nbsp;ID Number<br>(身份证号码)</td><td  width="35%" bgcolor="#d9d9d9" align="center">&nbsp;Company<br>(公司)</td><td width="15%" bgcolor="#d9d9d9" align="center">&nbsp;Name<br>(姓名)</td><td width="20%" bgcolor="#d9d9d9" align="center">&nbsp;Designation<br>(职务)</td></tr>';

                if (!empty($members)){
                    $i = 1;
                    foreach ($members as $user_id => $r) {
                        $worker_html .= '<tr><td align="center">&nbsp;' . $i . '</td><td align="center">&nbsp;' . $r['work_no'] . '</td><td align="center">&nbsp;'.$company_list[$r['contractor_id']].'</td><td align="center">&nbsp;' . $r['worker_name'] . '</td><td align="center">&nbsp;'.$roleList[$r['role_id']].'</td></tr>';
                        $i++;
                    }
                }
                if(!empty($worker_temp)){
                    foreach ($worker_temp as $k => $u) {
                        $worker_html .= '<tr><td align="center">&nbsp;' . $i . '</td><td align="center">&nbsp;Temp</td><td align="center">&nbsp;'.$u['contractor_name'].'</td><td align="center">&nbsp;' . $u['user_name'] . '</td><td align="center">&nbsp;'.$u['role_name'].'</td></tr>';
                        $i++;
                    }
                }
                $worker_html .= '</table>';

                $html2 = $audit_html2 . $document_html .$worker_html;
                $pdf->writeHTML($html2, true, false, true, false, '');
            }


        //输出PDF
//        $pdf->Output($filepath, 'I');

        $pdf->Output($filepath, 'I'); //保存到指定目录
        Utils::Download($filepath, $title, 'pdf'); //下载pdf
//============================================================+
// END OF FILE
//============================================================+
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genPromoteGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=meet/meeting/promotegrid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('tbm_meeting', 'seq'), '', 'center');
        $t->set_header(Yii::t('tbm_meeting', 'program_name'), '', 'center');
        $t->set_header(Yii::t('meeting', 'items'), '', 'center');
        $t->set_header(Yii::t('meeting', 'schedule'), '', 'center');
        $t->set_header(Yii::t('meeting', 'actual'), '', 'center');
        $t->set_header(Yii::t('common', 'record_time'), '', 'center');
        $t->set_header(Yii::t('meeting', 'status'), '', 'center');
        $t->set_header(Yii::t('common', 'action'), '15%', 'center');
        return $t;
    }

    /**
     * 查询
     */
    public function actionPromoteGrid() {
        $fields = func_get_args();
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件

//        if(count($fields) == 3 && $fields[0] != null ) {
//            $args['program_id'] = $fields[0];
//            $args['user_id'] = $fields[1];
//            $args['deal_type'] = $fields[2];
//        }
        $t = $this->genPromoteGrid();
        $this->saveUrl();

        $args['contractor_id'] = Yii::app()->user->contractor_id;
        $list = TrainSafetyPromote::queryList($page, $this->pageSize, $args);
        $this->renderPartial('plan_list', array('t' => $t, 'rows' => $list['rows'],'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionPromoteList() {

        $this->smallHeader = Yii::t('dboard', 'Menu Safety Promotion Programme');
        $this->render('planlist');
    }

    /**
     * 添加计划
     */
    public function actionNewPromote() {

        $this->smallHeader = Yii::t('tbm_meeting', 'add_plan');
        $model = new TrainSafetyPromote('create');
        $r = array();
        if (isset($_POST['TrainSafetyPromote'])) {
            $args = $_POST['TrainSafetyPromote'];
            $r = TrainSafetyPromote::InsertList($args);

        }

        $this->render('plan_form', array('model' => $model,'msg' => $r,'_mode_'=>'insert'));
    }
    /**
     * 修改
     */
    public function actionEditPromote() {
        $this->smallHeader = Yii::t('tbm_meeting', 'edit_plan');
        $model = new TrainSafetyPromote('modify');
        $r = array();
        $id = $_REQUEST['id'];
        $model = TrainSafetyPromote::model()->findByPk($id);
        if (isset($_POST['TrainSafetyPromote'])) {
            $args = $_POST['TrainSafetyPromote'];

            $r = TrainSafetyPromote::UpdateList($id,$args);
            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['TrainSafetyPromote'];
            }
        }

        $model->_attributes = TrainSafetyPromote::model()->findByPk($id);

        $this->render('plan_form', array('id'=>$id,'model' => $model, 'msg' => $r,'_mode_'=>'edit'));
    }

    /**
     * 注销
     */
    public function actionDelPromote() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = TrainSafetyPromote::DeleteList($id);
        }
        //var_dump($r);
        echo json_encode($r);
    }

    /**
     * 预览流程图
     */
    public function actionPreview() {
        $flow_id = $_REQUEST['flow_id'];
        $apply_id = $_REQUEST['apply_id'];
        $app_id = $_REQUEST['app_id'];
//        var_dump($apply_id);
//        var_dump($flow_id);
//        exit;
        $app_id = 'TRAIN';
        $step_list = WorkflowDetail::stepList($flow_id);

        $progress_list = CheckApplyDetailTrain::progressList($app_id,$apply_id);//审批进度流程
        $status_css = CheckApplyDetailTrain::statusText();
//        var_dump($progress_list);
        $this->renderPartial('preview', array('step_list' => $step_list,'progress_list'=>$progress_list,'status_css'=>$status_css));
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
        $args['module_type'] = '2';
        $r = Train::AllCntList($args);
        print_r(json_encode($r));
    }
    /**
     *查询违规次数（公司）
     */
    public function actionCntByCompany() {
        $args['program_id'] = $_REQUEST['id'];
        $args['date'] = $_REQUEST['date'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $args['module_type'] = '2';
        $r = Train::CompanyCntList($args);
        print_r(json_encode($r));
    }

}
