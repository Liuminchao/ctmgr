<?php

/**
 *
 *
 * @author liuminchao
 */
class CheckApplyDetail extends CActiveRecord {
   //状态:0 申请  1 申请审批   2 申请批准  3关闭   4 关闭审批   5  关闭批准   6 强制驳回
    const STATUS_APPLY = '0'; //申请
    const STATUS_APPLY_ACCESS = '1'; //申请审批
    const STATUS_APPLY_APPROVE = '2'; //申请批准
    const STATUS_CLOSE = '3'; //关闭
    const STATUS_CLOSE_ACCESS = '4'; //关闭审批
    const STATUS_CLOSE_APPROVE = '5'; //关闭批准
    const STATUS_REVOKED = '6';//驳回
    const STATUS_ALTER = '7';//修改
    const STATUS_FIRST_APPLY_ACCESS = '8';//申请预审
    const STATUS_FIRST_CLOSE_ACCESS = '9';//关闭预审
    const STATUS_ACKNOWLEDG = '10';//确认

    const RESULT_WAIT = 0;//待处理
    const RESULT_YES = 1;//成功　
    const RESULT_NO = 2;//拒绝

    public function tableName() {
        return 'bac_check_apply_detail';
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_APPLY => Yii::t('check_apply', 'STATUS_APPLY'),
            // self::STATUS_APPLY_ACCESS => Yii::t('check_apply', 'STATUS_APPLY_ACCESS'),
            self::STATUS_APPLY_ACCESS => Yii::t('check_apply', 'STATUS_APPLY_VERIFIED'),
            self::STATUS_FIRST_APPLY_ACCESS => Yii::t('check_apply', 'STATUS_APPLY_ACCESS2'),
            self::STATUS_APPLY_APPROVE => Yii::t('check_apply', 'STATUS_APPLY_APPROVE'),
            self::STATUS_CLOSE => Yii::t('check_apply', 'STATUS_CLOSE'),
            self::STATUS_CLOSE_ACCESS => Yii::t('check_apply', 'STATUS_CLOSE_ACCESS'),
            self::STATUS_FIRST_CLOSE_ACCESS => Yii::t('check_apply', 'STATUS_CLOSE_ACCESS'),
            self::STATUS_CLOSE_APPROVE => Yii::t('check_apply', 'STATUS_CLOSE_APPROVE'),
            self::STATUS_REVOKED => Yii::t('check_apply', 'STATUS_REVOKED'),
            self::STATUS_ALTER => Yii::t('check_apply','STATUS_ALTER'),
            self::STATUS_ACKNOWLEDG => Yii::t('check_apply','STATUS_ACKNOWLEDG'),

        );
        return $key === null ? $rs : $rs[$key];
    }

    //等待状态
    public static function pendingText($key = null) {
        $rs = array(
            '2' => Yii::t('check_apply', 'Pending_APPLY_ACCESS'),
            '3' => Yii::t('check_apply', 'Pending_APPLY_APPROVE'),
            '4' => Yii::t('check_apply', 'Pending_CLOSE'),
            '5' => Yii::t('check_apply', 'Pending_CLOSE_ACCESS'),
            '6' => Yii::t('check_apply', 'Pending_CLOSE_APPROVE'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //等待状态
    public static function pendingPtwText($key = null) {
        $rs = array(
            'A'=>array(
                '2' => Yii::t('check_apply', 'Pending_APPLY_ACCESS'),
                '3' => Yii::t('check_apply', 'Pending_APPLY_APPROVE'),
                '4' => Yii::t('check_apply', 'Pending_CLOSE'),
                '5' => Yii::t('check_apply', 'Pending_CLOSE_ACCESS'),
                '6' => Yii::t('check_apply', 'Pending_CLOSE_APPROVE'),
            ),
            'B'=>array(
                '2' => Yii::t('check_apply', 'Pending_APPLY_ACCESS'),
                '3' => Yii::t('check_apply', 'Pending_APPLY_ACCESS2'),
                '4' => Yii::t('check_apply', 'Pending_APPLY_APPROVE'),
                '5' => Yii::t('check_apply', 'Pending_CLOSE_ACCESS'),
                '6' => Yii::t('check_apply', 'Pending_CLOSE_APPROVE'),
            ),
            'C'=>array(
                '2' => Yii::t('check_apply', 'Pending_APPLY_ACCESS'),
                '3' => Yii::t('check_apply', 'Pending_APPLY_APPROVE'),
                '4' => Yii::t('check_apply', 'Pending_CLOSE'),
                '5' => Yii::t('check_apply', 'Pending_CLOSE_ACCESS'),
                '6' => Yii::t('check_apply', 'Pending_CLOSE_APPROVE'),
            ),
        );
        return $key === null ? $rs : $rs[$key];
    }

    public static function resultText($key = null) {
        $rs = array(
            self::RESULT_YES => Yii::t('tbm_meeting', 'agree'),
            self::RESULT_NO => Yii::t('tbm_meeting', 'disagree'),
            self::RESULT_WAIT => Yii::t('tbm_meeting', 'wait'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    public static function dealtypeTxt($key = null) {
        $rs = array(
            self::STATUS_APPLY => 'Submitted',
            self::STATUS_APPLY_ACCESS => 'Assessed',
            self::STATUS_APPLY_APPROVE => 'Approved',
            self::STATUS_CLOSE => 'Closed',
            self::STATUS_CLOSE_ACCESS => 'Closure Accepted',
            self::STATUS_CLOSE_APPROVE=> 'Closure Approved',
            self::STATUS_REVOKED => 'Revoked',
            self::STATUS_ALTER =>  'Revised',
            self::STATUS_FIRST_APPLY_ACCESS => 'Assessed 2',
            self::STATUS_FIRST_CLOSE_ACCESS => 'Approved',
        );
        return $key === null ? $rs : $rs[$key];
    }

    public static function statusTxt($key = null) {
        $rs = array(
            self::STATUS_APPLY => 'Submitted<br>(已申请)',
            self::STATUS_APPLY_ACCESS => 'Assessed<br>(已审批)',
            self::STATUS_FIRST_APPLY_ACCESS => 'Assessed<br>(已审批)',
            self::STATUS_APPLY_APPROVE => 'Approved<br>(已批准)',
            self::STATUS_CLOSE => 'Closed<br>(已关闭申请)',
            self::STATUS_CLOSE_ACCESS => 'Closure Accepted<br>(已关闭审批)',
            self::STATUS_FIRST_CLOSE_ACCESS => 'Assessed<br>(已关闭审批)',
            self::STATUS_CLOSE_APPROVE => 'Closure Approved<br>(已关闭批准)',
            self::STATUS_REVOKED => 'Revoked<br>(强制驳回)',
            self::STATUS_ALTER =>  'Revised<br>(已修改)',
        );
        return $key === null ? $rs : $rs[$key];
    }
    public static function rejectTxt($key = null) {
        $rs = array(
            self::STATUS_APPLY_ACCESS => 'Rejected<br>(审批不通过)',
            self::STATUS_FIRST_APPLY_ACCESS => 'Rejected<br>(审批不通过)',
            self::STATUS_APPLY_APPROVE => 'Rejected<br>(批准不通过)',
            self::STATUS_CLOSE => 'PTW Closed<br>(已关闭申请)',
            self::STATUS_CLOSE_ACCESS => 'Rejected<br>(关闭审批不通过)',
            self::STATUS_FIRST_CLOSE_ACCESS => 'Rejected<br>(关闭审批不通过)',
            self::STATUS_CLOSE_APPROVE => 'Rejected<br>(关闭批准不通过)',
            self::STATUS_REVOKED => 'Revoked<br>(强制驳回)',
        );
        return $key === null ? $rs : $rs[$key];
    }
    public static function resultTxt($key = null) {
        $rs = array(
            self::RESULT_YES => 'Agree(同意)',
            self::RESULT_NO => 'Disagree(拒绝)',
            self::RESULT_WAIT => 'Wait(待处理)',
        );
        return $key === null ? $rs : $rs[$key];
    }

    /**
     * 审批步骤(类型)
     */
    public static function dealtypeList($app_id, $apply_id,$step,$year) {
        $table = "bac_check_apply_detail_" . $year;
        $sql = "select deal_type,status from $table
                 where app_id=:app_id and apply_id=:apply_id and step=:step";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":app_id", $app_id, PDO::PARAM_STR);
        $command->bindParam(":apply_id", $apply_id, PDO::PARAM_STR);
        $command->bindParam(":step", $step, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }
    /**
     * 审批步骤(类型)
     */
    public static function dealtypeAllList($apply_id,$step,$year) {
        $table = "bac_check_apply_detail_" . $year;
        $sql = "select * from $table
                 where apply_id=:apply_id and step=:step";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":apply_id", $apply_id, PDO::PARAM_STR);
        $command->bindParam(":step", $step, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }
    /**
     * 审批结果(快照)
     * @return type
     */
    public static function progressList($app_id, $apply_id,$date) {

        $list = array();
        $table = "bac_check_apply_detail_" . $date;
        $sql = "select a.deal_user_id,a.deal_type, a.status, a.deal_time, a.step,a.remark,a.pic,a.address,a.check_list,
                       b.user_name
                  from $table a
                  left join bac_staff b
                    on a.deal_user_id = b.user_id
                 where a.app_id=:app_id and a.apply_id=:apply_id
                 order by a.step asc";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":app_id", $app_id, PDO::PARAM_STR);
        $command->bindParam(":apply_id", $apply_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }


    //通用版报告状态
    public static function NormalReportStatus($key = null) {
        $rs = array(
            "A"=>array(
                self::STATUS_APPLY => 'Submitted<br>(已申请)',
                self::STATUS_APPLY_ACCESS => 'Assessed<br>(已审批)',
                self::STATUS_FIRST_APPLY_ACCESS => 'Assessed<br>(已审批)',
                self::STATUS_APPLY_APPROVE => 'Approved<br>(已批准)',
                self::STATUS_CLOSE => 'Closed<br>(已关闭申请)',
                self::STATUS_CLOSE_ACCESS => 'Closure Accepted<br>(已关闭批准)',
                self::STATUS_FIRST_CLOSE_ACCESS => 'Assessed<br>(已关闭审批)',
                self::STATUS_CLOSE_APPROVE => 'Closure Approved<br>(已关闭审批)',
                self::STATUS_REVOKED => 'Revoked<br>(强制驳回)',
                self::STATUS_ALTER =>  'Revised<br>(已修改)',
            ),
            "B"=>array(
                self::STATUS_APPLY => 'Submitted<br>(已申请)',
                self::STATUS_APPLY_ACCESS => 'Assessed<br>(已审批)',
                self::STATUS_FIRST_APPLY_ACCESS => 'Assessed<br>(已审批)',
                self::STATUS_APPLY_APPROVE => 'Approved<br>(已批准)',
                self::STATUS_CLOSE => 'Closed<br>(已关闭申请)',
                self::STATUS_CLOSE_ACCESS => 'Closure Accepted<br>(已关闭审批)',
                self::STATUS_FIRST_CLOSE_ACCESS => 'Assessed<br>(已关闭审批)',
                self::STATUS_CLOSE_APPROVE => 'Closure Approved<br>(已关闭批准)',
                self::STATUS_REVOKED => 'Revoked<br>(强制驳回)',
                self::STATUS_ALTER =>  'Revised<br>(已修改)',
            ),
        );
        return $key === null ? $rs : $rs[$key];
    }
    //HDB报告状态
    public static function hdbReportStatus($key = null) {
        $rs = array(
            "A"=>array(
                self::STATUS_APPLY => 'Submitted<br>(已申请)',
                self::STATUS_APPLY_ACCESS => 'Assessed<br>(已审批)',
                self::STATUS_FIRST_APPLY_ACCESS => 'Assessed<br>(已审批)',
                self::STATUS_APPLY_APPROVE => 'Approved<br>(已批准)',
                self::STATUS_CLOSE => 'Closed<br>(已关闭申请)',
                self::STATUS_CLOSE_ACCESS => 'Closure Accepted<br>(已关闭审批)',
                self::STATUS_FIRST_CLOSE_ACCESS => 'Assessed<br>(已关闭审批)',
                self::STATUS_CLOSE_APPROVE => 'Closure Approved<br>(已关闭批准)',
                self::STATUS_REVOKED => 'Revoked<br>(强制驳回)',
                self::STATUS_ALTER =>  'Revised<br>(已修改)',
            ),
            "B"=>array(
                self::STATUS_APPLY => 'Submitted<br>(已申请)',
                self::STATUS_APPLY_ACCESS => 'Assessed<br>(已审批)',
                self::STATUS_FIRST_APPLY_ACCESS => 'Assessed<br>(已审批)',
                self::STATUS_APPLY_APPROVE => 'Approved<br>(已批准)',
                self::STATUS_CLOSE => 'Closed<br>(已关闭申请)',
                self::STATUS_CLOSE_ACCESS => 'Closure Approved<br>(已关闭审批)',
                self::STATUS_FIRST_CLOSE_ACCESS => 'Assessed<br>(已关闭审批)',
                self::STATUS_CLOSE_APPROVE => 'Closure Approved<br>(已关闭批准)',
                self::STATUS_REVOKED => 'Revoked<br>(强制驳回)',
                self::STATUS_ALTER =>  'Revised<br>(已修改)',
            ),
        );
        return $key === null ? $rs : $rs[$key];
    }
}
