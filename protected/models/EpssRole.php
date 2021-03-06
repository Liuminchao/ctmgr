<?php

/**
 * EPSS角色管理
 * @author LiuXiaoyuan
 */
class EpssRole extends CActiveRecord {

    const STATUS_NORMAL = '00'; //正常
    const STATUS_STOP = '01'; //停用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_epss_role';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'role_id' => Yii::t('sys_role', 'role_id'),
            'contractor_type' => Yii::t('sys_role', 'contractor_type'),
            'role_name' => Yii::t('sys_role', 'role_name'),
            'role_name_en' => Yii::t('sys_role', 'role_name_en'),
            'team_name' => Yii::t('sys_role', 'team_name'),
            'team_name_en' => Yii::t('sys_role', 'team_name_en'),
            'sort_id' => Yii::t('sys_role', 'order'),
            'status' => Yii::t('sys_role', 'status'),
            'record_time' => Yii::t('sys_role', 'record_time'),
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Role the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_NORMAL => Yii::t('sys_role', 'STATUS_NORMAL'),
            self::STATUS_STOP => Yii::t('sys_role', 'STATUS_STOP'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_NORMAL => 'label-success', //正常
            self::STATUS_STOP => ' label-danger', //停用
        );
        return $key === null ? $rs : $rs[$key];
    }


    //查询角色分组
    public static function roleTeamList(){

        $sql = "SELECT team_id,team_name FROM bac_epss_role WHERE status=00 group by team_name order by sort_id";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['team_id']] = $row['team_name'];
            }
        }
