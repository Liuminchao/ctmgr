<?php

/**
 * RFI/RFA
 * @author LiuMinchao
 */
class RfUser extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'rf_user';
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ApplyBasicLog the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    //添加
    public static function insertList($args){
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $id = date('Ymd').rand(01,99).date('His');
        $status ='1';

        $record_time = date("Y-m-d H:i:s");
        if($args['to']){
            $staff_model = Staff::model()->findByPk($args['to']);
            $to_name = $staff_model->user_name;
            $type = '1';
            $sql = "insert into rf_user (check_id,step,user_id,user_name,type,tag) values (:check_id,:step,:user_id,:user_name,:type,:tag)";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
            $command->bindParam(":step", $args['step'], PDO::PARAM_STR);
            $command->bindParam(":user_id", $args['to'], PDO::PARAM_STR);
            $command->bindParam(":user_name", $to_name, PDO::PARAM_STR);
            $command->bindParam(":type", $type, PDO::PARAM_STR);
            $command->bindParam(":tag", $args['to_radio'], PDO::PARAM_STR);
            $rs = $command->execute();
        }
        if($args['cc']){
//            $cc = explode(',',$args['cc']);
            foreach($args['cc'] as $j => $cc_id){
                if(is_numeric($cc_id)){
                    $staff_model = Staff::model()->findByPk($cc_id);
                    $cc_name = $staff_model->user_name;
                }else{
                    $cc_name = $cc_id;
                }
                $type = '2';
//                $tag = '0';
                $sql = "insert into rf_user (check_id,step,user_id,user_name,type,tag) values (:check_id,:step,:user_id,:user_name,:type,:tag)";
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
                $command->bindParam(":step", $args['step'], PDO::PARAM_STR);
                $command->bindParam(":user_id", $cc_id, PDO::PARAM_STR);
                $command->bindParam(":user_name", $cc_name, PDO::PARAM_STR);
                $command->bindParam(":type", $type, PDO::PARAM_STR);
                if(array_key_exists('select_cc',$args)){
                    $cc_tag = $args['select_cc'][$cc_id];
                }else{
                    $cc_tag = '3';
                }
                $command->bindParam(":tag", $cc_tag, PDO::PARAM_STR);
                $rs = $command->execute();
            }
        }
        if ($rs) {
            $r['msg'] = Yii::t('common', 'success_insert');
            $r['id'] = $id;
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
//                $trans->rollBack();
            $r['msg'] = Yii::t('common', 'error_insert');
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }

    /**
     * 详情
     */
    public static function dealList($check_id) {
        $sql = "select * from rf_user
                 where check_id=:check_id ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }

    /**
     * 某一步to/cc的人
     */
    public static function userList($check_id,$step,$type) {
        $sql = "select * from rf_user
                 where check_id=:check_id and step=:step and type=:type ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $command->bindParam(":step", $step, PDO::PARAM_STR);
        $command->bindParam(":type", $type, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }

    /**
     * 某一步to/cc的人
     */
    public static function userListByStep($check_id,$step) {
        $sql = "select * from rf_user
                 where check_id=:check_id and step=:step ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $command->bindParam(":step", $step, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }
}
