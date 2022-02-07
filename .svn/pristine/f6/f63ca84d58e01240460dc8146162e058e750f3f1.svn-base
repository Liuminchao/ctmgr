<?php

/**
 * 各模块统计信息
 * @author LiuMinChao
 */
class ConmoduleController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';
    const STATUS_NORMAL = 0; //正常

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('comp_statistics', 'contentHeader_day');
        $this->bigMenu = Yii::t('comp_statistics', 'bigMenu');
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=statistics/module/grid';
        $t->updateDom = 'datagrid';
//        $t->set_compound_header(用户信息, '', 'center','','','2');
//        $t->set_compound_header(Yii::t('comp_staff', 'User_id'), '', '','','','2');
        $t->set_compound_header(Yii::t('comp_statistics', 'date'), '', 'center','','','2');
        $t->set_compound_header(Yii::t('comp_statistics', 'sc_program'), '', 'center','','','2');
        $t->set_compound_header(PTW, '', 'center','','2');
        $t->set_compound_header(TBM, '', 'center','','2');
        $t->set_compound_header(Inspection, '', 'center','','2');
        $t->set_compound_header(Meeting, '', 'center','','2');
        $t->set_compound_header(Training, '', 'center','','2');
        $t->set_compound_header(RA, '', 'center','','2');
        $t->set_compound_header(Checklist, '', 'center','','2');
        $t->set_compound_header(Incident, '', 'center','','2');
        $t->set_compound_header(QA, '', 'center','','2');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_staffs'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_staffs'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_staffs'),'','');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'),'','');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_staffs'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_staffs'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_staffs'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_staffs'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_staffs'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_staffs'), '', '');
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
            $args['status'] = '0';
        }
        $t = $this->genDataGrid();
        $this->saveUrl();
        $args['contractor_type'] = Staff::CONTRACTOR_TYPE_SC;
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $list = Statistic::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows']));
    }
    /**
     * 列表
     */
    public function actionList() {
//        var_dump($_GET['id']);
        if($_GET['id']){
            $contractor_id = $_GET['id'];
            $contractor_name = $_GET['name'];
//            $this->contentHeader = $contractor_name;
            $this->smallHeader = Yii::t('comp_statistics', 'smallHeader List Company');
        }else{
            $contractor_id = Yii::app()->user->getState('contractor_id');
            $this->smallHeader = Yii::t('comp_statistics', 'smallHeader List Day');
        }
        $this->render('tabs',array('contractor_id'=>$contractor_id));
    }

    /**
     * 导出Excel
     */
    public function actionExport(){
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q'];
        if($args['status'] == ''){
            $args['status'] = '0';
        }
        $args['contractor_type'] = Staff::CONTRACTOR_TYPE_SC;
//        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $list = Statistic::queryList($page, $this->pageSize, $args);
        $rows = $list['rows'];
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
        $objectPHPExcel->getActiveSheet()->mergeCells('A1:A2');
        $objectPHPExcel->getActiveSheet()->setCellValue('A1',Yii::t('comp_statistics', 'date'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->mergeCells('B1:B2');
        $objectPHPExcel->getActiveSheet()->setCellValue('B1',Yii::t('comp_statistics', 'sc_program'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->mergeCells('C1:D1');
        $objectPHPExcel->getActiveSheet()->setCellValue('C1','PTW');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('C1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('C1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('C2',Yii::t('comp_statistics', 'day_sum'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('C2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('C2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('D2',Yii::t('comp_statistics', 'day_staffs'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('D2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('D2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->mergeCells('E1:F1');
        $objectPHPExcel->getActiveSheet()->setCellValue('E1','TBM');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('E1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('E1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('E2',Yii::t('comp_statistics', 'day_sum'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('E2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('E2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('F2',Yii::t('comp_statistics', 'day_staffs'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('F2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('F2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->mergeCells('G1:H1');
        $objectPHPExcel->getActiveSheet()->setCellValue('G1','Inspection');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('G1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('G1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('G2',Yii::t('comp_statistics', 'day_sum'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('G2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('G2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('H2',Yii::t('comp_statistics', 'day_staffs'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('H2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('H2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->mergeCells('I1:J1');
        $objectPHPExcel->getActiveSheet()->setCellValue('I1','Meeting');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('I1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('I1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('I2',Yii::t('comp_statistics', 'day_sum'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('I2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('I2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('J2',Yii::t('comp_statistics', 'day_staffs'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('J2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('J2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->mergeCells('K1:L1');
        $objectPHPExcel->getActiveSheet()->setCellValue('K1','Training');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('K1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('K1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('K2',Yii::t('comp_statistics', 'day_sum'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('K2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('K2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('L2',Yii::t('comp_statistics', 'day_staffs'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('L2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('L2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

//        //设置居中
//        $objectPHPExcel->getActiveSheet()->getStyle('A3:D3')
//            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//        //设置边框
//        $objectPHPExcel->getActiveSheet()->getStyle('A3:D3' )
//            ->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//
//
//        //设置颜色
//        $objectPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFill()
//            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');
        //写入数据
        $mc_sum = 0;
        $ptw_sum = 0;
        $tbm_sum =0;
        $ins_sum =0;
        $met_sum=0;
        $train_sum=0;
        $ptw_staffs = 0;
        $tbm_staffs = 0;
        $ins_staffs =0;
        $met_staffs =0;
        $train_staffs =0;
        if($rows){
            $n = 3;
            foreach($rows as $i=>$row){
                $mc_sum++;
//                $record_time = date('Y-m-d',strtotime('-1 day',strtotime($row['record_time'])));
                $objectPHPExcel->getActiveSheet()->setCellValue('A'.($n),Utils::DateToEn($row['record_time']));
                $objectPHPExcel->getActiveSheet()->setCellValue('B'.($n),$row['contractor_name']);
                $objectPHPExcel->getActiveSheet()->setCellValue('C'.($n),$row['ptw_sum']);
                $ptw_sum += $row['ptw_sum'];
                $objectPHPExcel->getActiveSheet()->setCellValue('D'.($n),$row['ptw_staffs']);
                $ptw_staffs += $row['ptw_staffs'];
                $objectPHPExcel->getActiveSheet()->setCellValue('E'.($n),$row['tbm_sum']);
                $tbm_sum += $row['tbm_sum'];
                $objectPHPExcel->getActiveSheet()->setCellValue('F'.($n),$row['tbm_staffs']);
                $tbm_staffs += $row['tbm_staffs'];
                $objectPHPExcel->getActiveSheet()->setCellValue('G'.($n),$row['ins_sum']);
                $ins_sum += $row['ins_sum'];
                $objectPHPExcel->getActiveSheet()->setCellValue('H'.($n),$row['ins_staffs']);
                $ins_staffs += $row['ins_staffs'];
                $objectPHPExcel->getActiveSheet()->setCellValue('I'.($n),$row['met_sum']);
                $met_sum += $row['met_sum'];
                $objectPHPExcel->getActiveSheet()->setCellValue('J'.($n),$row['met_staffs']);
                $met_staffs += $row['met_staffs'];
                $objectPHPExcel->getActiveSheet()->setCellValue('K'.($n),$row['train_sum']);
                $train_sum += $row['train_sum'];
                $objectPHPExcel->getActiveSheet()->setCellValue('L'.($n),$row['train_staffs']);
                $train_staffs += $row['train_staffs'];
                //设置边框
//                $currentRowNum = $n+4;
//                $objectPHPExcel->getActiveSheet()->getStyle('A'.($n+4).':D'.$currentRowNum )
//                    ->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $n++;
            }
        }else{
            static $n = 1;
        }
        //合并列
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue("A".$n,Yii::t('comp_statistics', 'day_toatl'));
        $objectPHPExcel->getActiveSheet()->setCellValue("B".$n,$mc_sum);
        $objectPHPExcel->getActiveSheet()->setCellValue("C".$n,$ptw_sum);
        $objectPHPExcel->getActiveSheet()->setCellValue("D".$n,$ptw_staffs);
        $objectPHPExcel->getActiveSheet()->setCellValue("E".$n,$tbm_sum);
        $objectPHPExcel->getActiveSheet()->setCellValue("F".$n,$tbm_staffs);
        $objectPHPExcel->getActiveSheet()->setCellValue("G".$n,$ins_sum);
        $objectPHPExcel->getActiveSheet()->setCellValue("H".$n,$ins_staffs);
        $objectPHPExcel->getActiveSheet()->setCellValue("I".$n,$ins_sum);
        $objectPHPExcel->getActiveSheet()->setCellValue("J".$n,$ins_staffs);
        $objectPHPExcel->getActiveSheet()->setCellValue("K".$n,$train_sum);
        $objectPHPExcel->getActiveSheet()->setCellValue("L".$n,$train_staffs);
        //下载输出
        ob_end_clean();
        //ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.Yii::t('comp_statistics', 'daily_statistics').'-'.date("Ymj").'.xls"');
        $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter->save('php://output');
    }

    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['module/list'] = str_replace("r=statistics/module/grid", "r=statistics/module/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }
}
