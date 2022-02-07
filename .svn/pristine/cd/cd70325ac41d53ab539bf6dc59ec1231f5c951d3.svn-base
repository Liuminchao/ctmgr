<?php

/**
 * Qa文档
 * @author LiuMinchao
 */
class QaDocument extends CActiveRecord {

    const STATUS_NORMAL = '0'; //已启用
    const STATUS_DISABLE = '1'; //未启用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'qa_checklist_record_document';
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

    //查询详情
    public static function detailList($check_id){

        $sql = "SELECT * FROM qa_checklist_record_document WHERE  check_id = '".$check_id."' ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;

    }

    //移动文件，添加数据
    public static function movePic($args){
//        $name = substr($file_src,35);
        foreach($args['attachment'] as $i => $file_src){
            $name = substr($file_src,38);

            $upload_path = Yii::app()->params['upload_data_path'];
            $contractor_id = Yii::app()->user->getState('contractor_id');
            $upload = $upload_path . '/qa_doc/' . $contractor_id . '/' .$args['program_id'] .'/';
            if (!file_exists($upload)) {
                umask(0000);
                @mkdir($upload, 0777, true);
            }
            $upload_file = $upload.$name;
            $file_name = explode('.',$name);
            //移动文件到指定目录下
            if (rename($file_src,$upload_file)) {
                $r['src'] = substr($upload_file,18);
                $r['status'] = 1;
                $r['refresh'] = true;
            }else{
                $r['msg'] = "Error moving";
                $r['status'] = -1;
                $r['refresh'] = false;
                return $r;
            }
            $model = new QaDocument('create');
            $record_time = date('Y-m-d H:i:s');
            $model->check_id = $args['check_id'];
            $model->doc_id = 0;
            $size = filesize($upload_file)/1024;
            $model->doc_path = substr($upload_file,18);
            $model->doc_name = $file_name[0];
            $model->doc_type = $file_name[1];
            $model->record_time = $record_time;
            $model->status = self::STATUS_NORMAL;
            $result = $model->save();
            if($result){
                $r['msg'] = Yii::t('common', 'success_insert');
                $r['status'] = 1;
                $r['refresh'] = true;
            }
        }

        return $r;
    }
}
