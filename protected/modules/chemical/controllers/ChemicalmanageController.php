<?php

class ChemicalmanageController extends AuthBaseController {
    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('chemical', 'bigMenu');
        $this->bigMenu = Yii::t('chemical', 'contentHeader');
    }
    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=chemical/chemicalmanage/grid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('chemical', 'chemical_type'), '', 'center');
        $t->set_header(Yii::t('chemical', 'chemical_id'), '', 'center');
        $t->set_header(Yii::t('chemical', 'chemical_name'), '', 'center');
//        $t->set_header(Yii::t('chemical', 'permit_startdate'), '', '');
//        $t->set_header(Yii::t('chemical', 'permit_enddate'), '', '');
        $t->set_header(Yii::t('chemical', 'status'), '', 'center');
        $t->set_header(Yii::t('chemical', 'record_time'), '', 'center');
        $t->set_header(Yii::t('comp_staff', 'Action'), '', 'center');
        return $t;
    }
    /**
     * 查询
     */
    public function actionGrid() {
        //var_dump($_GET['page']);
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
//        var_dump($args);
//        exit;
	if($args['status'] == ''){
            $args['status'] = '00';
	}	
        $t = $this->genDataGrid();
        $this->saveUrl();
//        $args['contractor_type'] = Staff::CONTRACTOR_TYPE_SC;
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
//        var_dump($args);
        
        $list = Chemical::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'],'info'=> $info, 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }
    public function actionList() {
        $this->smallHeader = Yii::t('chemical', 'smallHeader List');
        $this->render('list');
    }
    
    /**
     * 添加
     */
    public function actionNew() {

        $this->smallHeader = Yii::t('chemical', 'smallHeader New');
        $model = new Chemical('create');
        $r = array();
        if (isset($_POST['Chemical'])) {
//
            $args = $_POST['Chemical'];
            $old_file = $_POST['File'];
//            var_dump($_FILES['Chemical']);
//            exit;
//          //判断设备类型不为空
            if($args['type_no']==0){
                $r['status'] = '-1';
                $r['msg'] = Yii::t('chemical','Error Equipment_type is null');
                $r['refresh'] = false;
                goto end;
            }            
            //判断文件是不是为空
            if ($old_file['chemical_src'] == ''){
                $r['status'] = '-1';
                $r['msg'] = Yii::t('chemical','Error Upload_pic is null');
                $r['refresh'] = false;
                goto end;
            }
            if ($old_file['chemical_src'] <> ''){
                $rs = Compress::uploadPicture($old_file, Yii::app()->user->getState('contractor_id'));
//		var_dump($rs);
//                exit;
                $r['status'] = '';
                $r['msg'] = '';
                foreach($rs as $key => $row){
                    if($row['code'] <> 0){
                        $r['status']  .= $row['code'];
                        $r['msg']  .= $row['msg'].' ';
                    }else{
                        if($key == 'chemical_src') {
                            $args['chemical_img'] = substr($row['upload_file'],18);
                        }
                    }
                }//var_dump($r);
                if($r['status'] <> ''){
                    $r['refresh'] = false;
                    goto end;
//                    return $r;
                }
            }
            $r = Chemical::insertChemical($args);
            end:
//            $args['TYPE'] = 'MC';
//            $args['add_conid'] = Yii::app()->user->getState('contractor_id');
//            $args['add_operator'] = Yii::app()->user->id;
//            $r = Program::insertProgram($args);
//            var_dump($args);
            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['Chemical'];
            }
        }

        $this->render('new', array('model' => $model,'msg' => $r));
    }
    /**
     * 修改
     */
    public function actionEdit() {
        $this->smallHeader = Yii::t('chemical', 'smallHeader Edit');
        $model = new Chemical('modify');
        $r = array();
//        $chemical_id = $_REQUEST['chemical_id'];
        $primary_id = $_REQUEST['primary_id'];
        $chemical_model = Chemical::model()->findByPk($primary_id);
        $chemical_id = $chemical_model->chemical_id;
        $contractor_id = Yii::app()->user->getState('contractor_id');

        if (isset($_POST['Chemical'])) {
            $args = $_POST['Chemical'];
            $old_file = $_POST['File'];
            
            if ($old_file['chemical_src'] <> ''){
                $rs = Compress::uploadPicture($old_file, Yii::app()->user->getState('contractor_id'));
//		var_dump($rs);
//                exit;
                $r['status'] = '';
                $r['msg'] = '';
                foreach($rs as $key => $row){
                    if($row['code'] <> 0){
                        $r['status']  .= $row['code'];
                        $r['msg']  .= $row['msg'].' ';
                    }else{
                        if($key == 'chemical_src') {
                            $args['chemical_img'] = substr($row['upload_file'],18);
                        }
                    }
                }//var_dump($r);
                if($r['status'] <> ''){
                    $r['refresh'] = false;
                    goto end;
//                    return $r;
                }
            }
            $r = Chemical::updateChemical($args,$chemical_id);
            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['Chemical'];
            }
        }
        end:
        $model->_attributes = Chemical::model()->findByPk($primary_id);

        $this->render('edit', array('model' => $model, 'msg' => $r));
    }
        /**
     * 详细
     */
    public function actionDetail() {
        
    	$id = $_POST['id'];
        $model = Chemical::model()->find('chemical_id=:chemical_id',array(':chemical_id'=>$id));
        $primary_id = $model->primary_id;
        $program_list = ProgramChemical::ChemicalProgramName($primary_id);
        $program_detail = '';
        if($program_list){
            foreach($program_list as $cnt=>$list){
                $program_detail .= $list['program_name'].',';
            }
            $program_detail = substr($program_detail,0,-1);
        }
    	$msg['status'] = true;
        $chemical_list = ChemicalType::chemicalList();
    	if ($model) {
    		$msg['detail'] .= "<table class='detailtab'>";
    		$msg['detail'] .= "<tr>";
				$msg['detail'] .= "<td class='tname-1'>" . $model->getAttributeLabel('chemical_id') . "</td>";
				$msg['detail'] .= "<td class='tvalue-3'>" . (isset($model->chemical_id) ? $model->chemical_id : "") . "</td>";
				$msg['detail'] .= "<td class='tname-1'>" . $model->getAttributeLabel('chemical_name') . "</td>";
				$msg['detail'] .= "<td class='tvalue-3'>" . (isset($model->chemical_name) ? $model->chemical_name : "") . "</td>";
				$msg['detail'] .= "<td class='tname-1'>" . $model->getAttributeLabel('type_no') . "</td>";
				$msg['detail'] .= "<td class='tvalue-3 detail_phone'>" . (isset($model->type_no) ? $chemical_list[$model->type_no]: "") ."</td>";
    		$msg['detail'] .= "</tr>";

            $msg['detail'] .= "<tr>";
            $msg['detail'] .= "<td class='tname-1'>" . Yii::t('comp_staff', 'where the project') . "</td>";
            $msg['detail'] .= "<td class='tvalue-3' colspan='4'>" . $program_detail . "</td>";

            $msg['detail'] .= "</tr>";
			
    		$msg['detail'] .= "<tr>";
				//$msg['detail'] .= "<td class='tname-1'>" .$model->getAttributeLabel('permit_startdate') . "</td>";
                //$msg['detail'] .="<td class='tvalue-3'>" .  (isset($model->permit_startdate) ? Utils::DateToEn($model->permit_startdate): "") . "</td>";
				//$msg['detail'] .= "<td class='tname-1'>" .$model->getAttributeLabel('permit_enddate') . "</td>";
                //$msg['detail'] .="<td class='tvalue-3'>" .  (isset($model->permit_enddate) ? Utils::DateToEn($model->permit_enddate): "") . "</td>";
				$msg['detail'] .= "<td class='tname-1'>" .$model->getAttributeLabel('record_time') . "</td>";
				$msg['detail'] .="<td class='tvalue-3'>" .  (isset($model->record_time) ? Utils::DateToEn($model->record_time) : "") . "</td>";
			$msg['detail'] .= "</tr>";
			
//    		$msg['detail'] .= "<tr>";
//    	//	$msg['detail'] .= "<td class='tname-2'>" . $model->getAttributeLabel('team_id') . "</td>";
//    	//	$msg['detail'] .= "<td class='tvalue-4'>" . (isset($model->team_id) ? $model->team_id : "") . "</td>";
//				$msg['detail'] .= "<td class='tname-1'>" . $model->getAttributeLabel('role_id') . "</td>";
//				$msg['detail'] .= "<td class='tvalue-3'>" . (isset($model->role_id) ? $roleList[$model->role_id] : "") . "</td>";
//
//            $msg['detail'] .= "</tr>";    		
			$msg['detail'] .= "</table>";
    		print_r(json_encode($msg));
    	}
    }
    /**
     * 注销
     */
    public function actionLogout() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = Chemical::logoutChemical($id);
        }
        //var_dump($r);
        echo json_encode($r);
    }
     /**
     * 保存查询链接
     */
    private function saveUrl() {
        $a = Yii::app()->session['list_url'];
        $a['chemical/list'] = str_replace("r=chemical/chemicalmanage/grid", "r=chemical/chemicalmanage/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }
    
    /**
     * 资质证件照列表
     */
    public function actionAttachlist() {
//        var_dump($_SERVER['HTTPS']);
//        exit;
        $chemical_id = $_GET['chemical_id'];
        $primary_id = $_GET['primary_id'];
        if($_GET['type']){
            $type = $_GET['type'];
        }else{
            $type = 'mc';
        }
        if($primary_id <> '')
            $father_model = Chemical::model()->findByPk($primary_id);
//        var_dump($father_model);
//        exit;
        $this->smallHeader = $father_model->chemical_name;
        $this->contentHeader = Yii::t('chemical', 'Equipment_certificate');
        $this->bigMenu = $father_model->chemical_name;
        $this->render('attachlist',array('chemical_id'=>$chemical_id,'primary_id'=>$primary_id,'type'=>$type));
    }
    
    /**
     * 资质证件照表头
     */
    private function genAttachDataGrid($chemical_id,$primary_id) {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=chemical/chemicalmanage/attachgrid&chemical_id='.$chemical_id.'&primary_id='.$primary_id;
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('comp_staff', 'document_name'), '', '');
        $t->set_header(Yii::t('comp_staff', 'commonly_used'), '', '');
        $t->set_header(Yii::t('comp_staff', 'certificate_startdate'),'','');
        $t->set_header(Yii::t('comp_staff', 'certificate_enddate'), '', '');
        $t->set_header(Yii::t('sys_operator', 'Record Time'), '', '');
        $t->set_header(Yii::t('comp_staff', 'Action'), '', '');
        return $t;
    }
    
    /**
     * 资质证件照查询
     */
    public function actionAttachGrid() {
        
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件

//        $args = array();
        if($args['chemical_id'] == ''){
            $args['chemical_id'] = $_GET['chemical_id'];   
        }
        if($args['primary_id'] == ''){
            $args['primary_id'] = $_GET['primary_id'];
        }
        if(isset($_GET['type'])){
            $args['type'] = $_GET['type'];
        }
        if(array_key_exists('type',$args)){
            $type = $args['type'];
        }else{
            $type = 'mc';
        }
        $primary_id = $args['primary_id'];
        $chemical_id = $args['chemical_id'];
        
        $t = $this->genAttachDataGrid($chemical_id,$primary_id);
        //$this->saveUrl();
//        $args['contractor_id'] = Yii::app()->user->contractor_id;
//        var_dump($_GET['program_id']);
//        exit;
        $list = ChemicalInfo::queryList($page, $this->pageSize, $args);

        $this->renderPartial('attach_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'],'chemical_id'=> $chemical_id,'curpage' => $list['page_num'],'type'=>$type));
    }
    /**
     * 资质证件上传
     */
    public function actionUpload() {
        $args = $_GET['q'];
        $chemical_id = $args['chemical_id'];
        $primary_id = $args['primary_id'];
//        var_dump($task_id);
//        var_dump($program_id);
//        exit;
        $model = new ChemicalInfo('create');
        $this->render('upload',array('model'=> $model,'chemical_id'=>$chemical_id,'primary_id'=>$primary_id, '_mode_' => 'insert'));
    }
    /**
     * 资质证件上传（修改）
     */
    public function actionDisplayUpload() {
        $chemical_id = $_REQUEST['chemical_id'];
        $id = $_REQUEST['id'];
        $primary_id = $_REQUEST['primary_id'];
        $model = new ChemicalInfo('modify');
        $model->_attributes = ChemicalInfo::model()->find('id=:id', array(':id'=>$id));
        $this->render('upload',array('model'=> $model,'chemical_id'=>$chemical_id,'id'=>$id,'primary_id'=>$primary_id,'_mode_'=>'edit'));
    }
    /**
     * 资质证件删除
     */
    public function actionDeleteAptitude() {
        $args = array();
        $args['str'] = $_REQUEST['str'];
        $args['chemical_id'] = $_REQUEST['chemical_id'];
        $args['type_no'] = $_REQUEST['type_no'];
        $r = ChemicalInfo::deleteAttach($args);
        print_r(json_encode($r));
    }
    /**
     * 将上传的图片移动到正式路径下
     */
