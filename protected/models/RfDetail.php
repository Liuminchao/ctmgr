<?php

/**
 * RFI/RFA
 * @author LiuMinchao
 */
class RfDetail extends CActiveRecord {

    //状态 0 草稿 1 提交 2 回复 3 转发 4 批准 5 批准(带有评论) 6 拒绝 7 撤销 8 关闭
    const STATUS_DRAFT = '0'; //草稿
    const STATUS_SUBMIT = '1'; //提交
    const STATUS_REPLY = '2'; //回复
    const STATUS_FORWARD = '3'; //转发
    const STATUS_APPROVE = '4'; //批准
    const STATUS_APPROVE_COMMENT = '5'; //批准(带评论)
    const STATUS_REJECT = '6'; //拒绝
    const STATUS_WITHDRAW = '7'; //撤销
    const STATUS_CLOSE = '8'; //关闭
    const STATUS_CONFIRM = '9'; //确认
    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'rf_detail';
    }

    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_SUBMIT =>  'Submit',
            self::STATUS_REPLY => 'Reply',
            self::STATUS_FORWARD =>  'Forward',
            self::STATUS_APPROVE => 'Approve',
            self::STATUS_APPROVE_COMMENT =>  'Approve(with comment)',
            self::STATUS_REJECT => 'Reject',
            self::STATUS_WITHDRAW =>  'Withdraw',
            self::STATUS_CLOSE =>  'close',
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_DRAFT => 'label-info',
            self::STATUS_SUBMIT =>  'label-default',
            self::STATUS_PENDING => 'label-info',
            self::STATUS_CLOSE =>  'label-success',
        );
        return $key === null ? $rs : $rs[$key];
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
        $sql = "insert into rf_detail (check_id,step,user_id,deal_type,remark,status,record_time) values (:check_id,:step,:user_id,:deal_type,:remark,:status,:record_time)";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
        $command->bindParam(":step", $args['step'], PDO::PARAM_STR);
        $command->bindParam(":user_id", $args['operator_id'], PDO::PARAM_STR);
        $command->bindParam(":deal_type", $args['deal_type'], PDO::PARAM_STR);
        $command->bindParam(":remark", $args['message'], PDO::PARAM_STR);
        $command->bindParam(":status", $status, PDO::PARAM_STR);
        $command->bindParam(":record_time", $record_time, PDO::PARAM_STR);
        $rs = $command->execute();
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
        $sql = "select * from rf_detail
                 where check_id=:check_id ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }

    /**
     * 根据步骤查询详情
     */
    public static function dealListByStep($check_id,$step) {
        $sql = "select * from rf_detail
                 where check_id=:check_id and step=:step";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $command->bindParam(":step", $step, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }

    /**
     * 根据类型判断第几步
     */
    public static function stepByType($check_id,$type) {
        $sql = "select * from rf_detail
                 where check_id=:check_id and deal_type=:deal_type";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $command->bindParam(":deal_type", $type, PDO::PARAM_STR);
        $rows = $command->queryAll();
        $step = '';
        foreach ($rows as $i => $j){
            if($j['deal_type'] == $type){
                $step = $j['step'];
            }
        }
        return $step;
    }
}
