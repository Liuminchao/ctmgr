<?php

/**
 * 质量检查详情
 * @author LiuMinchao
 */
class QaCheckDetail extends CActiveRecord {

    //状态：0-进行中，1－已关闭，2-超时强制关闭。
    const STATUS_ONGOING = '0'; //进行中
    const STATUS_CLOSE = '1'; //已关闭
    const STATUS_TIMEOUT_CLOSE = '2'; //超时强制关闭


    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'qa_checklist_record_detail';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(

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

    //根据安全单编号查询安全单步骤
    public static function detailList($check_id){

        $sql = "SELECT * FROM qa_checklist_record_detail WHERE  check_id = '".$check_id."' ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;
    }
    //根据安全单编号查询安全单当前步骤时间
    public static function currentDetail($check_id){

        $sql = "SELECT b.record_time FROM qa_checklist_record a,qa_checklist_record_detail b WHERE  a.check_id = '".$check_id."' and a.current_step = b.step ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;
    }
    //详情
    public static function detailRecord($check_id){

        $sql = "SELECT * FROM qa_checklist_record_detail WHERE  check_id = '".$check_id."' ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;

    }
    //详情
    public static function detaildataRecord($check_id,$data_id){

        $sql = "SELECT * FROM qa_checklist_record_detail WHERE  check_id = '".$check_id."' and data_id = '".$data_id."' ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;

    }
    //处理类型列表
    public static function dealList() {
        $deal_list = array(
            '1'  => '安全检查组->负责人',
            '2'  => '负责人->安全检查组',
            '3'  => '关闭检查单',
            '4'  => '强制关闭检查单',
        );
        return $deal_list;
    }
}