//        $rs['No'] = '-'.Yii::t('sys_role', 'team_name').'-';
        return $rs;
        
    }
    
    public static function roleListByTeam($team_id=''){
            
        $sql = "SELECT role_id, role_name FROM bac_epss_role WHERE status=00";
        if($team_id <> '')
            $sql .= " and team_id='".$team_id."'";
        $sql .= "  order by sort_id";//var_dump($sql);
        
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['role_id']] = $row['role_name'];
            }
        }
        return $rs;
    }
    
    //角色列表
    public static function roleList() {

        $sql = "SELECT role_id,role_name FROM bac_epss_role WHERE status=00 order by sort_id";
        $command = Yii::app()->db->createCommand($sql);

        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['role_id']] = $row['role_name'];
            }
        }

        return $rs;
    }

    //团队列表
    public static function teamListByType($type_id) {

        $sql = "SELECT team_id,team_name FROM bac_epss_role WHERE status=00 and type='".$type_id."' order by sort_id";
        $command = Yii::app()->db->createCommand($sql);

        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['team_id']] = $row['team_name'];
            }
        }
        return $rs;
    }

    
    //角色列表
    public static function roleidListByTeam(){
            
        $sql = "SELECT role_id, role_name FROM bac_epss_role WHERE status=00";
        
        $sql .= "  order by sort_id";//var_dump($sql);
        
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['role_name']] = $row['role_id'];
            }
        }
        return $rs;
    }

    //ftp epss报告
    public static function epssReportByFtp($args){
        $program_id = $args['program_id'];
        $contractor_id = $args['contractor_id'];
        $type_id = $args['type_id'];
        $year = substr(Utils::MonthToCn($args['month']),0,4);
        $month = substr(Utils::MonthToCn($args['month']),5,2);
        $date = $year.$month;
        $rows = ProjectAttend::queryProgramAttend($date,$program_id,$type_id,$contractor_id);
        $pro_model = Program::model()->findByPk($program_id);
        $contractor_id = $pro_model->contractor_id;
        $con_model = Contractor::model()->findByPk($contractor_id);
        $type_list = EpssType::typeListByType($type_id);

        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';

        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        $objectPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objectPHPExcel->getActiveSheet();
        $objActSheet->setTitle('Sheet1');

        //报表头的输出
        $objectPHPExcel->getActiveSheet()->setCellValue('A1','Submission Of Monthly Manpower Usage');
        $objectPHPExcel->getActiveSheet()->getStyle( 'A1')->getFont()->setBold(true);
        $objStyleA1 = $objActSheet->getStyle('A1');
        $objStyleA1->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        //$objectPHPExcel->getActiveSheet()->getStyle('A1'.':'.'I2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        //$objectPHPExcel->getActiveSheet()->getStyle('A4')->getBorders()->getLeft()->getColor()->setARGB('FF993300');
        //字体及颜色
        $objFontA1 = $objStyleA1->getFont();
        $objFontA1->setSize(11);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
        $objectPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->getStyle( 'A3')->getFont()->setSize(11);
        $objectPHPExcel->getActiveSheet()->setCellValue('A3','Builder UEN No.');
        $objectPHPExcel->getActiveSheet()->getStyle('A3')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle( 'A3')->getFont()->setBold(true);
        $objectPHPExcel->getActiveSheet()->getStyle( 'B3')->getFont()->setSize(11);
        $objectPHPExcel->getActiveSheet()->getStyle('B3')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->setCellValue('B3',$con_model->company_sn);
        $objectPHPExcel->getActiveSheet()->getStyle('A4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle( 'A4')->getFont()->setSize(11);
        $objectPHPExcel->getActiveSheet()->setCellValue('A4','Project BP No. ');
        $objectPHPExcel->getActiveSheet()->getStyle( 'A4')->getFont()->setBold(true);
        $objectPHPExcel->getActiveSheet()->getStyle( 'B4')->getFont()->setSize(11);
        $objectPHPExcel->getActiveSheet()->getStyle('B4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->setCellValue('B4',$pro_model->program_bp_no);
        $objectPHPExcel->getActiveSheet()->getStyle( 'B4')->getFont()->setSize(11);
        $objectPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->getStyle( 'A5')->getFont()->setSize(11);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A5','Project Name');
        $objectPHPExcel->getActiveSheet()->getStyle('A5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle( 'A5')->getFont()->setBold(true);
        $objectPHPExcel->getActiveSheet()->getStyle( 'B5')->getFont()->setSize(11);
        $objectPHPExcel->getActiveSheet()->setCellValue('B5',$pro_model->program_name);
        $objectPHPExcel->getActiveSheet()->getStyle('B5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->getStyle( 'A6')->getFont()->setSize(11);
        $objectPHPExcel->getActiveSheet()->setCellValue('A6','Builder');
        $objectPHPExcel->getActiveSheet()->getStyle('A6')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle( 'A6')->getFont()->setBold(true);
        $objectPHPExcel->getActiveSheet()->getStyle( 'B6')->getFont()->setSize(11);
        $objectPHPExcel->getActiveSheet()->setCellValue('B6',$con_model->contractor_name);
        $objectPHPExcel->getActiveSheet()->getStyle('B6')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle('A7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->getStyle( 'A7')->getFont()->setSize(11);
        $objectPHPExcel->getActiveSheet()->setCellValue('A7','Month that Data is submitted for');
        $objectPHPExcel->getActiveSheet()->getStyle('A7')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle( 'A7')->getFont()->setBold(true);
        $objectPHPExcel->getActiveSheet()->setCellValue('B7',$month);
        $objectPHPExcel->getActiveSheet()->getStyle('B7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->getStyle( 'B7')->getFont()->setSize(11);
        $objectPHPExcel->getActiveSheet()->getStyle('B7')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle('A8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->setCellValue('A8','Year that Data is submitted for');
        $objectPHPExcel->getActiveSheet()->getStyle( 'A8')->getFont()->setSize(11);
        $objectPHPExcel->getActiveSheet()->getStyle('A8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle( 'A8')->getFont()->setBold(true);
        $objectPHPExcel->getActiveSheet()->setCellValue('B8',$year);
        $objectPHPExcel->getActiveSheet()->getStyle('B8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle('B8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->getStyle( 'B8')->getFont()->setSize(11);
        $objectPHPExcel->getActiveSheet()->getStyle('A10')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle('A10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->setCellValue('A10','Trades');
        $objectPHPExcel->getActiveSheet()->getStyle( 'A10')->getFont()->setSize(11);
        $objectPHPExcel->getActiveSheet()->getStyle( 'A10')->getFont()->setBold(true);
        $objectPHPExcel->getActiveSheet()->getStyle('A10')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->setCellValue('B10','ManPowerUsed(mandays)');
        $objectPHPExcel->getActiveSheet()->getStyle('B10')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle('B10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->getStyle( 'B10')->getFont()->setSize(11);
        $objectPHPExcel->getActiveSheet()->getStyle( 'B10')->getFont()->setBold(true);

        $x = 11;
        $y = 0;
        $teamname_tmp = '';
        $subtotal_tmp = '';
        foreach($type_list as $team_name => $team_list){
            if($team_name != 'N/A'){
                $y++;
                $subtotal = 0;
                $usrtotal = 0;
                foreach ($team_list as $k => $v) {
                    //$group_name = $item[$v['item_id']]['group_name'];
                    if ($teamname_tmp != $team_name) {
                        $teamname_tmp = $team_name;
                        if ($y > 1) {
                            $objectPHPExcel->getActiveSheet()->getStyle('A' .$x)->getFont()->setSize(11);
                            $objectPHPExcel->getActiveSheet()->setCellValue('A' .$x, $teamname_tmp);
                            $objectPHPExcel->getActiveSheet()->getStyle('A' .$x)->getFont()->setBold(true);
                            $objectPHPExcel->getActiveSheet()->getStyle('A' .$x)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                            $objectPHPExcel->getActiveSheet()->getStyle('B' .$x)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                            $x++;
                        }
                    }
                    $objectPHPExcel->getActiveSheet()->setCellValue('A' .$x,$v);
                    $objectPHPExcel->getActiveSheet()->getStyle('A' .$x)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                    if(array_key_exists($k,$rows)){
                        if($rows[$k]['act_hour'] != '0'){
                            if(!empty($rows[$k])){
                                $subtotal+=$rows[$k]['act_hour'];
                                $act_hour = round($rows[$k]['act_hour'],2);
                                if(array_key_exists('user_list',$rows[$k])){
                                    $user_list = $rows[$k]['user_list'];
                                    $user_list = array_unique($user_list);
                                    $user_cnt = count($user_list);
                                    $usrtotal+=$user_cnt;
                                    $objectPHPExcel->getActiveSheet()->setCellValue('B' .$x,$user_cnt.'('.$act_hour.')');
                                }else{
                                    $objectPHPExcel->getActiveSheet()->setCellValue('B' .$x,$act_hour);
                                }
                            }
                        }
                    }
                    $objectPHPExcel->getActiveSheet()->getStyle('B' .$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objectPHPExcel->getActiveSheet()->getStyle('B'.$x)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                    $x++;
                }
                $subtotal_teamname = 'SubTotal('.$team_name.')';
                $subtotal_tmp = $subtotal_teamname;

                $objectPHPExcel->getActiveSheet()->getStyle('A' .$x)->getFont()->setSize(11);
                $objectPHPExcel->getActiveSheet()->setCellValue('A' .$x, $subtotal_tmp);
                $objectPHPExcel->getActiveSheet()->getStyle('A' .$x)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle('A' .$x)->getFont()->setBold(true);
                $objectPHPExcel->getActiveSheet()->getStyle('B' .$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                if($subtotal != 0){
                    $subtotal = round($subtotal,2);
                    $objectPHPExcel->getActiveSheet()->setCellValue('B' .$x, $usrtotal.'('.$subtotal.')');
                }
                $objectPHPExcel->getActiveSheet()->getStyle('B' .$x)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $x++;
            }
        }
        //下载输出
        @ob_end_clean();
        //ob_start();
        header('Content-Type : application/vnd.ms-excel');
        if($type_id == '1'){
            header('Content-Disposition:attachment;filename="'.'Building Project Manpower (铁路或地轨项目人力统计)Template'.date("d M Y").'.xls"');
        }else if($type_id == '2'){
            header('Content-Disposition:attachment;filename="'.'Rail Project Manpower (铁路或地轨项目人力统计)Template'.date("d M Y").'.xls"');
        }else{
            header('Content-Disposition:attachment;filename="'.'Road Project Manpower (陆路项目人力统计)Template'.date("d M Y").'.xls"');
        }
        $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        if($args['filepath'] != ''){
            $objWriter->save($args['filepath']);
        }else{
            $objWriter->save('php://output');
        }

    }

    public static function SubmitApplicationsEpss($args)
    {
//        $sql = "SELECT * FROM bac_program_user  WHERE user_id = :user_id AND program_id = :program_id ";
//        $command = Yii::app()->db->createCommand($sql);
//        $command->bindParam(":user_id", $args['user_id'], PDO::PARAM_INT);
//        $command->bindParam(":program_id", $args['program_id'], PDO::PARAM_INT);
//        $rows = $command->queryAll();
        $build_role_model = EpssRole::model()->findByPk($args['build_role_id']);
        $rail_role_model = EpssRole::model()->findByPk($args['rail_role_id']);
        $road_role_model = EpssRole::model()->findByPk($args['road_role_id']);
        $build_team_id = $build_role_model->team_id;
        $rail_team_id = $rail_role_model->team_id;
        $road_team_id = $road_role_model->team_id;
        $sql = "UPDATE bac_program_user SET build_role_id=:build_role_id,build_team_id=:build_team_id,rail_role_id=:rail_role_id,rail_team_id=:rail_team_id,road_role_id=:road_role_id,road_team_id=:road_team_id WHERE program_id =:program_id and user_id =:user_id";//var_dump($sql);;;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":build_role_id", $args['build_role_id'], PDO::PARAM_STR);
        $command->bindParam(":build_team_id", $build_team_id, PDO::PARAM_STR);
        $command->bindParam(":rail_role_id", $args['rail_role_id'], PDO::PARAM_STR);
        $command->bindParam(":rail_team_id", $rail_team_id, PDO::PARAM_STR);
        $command->bindParam(":road_role_id", $args['road_role_id'], PDO::PARAM_STR);
        $command->bindParam(":road_team_id", $road_team_id, PDO::PARAM_STR);
        $command->bindParam(":program_id", $args['program_id'], PDO::PARAM_STR);
        $command->bindParam(":user_id", $args['user_id'], PDO::PARAM_STR);

        $rs = $command->execute();

        $r['msg'] = Yii::t('common', 'success_update');
        $r['status'] = 1;
        $r['refresh'] = true;

        return $r;
    }
}
