<?php

/**
 * 分包指定项目到人
 * @author LiuMinchao
 */
class ProgramUser extends CActiveRecord {
  
    const STATUS_NORMAL = '00'; //正常
    const STATUS_STOP = '01'; //停用
    const ENTRANCE_APPLY = '-1'; //进入申请名单
    const ENTRANCE_PENDING = '10'; //入场待审批
    const ENTRANCE_SUCCESS = '11'; //入场审批成功
    const ENTRANCE_FAIL= '12'; //入场审批失败
    const LEAVE_PENDING = '20'; //出场待审批
    const LEAVE_SUCCESS = '21'; //出场审批成功
    const LEAVE_FAIL= '22'; //出场审批失败

    public $subcomp_name; //指派分包公司名

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_program_user';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('proj_project_user', 'id'),
            'program_id' => Yii::t('proj_project_user', 'program_id'),
            'program_name' => Yii::t('proj_project_user', 'program_name'),
            'contractor_id' => Yii::t('proj_project_user', 'contractor_id'),
            'add_operator' => Yii::t('proj_project_user', 'add_operator'),
            'status' => Yii::t('proj_project_user', 'status'),
            'record_time' => Yii::t('proj_project_user', 'record_time'),
            'subcomp_name' => Yii::t('proj_project_user', 'subcomp_name'),
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Program the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::ENTRANCE_APPLY => Yii::t('proj_project_user', 'entrance_apply'),
            self::ENTRANCE_PENDING => Yii::t('proj_project_user', 'entrance_pending'),
            self::ENTRANCE_SUCCESS => Yii::t('proj_project_user', 'entrance_success'),
            self::ENTRANCE_FAIL => Yii::t('proj_project_user', 'entrance_fail'),
            self::LEAVE_PENDING => Yii::t('proj_project_user', 'leave_pending'),
        );
        return $key === null ? $rs : $rs[$key];
    }
    //状态
    public static function statusSubText($key = null) {
        $rs = array(
            self::ENTRANCE_PENDING => Yii::t('proj_project_user', 'entrance_pending'),
            self::ENTRANCE_SUCCESS => Yii::t('proj_project_user', 'entrance_success'),
            self::ENTRANCE_FAIL => Yii::t('proj_project_user', 'entrance_fail'),
            self::LEAVE_PENDING => Yii::t('proj_project_user', 'leave_pending'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::ENTRANCE_APPLY => 'label-warning',
            self::ENTRANCE_PENDING => 'label-default',
            self::ENTRANCE_SUCCESS => 'label-success',
            self::ENTRANCE_FAIL => 'label-danger',
            self::LEAVE_PENDING => 'label-danger',
        );
        return $key === null ? $rs : $rs[$key];
    }
    //查询某项目下人员(审核状态是-1，10，11，20)
    public static function queryByUser($args = array()){
        $entrance_apply = self::ENTRANCE_APPLY;
        $entrance_pending = self::ENTRANCE_PENDING;
        $entrance_success = self::ENTRANCE_SUCCESS;
        $leave_pending = self::LEAVE_PENDING;
        $pro_model = Program::model()->findByPk($args['program_id']);
        $root_proid = $pro_model->root_proid;
        $exist_data = ProgramUser::model()->findAll(
            array(
                'select'=>array('*'),
                'condition' => 'root_proid=:root_proid and check_status in (:entrance_apply,:entrance_pending,:entrance_success,:leave_pending) and contractor_id=:contractor_id',
                'params' => array(':root_proid'=>$root_proid,':contractor_id'=>$args['contractor_id'],':entrance_apply'=>$entrance_apply,'entrance_pending'=>$entrance_pending,
                    ':entrance_success'=>$entrance_success,':leave_pending'=>$leave_pending
                ),
            )
        );

        return $exist_data;
    }
    //分页查询某项目下人员(审核状态是-1，10，11，20)
    public static function queryListByUser($page, $pageSize, $args = array()) {
        $condition = '1=1';
        $params = array();
        $entrance_apply = self::ENTRANCE_APPLY;
        $entrance_pending = self::ENTRANCE_PENDING;
        $entrance_success = self::ENTRANCE_SUCCESS;
        $leave_pending = self::LEAVE_PENDING;

        //Program Id
        if ($args['program_id'] != '') {
            $pro_model = Program::model()->findByPk($args['program_id']);
            $root_proid = $pro_model->root_proid;
            $condition.= ( $condition == '') ? ' t.root_proid =:root_proid' : ' AND t.root_proid =:root_proid';
            $params['root_proid'] = $root_proid;
        }
        //Contractor
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' t.contractor_id =:contractor_id' : ' AND t.contractor_id =:contractor_id';
            $params['contractor_id'] = $args['contractor_id'];
        }
        //Check Status
        if($args['status'] != ''){
            $condition.= ( $condition == '') ? ' t.check_status=:check_status' : ' AND t.check_status=:check_status';
            $params['check_status'] = $args['status'];
        }else{
            $condition.= ( $condition == '') ? ' t.check_status in (:entrance_apply,:entrance_pending,:entrance_success,:leave_pending)' : ' AND t.check_status in (:entrance_apply,:entrance_pending,:entrance_success,:leave_pending)';
            $params['entrance_apply'] = $entrance_apply;
            $params['entrance_pending'] = $entrance_pending;
            $params['entrance_success'] = $entrance_success;
            $params['leave_pending'] = $leave_pending;
        }

        //Epss Status
        if($args['epss_status'] == '1'){
            $condition.= ( $condition == '') ? " (t.build_role_id != '' or t.rail_role_id != '' or t.road_role_id != '') " : " AND (t.build_role_id != '' or t.rail_role_id != '' or t.road_role_id != '')";
        }
        if($args['epss_status'] == '2'){
            $condition.= ( $condition == '') ? " t.build_role_id = '' and t.rail_role_id = '' and t.road_role_id = '' " : " AND t.build_role_id = '' and t.rail_role_id = '' and t.road_role_id = '' ";
        }

        if($args['user_name'] != ''){
            $sql = "select user_id from bac_staff where user_name like '%".$args['user_name']."%' ";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
            $i = '';
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $i.=$row['user_id'].',';
                }
            }
            if ($i != '')
                $user_id = substr($i, 0, strlen($i) - 1);
            $condition.= ( $condition == '') ? ' t.user_id IN ('.$user_id.') ' : ' AND t.user_id IN ('.$user_id.')';

        }
        $total_num = ProgramUser::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {
            //$order = 'field(check_status,-1,10,20,11)';
            $order = 'b.user_name ASC';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' ASC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }
        $criteria->order = $order;
        $criteria->join = 'LEFT JOIN bac_staff b ON b.user_id=t.user_id';
        $criteria->condition = $condition;
        $criteria->params = $params;
        $pages = new CPagination($total_num);
        $pages->pageSize = $pageSize;
        $pages->setCurrentPage($page);
        $pages->applyLimit($criteria);
        $rows = ProgramUser::model()->findAll($criteria);
        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }
    //分页查询某项目下人员(审核状态是10，11，20)
    public static function querySubListByUser($page, $pageSize, $args = array()) {
        $condition = '1=1';
        $params = array();
        $entrance_pending = self::ENTRANCE_PENDING;
        $entrance_success = self::ENTRANCE_SUCCESS;
        $leave_pending = self::LEAVE_PENDING;

        $program_model = Program::model()->findByPk($args['program_id']);
        $contractor_id = $program_model->contractor_id;
        //Program Id
        if ($args['program_id'] != '') {
            $pro_model = Program::model()->findByPk($args['program_id']);
            $root_proid = $pro_model->root_proid;
            $condition.= ( $condition == '') ? ' t.root_proid =:root_proid' : ' AND t.root_proid =:root_proid';
            $params['root_proid'] = $root_proid;
        }
        //Contractor
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' t.contractor_id =:contractor_id' : ' AND t.contractor_id =:contractor_id';
            $params['contractor_id'] = $args['contractor_id'];
        }
        //Check Status
        if($args['status'] != ''){
            $condition.= ( $condition == '') ? ' t.check_status=:check_status' : ' AND t.check_status=:check_status';
            $params['check_status'] = $args['status'];
        }else{
            $condition.= ( $condition == '') ? ' t.check_status in (:entrance_pending,:entrance_success,:leave_pending)' : ' AND t.check_status in (:entrance_pending,:entrance_success,:leave_pending)';
            $params['entrance_pending'] = $entrance_pending;
            $params['entrance_success'] = $entrance_success;
            $params['leave_pending'] = $leave_pending;
        }

        if($args['user_name'] != ''){
            $sql = "select user_id from bac_staff where user_name like '%".$args['user_name']."%' ";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
            $i = '';
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $i.=$row['user_id'].',';
                }
            }
            if ($i != '')
                $user_id = substr($i, 0, strlen($i) - 1);
            $condition.= ( $condition == '') ? ' t.user_id IN ('.$user_id.') ' : ' AND t.user_id IN ('.$user_id.')';

        }

        $total_num = ProgramUser::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {
            //$order = 'field(check_status,-1,10,20,11)';
            $order = 'b.user_name ASC';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' ASC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }
        $criteria->order = $order;
        $criteria->join = 'LEFT JOIN bac_staff b ON b.user_id=t.user_id';
        $criteria->condition = $condition;
        $criteria->params = $params;
        $pages = new CPagination($total_num);
        $pages->pageSize = $pageSize;
        $pages->setCurrentPage($page);
        $pages->applyLimit($criteria);
        $rows = ProgramUser::model()->findAll($criteria);
        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }
    //修改日志
    public static function updateLog($model) {
        $statusList = self::statusText();
        return array(
            $model->getAttributeLabel('program_id') => Yii::app()->db->lastInsertID,
            $model->getAttributeLabel('program_name') => $model->program_name,
            $model->getAttributeLabel('contractor_id') => $model->contractor_id,
            $model->getAttributeLabel('add_operator') => $model->add_operator,
            $model->getAttributeLabel('status') => $statusList[$model->status],
            $model->getAttributeLabel('record_time') => $model->record_time,
           // Yii::t('proj_project_user', 'Assign SC') => self::subcompText($model->program_id),
        );
    }

    
    //提交人员申请
    public static function SubmitApplicationsUser($args) {
        if ($args['program_id'] == '') {
            $r['msg'] = Yii::t('proj_project_user', 'error_projid_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        
        $model = Program::model()->findByPk($args['program_id']);
        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        
        /*if (empty($args['sc_list'])) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }*/
        try { 
            $program_id = $args['program_id'];
            $apply_user_id = Yii::app()->user->getState('contractor_id');
            //勾选人员数组
            $new_list = (array)$args['sc_list'];
            $entrance_apply = self::ENTRANCE_APPLY;
            $entrance_pending = self::ENTRANCE_PENDING;
            $entrance_success = self::ENTRANCE_SUCCESS;
            $leave_pending = self::LEAVE_PENDING;
            $leave_success = self::LEAVE_SUCCESS;
            $status_stop = self::STATUS_NORMAL;
            $power = 0 ;//角色权限
            $user_phone = '';
            $root_proid = $model->root_proid;
            $contractor_id = $args['contractor_id'];
            //将新增人员置为入场待审批
            if(!empty($new_list)){
                foreach ($new_list as $key => $id) {
                    $staff_model = Staff::model()->findByPk($id);
                    $role_id = $staff_model->role_id;
                    $program_role = $role_id.'|null|null';

                    $programuser_model = ProgramUser::model()->find('root_proid=:root_proid AND contractor_id=:contractor_id AND user_id=:user_id', array(':root_proid' => $root_proid,':user_id'=>$id,':contractor_id'=>$contractor_id));
                    $check_status = $programuser_model->check_status;
                    switch ($check_status)
                    {
                        case "21":
                            $sql = "UPDATE bac_program_user SET user_phone = '',check_status = '".$entrance_apply."',status = '".$status_stop."',apply_date = '".date('Y-m-d H:i:s', time())."',ra_role='".$power."',ptw_role='".$power."',wsh_mbr_flag='".$power."',meeting_flag='".$power."',training_flag='".$power."',program_role='".$program_role."' WHERE user_id='".$id."'and root_proid = '".$root_proid."' and contractor_id = '".$contractor_id."' ";
                            $command = Yii::app()->db->createCommand($sql);
                            $rs = $command->execute();
                            break;
                        case "12":
                            $sql = "UPDATE bac_program_user SET user_phone = '',check_status = '".$entrance_apply."',status = '".$status_stop."',apply_date = '".date('Y-m-d H:i:s', time())."',ra_role='".$power."',ptw_role='".$power."',wsh_mbr_flag='".$power."',meeting_flag='".$power."',training_flag='".$power."',program_role='".$program_role."' WHERE user_id='".$id."'and root_proid = '".$root_proid."' and contractor_id = '".$contractor_id."' ";
                            $command = Yii::app()->db->createCommand($sql);
                            $rs = $command->execute();
                            break;
                        case "-1":
                            break;
                        case "10":
                            break;
                        case "11":
                            break;
                        case "20":
                            break;
                        default:
                            $t=time();
                            $apply_date = date("Y-m-d H:i:s",$t);
                            $sql = 'INSERT INTO bac_program_user(program_id,contractor_id,user_id,user_phone,root_proid,program_role,check_status,status,apply_user_id,apply_date) VALUES(:program_id,:contractor_id,:user_id,:user_phone,:root_proid,:program_role,:check_status,:status,:apply_user_id,:apply_date)';
                            $command = Yii::app()->db->createCommand($sql);
                            $command->bindParam(":program_id", $program_id, PDO::PARAM_INT);
                            $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
                            $command->bindParam(":user_id", $id, PDO::PARAM_STR);
                            $command->bindParam(":user_phone", $user_phone, PDO::PARAM_STR);
                            $command->bindParam(":root_proid", $root_proid, PDO::PARAM_STR);
                            $command->bindParam(":program_role", $program_role, PDO::PARAM_STR);
                            $command->bindParam(":check_status", $entrance_apply, PDO::PARAM_STR);
                            $command->bindParam(":status", $status_stop, PDO::PARAM_STR);
                            $command->bindParam(":apply_user_id", $apply_user_id, PDO::PARAM_STR);
                            $command->bindParam(":apply_date", $apply_date, PDO::PARAM_STR);

                            $rs = $command->execute();
                    }
//                    if($check_status == '21'){
//                        $sql = "UPDATE bac_program_user SET check_status = '".$entrance_apply."',status = '".$status_stop."',ra_role='".$power."',ptw_role='".$power."',wsh_mbr_flag='".$power."',meeting_flag='".$power."',training_flag='".$power."',program_role='".$program_role."' WHERE user_id='".$id."'and program_id = '".$program_id."'";
//                        $command = Yii::app()->db->createCommand($sql);
//                        $rows = $command->execute();
//
//                    }else if($check_status != '-1'||$check_status != '10'||$check_status != '11'||$check_status != '20'){
//                        $user_data = ProgramUser::model()->findAll(
//                            array(
//                                'select'=>array('*'),
//                                'condition' => 'program_id=:program_id  and user_id=:user_id',
//                                'params' => array(':program_id'=>$program_id,':user_id'=>$id),
//                            )
//                        );
//                        if(!$user_data){
//                            $t=time();
//                            $apply_date = date("Y-m-d H:i:s",$t);
//                            $sql = 'INSERT INTO bac_program_user(program_id,contractor_id,user_id,root_proid,program_role,check_status,status,apply_user_id,apply_date) VALUES(:program_id,:contractor_id,:user_id,:root_proid,:program_role,:check_status,:status,:apply_user_id,:apply_date)';
//                            $command = Yii::app()->db->createCommand($sql);
//                            $command->bindParam(":program_id", $program_id, PDO::PARAM_INT);
//                            $command->bindParam(":contractor_id", $args['contractor_id'], PDO::PARAM_INT);
//                            $command->bindParam(":user_id", $id, PDO::PARAM_STR);
//                            $command->bindParam(":root_proid", $model->root_proid, PDO::PARAM_STR);
//                            $command->bindParam(":program_role", $program_role, PDO::PARAM_STR);
//                            $command->bindParam(":check_status", $entrance_apply, PDO::PARAM_STR);
//                            $command->bindParam(":status", $status_stop, PDO::PARAM_STR);
//                            $command->bindParam(":apply_user_id", $apply_user_id, PDO::PARAM_STR);
//                            $command->bindParam(":apply_date", $apply_date, PDO::PARAM_STR);
//
//                            $rs = $command->execute();
//                        }
//                    }
                }
            }

            OperatorLog::savelog(OperatorLog::MODULE_ID_PROJ, Yii::t('proj_project_user', 'Edit Proj'), self::updateLog($model));
            $r['msg'] = Yii::t('common', 'success_apply');
            $r['status'] = 1;
            $r['refresh'] = true;
        }
        catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
//        var_dump($r);
//        exit;
        return $r;
    }
    
    
    //启用
    public static function startProgram($args) {

        if ($args['program_id'] == '') {
            $r['msg'] = Yii::t('proj_project_user', 'error_projid_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = Program::model()->findByPk($args['program_id']);

        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {

            $model->status = self::STATUS_NORMAL;
            $result = $model->save();

            if ($result) {
                OperatorLog::savelog(OperatorLog::MODULE_ID_PROJ, Yii::t('proj_project_user', 'Start Proj'), self::updateLog($model));
                $r['msg'] = Yii::t('common', 'success_start');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_start');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }

    public static function myUserList($program_id, $contractor_id) {
        $model = Program::model()->findByPk($program_id);
        $root_proid = $model->root_proid;
        $sql = "SELECT a.user_id,b.user_name FROM bac_program_user  a,bac_staff b  WHERE  a.user_id =b.user_id and a.root_proid=:root_proid and a.contractor_id=:contractor_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":root_proid", $root_proid, PDO::PARAM_INT);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['user_id']] = $row['user_name'];
            }
        }
        return $rs;
    }

    public static function OperatorListByRf($program_id, $contractor_id) {
        $model = Program::model()->findByPk($program_id);
        $root_proid = $model->root_proid;
        $sql = "SELECT a.user_id,b.user_name FROM bac_program_user  a,bac_staff b  WHERE  a.user_id =b.user_id and a.root_proid=:root_proid and a.contractor_id=:contractor_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":root_proid", $root_proid, PDO::PARAM_INT);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['user_id']] = $row['user_name'];
            }
        }
        $sql = "SELECT operator_id FROM bac_operator  WHERE  operator_role = '00' and contractor_id=:contractor_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
        $r = $command->queryAll();
        $rs[$r[0]['operator_id']] = $r[0]['operator_id'];
        return $rs;
    }

    //查询项目已出场的人员
    public static function myUserListByLeave($program_id, $contractor_id){
        $model = Program::model()->findByPk($program_id);
        $root_proid = $model->root_proid;
        $leave_success = self::LEAVE_SUCCESS;
        $sql = "SELECT user_id FROM bac_program_user WHERE root_proid=:root_proid and contractor_id=:contractor_id and check_status=".$leave_success."";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":root_proid", $root_proid, PDO::PARAM_INT);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
        $rows = $command->queryAll();
        if(count($rows)>0){
            foreach(($rows) as $key => $row){
                $r[] = $row['user_id'];
            }
        }
        return $r;
    }
    //查询项目中状态为(-1,10,11,20)的人员
    public static function myUserListBySuccess($program_id, $contractor_id) {
        $entrance_apply = self::ENTRANCE_APPLY;
        $entrance_pending = self::ENTRANCE_PENDING;
        $entrance_success = self::ENTRANCE_SUCCESS;
        $leave_pending = self::LEAVE_PENDING;
        $model = Program::model()->findByPk($program_id);
        $root_proid = $model->root_proid;
        $sql = "SELECT a.user_id,b.user_name FROM bac_program_user  a,bac_staff b
              WHERE  a.user_id =b.user_id and b.status=0 and a.root_proid=:root_proid
              and a.contractor_id=:contractor_id and a.check_status in(:entrance_apply,:entrance_pending,:entrance_success,:leave_pending)";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":root_proid", $root_proid, PDO::PARAM_INT);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
        $command->bindParam(":entrance_apply", $entrance_apply, PDO::PARAM_INT);
        $command->bindParam(":entrance_pending",$entrance_pending,PDO::PARAM_INT);
        $command->bindParam(":entrance_success", $entrance_success, PDO::PARAM_INT);
        $command->bindParam(":leave_pending",$leave_pending,PDO::PARAM_INT);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['user_id']] = $row['user_name'];
            }
        }
        return $rs;
    }
    //查询项目中待审批(包含出场和入场)的人员
    public static function myUserListByPending($program_id, $contractor_id) {
        $entrance_status = self::ENTRANCE_PENDING;
        $leave_status = self::LEAVE_PENDING;
        $model = Program::model()->findByPk($program_id);
        $root_proid = $model->root_proid;
        $sql = "SELECT a.user_id,b.user_name FROM bac_program_user  a,bac_staff b  WHERE  a.user_id =b.user_id and a.root_proid=:root_proid and a.contractor_id=:contractor_id and a.check_status in (:entrance_status,:leave_status)";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":root_proid", $root_proid, PDO::PARAM_INT);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
        $command->bindParam(":entrance_status", $entrance_status, PDO::PARAM_INT);
        $command->bindParam(":leave_status", $leave_status, PDO::PARAM_INT);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['user_id']] = $row['user_name'];
            }
        }
        return $rs;
    }
    //查询用户是否在项目组中
    public static function UserProgram($user_id){
        if($user_id == ''){
            return '';
        }
        $cnt = ProgramUser::model()->count("user_id='".$user_id."' and check_status=11");
        return $cnt;
    }
    //查询用户在shell的哪个项目组中
    public static function UserProgramName($user_id){
        $rows = [];
        $sql = "select b.program_name from bac_program_user a ,bac_program b where a.user_id = '".$user_id."' and a.check_status in (11,20) and a.program_id = b.program_id and b.status = '00'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }

    //查询用户在哪个项目组中
    public static function UserProgramQName($user_id){
        $rows = [];
        $sql = "select b.program_name from bac_program_user_q a ,bac_program b where a.user_id = '".$user_id."' and a.check_status in (11,20) and a.program_id = b.program_id and b.status = '00'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }

    //根据PTW的施工人员得到每人的角色权限
    public static function PtwRoleList($program_id,$worker_list){
        foreach($worker_list as $id=>$r ){
            $sql = "SELECT * FROM bac_program_user WHERE program_id = :program_id and user_id = :user_id";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":program_id", $program_id, PDO::PARAM_STR);
            $command->bindParam(":user_id", $id, PDO::PARAM_STR);
            $rows = $command->queryAll();
            if(count($rows)>0){
                foreach($rows as $num => $arr){
                    $rs[$id]['ra_role'] = $arr['ra_role'];
                    $rs[$id]['ptw_role'] = $arr['ptw_role'];
                    $rs[$id]['wsh_mbr_flag'] = $arr['wsh_mbr_flag'];
                    $rs[$id]['meeting_flag'] = $arr['meeting_flag'];
                    $rs[$id]['training_flag'] = $arr['training_flag'];
                    $rs[$id]['program_role'] = $arr['program_role'];
                }
            }
        }
    }
    //查询承包商下所有项目的角色
    public static function ProgramRoleList($contractor_id,$role=array()) {
        $sql = "SELECT ra_role,ptw_role,wsh_mbr_flag,meeting_flag,training_flag,program_role FROM bac_program_user WHERE contractor_id=:contractor_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
        $rows = $command->queryAll();
        $i = 0;
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                foreach ($row as $num => $r){
                    if($r!=""){
                        $i++;
                        $new = $num.'_'.$r;
                        $v[$new] = $role[$num][$r];
                        $roleList[][$num] = $r;
                    }
                }
            }
        } 
        return $v;
    }
    //查询承包商下所有项目的人员
    public static function ProgramWorkerList($contractor_id, $type='K-V') {
        $sql = "SELECT a.user_id,b.user_name FROM bac_program_user a,bac_staff b WHERE a.user_id = b.user_id AND a.contractor_id = b.contractor_id AND a.contractor_id=:contractor_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
        $rows = $command->queryAll();
        if($type <> 'K-V')
            return $rows;
        
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['user_id']] = $row['user_name'];
            }
        }
        
        return $rs;
    }
    //设置权限
    public static function SetAuthority($args) {
        $model = Program::model()->findByPk($args['program_id']);
        $root_proid = $model->root_proid;
        $contractor_id = $model->contractor_id;
        $exist_data = ProgramUser::model()->findAll(
                array(
                    'select'=>array('*'),
                    'condition' => 'root_proid=:root_proid and contractor_id=:contractor_id and user_id=:user_id',
                    'params' => array(':root_proid'=>$root_proid,':contractor_id'=>$contractor_id,':user_id'=>$args['user_id']),
                )
        );
        //区分角色
        if($args['name']=='ra_role'){
            $sql = "UPDATE bac_program_user SET ra_role = '".$args['value']."' WHERE user_id = '".$args['user_id']."' and root_proid ='".$root_proid."' and contractor_id ='".$contractor_id."' ";
        }else if($args['name']=='ptw_role'){
            $sql = "UPDATE bac_program_user SET ptw_role = '".$args['value']."' WHERE user_id = '".$args['user_id']."' and root_proid ='".$root_proid."' and contractor_id ='".$contractor_id."' ";
        }else if($args['name']=='wsh_mbr_flag'){
            $sql = "UPDATE bac_program_user SET wsh_mbr_flag = '".$args['value']."' WHERE user_id = '".$args['user_id']."' and root_proid ='".$root_proid."' and contractor_id ='".$contractor_id."' ";
        }else if($args['name']=='meeting_flag'){
            $sql = "UPDATE bac_program_user SET meeting_flag = '".$args['value']."' WHERE user_id = '".$args['user_id']."'  and root_proid ='".$root_proid."' and contractor_id ='".$contractor_id."'  ";
        }else if($args['name']=='training_flag'){
            $sql = "UPDATE bac_program_user SET training_flag = '".$args['value']."' WHERE user_id = '".$args['user_id']."'  and root_proid ='".$root_proid."' and contractor_id ='".$contractor_id."'  ";
        }else if($args['name']=='first_role'){
            $program_role = $exist_data[0]['program_role'];
            $rgs = explode('|',$program_role);
            $rgs[0] = $args['value'];
            $rs = implode("|",$rgs);
//            var_dump($rs);
            $sql = "UPDATE bac_program_user SET program_role = '".$rs."' WHERE user_id = '".$args['user_id']."'  and root_proid ='".$root_proid."' and contractor_id ='".$contractor_id."'  ";
        }else if($args['name']=='second_role'){  
            $program_role = $exist_data[0]['program_role'];
            $rgs = explode('|',$program_role);
            $rgs[1] = $args['value'];
            $rs = implode("|",$rgs);
            $sql = "UPDATE bac_program_user SET program_role = '".$rs."' WHERE user_id = '".$args['user_id']."'  and root_proid ='".$root_proid."' and contractor_id ='".$contractor_id."'  ";
        }else if($args['name']=='third_role'){
            $program_role = $exist_data[0]['program_role'];
            $rgs = explode('|',$program_role);
            $rgs[2] = $args['value'];
            $rs = implode("|",$rgs);
            $sql = "UPDATE bac_program_user SET program_role = '".$rs."' WHERE user_id = '".$args['user_id']."'  and root_proid ='".$root_proid."' and contractor_id ='".$contractor_id."'  ";
        }
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->execute();
        //var_dump($rows);
        if($rows ==2 || $rows == 1){
            $r['msg'] = '设置成功';
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
            $r['msg'] = '设置失败';
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }
    public static function DeleteUser($program_id,$user_id) {

        $model = Program::model()->findByPk($program_id);
        $root_proid = $model->root_proid;
        $contractor_id = $model->contractor_id;
        $add_list = array();
        $del_list[0] = $user_id; 
        
//        $faceModel = new Face();//var_dump($model->faceset_id); //var_dump($del_list);var_dump($add_list);
//        $t = $faceModel->FacesetEditFace($model->faceset_id, $del_list, $add_list);

        $sql = "DELETE FROM bac_program_user WHERE root_proid =:root_proid and contractor_id =:contractor_id and user_id =:user_id";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":root_proid", $root_proid, PDO::PARAM_STR);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_STR);
        $command->bindParam(":user_id", $user_id, PDO::PARAM_STR);

        $rs = $command->execute();
        
        if($rs ==2 || $rs == 1){
            $r['msg'] = Yii::t('common', 'success_delete');
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
            $r['msg'] = Yii::t('common', 'error_delete');
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }
    public static function BatchLeaveUser($program_id,$user_list) {

        $model = Program::model()->findByPk($program_id);
        $root_proid = $model->root_proid;
        $contractor_id = $model->contractor_id;
        $leave_pending = self::LEAVE_PENDING;
        $status = self::STATUS_NORMAL;
        foreach($user_list as $k => $user_id){
            $sql = "UPDATE bac_program_user SET check_status=:check_status,status=:status WHERE root_proid =:root_proid and contractor_id =:contractor_id and user_id =:user_id";//var_dump($sql);;;
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":check_status", $leave_pending, PDO::PARAM_STR);
            $command->bindParam(":status", $status, PDO::PARAM_STR);
            $command->bindParam(":root_proid", $root_proid, PDO::PARAM_STR);
            $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_STR);
            $command->bindParam(":user_id", $user_id, PDO::PARAM_STR);

            $rs = $command->execute();
        }
        $model = Program::model()->findByPk($program_id);
        $add_list = array();
        $del_list[0] = $user_id;

