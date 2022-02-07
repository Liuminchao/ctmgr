<?php

/**
 * RFI/RFA
 * @author LiuMinchao
 */
class RfAttachment extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'rf_attachment';
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
        if($args['attachment']){
            foreach($args['attachment'] as $i => $file_src){
                $name = $file_src;
                $formal_src = $file_src;
                if(strpos($file_src,'/filebase/data/rf/') == false){
                    $name = substr($file_src,38);
                    $upload_path = Yii::app()->params['upload_data_path'];
                    $contractor_id = Yii::app()->user->getState('contractor_id');
                    $upload = $upload_path . '/rf/' . $contractor_id . '/' .$args['program_id'] .'/';
                    if (!file_exists($upload)) {
                        umask(0000);
                        @mkdir($upload, 0777, true);
                    }
                    $upload_file = $upload.$name;
                    //移动文件到指定目录下
                    if (rename($file_src,$upload_file)) {
                        $formal_src = substr($upload_file,18);
                    }else{
                        goto end;
                    }
                }
                $sql = "insert into rf_attachment (check_id,step,doc_name,doc_path) values (:check_id,:step,:doc_name,:doc_path)";
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
                $command->bindParam(":step", $args['step'], PDO::PARAM_STR);
                $command->bindParam(":doc_name", $name, PDO::PARAM_STR);
                $command->bindParam(":doc_path", $formal_src, PDO::PARAM_STR);
                $rs = $command->execute();
                end:
            }
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
    public static function dealList($check_id) {
        $sql = "select * from rf_attachment
                 where check_id=:check_id ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }

    /**
     * 详情
     */
    public static function dealListBystep($check_id,$step) {
        $sql = "select * from rf_attachment
                 where check_id=:check_id and step=:step";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $command->bindParam(":step", $step, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }

}
