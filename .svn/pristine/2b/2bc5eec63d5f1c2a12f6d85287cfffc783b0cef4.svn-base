<?php

/**
 * RFI/RFA
 * @author LiuMinchao
 */
class RfList extends CActiveRecord {

    //状态
    const STATUS_DRAFT = '-1'; //草稿
    const STATUS_SUBMIT = '0'; //提交
    const STATUS_PENDING = '1'; //已回复
    const STATUS_APPROVE = '2'; //批准
    const STATUS_CLOSE = '3'; //关闭
    const STATUS_REJECT = '4'; //拒绝
    const STATUS_CONFIRM = '5'; //确认
    const STATUS_APPROVE_COMMENT = '6'; //批准带回复
    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'rf_list';
    }

    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_SUBMIT =>  'Sent',
            self::STATUS_PENDING => 'Replied',
            self::STATUS_CLOSE =>  'Closed',
            self::STATUS_APPROVE => 'Approve',
            self::STATUS_REJECT => 'Reject',
            self::STATUS_CONFIRM=> 'Read',
            self::STATUS_APPROVE_COMMENT =>'Approve With Comment',
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
            self::STATUS_APPROVE => 'label-success',
            self::STATUS_REJECT => 'label-warning',
            self::STATUS_APPROVE_COMMENT =>'label-success',
            self::STATUS_CONFIRM=> 'label-info',
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

    /**
     * 查询
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function queryList($page, $pageSize, $args = array()) {

        $condition = '';
        $params = array();
        $sql = "select * from rf_list where ";

        $program_id = $args['program_id'];
        $pro_model =Program::model()->findByPk($args['program_id']);
        $program_id = $pro_model->root_proid;
        $type_id = $args['type'];

        $condition.= " program_id = '$program_id' ";
        if($type_id != '3'){
            $condition.= " and type = '$type_id'";
        }
        if ($args['con_id'] != '') {
            $con_id = $args['con_id'];
            $condition.= " and contractor_id = '$con_id'";
        }
        if ($args['status'] != '') {
            $status = $args['status'];
            $condition.= " and status = '$status'";
        }
        if ($args['start_date'] != '') {
            $start_date = Utils::DateToCn($args['start_date']);
            $condition .= " and record_time >='$start_date'";
        }

        if ($args['end_date'] != '') {
            $end_date = Utils::DateToCn($args['end_date']);
            $condition .= " and record_time <='$end_date 23:59:59'";
        }

        $operator_id = Yii::app()->user->id;
        $operator_model = Operator::model()->findByPk($operator_id);
        $operator_role = $operator_model->operator_role;

        if($operator_role == '01'){
            $user = Staff::userByPhone($operator_id);
            $to_user = $user[0]['user_id'];
            $add_user = $user[0]['user_id'];
            $cc_user = $user[0]['user_id'];
            $condition.= " and ( to_user ='$to_user' OR add_user ='$add_user' OR cc_user ='$cc_user' ) ";
        }
        $sql = $sql.$condition;
        $command = Yii::app()->db->createCommand($sql);
        $data = $command->queryAll();

        $start=$page*$pageSize; #计算每次分页的开始位置
        $count = count($data);
        $pagedata=array();
        if($count>0){
            $pagedata=array_slice($data,$start,$pageSize);
        }else{
            $pagedata = array();
        }

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $count;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $pagedata;

        return $rs;
    }

    //添加
    public static function insertList($args){
        if(!array_key_exists('select_cc',$args)){
            $r['msg'] = 'Please select Copy to user.';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $id = date('Ymd').rand(01,99).date('His');
        $add_user = Yii::app()->user->id;
        $status =$args['status'];
        $record_time = date("Y-m-d H:i:s");
        $valid_time = Utils::DateToCn($args['valid_time']);
        $to_user = $args['to'];
        $cc_user = implode(',',$args['cc']);
        $step = 1;
        if($args['type_id'] == '1'){
            $args['spec_ref'] = '';
        }
        $sql = "insert into rf_list (check_id,check_no,data_id,step,add_user,to_user,cc_user,program_id,contractor_id,location_ref,subject,related_to,valid_time,spec_ref,others,type,status,record_time) values (:check_id,:check_no,:data_id,:step,:add_user,:to_user,:cc_user,:program_id,:contractor_id,:location_ref,:subject,:related_to,:valid_time,:spec_ref,:others,:type,:status,:record_time)";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
        $command->bindParam(":check_no", $args['check_no'], PDO::PARAM_STR);
        $command->bindParam(":data_id", $args['data_id'], PDO::PARAM_STR);
        $command->bindParam(":step", $step, PDO::PARAM_INT);
        $command->bindParam(":add_user", $args['from'], PDO::PARAM_INT);
        $command->bindParam(":to_user", $to_user, PDO::PARAM_INT);
        $command->bindParam(":cc_user", $cc_user, PDO::PARAM_INT);
        $command->bindParam(":program_id", $args['program_id'], PDO::PARAM_STR);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_STR);
        $command->bindParam(":location_ref", $args['location'], PDO::PARAM_STR);
        $command->bindParam(":subject", $args['subject'], PDO::PARAM_STR);
        $command->bindParam(":valid_time", $valid_time, PDO::PARAM_STR);
        $command->bindParam(":related_to", $args['related'], PDO::PARAM_STR);
        $command->bindParam(":spec_ref", $args['spec_ref'], PDO::PARAM_STR);
        $command->bindParam(":others", $args['others'], PDO::PARAM_STR);
        $command->bindParam(":type", $args['type_id'], PDO::PARAM_STR);
        $command->bindParam(":status", $status, PDO::PARAM_STR);
        $command->bindParam(":record_time", $record_time, PDO::PARAM_STR);
        $rs = $command->execute();
        if ($rs) {
            $args['step'] = 1;
            $user_phone = Yii::app()->user->id;
            $user = Staff::userByPhone($user_phone);
            if(count($user)>0){
                $user_model = Staff::model()->findByPk($user[0]['user_id']);
                $args['operator_id'] = $user_model->user_id;
            }else{
                $args['operator_id'] = Yii::app()->user->id;
            }
            $args['remarks'] = '';
            if($args['type_id'] == 1){
                $args['to_radio'] = '1';
            }else{
                $args['to_radio'] = '2';
            }
            $r = RfDetail::insertList($args);
            $r = RfUser::insertList($args);
            if(count($args['attachment'])>0){
                $r = RfAttachment::insertList($args);
            }
            if($args['view']>0){
                $r = RfModelView::insertList($args);
            }
            if($args['uuid']>0){
                $r = RfModelComponent::insertList($args);
            }
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
    //发起
    public static function sendList($args){
        if(!array_key_exists('select_cc',$args)){
            $r['msg'] = 'Please select Copy to user.';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $id = date('Ymd').rand(01,99).date('His');
        $status =$args['status'];
        $record_time = date("Y-m-d H:i:s");
        $valid_time = Utils::DateToCn($args['valid_time']);
        $to_user = $args['to'];
        $cc_user = implode(',',$args['cc']);
        $rf_list = RfList::model()->findByPk($args['check_id']);
        $step = $rf_list->step;
        $data_id = $rf_list->data_id;
        $step = $step+1;
        $add_user = Yii::app()->user->id;
        if($args['type_id'] == '1'){
            $args['spec_ref'] = '';
        }
        $sql = "update rf_list set check_no =:check_no,data_id = :data_id,step=:step,add_user=:add_user,to_user=:to_user,cc_user=:cc_user,program_id = :program_id,contractor_id = :contractor_id,location_ref = :location_ref,subject = :subject,related_to = :related_to,valid_time = :valid_time,spec_ref = :spec_ref,others = :others,type = :type,status = :status,record_time = :record_time  where check_id = :check_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
        $command->bindParam(":check_no", $args['check_no'], PDO::PARAM_STR);
        $command->bindParam(":data_id", $data_id, PDO::PARAM_STR);
        $command->bindParam(":step", $step, PDO::PARAM_INT);
        $command->bindParam(":add_user", $args['from'], PDO::PARAM_INT);
        $command->bindParam(":to_user", $to_user, PDO::PARAM_INT);
        $command->bindParam(":cc_user", $cc_user, PDO::PARAM_INT);
        $command->bindParam(":program_id", $args['program_id'], PDO::PARAM_STR);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_STR);
        $command->bindParam(":location_ref", $args['location'], PDO::PARAM_STR);
        $command->bindParam(":subject", $args['subject'], PDO::PARAM_STR);
        $command->bindParam(":related_to", $args['related'], PDO::PARAM_STR);
        $command->bindParam(":valid_time", $valid_time, PDO::PARAM_STR);
        $command->bindParam(":spec_ref", $args['spec_ref'], PDO::PARAM_STR);
        $command->bindParam(":others", $args['others'], PDO::PARAM_STR);
        $command->bindParam(":type", $args['type_id'], PDO::PARAM_STR);
        $command->bindParam(":status", $status, PDO::PARAM_STR);
        $command->bindParam(":record_time", $record_time, PDO::PARAM_STR);
        $rs = $command->execute();
        if ($rs) {
            $args['step'] = 2;
            $user_phone = Yii::app()->user->id;
            $user = Staff::userByPhone($user_phone);
            if(count($user)>0){
                $user_model = Staff::model()->findByPk($user[0]['user_id']);
                $args['operator_id'] = $user_model->user_id;
            }else{
                $args['operator_id'] = Yii::app()->user->id;
            }
            $args['deal_type'] = RfDetail::STATUS_SUBMIT;
            if($args['type_id'] == 1){
                $args['to_radio'] = '1';
            }else{
                $args['to_radio'] = '2';
            }
            $r = RfDetail::insertList($args);
            $r = RfUser::insertList($args);
            if(count($args['attachment'])>0){
                $r = RfAttachment::insertList($args);
            }
            if($args['view']){
                $r = RfModelView::insertList($args);
            }
            if($args['uuid']){
                $r = RfModelComponent::insertList($args);
            }
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
    //回复
    public static function replyList($args){
        if(!array_key_exists('select_cc',$args)){
            $r['msg'] = 'Please select Copy to user.';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        $rf_model = RfList::model()->findByPk($args['check_id']);
        $step = $rf_model->step;
        $args['step'] = $step+1;
        $status = self::STATUS_PENDING;
        $sql = "update rf_list set step=:step,status=:status where check_id = :check_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
        $command->bindParam(":step", $args['step'], PDO::PARAM_STR);
        $command->bindParam(":status", $status, PDO::PARAM_STR);
        $rs = $command->execute();
        if ($rs) {
            $user_phone = Yii::app()->user->id;
            $user = Staff::userByPhone($user_phone);
            if(count($user)>0){
                $user_model = Staff::model()->findByPk($user[0]['user_id']);
                $args['operator_id'] = $user_model->user_id;
            }else{
                $args['operator_id'] = Yii::app()->user->id;
            }
            $args['deal_type'] = RfDetail::STATUS_REPLY;
            if($rf_model->type == 1){
                $args['to_radio'] = '1';
            }
            $r = RfDetail::insertList($args);
            $r = RfUser::insertList($args);
            if($args['attachment']){
                $r = RfAttachment::insertList($args);
            }
            if($args['view']){
                $r = RfModelView::insertList($args);
            }
            if($args['uuid']){
                $r = RfModelComponent::insertList($args);
            }
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
    //转发
    public static function forwardList($args){
        $rf_model = RfList::model()->findByPk($args['check_id']);
        $step = $rf_model->step;
        $args['step'] = $step+1;
        $status = self::STATUS_PENDING;
        $sql = "update rf_list set step=:step,status=:status,reply_attachment=:reply_attachment where check_id = :check_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
        $command->bindParam(":step", $args['step'], PDO::PARAM_STR);
        $command->bindParam(":status", $status, PDO::PARAM_STR);
        $command->bindParam(":reply_attachment", $args['reply_attachment'], PDO::PARAM_STR);
        $rs = $command->execute();
        if ($rs) {
            $user_phone = Yii::app()->user->id;
            $user = Staff::userByPhone($user_phone);
            if(count($user)>0){
                $user_model = Staff::model()->findByPk($user[0]['user_id']);
                $args['operator_id'] = $user_model->user_id;
            }else{
                $args['operator_id'] = Yii::app()->user->id;
            }
            $args['deal_type'] = RfDetail::STATUS_FORWARD;
            $r = RfDetail::insertList($args);
            $r = RfUser::insertList($args);
            if($args['attachment']){
                $r = RfAttachment::insertList($args);
            }
            if($args['view']){
                $r = RfModelView::insertList($args);
            }
            if($args['uuid']){
                $r = RfModelComponent::insertList($args);
            }
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
    //批准
    public static function approveList($args){
        if(!array_key_exists('select_cc',$args)){
            $r['msg'] = 'Please select Copy to user.';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        $rf_model = RfList::model()->findByPk($args['check_id']);
        $step = $rf_model->step;
        $args['step'] = $step+1;
        $status = RfList::STATUS_APPROVE;
        $sql = "update rf_list set step=:step,status=:status,reply_attachment=:reply_attachment where check_id = :check_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
        $command->bindParam(":step", $args['step'], PDO::PARAM_INT);
        $command->bindParam(":status", $status, PDO::PARAM_STR);
        $command->bindParam(":reply_attachment", $args['reply_attachment'], PDO::PARAM_STR);
        $rs = $command->execute();
        if ($rs) {
            $user_phone = Yii::app()->user->id;
            $user = Staff::userByPhone($user_phone);
            if(count($user)>0){
                $user_model = Staff::model()->findByPk($user[0]['user_id']);
                $args['operator_id'] = $user_model->user_id;
            }else{
                $args['operator_id'] = Yii::app()->user->id;
            }
            $args['deal_type'] = RfDetail::STATUS_APPROVE;
            $args['to_radio'] = '0';
            $r = RfDetail::insertList($args);
            $r = RfUser::insertList($args);
            if($args['attachment']){
                $r = RfAttachment::insertList($args);
            }
            if($args['view']){
                $r = RfModelView::insertList($args);
            }
            if($args['uuid']){
                $r = RfModelComponent::insertList($args);
            }
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
    //批准(带评论)
    public static function approveCommentList($args){
        if(!array_key_exists('select_cc',$args)){
            $r['msg'] = 'Please select Copy to user.';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        $rf_model = RfList::model()->findByPk($args['check_id']);
        $step = $rf_model->step;
        $args['step'] = $step+1;
        $status = RfList::STATUS_APPROVE_COMMENT;
        $sql = "update rf_list set step=:step,status=:status,reply_attachment=:reply_attachment where check_id = :check_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
        $command->bindParam(":step", $args['step'], PDO::PARAM_STR);
        $command->bindParam(":status", $status, PDO::PARAM_STR);
        $command->bindParam(":reply_attachment", $args['reply_attachment'], PDO::PARAM_STR);
        $rs = $command->execute();
        if ($rs) {
            $user_phone = Yii::app()->user->id;
            $user = Staff::userByPhone($user_phone);
            if(count($user)>0){
                $user_model = Staff::model()->findByPk($user[0]['user_id']);
                $args['operator_id'] = $user_model->user_id;
            }else{
                $args['operator_id'] = Yii::app()->user->id;
            }
            $args['deal_type'] = RfDetail::STATUS_APPROVE_COMMENT;
            $args['to_radio'] = '0';
            $r = RfDetail::insertList($args);
            $r = RfUser::insertList($args);
            if($args['attachment']){
                $r = RfAttachment::insertList($args);
            }
            if($args['view']){
                $r = RfModelView::insertList($args);
            }
            if($args['uuid']){
                $r = RfModelComponent::insertList($args);
            }
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

    //拒绝
    public static function rejectList($args){
        if(!array_key_exists('select_cc',$args)){
            $r['msg'] = 'Please select Copy to user.';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        $rf_model = RfList::model()->findByPk($args['check_id']);
        $step = $rf_model->step;
        $args['step'] = $step+1;
        $status = RfList::STATUS_REJECT;
        $sql = "update rf_list set step=:step,status=:status where check_id = :check_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
        $command->bindParam(":step", $args['step'], PDO::PARAM_STR);
        $command->bindParam(":status", $status, PDO::PARAM_STR);
        $rs = $command->execute();
        if ($rs) {
            $user_phone = Yii::app()->user->id;
            $user = Staff::userByPhone($user_phone);
            if(count($user)>0){
                $user_model = Staff::model()->findByPk($user[0]['user_id']);
                $args['operator_id'] = $user_model->user_id;
            }else{
                $args['operator_id'] = Yii::app()->user->id;
            }
            $args['deal_type'] = RfDetail::STATUS_REJECT;
            $args['to_radio'] = '0';
            $r = RfDetail::insertList($args);
            $r = RfUser::insertList($args);
            if($args['attachment']){
                $r = RfAttachment::insertList($args);
            }
            if($args['view']){
                $r = RfModelView::insertList($args);
            }
            if($args['uuid']){
                $r = RfModelComponent::insertList($args);
            }
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

    //撤回
    public static function withdrawList($args){
        $rf_model = RfList::model()->findByPk($args['check_id']);
        $step = $rf_model->step;
        $args['step'] = $step+1;
        $status = RfList::STATUS_DRAFT;
        $sql = "update rf_list set step=:step,status=:status where check_id = :check_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":step", $args['step'], PDO::PARAM_STR);
        $command->bindParam(":status", $status, PDO::PARAM_STR);
        $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
        $rs = $command->execute();
        if ($rs) {
            $user_phone = Yii::app()->user->id;
            $user = Staff::userByPhone($user_phone);
            if(count($user)>0){
                $user_model = Staff::model()->findByPk($user[0]['user_id']);
                $args['operator_id'] = $user_model->user_id;
            }else{
                $args['operator_id'] = Yii::app()->user->id;
            }
            $args['deal_type'] = RfDetail::STATUS_WITHDRAW;
            $args['message'] = '';
            $r = RfDetail::insertList($args);
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

    //确认
    public static function confirmList($args){
        $rf_model = RfList::model()->findByPk($args['check_id']);
        $step = $rf_model->step;
        $args['step'] = $step+1;
        $status = RfList::STATUS_CONFIRM;
        $sql = "update rf_list set step=:step,status=:status where check_id = :check_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":step", $args['step'], PDO::PARAM_STR);
        $command->bindParam(":status", $status, PDO::PARAM_STR);
        $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
        $rs = $command->execute();
        if ($rs) {
            $user_phone = Yii::app()->user->id;
            $user = Staff::userByPhone($user_phone);
            if(count($user)>0){
                $user_model = Staff::model()->findByPk($user[0]['user_id']);
                $args['operator_id'] = $user_model->user_id;
            }else{
                $args['operator_id'] = Yii::app()->user->id;
            }
            $args['deal_type'] = RfDetail::STATUS_CONFIRM;
            $args['message'] = '';
            $r = RfDetail::insertList($args);
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

    //关闭
    public static function closeList($args){
        $rf_model = RfList::model()->findByPk($args['check_id']);
        $step = $rf_model->step;
        $args['step'] = $step+1;
        $status = RfList::STATUS_CLOSE;
        $sql = "update rf_list set step=:step,status=:status where check_id = :check_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
        $command->bindParam(":step", $args['step'], PDO::PARAM_STR);
        $command->bindParam(":status", $status, PDO::PARAM_STR);
        $rs = $command->execute();
        if ($rs) {
            $user_phone = Yii::app()->user->id;
            $user = Staff::userByPhone($user_phone);
            if(count($user)>0){
                $user_model = Staff::model()->findByPk($user[0]['user_id']);
                $args['operator_id'] = $user_model->user_id;
            }else{
                $args['operator_id'] = Yii::app()->user->id;
            }
            $args['deal_type'] = RfDetail::STATUS_CLOSE;
            $args['message'] = '';
            $r = RfDetail::insertList($args);
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

    //查询数据表索引
    public static function queryIndex(){
        $sql = "select max(data_id) from rf_list ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                if($row['max(data_id)'] != 'NULL'){
                    $data_id = $row['max(data_id)']+1;
                }else{
                    $data_id = 1;
                }
            }
        }

        return $data_id;
    }
    //结束
    public static function endList($args){

        $contractor_id = Yii::app()->user->getState('contractor_id');
        $status = self::STATUS_FINISH;
        $sql = "UPDATE bac_rfi_list SET status = '".$status."' WHERE check_id = '".$args['check_id']."' and program_id ='".$args['program_id']."'  ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->execute();
        if ($rows) {
            $r['msg'] = Yii::t('common', 'success_update');
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
//                $trans->rollBack();
            $r['msg'] = Yii::t('common', 'error_update');
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }

    /**
     * 详情
     */
    public static function dealList($check_id) {
        $sql = "select * from rf_list
                 where check_id=:check_id ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }

    /**
     * 查询人员操作的权限
     *
     */
    public static function permissionsInfo($check_id,$operator_id) {
        $rf_model = RfList::model()->findByPk($check_id);
        $status = $rf_model->status;
        $step = $rf_model->step;
        if($status == '5'){
            $step = $step -1;
        }
        $type = $rf_model->type;
        if(is_numeric($operator_id)){
            $info = Staff::userByPhone($operator_id);
            $user_id = $info[0]['user_id'];
        }else{
            $user_id = $operator_id;
        }
        if($type == '1'){
            $sql = "select * from rf_user
                 where check_id=:check_id and user_id=:user_id and type = '1' order by step asc";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
            $command->bindParam(":user_id", $user_id, PDO::PARAM_STR);
            $rows = $command->queryAll();
            foreach($rows as $i =>$j){
                $tag = $j['tag'];
            }
            if (count($rows) > 0) {
                $r['rf'] = $type;
                $r['tag'] = $tag;
                $r['type'] = '1';
            }else{
                $r['rf'] = '0';
                $r['tag'] = '0';
                $r['type'] = '0';
            }
        }else{
            $sql = "select * from rf_user
                 where check_id=:check_id and step=:step and user_id=:user_id and type = '1'";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
            $command->bindParam(":step", $step, PDO::PARAM_STR);
            $command->bindParam(":user_id", $user_id, PDO::PARAM_STR);
            $rows = $command->queryAll();
            if (count($rows) > 0) {
                $r['rf'] = $type;
                $r['tag'] = $rows[0]['tag'];
                $r['type'] = $rows[0]['type'];
            }else{
                $r['rf'] = '0';
                $r['tag'] = '0';
                $r['type'] = '0';
            }
        }
        return $r;
    }

    //下载PDF
    public static function downloadPDF($params,$app_id){
        $filepath = self::downloaddefaultPDF($params,$app_id);//通用
        return $filepath;
    }

    public static function updatePath($check_id,$save_path) {
        $save_path = substr($save_path,18);
        $model = RfList::model()->findByPk($check_id);
        $model->save_path = $save_path;
        $result = $model->save();
    }

    //下载默认PDF
    public static function downloaddefaultPDF($params,$app_id){

        $id = $params['id'];
        $rf_model = RfList::model()->findByPk($id);
        $program_id = $rf_model->program_id;
        $contractor_id = $rf_model->contractor_id;
        $lang = "_en";
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($rf_model->record_time,0,4);//年
        $month = substr($rf_model->record_time,5,2);//月
        $day = substr($rf_model->record_time,8,2);//日
        $hours = substr($rf_model->record_time,11,2);//小时
        $minute = substr($rf_model->record_time,14,2);//分钟
        $time = $day.$month.$year.$hours.$minute;
        //报告路径存入数据库

        //$filepath = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/ptw'.'/PTW' . $id . '.pdf';
        $filepath = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/'.$program_id.'/rf/'.$contractor_id.'/RF' . $id . $time .'.pdf';
        RfList::updatepath($id,$filepath);

        //$full_dir = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/ptw';
        $full_dir = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/'.$program_id.'/rf/'.$contractor_id;
        if(!file_exists($full_dir))
        {
            umask(0000);
            @mkdir($full_dir, 0777, true);
        }
        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
        require_once($tcpdfPath);
        //Yii::import('application.extensions.tcpdf.TCPDF');
        //$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf = new RfPdf('P', 'mm', 'A4', true, 'UTF-8', false);
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($id);
        $pdf->SetSubject($id);
        //$pdf->SetKeywords('PDF, LICEN');
        $pro_model = Program::model()->findByPk($program_id);
        $pro_name = $pro_model->program_name;
        $pro_params = $pro_model->params;//项目参数
        $main_model = Contractor::model()->findByPk($contractor_id);
        $main_conid_name = $main_model->contractor_name;
        $logo_pic = '/opt/www-nginx/web'.$main_model->remark;

        $_SESSION['title'] = 'RF No.:  ' . $id;

        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        // 设置页眉和页脚字体
        $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //英文

        $pdf->Header($logo_pic);
        $pdf->setFooterFont(Array('helvetica', '', '10'));
        $pdf->setCellPaddings(1,1,1,1);

        //设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');

        //设置间距
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        //设置分页
        $pdf->SetAutoPageBreak(TRUE, 25);
        //set image scale factor
        $pdf->setImageScale(1.25);
        //set default font subsetting mode
        $pdf->setFontSubsetting(true);
        //设置字体
        $pdf->SetFont('droidsansfallback', '', 10, '', true); //英文

        $pdf->AddPage();
        $pdf->SetLineWidth(0.1);
        $step = RfDetail::stepByType($id,'1');
        $to_user = $rf_model->to_user;
        $add_user_id = $rf_model->add_user;
        $add_user = Staff::model()->findByPk($add_user_id);
        $add_user_name = $add_user->user_name;
        $con_model = Contractor::model()->findByPk($contractor_id);
        $con_name = $con_model->contractor_name;
        $con_adr = $con_model->company_adr;
        $link_tel = $con_model->link_tel;
//        $con_logo = $con_model->remark;
        $con_logo = 'img/RF.jpg';
        $to_user_model = Staff::model()->findByPk($to_user);
        $to_user_name = $to_user_model->user_name;
        $record_time = $rf_model->record_time;
        $date = Utils::DateToEn(substr($record_time,0,10));
        $unchecked_img = 'img/checkbox_unchecked.png';
        $checked_img = 'img/checkbox_checked.png';
        $checked_img_html= '<img src="'.$checked_img.'" height="10" width="10" /> ';
        $unchecked_img_html= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
        $step = RfDetail::stepByType($id,'1');
        $detail_list = RfDetail::dealListByStep($id,$step);
        $deal_model_1 = Staff::model()->findByPk($detail_list[0]['user_id']);
        $deal_signature_1 = $deal_model_1->signature_path;
        $signature_html_1= '<img src="'.$deal_signature_1.'" height="30" width="30" />';
        $cc_user = RfUser::userList($id,$step,'2');
        $subject = $rf_model->subject;
        $valid_time = $rf_model->valid_time;
        $valid_time = Utils::DateToEn(substr($valid_time,0,10));
        $spc_ref = $rf_model->spec_ref;
        $related_to =$rf_model->related_to;
        $location_ref = $rf_model->location_ref;
        $others = $rf_model->others;
        $type = $rf_model->type;
        $status = $rf_model->status;
        $end_step = $rf_model->step;
        $end_cc_user = RfUser::userList($id,$end_step,'2');
        $end_list = RfDetail::dealListByStep($id,$end_step);
        $deal_model_2 = Staff::model()->findByPk($end_list[0]['user_id']);
        $deal_signature_2 = $deal_model_2->signature_path;
        $signature_html_2= '<img src="'.$deal_signature_2.'" height="30" width="30" />';
        $end_date = $end_list[0]['record_time'];
        $end_date = Utils::DateToEn(substr($end_date,0,10));
        $confirm_step = RfDetail::stepByType($id,'9');
        $confirm_list = RfDetail::dealListByStep($id,$confirm_step);
        $confirm_date = $confirm_list[0]['record_time'];
        $confirm_date = Utils::DateToEn(substr($confirm_date,0,10));
        $info_html = "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
        $info_html.="<tr><td height=\"30px\" colspan=\"4\"><h3>PROJECT: &nbsp;{$pro_name}</h3></td></tr>";
        $info_html.="<tr><td nowrap=\"nowrap\"  style=\"border-width: 1px;border-color:gray gray gray gray\">To:</td><td  nowrap=\"nowrap\"  style=\"border-width: 1px;border-color:gray gray gray gray\">{$con_model->contractor_name}</td><td  nowrap=\"nowrap\"  style=\"border-width: 1px;border-color:gray gray gray gray\">From :</td><td nowrap=\"nowrap\"  style=\"border-width: 1px;border-color:gray gray gray gray\">{$add_user_name}</td></tr>";
        $info_html.="<tr><td nowrap=\"nowrap\"  style=\"border-width: 1px;border-color:gray gray gray gray\">Attn:</td><td  nowrap=\"nowrap\"  style=\"border-width: 1px;border-color:gray gray gray gray\">{$to_user_name}</td><td  nowrap=\"nowrap\"  style=\"border-width: 1px;border-color:gray gray gray gray\">Date :</td><td nowrap=\"nowrap\"  style=\"border-width: 1px;border-color:gray gray gray gray\">{$date}</td></tr>";
        $info_html.="<tr><td colspan=\"4\">Copy to: </td></tr>";
        $cc_cnt_1 = 0;
        $cc_tag['3'] = 'YES';
        $cc_tag['4'] = 'NO';
        $cc_tag['0'] = 'Y/N';
        foreach($cc_user as $i => $j){
            $cc_cnt_1++;
            $cc_select = $cc_tag[$j['tag']];
            if($cc_cnt_1 % 2 == 0){
                $info_html.="<td width='20%'>{$unchecked_img_html}{$j['user_name']}</td> <td width='30%' align='right'>- enclosure $cc_select</td></tr>";
            }else{
                $info_html.="<tr><td width='20%'>{$unchecked_img_html}{$j['user_name']}</td> <td width='30%' align='right'>- enclosure $cc_select</td>";
            }
        }
        if($cc_cnt_1 % 2 == 1){
            $info_html.="<td width='20%'></td><td width='30%' align='right'></td></tr>";
        }
        $info_html.="<tr><td height=\"30px\" colspan=\"2\" width='70%' style=\"border-width: 1px;border-color:gray gray gray gray\"><h3>Subject: &nbsp;{$subject}</h3></td><td colspan=\"2\" height=\"30px\"  width='30%' style=\"border-width: 1px;border-color:gray gray gray gray\"><h3>Latest Date to Reply : &nbsp;<br>{$valid_time}</h3></td></tr>";
        $info_html.="<tr><td height=\"30px\" colspan=\"4\">Description: </td></tr>";
        $info_html.="<tr><td  colspan=\"2\" width='70%' style=\"border-width: 1px;border-color:gray gray gray gray\">Particulars of Information (Related to): </td><td  colspan=\"2\" width='30%' style=\"border-width: 1px;border-color:gray gray gray gray\">{$related_to}</td></tr>";
        $info_html.="<tr><td colspan=\"2\" width='70%' style=\"border-width: 1px;border-color:gray gray gray gray\">Location, Drawing Ref No: </td><td colspan=\"2\" width='30%' style=\"border-width: 1px;border-color:gray gray gray gray\">{$location_ref}</td></tr>";
        $info_html.="<tr><td colspan=\"2\" width='70%' style=\"border-width: 1px;border-color:gray gray gray gray\">Specification Ref (Clause): </td><td colspan=\"2\" width='30%' style=\"border-width: 1px;border-color:gray gray gray gray\">{$spc_ref}</td></tr>";
        $info_html.="<tr><td colspan=\"2\" width='70%' style=\"border-width: 1px;border-color:gray gray gray gray\">Others (Email): </td><td colspan=\"2\" width='30%' style=\"border-width: 1px;border-color:gray gray gray gray\">{$others}</td></tr>";
        if($type == '1'){
            $info_html.="<tr><td height=\"30px\" colspan=\"4\"><h3>Reason (S) for RFI: </h3></td></tr>";
        }
        $info_html.="<tr><td height=\"60px\" colspan=\"4\">{$detail_list[0]['remark']}</td></tr>";
        $info_html.="<tr><td colspan=\"2\" width='60%'  ></td><td colspan=\"2\" width='40%' align=\"center\">{$signature_html_1}</td></tr>";
        $info_html.="<tr><td colspan=\"2\" width='60%'  style=\"border-width: 1px;border-color:white white gray gray\"></td><td colspan=\"2\" width='40%' style=\"border-width: 1px;border-color:gray gray gray white\">Coordinator Name / PM Name <br> Name & Signature of Contractor’s Representative</td></tr>";
        $info_html.="<tr><td height=\"30px\" colspan=\"4\" style=\"border-width: 1px;border-color:gray gray white gray\">Consultant’s Reply : (Enclosure Y / N) </td></tr>";
        if($end_list[0]['deal_type'] == '4'){
            $info_html.="<tr><td  width='33%' >{$checked_img_html} Approved</td><td  width='33%' >{$unchecked_img_html} Approved with Comments</td><td  width='34%' >{$unchecked_img_html} Amend / Reject & Resubmit</td></tr>";
        }else if($end_list[0]['deal_type'] == '5'){
            $info_html.="<tr><td  width='33%' >{$unchecked_img_html} Approved</td><td  width='33%' >{$checked_img_html} Approved with Comments</td><td  width='34%' >{$unchecked_img_html} Amend / Reject & Resubmit</td></tr>";
        }else if($end_list[0]['deal_type'] == '6'){
            $info_html.="<tr><td  width='33%' >{$unchecked_img_html} Approved</td><td  width='33%' >{$unchecked_img_html} Approved with Comments</td><td  width='34%' >{$checked_img_html} Amend / Reject & Resubmit</td></tr>";
        }
        $info_html.="<tr><td height=\"60px\" colspan=\"4\">{$end_list[0]['remark']}</td></tr>";
        $info_html.="<tr><td colspan=\"2\" width='60%'  ></td><td colspan=\"2\" width='40%' align=\"center\">{$signature_html_2}</td></tr>";
        $info_html.="<tr><td colspan=\"2\" width='60%'  style=\"border-width: 1px;border-color:white white gray gray\"></td><td colspan=\"2\" width='40%' style=\"border-width: 1px;border-color:gray gray gray white\">Coordinator Name / PM Name <br> Name & Signature of Contractor’s Representative</td></tr>";
        $info_html.="<tr><td colspan=\"2\" width='70%' style=\"border-width: 1px;border-color:gray gray gray gray\">Consultant Rep’s Signature & Date Received <br> {$confirm_date} </td><td colspan=\"2\" width='30%' style=\"border-width: 1px;border-color:gray gray gray gray\">Consultant Rep’s Signature & Date Replied <br> {$end_date}</td></tr>";
        $cc_cnt_2 = 0;
        foreach($end_cc_user as $i => $j){
            $cc_cnt_2++;
            $end_cc_select = $cc_tag[$j['tag']];
            if($cc_cnt_2 % 2 == 0){
                $info_html.="<td width='20%'>{$unchecked_img_html}{$j['user_name']}</td> <td width='30%' align='right'>- enclosure $end_cc_select</td></tr>";
            }else{
                $info_html.="<tr><td width='20%'>{$unchecked_img_html}{$j['user_name']}</td> <td width='30%' align='right'>- enclosure $end_cc_select</td>";
            }
        }
        if($cc_cnt_2 % 2 == 1){
            $info_html.="<td width='20%'></td><td width='30%' align='right'></td></tr>";
        }
        $info_html.="</table>";

        if($type == '1'){
            $title = '<h3>Request For</h3> <br> <h3>Information</h3> <br> (XXX)';
        }else{
            $title = '<h3>Shopdrawing</h3>  <h3>For Approval</h3> <br> (XXX)';
        }
        $ref_no = 'Reff No: '.$rf_model->check_no;
        $con_info = '<h3>'.'  '.$con_name . '<br>' .'  '. $con_adr. '<br>' .'  '.$link_tel.'</h3>';
        $logo_img= '<img src="'.$con_logo.'" height="70" width="100"  />';
        $header = "<table><tr ><td width='30%' style=\"border-width: 1px;border-color:gray gray gray gray;height:50px\" align=\"center\">$title</td><td rowspan='2' align=\"left\" width='45%'>$con_info</td><td rowspan='2' align=\"cnter\">$logo_img</td></tr><tr><td style=\"border-width: 1px;border-color:gray gray gray gray;height:20px\">$ref_no</td></tr></table>";
//        $pdf->writeHTML($header, true, true, true, false, '');
        $pdf->writeHTML($header.$info_html, true, true, true, false, '');
        $pdf->Output($filepath, 'I');  //保存到指定目录
        return $filepath;
    }
}
