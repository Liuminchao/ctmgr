<?php
class ModuleReportCommand extends CConsoleCommand
{
    //php /opt/www-nginx/web/test/ctmgr/protected/yiic modulereport bach
    //0,10 3-4 * * * php /opt/www-nginx/web/test/ctmgr/protected/yiic modulereport bach
    //yiic 自定义命令类名称 动作名称 --参数1=参数值 --参数2=参数值 --参数3=参数值……
    public function actionBach()
    {
        //$program_id = $_GET['program_id'];
        $program_id = '1876';
        $pro_model = Program::model()->findByPk($program_id);
        $pro_params = $pro_model->params;//项目参数
        $date = '2021-07';
        //ini_set('memory_limit','512M');
        // //1
        //  $sql = "select * from ptw_apply_basic where program_id = $program_id and record_time like '%$date%' ";
        //  //// $sql = "select * from ptw_apply_basic where program_id = $program_id and apply_contractor_id = '377' and record_time like '%$date%' ";
        //  $command = Yii::app()->db->createCommand($sql);
        //  $rows = $command->queryAll();
        //  $n = 0;
        //  if($pro_params != '0') {
        //      $pro_params = json_decode($pro_params, true);
        //      //判断是否是迁移的
        //      if (array_key_exists('ptw_report', $pro_params)) {
        //          $params['type'] = $pro_params['ptw_report'];
        //      } else {
        //          $params['type'] = 'A';
        //      }
        //  }else{
        //     $params['type'] = 'A';
        // }
        //  foreach($rows as $i => $j){
        //      $params['id'] = $j['apply_id'];
        //      $app_id = 'PTW';
        //      $add_operator = explode('|',$j['add_operator']);
        //      $step = $add_operator[0];
        //      $year = substr($j['record_time'],0,4);//年
        //      $deal_type = CheckApplyDetail::dealtypeList($app_id, $j['apply_id'], $step,$year);
        //      if($deal_type[0]['status'] == 2){
        //          if($j['save_path'] == ''){
        //              $n++;
        //              $filepath = DownloadPdf::transferDownload($params,$app_id);
        //              if(file_exists($filepath)){
        //                 echo 'OK';
        //              }
        //          }
        //      }else if($deal_type[0]['deal_type'] >= 2 && $deal_type[0]['deal_type']!=8){
        //          if($j['save_path'] == ''){
        //              $n++;
        //              $filepath = DownloadPdf::transferDownload($params,$app_id);
        //              if(file_exists($filepath)){
        //                  echo 'OK';
        //              }
        //          }
        //      }
        //      if($n==200){
        //          goto end;
        //      }
        //  };
        // // 2
        // $sql = "select * from tbm_meeting_basic where program_id = $program_id and record_time like '$date%' ";
        // $command = Yii::app()->db->createCommand($sql);
        // $rows = $command->queryAll();
        // $n = 0;
        // if($pro_params != '0') {
        //     $pro_params = json_decode($pro_params, true);
        //     //判断是否是迁移的
        //     if (array_key_exists('tbm_report', $pro_params)) {
        //         $params['type'] = $pro_params['tbm_report'];
        //     } else {
        //         $params['type'] = 'A';
        //     }
        // }else{
        //     $params['type'] = 'A';
        // }
        // foreach($rows as $i => $j){
        //     $params['id'] = $j['meeting_id'];
        //     $params['tag'] = 0;
        //     $app_id = 'TBM';
        //     if($j['status'] == 1){
        //         if($j['save_path'] == ''){
        //             $n++;
        //             $filepath = DownloadPdf::transferDownload($params,$app_id);
        //             if(file_exists($filepath)){
        //                 echo 'OK';
        //             }
        //         }
        //     }
        //     if($n==200){
        //         goto end;
        //     }
        // }
        // //3
        // $sql = "SELECT * FROM train_apply_basic WHERE program_id = $program_id and record_time like '$date%' ";
        // //$sql = "SELECT * FROM train_apply_basic WHERE program_id = $program_id and add_conid = '377' and record_time like $date.'%' ";
        // $command = Yii::app()->db->createCommand($sql);
        // $rows = $command->queryAll();
        // $n = 0;
        // if($pro_params != '0') {
        //     $pro_params = json_decode($pro_params, true);
        //     //判断是否是迁移的
        //     if (array_key_exists('train_report', $pro_params)) {
        //         $params['type'] = $pro_params['train_report'];
        //     } else {
        //         $params['type'] = 'A';
        //     }
        // }else{
        //     $params['type'] = 'A';
        // }
        // foreach($rows as $i => $j){
        //     $params['id'] = $j['training_id'];
        //     $app_id = 'TRAIN';
        //     if($j['status'] == 1){
        //         if($j['save_path'] == ''){
        //             $n++;
        //             $filepath = DownloadPdf::transferDownload($params,$app_id);
        //             if(file_exists($filepath)){
        //                 echo 'OK';
        //             }
        //         }
        //     }
        //     if($n==200){
        //         goto end;
        //     }
        // }
        //  //4
        // $sql = "select * from bac_safety_check where root_proid = $program_id and apply_time like '$date%' ";
        // //$sql = "select * from bac_safety_check where root_proid = $program_id and contractor_id = '377' and apply_time like $date.'%'  ";
        // $command = Yii::app()->db->createCommand($sql);
        // $rows = $command->queryAll();
        // $n = 0;
        // if($pro_params != '0') {
        //     $pro_params = json_decode($pro_params, true);
        //     //判断是否是迁移的
        //     if (array_key_exists('wsh_report', $pro_params)) {
        //         $params['type'] = $pro_params['wsh_report'];
        //     } else {
        //         $params['type'] = 'A';
        //     }
        // }else{
        //     $params['type'] = 'A';
        // }
        // foreach($rows as $i => $j){
        //     $params['check_id'] = $j['check_id'];
        //     $app_id = 'WSH';
        //     if($j['status'] == 1 || $j['status'] == 2){
        //         if($j['save_path'] == ''){
        //             $n++;
        //             $filepath = DownloadPdf::transferDownload($params,$app_id);
        //             if(file_exists($filepath)){
        //                 echo 'OK';
        //             }
        //         }
        //     }
        //     if($n==200){
        //         goto end;
        //     }
        // }
         //5
        $sql = "select * from bac_routine_check where root_proid = $program_id and apply_time like '$date%' ";
        //$sql = "select * from bac_routine_check where root_proid = $program_id and contractor_id = '377' and apply_time like $date.'%'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $n = 0;
        foreach($rows as $i => $j){
            $params['check_id'] = $j['check_id'];
            $app_id = 'ROUTINE';
            if($j['status'] == 1 || $j['status'] == 2){
                if($j['save_path'] == ''){
                    $n++;
                    $filepath = DownloadPdf::transferDownload($params,$app_id);
                    if(file_exists($filepath)){
                        echo 'OK';
                    }
                }
            }
            if($n==200){
                goto end;
            }
        }
        //  //6
        // // $sql = "select * from ra_swp_basic where main_proid = $program_id and contractor_id = '377' ";
        // $sql = "select * from ra_swp_basic where main_proid = $program_id  ";
        // $n = 0;
        // $command = Yii::app()->db->createCommand($sql);
        // $rows = $command->queryAll();
        // foreach($rows as $i => $j){
        //     $params['ra_swp_id'] = $j['ra_swp_id'];
        //     $app_id = 'RA';
        //     if($j['status'] == 4 || $j['status'] == 3){
        //         if($j['save_path'] == ''){
        //             $n++;
        //             $filepath = DownloadPdf::transferDownload($params,$app_id);
        //             if(file_exists($filepath)){
        //                echo 'OK';
        //             }
        //         }
        //     }
        //     if($n==200){
        //         goto end;
        //     }
        // }
        //  //7
        // $sql = "select * from accident_basic where root_proid = $program_id and record_time like '$date%' ";
        // $n = 0;
        // $command = Yii::app()->db->createCommand($sql);
        // $rows = $command->queryAll();
        // if($pro_params != '0') {
        //     $pro_params = json_decode($pro_params, true);
        //     //判断是否是迁移的
        //     if (array_key_exists('acci_report', $pro_params)) {
        //        $params['type'] = $pro_params['acci_report'];
        //     } else {
        //         $params['type'] = 'A';
        //     }
        // }else{
        //     $params['type'] = 'A';
        // }
        // foreach($rows as $i => $j){
        //     $params['id'] = $j['apply_id'];
        //     $app_id = 'ACCI';
        //     if($j['status'] == 1){
        //         if($j['save_path'] == ''){
        //             $n++;
        //             $filepath = DownloadPdf::transferDownload($params,$app_id);
        //             if(file_exists($filepath)){
        //                 echo 'OK';
        //             }
        //         }
        //     }
        //     if($n==200){
        //         goto end;
        //     }
        // }
        end:
        echo 'over';

        // exec("cd /opt/www-nginx/web/filebase/report/pdf/2017/11/ && zip -qr test.zip ./*",$out_put,$result);
        // if($result == 0){
        //     exec("pwd",$out);
        //     exec("zip -qr test.zip ./*",$out,$rs);
        // }
    }