//        $faceModel = new Face();//var_dump($model->faceset_id); //var_dump($del_list);var_dump($add_list);
//        $t = $faceModel->FacesetEditFace($model->faceset_id, $del_list, $add_list);

        //更新faceset中的脸
//        $faceModel = new Face();//var_dump($model->faceset_id); //var_dump($del_list);var_dump($add_list);
//        $r = $faceModel->FacesetEditFace($model->faceset_id, $del_list, $add_list);
        //exit;
        if($rs ==2 || $rs == 1){
            $r['msg'] = Yii::t('common', 'success_apply');
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
            $r['msg'] = Yii::t('common', 'error_apply');
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }
    public static function LeaveUser($program_id,$user_id) {

        $model = Program::model()->findByPk($program_id);
        $root_proid = $model->root_proid;
        $contractor_id = $model->contractor_id;
        $leave_pending = self::LEAVE_PENDING;
        $status = self::STATUS_NORMAL;
        $sql = "UPDATE bac_program_user SET check_status=:check_status,status=:status WHERE root_proid =:root_proid and contractor_id =:contractor_id and user_id =:user_id";//var_dump($sql);;;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_status", $leave_pending, PDO::PARAM_STR);
        $command->bindParam(":status", $status, PDO::PARAM_STR);
        $command->bindParam(":root_proid", $root_proid, PDO::PARAM_STR);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_STR);
        $command->bindParam(":user_id", $user_id, PDO::PARAM_STR);
                    
        $rs = $command->execute();
        
        $model = Program::model()->findByPk($program_id);
        $add_list = array();
        $del_list[0] = $user_id; 

