<?php

/**
 * 分包项目区域
 * @author LiuMinchao
 */
class ProgramRegion extends CActiveRecord {

    const STATUS_NORMAL = 0; //已启用
    const STATUS_DISABLE = 1; //未启用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_program_block';
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Meeting the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
     //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_NORMAL => Yii::t('device', 'STATUS_NORMAL'),
            self::STATUS_DISABLE => Yii::t('device', 'STATUS_DISABLE'),
        );
        return $key === null ? $rs : $rs[$key];
    }
    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_NORMAL => 'label-success', //已启用
            self::STATUS_DISABLE => ' label-danger', //未启用
        );
        return $key === null ? $rs : $rs[$key];
    }
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'region' => Yii::t('proj_project', 'region'),
        );
    }
    //查询项目区域
    public static function regionList($program_id){
         
        $sql = "SELECT location,block,secondary_region FROM bac_program_block WHERE status=0 and program_id = '".$program_id."'  order by block ASC";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['block']][] = $row['secondary_region'];
            }
        }
        return $rs;

    }
    //展示项目区域
    public static function regionShow($program_id){

        $sql = "SELECT location,block,secondary_region FROM bac_program_block WHERE status=0 and program_id = '".$program_id."' order by location asc ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['block']][] = $row['secondary_region'];
            }
        }
        return $rs;

    }

    //展示项目区域固定位置
    public static function locationShow($program_id){

        $sql = "SELECT location,block,secondary_region FROM bac_program_block WHERE status=0 and program_id = '".$program_id."' order by location asc ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['location']][] = $row['secondary_region'];
            }
        }
        return $rs;

    }

    //展示项目区域固定位置
    public static function locationBlock($program_id){

        $sql = "SELECT location,block FROM bac_program_block WHERE status=0 and program_id = '".$program_id."' ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['location']]['block'] = $row['block'];
            }
        }
        return $rs;

    }
    /**
     * 查询总包项目下的分包项目
     */
    public static function ScProgram($program_id){
        $sql = "select program_id
                  from bac_program
                 where  root_proid =".$program_id." and status=00 ";
        //var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;
    }

    //插入区域数据
    public static function InsertRegion($region,$arr){
        $status = self::STATUS_NORMAL;
        $exist_data = ProgramRegion::model()->count('program_id=:program_id and location=:location', array('program_id' => $region['program_id'],'location' => $region['location']));
        if($region['tag'] == ''){
            $r['status'] = -1;
            $r['msg'] = 'The first region cannot be empty';
            $r['refresh'] = false;
            return $r;
        }
        if ($exist_data != 0) {
            $sql = 'DELETE FROM bac_program_block WHERE program_id=:program_id and location=:location';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":program_id", $region['program_id'], PDO::PARAM_INT);
            $command->bindParam(":location", $region['location'], PDO::PARAM_STR);
            $rs = $command->execute();
        }
        $trans = Yii::app()->db->beginTransaction();
        try {
            if(count($arr)>0){
                $tag = 0;
                foreach($arr as $item => $data){
                    if($data != '') {
                        $tag =1;
                        $sub_sql = 'INSERT INTO bac_program_block(program_id,block,location,secondary_region,status) VALUES(:program_id,:block,:location,:secondary_region,:status)';
                        $command = Yii::app()->db->createCommand($sub_sql);
                        $command->bindParam(":program_id", $region['program_id'], PDO::PARAM_INT);
                        $command->bindParam(":block", $region['tag'], PDO::PARAM_INT);
                        $command->bindParam(":location", $region['location'], PDO::PARAM_INT);
                        $command->bindParam(":secondary_region", $data, PDO::PARAM_INT);
                        $command->bindParam(":status", $status, PDO::PARAM_INT);
                        $rs = $command->execute();
                    }
                }
                $secondary_region = '';
                if($tag == 0){
                    $sub_sql = 'INSERT INTO bac_program_block(program_id,block,location,secondary_region,status) VALUES(:program_id,:block,:location,:secondary_region,:status)';
                    $command = Yii::app()->db->createCommand($sub_sql);
                    $command->bindParam(":program_id", $region['program_id'], PDO::PARAM_INT);
                    $command->bindParam(":block", $region['tag'], PDO::PARAM_INT);
                    $command->bindParam(":location", $region['location'], PDO::PARAM_INT);
                    $command->bindParam(":secondary_region", $secondary_region, PDO::PARAM_INT);
                    $command->bindParam(":status", $status, PDO::PARAM_INT);
                    $rs = $command->execute();
                }
            }
            $r['msg'] = Yii::t('common','success_insert');
            $r['status'] = 1;
            $r['refresh'] = true;

            $trans->commit();
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getMessage();
            $r['refresh'] = false;
            $trans->rollback();
        }
        return $r;
    }


    //插入区域数据
    public static function InsertRegion_New($args){
        $status = self::STATUS_NORMAL;
        $program = $args['program'];
        $block = $args['block'];
        $level = $args['level'];
        $trans = Yii::app()->db->beginTransaction();
        try {
            $sql = 'DELETE FROM bac_program_block WHERE program_id=:program_id ';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":program_id", $program[0], PDO::PARAM_INT);
            $rs = $command->execute();

            foreach($block as $i => $j){
//                var_dump($block);
//                var_dump($i);
//                var_dump($j);

                foreach($level[$i] as $x => $y){
//                    var_dump($level[$i]);
//                    var_dump($x);
//                    var_dump($y);
//                    exit;
                    $sub_sql = 'INSERT INTO bac_program_block(program_id,block,secondary_region,status) VALUES(:program_id,:block,:secondary_region,:status)';
                    $command = Yii::app()->db->createCommand($sub_sql);
                    $command->bindParam(":program_id", $program[0], PDO::PARAM_INT);
                    $command->bindParam(":block", $j[0], PDO::PARAM_INT);
                    $command->bindParam(":secondary_region", $y, PDO::PARAM_INT);
                    $command->bindParam(":status", $status, PDO::PARAM_INT);
                    $rs = $command->execute();
                }
            }
            $r['msg'] = Yii::t('common','success_insert');
            $r['status'] = 1;
            $r['refresh'] = true;

            $trans->commit();
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getMessage();
            $r['refresh'] = false;
            $trans->rollback();
        }
        return $r;
    }

    //插入总包区域数据
    public static function InsertMcRegion($args,$program_id) {

        if ($program_id == '') {
            $r['msg'] = Yii::t('proj_project', 'error_id_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
//        if (empty($args)) {
//            $r['msg'] = Yii::t('proj_project', 'block is null');
//            $r['status'] = -1;
//            $r['refresh'] = false;
//            return $r;
//        }
        $exist_data = ProgramRegion::model()->count('program_id=:program_id', array('program_id' => $program_id));
//        var_dump($exist_data);
//        exit;
        if ($exist_data != 0) {
            $sql = 'DELETE FROM bac_program_block WHERE program_id=:program_id';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":program_id", $program_id, PDO::PARAM_INT);
            $rs = $command->execute();
        }
        $sc_program = self::ScProgram($program_id);
        $trans = Yii::app()->db->beginTransaction();

        try {

            $status = self::STATUS_NORMAL;
//            $cnt = count($args['block']);

            foreach($args as $block => $region) {
//                var_dump($block);
//                var_dump($region);
//                exit;
                foreach($region as $num => $content){
                    if($region['tag'] != ''){
                        $tag = $region['tag'];
                    }else{
                        $r['msg'] = Yii::t('proj_project', 'error_block_is_null');
                        $r['status'] = -1;
                        $r['refresh'] = false;
                        return $r;
                    }
                    if(is_numeric($num)) {
                        if ($content != '') {
                            $sub_sql = 'INSERT INTO bac_program_block(program_id,block,location,secondary_region,status) VALUES(:program_id,:block,:location,:secondary_region,:status)';
                            $command = Yii::app()->db->createCommand($sub_sql);
                            $command->bindParam(":program_id", $program_id, PDO::PARAM_INT);
                            $command->bindParam(":block", $tag, PDO::PARAM_INT);
                            $command->bindParam(":location", $block, PDO::PARAM_INT);
                            $command->bindParam(":secondary_region", $content, PDO::PARAM_INT);
                            $command->bindParam(":status", $status, PDO::PARAM_INT);
                            $rs = $command->execute();
                        }
                    }
                    //分包也添加相同区域
//                    foreach($sc_program as $cnt => $id){
//                        $exist_data = ProgramRegion::model()->count('program_id=:program_id', array('program_id' => $id));
//                        if ($exist_data != 0) {
//                            $sql = 'DELETE FROM bac_program_block WHERE program_id=:program_id';
//                            $command = Yii::app()->db->createCommand($sql);
//                            $command->bindParam(":program_id", $id, PDO::PARAM_INT);
//                            $rs = $command->execute();
//                        }
//                        if(is_numeric($num)) {
//                            if ($content != '') {
//                                $sub_sql = 'INSERT INTO bac_program_block(program_id,block,location,secondary_region,status) VALUES(:program_id,:block,:location,:secondary_region,:status)';
//                                $command = Yii::app()->db->createCommand($sub_sql);
//                                $command->bindParam(":program_id", $id, PDO::PARAM_INT);
//                                $command->bindParam(":block", $tag, PDO::PARAM_INT);
//                                $command->bindParam(":location", $block, PDO::PARAM_INT);
//                                $command->bindParam(":secondary_region", $content, PDO::PARAM_INT);
//                                $command->bindParam(":status", $status, PDO::PARAM_INT);
//                                $rs = $command->execute();
//                            }
//                        }
//                    }
                }
            }

            $r['msg'] = Yii::t('common','success_insert');
            $r['status'] = 1;
            $r['refresh'] = true;

            $trans->commit();
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getMessage();
            $r['refresh'] = false;
            $trans->rollback();
        }
        return $r;
    }
    //插入分包区域数据
    public static function InsertScRegion($args,$program_id) {

        if ($program_id == '') {
            $r['msg'] = Yii::t('proj_project', 'error_id_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
//        if (empty($args)) {
//            $r['msg'] = Yii::t('proj_project', 'block is null');
//            $r['status'] = -1;
//            $r['refresh'] = false;
//            return $r;
//        }
        $exist_data = ProgramRegion::model()->count('program_id=:program_id', array('program_id' => $program_id));
//        var_dump($exist_data);
//        exit;
        if ($exist_data != 0) {
            $sql = 'DELETE FROM bac_program_block WHERE program_id=:program_id';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":program_id", $program_id, PDO::PARAM_INT);
            $rs = $command->execute();
        }

        $trans = Yii::app()->db->beginTransaction();

        try {

            $status = self::STATUS_NORMAL;
//            $cnt = count($args['block']);

            foreach($args as $block => $region) {
//                var_dump($block);
//                var_dump($region);
//                exit;
                foreach($region as $num => $content){
                    if($region['block'] != ''){
                        $tag = $region['block'];
                    }
                    if(is_numeric($num)) {
                        if ($content != '') {
                            $sub_sql = 'INSERT INTO bac_program_block(program_id,block,location,secondary_region,status) VALUES(:program_id,:block,:location,:secondary_region,:status)';
                            $command = Yii::app()->db->createCommand($sub_sql);
                            $command->bindParam(":program_id", $program_id, PDO::PARAM_INT);
                            $command->bindParam(":block", $tag, PDO::PARAM_INT);
                            $command->bindParam(":location", $block, PDO::PARAM_INT);
                            $command->bindParam(":secondary_region", $content, PDO::PARAM_INT);
                            $command->bindParam(":status", $status, PDO::PARAM_INT);
                            $rs = $command->execute();
                        }
                    }
                }
            }

            $r['msg'] = Yii::t('common','success_insert');
            $r['status'] = 1;
            $r['refresh'] = true;

            $trans->commit();
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getMessage();
            $r['refresh'] = false;
            $trans->rollback();
        }
        return $r;
    }
}
