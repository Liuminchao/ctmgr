<?php

/**
 * 工时
 * @author LiuMinchao
 */
class WorkhourController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';
//    const STATUS_NORMAL = 0; //正常

    public function init() {
        parent::init();
        
        $this->bigMenu = Yii::t('pay_payroll', 'bigMenu');
    }
    public function actionList() {
        $this->contentHeader = Yii::t('pay_payroll', 'contentHeader');
        $this->smallHeader = Yii::t('pay_payroll', 'smallHeader List');
        $args = array(
            'contractor_id' => Yii::app()->user->contractor_id,
            'project_type'  =>  'MC',
        );
        $program_list = Program::McProgramList($args);
        $this->render('list',array('program_list'=>$program_list));
    }
     /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=payroll/workhour/grid';
        $t->updateDom = 'datagrid';
	$t->set_header(Yii::t('pay_payroll', 'working_date'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'program'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'user_name'), '', '');
        $t->set_header(Yii::t('pay_payroll','record_time'),'','');
        $t->set_header(Yii::t('pay_payroll', 'status'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'work_hours'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'overtime_hours'), '', '');
//        $t->set_header(Yii::t('comp_staff', 'Action'), '', '');
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
//        if($args['status'] == ''){
//            $args['status'] = '0';
//        }
        $t = $this->genDataGrid();
        $this->saveUrl();
        $args['contractor_id'] = Yii::app()->user->contractor_id;
        $rgs['contractor_id'] = Yii::app()->user->contractor_id;
        $rgs['project_type'] = 'MC';
        $program_list = Program::programAllList($rgs);

//        $args['contractor_type'] = Staff::CONTRACTOR_TYPE_SC;
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $programlist = PayrollWorkHour::programList($args);
        $list = PayrollWorkHour::queryList($page, $this->pageSize, $args,$programlist);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'],'program_list'=>$program_list, 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }
    /**
     * 设置工时
     */
    public function actionSet() {
        $this->contentHeader = Yii::t('pay_payroll', 'contentHeader_set');
        $this->smallHeader = Yii::t('pay_payroll', 'smallHeader List');
        $args = array(
            'contractor_id' => Yii::app()->user->contractor_id,
            'project_type'  =>  'MC',
        );
        $program_list = Program::McProgramList($args);
        $this->render('workhour_list',array('program_list'=>$program_list));
    }
     /**
     * 表头
     * @return SimpleGrid
     */
    private function genEditDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=payroll/workhour/query';
        $t->updateDom = 'datagrid';
	$t->set_header(Yii::t('pay_payroll', 'working_date'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'program'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'user_name'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'work_hours'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'overtime_hours'), '', '');
