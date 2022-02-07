<?php

/**
 *
 *
 * @author liuminchao
 */
class CheckApplyDetailRa extends CActiveRecord {
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
        return 'bac_check_apply_detail_ra';
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
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
     * 审批结果(快照)
     * @return type
     */
    public static function progressList($app_id, $apply_id) {

        $list = array();

        $sql = "select a.deal_user_id,a.deal_type, a.status, a.deal_time, a.step,a.remark,a.pic,a.address,a.check_list,
                       b.user_name
                  from bac_check_apply_detail_ra a
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


    /**
     * 添加RA申请步骤
     */
    public static function insertRaApplyDetail($ra_swp_id){

        $model = new CheckApplyDetailRa('create');
        $app_id = 'RA';
        $trans = $model->dbConnection->beginTransaction();
        try {
            $model->apply_id = $ra_swp_id;
            $model->app_id = $app_id;
            $model->deal_type = '0';
            $model->step = '1';
            $model->deal_user_id = Yii::app()->user->id;
            $model->status = '0';
            $model->apply_time = date('Y-m-d H:i:s', time());
            $model->deal_time = date('Y-m-d H:i:s', time());
            $result = $model->save();
            $trans->commit();
            if ($result) {
                $r['msg'] = Yii::t('common', 'success_submit');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_submit');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        }catch(Exception $e){
            $trans->rollBack();
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }
    /**
     * 修改RA申请步骤
     */
    public static function updateRaApplyDetail($ra_swp_id){
        $model = RaBasic::model()->findByPk($ra_swp_id);
        $step = $model->current_step;
        $model = new CheckApplyDetailRa('create');
        $app_id = 'RA';
        $trans = $model->dbConnection->beginTransaction();
        try {
            $model->apply_id = $ra_swp_id;
            $model->app_id = $app_id;
            $model->deal_type = '0';
            $model->step = $step + 1;
            $model->deal_user_id = Yii::app()->user->id;
            $model->status = '0';
            $model->apply_time = date('Y-m-d H:i:s', time());
            $model->deal_time = date('Y-m-d H:i:s', time());
            $result = $model->save();
            $trans->commit();
            if ($result) {
                $r['msg'] = Yii::t('common', 'success_apply');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_apply');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        }catch(Exception $e){
            $trans->rollBack();
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }


}