//        $faceModel = new Face();//var_dump($model->faceset_id); //var_dump($del_list);var_dump($add_list);
//        $t = $faceModel->FacesetEditFace($model->faceset_id, $del_list, $add_list);

        //更新faceset中的脸
//        $faceModel = new Face();//var_dump($model->faceset_id); //var_dump($del_list);var_dump($add_list);
//        $r = $faceModel->FacesetEditFace($model->faceset_id, $del_list, $add_list);
            //exit;
        if($rs ==2 || $rs == 1){
            $r['msg'] = Yii::t('common', 'success_apply');
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
            $r['msg'] = Yii::t('common', 'error_apply');
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }

    public static function SubmitApplicationsEpss($args)
    {
        $model = Program::model()->findByPk($args['program_id']);
        $root_proid = $model->root_proid;
        $contractor_id = $model->contractor_id;
        $sql = "UPDATE bac_program_user SET build_role_id=:build_role_id,build_team_id=:build_team_id,rail_role_id=:rail_role_id,rail_team_id=:rail_team_id,road_role_id=:road_role_id,road_team_id=:road_team_id WHERE root_proid =:root_proid and contractor_id =:contractor_id  and user_id =:user_id";//var_dump($sql);;;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":build_role_id", $args['build_role_id'], PDO::PARAM_STR);
        $command->bindParam(":build_team_id", $args['build_team_id'], PDO::PARAM_STR);
        $command->bindParam(":rail_role_id", $args['rail_role_id'], PDO::PARAM_STR);
        $command->bindParam(":rail_team_id", $args['rail_team_id'], PDO::PARAM_STR);
        $command->bindParam(":road_role_id", $args['road_role_id'], PDO::PARAM_STR);
        $command->bindParam(":road_team_id", $args['road_team_id'], PDO::PARAM_STR);
        $command->bindParam(":root_proid", $root_proid, PDO::PARAM_STR);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_STR);
        $command->bindParam(":user_id", $args['user_id'], PDO::PARAM_STR);

        $rs = $command->execute();

        $r['msg'] = Yii::t('common', 'success_update');
        $r['status'] = 1;
        $r['refresh'] = true;

        return $r;
    }

    public static function EntranceUser($program_id,$row)
    {
        // $exist_data = ProgramApp::model()->count('program_id=:program_id and app_id=:app_id and status=:status', array('program_id' => $program_id, 'app_id' => 'SAF','status' => '0'));
        // if ($exist_data != 0) {
        //     $entrance_pending = self::ENTRANCE_PENDING;
        // }else{
        //     $entrance_pending = self::ENTRANCE_SUCCESS;
        // }
        $entrance_pending = self::ENTRANCE_PENDING;
        $status = self::STATUS_NORMAL;
        $model = Program::model()->findByPk($program_id);
        $root_proid = $model->root_proid;
        foreach ($row as $n => $id) {
            $sql = "UPDATE bac_program_user SET check_status=:check_status,status=:status WHERE root_proid =:root_proid and user_id =:user_id";//var_dump($sql);;;
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":check_status", $entrance_pending, PDO::PARAM_STR);
            $command->bindParam(":status", $status, PDO::PARAM_STR);
            $command->bindParam(":root_proid", $root_proid, PDO::PARAM_STR);
            $command->bindParam(":user_id", $id, PDO::PARAM_STR);

            $rs = $command->execute();
        }
            // $staff_model = Staff::model()->findByPk($id);
            // $staffinfo_model = StaffInfo::model()->findByPk($id);
            // $face_id = $staff_model->face_id;
            // $face_img = $staffinfo_model->face_img;
            // if (empty($face_id)) {
            //     if ($face_img) {
            //         //换取face_id
            //         $face_rs = Face::face_id($face_img);
            //         if ($face_rs['errno'] == 0) {
            //             //将faceid保存到数据库
            //             $staff_model->face_id = $face_rs['face_id'];
            //             $result = $staff_model->save();
            //         }
            //     }
            // }
            // $r['msg'] = Yii::t('common', 'success_update');
            // $r['status'] = 1;
            // $r['program_id'] = $program_id;
            // $r['start_cnt'] = $start_cnt;
            // $r['cnt'] = $cnt;
            // $r['refresh'] = true;
            if ($rs == 2 || $rs == 1) {
                $r['msg'] = Yii::t('common', 'success_apply');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_apply');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
            return $r;
    }
    //更新program_role字段的信息
    public static function UpateProgramRole($program_id){
        $model = Program::model()->findByPk($program_id);
        $root_proid = $model->root_proid;
        $contractor_id = $model->contractor_id;
        $exist_data = ProgramUser::model()->findAll(
            array(
                'select'=>array('*'),
                'condition' => 'program_id=:program_id',
                'params' => array(':root_proid'=>$root_proid,':contractor_id'=>$contractor_id),
            )
        );
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $user_role = Staff::userRoleList($contractor_id);
//        var_dump($user_role);
//        exit;
        foreach($exist_data as $n => $rows){
//            var_dump($user_role[$rows['user_id']]['role_id']);
            $program_role = $rows['program_role'];
            $rgs = explode('|',$program_role);
            $rgs[0] = $user_role[$rows['user_id']]['role_id'];
            $rs = implode("|",$rgs);
//            var_dump($rs);
            $sql = "UPDATE bac_program_user SET program_role = '".$rs."' WHERE user_id = '".$rows['user_id']."' and root_proid ='".$root_proid."' and contractor_id ='".$contractor_id."' ";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->execute();
        }
//        var_dump($exist_data);
//        exit;
    }
    //根据总包项目查询总包项目以及其下分包的入场人员的face_id
    public static function ProgramFaceid($program_id){
        $sql = "SELECT b.user_id,b.face_id FROM bac_program a,bac_staff b,bac_program_user c,bac_staff_info d WHERE a.root_proid='".$program_id."' and a.program_id = c.program_id and b.status=0 and b.user_id = c.user_id and c.check_status = 11 and d.face_img != '' and b.user_id = d.user_id";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        foreach($rows as $cnt => $face){
            $arr[$face['user_id']] = $face['face_id'];
        }
        return $arr;
    }
    //更新总包项目以及其下分包项目的入场人员的face_id
    public static function UpdateFaceid($program_id){
        $sql = "SELECT b.user_id FROM bac_program a,bac_staff b,bac_program_user c WHERE a.root_proid='".$program_id."' and a.program_id = c.program_id and b.user_id = c.user_id and c.check_status = 11";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        foreach($rows as $cnt => $r){
            $model = Staff::model()->findByPk($r['user_id']);
            $old_face_id = $model->face_id;
            $staffinfo_model = StaffInfo::model()->findByPk($r['user_id']);
            $face_img = '/opt/www-nginx/web/'.$staffinfo_model->face_img;
            $face_rs=Face::face_id($face_img);
            if($face_rs['errno'] == -1){
                $r['msg'] = Yii::t('comp_staff', 'Error no face');
                $r['status'] = -1;
                $r['refresh'] = false;
                return $r;
            }
            $model->face_id=$face_rs['face_id'];
            $new_face_id = $face_rs['face_id'];
            if($new_face_id) {
                Face::EditUserFace($model->user_id, $old_face_id, $new_face_id);
            }
            $result = $model->save();
        }
    }
    //查询某项目下入场员工的权限
    public static function PersonelAuthority($user_id,$program_id){
        $model = Program::model()->findByPk($program_id);
        $root_proid = $model->root_proid;
        $sql = "SELECT * FROM bac_program_user WHERE user_id = :user_id AND root_proid = :root_proid";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $command->bindParam(":root_proid", $root_proid, PDO::PARAM_INT);
        $rows = $command->queryAll();
        return $rows; 
    }
    //查询项目中状态为已入场的人员
    public static function UserListByEntrySuccess($program_id, $contractor_id) {
        $model = Program::model()->findByPk($program_id);
        $root_proid = $model->root_proid;
        $entrance_apply = self::ENTRANCE_APPLY;
        $entrance_pending = self::ENTRANCE_PENDING;
        $entrance_success = self::ENTRANCE_SUCCESS;
        $leave_pending = self::LEAVE_PENDING;
        $sql = "SELECT a.user_id,a.program_role,b.face_img FROM bac_program_user  a,bac_staff_info b  WHERE  a.user_id =b.user_id and a.root_proid=:root_proid and a.contractor_id=:contractor_id and a.check_status =:entrance_success";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":root_proid", $root_proid, PDO::PARAM_INT);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
        $command->bindParam(":entrance_success", $entrance_success, PDO::PARAM_INT);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['user_id']]['face_img'] = $row['face_img'];
                $rs[$row['user_id']]['program_role'] = $row['program_role'];
            }
        }
        return $rs;
    }

    //根据总包项目查询各企业下人员
    public static function UserListByMcProgram($contractor_id, $program_id) {
        $model = Program::model()->findByPk($program_id);
        $root_proid = $model->root_proid;
        $sql = "SELECT a.user_id,a.program_role,b.user_name FROM bac_program_user  a,bac_staff b  WHERE  a.user_id =b.user_id and a.root_proid=:root_proid and a.contractor_id=:contractor_id and a.check_status in ('11','20')";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":root_proid", $root_proid, PDO::PARAM_INT);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['user_id']] = $row['user_name'];
            }
        }
        return $rs;
    }

    //查询项目中状态为已入场的RA权限人员
    public static function UserListByRa($program_id, $contractor_id) {
        $model = Program::model()->findByPk($program_id);
        $root_proid = $model->root_proid;
        $entrance_success = self::ENTRANCE_SUCCESS;
        $sql = "SELECT a.user_id,a.ra_role,b.user_name,b.role_id FROM bac_program_user  a,bac_staff b  WHERE  a.user_id =b.user_id and a.root_proid=:root_proid and a.contractor_id=:contractor_id and a.check_status =:entrance_success and a.ra_role !=0 ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":root_proid", $root_proid, PDO::PARAM_INT);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
        $command->bindParam(":entrance_success", $entrance_success, PDO::PARAM_INT);
        $rows = $command->queryAll();
        $a=0;
        $b=0;
        $c=0;
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                if($row['ra_role'] == '1') {
                    $ra[$a]['user_id'] = $row['user_id'];
                    $ra[$a]['user_name'] = $row['user_name'];
                    $ra[$a]['role_id'] = $row['role_id'];
                    $rs[$row['ra_role']] = $ra;
                    $a++;
                }else if($row['ra_role'] == '2'){
                    $rb[$b]['user_id'] = $row['user_id'];
                    $rb[$b]['user_name'] = $row['user_name'];
                    $rb[$b]['role_id'] = $row['role_id'];
                    $rs[$row['ra_role']] = $rb;
                    $b++;
                }else if($row['ra_role'] == '3'){
                    $rc[$c]['user_id'] = $row['user_id'];
                    $rc[$c]['user_name'] = $row['user_name'];
                    $rc[$c]['role_id'] = $row['role_id'];
                    $rs[$row['ra_role']] = $rc;
                    $c++;
                }
            }
        }else{
            $rs = array();
        }
        return $rs;
    }
    //查询公司下所有项目的成员个数
    public static function AllCntList(){
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $sql = "select count(a.user_id) as cnt,a.program_id,a.root_proid,b.program_name from bac_program_user a,bac_program b where a.contractor_id = :contractor_id and a.root_proid = b.root_proid and a.contractor_id = b.contractor_id and a.check_status in ('11','20') GROUP BY program_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
        $rows = $command->queryAll();
        if(!empty($rows)){
            foreach($rows as $num => $list){
                $r[$list['root_proid']]['cnt'] = $list['cnt'];
                $r[$list['root_proid']]['program_name'] = $list['program_name'];
            }
        }
        return $r;
    }
    //系统下全部权限(英文/中文)
    public static  function AllRoleList($contractor_id = null){
        if (Yii::app()->language == 'zh_CN') {

            $role = array("ra_role" => array("0" => "否",
                "1" => "批准者",
                "2" => "领导",
                "3" => "成员"
            ),
                "ptw_role" => array("0" => "否",
                    "1" => "申请者",
                    "4" => "审批者2",
                    "2" => "审批者",
                    "3" => "批准者",
                    "5" => "检查者",
                ),
                "wsh_mbr_flag" => array("0" => "否",
                    "1" => "是"
                ),
                "meeting_flag" => array("0" => "否",
                    "1" => "发起者",
                    "2" => "批准者"
                ),
                "training_flag" => array("0" => "否",
                    "1" => "发起者",
                    "2" => "批准者"
                ),
            );

            if($contractor_id == '799'){
                $role['ptw_role']['4'] = "HDKH工程师";
                $role['ptw_role']['5'] = "HDKH安全部门";
            }
        }else{
            $role = array("ra_role" => array("0" => "No",
                "1" => "Approver",
                "2" => "Leader",
                "3" => "Member"
            ),
                "ptw_role" => array("0" => "No",
                    "1" => "Applicant",
                    "4" => "Assessor2",
                    "2" => "Assessor",
                    "3" => "Approver",
                    "5" => "Checker",
                ),
                "wsh_mbr_flag" => array("0" => "No",
                    "1" => "Yes"
                ),
                "meeting_flag" => array("0" => "No",
                    "1" => "Conducting",
                    "2" => "Approver"
                ),
                "training_flag" => array("0" => "No",
                    "1" => "Conducting",
                    "2" => "Approver"
                ),
            );
            if($contractor_id == '799'){
                $role['ptw_role']['4'] = "HDKH Engineer";
                $role['ptw_role']['5'] = "HDKH HSE";
            }
        }
        return $role;
    }
    //系统下全部权限(英文+中文)
    public static  function AllTranslation(){
        $role = array("ra_role" => array("0" => "No(否)",
                "1" => "Approver(批准者)",
                "2" => "Leader(领导)",
                "3" => "Member(成员)"
            ),
            "ptw_role" => array("0" => "No(否)",
                "1" => "Applicant(申请者)",
                "2" => "Assessor(评审者)",
                "3" => "Approver(批准者)",
//                "4" => "First instance(初审者)"
            ),
            "wsh_mbr_flag" => array("0" => "No(否)",
                "1" => "Yes(是)"
            ),
            "meeting_flag" => array("0" => "No(否)",
                "1" => "Conducting(发起者)",
                "2" => "Approver(批准者)"
            ),
            "training_flag" => array("0" => "No(否)",
                "1" => "Conducting(发起者)",
                "2" => "Approver(批准者)"
            ),
        );
        return $role;
    }

    //根据承包商ID和user_id获取所在项目的各项信息
    public static function SelfInfo($contractor_id,$user_id){
        $sql = "select a.*,b.program_name,b.program_id from bac_program_user a,bac_program b where a.contractor_id = '".$contractor_id."' and a.user_id = '".$user_id."' and a.root_proid = b.program_id";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //根据承包商ID和user_id以及program_id获取所在项目的各项信息
    public static function SelfInpro($contractor_id,$user_id,$program_id){
        $model = Program::model()->findByPk($program_id);
        $root_proid = $model->root_proid;
        $sql = "select a.*,b.program_name,b.program_id from bac_program_user a,bac_program b where a.contractor_id = '".$contractor_id."' and a.user_id = '".$user_id."'and a.root_proid = '".$root_proid."' and a.root_proid = b.program_id";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //根据承包商ID和user_id以及program_id获取所在项目的各项信息
    public static function SelfByPro($contractor_id,$user_id,$program_id){
        $model = Program::model()->findByPk($program_id);
        $root_proid = $model->root_proid;
        $sql = "select a.*,b.program_name from bac_program_user a,bac_program b where a.contractor_id = '".$contractor_id."' and a.user_id = '".$user_id."' and a.root_proid = '".$root_proid."' and a.root_proid = b.program_id";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $authority_list = ProgramUser::AllRoleList();//权限列表
        $role_list = Role::roleList();//岗位列表
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs['program_name'] = $row['program_name'];
                $program_role = explode("|",$row['program_role']);

                $rs['ra_role'] = $authority_list['ra_role'][$row['ra_role']];
                $rs['ptw_role'] = $authority_list['ptw_role'][$row['ptw_role']];
                $rs['wsh_mbr_flag'] = $authority_list['wsh_mbr_flag'][$row['wsh_mbr_flag']];
                $rs['meeting_flag'] = $authority_list['meeting_flag'][$row['meeting_flag']];
                $rs['training_flag'] = $authority_list['training_flag'][$row['training_flag']];
                $rs['training_flag'] = $authority_list['training_flag'][$row['training_flag']];
                if($program_role[0] != "NULL"){
                    $rs['first_role'] = $role_list[$program_role[0]];
                }else{
                    $rs['first_role'] = '';
                }
                if($program_role[1] != "NULL"){
                    $rs['second_role'] = $role_list[$program_role[1]];
                }else{
                    $rs['second_role'] = '';
                }
                if($program_role[2] != "NULL"){
                    $rs['third_role'] = $role_list[$program_role[2]];
                }else{
                    $rs['third_role'] = '';
                }
            }
        }
        return $rs;
    }
    //统计一个人在某个项目中ptw角色
    public static function SelfPtwRole($user_id,$program_id){
        $pro_model = Program::model()->findByPk($program_id);
        $user_model = Staff::model()->findByPk($user_id);
        $root_proid = $pro_model->root_proid;
        if($program_id != $root_proid){
            $sql = "select ptw_role from bac_program_user where root_proid = '".$root_proid."' and  user_id = '".$user_id."'";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
            return $rows;
        }else{
            $sql = "select ptw_role from bac_program_user where root_proid = '".$program_id."' and  user_id = '".$user_id."'";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
            return $rows;
        }
    }
    //统计一个人在某个项目中进出场日期
    public static function SelfByDate($user_id,$program_id,$date){
        $model = Program::model()->findByPk($program_id);
        $root_proid = $model->root_proid;
        $sql = "select * from bac_program_user where root_proid = '".$root_proid."' and  user_id = '".$user_id."'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $newsql = "select * from bac_check_apply_detail_inout";
        $newcommand = Yii::app()->db->createCommand($newsql);
        $newrows = $newcommand->queryAll();
        foreach($newrows as $n => $list){
            $r[$list['apply_id']]['apply_time'] = $list['apply_time'];
        }
        foreach($rows as $n => $list){
            if($r[$list['entrance_apply_id']]['apply_time'] != "NULL"){
                $rs[$n]['entrance_time'] = $r[$list['entrance_apply_id']]['apply_time'];
            }else{
                $rs[$n]['entrance_time'] = '';
            }
            if($r[$list['leave_apply_id']]['apply_time'] != "NULL"){
                $rs[$n]['leave_time'] = $r[$list['leave_apply_id']]['apply_time'];
            }else{
                $rs[$n]['leave_time'] = '';
            }
        }
        return $rs;
    }
    //下载PDF
    public static function downloadPdf($params,$app_id){
        $program_id = $params['program_id'];
        $user_id = $params['user_id'];
        $pro_model = Program::model()->findByPk($program_id);
        $pro_params = $pro_model->params;//项目参数
        if($pro_params != '0'){
            $pro_params = json_decode($pro_params,true);
            //判断是否是迁移的
            if(array_key_exists('transfer_con',$pro_params)){
                $contractor_id = $pro_params['transfer_con'];
            }else{
                $contractor_id = Yii::app()->user->getState('contractor_id');
            }
        }else{
            $contractor_id = Yii::app()->user->getState('contractor_id');
        }

        $main_model = Contractor::model()->findByPk($contractor_id);
        $logo_pic = '/opt/www-nginx/web'.$main_model->remark;
        $_SESSION['logo'] = '';
        $arry['contractor_id'] = $contractor_id;
        // $program_list = Program::programAllList($arry);//项目列表
        // $program_name = $program_list[$program_id];//项目名称
        $root_pro_model = Program::model()->findByPk($pro_model->root_proid);
        $program_name = $root_pro_model->program_name;//项目名称

        $staff_model = Staff::model()->findByPk($user_id);//员工信息
        $con_id = $staff_model->contractor_id;//员工所属公司
        $staffinfo_model = StaffInfo::model()->findByPk($user_id);//员工资质信息
        $roleList = Role::roleList();//岗位列表
        $teamList = Role::teamList();//团队列表
        $roleList['null'] = 'No';
        $qrcode =  $staff_model->qrcode;
        $home_id_photo = $staffinfo_model->home_id_photo;
        $bca_photo = $staffinfo_model->bca_photo;
        $csoc_photo = $staffinfo_model->csoc_photo;
        $ppt_photo = $staffinfo_model->ppt_photo;
        $face_img = $staffinfo_model->face_img;
        $programuser_list = ProgramUser::PersonelAuthority($user_id, $program_id);//项目成员信息
        $authority_list = ProgramUser::AllRoleList();
        //var_dump($programuser_model);
        $approve_id = $programuser_list[0]['entrance_apply_id'];//入场审批编号
        $approve_info = CheckApplyDetailInout::dealList($approve_id);
        //$user_list = Staff::userAllList();//员工姓名
        //$user_info_list = Staff::allInfo();//员工信息（包括已被删除的）
        //$photo_list =  StaffInfo::staffinfoPhoto($user_id);
        $contractor_list = Contractor::compList();//承包商名称
        $lang = "_en";
        $showtime=Utils::DateToEn(date("Y-m-d"));//当前时间
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        //$filepath = './attachment' . '/USER' . $user_id . $lang . '.pdf';
        $filepath = Yii::app()->params['upload_file_path'] . '/USER' . $user_id . $lang . '.pdf';
        $pdf_title = 'User' . $user_id . $lang . '.pdf';
        //$filepath = '/opt/www-nginx/web/ctmgr/webuploads' . '/PTW' . $id . $lang . '.pdf';
        //$filepath = '/opt/www-nginx/web/test/ctmgr/attachment' . '/PTW' . $id . $lang . '.pdf';
        $title = Yii::t('proj_project_user', 'pdf_title');
        $header_title = Yii::t('proj_project_user','header_title');
        ///opt/www-nginx/web/test/ctmgr

        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
        require_once($tcpdfPath);
        //Yii::import('application.extensions.tcpdf.TCPDF');
        //$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf = new ReportPdf('P', 'mm', 'A4', true, 'UTF-8', false);
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);
        //$pdf->SetKeywords('PDF, LICEN');
        $_SESSION['title'] = $header_title; // 把username存在$_SESSION['user'] 里面
        // 设置页眉和页脚信息
        //$pdf->SetHeaderData('', 0, '', $header_title, array(0, 64, 255), array(0, 64, 128));
        //$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
        $pdf->Header();

        // 设置页眉和页脚字体

        if (Yii::app()->language == 'zh_CN') {
            $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //中文
        } else if (Yii::app()->language == 'en_US') {
            $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //英文
        }

        $pdf->setFooterFont(Array('helvetica', '', '8'));

        //设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');

        //设置间距
        $pdf->SetMargins(15, 27, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        //设置分页
        $pdf->SetAutoPageBreak(TRUE, 25);
        //set image scale factor
        $pdf->setImageScale(1.25);
        //set default font subsetting mode
        $pdf->setFontSubsetting(true);
        //设置字体
        if (Yii::app()->language == 'zh_CN') {
            $pdf->SetFont('droidsansfallback', '', 14, '', true); //中文
        } else if (Yii::app()->language == 'en_US') {
            $pdf->SetFont('droidsansfallback', '', 14, '', true); //英文
        }
        $pdf->AddPage();
        //拍照记录
        $y = $pdf->GetY();
        $x = $pdf->GetX();
        $next_x = $x+75;
        $last_x = $x+150;
        $main_model = Contractor::model()->findByPk($con_id);
        if($face_img){
            //$face_img = '/opt/www-nginx/web'.$face_img;
            $type = getimagesize($face_img);
            if($type['mime'] == 'image/jpeg'){
                $pdf->Image($face_img, $x, $y, 30, 30, 'JPG', '', '',  false, 300, '', false, false, 1, false, false, false);
            }else{
                $pdf->Image($face_img, $x, $y, 30, 30, 'PNG', '', '',  false, 300, '', false, false, 1, false, false, false);
            }
        }
        //$logo_pic = '/opt/www-nginx/web/filebase/company/146/图片1.png';
        if($main_model->remark != ''){
            $logo_pic = '/opt/www-nginx/web'.$main_model->remark;
        }else{
            $logo_pic = '';
        }
        if(file_exists($logo_pic)){
            $_SESSION['logo'] = $logo_pic;
        }else{
            $_SESSION['logo'] = '';
        }
        if($qrcode){
            $pdf->Image($qrcode, $last_x, $y, 30, 30, 'PNG', '', '',  false, 300, '', false, false, 1, false, false, false);
        }
        $pic_html = '<table ><tr ><td height="105px"></td></tr></table>';
        $pdf->writeHTML($pic_html, true, false, true, false, '');
        //员工信息
        $user_model = Staff::model()->findByPk($user_id);
        $user_name = $user_model->user_name;
        $staff_html =
            '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse; border-color:gray gray gray gray;border-width: 0 1px 0 1px;"><tr><td colspan="2" style="border-width: 1px;border-color:gray gray gray gray"><h5 align="center">'.$title.'</h5></td></tr><tr><td width="30%" style="border-width: 1px;border-color:gray gray gray gray">'.Yii::t('proj_project_user', 'personel_name').'</td><td width="70%" style="border-width: 1px;border-color:gray gray gray gray">'.$user_name.'</td></tr><tr><td width="30%" style="border-width: 1px;border-color:gray gray gray gray">'
            . Yii::t('proj_project_user', 'company_name') . '</td><td width="70%" height="30px" style="border-width: 1px;border-color:gray gray gray gray">'. $contractor_list[$con_id].'</td></tr><tr><td width="30%" style="border-width: 1px;border-color:gray gray gray gray">'
            . Yii::t('proj_project', 'program_name') . '</td><td width="70%" style="border-width: 1px;border-color:gray gray gray gray">'. $program_name.'</td></tr><tr><td width="30%" style="border-width: 1px;border-color:gray gray gray gray">'
            . Yii::t('proj_project_user', 'bca_pass_no') . '</td><td width="70%" style="border-width: 1px;border-color:gray gray gray gray">'.$staff_model->work_no.'</td></tr><tr><td width="30%" style="border-width: 1px;border-color:gray gray gray gray">'
            . Yii::t('proj_project_user', 'Group') . '</td><td width="70%" style="border-width: 1px;border-color:gray gray gray gray">'.$teamList[$staff_model->role_id].'</td></tr><tr><td width="30%" style="border-width: 1px;border-color:gray gray gray gray">'
            . Yii::t('proj_project_user', 'Role_id') .'</td><td width="70%" style="border-width: 1px;border-color:gray gray gray gray">'.$roleList[$staff_model->role_id].'</td></tr>';
        //风险评估职责
        $ra_role = $programuser_list[0]['ra_role'];
        $rarole_html = '<tr><td width="30%" style="border-width: 1px;border-color:gray gray gray gray">'
            . Yii::t('proj_project_user', 'ra_role') .'</td><td width="70%" style="border-width: 1px;border-color:gray gray gray gray">'
            .$authority_list['ra_role'][$ra_role].'</td></tr>' ;
        //许可证成员
        $ptw_role = $programuser_list[0]['ptw_role'];
        $ptwrole_html = '<tr><td width="30%" style="border-width: 1px;border-color:gray gray gray gray">'
            . Yii::t('proj_project_user', 'ptw_role') .'</td><td width="70%" style="border-width: 1px;border-color:gray gray gray gray">'
            .$authority_list['ptw_role'][$ptw_role].'</td></tr>';
        //安全委员会委员
        $wsh_mbr_flag = $programuser_list[0]['wsh_mbr_flag'];
        $wsh_html = '<tr><td width="30%" style="border-width: 1px;border-color:gray gray gray gray">'
            . Yii::t('proj_project_user', 'wsh_mbr_flag') .'</td><td width="70%" style="border-width: 1px;border-color:gray gray gray gray">'
            .$authority_list['wsh_mbr_flag'][$wsh_mbr_flag].'</td></tr>' ;
        //举行会议人
        $meeting_flag = $programuser_list[0]['meeting_flag'];
        $meeting_html = '<tr><td width="30%" style="border-width: 1px;border-color:gray gray gray gray">'
            . Yii::t('proj_project_user', 'meeting_flag') .'</td><td width="70%" style="border-width: 1px;border-color:gray gray gray gray">'
            .$authority_list['meeting_flag'][$meeting_flag].'</td></tr>' ;
        //举行培训人
        $training_flag = $programuser_list[0]['training_flag'];
        $training_html = '<tr><td width="30%" style="border-width: 1px;border-color:gray gray gray gray">'
            . Yii::t('proj_project_user', 'training_flag') . '</td><td width="70%" style="border-width: 1px;border-color:gray gray gray gray">'
            .$authority_list['training_flag'][$training_flag].'</td></tr>' ;
        //第一角色
        $program_role = explode('|',$programuser_list[0]['program_role']);
        $firstrole_html = '<tr><td width="30%" style="border-width: 1px;border-color:gray gray gray gray">'
            . Yii::t('proj_project_user', 'first_role') . '</td><td width="70%" style="border-width: 1px;border-color:gray gray gray gray">'
            .$roleList[$program_role[0]].'</td></tr>' ;
        //第二角色
        $secondrole_html = '<tr><td width="30%" style="border-width: 1px;border-color:gray gray gray gray">'
            . Yii::t('proj_project_user', 'second_role') . '</td><td width="70%" style="border-width: 1px;border-color:gray gray gray gray">'
            .$roleList[$program_role[1]].'</td></tr>' ;
        //第三角色
        $thirdrole_html = '<tr><td width="30%" style="border-width: 1px;border-color:gray gray gray gray">'
            . Yii::t('proj_project_user', 'third_role') . '</td><td width="70%" style="border-width: 1px;border-color:gray gray gray gray">'
            .$roleList[$program_role[2]].'</td></tr>' ;

        //提交时间
        $submit_html = '<tr><td width="30%" style="border-width: 1px;border-color:gray gray gray gray">'
            . Yii::t('proj_project_user', 'Submitted on') . '</td><td width="70%" style="border-width: 1px;border-color:gray gray gray gray">'
            . Utils::DateToEn($programuser_list[0]['record_time']) . '</td></tr>';

        //批准时间
        $approved_on_html = '<tr><td width="30%" style="border-width: 1px;border-color:gray gray gray gray">'
            . Yii::t('proj_project_user', 'Approved on') . '</td><td width="70%" style="border-width: 1px;border-color:gray gray gray gray">'
            . Utils::DateToEn($programuser_list[0]['apply_date']) . '</td></tr>';

        //批准人
        $approve_model = Staff::model()->findByPk($approve_info[0]['deal_user_id']);
        $approve_name = $approve_model->user_name;
        $approved_by_html = '<tr><td width="30%" style="border-width: 1px;border-color:gray gray gray gray">'
            . Yii::t('proj_project_user', 'Approved by') . '</td><td width="70%" style="border-width: 1px;border-color:gray gray gray gray">'
            . $approve_name . '</td></tr>';

        //批准人签名
        $content = $approve_model->signature_path;
        if($content != '' && $content != 'nil' && $content != '-1') {
            if(file_exists($content)) {
                //$content = '/opt/www-nginx/web'.$content;
                //$content = '/opt/www-nginx/web/filebase/record/2018/04/sign/pic/sign1523249465052_1.jpg';
                $approved_sign_html = '<tr><td width="30%" style="border-width: 1px;border-color:gray gray gray gray">'
                    . Yii::t('proj_project_user', 'Approver Electronic Signature') . '</td><td width="70%" style="border-width: 1px;border-color:gray gray gray gray"><img src="'.$content.'" height="50" width="50"/></td></tr>';
            }else{
                $approved_sign_html = '<tr><td width="30%" style="border-width: 1px;border-color:gray gray gray gray">'
                    . Yii::t('proj_project_user', 'Approver Electronic Signature') . '</td><td width="70%" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;</td></tr>';
            }
        }else{
            $approved_sign_html = '<tr><td width="30%" style="border-width: 1px;border-color:gray gray gray gray">'
                . Yii::t('proj_project_user', 'Approver Electronic Signature') . '</td><td width="70%" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;</td></tr>';
        }

        //Member Electronic Signature
        //本人签名
        $user_phone = $programuser_list[0]['user_phone'];
        if($user_phone == '1' ) {
            $self_sign = $user_model->signature_path;
            if($self_sign != '' && $self_sign != 'nil' && $self_sign != '-1') {
                if(file_exists($self_sign)) {
                    $self_content = $self_sign;
                    $self_sign_html = '<tr><td width="30%" style="border-width: 1px;border-color:gray gray gray gray">'
                        . 'Member Electronic Signature' . '</td><td width="70%" style="border-width: 1px;border-color:gray gray gray gray"><img src="'.$self_content.'" height="50" width="50"/></td></tr></table>';
                }else{
                    $self_sign_html = '<tr><td width="30%" style="border-width: 1px;border-color:gray gray gray gray">'
                        . 'Member Electronic Signature' . '</td><td width="70%" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;</td></tr></table>';
                }
            }else{
                $self_sign_html = '<tr><td width="30%" style="border-width: 1px;border-color:gray gray gray gray">'
                    . 'Member Electronic Signature' . '</td><td width="70%" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;</td></tr></table>';
            }
        }else{
            $self_sign = $user_model->signature_path;
            if(file_exists($self_sign)) {
                $self_content = $self_sign;
                $self_sign_html = '<tr><td width="30%" style="border-width: 1px;border-color:gray gray gray gray">'
                    . 'Member Electronic Signature' . '</td><td width="70%" style="border-width: 1px;border-color:gray gray gray gray"><img src="'.$self_content.'" height="50" width="50"/></td></tr></table>';
            }else{
                $self_sign_html = '<tr><td width="30%" style="border-width: 1px;border-color:gray gray gray gray">'
                    . 'Member Electronic Signature' . '</td><td width="70%" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;</td></tr></table>';
            }
        }

        $html = $staff_html . $rarole_html . $ptwrole_html . $wsh_html . $meeting_html . $training_html  . $firstrole_html . $secondrole_html . $thirdrole_html . $submit_html .$approved_on_html .$approved_by_html .$approved_sign_html .$self_sign_html;

        $pdf->writeHTML($html, true, false, true, false, '');

        $img_num = 0;//检验页码标志

        //身份证照片
        $home_html = '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse; border-color:gray gray gray gray;border-width: 0 1px 0 1px;"><tr><td width="25%" height="150px" style="border-width: 1px;border-color:gray gray gray gray">'
            . Yii::t('proj_project_user', 'hpme_id_photo') .'</td><td width="75%" style="border-width: 1px;border-color:gray gray gray gray"></td></tr>';
        $x = 30;
        $y_1 = 30;//第一张y的位置
        $y_2 = 150;//第二张y的位置
        //$home_id_photo
        if(file_exists($home_id_photo)){
            $pdf->AddPage();//再加一页
            $img_num = $img_num +1;
            $pdf->Image($home_id_photo, $x, $y_1, 150, 100, 'JPG', '', '',  false, 300, '', false, false, 0, $fitbox, false, false);
        }
        //护照照片
        $ppt_html = '<tr><td width="25%" height="150px" style="border-width: 1px;border-color:gray gray gray gray">'
            . Yii::t('proj_project_user', 'ppt_photo') .'</td><td width="75%" style="border-width: 1px;border-color:gray gray gray gray"></td></tr>';
        //$ppt_photo
        if(file_exists($ppt_photo)){
            if($img_num%2  == 0 ) {
                $pdf->AddPage();//再加一页
                $img_num = $img_num + 1;
                $pdf->Image($ppt_photo, $x, $y_1, 150, 100, 'JPG', '', '', false, 300, '', false, false, 0, $fitbox, false, false);
            }else{
                $img_num = $img_num +1;
                $pdf->Image($ppt_photo, $x, $y_2, 150, 100, 'JPG', '', '', false, 300, '', false, false, 0, $fitbox, false, false);
            }
        }
        //安全证照片
        $csoc_html = '<tr><td width="25%" height="150px" style="border-width: 1px;border-color:gray gray gray gray">'
            . Yii::t('proj_project_user', 'csoc_photo') .'</td><td width="75%" style="border-width: 1px;border-color:gray gray gray gray"></td></tr>';
        //$csoc_photo
        if(file_exists($csoc_photo)){
            if($img_num%2  == 0 ){
                $pdf->AddPage();//再加一页
                $img_num = $img_num +1;
                $pdf->Image($csoc_photo, $x, $y_1, 150, 100, 'JPG', '', '',  false, 300, '', false, false, 0, $fitbox, false, false);
            }else{
                $img_num = $img_num +1;
                $pdf->Image($csoc_photo, $x, $y_2, 150, 100, 'JPG', '', '',  false, 300, '', false, false, 0, $fitbox, false, false);
            }

        }
        //准证照片
        $bca_html = '<tr><td width="25%" height="150px" style="border-width: 1px;border-color:gray gray gray gray">'
            . Yii::t('proj_project_user', 'bca_photo') .'</td><td width="75%" style="border-width: 1px;border-color:gray gray gray gray"></td></tr></table>';
        //$bca_photo
        if(file_exists($bca_photo)){
            if($img_num%2  == 0 ){
                $pdf->AddPage();//再加一页
                $img_num = $img_num +1;
                $pdf->Image($bca_photo, $x, $y_1, 150, 100, 'JPG', '', '',  false, 300, '', false, false, 0, $fitbox, false, false);
            }else{
                $img_num = $img_num +1;
                $pdf->Image($bca_photo, $x, $y_2, 150, 100, 'JPG', '', '',  false, 300, '', false, false, 0, $fitbox, false, false);
            }
        }
        $aptitude_list =UserAptitude::queryAll($user_id);//人员证书
        if($aptitude_list){
            foreach($aptitude_list as $cnt => $list){
                $aptitude = explode('|',$list['aptitude_photo']);
                foreach($aptitude as $i => $photo){
                    $file = substr($photo,strripos($photo,".")+1);
                    if($file != 'pdf'){
                        if($img_num%2  == 0 ){
                            $pdf->AddPage();//再加一页
                            $img_num = $img_num +1;
                            $pdf->Image($photo, $x, $y_1, 150, 100, 'JPG', '', '',  false, 300, '', false, false, 0, $fitbox, false, false);
                        }else{
                            $img_num = $img_num +1;
                            $pdf->Image($photo, $x, $y_2, 150, 100, 'JPG', '', '',  false, 300, '', false, false, 0, $fitbox, false, false);
                        }
                    }
                }
            }
        }
        $html_2 = $home_html . $ppt_html . $csoc_html  .  $bca_html;

//        $pdf->writeHTML($html_2, true, false, true, false, '');
        //输出PDF
        $pdf->Output($pdf_title, 'D');
//        $pdf->Output($pdf_title, 'I');
        //$pdf->Output($filepath, 'F'); //保存到指定目录
        //Utils::Download($filepath, $title, 'pdf'); //下载pdf
//============================================================+
// END OF FILE
//============================================================+
    }
    //导出Excel
    public static function staffinfo($args){

        $entrance_apply = self::ENTRANCE_APPLY;
        $entrance_pending = self::ENTRANCE_PENDING;
        $entrance_success = self::ENTRANCE_SUCCESS;
        $leave_pending = self::LEAVE_PENDING;

        if($args['status'] != ''){
            $sql = "select a.user_id,a.user_name,a.user_phone,a.work_pass_type,a.nation_type,a.category,a.qrcode,a.work_no,b.ra_role,b.ptw_role,b.wsh_mbr_flag,b.record_time,b.meeting_flag,b.training_flag,b.program_role,c.face_img
                  from (bac_staff a inner join bac_program_user b
                  on a.user_id = b.user_id and b.program_id = '".$args['program_id']."' and b.check_status='".$args['status']."')
                  inner join bac_staff_info c on a.user_id = c.user_id";
        }else{
            $sql = "select a.user_id,a.user_name,a.user_phone,a.work_pass_type,a.nation_type,a.category,a.qrcode,a.work_no,b.ra_role,b.ptw_role,b.wsh_mbr_flag,b.record_time,b.meeting_flag,b.training_flag,b.program_role,c.face_img
                  from (bac_staff a inner join bac_program_user b
                  on a.user_id = b.user_id and b.program_id = '".$args['program_id']."' and b.check_status in ('".$entrance_apply."','".$entrance_pending."','".$entrance_success."','".$leave_pending."'))
                  inner join bac_staff_info c on a.user_id = c.user_id";
        }
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if($args['tag'] != ''){
            $tag = explode('|',$args['tag']);
            foreach($rows as $n => $m){
                foreach($tag as $i => $id){
                    if($id == $m['user_id']){
                        $r[] = $m;
                    }
                }
            }
        }else{
            foreach($rows as $n => $m){
                $r[] = $m;
            }
        }
        return $r;
    }

    //下载PDF
    public static function downloadQrPdf($data,$program_id){
        $pro_model = Program::model()->findByPk($program_id);
        $father_proid = $pro_model->father_proid;
        $root_proid = $pro_model->root_proid;
        $program_name = $pro_model->program_name;
        $company_list = Contractor::compAllList();//承包商公司列表
        $lang = "_en";
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        //$filepath = './attachment' . '/USER' . $user_id . $lang . '.pdf';
        $filepath = Yii::app()->params['upload_tmp_path'] . '/' . $data[0] . '.pdf';
//        $full_dir = Yii::app()->params['upload_tmp_path'] . '/' .$data[0]['modelId'];
//        if(!file_exists($full_dir))
//        {
//            umask(0000);
//            @mkdir($full_dir, 0777, true);
//        }
        $title = Yii::t('proj_project_user', 'pdf_title');
        $header_title = Yii::t('proj_project_user','header_title');

        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
        require_once($tcpdfPath);
//        Yii::import('application.extensions.tcpdf.TCPDF');
//        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf = new RfPdf('P', 'mm', 'A7', true, 'UTF-8', false);
        //        var_dump($pdf);
//        exit;
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);
        //$pdf->SetKeywords('PDF, LICEN');
        $_SESSION['title'] = $header_title; // 把username存在$_SESSION['user'] 里面
        // 设置页眉和页脚信息
