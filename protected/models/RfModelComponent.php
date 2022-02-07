<?php

/**
 * RFI/RFA
 * @author LiuMinchao
 */
class RfModelComponent extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'rf_model_component';
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
        if($args['uuid']){
            $sql = "insert into rf_model_component (check_id,step,model_id,version,entityId,uuid) values (:check_id,:step,:model_id,:version,:entityId,:uuid)";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
            $command->bindParam(":step", $args['step'], PDO::PARAM_STR);
            $command->bindParam(":model_id", $args['model_component_id'], PDO::PARAM_STR);
            $command->bindParam(":version", $args['version'], PDO::PARAM_STR);
            $command->bindParam(":entityId", $args['entityId'], PDO::PARAM_STR);
            $command->bindParam(":uuid", $args['uuid'], PDO::PARAM_STR);
            $rs = $command->execute();
        }else{
            $rs =1;
        }
        if ($rs) {
            $r['msg'] = Yii::t('common', 'success_insert');
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
    public static function dealList($check_id,$step) {
        $sql = "select * from rf_model_component
                 where check_id=:check_id and step=:step ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $command->bindParam(":step", $step, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }

}
