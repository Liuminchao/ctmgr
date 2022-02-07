<?php

/**
 * 同步任务表
 * @author Liumc
 */
class AtdApiTask extends CActiveRecord {


    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'atd_api_task';
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



}
