<?php

class UploadController extends AuthBaseController {

    public $title_rows = 6;
    //public $per_read_cnt = 5;

    //导出员工信息表
    public function actionExport(){
        set_time_limit(0);
        ini_set("memory_limit","512M");
        $fields = func_get_args();
        $type_list = PtwType::levelText();
        $con_list = Contractor::compList();
//        $region_list = PtwApplyBlock::regionAllList();
//        var_dump($region_list);
//        exit;

//        $args = $_GET['q']; //查询条件
        $args['program_id'] = $_REQUEST['program_id'];
        $args['month'] = $_REQUEST['month'];
        $args['contractor_id'] = Yii::app()->user->contractor_id;

        $list = ApplyBasic::queryExcelList($args);
//        var_dump($list);
//        exit;
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();
        // 设置宽度
        $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(23);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(23);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(33);
        $objectPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(45);
        $objectPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(30);
        $objectPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(45);
        //设置单元格内容自动换行
        $objectPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setWrapText(true);
        $objectPHPExcel->getActiveSheet()->getStyle('I3')->getAlignment()->setWrapText(true);
        $objectPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objectPHPExcel->getActiveSheet();
        $objActSheet->setTitle('Sheet1');

        //报表头的输出
        $objectPHPExcel->getActiveSheet()->mergeCells('A1:K1');
        $objectPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->setCellValue('A1','Permit-To-Work (PTW)');
        $objStyleA1 = $objActSheet->getStyle('A1');
         //字体及颜色
        $objFontA1 = $objStyleA1->getFont();
        $objFontA1->setSize(20);
        $objectPHPExcel->getActiveSheet()->mergeCells('A2:A3');
        $objectPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2','S/N');
        $objectPHPExcel->getActiveSheet()->mergeCells('B2:B3');
        $objectPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('B2','Location of Work');
        $objectPHPExcel->getActiveSheet()->mergeCells('C2:C3');
        $objectPHPExcel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('C2','Type of works to be performed(ie.Demolition works,excavation works,piling works,lifting operations,works in confined spaces.etc)');
        $objectPHPExcel->getActiveSheet()->mergeCells('D2:D3');
        $objectPHPExcel->getActiveSheet()->getStyle('D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('D2','Company/Trade');
        $objectPHPExcel->getActiveSheet()->mergeCells('E2:E3');
        $objectPHPExcel->getActiveSheet()->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('E2','PTW Serial No.');
        $objectPHPExcel->getActiveSheet()->mergeCells('F2:I2');
        $objectPHPExcel->getActiveSheet()->getStyle('F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('F2','PTW information');
        $objectPHPExcel->getActiveSheet()->getStyle('F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('F3','Permit Start Date/Time');
        $objectPHPExcel->getActiveSheet()->getStyle('G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('G3','Permit End Date/Time');
        $objectPHPExcel->getActiveSheet()->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('H3','Approved Person');
        $objectPHPExcel->getActiveSheet()->getStyle('I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('I3','Status(ie.Draft,Submitted,Acknowledged,Assessed,Approved,Rejected,Work Completed,Closed)');

        //写入数据
        $x =0;
        $k = 0;
        if(count($list['rows'])>0){
            foreach ($list['rows'] as $apply_id=>$v){
//            foreach ($list['s'] as $x=>$y){
//                if($v['apply_id'] == $y['apply_id']){
//                    $position = $y['block'].' '.$y['secondary_region'];
//                }
//            }
                $region_list = PtwApplyBlock::regionexcelList($apply_id);
                if(!empty($region_list)){
                    foreach($region_list as $block => $secondary_region){
                        $position = $block.' ';
                        foreach($secondary_region as $i => $j){
                            $position.=$j.' ';
                        }
                    }
                }else{
                    $position = '';
                }
//            $position = $region_list[$v['apply_id']];
//            var_dump($region_list);
//            exit;
                $add_operator = explode('|',$v['add_operator']);
                $step = $add_operator[0];
                $year = substr($v['record_time'],0,4);
                $deal_all = CheckApplyDetail::dealtypeAllList($v['apply_id'],$step,$year);
                $deal_user_model = Staff::model()->findByPk($deal_all[0]['deal_user_id']);
                $deal_user_name = $deal_user_model->user_name;
                $num=$k+4;//从第4行开始写入数据，第一行是表名，第二,三行是表头
//            $count=count($rs["rows"])+3;//数组总个数
                $objectPHPExcel->setActiveSheetIndex(0)
                    //Excel的第A列，project_name是你查出数组的键值，下面以此类推
                    ->setCellValue('A'.$num, $x++)
                    ->setCellValue('B'.$num, $position)
                    ->setCellValue('C'.$num, $type_list[$v['type_id']])
                    ->setCellValue('D'.$num, $con_list[$v['apply_contractor_id']])
                    ->setCellValue('E'.$num, $v['apply_id'])
                    ->setCellValue('F'.$num, Utils::DateToEn($v['start_date']).' '.$v['start_time'])
                    ->setCellValue('G'.$num, Utils::DateToEn($v['end_date']).' '.$v['end_time'])
                    ->setCellValue('H'.$num, $deal_user_name)
                    ->setCellValue('I'.$num, ApplyBasic::statusText($v['status']));
                $objectPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objectPHPExcel->getActiveSheet()->getStyle('B'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objectPHPExcel->getActiveSheet()->getStyle('C'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objectPHPExcel->getActiveSheet()->getStyle('D'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objectPHPExcel->getActiveSheet()->getStyle('E'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objectPHPExcel->getActiveSheet()->setCellValueExplicit('E'.$num,$v['apply_id'],PHPExcel_Cell_DataType::TYPE_STRING);
                $objectPHPExcel->getActiveSheet()->getStyle('F'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objectPHPExcel->getActiveSheet()->getStyle('G'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objectPHPExcel->getActiveSheet()->getStyle('H'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objectPHPExcel->getActiveSheet()->getStyle('I'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $k++;
            }
        }
        //下载输出
        ob_end_clean();
        //ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.'PTW Report – '.$args['month'].'.xlsx"');
        //$objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter = PHPExcel_IOFactory::createWriter($objectPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

    //导出月报
    public function actionMonthExport(){
        $args['program_id'] = $_REQUEST['program_id'];
        $args['month'] = $_REQUEST['month'];
        $pro_model = Program::model()->findByPk($args['program_id']);
        $program_name = $pro_model->program_name;
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $r = SafetyCheck::CntExcelList($args);
        $s = SafetyCheck::ScCompanyExcelList($args);
        $o = ApplyBasic::StatusExcelList($args);
//        var_dump($r);
//        exit;
        $safety_type[0][] = 'Name';
        $safety_type[0][] = 'Percentage(%)';
        $safety_type[0][] = 'Value';
        $v = 1;

        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel-1.8'.DIRECTORY_SEPARATOR.'Classes'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objPHPExcel = new PHPExcel();
        $objWorksheet = $objPHPExcel->getActiveSheet();

        $objWorksheet
            //Excel的第A列，project_name是你查出数组的键值，下面以此类推
            ->setCellValue('Q1', 'Name')
            ->setCellValue('R1', 'Percentage(%)')
            ->setCellValue('S1', 'Value');
        $index = 1;
        if(!empty($r)){
            $r_sum = 0;
            foreach ($r as $i => $j){
                $r_sum+= $j['cnt'];
            }
            foreach ($r as $i => $j){
                $index++;
                $objWorksheet
                    //Excel的第A列，project_name是你查出数组的键值，下面以此类推
                    ->setCellValue('Q'.$index, $j['type_name'])
                    ->setCellValue('R'.$index, round($j['cnt']/$r_sum*100,2))
                    ->setCellValue('S'.$index, $j['cnt']);
            }
        }else{
            $index++;
        }

        $count_1 = count($r);
        $count_2 = count($s);
        $count_3 = count($o);
//        var_dump($count_1);
//        var_dump($count_2);
//        var_dump($count_3);
//        exit;
        if(!empty($s)){
            $s_sum = 0;
            foreach ($s as $m => $n){
                $s_sum+= $n['cnt'];
            }
            foreach ($s as $m => $n){
                $index++;
                $objWorksheet
                    //Excel的第A列，project_name是你查出数组的键值，下面以此类推
                    ->setCellValue('Q'.$index, $n['contractor_name'])
                    ->setCellValue('R'.$index, round($n['cnt']/$s_sum*100,2))
                    ->setCellValue('S'.$index, $n['cnt']);
            }
        }else{
            $index++;
        }

        if(!empty($o)){
            $o_sum = 0;
            foreach ($o as $x => $y){
                $o_sum+= $y['cnt'];
            }
            foreach ($o as $x => $y){
                $index++;
                $objWorksheet
                    //Excel的第A列，project_name是你查出数组的键值，下面以此类推
                    ->setCellValue('Q'.$index, $y['status'])
                    ->setCellValue('R'.$index, round($y['cnt']/$o_sum*100,2))
                    ->setCellValue('S'.$index, $y['cnt']);
            }
        }else{
            $index++;
        }
//        var_dump($safety_type);
//        exit;

        foreach ($safety_type as $m => $n){

        }
//	Set the Labels for each data series we want to plot
//		Datatype
//		Cell reference for data
//		Format Code
//		Number of datapoints in series
//		Data values
//		Data Marker
        $dataSeriesLabels_1 = array(
            new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$A$1', NULL, 1),
            new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$B$1', NULL, 1),
        );
        $dataSeriesLabels_2 = array(
            new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$C$1', NULL, 1),
            new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$D$1', NULL, 1),

        );
        $dataSeriesLabels_3 = array(
            new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$E$1', NULL, 1),
            new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$F$1', NULL, 1),
        );
//	Set the X-Axis Labels
        if($count_1 > 0){
            $end_1 = 2+$count_1-1;
        }else{
            $end_1 = 2;
        }

        $xAxisTickValues_1 = array(
            new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$Q$2:$Q$'.$end_1, NULL, $count_1),	//	Q1 to Q4
        );
        $start_2 = $end_1+1;
        if($count_2>0){
            $end_2 = $start_2+$count_2-1;
        }else{
            $end_2 = $start_2;
        }

        $xAxisTickValues_2 = array(
            new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$Q$'.$start_2.':$Q$'.$end_2, NULL, $count_2),	//	Q1 to Q4
        );
        $start_3 = $end_2+1;

        if($count_3>0){
            $end_3 = $start_3+$count_3-1;
        }else{
            $end_3 = $start_3;
        }

        $xAxisTickValues_3 = array(
            new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$Q$'.$start_3.':$Q$'.$end_3, NULL, $count_3),	//	Q1 to Q4
        );
//	Set the Data values for each data series we want to plot
//		Data Marker
        $dataSeriesValues_1 = array(
            new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$R$2:$R$'.$end_1, NULL, $count_1),
        );

        $dataSeriesValues_2 = array(
            new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$S$'.$start_2.':$S$'.$end_2, NULL, $count_2),
        );

        $dataSeriesValues_3 = array(
            new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$R$'.$start_3.':$R$'.$end_3, NULL, $count_3),
        );

//	Build the dataseries
        $series_1 = new PHPExcel_Chart_DataSeries(
            PHPExcel_Chart_DataSeries::TYPE_BARCHART,		// plotType
            PHPExcel_Chart_DataSeries::GROUPING_STANDARD,	// plotGrouping
            range(0, count($dataSeriesValues_1)-1),			// plotOrder
            $dataSeriesLabels_1,								// plotLabel
            $xAxisTickValues_1,								// plotCategory
            $dataSeriesValues_1								// plotValues
        );
        $series_2 = new PHPExcel_Chart_DataSeries(
            PHPExcel_Chart_DataSeries::TYPE_BARCHART,		// plotType
            PHPExcel_Chart_DataSeries::GROUPING_STANDARD,	// plotGrouping
            range(0, count($dataSeriesValues_2)-1),			// plotOrder
            $dataSeriesLabels_2,								// plotLabel
            $xAxisTickValues_2,								// plotCategory
            $dataSeriesValues_2								// plotValues
        );
        $series_3 = new PHPExcel_Chart_DataSeries(
            PHPExcel_Chart_DataSeries::TYPE_DONUTCHART,		// plotType
            NULL,	// plotGrouping
            range(0, count($dataSeriesValues_2)-1),	// plotOrder
            $dataSeriesLabels_3,								// plotLabel
            $xAxisTickValues_3,								// plotCategory
            $dataSeriesValues_3								// plotValues
        );
//	Set additional dataseries parameters
//		Make it a vertical column rather than a horizontal bar graph
        $series_1->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_BAR);
        $series_2->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_BAR);
        $series_3->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_BAR);
//	Set up a layout object for the Pie chart
        $layout = new PHPExcel_Chart_Layout();
        $layout->setShowVal(TRUE);
        $layout->setShowCatName(FALSE);
//	Set the series in the plot area
        $plotArea_1 = new PHPExcel_Chart_PlotArea(NULL, array($series_1));
        $plotArea_2 = new PHPExcel_Chart_PlotArea(NULL, array($series_2));
        $plotArea_3 = new PHPExcel_Chart_PlotArea($layout, array($series_3));
//	Set the chart legend
        $legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);

        $title_1 = new PHPExcel_Chart_Title('Breakdown in percentage on the safety non-compliancs found based on trades');
        $title_2 = new PHPExcel_Chart_Title('Breakdown on the total number of safety non-compliancs by the different subcontractors');
        $title_3 = new PHPExcel_Chart_Title('Monthly Permit-To-Work(PTW) status');
        $yAxisLabel_1 = new PHPExcel_Chart_Title('Percentage(%)');
        $yAxisLabel_2 = new PHPExcel_Chart_Title('Cnt');


//	Create the chart
        $chart_1 = new PHPExcel_Chart(
            'chart1',		// name
            $title_1,			// title
            NULL,		// legend
            $plotArea_1,		// plotArea
            true,			// plotVisibleOnly
            0,				// displayBlanksAs
            NULL,			// xAxisLabel
            $yAxisLabel_1		// yAxisLabel
        );

        $chart_2 = new PHPExcel_Chart(
            'chart2',		// name
            $title_2,			// title
            NULL,		// legend
            $plotArea_2,		// plotArea
            true,			// plotVisibleOnly
            0,				// displayBlanksAs
            NULL,			// xAxisLabel
            $yAxisLabel_2		// yAxisLabel
        );

        $chart_3 = new PHPExcel_Chart(
            'chart3',		// name
            $title_3,			// title
            $legend,		// legend
            $plotArea_3,		// plotArea
            true,			// plotVisibleOnly
            0,				// displayBlanksAs
            NULL,			// xAxisLabel
            NULL		// yAxisLabel
        );

//	Set the position where the chart should appear in the worksheet
        $chart_1->setTopLeftPosition('A15');
        $chart_1->setBottomRightPosition('H28');

        $chart_2->setTopLeftPosition('I15');
        $chart_2->setBottomRightPosition('P28');

        $chart_3->setTopLeftPosition('I1');
        $chart_3->setBottomRightPosition('P14');

//	Add the chart to the worksheet
        $objWorksheet->addChart($chart_1);
        $objWorksheet->addChart($chart_2);
        $objWorksheet->addChart($chart_3);

        $objPHPExcel->getActiveSheet()->setCellValue('A1','FOR THE MONTH OF:'.$args['month']);
        $objPHPExcel->getActiveSheet()->setCellValue('A2','Total Number of Workplace Accidents Reported:');
        $objPHPExcel->getActiveSheet()->setCellValue('A3','Total Number of Mandays Worked:');
        $objPHPExcel->getActiveSheet()->setCellValue('A4','Accident Frequency Rate(AFR)');
        $objPHPExcel->getActiveSheet()->setCellValue('A5','Number of DO:');
        $objPHPExcel->getActiveSheet()->setCellValue('A6','Fatality:');
// Save Excel 2007 file
        ob_end_clean();
        //ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.'Monthly Safety Data Report-'.$args['month'].'.xlsx"');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->setIncludeCharts(TRUE);
        $objWriter->save('php://output');
    }

    //创建目录
    private static function createPath($path){
        if($path == ''){
            return false;
        }
        if(!file_exists($path))
        {
            umask(0000);
            @mkdir($path, 0777, true);
        }
        return true;
    }
    //验证日期格式
    function is_Date($str){
        $format='Y-m-d';
        $unixTime_1=strtotime($str);
        if(!is_numeric($unixTime_1)) return false; //如果不是数字格式，则直接返回
        $checkDate=date($format,$unixTime_1);
        $unixTime_2=strtotime($checkDate);
        if($unixTime_1==$unixTime_2){
            return true;
        }else{
            return false;
        }
    }
}