//    public function actionMovePic() {
//        $file_src = $_REQUEST['file_src'];
//        $r = ChemicalInfo::movePic($file_src);
//        print_r(json_encode($r));
//    }
    public function actionMovePic() {
//        $file_src = $_REQUEST['file_src'];
        $args = array();
        $args = $_POST['ChemicalInfo'];
        $file_src = $args['tmp_src'];
        $r = ChemicalInfo::movePic($file_src);
//        var_dump($args);
//        exit;
        if($r['status'] == 1){
            $args['certificate_photo'] = $r['src'];

            $name = substr($r['src'],25);
            $src = '/opt/www-nginx/web'.$r['src'];
//            var_dump($name);
//            var_dump($src);
//            exit;
            $file_name = explode('.',$name);
            $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
            $size = filesize($src)/1024;
            $args['certificate_size'] = sprintf('%.2f',$size);
//            $model->doc_path = substr($upload_file,18);
            $args['certificate_title'] = $file_name[0];
            $args['type'] = $file_name[1];
//            var_dump($args);
//            exit;
            if($args['mode'] == 'insert'){
                $r = ChemicalInfo::insertAttach($args);
            }else{
                $r = ChemicalInfo::updateAttach($args);
            }

            print_r(json_encode($r));
        }else{
            print_r(json_encode($r));
        }

    }
    /**
     * 删除上传图片
     */
    public function actionDelPic() {
        $src = $_REQUEST['src'];
        $r = UserAptitude::deletePic($src);
        print_r(json_encode($r));
    }

    /**
     * 设置常用
     */
    public function actionSetused() {
        $id = trim($_REQUEST['id']);
        $certificate_use = trim($_REQUEST['certificate_use']);
        $r = array();
        $r = ChemicalInfo::setUsed($id,$certificate_use);
        //var_dump($r);
        echo json_encode($r);
    }
    /**
     * 在线预览文件
     */
    public function actionPreview() {
        $certificate_photo = $_REQUEST['certificate_photo'];
        $chemical_id = $_REQUEST['chemical_id'];
//        var_dump($file_path);
//        exit;
        $this->renderPartial('preview',array('certificate_photo'=>$certificate_photo,'chemical_id'=>$chemical_id));
    }
    /**
     * 删除文档
     */
    public function actionDelete() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = ChemicalInfo::deleteFile($id);
        }
        //var_dump($r);
        echo json_encode($r);
    }

    /**
     * 下载文档
     */
    public function actionDownload() {
        $certificate_photo = $_REQUEST['certificate_photo'];
        $filepath = '/opt/www-nginx/web'.$certificate_photo;
        if(file_exists($filepath)){
            $file_name = explode('.',$certificate_photo);
            $show_name = $file_name[0];
            $filepath = '/opt/www-nginx/web'.$certificate_photo;
            $extend = $file_name[1];
            Utils::Download($filepath, $show_name, $extend);
            return;
        }
    }
    /**
     * 在线预览文件
     */
    public function actionPreviewPrint() {
        $primary_id = $_REQUEST['primary_id'];
//        var_dump($file_path);
//        exit;
        $this->renderPartial('print_qrcode',array('primary_id'=>$primary_id));
    }
    /**
     * 轮播图片
     */
    public function actionShowPic() {
        $pic_str = $_REQUEST['pic_str'];
        $chemical_id = $_REQUEST['chemical_id'];
        $type_no = $_REQUEST['type_no'];
        $primary_id = $_REQUEST['primary_id'];
        $this->render('pic_show',array('pic_str'=>$pic_str,'chemical_id'=>$chemical_id,'type_no'=>$type_no,'primary_id'=>$primary_id));
    }
    /**
     * 编辑资质证件照和内容
     */
    public function actionEditAptitude() {
        $args = array();
        $args = $_POST['ChemicalInfo'];
        $file_src = $args['tmp_src'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $args['permit_startdate'] = Utils::DateToCn($args['permit_startdate']);
        $args['permit_enddate'] = Utils::DateToCn($args['permit_enddate']);
//        var_dump($args);
//        exit;
        $r = ChemicalInfo::updateAttach($args);
        print_r(json_encode($r));
    }

    /**
     * 设备统计信息
     */
    public function actionStatistics() {
        $this->contentHeader = Yii::t('chemical', 'Statistics contentHeader');
        $this->smallHeader = Yii::t('chemical', 'Statistics contentHeader');
        $chemical_id = $_REQUEST['chemical_id'];
        $primary_id = $_REQUEST['primary_id'];
        $contractor_id = Yii::app()->user->getState('contractor_id');

        $this->render('statisticslist',array('chemical_id' => $chemical_id,'primary_id'=>$primary_id));
    }

    /**
     * 人员信息局部刷新
     */
    public function actionSelfGrid($chemical_id) {

        $args = array();
        if($chemical_id == ''){
            $chemical_id = $_REQUEST['chemical_id'];
        }
        $contractor_id = Yii::app()->user->contractor_id;
        if($_REQUEST['program_id']){
            $program_id = $_REQUEST['program_id'];
        }else{
            $program_id = '';
        }
        $this->render('statistics_toolBox', array('chemical_id'=> $chemical_id,'contractor_id'=>$contractor_id,'program_id'=>$program_id));
    }

    /**
     * PTW按类别统计次数
     */
    public function actionPtwRole() {
        $chemical_id = $_REQUEST['chemical_id'];
        $program_id = $_REQUEST['program_id'];
        $ptw_role = ApplyBasic::chemicalByType($chemical_id,$program_id);//PTW按权限统计次数
        print_r(json_encode($ptw_role));
    }

    /**
     * PTW统计总次数
     */
    public function actionPtwCnt() {
        $chemical_id = $_REQUEST['chemical_id'];
        $program_id = $_REQUEST['program_id'];
        $ptw_cnt = ApplyBasic::chemicalByAll($chemical_id,$program_id);//PTW按权限统计次数
        print_r(json_encode($ptw_cnt));
    }

    /**
     * INSPECTION按权限/成员统计次数
     */
    public function actionInspectionRole() {
        $chemical_id = $_REQUEST['chemical_id'];
        $program_id = $_REQUEST['program_id'];
        $inspection_role = SafetyCheck::chemicalByMember($chemical_id,$program_id);//INSPECTION按权限统计次数
        print_r(json_encode($inspection_role));
    }

    /**
     * INSPECTION统计总次数
     */
    public function actionInspectionCnt() {
        $chemical_id = $_REQUEST['chemical_id'];
        $program_id = $_REQUEST['program_id'];
        $inspection_cnt = SafetyCheck::chemicalByAll($chemical_id,$program_id);//INSPECTION按权限统计次数
        print_r(json_encode($inspection_cnt));
    }

    //导出设备信息表
    public static function actionChemicalExport(){
        $args = $_GET['q'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $chemical = Chemical::chemicalExport($args);
        if (count($chemical) > 0) {
            foreach ($chemical as $key => $row) {
                $rs[$row['primary_id']] = $row['primary_id'];
            }
        }
        $chemicalinfo = ChemicalInfo::chemicalinfoExport($rs);
//        var_dump($chemicalinfo);
//        exit;
//        $rs = ProgramChemical::chemicalinfo($args);
        $typeList = ChemicalType::chemicalList();//设备型号列表

        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        $objectPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objectPHPExcel->getActiveSheet();
        $objActSheet->setTitle('Sheet1');

        //报表头的输出
        $objectPHPExcel->getActiveSheet()->mergeCells('A1'.':'.'I1');
        $objectPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->setCellValue('A1',Yii::t('proj_project_chemical','project_chemical_excel'));
        $objStyleA1 = $objActSheet->getStyle('A1');
        //字体及颜色
        $objFontA1 = $objStyleA1->getFont();
        $objFontA1->setSize(20);
        $objectPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->mergeCells('A2'.':'.'I2');
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2',Yii::t('proj_project_user','program_name').'：'.date("d M Y"));
        $objectPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('A3',Yii::t('chemical','chemical_img'));
        $objectPHPExcel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('B3',Yii::t('chemical','chemical_name'));
        $objectPHPExcel->getActiveSheet()->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('C3',Yii::t('chemical','chemical_id'));
        $objectPHPExcel->getActiveSheet()->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('D3',Yii::t('chemical','chemical_type'));
        $objectPHPExcel->getActiveSheet()->getStyle('E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('E3',Yii::t('comp_staff','qr_code'));
        $objectPHPExcel->getActiveSheet()->getStyle('F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('F3',Yii::t('comp_staff','aptitude_content'));
        $objectPHPExcel->getActiveSheet()->getStyle('G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('G3',Yii::t('comp_staff', 'certificate_startdate'));
        $objectPHPExcel->getActiveSheet()->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('H3',Yii::t('comp_staff', 'certificate_enddate'));
//        //设置颜色
//        $objectPHPExcel->getActiveSheet()->getStyle('AP3')->getFill()
//            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');
        //写入数据
//        var_dump($rs);
//        exit;
        $i = 0;
        $total_num = count($chemical) + count($chemicalinfo); //总行数
        for($p =4;$p<=$total_num+3;$p++) {
            $objectPHPExcel->getActiveSheet()->getRowDimension($p)->setRowHeight(90);
        }
        foreach ($chemical as $k => $v) {
//            var_dump($chemical);
//            exit;
            $mergeCells = count($chemicalinfo[$v['primary_id']]);
//                static $n = 1;

            if($mergeCells >1){
                $mergeCells =$mergeCells-1;
                $objectPHPExcel->getActiveSheet()->mergeCells('A'.($i+4).':'.'A'.($i+4+$mergeCells));
                $objectPHPExcel->getActiveSheet()->mergeCells('B'.($i+4).':'.'B'.($i+4+$mergeCells));
                $objectPHPExcel->getActiveSheet()->mergeCells('C'.($i+4).':'.'C'.($i+4+$mergeCells));
                $objectPHPExcel->getActiveSheet()->mergeCells('D'.($i+4).':'.'D'.($i+4+$mergeCells));
                $objectPHPExcel->getActiveSheet()->mergeCells('E'.($i+4).':'.'E'.($i+4+$mergeCells));
            }else{
                $mergeCells = 0;
            }
            /*设置表格高度*/

            if($v['chemical_img'] !=''){
                if(substr($v['chemical_img'],0,1) != '.') {
                    /*实例化excel图片处理类*/
                    $objDrawing = new PHPExcel_Worksheet_Drawing();
                    /*设置图片路径:只能是本地图片*/
//                        var_dump($value['field']);
                    $objDrawing->setPath('/opt/www-nginx/web' . $v['chemical_img']);
                    /*设置图片高度*/
                    $objDrawing->setHeight(20);
                    /*设置图片宽度*/
                    $objDrawing->setWidth(80);
//                      //自适应
//                       $objDrawing->setResizeProportional(true);
                    /*设置图片要插入的单元格*/
                    $objDrawing->setCoordinates(A . ($i + 4+$mergeCells));
                    /*设置图片所在单元格的格式*/
                    $objDrawing->setOffsetX(30);//30
                    $objDrawing->setOffsetY(5);
                    $objDrawing->setRotation(40);//40
                    $objDrawing->getShadow()->setVisible(true);
                    $objDrawing->getShadow()->setDirection(20);//20
                    $objDrawing->setWorksheet($objActSheet);
                }
            }

            $objectPHPExcel->getActiveSheet()->setCellValue(B . ($i + 4),$v['chemical_name']);
            $objectPHPExcel->getActiveSheet()->setCellValue(C . ($i + 4),$v['chemical_id']);
            $objectPHPExcel->getActiveSheet()->setCellValue(D . ($i + 4),$typeList[$v['type_no']]);

//            $objectPHPExcel->getActiveSheet()->setCellValue(F . ($k + 4),Utils::DateToEn($v['apply_date']));
//            $n++;
            if($chemicalinfo[$v['primary_id']]) {
                $t = $i + 4;
                foreach ($chemicalinfo[$v['primary_id']] as $e => $j) {
                    $objectPHPExcel->getActiveSheet()->setCellValue(F .$t, $j['certificate_title']);
                    $objectPHPExcel->getActiveSheet()->setCellValue(G .$t, $j['permit_startdate']);
                    $objectPHPExcel->getActiveSheet()->setCellValue(H .$t, $j['permit_enddate']);
                    $t=$t+1;
                }
            }
            $i = $i + $mergeCells +1;
        }
//        exit;

        //下载输出
        ob_end_clean();
        //ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.'Equipment information table-'.date("d M Y").'.xls"');
        $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter->save('php://output');
    }
}