//        $pdf->SetHeaderData('', 0, '', $header_title, array(0, 64, 255), array(0, 64, 128));
//        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // 设置页眉和页脚字体

        if (Yii::app()->language == 'zh_CN') {
            $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //中文
        } else if (Yii::app()->language == 'en_US') {
            $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //英文
        }

        $pdf->setFooterFont(Array('helvetica', '', '8'));

        //设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');

        //设置间距
        $pdf->SetMargins(1, 1, 1);
        $pdf->setCellPaddings(1,1,1,1);
        $pdf->SetHeaderMargin(1);
        $pdf->SetFooterMargin(1);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        //设置分页
        $pdf->SetAutoPageBreak(TRUE, 1);
        //set image scale factor
        $pdf->setImageScale(1.25);
        //set default font subsetting mode
        $pdf->setFontSubsetting(true);
        //设置字体
        if (Yii::app()->language == 'zh_CN') {
            $pdf->SetFont('droidsansfallback', '', 10, '', true); //中文
        } else if (Yii::app()->language == 'en_US') {
            $pdf->SetFont('droidsansfallback', '', 10, '', true); //英文
        }
        $cms = 'img/RF.jpg';
        //总包
        if($father_proid == '0'){
            $contractor_id = $pro_model->contractor_id;
            foreach($data as $i => $user_id){
                $html = "";
                $pdf->AddPage('L', 'A7');
                $user_model = Staff::model()->findByPk($user_id);
                $user_name = $user_model->user_name;
                $work_no = $user_model->work_no;
                Staff::buildQrCode($contractor_id,$user_id);
                $filename = $user_model->qrcode;
//            $pdf->Image($cms, 84, 60, 20, 5, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                $title = "<h2 style=\"font-size: 200% \" align=\"center\">$program_name</h2>";
                $html.= "<table width=\"100%\" border=\"1\" cellpadding=\"4\">
                    <tr>
  	                    <td  align=\"left\" height=\"20px\" width=\"25%\" >NAME</td>
  	                    <td  align=\"center\" width=\"42%\" >$user_name</td>
  	                    <td rowspan=\"3\" align=\"center\" width=\"33%\"><br><br><img src=\"$filename\" height=\"100\" width=\"100\" align=\"middle\"/></td>
                    </tr>
                    <tr>
                        <td  align=\"left\" height=\"20px\" width=\"25%\" >COMPANY</td>
  	                    <td  align=\"center\" width=\"42%\" >$company_list[$contractor_id]</td>
                    </tr>
                    <tr>
                        <td  align=\"left\" height=\"20px\" width=\"25%\" >WORK PASS / NRIC NO.</td>
  	                    <td  align=\"center\" width=\"42%\" >$work_no</td>
                    </tr>
                </table><br><div style=\"text-align:right\"><img src=\"$cms\" height=\"20\" width=\"65\" /></div>";
                $pdf->writeHTML($title, true, false, true, false, '');
                $pdf->writeHTML($html, true, false, true, false, '');
            }
        }else{
            $program_name =  substr($program_name,0,strrpos($program_name ,"_"));;
            $sub_contractor_id = $pro_model->contractor_id;
            $root_model = Program::model()->findByPk($root_proid);
            $contractor_id = $root_model->contractor_id;
            foreach($data as $i => $user_id){
                $html = "";
                $pdf->AddPage('L', 'A7');
                $user_model = Staff::model()->findByPk($user_id);
                $user_name = $user_model->user_name;
                $work_no = $user_model->work_no;
                Staff::buildQrCode($sub_contractor_id,$user_id);
                $filename = $user_model->qrcode;
//            $pdf->Image($cms, 84, 60, 20, 5, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                $title = "<h2 style=\"font-size: 200% \" align=\"center\">$program_name</h2>";
                $html.= "<table width=\"100%\" border=\"1\" cellpadding=\"4\">
                    <tr>
  	                    <td  align=\"left\" height=\"20px\" width=\"25%\" >NAME</td>
  	                    <td  align=\"center\" width=\"42%\" >$user_name</td>
  	                    <td rowspan=\"4\" align=\"center\" width=\"33%\"><br><br><img src=\"$filename\" height=\"100\" width=\"100\" align=\"middle\"/></td>
                    </tr>
                    <tr>
                        <td  align=\"left\" height=\"20px\" width=\"25%\" >COMPANY</td>
  	                    <td  align=\"center\" width=\"42%\" >$company_list[$sub_contractor_id]</td>
                    </tr>
                   <tr>
                        <td  align=\"left\" height=\"20px\" width=\"25%\" >MAIN CONTRACTOR</td>
  	                    <td  align=\"center\" width=\"42%\" >$company_list[$contractor_id]</td>
                    </tr>
                    <tr>
                        <td  align=\"left\" height=\"20px\" width=\"25%\" >WORK PASS / NRIC NO.</td>
  	                    <td  align=\"center\" width=\"42%\" >$work_no</td>
                    </tr>
                </table><br><div style=\"text-align:right\"><img src=\"$cms\" height=\"20\" width=\"65\" /></div>";
                $pdf->writeHTML($title, true, false, true, false, '');
                $pdf->writeHTML($html, true, false, true, false, '');
            }
        }
        //输出PDF