//        $t->set_header(Yii::t('comp_staff', 'Action'), '', '');
        return $t;
    }
     /**
     * 搜索
     */
    public function actionQuery() {
        //var_dump($_GET['page']);
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
//        var_dump($args);
        
//        if($args['status'] == ''){
//            $args['status'] = '0';
//        }
        $t = $this->genEditDataGrid();
        $this->saveUrl();
        $args['contractor_id'] = Yii::app()->user->contractor_id;
        $rgs['contractor_id'] = Yii::app()->user->contractor_id;
        $rgs['project_type'] = 'MC';
        $program_list = Program::programAllList($rgs);

        $list = PayrollWorkHour::myUserList($page, $this->pageSize,$args);//按项目和承包商编号查员工
//        $re = Program::ScProgramList($args);
        $programlist = PayrollWorkHour::programList($args);
        $r = PayrollWorkHour::listuser_id($args,$programlist);//按项目，日期，承包商编号查员工工时
//        var_dump($list);
//        var_dump($re);
//        var_dump($r);
//        $args['contractor_type'] = Staff::CONTRACTOR_TYPE_SC;
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
//        $list = PayrollWorkHour::queryList($page, $this->pageSize, $args);
        $this->renderPartial('workhour_form', array('t' => $t,'re' => $re, 'rs' => $list['r'],'program_list'=>$program_list,'r' => $r,'working_date'=>$args['start_date'],'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }
    /**
     * 编辑表格内容
     */
    public function actionEdit() {
        $args['name'] = $_REQUEST['name'];
        $args['pk'] = $_REQUEST['pk'];
        $args['value'] = $_REQUEST['value'];
        $args['working_date'] = str_replace('-', '', Utils::DateToCn($_REQUEST['working_date']));
        $args['user_id'] = $_REQUEST['user_id'];
        $model = Staff::model()->findByPk($args['user_id']);
        $args['role_id'] = $model->role_id;
        $args['contractor_id'] = Yii::app()->user->contractor_id;
        $args['program_id'] = $_REQUEST['program_id'];
        $user_list = Staff::userAllList();
        $args['user_name'] = $user_list[$args['user_id']];
        $args['operator_id'] = Yii::app()->user->id;
        $args['operator'] = Yii::app()->user->name;
        $args['status'] = PayrollWorkHour::STATUS_CONFIRM;
//        var_dump($args);
        
        $rs = PayrollWorkHour::setdate($args);
        echo json_encode($rs);
    }
    /**
     *导出工时统计报表
     */
    public function actionExport() {
        $args = $_GET['q'];
//        var_dump($args);
//        exit;
        $programlist = PayrollWorkHour::programList($args);//获得总包下的承包商所属分包项目
        
        $args['contractor_id'] = Yii::app()->user->contractor_id;
        $list = PayrollWorkHour::report($args,$programlist);
//        var_dump($list);
//        exit;
        $rgs = array(
            'contractor_id' => Yii::app()->user->contractor_id,
            'project_type'  =>  'MC',
        );
        $program_list = Program::programAllList($rgs);
        	
        //判断月份
        if($args['start_date'] == '')
            $args['start_date'] = date('Y-m-d',strtotime('-1 day'));
	  
            $start = str_replace('-', '', Utils::DateToCn($args['start_date']));
		
            $year = substr($start, 0, 4);
            $month = substr($start,4,2);
            //获取天数
            $days=cal_days_in_month(CAL_GREGORIAN,$month,$year);
//            var_dump($d);
//            exit;
            
        spl_autoload_unregister(array('YiiBase', 'autoload')); 
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));   
        $objectPHPExcel = new PHPExcel();
        $objActSheet = $objectPHPExcel->getActiveSheet();
        $objActSheet->setTitle('Sheet1');
        $objectPHPExcel->setActiveSheetIndex(0);
         
       //  $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B2','日期：'.date("Y年m月j日"));
//         //表格头的输出
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A3','项目');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
         
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B3','工人姓名');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        for($i=1,$j=1; $i<=$days; $i++,$j++){
            if($i<25){
                $a = chr(66+$i);
                $b = $a.'3';
                $objectPHPExcel->getActiveSheet()->getColumnDimension($a)->setWidth(5);
                $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($b,$j);
            }else{
                $a = chr(64 + ($i / 25)).chr(65 + ($i % 25));
                $b = $a.'3';
                $objectPHPExcel->getActiveSheet()->getColumnDimension($a)->setWidth(5);
                $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($b,$j);
            }
        }
//        $c = chr(64 + ($i / 26)).chr(65 + ($i % 26));
        $column_workhour = chr(64 + ($i / 26)).chr(65 + ($i % 26)+1);
        $column_overtimehour = chr(64 + ($i / 26)).chr(65 + ($i % 26)+2);
//        var_dump($column_workhour);
//        var_dump($column_overtimehour);
//        exit;
         
          //报表头的输出
        $objectPHPExcel->getActiveSheet()->mergeCells('A1'.':'.$column_overtimehour.'1');
        $objectPHPExcel->getActiveSheet()->setCellValue('A1','工时统计');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getFont()->setSize(24);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')
              ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //表格头的输出 
         $objectPHPExcel->getActiveSheet()->mergeCells('A2'.':'.$column_overtimehour.'2');
         $objectPHPExcel->getActiveSheet()->setCellValue('A2','查询日期：'.$args['start_date'].'---'.$args['end_date']);
//         $objectPHPExcel->getActiveSheet()->mergeCells('B3'.':'.$a.'3');
//         $objectPHPExcel->getActiveSheet()->setCellValue('B3',substr($month,4,5).月份.(month));
//         $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B3')
//              ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

//         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($c.'3','Total Hrs');
//         $objectPHPExcel->getActiveSheet()->getColumnDimension($c)->setWidth(20);

         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($column_workhour.'3','本月实际工时');
         $objectPHPExcel->getActiveSheet()->getColumnDimension($column_workhour)->setWidth(20);
         
//         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('AH3','Overtime Hrs');
//         $objectPHPExcel->getActiveSheet()->getColumnDimension('AH')->setWidth(20);

         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($column_overtimehour.'3','本月加班工时');
         $objectPHPExcel->getActiveSheet()->getColumnDimension($column_overtimehour)->setWidth(20);
         
         //设置居中
         $objectPHPExcel->getActiveSheet()->getStyle('C3'.':'.$b)
              ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //设置边框
         $objectPHPExcel->getActiveSheet()->getStyle('C3'.':'.$b)
              ->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        //设置颜色
         $objectPHPExcel->getActiveSheet()->getStyle('C3'.':'.$b)->getFill()
              ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');
         
//         //写入数据
//
         if($list){
            foreach($list as $i=>$row){
//                var_dump($i);
//                exit;
                static $n = 2;
                $objectPHPExcel->getActiveSheet()->setCellValue('A'.($n+2),$program_list[$row[0]['program_id']]);
                $objectPHPExcel->getActiveSheet()->setCellValue('B'.($n+2),$i);
                $workhour_sum = 0;
                $overtime_sum = 0;
                foreach($row as $key=>$v){
                    
                    /*$k = substr($v,-1);
                    //var_dump($n);
                    if($k<5 && $k>0){
                        $v = floor($v)+0.5;
                    }else if($k>5){
                        $v = floor($v)+1.0;
                    }else{
                        $v = floor($v);
                    }*/
                    //var_dump($v);
                    $num = substr($v['working_date'],6,7);
//                    var_dump($num);
//                    exit;
                    if($num<25){
                        $a = chr(66+$num);
                        $objectPHPExcel->getActiveSheet()->setCellValue($a.($n+2),$v['work_hours']);
                    }else{
                        $b = chr(64 + ($num / 25)).chr(65 + ($num % 25));
                        $objectPHPExcel->getActiveSheet()->setCellValue($b.($n+2),$v['work_hours']);
                    }
                    $workhour_sum+=$v['work_hours'];
                    $overtime_sum+=$v['overtime_hours'];
                }
               
//                if($re){
//                    foreach($re as $j=>$rs){
//                        if($j==$i){
//                            $objectPHPExcel->getActiveSheet()->setCellValue('AH'.($n+3),$rs);
//                        }
//                    }
//                }
                $objectPHPExcel->getActiveSheet()->setCellValue($column_workhour.($n+2),$workhour_sum);
                $objectPHPExcel->getActiveSheet()->setCellValue($column_overtimehour.($n+2),$overtime_sum);
                $n++; 
            
                //设置边框
                $currentRowNum = $n+3;
                $objectPHPExcel->getActiveSheet()->getStyle('A'.($n+2).':'.$column_overtimehour.$currentRowNum )
                    ->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            }
            //exit;
        }else{
                static $n = 2;
        }
//          //合并列
         //$objectPHPExcel->getActiveSheet()->mergeCells("A".($n+2).":I".($n+2));
//         $objectPHPExcel->getActiveSheet()->mergeCells('A2:I2');
//         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2',T('work_summary'));
//         $objectPHPExcel->getActiveSheet()->setCellValue('J2',$hour);
////         //下载输出
        ob_end_clean();
        //ob_start();
        
        header('Content-Type : application/vnd.ms-excel;charset=utf8');
        header('Content-Disposition:attachment;filename="'.'工时统计报表'.date("Ymdhis").'.xls"');
        
        //header('Content-Disposition:attachment;filename="'.'xxxx-'.date("Y-m-d").'.xls"');
        $objWriter = new PHPExcel_Writer_Excel5($objectPHPExcel);
        //$objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter->save('php://output');
        //$objWriter->save(time().'.xls');
    }
    
    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['workhour/list'] = str_replace("r=payroll/workhour/grid", "r=payroll/workhour/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }
    
}
