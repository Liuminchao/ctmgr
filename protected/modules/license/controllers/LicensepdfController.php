<?php

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;

/**
 * 许可证下载
 * @author LiuMinChao
 */
class LicensepdfController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';
    const STATUS_NORMAL = 0; //正常

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('license_licensepdf', 'contentHeader');
        //$this->contentHeader = '';
        $this->bigMenu = Yii::t('license_licensepdf', 'bigMenu');
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=license/licensepdf/grid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('license_licensepdf', 'apply_id'), '', 'center');
        $t->set_header('PTW Serial No.', '', 'center');
        $t->set_header(Yii::t('license_licensepdf', 'company'), '', 'center');
        $t->set_header(Yii::t('license_licensepdf', 'ptw_title'), '', 'center');
        $t->set_header(Yii::t('license_licensepdf', 'ptw_type'), '', 'center');
        //$t->set_header(Yii::t('license_licensepdf', 'contractor_name'), '', '');
        $t->set_header(Yii::t('license_licensepdf', 'apply_date'), '', 'center');
        $t->set_header(Yii::t('license_licensepdf', 'status'), '', 'center');
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
        //$args['status'] = ApplyBasic::STATUS_FINISH;
        $operator_id = Yii::app()->user->id;
        $operator_tag = '0';
        $operator_model = Operator::model()->findByPk($operator_id);
        $operator_role = $operator_model->operator_role;
        if($operator_role == '01'){
            $user = Staff::userByPhone($operator_id);
            $user_model = Staff::model()->findByPk($user[0]['user_id']);
            $user_id = $user_model->user_id;
            $ptw_role = ProgramUser::SelfPtwRole($user_id,$args['program_id']);
            if($ptw_role[0]['ptw_role'] == '1' ){
                $operator_tag = '1';
            }else if($ptw_role[0]['ptw_role'] == '2'){
                $operator_tag = '2';
            }else if($ptw_role[0]['ptw_role'] == '3'){
                $operator_tag = '3';
            }else if($ptw_role[0]['ptw_role'] == '4'){
                $operator_tag = '4';
            }
        }
        $args['contractor_id'] = Yii::app()->user->contractor_id;
        $app_id = 'PTW';
        $pro_model = Program::model()->findByPk($args['program_id']);
        $pro_params = $pro_model->params;//项目参数
        if($pro_params != '0') {
            $pro_params = json_decode($pro_params, true);
            //判断是否是迁移的
            if (array_key_exists('ptw_mode', $pro_params)) {
                $ptw_mode = $pro_params['ptw_mode'];
            } else {
                $ptw_mode = 'A';
            }
        }else{
            $ptw_mode = 'A';
        }
        $list = ApplyBasic::newqueryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t,'program_id'=>$args['program_id'],'app_id'=>$app_id,'ptw_mode' => $ptw_mode,'status_tag' => $args['status'],'operator_tag' => $operator_tag,'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {
        $program_id = $_REQUEST['program_id'];
        $type_id = $_REQUEST['type_id'];
        $this->smallHeader = Yii::t('license_licensepdf', 'smallHeader List');
        $this->render('list',array('program_id'=> $program_id,'type_id'=>$type_id));
    }

    /**
     * 下载附件列表
     */
    public function actionDownloadAttachment() {
        $apply_id = $_REQUEST['apply_id'];
        $form_data_list = ApplyDocument::detailList($apply_id); //记录
        $this->renderPartial('download_attachment', array('apply_id'=>$apply_id,'form_data_list'=>$form_data_list));
    }


    /**
     * 详情
     */
    public static function actionStaffDownload() {
        $id = $_REQUEST['apply_id'];
        $tag = $_REQUEST['tag'];
        $params['tag'] = $tag;
        $params['id'] = $id;
        $app_id = $_REQUEST['app_id'];
        $apply = ApplyBasic::model()->findByPk($id);//许可证基本信息表
        $title = $apply->program_name;
        // if($apply->save_path){
        //     $file_path = $apply->save_path;
        //     $filepath = '/opt/www-nginx/web'.$file_path;
        // }else{
        //     $filepath = DownloadPdf::transferDownload($params,$app_id);
        // }
        //报告定制化
        $program_id = $apply->program_id;
        $pro_model = Program::model()->findByPk($program_id);
        $pro_params = $pro_model->params;//项目参数
        if($pro_params != '0') {
            $pro_params = json_decode($pro_params, true);
            //判断是否是迁移的
            if (array_key_exists('ptw_report', $pro_params)) {
                $params['type'] = $pro_params['ptw_report'];
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
     * 按承包商查询PTW类型
     */
    public function actionQueryType() {
        $program_id = $_POST['program_id'];

        $rows = PtwType::typeByContractor($program_id);

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
     * 下载列表
     */
    public function actionDownloadPreview() {
        $apply_id = $_REQUEST['apply_id'];
        $app_id = $_REQUEST['app_id'];
        $apply = ApplyBasic::model()->findByPk($apply_id);//许可证基本信息表
        $check_list = json_decode($apply->check_list,true);
        $apply_id = $apply->apply_id;
        // for($i=0;$i<=count($check_list)-1;$i++){
        //     var_dump($check_list[$i]);
        // }
        $this->renderPartial('download_preview', array('apply_id'=>$apply_id,'check_list' => $check_list,'app_id'=>$app_id));
    }
     /**
     * 预览流程图
     */
    public function actionPreview() {
        $flow_id = $_REQUEST['flow_id'];
        $apply_id = $_REQUEST['apply_id'];
        $app_id = $_REQUEST['app_id'];
        $apply = ApplyBasic::model()->findByPk($apply_id);//许可证基本信息表
        //报告定制化
        $program_id = $apply->program_id;
        $pro_model = Program::model()->findByPk($program_id);
        $pro_name = $pro_model->program_name;
        $pro_params = $pro_model->params;//项目参数
        if($pro_params != '0') {
            $pro_params = json_decode($pro_params, true);
            //判断是否是迁移的
            if (array_key_exists('ptw_report', $pro_params)) {
                $ptw_mode = $pro_params['ptw_mode'];
            } else {
                $ptw_mode = 'A';
            }
        }else{
            $ptw_mode = 'A';
        }
        //$app_id = 'PTW';
        $step_list = WorkflowDetail::stepList($flow_id);
        $year = substr($apply->record_time,0,4);//年
        $progress_list = CheckApplyDetail::progressList($app_id,$apply_id,$year);//审批进度流程
        $status_css = CheckApplyDetail::statusText();
        $result_text = CheckApplyDetail::resultText();
        $pending_text = CheckApplyDetail::pendingPtwText();
        $this->renderPartial('preview', array('step_list' => $step_list,'progress_list'=>$progress_list,'result_text' => $result_text,'pending_text'=>$pending_text[$ptw_mode],'status_css'=>$status_css));
    }

    /**
     * 项目统计图表
     */
    public function actionProjectChart() {
        $platform = $_REQUEST['platform'];
        $program_id = $_REQUEST['program_id'];
        $this->smallHeader = Yii::t('dboard', 'PTW Type Statistics');
        if(!is_null($platform)){
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
        if(!is_null($platform)){
            $this->layout = '//layouts/main_2';
            $this->render('statistical_company_dash',array('program_id'=>$program_id,'platform'=>$platform));
        }else{
            $this->render('statistical_company',array('program_id'=>$program_id));
        }
    }

    /**
     * 测试统计图表2019-03-05添加
     */
    public function actionTestChart() {
        $platform = $_REQUEST['platform'];
        $program_id = $_REQUEST['program_id'];
        $this->smallHeader = Yii::t('dboard', 'Test Charts');
        if(!is_null($platform)){
            $this->layout = '//layouts/main_2';
            $this->render('statistical_test_dash',array('program_id'=>$program_id,'platform'=>$platform));
        }else{
            $this->render('statistical_test',array('program_id'=>$program_id));
        }
    }
    /**
     *查询违规次数（项目）
     */
    public function actionCntByProject() {
        $args['program_id'] = $_REQUEST['id'];
        $args['date'] = $_REQUEST['date'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $r = ApplyBasic::AllCntList($args);
        print_r(json_encode($r));
    }
    /**
     *查询违规次数（公司）
     */
    public function actionCntByCompany() {
        $args['program_id'] = $_REQUEST['id'];
        $args['date'] = $_REQUEST['date'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $r = ApplyBasic::CompanyCntList($args);
        print_r(json_encode($r));
    }
    /**
     *查询违规次数（测试）cntbytest
     */
    public function actionCntByTest() {
        $args['program_id'] = $_REQUEST['id'];
        $args['date'] = $_REQUEST['date'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $r = ApplyBasic::TestCntList($args);
        print_r(json_encode($r));
    }

    /**
     * Month Report
     */
    public function actionMonthReport() {
        $program_id = $_REQUEST['program_id'];
        $this->renderPartial('month_report',array('program_id'=>$program_id));
    }

    /**
     * Month Batch Report
     */
    public function actionBatchMonthReport() {
        $program_id = $_REQUEST['program_id'];
        $this->renderPartial('batch_month_report',array('program_id'=>$program_id));
    }

    /**
     * 合并PDF
     */
    public function actionMergeReport() {

        $apply_id = $_REQUEST['apply_id'];
        $apply = ApplyBasic::model()->findByPk($apply_id);//许可证基本信息表
        //报告定制化
        $program_id = $apply->program_id;
        $year = substr($apply->record_time,0,4);//年
        $month = substr($apply->record_time,5,2);//月
        $day = substr($apply->record_time,8,2);//日
        $pro_model = Program::model()->findByPk($program_id);
        $pro_name = $pro_model->program_name;
        $pro_params = $pro_model->params;//项目参数
        if($pro_params != '0') {
            $pro_params = json_decode($pro_params, true);
            //判断是否是迁移的
            if (array_key_exists('ptw_report', $pro_params)) {
                $params['type'] = $pro_params['ptw_report'];
            } else {
                $params['type'] = 'A';
            }
        }else{
            $params['type'] = 'A';
        }

        $files = array();

        $app_id = 'PTW';
        $params['id'] = $apply_id;
        $filepath = DownloadPdf::transferDownload($params,$app_id);
        $files[] = '/opt/www-nginx/web'.$filepath;

        $checklist_str = $_REQUEST['checklist_str'];
        $checklist_arr = explode('|',$checklist_str);

        if($checklist_arr[0] != ''){
            foreach($checklist_arr as $k=>$check_id){
                //$check_id = $r['check_id'];
                $check_list = RoutineCheck::detailList($check_id);//例行检查单
                //if(!$check_list[0]['save_path']) {
                $params['check_id'] = $check_id;
                $app_id = 'ROUTINE';
                $filepath = DownloadPdf::transferDownload($params,$app_id);
                if(file_exists($filepath)){
                    $files[] = '/opt/www-nginx/web'.$filepath;
                }
                //}else{
                //  $files[] = '/opt/www-nginx/web'.$check_list[0]['save_path'];
                //}
            }
        }

        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'fpdf'.DIRECTORY_SEPARATOR.'fpdf.php';
        require_once($tcpdfPath);
        $fpdiPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'FPDI'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'autoload.php';
        //require_once($fpdfPath);
        require_once($fpdiPath);
        // define some files to concatenate
        //$files = array(
        //  '/opt/www-nginx/web/filebase/data/ra/146/qq.pdf',
        //  '/opt/www-nginx/web/filebase/data/ra/146/RA Installation of Gantry.pdf'
        //);

        // initiate FPDI
        $pdf = new Fpdi();

        // iterate through the files
        foreach ($files AS $file) {
            // get the page count
            $pageCount = $pdf->setSourceFile($file);
            // iterate through all pages
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                // import a page
                $templateId = $pdf->importPage($pageNo);
                // get the size of the imported page
                $size = $pdf->getTemplateSize($templateId);
                //print_r($size);break;
                // create a page (landscape or portrait depending on the imported page size)
                if ($size['width'] > $size['height']) {
                    $pdf->AddPage('L', array($size['width'], $size['height']));
                } else {
                    $pdf->AddPage('P', array($size['width'], $size['height']));
                }

                // use the imported page
                $pdf->useTemplate($templateId);

                $pdf->SetFont('Helvetica');
                $pdf->SetXY(5, 5);
                //$pdf->Write(8, 'A simple concatenation demo with FPDI');
            }
        }

        // Output the new PDF
        //$pdf->Output();     //直接输出
        $filepath = '/opt/www-nginx/web/filebase/tmp/Ptw Merge.pdf';
        $pdf->output($filepath, "F");   //下载到本地

        if (file_exists($filepath) == false) {
            header("Content-type:text/html;charset=utf-8");
            echo "<script>alert('".Yii::t('common','Document not found')."');</script>";
            return;
        }
        $file = fopen($filepath, "r"); // 打开文件
        header('Content-Encoding: none');
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Accept-Length: " . filesize($filepath));
        header('Content-Transfer-Encoding: binary');
        $name = $pro_name.'-PTW'.$year.$month.$day.'.pdf';
        header("Content-Disposition: attachment; filename=" . $name); //以真实文件名提供给浏览器下载
        header('Pragma: no-cache');
        header('Expires: 0');
        echo fread($file, filesize($filepath));
        fclose($file);
        if (!unlink($filepath))
        {
            echo ("Error deleting ");
        }
        else
        {
            echo ("Deleted successed");
        }
    }
    /**
     * 批量审批
     * button 状态 0 无 1 审批  2批准  3关闭审批 4关闭批准 5申请关闭  6驳回
     */
    //3+3&4+2 批量审批
    public function actionBatchAssessed() {
        $tag = $_REQUEST['tag'];
        $program_id = $_REQUEST['program_id'];
        $status = $_REQUEST['status'];
        $ptw_mode = $_REQUEST['ptw_mode'];
        $tag_list  = explode('|',$tag);
        $operator_id = Yii::app()->user->id;
        $user = Staff::userByPhone($operator_id);
        $user_model = Staff::model()->findByPk($user[0]['user_id']);
        $user_id = $user_model->user_id;
        foreach($tag_list as $k => $id){
            $data_1 = array(
                'uid' => $user_id,
                'token' => 'lalala',
                'apply_id' => $id,
                'program_id' => $program_id,
                'moduletype' => 'PTW'
            );
            $data_2 = array(
                'uid' => $user_id,
                'token' => 'lalala',
                'apply_id' => $id,
                'deal_user_id' => '',
                'longitude' => '',
                'latitude' => '',
                'address' => '',
                'pic' => '',
                'deal_result' => '1',
                'remark' => '',
                'check_list' => '',
                'program_id' => $program_id,
                'moduletype' => 'PTW'
            );
            foreach ($data_1 as $key => $value) {
                $post_data_1[$key] = $value;
            }
            foreach ($data_2 as $key => $value) {
                $post_data_2[$key] = $value;
            }
            $data_1 = json_encode($post_data_1);
            $data_2 = json_encode($post_data_2);
            $module = 'CMSC0413';
            $url = "https://127.0.0.1/dbcms/dbapi?cmd=".$module."";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, true); //post提交
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_1);
            // 3. 执行并获取HTML文档内容
            $output = curl_exec($ch);
            $result = json_decode($output,true);
            if($result['errno'] == 0 ){
                if($result['button'] == 1){
                    $module = 'CMSC0404';
                    $url = "https://127.0.0.1/dbcms/dbapi?cmd=".$module."";
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_POST, true); //post提交
                    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_2);
                    // 3. 执行并获取HTML文档内容
                    $output = curl_exec($ch);
                    $result = json_decode($output,true);
                }else{
                    goto end;
                }
            }
            if ($output === FALSE) {
                echo "cURL Error: " . curl_error($ch);
                return null;
            }
            // 4. 释放curl句柄
            curl_close($ch);
        }
        end:
        print_r(json_encode($result));
    }

    /**
     * 批量审批
     * button 状态 0 无 1 审批&审批人2  2批准  3关闭审批 4关闭批准 5申请关闭  6驳回
     */
    //4+2 批量审批2
    public function actionBatchAssessed2() {
        $tag = $_REQUEST['tag'];
        $program_id = $_REQUEST['program_id'];
        $status = $_REQUEST['status'];
        $ptw_mode = $_REQUEST['ptw_mode'];
        $tag_list  = explode('|',$tag);
        $operator_id = Yii::app()->user->id;
        $user = Staff::userByPhone($operator_id);
        $user_model = Staff::model()->findByPk($user[0]['user_id']);
        $user_id = $user_model->user_id;
        foreach($tag_list as $k => $id){
            $data_1 = array(
                'uid' => $user_id,
                'token' => 'lalala',
                'apply_id' => $id,
                'program_id' => $program_id,
                'moduletype' => 'PTW'
            );
            $data_2 = array(
                'uid' => $user_id,
                'token' => 'lalala',
                'apply_id' => $id,
                'deal_user_id' => '',
                'longitude' => '',
                'latitude' => '',
                'address' => '',
                'pic' => '',
                'deal_result' => '1',
                'remark' => '',
                'check_list' => '',
                'program_id' => $program_id,
                'moduletype' => 'PTW'
            );
            foreach ($data_1 as $key => $value) {
                $post_data_1[$key] = $value;
            }
            foreach ($data_2 as $key => $value) {
                $post_data_2[$key] = $value;
            }
            $data_1 = json_encode($post_data_1);
            $data_2 = json_encode($post_data_2);
            $module = 'CMSC0413';
            $url = "https://127.0.0.1/dbcms/dbapi?cmd=".$module."";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, true); //post提交
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_1);
            // 3. 执行并获取HTML文档内容
            $output = curl_exec($ch);
            $result = json_decode($output,true);
            if($result['errno'] == 0 ){
                if($result['button'] == 1){
                    $module = 'CMSC0404';
                    $url = "https://127.0.0.1/dbcms/dbapi?cmd=".$module."";
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_POST, true); //post提交
                    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_2);
                    // 3. 执行并获取HTML文档内容
                    $output = curl_exec($ch);
                    $result = json_decode($output,true);
                }else{
                    goto end;
                }
            }
            if ($output === FALSE) {
                echo "cURL Error: " . curl_error($ch);
                return null;
            }
            // 4. 释放curl句柄
            curl_close($ch);
        }
        end:
        print_r(json_encode($result));
    }

    //3+3&4+2  批量批准
    public function actionBatchApproved() {
        $tag = $_REQUEST['tag'];
        $program_id = $_REQUEST['program_id'];
        $status = $_REQUEST['status'];
        $ptw_mode = $_REQUEST['ptw_mode'];
        $tag_list  = explode('|',$tag);
        $operator_id = Yii::app()->user->id;
        $user = Staff::userByPhone($operator_id);
        $user_model = Staff::model()->findByPk($user[0]['user_id']);
        $user_id = $user_model->user_id;
        foreach($tag_list as $k => $id){
            $data_1 = array(
                'uid' => $user_id,
                'token' => 'lalala',
                'apply_id' => $id,
                'program_id' => $program_id,
                'moduletype' => 'PTW'
            );
            $data_2 = array(
                'uid' => $user_id,
                'token' => 'lalala',
                'apply_id' => $id,
                'deal_user_id' => '',
                'longitude' => '',
                'latitude' => '',
                'address' => '',
                'pic' => '',
                'deal_result' => '1',
                'remark' => '',
                'check_list' => '',
                'program_id' => $program_id,
                'moduletype' => 'PTW'
            );
            foreach ($data_1 as $key => $value) {
                $post_data_1[$key] = $value;
            }
            foreach ($data_2 as $key => $value) {
                $post_data_2[$key] = $value;
            }
            $data_1 = json_encode($post_data_1);
            $data_2 = json_encode($post_data_2);
            $module = 'CMSC0413';
            $url = "https://127.0.0.1/dbcms/dbapi?cmd=".$module."";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, true); //post提交
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_1);
            // 3. 执行并获取HTML文档内容
            $output = curl_exec($ch);
            $result = json_decode($output,true);
            if($result['errno'] == 0 ){
                if($result['button'] == 2){
                    $module = 'CMSC0404';
                    $url = "https://127.0.0.1/dbcms/dbapi?cmd=".$module."";
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_POST, true); //post提交
                    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_2);
                    // 3. 执行并获取HTML文档内容
                    $output = curl_exec($ch);
                    $result = json_decode($output,true);
                }else{
                    goto end;
                }
            }
            if ($output === FALSE) {
                echo "cURL Error: " . curl_error($ch);
                return null;
            }
            // 4. 释放curl句柄
            curl_close($ch);
        }
        end:
        print_r(json_encode($result));
    }

    /**
     * 批量审批
     * button 状态 0 无 1 审批  2批准  3关闭审批 4关闭批准 5申请关闭  6驳回
     */
    //3+3  批量关闭审批
    public function actionBatchOperateA() {
        $tag = $_REQUEST['tag'];
        $program_id = $_REQUEST['program_id'];
        $status = $_REQUEST['status'];
        $ptw_mode = $_REQUEST['ptw_mode'];
        $tag_list  = explode('|',$tag);
        $operator_id = Yii::app()->user->id;
        $user = Staff::userByPhone($operator_id);
        $user_model = Staff::model()->findByPk($user[0]['user_id']);
        $user_id = $user_model->user_id;
        foreach($tag_list as $k => $id){
            $data_1 = array(
                'uid' => $user_id,
                'token' => 'lalala',
                'apply_id' => $id,
                'program_id' => $program_id,
                'moduletype' => 'PTW'
            );
            $data_2 = array(
                'uid' => $user_id,
                'token' => 'lalala',
                'apply_id' => $id,
                'deal_user_id' => '',
                'longitude' => '',
                'latitude' => '',
                'address' => '',
                'pic' => '',
                'deal_result' => '1',
                'remark' => '',
                'check_list' => '',
                'program_id' => $program_id,
                'moduletype' => 'PTW'
            );
            foreach ($data_1 as $key => $value) {
                $post_data_1[$key] = $value;
            }
            foreach ($data_2 as $key => $value) {
                $post_data_2[$key] = $value;
            }
            $data_1 = json_encode($post_data_1);
            $data_2 = json_encode($post_data_2);
            $module = 'CMSC0413';
            $url = "https://127.0.0.1/dbcms/dbapi?cmd=".$module."";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, true); //post提交
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_1);
            // 3. 执行并获取HTML文档内容
            $output = curl_exec($ch);
            $result = json_decode($output,true);
            if($result['errno'] == 0 ){
                if($result['button'] == 3){
                    $module = 'CMSC0404';
                    $url = "https://127.0.0.1/dbcms/dbapi?cmd=".$module."";
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_POST, true); //post提交
                    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_2);
                    // 3. 执行并获取HTML文档内容
                    $output = curl_exec($ch);
                    $result = json_decode($output,true);
                }else{
                    goto end;
                }
            }
            if ($output === FALSE) {
                echo "cURL Error: " . curl_error($ch);
                return null;
            }
            // 4. 释放curl句柄
            curl_close($ch);
        }
        end:
        print_r(json_encode($result));
    }
    //3+3  批量关闭批准
    public function actionBatchOperateB() {
        $tag = $_REQUEST['tag'];
        $program_id = $_REQUEST['program_id'];
        $status = $_REQUEST['status'];
        $ptw_mode = $_REQUEST['ptw_mode'];
        $tag_list  = explode('|',$tag);
        $operator_id = Yii::app()->user->id;
        $user = Staff::userByPhone($operator_id);
        $user_model = Staff::model()->findByPk($user[0]['user_id']);
        $user_id = $user_model->user_id;
        foreach($tag_list as $k => $id){
            $data_1 = array(
                'uid' => $user_id,
                'token' => 'lalala',
                'apply_id' => $id,
                'program_id' => $program_id,
                'moduletype' => 'PTW'
            );
            $data_2 = array(
                'uid' => $user_id,
                'token' => 'lalala',
                'apply_id' => $id,
                'deal_user_id' => '',
                'longitude' => '',
                'latitude' => '',
                'address' => '',
                'pic' => '',
                'deal_result' => '1',
                'remark' => '',
                'check_list' => '',
                'program_id' => $program_id,
                'moduletype' => 'PTW'
            );
            foreach ($data_1 as $key => $value) {
                $post_data_1[$key] = $value;
            }
            foreach ($data_2 as $key => $value) {
                $post_data_2[$key] = $value;
            }
            $data_1 = json_encode($post_data_1);
            $data_2 = json_encode($post_data_2);
            $module = 'CMSC0413';
            $url = "https://127.0.0.1/dbcms/dbapi?cmd=".$module."";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, true); //post提交
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_1);
            // 3. 执行并获取HTML文档内容
            $output = curl_exec($ch);
            $result = json_decode($output,true);
            if($result['errno'] == 0 ){
                if($result['button'] == 4){
                    $module = 'CMSC0404';
                    $url = "https://127.0.0.1/dbcms/dbapi?cmd=".$module."";
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_POST, true); //post提交
                    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_2);
                    // 3. 执行并获取HTML文档内容
                    $output = curl_exec($ch);
                    $result = json_decode($output,true);
                }else{
                    goto end;
                }
            }
            if ($output === FALSE) {
                echo "cURL Error: " . curl_error($ch);
                return null;
            }
            // 4. 释放curl句柄
            curl_close($ch);
        }
        end:
        print_r(json_encode($result));
    }
    //4+2  批量关闭批准
    public function actionBatchOperateC() {
        $tag = $_REQUEST['tag'];
        $program_id = $_REQUEST['program_id'];
        $status = $_REQUEST['status'];
        $ptw_mode = $_REQUEST['ptw_mode'];
        $tag_list  = explode('|',$tag);
        $operator_id = Yii::app()->user->id;
        $user = Staff::userByPhone($operator_id);
        $user_model = Staff::model()->findByPk($user[0]['user_id']);
        $user_id = $user_model->user_id;
        foreach($tag_list as $k => $id){
            $data_1 = array(
                'uid' => $user_id,
                'token' => 'lalala',
                'apply_id' => $id,
                'program_id' => $program_id,
                'moduletype' => 'PTW'
            );
            $data_2 = array(
                'uid' => $user_id,
                'token' => 'lalala',
                'apply_id' => $id,
                'deal_user_id' => '',
                'longitude' => '',
                'latitude' => '',
                'address' => '',
                'pic' => '',
                'deal_result' => '1',
                'remark' => '',
                'check_list' => '',
                'program_id' => $program_id,
                'moduletype' => 'PTW'
            );
            foreach ($data_1 as $key => $value) {
                $post_data_1[$key] = $value;
            }
            foreach ($data_2 as $key => $value) {
                $post_data_2[$key] = $value;
            }
            $data_1 = json_encode($post_data_1);
            $data_2 = json_encode($post_data_2);
            $module = 'CMSC0413';
            $url = "https://127.0.0.1/dbcms/dbapi?cmd=".$module."";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, true); //post提交
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_1);
            // 3. 执行并获取HTML文档内容
            $output = curl_exec($ch);
            $result = json_decode($output,true);
            if($result['errno'] == 0 ){
                if($result['button'] == 3){
                    $module = 'CMSC0404';
                    $url = "https://127.0.0.1/dbcms/dbapi?cmd=".$module."";
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_POST, true); //post提交
                    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_2);
                    // 3. 执行并获取HTML文档内容
                    $output = curl_exec($ch);
                    $result = json_decode($output,true);
                }else{
                    goto end;
                }
            }
            if ($output === FALSE) {
                echo "cURL Error: " . curl_error($ch);
                return null;
            }
            // 4. 释放curl句柄
            curl_close($ch);
        }
        end:
        print_r(json_encode($result));
    }

    /**
     * 记录批量
     */
    public static function actionUserBatch() {
        $tag = $_REQUEST['tag'];
        $startrow = $_REQUEST['startrow'];
        $per_read_cnt = $_REQUEST['per_read_cnt'];
        $program_id = $_REQUEST['id'];
        $array = explode('|',$tag);
        $array=array_slice($array,$startrow,$per_read_cnt);
        foreach($array as $cnt => $id) {
            $params['id'] = $id;
            $app_id = 'PTW';
            $apply = ApplyBasic::model()->findByPk($id);//许可证基本信息表
            $title = $apply->program_name;

            //报告定制化
            $program_id = $apply->program_id;
            $pro_model = Program::model()->findByPk($program_id);
            $pro_params = $pro_model->params;//项目参数
            if($pro_params != '0') {
                $pro_params = json_decode($pro_params, true);
                //判断是否是迁移的
                if (array_key_exists('ptw_report', $pro_params)) {
                    $params['type'] = $pro_params['ptw_report'];
                } else {
                    $params['type'] = 'A';
                }
            }else{
                $params['type'] = 'A';
            }
            $params['ftp'] = '1';
            $filepath = DownloadPdf::transferDownload($params,$app_id);
            $redis = new Redis();
            $redis->connect('127.0.0.1', 6379);
            $redis->select(7);
            $filepath_cnt = $redis->lPush('file-list', $filepath);
            $redis->set('filepath_cnt', $filepath_cnt);
        }
        $r['startrow'] = $startrow;
        echo json_encode($r);
    }
    /**
     * 下载入场压缩包
     */
    public static function actionCompress(){
        $curpage = $_REQUEST['curpage'];
        $filename = "/opt/www-nginx/web/filebase/tmp/ptw_bak".$curpage.".zip";
        if (!file_exists($filename)) {
            $zip = new ZipArchive();//使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释
            //$zip->open($filename,ZipArchive::CREATE);//创建一个空的zip文件
            if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
                //如果是Linux系统，需要保证服务器开放了文件写权限
                exit("文件打开失败!");
            }
            $redis = new Redis();
            $redis->connect('127.0.0.1', 6379);
            $redis->select(7);
            $filepath_cnt = $redis->get('filepath_cnt');
            $x = 0;
            for($j=0;$j<=$filepath_cnt;$j++){
                $path = $redis->lPop('file-list');
                if (file_exists($path)) {
                    $file[$x] = $path;
                    $zip->addFile($path, basename($path));//第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下
                    $x++;
                }
            }
            $zip->close();
        }
        if(count($file) > 0){
            foreach ($file as $cnt => $path) {
                if (file_exists($path)) {
                    unlink($path);
                }
            }
        }
        if (file_exists($filename) == false) {
            header("Content-type:text/html;charset=utf-8");
            echo "<script>alert('".Yii::t('common','Document not found')."');</script>";
            return;
        }
        $file = fopen($filename, "r"); // 打开文件
        header('Content-Encoding: none');
        header("Content-type: application/zip");
        header("Accept-Ranges: bytes");
        header("Accept-Length: " . filesize($filename));
        header('Content-Transfer-Encoding: binary');
        $name = basename($filename);
        header("Content-Disposition: attachment; filename=" . $name); //以真实文件名提供给浏览器下载
        header('Pragma: no-cache');
        header('Expires: 0');
        echo fread($file, filesize($filename));
        fclose($file);
        unlink($filename);
    }

    /**
     * 读取模型全部信息
     */
    public function actionCreateQrPdf() {

        $startrow = $_REQUEST['startrow'];
        $per_read_cnt = $_REQUEST['per_read_cnt'];
        $tag = $_REQUEST['tag'];
        $program_id = $_REQUEST['program_id'];
        $typeList = ApplyBasic::typeList();
        $company_list = Contractor::compAllList();//承包商公司列表
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->select(7);
        $rs = explode('|',$tag);
        $pagedata=array_slice($rs,$startrow,$per_read_cnt);

        if(count($pagedata)>0){
            foreach ($pagedata as $i => $j){
                $result[] =$j;
            }
            $file_path = ApplyBasic::downloadQrPdf($result,$program_id);
            $filepath_cnt = $redis->lPush('ptw-list', $file_path);
            $redis->set('ptw_cnt', $filepath_cnt);
            $r['file_path'] = $file_path;
        }else{
            $r['file_path'] = '';
        }
        $i = 0;
        $redis->close();
        print_r(json_encode($r));
    }

    /**
     * 下载压缩包，清除redis缓存，列表
     */
    public function actionDownloadQrZip() {
        $filename = ApplyBasic::createPbuZip();
        if (file_exists($filename) == false) {
            header("Content-type:text/html;charset=utf-8");
            echo "<script>alert('".Yii::t('common','Document not found')."');</script>";
            return;
        }
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->select(7);
        $redis->delete('ptw-list');
        $redis->delete('ptw_cnt');
        $redis->close();

        $file = fopen($filename, "r"); // 打开文件
        header('Content-Encoding: none');
        header("Content-type: application/zip");
        header("Accept-Ranges: bytes");
        header("Accept-Length: " . filesize($filename));
        header('Content-Transfer-Encoding: binary');
        $name = basename($filename);
        header("Content-Disposition: attachment; filename=" . $name); //以真实文件名提供给浏览器下载
        header('Pragma: no-cache');
        header('Expires: 0');
        echo fread($file, filesize($filename));
        fclose($file);
        unlink($filename);
    }

    public function actionDisclaimer(){
        $tag = $_REQUEST['tag'];
        $status = $_REQUEST['status'];
        $program_id = $_REQUEST['program_id'];
        $app = $_REQUEST['app'];
        $this->renderPartial('disclaimer', array('tag'=>$tag,'status'=>$status,'program_id'=>$program_id,'app'=>$app));
    }
}