//        $pdf->Output($pdf_title, 'D');
//        $pdf->Output($pdf_title, 'I');
        $pdf->Output($filepath, 'F'); //保存到指定目录
        return $filepath;
//============================================================+
// END OF FILE
//============================================================+
    }

    //生成压缩包
    public static function createPbuZip(){
        $time = time();
        $filename = "/opt/www-nginx/web/filebase/tmp/".$time.".zip";
        if (!file_exists($filename)) {
            $zip = new ZipArchive();//使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释
//                $zip->open($filename,ZipArchive::CREATE);//创建一个空的zip文件
            if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
                //如果是Linux系统，需要保证服务器开放了文件写权限
                exit("文件打开失败!");
            }
            $redis = new Redis();
            $redis->connect('127.0.0.1', 6379);
            $redis->select(7);
            $filepath_cnt = $redis->get('user_cnt');
            $x = 0;
            for($j=0;$j<=$filepath_cnt;$j++){
                $path = $redis->lPop('user-list');
                if (file_exists($path)) {
                    $file[$x] = $path;
                    $zip->addFile($path, basename($path));//第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下
                    $x++;
                }
            }

            $zip->close();
        }
        if(count($file) > 0){
            foreach ($file as $cnt => $path) {
                unlink($path);
            }
        }
        return $filename;
    }
    //项目人员同步到人脸机
    public static function UserSync($program_id){
        $pro_model = Program::model()->findByPk($program_id);
        $root_proid = $pro_model->root_proid;
        $sql = "select a.user_id, a.user_name, a.contractor_id, a.user_phone,
                   b.face_img,
                   c.check_status,c.refid
            from bac_staff a
            join bac_program_user c on a.user_id = c.user_id
            left join bac_staff_info b on a.user_id = b.user_id
            where c.root_proid = :root_proid and b.face_img <> '' and a.status = '0' and c.check_status = '11'";

        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":root_proid", $root_proid, PDO::PARAM_STR);
        $rows = $command->queryAll();
        $img_path = "/opt/www-nginx/web";
        $method = "usersync";
        $version = "1.0";
        $userlist = array();
        $index = 0;
        if(count($rows)>0){
            foreach($rows as $i => $j){
                $index++;
                if(strstr($j['face_img'],$img_path) == false){
                    $face_img = $img_path.$j['face_img'];
                }else{
                    $face_img = $j['face_img'];
                }
                $t = array();
                $t['name'] = $j['user_name'];
                $t['gender'] = '';
                $t['phone'] = $j['user_id'];
                $t['company_id'] = $j['contractor_id'];
                $t['face_img_path'] = $face_img;
                //$j['refid'] 没有值时表示此人不在人脸机上，调用新增接口，有值调用更新
                //0-新增 1-删除 2-修改
                if($j['refid'] == '' || $j['refid'] == NULL){
                    $t['operate'] = '0';
                }else{
                    $t['operate'] = '2';
                }
                $userlist[] = $t;
                if($index == 20){
                    $body = [];
                    $body['method'] = $method;
                    $body['version'] = $version;
                    $body['data']['userlist'] = $userlist;
                    $body['data']['project_id'] = $root_proid;
                    //$json_body = json_encode($body,JSON_UNESCAPED_SLASHES);
                    $json_body = str_replace("\\/", "/", json_encode($body,JSON_UNESCAPED_UNICODE));
                    $model = new AtdApiTask('create');
                    $model->cmd = $method;
                    $model->request = $json_body;
                    $result = $model->save();
                    $id = $model->id;
                    $apiurl = "https://127.0.0.1:443/faceapi/".$method.'?id='.$id;

                    $header = array(
                        'Content-Type: charset=utf-8',
                    );
                    $curl = curl_init();
                    //设置抓取的url
                    curl_setopt($curl, CURLOPT_URL, $apiurl);
                    //设置头文件的信息作为数据流输出
                    curl_setopt($curl, CURLOPT_HEADER, 0);
                    // 超时设置,以秒为单位
                    curl_setopt($curl, CURLOPT_TIMEOUT, 1);

                    // 超时设置，以毫秒为单位
                    curl_setopt($curl, CURLOPT_TIMEOUT, 1);

                    // 设置请求头
                    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
                    //设置获取的信息以文件流的形式返回，而不是直接输出。
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                    //执行命令
                    $data = curl_exec($curl);
                     //显示错误信息
                    if (curl_error($curl)) {
                        $r['status'] = '-1';
                        $r['msg'] = curl_error($curl);
                    } else {
                        // 打印返回的内容
                        curl_close($curl);
                        $r['status'] = '1';
                        $r['msg'] = json_encode($data);
                    }
                    $userlist = array();
                    $index = 0;
                }
            }
            if($index < 20){
                $body = [];
                $body['method'] = $method;
                $body['version'] = $version;
                $body['data']['userlist'] = $userlist;
                $body['data']['project_id'] = $root_proid;
                //$json_body = json_encode($body,JSON_UNESCAPED_SLASHES);
                $json_body = str_replace("\\/", "/", json_encode($body,JSON_UNESCAPED_UNICODE));
                $model = new AtdApiTask('create');
                $model->cmd = $method;
                $model->request = $json_body;
                $result = $model->save();
                $id = $model->id;
                $apiurl = "https://127.0.0.1:443/faceapi/".$method.'?id='.$id;

                $header = array(
                    'Content-Type: charset=utf-8',
                );
                $curl = curl_init();
                //设置抓取的url
                curl_setopt($curl, CURLOPT_URL, $apiurl);
                //设置头文件的信息作为数据流输出
                curl_setopt($curl, CURLOPT_HEADER, 0);
                // 超时设置,以秒为单位
                curl_setopt($curl, CURLOPT_TIMEOUT, 1);

                // 超时设置，以毫秒为单位
                // curl_setopt($curl, CURLOPT_TIMEOUT_MS, 500);

                // 设置请求头
                curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
                //设置获取的信息以文件流的形式返回，而不是直接输出。
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                //执行命令
                $data = curl_exec($curl);
                 //显示错误信息
                if (curl_error($curl)) {
                    $r['status'] = '-1';
                    $r['msg'] = curl_error($curl);
                } else {
                    // 打印返回的内容
                    curl_close($curl);
                    $r['status'] = '1';
                    $r['msg'] = json_encode($data);
                }
            }
        }else{
            $r['status'] = '0';
            $r['msg'] = 'No sync user';
        }
        if ($r['msg'] == 'Operation timed out after 1000 milliseconds with 0 bytes received') {
            $r['msg'] = 'Please wait while executing...';
        }
        // $r['status'] = '1';
        // $r['msg'] = 'Set Success';
        return $r;
    }
}
