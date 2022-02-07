<?php

class ReportController extends AuthBaseController {
    
    public $title_rows = 6;
    //public $per_read_cnt = 5;
    
    //图片对应的存储文件路径
    private static function TypePath(){
        return array(
            'face_img'  =>  'face',
            'ppt_photo' =>  'staff',
            'home_id_photo' => 'staff',
            'bca_photo' =>  'cert',
            'csoc_photo'=>  'cert',
            'task_attach'=> 'cert',
        );
    }
    //图片对应的存储文件后缀名称
    private static function TypeSuffix(){
        return array(
            'face_img'  =>  'face',
            'ppt_photo' =>  'pass',
            'home_id_photo' => 'per',
            'bca_photo' =>  'bca',
            'csoc_photo'=>  'csoc',
            'task_attach'=> 'task',
        );
    }

    //表头数组
    private static function Excelhead(){
        return array(
            'A' => array('field'=> 'user_name','type'=> 'string','array'=> 'staff','title_cn' => '序号','title_en'=>'S/N'),
            'B' => array('field'=> 'user_name','type'=> 'string','array'=> 'staff','title_cn' => '员工名称','title_en'=>'Name'),
            'C' => array('field' => 'role_id','type'=> 'string','array'=> 'staff','title_cn' => '岗位','title_en'=>'Position'),
        );
    }

    
    public function actionDownload()
    {   
        $file_name = "./template/excel/Staff.xls";
        $file = fopen($file_name, "r"); // 打开文件
        header('Content-Encoding: none');
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Accept-Length: " . filesize($file_name));
        header('Content-Transfer-Encoding: binary');
//        $name = "员工信息表导入模版".date('YmdHis').".xls";
        if (Yii::app()->language == 'zh_CN') {
            $name = "员工信息表导入模版".".xls";
        }else{
            $name = "BatchingTemplate(multiple upload)".".xls";
        }

        header("Content-Disposition: attachment; filename=" . $name); //以真实文件名提供给浏览器下载
        header('Pragma: no-cache');
        header('Expires: 0');
        echo fread($file, filesize($file_name));
        fclose($file);
    }
    public function actionView() {
        $this->render("batch");
    }

    //导出员工信息表
    public function actionExport(){
        $args = $_GET['q'];
        $args['status'] = '0';
        $certificate_list = CertificateType::shortList();
        $detail_list = UserAptitude::userDetail($args['program_id'],$args['con_id']);
        $user_list = Staff::allInfo();
        $role_list = Role::roleList();
//        var_dump($detail_list);
//        exit;
        $args['contractor_type'] = Staff::CONTRACTOR_TYPE_SC;
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $staff = Staff::staffExport($args);
        $category = Staff::Category();
        if (count($staff) > 0) {
            foreach ($staff as $key => $row) {
                $rs[$row['user_id']] = $row['user_id'];
            }
        }
//        var_dump($rs);
//        exit;
        $staffinfo = StaffInfo::staffinfoExport($rs);
//        var_dump($staffinfo);
//        var_dump($staff);
//        exit;
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();
        
        $objectPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objectPHPExcel->getActiveSheet();
        $objActSheet->setTitle('Sheet1');
        
        //获取表头数组
        $rowKey = array();

        $objectPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2','S/N');

        $objectPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B2','NAME');

        $objectPHPExcel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('C2','DESIGNATION');

        $colIndex='D';
        $i = 0;
        $count = count($certificate_list);
        foreach($certificate_list as $k => $v){
            $i++;
            $objectPHPExcel->getActiveSheet()->getStyle($colIndex.'2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($colIndex.'2',$v);
            if($i < $count){
                $colIndex++;
            }
        }
        $objectPHPExcel->getActiveSheet()->mergeCells('A1:C1');
        $objectPHPExcel->getActiveSheet()->mergeCells('D1'.':'.$colIndex.'1');
        //报表头的输出
        $objectPHPExcel->getActiveSheet()->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('D1','MANDATORY SAFETY TRAINING REGISTER');
        $objStyleA1 = $objActSheet->getStyle('D1');
        //字体及颜色
        $objFontA1 = $objStyleA1->getFont();
        $objFontA1->setSize(20);

        $no = 1;
        foreach($detail_list as $user_id => $val){
            $index = 'D';
            $user_info = $user_list[$user_id];
            $objectPHPExcel->getActiveSheet()->setCellValue('A'.($no+2),$no);
            $objectPHPExcel->getActiveSheet()->setCellValue('B'.($no+2),$user_info[0]['user_name']);
            $objectPHPExcel->getActiveSheet()->setCellValue('C'.($no+2),$role_list[$user_info[0]['role_id']]);
            foreach($certificate_list as $k => $v){
                foreach ($val as $certificate_type => $aptitude_name){
                    if($k == $certificate_type){
                        $objectPHPExcel->getActiveSheet()->getStyle($index.($no+2))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FF00FF');
                    }
                }
                $index++;
            }
            $no++;
        }
        $objectPHPExcel->getActiveSheet()->freezePane('A2');
        $objectPHPExcel->getActiveSheet()->freezePane('B2');
        $objectPHPExcel->getActiveSheet()->freezePane('C2');
        $objectPHPExcel->getActiveSheet()->freezePane('D2');
        $objectPHPExcel->getActiveSheet()->freezePane('B3');
        $objectPHPExcel->getActiveSheet()->freezePane('B3');
        $objectPHPExcel->getActiveSheet()->freezePane('C3');
        $objectPHPExcel->getActiveSheet()->freezePane('D3');
//        $objectPHPExcel->getActiveSheet()->freezePane('C');

        //下载输出
        ob_end_clean();
        //ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.'MANDATORY SAFETY TRAINING REGISTER-'.date("d M Y").'.xls"');
        $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
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
