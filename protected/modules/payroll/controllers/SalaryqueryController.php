<?php

/**
 * 工资查询
 * @author LiuMinchao
 */
class SalaryqueryController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';
//    const STATUS_NORMAL = 0; //正常

    public function init() {
        parent::init();
        
        $this->bigMenu = Yii::t('pay_payroll', 'bigMenu');
    }
    /**
     * 汇总工资
     */
    public function actionList() {
        $this->contentHeader = Yii::t('pay_payroll', 'contentHeader_salary');
        $this->smallHeader = Yii::t('pay_payroll', 'smallHeader Salary List');
//        $args = array(
//            'ation_type'  =>  'PRC',
//        );
        $this->render('list');
    }
    /**
     * 详细工资
     */
    public function actionDetailList() {
        $summary_id = $_GET['summary_id'];
        $start_date = $_GET['start_date'];
//        var_dump($summary_id);
//        exit;
//        if($summary_id <> '')
//            $father_model = Program::model()->findByPk($father_proid);
        $this->contentHeader = Yii::t('pay_payroll', 'contentHeader_salary');
        $this->smallHeader = Yii::t('pay_payroll', 'smallHeader Salary List');
       
        $this->render('detaillist', array('summary_id'=>$summary_id,'start_date'=>$start_date));
    }
     /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=payroll/salaryquery/grid';
        $t->updateDom = 'datagrid';
//        $t->set_header(Yii::t('pay_payroll', 'export_month'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'start_date'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'end_date'), '', '');
	$t->set_header(Yii::t('comp_staff','User_name'), '', '');
//        $t->set_header(Yii::t('pay_payroll', 'wage'), '', '');
//        $t->set_header(Yii::t('pay_payroll', 'work_hours'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'basic_wage'), '', '');
//        $t->set_header(Yii::t('pay_payroll', 'wage_overtime'), '', '');
//        $t->set_header(Yii::t('pay_payroll', 'overtime_hours'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'overtime_wage'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'allowance'), '', '');
//        $t->set_header(Yii::t('pay_payroll', 'deduction_wage'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'total_wage'), '', '');
        $t->set_header(Yii::t('pay_payroll','record_time'),'','');
        $t->set_header(Yii::t('pay_payroll', 'status'), '', '');
        $t->set_header(Yii::t('comp_staff', 'Action'), '', '');
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

        $t = $this->genDataGrid();
        $this->saveUrl();
//        var_dump($_SERVER);
        if($args['month']){
            $month = Utils::MonthToCn($args['month']);
            $args['month'] = $month;
        }
        $args['status'] = PayrollSalary::STATUS_NORMAL;
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
//        $programlist = PayrollWorkHour::programList($args);
        $list = PayrollSalarysummary::queryList($page, $this->pageSize, $args);
//        var_dump($list);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'],'wage_rows' => $wage_list,'ation_type'=>$ation_type, 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    
     /**
     * 表头
     * @return SimpleGrid
     */
    private function genDetailGrid($summary_id,$start_date) {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=payroll/salaryquery/detailgrid&summary_id='.$summary_id.'&start_date='.$start_date;
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('pay_payroll', 'wage_date'), '', '');
	$t->set_header(Yii::t('comp_staff','User_name'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'wage'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'work_hours'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'basic_wage'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'wage_overtime'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'overtime_hours'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'overtime_wage'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'allowance'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'allowance_content'), '', '');
//        $t->set_header(Yii::t('pay_payroll', 'deduction_wage'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'total_wage'), '', '');
        $t->set_header(Yii::t('pay_payroll','record_time'),'','');
        $t->set_header(Yii::t('pay_payroll', 'status'), '', '');
