<?php
class TypeController extends BaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = "";
    public $bigMenu = "";

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('license_type', 'contentHeader');
        $this->bigMenu = Yii::t('license_type', 'bigMenu');
    }
    
    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=license/type/grid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('license_type','seq'), '', '');
        $t->set_header(Yii::t('license_type','type_name'), '', '');
        $t->set_header(Yii::t('license_type','type_name_en'), '', '');
        $t->set_header(Yii::t('license_type','status'), '', '');
        $t->set_header(Yii::t('common','record_time'), '', '');
        $t->set_header(Yii::t('common','action'), '15%', '');
        return $t;
    }

    /**
     * 查询
     */
    public function actionGrid() {
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件

        if(!$args['type_id']){
            $args['type_id'] = 1;
        }

        $t = $this->genDataGrid();
        $this->saveUrl();
        $args['status'] = '00';
        $list = PtwType::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {

//        if (Yii::app()->language == 'zh_CN') {
//            $lang = "_zh"; //中文
//        }
//
//        $filepath = '/opt/www-nginx/web/filebase/tmp/DEMO.pdf';
//
//
//        $title = 'TEST';
//
////        if (file_exists($filepath)) {
////            $show_name = $title;
////            $extend = 'pdf';
////            Utils::Download($filepath, $show_name, $extend);
////            return;
////        }
//        $tcpdfPath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . 'tcpdf' . DIRECTORY_SEPARATOR . 'tcpdf.php';
//        require_once($tcpdfPath);
////        Yii::import('application.extensions.tcpdf.TCPDF');
//        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
//        // 设置文档信息
//        $pdf->SetCreator(Yii::t('login', 'Website Name'));
//        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
//        $pdf->SetTitle($title);
//        $pdf->SetSubject($title);
//        //$pdf->SetKeywords('PDF, LICEN');
//        // 设置页眉和页脚信息
//
//        $pdf->SetHeaderData('', 0, 'Meeting No.(会议编号): ' . 111111, 'CMS', array(0, 64, 255), array(0, 64, 128));
//
//
//        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
//
//        // 设置页眉和页脚字体
//
////        if (Yii::app()->language == 'zh_CN') {
////            $pdf->setHeaderFont(Array('stsongstdlight', '', '10')); //中文
////        } else if (Yii::app()->language == 'en_US') {
//        $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //英文OR中文
////        }
//
//        $pdf->setFooterFont(Array('helvetica', '', '10'));
//
//        //设置默认等宽字体
//        $pdf->SetDefaultMonospacedFont('courier');
//
//        //设置间距
//        $pdf->SetMargins(15, 27, 15);
//        $pdf->SetHeaderMargin(5);
//        $pdf->SetFooterMargin(10);
//
//        //设置分页
//        $pdf->SetAutoPageBreak(TRUE, 25);
//        //set image scale factor
//        $pdf->setImageScale(1.25);
//        //set default font subsetting mode
//        $pdf->setFontSubsetting(true);
//        //设置字体
////        if (Yii::app()->language == 'zh_CN') {
////            $pdf->SetFont('droidsansfallback', '', 14, '', true); //中文
////        } else if (Yii::app()->language == 'en_US') {
//        $pdf->SetFont('droidsansfallback', '', 9, '', true); //英文OR中文
////        }
//
//        $pdf->AddPage();
//
//        //标题(许可证类型+项目)
//        $title_html = "<h3 align=\"center\">TEST</h3><br/>";
//        //申请人资料
//        $apply_info_html = "<h4 align=\"center\">Applicant Details (申请人详情)</h4><table border=\"1\">";
//        $apply_info_html .="<tr><td width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Name of Applicant (申请人姓名)</td><td width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Designation of Applicant (申请人职位)</td><td width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">ID No.of Applicant (申请人身份证号码)</td></tr>";
//        $apply_info_html .="<tr><td height=\"50px\" align=\"center\">&nbsp;12344</td><td align=\"center\">&nbsp;456456</td><td align=\"center\">&nbsp;45646</td></tr>";
//        $apply_info_html .="<tr><td width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Applicant Company (申请人公司)</td><td width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Date of Application (申请时间)</td><td width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Electronic Signature (电子签名)</td></tr>";
//        $apply_info_html .="<tr><td height=\"50px\" align=\"center\">&nbsp;456456</td><td align=\"center\">&nbsp;45645656</td><td align=\"center\">&nbsp;</td></tr>";
//        $apply_info_html .="</table>";
//
//        //工作内容
//        $work_content_html = "<h4 align=\"center\">Meeting Content (会议内容)</h4><table border=\"1\"  >";
//        $work_content_html .="<tr><td width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Title (标题)</td><td width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Description (描述)</td></tr>";
//        $work_content_html .="<tr><td height=\"400px\">123434</td><td >423423</td></tr></table>";
//
//
//        $html_1 = $title_html . $apply_info_html;
//
//        $pdf->writeHTML($html_1, true, false, true, false, '');
//        $y1= $pdf->GetY();
//        $html_2 = $work_content_html ;
//
//        $pdf->writeHTML($html_2, true, false, true, false, '');
//        //判断电子签名是否存在 $add_operator->signature_path
//        $path = '/opt/www-nginx/appupload/1/0001314991_photo.jpg';
//        $y2= $pdf->GetY();
//        $pdf->Rect(150, $y2, 20, 9, 'F', array('123456'), array(128,255,255));
//        $pdf->Image($path, 150, $y2, 20, 9, 'JPG', '', '', false, 300, '', false, false, 0, 'T', false, false);
//
//
////        $x= $pdf->GetX();
////        var_dump($y1);
////        var_dump($y2);
////        var_dump($x);
////        exit;
////        $pdf->AddPage();
//
//        //输出PDF
////        $pdf->Output($filepath, 'I');
//
//        $pdf->Output($filepath, 'I');  //保存到指定目录
//        return $filepath;
//============================================================+
// END OF FILE
//============================================================+
        $this->smallHeader = Yii::t('license_type', 'smallHeader List');
        $this->render('list');
    }

    /**
     * 添加
     */
    public function actionNew() {

        $this->smallHeader = Yii::t('license_type', 'smallHeader New');
        $this->render('method_statement',array('mode'=>'add'));
    }

    /**
     * 修改
     */
    public function actionEdit() {

        $this->smallHeader = Yii::t('license_type', 'smallHeader Edit');
        $model = new PtwType('modify');
        $r = array();
        $id = trim($_REQUEST['id']);
        if (isset($_POST['PtwType'])) {
            $args = $_POST['PtwType'];
            $r = PtwType::updatePtwType($args);
            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['PtwType'];
            }
        }
        $model->_attributes = PtwType::model()->findByPk($id);
        $this->render('edit', array('model' => $model, 'msg' => $r));
    }

    /**
     * Method Statement with Risk Assessment
     */
    public function actionMethod() {
        $this->smallHeader = Yii::t('license_type', 'smallHeader New');
        $type_id = $_REQUEST['id'];
        $type_model = PtwType::model()->findByPk($type_id);
        $this->render('method_statement',array('type_id'=>$type_id,'mode'=>'copy','type_model'=>$type_model));
    }

    /**
     * Method Statement with Risk Assessment
     */
    public function actionSaveMethod() {
        $json = $_REQUEST['json_data'];
        $operator_type   =  Yii::app()->user->getState('operator_type');
        if($operator_type == '00'){
            $args['contractor_id'] = 0;
        }else{
            $args['contractor_id'] = Yii::app()->user->contractor_id;
        }
        $args['type_id'] = $_REQUEST['type'];
        $args['type_name'] = $_REQUEST['type_name'];
        $args['type_name_en'] = $_REQUEST['type_name_en'];
        $args['status'] = '00';
        $data = json_decode($json);
        $r = PtwCondition::insertPtwCondition($args,$data);
        echo json_encode($r);
        // var_dump($data);
        // exit;
    }

    /**
     * json数据
     */
    public function actionDemoData() {
        $type_id = $_REQUEST['id'];
        $detail_list = PtwCondition::detailList($type_id);
        print_r(json_encode($detail_list));
    }
    
    /**
     * 启用
     */
    public function actionStart() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {

            $r = PtwType::startType($id);
        }
        echo json_encode($r);
    }

    /**
     * 停用
     */
    public function actionStop() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {

            $r = PtwType::stopType($id);
        }
        echo json_encode($r);
    }
    
     /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['type/list'] = str_replace("r=license/type/grid", "r=license/type/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

}
