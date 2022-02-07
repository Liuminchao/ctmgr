<?php

/**
 * 分包类型
 * @author LiuMinchao
 */
class ContractorType extends CActiveRecord {

    const STATUS_NORMAL = 0; //已启用
    const STATUS_DISABLE = 1; //未启用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_contractor_type';
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

    //查询证书类别
    public static function typeList(){

        $sql = "SELECT type_id,type_name FROM bac_contractor_type WHERE status=0 ORDER BY type_id asc";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['type_id']] = $row['type_name'];
            }
        }

        return $rs;

    }

    //查询证书类别
    public static function shortList(){
        $certificate_list = array(
            '1' => 'CSCPM',
            '10'=> 'FSS',
            '11'=> 'SMSEC',
            '12'=> 'MSEC',
            '13'=> 'CSSA',
            '14'=> 'OFA',
            '15'=> 'TCO',
            '16'=> 'MCO',
            '17'=> 'PMO',
            '18'=> 'EXO',
            '19'=> 'FDT',
            '2'=> 'WSHO',
            '20'=> 'MEW (B)',
            '21'=> 'MEW (S)',
            '22'=> 'SSGIC',
            '23'=> 'WAH (SUP)',
            '24'=> 'WAH (ASS)',
            '25'=> 'WAH (MAN)',
            '26'=> 'WELDER',
            '28'=> 'R & S',
            '29'=> 'ECO',
            '3'=> 'AWSHCS',
            '30'=> 'CSOC',
            '31'=> 'SCWWSH',
            '32'=> 'BCSS',
            '41'=> 'LCO',
            '42'=> 'RMC',
            '43'=> 'WSHC',
            '44'=> 'LFS',
            '45'=> 'R',
            '46'=> 'S',
            '47'=> 'EPTOC',
            '48'=> 'SSEC',
            '49'=> 'SSSC',
            '5'=> 'MSAC',
            '50'=> 'WAH (W)',
            '51'=> 'MWAH',
            '52'=> 'CSOC (TUN)',
            '53'=> 'BCSS (TUN)',
            '54'=> 'FSW',
            '55'=> 'WSHO (C)',
            '56'=> 'MHS',
            '57'=> 'CNV',
            '58'=> 'MNV',
            '59'=> 'PWCS',
            '6'=> 'SWCS',
            '60'=> 'CSER',
            '61'=> 'LEW',
        );
        if (Yii::app()->language == 'zh_CN') {
            if (count($certificate_list) > 0) {
                foreach ($certificate_list as $key => $v) {
                    $rs[$key] = $v;
                }
            }
        } else if (Yii::app()->language == 'en_US') {
            if (count($certificate_list) > 0) {
                foreach ($certificate_list as $key => $v) {
                    $rs[$key] = $v;
                }
            }
        }
        return $rs;

    }
}