    public function actionBatchExport(){
        set_time_limit(0);
        ini_set("memory_limit","512M");
        $fields = func_get_args();
        $type_list = PtwType::levelText();
        $con_list = Contractor::compList();
        //$region_list = PtwApplyBlock::regionAllList();

        //$args = $_GET['q']; //查询条件
        $args['program_id'] = '1453';
        $args['contractor_id'] = '222';

        $list = ApplyBasic::queryExcelList($args);
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
        foreach ($list['rows'] as $apply_id=>$v){
            // foreach ($list['s'] as $x=>$y){
            //     if($v['apply_id'] == $y['apply_id']){
            //         $position = $y['block'].' '.$y['secondary_region'];
            //     }
            // }
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

            $add_operator = explode('|',$v['add_operator']);
            $step = $add_operator[0];
            $year = substr($v['record_time'],0,4);
            $deal_all = CheckApplyDetail::dealtypeAllList($v['apply_id'],$step,$year);
            $deal_user_model = Staff::model()->findByPk($deal_all[0]['deal_user_id']);
            $deal_user_name = $deal_user_model->user_name;
            $num=$k+4;//从第4行开始写入数据，第一行是表名，第二,三行是表头
            //$count=count($rs["rows"])+3;//数组总个数
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
        //下载输出
        //ob_end_clean();
        //ob_start();
        //header('Content-Type : application/vnd.ms-excel');
        //header('Content-Disposition:attachment;filename="'.'PTW Report – '.$args['month'].'.xlsx"');
        //$objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter = PHPExcel_IOFactory::createWriter($objectPHPExcel, 'Excel2007');
        $objWriter->save('/opt/www-nginx/web/test/ctmgr/Ptw_20210816.xlsx');
        echo 'export success';
    }
}