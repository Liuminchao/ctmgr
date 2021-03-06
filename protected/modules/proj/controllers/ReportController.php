<?php

/**
 * 项目报表
 * @author weijuan
 */
class ReportController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';
    public $layout = '//layouts/main_1';

    public function init() {
        parent::init();
        //echo Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        //$this->contentHeader = Yii::t('proj_report', 'contentHeader');
        $this->bigMenu = Yii::t('proj_report', 'bigMenu');
    }

    /**
     * 考勤统计表头
     * @return SimpleGrid
     */
    private function genAttendDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=proj/report/attendgrid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('proj_report', 'report_num'), '', '');
//        $t->set_header(Yii::t('proj_report', 'report_program'), '', '');
        $t->set_header(Yii::t('proj_report', 'report_role'), '', '');
        $t->set_header(Yii::t('proj_report', 'attend_hour'), '', '');
        return $t;
    }

    /**
     * 考勤统计查询
     */
    public function actionAttendGrid() {

        $args = $_GET['q']; //查询条件
        //var_dump($args);
        
        $t = $this->genAttendDataGrid();
        //$this->saveAttendUrl();
       
        $list = ProjectAttend::report($args);
        //var_dump($list);
        $args = array(
            'contractor_id' => Yii::app()->user->contractor_id,
            'project_type'  =>  'MC',
        );
        $program_list = Program::programList($args);
        $role_list = Role::roleList();
        
        $this->renderPartial('attend_list', array('t' => $t, 'rows' => $list, 'program_list'=>$program_list, 'role_list'=>$role_list));
    }

    /**
     * 考勤统计列表
     */
    public function actionAttendList() {

        $this->smallHeader = Yii::t('proj_report', 'smallHeader Attend');
        
        $args = array(
            'contractor_id' => Yii::app()->user->contractor_id,
            'project_type'  =>  'MC',
        );
 
        $program_list = Program::programList($args);
        $role_list = Role::roleList();
        $this->render('attendlist', array('program_list'=>$program_list, 'role_list'=>$role_list));
    }
    /**
     * 导出Excel
     */
    public function actionExport(){
        $args = $_GET['q'];
//        var_dump($args);
//        exit();
        $list = ProjectAttend::report($args);
//        var_dump($list);
//        exit();
        $args = array(
            'contractor_id' => Yii::app()->user->contractor_id,
            'project_type'  =>  'MC',
        );
        $program_list = Program::programList($args);
        $role_list = Role::roleList();
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
         $objectPHPExcel->getActiveSheet()->mergeCells('A1:D1');
         $objectPHPExcel->getActiveSheet()->setCellValue('A1','总包项目工时统计表');
         $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getFont()->setSize(24);
         $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')
              ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2','日期：'.date("Y年m月j日"));
        //表格头的输出
         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A3','序号');
         $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6.5);
         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B3','总包项目');
         $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('C3','角色');
         $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(17);
         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('D3','出勤时间（小时）');
         $objectPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(17);
        //设置居中
         $objectPHPExcel->getActiveSheet()->getStyle('A3:D3')
              ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //设置边框
         $objectPHPExcel->getActiveSheet()->getStyle('A3:D3' )
              ->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
     
         
        //设置颜色
         $objectPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFill()
              ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');
        //写入数据
     if($list){
        foreach($list as $i=>$row){
              if($row['hours']>0){
                static $n = 1;
                static $t = 0;
                $objectPHPExcel->getActiveSheet()->setCellValue('A'.($n+3),$n);
                $objectPHPExcel->getActiveSheet()->setCellValue('B'.($n+3),$program_list[$row['node_id']]);
                $objectPHPExcel->getActiveSheet()->setCellValue('C'.($n+3),$role_list[$row['node_name']]);
                $objectPHPExcel->getActiveSheet()->setCellValue('D'.($n+3),$row['hours']);
                //设置边框
                $currentRowNum = $n+4;
                $objectPHPExcel->getActiveSheet()->getStyle('A'.($n+4).':D'.$currentRowNum )
                    ->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $n++; 
                $t+=$row['hours'];
              }  
            }
     }else{
         static $n = 1;
     }   
         //合并列
         $objectPHPExcel->getActiveSheet()->mergeCells("A".($n+3).":C".($n+3));
         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue("A".($n+3),'出勤时间汇总');
         $objectPHPExcel->getActiveSheet()->setCellValue("D".($n+3),$t);
        //下载输出
        ob_end_clean();
        //ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.'工时统计表-'.date("Y年m月j日").'.xls"');
        $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter->save('php://output');
    }
    /**
     * 导出Excel
     */
    public function actionExportEpss(){
        $args['program_id'] = $_REQUEST['program_id'];
        $args['type_id'] = $_REQUEST['type_id'];
        $args['month'] = $_REQUEST['month'];
        $args['filepath'] = '';
        $args['contractor_id'] = '';
        EpssRole::epssReportByFtp($args);
    }
    /**
     * 保存考勤统计查询链接
     */
    private function saveAttendUrl() {

        $a = Yii::app()->session['list_url'];
        $a['licensepdf/list'] = str_replace("r=proj/attend/attendgrid", "r=proj/attend/attendlist", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

}