//        $t->set_header(Yii::t('comp_staff', 'Action'), '', '');
        return $t;
    }
    /**
     * 查询
     */
    public function actionDetailGrid($summary_id,$start_date) {
//        var_dump($summary_id);
//        exit;
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        $t = $this->genDetailGrid($summary_id,$start_date);
        $this->saveUrl();
        if($_REQUEST['summary_id']){
            $summary_id = $_REQUEST['summary_id'];
        }
//        var_dump($_SERVER);
        $args['summary_id'] = $summary_id;
        $args['start_date'] = $start_date;
        $args['status'] = PayrollSalary::STATUS_DISABLE;
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
//        $programlist = PayrollWorkHour::programList($args);
        $list = PayrollSalary::queryList($page, $this->pageSize, $args);
//        var_dump($list);
        $this->renderPartial('detail_list', array('t' => $t, 'rows' => $list['rows'],'wage_rows' => $wage_list,'ation_type'=>$ation_type, 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }
    /**
     * 工资计算报表
     */
    public function actionSalaryExport() {
        $this->smallHeader = Yii::t('pay_payroll', 'contentHeader_export_salary');
        $model = new PayrollSalary();
        $r = array();
        $this->renderPartial('export_form', array('model' => $model, 'msg' => $r));
        //月工资报表
//        得到 wage_month 后，根据user_id,contractor_id,wage_month 加起来  
    }
    /**
     * 导出工资报表
     */
    public function actionExport() {
        $args = $_REQUEST['Export'];
        $start = str_replace('-', '', Utils::DateToCn($args['start_date']));
        $end = str_replace('-', '', Utils::DateToCn($args['end_date']));
        
        $contractor_id = Yii::app()->user->contractor_id;
        $status = PayrollSalary::STATUS_NORMAL;
        $info = PayrollSalarysummary::exportQuery($contractor_id, $start, $end,$status);
//        var_dump($info);
//        exit;
        if(empty($info)){
//            alert(Yii::t('common', 'error_record_is_null'));
            echo "alert('提示内容')";
        }
//        var_dump($start);
//        var_dump($end);
//        exit;
        //为指定表单制定表格
       //echo Yii::app()->basePath;
         spl_autoload_unregister(array('YiiBase', 'autoload')); 
         $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
         require_once($phpExcelPath);
         spl_autoload_register(array('YiiBase', 'autoload')); 
         $objectPHPExcel = new PHPExcel();
         $objectPHPExcel->setActiveSheetIndex(0);
         $objActSheet = $objectPHPExcel->getActiveSheet();
         $objActSheet->setTitle('Sheet1');
//        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭yii的自动加载功能

        //报表头的输出
         $objectPHPExcel->getActiveSheet()->mergeCells('A1:J1');
         $objectPHPExcel->getActiveSheet()->setCellValue('A1','工资统计表');
         $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getFont()->setSize(24);
         $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')
              ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
         $objectPHPExcel->getActiveSheet()->mergeCells('A2:J2');
         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2','日期：'.$start.'--'.$end);
        //表格头的输出
         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A3','姓名');
         $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(17);
         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B3','时薪');
         $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(8);
         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('C3','基本工时');
         $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(8);
         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('D3','基本工资');
         $objectPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(8);
         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('E3','加班时薪');
         $objectPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(8);
         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('F3','加班工时');
         $objectPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(8);
         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('G3','加班工资');
         $objectPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(8);
         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('H3','补贴金额');
         $objectPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(8);
         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('I3','补贴明细');
         $objectPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(37);
         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('J3','工资总额');
         $objectPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        //设置居中
         $objectPHPExcel->getActiveSheet()->getStyle('A3:J3')
              ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //设置边框
         $objectPHPExcel->getActiveSheet()->getStyle('A3:J3' )
              ->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
     
         
        //设置颜色
         $objectPHPExcel->getActiveSheet()->getStyle('A3:J3')->getFill()
              ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');
        //写入数据
     if($info){
        foreach($info as $i=>$row){
            static $n = 1;
            static $t = 0;
            $objectPHPExcel->getActiveSheet()->setCellValue('A'.($n+3),$row['user_name']);
            $objectPHPExcel->getActiveSheet()->setCellValue('B'.($n+3),$row['wage']);
                $objectPHPExcel->getActiveSheet()->setCellValue('C'.($n+3),$row['work_hours']);
                $objectPHPExcel->getActiveSheet()->setCellValue('D'.($n+3),$row['basic_wage']);
                $objectPHPExcel->getActiveSheet()->setCellValue('E'.($n+3),$row['wage_overtime']);
                $objectPHPExcel->getActiveSheet()->setCellValue('F'.($n+3),$row['overtime_hours']);
                $objectPHPExcel->getActiveSheet()->setCellValue('G'.($n+3),$row['overtime_wage']);
                $objectPHPExcel->getActiveSheet()->setCellValue('H'.($n+3),$row['allowance']);
                $objectPHPExcel->getActiveSheet()->setCellValue('I'.($n+3),$row['allowance_content']);
                $objectPHPExcel->getActiveSheet()->setCellValue('J'.($n+3),$row['total_wage']);
                //设置边框
                $currentRowNum = $n+4;
                $objectPHPExcel->getActiveSheet()->getStyle('A'.($n+4).':J'.$currentRowNum )
                    ->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $n++; 
                $t+=$row['total_wage'];
              
            }
     }else{
         static $n = 1;
     }   
         //合并列
         $objectPHPExcel->getActiveSheet()->mergeCells("A".($n+3).":I".($n+3));
         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue("A".($n+3),'工资总计');
         $objectPHPExcel->getActiveSheet()->setCellValue("J".($n+3),$t);
        //下载输出
        ob_end_clean();
        //ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.'工资统计表-'.date("Y年m月j日").'.xls"');
        $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter->save('php://output');
    }
    
    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['salaryquery/list'] = str_replace("r=payroll/salaryquery/grid", "r=payroll/salaryquery/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }
    
}
