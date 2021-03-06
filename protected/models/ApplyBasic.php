<?php

/**
 * 许可证申请表
 * @author LiuMinchao
 */
class ApplyBasic extends CActiveRecord {

    //状态：00-未审批，01－审批中，02审批完成。
    const STATUS_APPLY_AUDITING = '0'; //申请审批中
    const STATUS_APPLY_FINISH = '1'; //申请审批完成
    const STATUS_REJECT = '2'; //拒绝或者驳回
    const STATUS_CLOSE_AUDITING = '3'; //申请关闭中
    const STATUS_CLOSE_FINISH = '4'; //关闭审批完成
    const STATUS_ACKNOWLEDG = '10';//确认
    const RESULT_YES = 0;
    const RESULT_NO = 1;
    const RESULT_NA = 2;
    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'ptw_apply_basic';
    }

    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_APPLY_AUDITING => Yii::t('license_licensepdf', 'STATUS_AUDITING'),
            self::STATUS_APPLY_FINISH => Yii::t('license_licensepdf', 'STATUS_FINISH'),
            self::STATUS_CLOSE_AUDITING => Yii::t('license_licensepdf', 'STATUS_CLOSE_AUDITING'),
            self::STATUS_CLOSE_FINISH => Yii::t('license_licensepdf', 'STATUS_CLOSE_FINISH'),
            self::STATUS_REJECT       => Yii::t('license_licensepdf', 'STATUS_REVOKED'),
        );
        return $key === null ? $rs : $rs[$key];
    }
    //拒绝&&驳回状态
    public static function statusNo($key = null){
        $rs = array(
            '1' => Yii::t('license_licensepdf', 'STATUS_REJECT'),
            '2' => Yii::t('license_licensepdf', 'STATUS_REJECT'),
            '4' => Yii::t('license_licensepdf', 'STATUS_CLOSE_REJECT'),
            '5' => Yii::t('license_licensepdf', 'STATUS_CLOSE_REJECT'),
            '6' => Yii::t('license_licensepdf', 'STATUS_REVOKED'),
            '8' => Yii::t('license_licensepdf', 'STATUS_REJECT'),
            '10' => Yii::t('license_licensepdf', 'STATUS_REJECT'),
        );
        return $key === null ? $rs : $rs[$key];
    }
    //状态列表
    public static function statusList($key = null){
        $rs = array(
            '0' => Yii::t('license_licensepdf', 'submitted'),
            '1' => Yii::t('license_licensepdf', 'assessed'),
            '11' => Yii::t('license_licensepdf', 'assessed2'),
            '2' => Yii::t('license_licensepdf', 'approved'),
            '1R' => Yii::t('license_licensepdf', 'rejected'),
            '3' => Yii::t('license_licensepdf', 'closed'),
            '4' => Yii::t('license_licensepdf', 'closure accepted'),
            '5' => Yii::t('license_licensepdf', 'closure approved'),
            '2R' => Yii::t('license_licensepdf', 'closure rejected'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_APPLY_AUDITING => 'label-info', //审批中
            self::STATUS_CLOSE_AUDITING => 'label-info', //关闭中
            self::STATUS_ACKNOWLEDG => 'label-info',
            self::STATUS_APPLY_FINISH => 'label-success', //审批完成
            self::STATUS_CLOSE_FINISH => 'label-success', //关闭完成
            self::STATUS_REJECT => 'label-danger', //不通过或者驳回

        );
        return $key === null ? $rs : $rs[$key];
    }

    public static  function AllRoleList(){
        if (Yii::app()->language == 'zh_CN') {
            $role = array("0" => array("0" => "等待审批",
                "1" => "审批中",
                "2" => "审批中",
                "3" => "成员"
            ),
                "ptw_role" => array("0" => "否",
                    "1" => "申请者",
                    "4" => "评审者2",
                    "2" => "评审者",
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
        }
        return $role;
    }


    public static function resultText($key = null) {
        $rs = array(
            self::RESULT_YES => 'YES',
            self::RESULT_NO => 'NO',
            self::RESULT_NA => 'N/A',
        );
        return $key == null ? $rs : $rs[$key];
    }
    public static function declareText($program_id=null){
        if ($program_id == null) {
            $list = array(
                "A" => array(
                    "0" => "I shall assure that there are no incompatible works before submitting this PTW and shall ensure that all safety control measures are duly checked by the person responsible until work completion.I am aware that no work shall be carried out before PTW approval.",
                    "1" => " a) I have verified all the checklists attached to this PTW, and b) I confirm that the safety control measures are in place when assessing this PTW and thereafter shall ensure it remain in place by the applicant's until work completion by exercising close monitoring.",
                    "2" => " I shall ensure that there are no incompatible works before approving this permit and shall ensure that the safety control measures are duly checked by the person responsible until work completion."
                ),
                "B" => array(
                    "0" => "I shall assure that there are no incompatible works before submitting this PTW and shall ensure that all safety control measures are duly checked by the person responsible until work completion.I am aware that no work shall be carried out before PTW approval.",
                    "1" => " a) I have verified all the checklists attached to this PTW, and b) I confirm that the safety control measures are in place when assessing this PTW and thereafter shall ensure it remain in place by the applicant's until work completion by exercising close monitoring.",
                    "2" => "a) I have verified all the checklists attached to this PTW, and b) I confirm that the safety control measures are in place when assessing this PTW and thereafter shall ensure it remain in place by the applicant's until work completion by exercising close monitoring.",
                    "3" => " I shall ensure that there are no incompatible works before approving this permit and shall ensure that the safety control measures are duly checked by the person responsible until work completion."
                )
            );
        }else{
            $program = Program::model()->findByPk($program_id);
            if ($program) {
                if ($program->main_conid=='970') {
                    $list = array(
                        "A" => array(
                            "0" => "I shall assure that there are no incompatible works before submitting this PTW and shall ensure that all safety control measures are duly checked by the person responsible until work completion.I am aware that no work shall be carried out before PTW approval.",
                            "1" => " The above is in order during the time of my inspection.",
                            "2" => " I confirmed the above is in order."
                        ),
                        "B" => array(
                            "0" => "I shall assure that there are no incompatible works before submitting this PTW and shall ensure that all safety control measures are duly checked by the person responsible until work completion.I am aware that no work shall be carried out before PTW approval.",
                            "1" => " The above is in order during the time of my inspection.",
                            "2" => " I confirmed the above is in order."
                        )
                    );
                }else if($program->main_conid=='454') {
                    $list = array(
                        "A" => array(
                            "0" => "I fully understand the nature of the work and safety conditions that must be met. I have inspected the safety conditions relating to the work to be performed.",
                            "1" => "",
                            "2" => ""
                        ),
                        "B" => array(
                            "0" => "I fully understand the nature of the work and safety conditions that must be met. I have inspected the safety conditions relating to the work to be performed.",
                            "1" => "",
                            "2" => ""
                        )
                    );

                }else if($program->main_conid=='988'){
                    $list = array(
                        "A" => array(
                            "0" => "I shall assure that there are no incompatible works before submitting this PTW and shall ensure that all safety control measures are duly checked by the person responsible until work completion.I am aware that no work shall be carried out before PTW approval.",
                            "1" => " a) I have verified all the checklists attached to this PTW, and b) I confirm that the safety control measures are in place when assessing this PTW and thereafter shall ensure it remain in place by the applicant's until work completion by exercising close monitoring.",
                            "2" => " I shall ensure that there are no incompatible works before approving this permit and shall ensure that the safety control measures are duly checked by the person responsible until work completion."
                        ),
                        "B" => array(
                            "0" => "  I fully understand the nature of the work and safety conditions that must be met. I have inspected the safety conditions relating to the work to be performed and will ensure that it is maintained safe for work to be carried out at all times during the duration of this permit.",
                            "1" => " The above is in order during the time of my inspection.",
                            "2" => " The above Permit to Work is acknowledged by the Workplace Safety & Health Officer.",
                            "3" => " I confirmed the above is in order and hereby APPROVE/REJECT."
                        )
                    );
                }else{
                    $list = array(
                        "A" => array(
                            "0" => "I shall assure that there are no incompatible works before submitting this PTW and shall ensure that all safety control measures are duly checked by the person responsible until work completion.I am aware that no work shall be carried out before PTW approval.",
                            "1" => " a) I have verified all the checklists attached to this PTW, and b) I confirm that the safety control measures are in place when assessing this PTW and thereafter shall ensure it remain in place by the applicant's until work completion by exercising close monitoring.",
                            "2" => " I shall ensure that there are no incompatible works before approving this permit and shall ensure that the safety control measures are duly checked by the person responsible until work completion."
                        ),
                        "B" => array(
                            "0" => "I shall assure that there are no incompatible works before submitting this PTW and shall ensure that all safety control measures are duly checked by the person responsible until work completion.I am aware that no work shall be carried out before PTW approval.",
                            "1" => " a) I have verified all the checklists attached to this PTW, and b) I confirm that the safety control measures are in place when assessing this PTW and thereafter shall ensure it remain in place by the applicant's until work completion by exercising close monitoring.",
                            "2" => "a) I have verified all the checklists attached to this PTW, and b) I confirm that the safety control measures are in place when assessing this PTW and thereafter shall ensure it remain in place by the applicant's until work completion by exercising close monitoring.",
                            "3" => " I shall ensure that there are no incompatible works before approving this permit and shall ensure that the safety control measures are duly checked by the person responsible until work completion."
                        )
                    );
                }
            }
        }
        return $list;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'apply_id' => 'Apply',
            'approve_id' => 'Approve',
            'program_id' => 'Program',
            'program_name' => 'Program Name',
            'apply_date' => 'Apply Date',
            'contractor_id' => 'Contractor',
            'contractor_name' => 'Contractor Name',
            'from_time' => 'From Time',
            'to_time' => 'To Time',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'condition_set' => 'Condition Set',
            'status' => 'Status',
            'record_time' => 'Record Time',
            'work_content' => 'Work Content',
        );
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
    public static function newqueryList($page, $pageSize, $args = array()) {

        $condition = '';
        $block_condition = '';
        $sql = '';
        $apply_contractor_id = '';

        $contractor_id = Yii::app()->user->getState('contractor_id');
        $pro_model =Program::model()->findByPk($args['program_id']);

        //分包项目
        if($pro_model->main_conid != $contractor_id){
            $root_proid = $pro_model->root_proid;
            $args['con_id'] = $contractor_id;
        }else{
            $root_proid = $args['program_id'];
        }

        if ($args['con_id'] != '') {
            $apply_contractor_id = $args['con_id'];
            $condition .= "and apply_contractor_id = '$apply_contractor_id'";
        }

        if ($args['type_id'] != '') {
            $type_id = $args['type_id'];
            $condition .= "and type_id = '$type_id'";
        }

        if ($args['start_date'] != '') {
            $start_date = Utils::DateToCn($args['start_date']);
            $condition .= " and record_time >='$start_date'";
        }

        if ($args['end_date'] != '') {
            $end_date = Utils::DateToCn($args['end_date']);
            $condition .= " and record_time <='$end_date 23:59:59'";
        }

        if ($args['block'] != '') {
            $block = $args['block'];
            $block_condition .= " and b.block like '%$block%'";
        }

        if ($args['secondary_region'] != '') {
            $secondary_region = $args['secondary_region'];
            $block_condition .= " and b.secondary_region = '$block'";
        }

        $condition_status = '1=1';
        if($args['status'] != ''){
            $status = $args['status'];
            if ($args['status'] == '1R'){
                $condition_status.= " and (enddealtype in ('1','8','2') and enddealstatus = '2' or enddealtype ='6')";
            }else if ($args['status'] == '2R'){
                $condition_status.= " and enddealtype in ('4','5') and enddealstatus = '2'";
            }else if ($args['status'] == '1'){
                $condition_status.= " and enddealtype = '1' and enddealstatus = '1'";
            }else if ($args['status'] == '11'){
                $condition_status.= " and enddealtype = '8' and enddealstatus = '1'";
            }else{
                $condition_status.= " and enddealtype = $status and enddealstatus = '1'";
            }
        }
        $start=$page*$pageSize;
        if ($args['block'] == '' && $args['secondary_region'] == ''){
            $sql_1 = "SELECT
            p.*, if(p.enddealtype='6','1','0') as isreject,
            p.enddealstep as current_step, p.enddealstatus as deal_status
        FROM(
            SELECT
                a.apply_id, a.title, a.record_time, a.program_id, a.program_name,
                a.apply_contractor_id, a.apply_contractor_name as contractor_name,a.type_id,
                a.apply_user_id, a.status, IFnull(a.add_conid, 'A') as ptw_mode,
                SUBSTRING_INDEX(a.add_operator, '|', 1) as enddealstep,
                SUBSTRING_INDEX(SUBSTRING_INDEX(a.add_operator, '|', 2), '|', -1) as enddealtype,
                SUBSTRING_INDEX(a.add_operator, '|', -1) as enddealstatus
            FROM
                ptw_apply_basic a
            WHERE
                a.program_id = '".$root_proid."'  $condition
            ) p
        WHERE $condition_status
        ORDER BY p.record_time desc limit $start, $pageSize";

            $sql_2 = "SELECT
            count(*)
        FROM(
            SELECT
                a.apply_id, a.title, a.record_time, a.program_id, a.program_name,
                a.apply_contractor_id, a.apply_contractor_name as contractor_name,a.type_id,
                a.apply_user_id, a.status, IFnull(a.add_conid, 'A') as ptw_mode,
                SUBSTRING_INDEX(a.add_operator, '|', 1) as enddealstep,
                SUBSTRING_INDEX(SUBSTRING_INDEX(a.add_operator, '|', 2), '|', -1) as enddealtype,
                SUBSTRING_INDEX(a.add_operator, '|', -1) as enddealstatus
            FROM
                ptw_apply_basic a
            WHERE
                a.program_id = '".$root_proid."'  $condition
            ) p
        WHERE $condition_status
        ORDER BY p.record_time desc";
        }else{
            $sql_1 = "SELECT
            p.*, if(p.enddealtype='6','1','0') as isreject,
            p.enddealstep as current_step, p.enddealstatus as deal_status
        FROM(
            SELECT
                a.apply_id, a.title, a.record_time, a.program_id, a.program_name,
                a.apply_contractor_id, a.apply_contractor_name as contractor_name,a.type_id,
                a.apply_user_id, a.status, IFnull(a.add_conid, 'A') as ptw_mode,
                SUBSTRING_INDEX(a.add_operator, '|', 1) as enddealstep,
                SUBSTRING_INDEX(SUBSTRING_INDEX(a.add_operator, '|', 2), '|', -1) as enddealtype,
                SUBSTRING_INDEX(a.add_operator, '|', -1) as enddealstatus
            FROM
                ptw_apply_basic a
            JOIN
                ptw_apply_block b ON a.apply_id = b.apply_id
            WHERE
                a.program_id = '".$root_proid."'  $condition $block_condition
            ) p
        WHERE $condition_status
        ORDER BY p.record_time desc limit $start, $pageSize";

            $sql_2 = "SELECT
            count(*)
        FROM(
            SELECT
                a.apply_id, a.title, a.record_time, a.program_id, a.program_name,
                a.apply_contractor_id, a.apply_contractor_name as contractor_name,a.type_id,
                a.apply_user_id, a.status, IFnull(a.add_conid, 'A') as ptw_mode,
                SUBSTRING_INDEX(a.add_operator, '|', 1) as enddealstep,
                SUBSTRING_INDEX(SUBSTRING_INDEX(a.add_operator, '|', 2), '|', -1) as enddealtype,
                SUBSTRING_INDEX(a.add_operator, '|', -1) as enddealstatus
            FROM
                ptw_apply_basic a
           JOIN
                ptw_apply_block b ON a.apply_id = b.apply_id     
            WHERE
                a.program_id = '".$root_proid."'  $condition  $block_condition
            ) p
        WHERE $condition_status
        ORDER BY p.record_time desc";
        }
        $command_1 = Yii::app()->db->createCommand($sql_1);
        $retdata_1 = $command_1->queryAll();

        $command_2 = Yii::app()->db->createCommand($sql_2);
        $retdata_2 = $command_2->queryAll();

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $retdata_2[0]['count(*)'];
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $retdata_1;

        return $rs;
    }

    public static function typeList() {

        if (Yii::app()->language == 'zh_CN') {
            $sql = "SELECT type_id,type_name FROM ptw_type_list WHERE status=00 ";
            $command = Yii::app()->db->createCommand($sql);

            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['type_id']] = $row['type_name'];
                }
            }
        } else if (Yii::app()->language == 'en_US') {
            $sql = "SELECT type_id,type_name_en FROM ptw_type_list WHERE status=00  ";
            $command = Yii::app()->db->createCommand($sql);

            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['type_id']] = $row['type_name_en'];
                }
            }
        }
        return $rs;
    }

    public static function typelanguageList() {

        $sql = "SELECT type_id,type_name,type_name_en,short_type FROM ptw_type_list WHERE status=00 ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['type_id']]['type_name'] = $row['type_name'];
                $rs[$row['type_id']]['type_name_en'] = $row['type_name_en'];
                if($row['short_type']){
                    $rs[$row['type_id']]['short_type'] = $row['short_type'];
                }else{
                    $rs[$row['type_id']]['short_type'] = $row['type_name_en'];
                }
            }
        }
        return $rs;
    }

    public static function updatePath($apply_id,$save_path) {
        $save_path = substr($save_path,18);
        $model = ApplyBasic::model()->findByPk($apply_id);
        $model->save_path = $save_path;
        $result = $model->save();
    }
    //人员按权限和成员进行统计
    public static function findBySummary($user_id,$program_id,$date){
        $table = "bac_check_apply_detail_" . $date;
        $sql_1 = "SELECT a.type_id, b.deal_type, count(distinct b.apply_id) as cnt
                  FROM ptw_apply_basic a inner join $table b
                  on  a.apply_id = b.apply_id and a.program_id = '".$program_id."' and b.app_id = 'PTW' and b.deal_user_id = '".$user_id."'
                  group by a.type_id";
        $sql_2 ="SELECT c.type_id, 'MEMBER' as deal_type, count(distinct c.apply_id) as cnt
                  FROM ptw_apply_basic c inner join ptw_apply_worker d
                  on c.apply_id=d.apply_id where c.program_id = '".$program_id."' and d.user_id = '".$user_id."'
                  group by c.type_id";
        $ptw_type = ApplyBasic::typeList();//许可证类型表(双语)
        $status_css = CheckApplyDetail::statusText();//PTW执行类型
        $command_1 = Yii::app()->db->createCommand($sql_1);
        $rows_1 = $command_1->queryAll();
        $command_2 = Yii::app()->db->createCommand($sql_2);
        $rows_2 = $command_2->queryAll();
        $rows = array_merge($rows_1, $rows_2);
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$key]['type_id'] = $row['type_id'];
                $rs[$key]['type_name'] = $ptw_type[$row['type_id']];
                if($row['deal_type'] == 'MEMBER'){
                    $rs[$key]['deal_type'] = $row['deal_type'];
                }else{
                    $rs[$key]['deal_type'] = $row['deal_type'];
                }
                $rs[$key]['cnt'] = $row['cnt'];
            }
        }
        return $rs;
    }
    //统计总次数
    public static function cntBySummary($user_id,$program_id,$date){
        $table = "bac_check_apply_detail_" . $date;
        $sql = "select count(DISTINCT aa.apply_id) as cnt
                  from (  SELECT a.type_id, b.deal_type, b.apply_id
                              FROM ptw_apply_basic a inner join $table b
                              on  a.apply_id = b.apply_id and a.program_id = '".$program_id."' and b.app_id = 'PTW'  and b.deal_user_id = '".$user_id."'
                           UNION
                           SELECT c.type_id, 'MEMBER' as deal_type, c.apply_id
                              FROM ptw_apply_basic c inner join ptw_apply_worker d
                              on c.apply_id=d.apply_id where c.program_id = '".$program_id."' and d.user_id = '".$user_id."'
                  )aa";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }

    //设备统计使用次数
    public static function deviceByType($device_id,$program_id){
        $sql = "SELECT b.type_id, count(a.apply_id) as cnt
                FROM ptw_apply_device a inner join ptw_apply_basic b
                on a.device_id = '".$device_id."' and a.apply_id=b.apply_id and b.program_id = '".$program_id."'
                group by b.type_id ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $ptw_type = ApplyBasic::typeList();//许可证类型表(双语)
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$key]['type_id'] = $row['type_id'];
                $rs[$key]['type_name'] = $ptw_type[$row['type_id']];
                $rs[$key]['cnt'] = $row['cnt'];
            }
        }
        return $rs;
    }
    //统计使用总次数
    public static function deviceByAll($device_id,$program_id){
        $sql = "select count(DISTINCT a.apply_id) as cnt
                FROM ptw_apply_device a inner join ptw_apply_basic b
                on a.device_id = '".$device_id."' and a.apply_id=b.apply_id and b.program_id = '".$program_id."' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //下载PDF
    public static function downloadPDF($params,$app_id){
        $type = $params['type'];
        if($type == 'A'){
            $filepath = self::downloaddefaultPDF($params,$app_id);//通用
        }else if($type == 'B'){
            $id = $params['id'];
            $apply = ApplyBasic::model()->findByPk($id);//许可证基本信息表
            $program_id = $apply->program_id;
            if($program_id == '2259' || $program_id == '2411'){
                $filepath = self::downloadShsdPDF1($params,$app_id);//上海隧道
            }else{
                $filepath = self::downloadShsdPDF($params,$app_id);//上海隧道
            }
        }else if($type == 'C'){
            $filepath = self::downloadZjnyPDF($params,$app_id);//中建南阳
        }
        return $filepath;
    }
    //下载默认PDF
    public static function downloaddefaultPDF($params,$app_id){

        $id = $params['id'];
        $apply = ApplyBasic::model()->findByPk($id);//许可证基本信息表
        $device_list = ApplyDevice::getDeviceList($id);//许可证申请设备表
        $worker_list = ApplyWorker::getWorkerList($apply->apply_id);//许可证申请人员表
        $company_list = Contractor::compAllList();//承包商公司列表
        $document_list = PtwDocument::queryDocument($id);//文档列表
        $roleList = Role::roleallList();//岗位列表
        //$program_list =  Program::programAllList();//获取承包商所有项目
        //$ptw_type = ApplyBasic::typeList();//许可证类型表
        $ptw_type = ApplyBasic::typelanguageList();//许可证类型表(双语)
        $type_id = $apply->type_id;//许可证类型编号
        $program_id = $apply->program_id;
        //$programdetail_list = Program::getProgramDetail($program_id);
        //根据项目id得到总包商和根节点项目
        $region_list = PtwApplyBlock::regionList($id);//PTW项目区域

        $sql = "select count(*) as cnt from ptw_apply_basic where program_id = '".$program_id."' and apply_id < '".$id."'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();


        $lang = "_en";
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($apply->record_time,0,4);//年
        $month = substr($apply->record_time,5,2);//月
        $day = substr($apply->record_time,8,2);//日
        $hours = substr($apply->record_time,11,2);//小时
        $minute = substr($apply->record_time,14,2);//分钟
        $time = $day.$month.$year.$hours.$minute;
        //报告路径存入数据库

        //$filepath = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/ptw'.'/PTW' . $id . '.pdf';
        $filepath = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/'.$program_id.'/ptw/'.$apply->apply_contractor_id.'/PTW' . $id . $time .'.pdf';
        ApplyBasic::updatepath($id,$filepath);

        //$full_dir = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/ptw';
        $full_dir = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/'.$program_id.'/ptw/'.$apply->apply_contractor_id;
        if(!file_exists($full_dir))
        {
            umask(0000);
            @mkdir($full_dir, 0777, true);
        }
        //$filepath = '/opt/www-nginx/web/test/ctmgr/attachment' . '/PTW' . $id . $lang . '.pdf';
        $title = $apply->program_name;

        //if (file_exists($filepath)) {
        //$show_name = $title;
        //$extend = 'pdf';
        //Utils::Download($filepath, $show_name, $extend);
        //return;
        //}
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
        $pro_model = Program::model()->findByPk($program_id);
        $pro_params = $pro_model->params;//项目参数
        if($pro_params != '0'){
            $pro_params = json_decode($pro_params,true);
            if (array_key_exists('ptw_mode', $pro_params)) {
                $ptw_mode = $pro_params['ptw_mode'];
            } else {
                $ptw_mode = 'A';
            }
            //判断是否是迁移的
            if(array_key_exists('transfer_con',$pro_params)){
                $main_conid = $pro_params['transfer_con'];
            }else{
                $main_conid = $pro_model->contractor_id;//总包编号
            }
        }else{
            $ptw_mode = 'A';
            $main_conid = $pro_model->contractor_id;//总包编号
        }
        $status_css = CheckApplyDetail::normalReportStatus($ptw_mode);//PTW执行类型(成功)
        $reject_css = CheckApplyDetail::rejectTxt();//PTW执行类型(拒绝)
        $main_model = Contractor::model()->findByPk($main_conid);
        $main_conid_name = $main_model->contractor_name;
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
        $_SESSION['title'] = '';
        //$_SESSION['title'] = 'Permit-to-Work (PTW) No. (许可证申请编号):  ' . $apply->apply_id;

        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        // 设置页眉和页脚字体
        $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //英文

        $pdf->Header($logo_pic);
        $pdf->setFooterFont(Array('helvetica', '', '10'));
        $pdf->setCellPaddings(1,1,1,1);

        //设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');

        //设置间距
        $pdf->SetMargins(15, 23, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        //设置分页
        $pdf->SetAutoPageBreak(TRUE, 25);
        //set image scale factor
        $pdf->setImageScale(1.25);
        //set default font subsetting mode
        $pdf->setFontSubsetting(true);
        //设置字体
        $pdf->SetFont('droidsansfallback', '', 8, '', true); //英文

        $pdf->AddPage();
        $pdf->SetLineWidth(0.1);
        $operator_id = $apply->apply_user_id;//申请人ID
        $add_operator = Staff::model()->findByPk($operator_id);//申请人信息
        $add_role = $add_operator->role_id;
        $roleList = Role::roleallList();//岗位列表
        $apply_first_time = Utils::DateToEn(substr($apply->record_time,0,10));
        $apply_second_time = substr($apply->record_time,11,18);
        //$path = '/opt/www-nginx/appupload/4/0000002314_TBMMEETINGPHOTO.jpg';
        //标题(许可证类型+项目)
        $serial_no = $apply->program_name.'/'.'PTW'.'/'.$ptw_type[$type_id]['short_type'].'/'.$rows[0]['cnt'];
        $title_html = "<h1 style=\"font-size: 300%\" align=\"center\">{$main_conid_name}</h1><br/><h2  style=\"font-size:200% \" align=\"left\">Project (项目) : {$apply->program_name}</h2><br><h1 align=\"left\">PTW Serial No (序列号):{$serial_no}</h1>
            <h2 style=\"font-size: 200%\" align=\"left\">PTW Type (许可证类型): {$ptw_type[$type_id]['type_name_en']} ({$ptw_type[$type_id]['type_name']})</h2><br/>";
        $html =$title_html;
        $pdf->writeHTML($html, true, false, true, false, '');
        $apply_y = $pdf->GetY();
        //申请人资料
        $apply_info_html = "<h2 align=\"center\"> Applicant Details (申请人详情)</h2><table style=\"border-width: 1px;border-color:gray gray gray gray\">";
        $apply_info_html .="<tr><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Name(姓名)</td><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Designation(职位)</td><td height=\"20px\" width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">ID Number (身份证号码)</td></tr>";
        if($add_operator->work_pass_type = 'IC' || $add_operator->work_pass_type = 'PR'){
            if(substr($add_operator->work_no,0,1) == 'S' && strlen($add_operator->work_no) == 9){
                $work_no = 'SXXXX'.substr($add_operator->work_no,5,8);
            }else{
                $work_no = $add_operator->work_no;
            }
        }else{
            $work_no = $add_operator->work_no;
        }
        $apply_info_html .="<tr><td height=\"50px\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$add_operator->user_name}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$roleList[$add_role]}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$work_no}</td></tr>";
        $apply_info_html .="<tr><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Company (公司)</td><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Date of Application (申请时间)</td><td height=\"20px\" width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Electronic Signature (电子签名)</td></tr>";
        $apply_info_html .="<tr><td height=\"50px\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$company_list[$apply->apply_contractor_id]}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$apply_first_time}  {$apply_second_time}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">&nbsp;</td></tr>";
        $apply_info_html .="</table>";
        //判断电子签名是否存在 $add_operator->signature_path
//        $content = '/opt/www-nginx/web/filebase/record/2018/04/sign/pic/sign1523249465052_1.jpg';
        $content = $add_operator->signature_path;
        if($content){
            $pdf->Image($content, 150, $apply_y+40, 20, 9, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
        }
        //工作内容
        $work_content_html = "<h2 align=\"center\">Nature of Work (工作详情)</h2><table style=\"border-width: 1px;border-color:gray gray gray gray\" >";
        $work_content_html .="<tr><td height=\"20px\" width=\"100%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Title (标题)</td></tr>";
        $work_content_html .="<tr><td height=\"40px\" width=\"100%\" nowrap=\"nowrap\"  style=\"border-width: 1px;border-color:gray gray gray gray\"><br/>{$apply->title}</td></tr>";
        $work_content_html .="<tr><td height=\"20px\" width=\"100%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Work to be Performed (描述)</td></tr>";
        $work_content_html .="<tr><td height=\"80px\" width=\"100%\" nowrap=\"nowrap\"  style=\"border-width: 1px;border-color:gray gray gray gray\">{$apply->work_content}</td></tr>";
        $work_content_html .="<tr><td height=\"20px\" width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Start Time (开始时间)</td><td height=\"20px\" width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">End Time (结束时间)</td><td height=\"20px\" width=\"32%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Total Working Hours (总工时)</td></tr>";
        $work_content_html .="<tr><td height=\"50px\" width=\"34%\" nowrap=\"nowrap\"  align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>{$apply->start_time}</td><td height=\"50px\" width=\"34%\" nowrap=\"nowrap\"  align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>{$apply->end_time}</td><td height=\"50px\" width=\"32%\" nowrap=\"nowrap\"  align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>{$apply->from_time}</td></tr></table>";

        $region_html = '<h2 align="center">Work Location (工作地点)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="100%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Project Area (项目区域)</td></tr>';
        if (!empty($region_list)){
            foreach($region_list as $region => $secondary){
                $secondary_str = '';
                if(!empty($secondary))
                    foreach($secondary as $num => $secondary_region){
                        $secondary_str .= '['.$num.']:'.$secondary_region.'  ';
                    }

                $region_html .= '<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $region . '</td></tr>';
            }
        }else{
            $region_html .= '<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Nil</td></tr>';
        }

        $region_html .= '</table>';

        $progress_list = CheckApplyDetail::progressList( $app_id,$apply->apply_id,$year);//审批步骤详情

        $html_1 = $apply_info_html . $work_content_html. $region_html;

        $pdf->writeHTML($html_1, true, false, true, false, '');

        //2021-12-31zhushi start
        //安全条件
        $condition_html = '<h2 align="center">Safety Conditions (安全条件)</h2><table style="border-width: 1px;border-color:gray gray gray gray"><tr><td height="20px" width="10%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N(序号)</td><td height="20px" width="75%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Safety Conditions (安全条件)</td><td height="20px" width="15%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">YES / NO / N/A</td></tr>';  

        if($apply->condition_set != '{}' && $apply->condition_set != '[]') {
            $condition_set = json_decode($apply->condition_set, true);
            $resultText = ApplyBasic::resultText();
            $condition_list = PtwCondition::conditionList();

            if (array_key_exists('name', $condition_set[0])) {
                foreach ($condition_set as $key => $row) {
                    $condition_name = '';
                    $condition_name = $row['name'] . '<br>' . $row['name_en'];
                    $condition_html .= '<tr><td  align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . ($key + 1) . '</td><td>&nbsp;' . $condition_name . '</td><td  align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $resultText[$row['check']] . '</td></tr>';
                }
            }
            if (array_key_exists('id', $condition_set[0])) {
                foreach ($condition_set as $key => $row) {
                    $condition_name = '';
                    $name = $condition_list[$row['id']]['condition_name'];
                    $name_en = $condition_list[$row['id']]['condition_name_en'];
                    $condition_name = $name . '<br>' . $name_en;
                    $condition_name = htmlspecialchars($condition_name);//针对字符串中带有 < > 进行html实体转义
                    $condition_html .= '<tr><td  align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . ($key + 1) . '</td><td  style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $condition_name . '</td><td  align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $resultText[$row['check']] . '</td></tr>';
                }
            }
        }
        $condition_html .= '</table>';
        $remark_html = "<table style=\"border-width: 1px;border-color:gray gray gray gray\"><tr><td height=\"20px\" width=\"100%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Remark (备注)</td></tr>";
        $remark_html .="<tr><td height=\"20px\" width=\"100%\" nowrap=\"nowrap\"  align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$apply->devices}</td></tr></table>";
        $html_2 =$condition_html . $remark_html;
        $pdf->writeHTML($html_2, true, false, true, false, '');
        //2021-12-31zhushi end

        //现场人员
        $worker_html = '<br/><br/><h2 align="center">Member(s) (施工人员)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="10%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N<br>(序号)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;ID Number<br>(身份证号码)</td><td  height="20px" width="35%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Name<br>(姓名)</td><td height="20px" width="15%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Employee ID<br>(员工编号)</td><td height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Designation<br>(职位)</td></tr>';

        if (!empty($worker_list)){
            $i = 1;
            foreach ($worker_list as $user_id => $r) {
                $user_model =Staff::model()->findByPk($user_id);
                if($user_model->work_pass_type = 'IC' || $user_model->work_pass_type = 'PR'){
                    if(substr($user_model->work_no,0,1) == 'S' && strlen($user_model->work_no) == 9){
                        $work_no = 'SXXXX'.substr($user_model->work_no,5,8);
                    }else{
                        $work_no = $r['work_no'];
                    }
                }else{
                    $work_no = $r['work_no'];
                }
                $worker_html .= '<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $i . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $work_no . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $r['user_name'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $user_id . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' .$roleList[$r['role_id']].  '</td></tr>';
                $i++;
            }
        }
        $worker_html .= '</table>';
        $pdf->writeHTML($worker_html, true, false, true, false, '');

        $pdf->AddPage();
        $approval_y = $pdf->GetY();
        $audit_html = '<h2 align="center">Approval Process (审批流程)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Person in Charge<br>(执行人)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray"> Status<br>(状态)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray"> Date & Time<br>(日期&时间)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Remark<br>(备注)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Electronic Signature<br>(电子签名)</td></tr>';
        $audit_html = '<h2 align="center">Approval Process (审批流程)</h2><table style="border-width: 1px;border-color:gray gray gray gray">';
        //$audit_close_html = '<h5 align="center">' . Yii::t('license_licensepdf', 'progress_close_list') . '</h5><table border="1">
        //<tr><td width="10%">&nbsp;' . Yii::t('license_licensepdf', 'seq') . '</td><td width="30%">&nbsp;' . Yii::t('license_licensepdf', 'audit_person') . '</td><td width="30%"> '. Yii::t('license_licensepdf','audit_result').'</td><td width="30%"> ' . Yii::t('tbm_meeting', 'audit_date') . '</td></tr>';

        //$progress_close_list = WorkflowProgressDetail::progressList('PTW_CLOSE', $apply->apply_id);

        $progress_result = CheckApplyDetail::resultTxt();
        $j = 1;
        $y = 1;
        $info_xx = 170;
        if($approval_y > 260){
            $info_yy = 46;
        }else{
            $info_yy = $approval_y+24;
        }
        $declare_list = self::declareText($program_id);
        if (!empty($progress_list))
            foreach ($progress_list as $key => $row) {
                $content_list = Staff::model()->findAllByPk($row['deal_user_id']);
                $content = $content_list[0]['signature_path'];
                $role_id = $content_list[0]['role_id'];
                $ptw_role = ProgramUser::SelfPtwRole($row['deal_user_id'],$program_id);
                $role_list = ProgramUser::AllRoleList($main_conid);
                if($content != '' && $content != 'nil' && $content != '-1') {
                    if(file_exists($content)){
                        $content = '<img src="'.$content.'" height="30" width="30"  /> ';
                        // $pdf->Image($content, $info_xx, $info_yy, 21, 10, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                    }
                }
                if($row['status'] == 2){
                    $status = $reject_css[$row['deal_type']];
                }else{
                    $status = $status_css[$row['deal_type']];
                }
                $record_time = Utils::DateToEn($row['deal_time']);
                $date = substr($record_time,0,11);
                $time = substr($record_time,12,18);
                if(array_key_exists($key,$declare_list[$ptw_mode])){
                    if($declare_list[$ptw_mode][$key]){
                        $declare = $declare_list[$ptw_mode][$key];
                        $audit_html .= '<tr><td colspan="4" style="border-width: 1px;border-color:gray gray white gray">'.$declare.'</td>';
                       
                        $audit_html .= '<td rowspan="3" style="border-width: 1px;border-color:gray gray gray gray" align="center">'.$status.'<br>'.$date.'<br>'.$time.'</td></tr>';
                        $audit_html .= '<tr><td >Name</td><td align="left">'.$row['user_name'].'</td><td align="center">PTW Role</td><td>'.$role_list['ptw_role'][$ptw_role[0]['ptw_role']].'</td></tr>';
                        $audit_html .= '<tr><td style="border-width: 1px;border-color:white white gray gray">Designation</td><td align="left">'.$roleList[$role_id].'</td><td align="center">Signature</td><td style="border-width: 1px;border-color:white gray gray white">'.$content.'</td></tr>';
                    }else{
                        $audit_html .= '<tr><td >Name</td><td align="left" style="border-width: 1px;border-color:gray white white white">'.$row['user_name'].'</td><td align="center" style="border-width: 1px;border-color:gray white white white">PTW Role</td><td>'.$role_list['ptw_role'][$ptw_role[0]['ptw_role']].'</td>';
                        $audit_html .= '<td rowspan="2" style="border-width: 1px;border-color:gray gray gray gray" align="center">'.$status.'<br>'.$date.'<br>'.$time.'</td></tr>';
                        $audit_html .= '<tr><td style="border-width: 1px;border-color:white white gray gray">Designation</td><td align="left">'.$roleList[$role_id].'</td><td align="center">Signature</td><td style="border-width: 1px;border-color:white gray gray white">'.$content.'</td></tr>';
                    }
                }else{
                    $audit_html .= '<tr><td >Name</td><td align="left" style="border-width: 1px;border-color:gray white white white">'.$row['user_name'].'</td><td align="center" style="border-width: 1px;border-color:gray white white white">PTW Role</td><td>'.$role_list['ptw_role'][$ptw_role[0]['ptw_role']].'</td>';
                    $audit_html .= '<td rowspan="2" style="border-width: 1px;border-color:gray gray gray gray" align="center">'.$status.'<br>'.$date.'<br>'.$time.'</td></tr>';
                    $audit_html .= '<tr><td style="border-width: 1px;border-color:white white gray gray">Designation</td><td align="left">'.$roleList[$role_id].'</td><td align="center">Signature</td><td style="border-width: 1px;border-color:white gray gray white">'.$content.'</td></tr>';
                }

                $audit_html .= '<tr><td colspan="5" style="border-width: 1px;border-color:gray gray gray gray">Remarks:   '.$row['remark'].'</td></tr>';

                $j++;
            }
        $audit_html .= '</table>';
        $pdf->writeHTML($audit_html, true, false, true, false, '');

//        $pic_html = '<h4 align="center">Site Photo(s) (现场照片)</h4><table border="1">
//                <tr><td width ="100%" height="107px"></td></tr>';
//        $pic = $progress_list[0]['pic'];
//        if($pic != '') {
//            $pic = explode('|', $pic);
////            var_dump($pic);
////            exit;
//            $info_x = 40;
//            $info_y = 148;
//            foreach ($pic as $key => $content) {
////                $pic = 'C:\Users\minchao\Desktop\5.png';
//                if($content != '' && $content != 'nil' && $content != '-1') {
//                    if(file_exists($content)){
//                        $pdf->Image($content, $info_x, $info_yy + 13, 30, 23, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
//                        $info_x += 50;
//                    }
//                }
//            }
//        }
//        $pic_html .= '</table>';

        //现场设备
        $primary_list = Device::primaryAllList();
        $device_type = DeviceType::deviceList();
        $devices_html = '<br/><br/><h2 align="center">Equipment (设备)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="10%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N<br>(序号)</td><td height="20px" width="30%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Registration No.<br>(设备编码)</td><td height="20px" width="30%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Equipment Name<br>(设备名称)</td><td height="20px" width="30%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Equipment Type<br>(设备类型)</td></tr>';
        if (!empty($device_list)){
            $j =1;
            foreach ($device_list as $id => $list) {
                $devicetype_model = DeviceType::model()->findByPk($list['type_no']);//设备类型信息
                $device_type_ch = $devicetype_model->device_type_ch;
                $device_type_en = $devicetype_model->device_type_en;
                $devices_html .= '<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $j . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $primary_list[$id] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $list['device_name'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' .$device_type_en.'<br>'.$device_type_ch . '</td></tr>';
                $j++;
            }
        }else{
            $devices_html .= '<tr><td align="center" colspan="4" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Nil</td></tr>';
        }
        $devices_html .= '</table>';

        //文档标签
        $document_html = '<br/><br/><h2 align="center">Attachment(s) (附件)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">S/N (序号)</td><td  height="20px" width="80%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">Document Name (文档名称)</td></tr>';
        if(!empty($document_list)){
            $i =1;
            foreach($document_list as $cnt => $name){
                $document_html .='<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $i . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $name . '</td></tr>';
                $i++;
            }
        }else{
            $document_html .='<tr><td colspan="2" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Nil</td></tr>';
        }
        $document_html .= '</table>';

        $region_html = '<h2 align="center">Work Location (工作地点)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="100%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Project Area (项目区域)</td></tr>';
        if (!empty($region_list))
            foreach($region_list as $region => $secondary){
                $secondary_str = '';
                if(!empty($secondary))
                    foreach($secondary as $num => $secondary_region){
                        $secondary_str .= '['.$num.']:'.$secondary_region.'  ';
                    }

                $region_html .= '<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $region . '</td></tr>';
            }
        $region_html .= '</table>';

        $html_3 = $devices_html . $document_html;

        $pdf->writeHTML($html_3, true, false, true, false, '');

        //现场照片
        $pdf->AddPage();
        $pic_title_html = '<h2 align="center">Site Photo(s) (现场照片)</h2>';
        $pdf->writeHTML($pic_title_html, true, false, true, false, '');
        if (!empty($progress_list)){
            foreach ($progress_list as $key => $row) {
                if ($row['pic'] != '') {
                    $pic = explode('|', $row['pic']);
                    foreach ($pic as $key => $content) {
                        if ($content != '' && $content != 'nil' && $content != '-1') {
                            if (file_exists($content)) {
                                $img_info = getimagesize($content);
                                $img_y = $img_info[1]/5;
                                if($img_y > 820){
                                    $img_y = 820;
                                }else{
                                    $img_y = $img_info[1];
                                }
                                $img_y = 820;
                                $img= '<img src="'.$content.'"  height="'.$img_y.'" /> ';
                                $pic_html = '<table style="border-width: 1px;border-color:lightslategray lightslategray lightslategray lightslategray">
                                    <tr><td width ="100%" >'.$img.'</td></tr>';
                                $pdf->writeHTML($pic_html, true, false, true, false, '');
                            }
                        }
                    }
                }
            }
        }
        //输出PDF
        if(array_key_exists('tag',$params)){
            if($params['tag'] == 0){
                $pdf->Output($filepath, 'F');  //保存到指定目录
            }else if($params['tag'] == 1){
                $pdf->Output($filepath, 'I');  //保存到指定目录
            }
        }else{
            $pdf->Output($filepath, 'F');  //保存到指定目录
        }
        

        return $filepath;
//============================================================+
// END OF FILE
//============================================================+
    }

    //下载PDF
    public static function downloadShsdPDF($params,$app_id){
        $id = $params['id'];
        $apply = ApplyBasic::model()->findByPk($id);//许可证基本信息表
        $device_list = ApplyDevice::getDeviceList($id);//许可证申请设备表
        $device_no = '';
        foreach($device_list as $device_id =>$device_info){
            $device_model = Device::model()->findByPk($device_id);
            $device_no = $device_model->device_id;
        }
        $worker_list = ApplyWorker::getWorkerList($apply->apply_id);//许可证申请人员表
        $company_list = Contractor::compAllList();//承包商公司列表
        $document_list = PtwDocument::queryDocument($id);//文档列表
        $staff_list = Staff::userAllList();
        //$program_list =  Program::programAllList();//获取承包商所有项目
        //$ptw_type = ApplyBasic::typeList();//许可证类型表
        $ptw_type = ApplyBasic::typelanguageList();//许可证类型表(双语)
        $role_list = Role::roleList();
        $type_id = $apply->type_id;//许可证类型编号
        $check_list = $apply->check_list;
        $program_id = $apply->program_id;
        //$programdetail_list = Program::getProgramDetail($program_id);
        //根据项目id得到总包商和根节点项目
        $region_list = PtwApplyBlock::regionList($id);//PTW项目区域
        $status_css = CheckApplyDetail::statusTxt();//PTW执行类型(成功)
        $reject_css = CheckApplyDetail::rejectTxt();//PTW执行类型(拒绝)

        $lang = "_en";
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($apply->record_time,0,4);//年
        $month = substr($apply->record_time,5,2);//月
        $day = substr($apply->record_time,8,2);//日
        $hours = substr($apply->record_time,11,2);//小时
        $minute = substr($apply->record_time,14,2);//分钟
        $time = $day.$month.$year.$hours.$minute;
        //报告路径存入数据库

        //$filepath = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/ptw'.'/PTW' . $id . '.pdf';
        $filepath = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/'.$program_id.'/ptw/'.$apply->apply_contractor_id.'/PTW' . $id . $time .'.pdf';
        ApplyBasic::updatepath($id,$filepath);

        //$full_dir = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/ptw';
        $full_dir = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/'.$program_id.'/ptw/'.$apply->apply_contractor_id;
        if(!file_exists($full_dir))
        {
            umask(0000);
            @mkdir($full_dir, 0777, true);
        }
        //$filepath = '/opt/www-nginx/web/test/ctmgr/attachment' . '/PTW' . $id . $lang . '.pdf';
        $title = $apply->program_name;

        //if (file_exists($filepath)) {
        //$show_name = $title;
        //$extend = 'pdf';
        //Utils::Download($filepath, $show_name, $extend);
        //return;
        //}
        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
        require_once($tcpdfPath);

        //Yii::import('application.extensions.tcpdf.TCPDF');
        //$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf = new ReportShsdPdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);
        //$pdf->SetKeywords('PDF, LICEN');
        $pro_model = Program::model()->findByPk($program_id);
        $pro_params = $pro_model->params;//项目参数
        $program_name = $pro_model->program_name;//项目名称
        $operator_id = $apply->apply_user_id;//申请人ID
        $add_operator = Staff::model()->findByPk($operator_id);//申请人信息
        $add_operator_conid = $add_operator->contractor_id;//总包编号
        $add_operator_model = Contractor::model()->findByPk($add_operator_conid);
        $apply_conid_name = $add_operator_model->contractor_name;
        $main_conid = $pro_model->contractor_id;//总包编号
        $main_model = Contractor::model()->findByPk($main_conid);
        $main_conid_name = $main_model->contractor_name;
        $logo_pic = '/opt/www-nginx/web'.$main_model->remark;

        $_SESSION['title'] = 'Permit-to-Work (PTW) No. (许可证申请编号):  ' . $apply->apply_id;
        $_SESSION['contractor_name'] = $main_conid_name;
        $_SESSION['contractor_id'] = $main_conid;

        $logo_pic = '/opt/www-nginx/web/filebase/company/146/shsd.png';

        $pdf->SetHeaderData($logo_pic, 20,  '',  '',array(0, 64, 255), array(0, 64, 128));

        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
        
        $pdf->SetHeaderData('', 20,  '',  '',array(0, 64, 255), array(0, 64, 128));

        // 设置页眉和页脚字体
        $pdf->setHeaderFont(Array('droidsansfallback', '', '20')); //英文
        $pdf->setFooterFont(Array('helvetica', '', '10'));
        $pdf->setCellPaddings(1,1,1,1);

        //设置默认等宽字体
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        //设置间距
        $pdf->SetMargins(15, 27, 15);
        $pdf->SetHeaderMargin(9);
        $pdf->SetFooterMargin(10);

        //设置分页
        $pdf->SetAutoPageBreak(TRUE, 25);
        //set image scale factor
        $pdf->setImageScale(1.25);
        //set default font subsetting mode
        $pdf->setFontSubsetting(true);
        //设置字体
        $pdf->SetFont('times', '', 12, '', true); //英文
        $pdf->AddPage();

        $pdf->SetLineWidth(0.1);

        $progress_list = CheckApplyDetail::progressList($app_id,$apply->apply_id,$year);//审批步骤详情
        $progress_cnt = count($progress_list);
        $background_path = 'https://shell.cmstech.sg/test/ctmgr/img/background.jpg';
        $err = file_exists($background_path);
        $checker_user_name = '';
        $checker_pic_html = '<img src="'.$background_path.'" height="30" width="30"  /> ';
        $checker_deal_time = '';
        $sc_approve_tag =0; //分包审批标识
        $sc_apply_tag = 0;//分包申请标识
        foreach($progress_list as $i => $j){
            //分包是否参与申请
            if($j['deal_type'] == '0'){
                $apply_model = Staff::model()->findByPk($j['deal_user_id']);
                $apply_contractor_id = $apply_model->contractor_id;
                if($main_conid != $apply_contractor_id){
                    $sc_apply_tag =1;
                }
            }
            //确认人
            if($j['deal_type'] == '10' && $j['step'] == '2'){
                $checker_step = $j['step'];
                $checker_user_name = $staff_list[$j['deal_user_id']];
                $checker_deal_time = Utils::DateToEn($j['deal_time']);
                $checker_model = Staff::model()->findByPk($j['deal_user_id']);
                $checker_role_id = $checker_model->role_id;
                $checker_role = $role_list[$checker_role_id];
                $checker_signature_path = $checker_model->signature_path;
                if(file_exists($checker_signature_path)) {
                    $checker_pic_html= '<img src="'.$checker_signature_path.'" height="30" width="30" />';
                }else{
                    $checker_pic_html = '<img src="'.$background_path.'" height="30" width="30"  /> ';
                }
            }
            //分包是否参与审批
            if($j['deal_type'] == '1'){
                $approve_model = Staff::model()->findByPk($j['deal_user_id']);
                $approve_contractor_id = $approve_model->contractor_id;
                if($main_conid != $approve_contractor_id){
                    $sc_approve_tag =1;
                }
            }
        }
        $ptwShsdList = ReportTemplate::ptwShsdList();
        $check_list = json_decode($check_list,true);
        if(count($check_list)>0){
            $check_id = $check_list[0]['check_id'];
            $check_list = RoutineCheck::detailList($check_id);//例行检查单
            $condition_set = json_decode($check_list[0]['condition_list'], true);
        }else{
            $condition_set = 'NULL';
        }

        $resultText = ReportShsdPdf::resultText();
        $condition_list = RoutineCondition::conditionList();
        //$data = $ptwShsdList['SHSD001'];
        $data = $ptwShsdList[$type_id];
        $_SESSION['type_id'] = $type_id;
        $bck_html= '<img src="'.$background_path.'" height="30" width="30"  />';
        $e = -2;
        $t = 0;
        if (!empty($region_list)){
            $secondary_str = "";
            $secondary_str_1 = "";
            $secondary_str_2 ="";
            $count = count($region_list);
            foreach($region_list as $region => $secondary){
                $t++;
                if($count == 1){
                    $secondary_str .= $region.' ';
                }else{
                    if($type_id == 'SHSD011' || $type_id == 'SHSD008'){
                        if($t == 2){
                            $secondary_str_1 .= 'From '.$region.' To ';
                        }
                        if($t == 1){
                            $secondary_str_2 .= $region.' ';
                        }
                    }else{
                        $secondary_str .= $region.'<br>';
                    }
                }
            }
            if($secondary_str_1){
                $secondary_str = $secondary_str_1.$secondary_str_2;
            }
        }else{
            $secondary_str = '';
        }
        foreach($data as $i => $list){
            //stage
            if($i == 'title'){

                $pdf->writeHTMLCell(0, 0, '', '', $program_name, 0, 1, 0, true, 'C', true);
                //$pdf->Cell(0, 0, $program_name, 0, 0, 'C', 0, '', 0);
                //$pdf->Cell(0, 0, 'PTW No.:'.$id, 0, 1, 'R', 0, '', 0);
                // Set some content to print
                if($type_id == 'SHSD005' || $type_id == 'SHSD006'){
                    $stage_title = $data['stage']['title'];
                    $html_left = "<b>$stage_title  $device_no)</b>";
                }else{
                    $html_left = "<b>{$data['stage']['title']}</b>";
                }
                $html_right = "<b>PTW No.:</b>{$id}";
                // Print text using writeHTMLCell()
                $pdf->writeHTMLCell(0, 0, '15', '', $html_left, 0, 0, 0, true, 'L', true);

                $pdf->writeHTMLCell(0, 0, '95', '', $html_right, 0, 1, 0, true, 'R', true);
                //$pdf->SetFont('droidsansfallback', '', 10, '', true); //英文
                $pdf->Ln(4);
            }
            $pdf->SetFont('times', '', 10, '', true); //英文
            if($i == 'stage'){
                $start_date = Utils::DateToEn($apply->start_date);
                $end_date = Utils::DateToEn($apply->start_date);
                $stage_html = "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
                foreach($list as $stage_name => $stage_list){
                    if($stage_name != 'title' && $stage_name != 'note'){
                        if($stage_name == 'dept'){
                            $stage_html .="<tr><td width=\"30%\" height=\"40px\"  nowrap=\"nowrap\"  align=\"left\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$stage_list}</td><td width=\"70%\" height=\"40px\"  nowrap=\"nowrap\"  align=\"left\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$apply_conid_name}</td></tr>";
                        }else if($stage_name == 'location'){
                            $stage_html .="<tr><td width=\"30%\" height=\"40px\"  nowrap=\"nowrap\"  align=\"left\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$stage_list}</td><td width=\"70%\" height=\"40px\"  nowrap=\"nowrap\"  align=\"left\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$secondary_str}</td></tr>";
                        }else if($stage_name == 'description'){
                            $pdf->SetFont('droidsansfallback', '', 10, '', true);
                            $stage_html .="<tr><td width=\"30%\" height=\"40px\"  nowrap=\"nowrap\"  align=\"left\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$stage_list}</td><td width=\"70%\" height=\"40px\"  nowrap=\"nowrap\"  align=\"left\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$apply->work_content}</td></tr>";
                        }else if($stage_name == 'permit_date'){
                            $stage_html .="<tr><td width=\"30%\" height=\"40px\"  nowrap=\"nowrap\"  align=\"left\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$stage_list}</td><td width=\"70%\" height=\"40px\"  nowrap=\"nowrap\"  align=\"left\" style=\"border-width: 1px;border-color:gray gray gray gray\">Form: $start_date  $apply->start_time To: $end_date  $apply->end_time</td></tr>";
                        }else if($stage_name == 'location_1'){
                            $stage_html .="<tr><td width=\"30%\" height=\"40px\"  nowrap=\"nowrap\"  align=\"left\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$stage_list['Location of Work']}</td><td width=\"70%\" height=\"40px\"  nowrap=\"nowrap\"  align=\"left\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$stage_list['Tunnel: From Ring No.']}{$secondary_str}</td></tr>";
                        }else if($stage_name == 'machine_no'){
                            $stage_html .="<tr><td width=\"30%\" height=\"40px\"  nowrap=\"nowrap\"  align=\"left\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$stage_list}</td><td width=\"70%\" height=\"40px\"  nowrap=\"nowrap\"  align=\"left\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$device_no}</td></tr>";
                        }else if($stage_name == 'machinery_movement'){
                            $stage_html .="<tr><td width=\"30%\" height=\"40px\"  nowrap=\"nowrap\"  align=\"left\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$stage_list['Machinery Movement']}</td><td width=\"70%\" height=\"40px\"  nowrap=\"nowrap\"  align=\"left\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$stage_list['Machinery Shifting From :']}&nbsp;&nbsp;{$secondary_str}</td></tr>";
                        }else if($stage_name == 'air_pressure'){
                            $stage_html .="<tr><td width=\"30%\" height=\"40px\"  nowrap=\"nowrap\"  align=\"left\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$stage_list['Compressed Air Pressure']}</td>";
                            if(is_array($condition_set)){
                                foreach ($condition_set as $key => $row) {
                                    //$pdf->SetFont('droidsansfallback', '', 9, '', true); //英文
                                    $name_en = $condition_list[$row['condition_id']]['condition_name_en'];
                                    $condition_name = $name_en;
                                    $stage_html .= '<td width="35%" style="text-align: left;border-width: 1px;border-color:gray gray gray gray" >&nbsp;' . $condition_name . '&nbsp;' . $row['remarks']  . '</td>';
                                }
                            }
                            $stage_html .= '</tr>';
                        }
                    }
                }
                $stage_html .="</table>";

                $pdf->writeHTML($stage_html, true, false, true, false, '');
                $pdf->SetFont('times', '', 10, '', true); //英文
                $stage_note = '<b>NOTE: </b>'.$list['note'];
                $pdf->MultiCell(0, 0, $stage_note, 0, 'L', 0, 1, '', '', true, 0, true, true, 20, 'M', true);
                $stage1_title = "<b>{$data['stage1']['title']}</b>";
                $pdf->writeHTMLCell(0, 0, '15', '', $stage1_title, 0, 1, 0, true, 'L', true);

                $stage1_note_1 = '<b>Note:</b> ';
                $stage1_note_2 = 'Condition of issue must be confirmed and ticked (√), if not applicable write ';
                $stage1_note_3 = '<b>‘NA’</b>';
                $pdf->writeHTMLCell(0, 0, '15', '', $stage1_note_1, 0, 0, 0, true, 'L', true);
                $pdf->SetFont('droidsansfallback', '', 10, '', true); //英文
                $pdf->writeHTMLCell(0, 0, '24', '', $stage1_note_2, 0, 0, 0, true, 'L', true);
                $pdf->SetFont('times', '', 10, '', true); //英文
                $pdf->writeHTMLCell(0, 0, '144', '', $stage1_note_3, 0, 1, 0, true, 'L', true);
            }

            $pdf->SetFont('droidsansfallback', '', 9, '', true); //英文

            if($i == 'stage1'){

                $condition_html = '<table style="border-width: 1px;border-color:gray gray gray gray">';
                if($apply->condition_set != '{}' && $apply->condition_set != '[]') {
                    $condition_set = json_decode($apply->condition_set, true);
                    $resultText = ReportShsdPdf::resultText();
                    $condition_list = PtwCondition::conditionList();
                    if (array_key_exists('name', $condition_set[0])) {
                        foreach ($condition_set as $key => $row) {
                            $condition_name = '';
                            $condition_name = $row['name_en'];
                            $condition_html .= '<tr><td >&nbsp;' . $condition_name . '</td><td  align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $resultText[$row['check']] . '</td></tr>';
                        }
                    }
                    if (array_key_exists('id', $condition_set[0])) {
                        $condition_count = round(count($condition_set)/2)-1;
                        $total_condition_count = count($condition_set);
//                        var_dump($condition_set[0]);
//                        exit;
                        foreach ($condition_set as $key => $row) {

                            if($key<=$condition_count){
                                $name_en_1 = $condition_list[$condition_set[$key]['id']]['condition_name_en'];
                                $key_2 = $key+$condition_count+1;
                                if($key_2 >= $total_condition_count){
                                    $name_en_2 = '';
                                    $resultText_2 = '';
                                }else{
                                    $name_en_2 = $condition_list[$condition_set[$key+$condition_count+1]['id']]['condition_name_en'];
                                    $resultText_2 = $resultText[$condition_set[$key+$condition_count+1]['check']];
                                }
                                $condition_html .= '<tr><td width="40%" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $name_en_1 . '</td><td width="10%" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $resultText[$condition_set[$key]['check']] . '</td>';
                                $condition_html .= '<td width="40%" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $name_en_2 . '</td><td width="10%" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $resultText_2 . '</td></tr>';
//                            }else{
                            }
                        }
                    }
                }
//                if($key%2!=1){
//                    $condition_html .= '<td width="40%" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;1111' .  '</td><td width="10%" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;222' . '</td></tr>';
//                }
                $condition_html .= '<tr><td height="80px" style="border-width: 1px;border-color:gray gray gray gray" colspan="4">&nbsp;State Other Safety Requirements:<br>' . $apply->devices . '</td></tr>';
                $condition_html .= '<tr><td style="border-width: 1px;border-color:white gray white " colspan="4">' . $list['note'] . '</td></tr>';
                $stage1_deal_user = $staff_list[$progress_list[0]['deal_user_id']];
                $stage_deal_model = Staff::model()->findByPk($progress_list[$e]['deal_user_id']);
                $signature_path = $stage_deal_model->signature_path;
//                $signature_path = '/filebase/record/2018/02/sign/pic/sign1518052755787_1.jpg';
                $pic_html= '<img src="'.$signature_path.'" height="30" width="30" />';
                if($checker_user_name != ''){
                    if($checker_step == '2'){
                        $e = $e+1;
                    }
                }
                $condition_html .= '<tr><td style="border-width: 1px;border-color:white white white " colspan="2">&nbsp;' . $list['APPLICANT NAME:'] .$stage1_deal_user. '</td><td style="border-width: 1px;border-color:white gray white " colspan="2" align="center">&nbsp;&nbsp;&nbsp;' . $list['STEC IN-CHARGE:'] .$checker_user_name.'</td></tr>';
                $stage1_deal_date = Utils::DateToEn($progress_list[0]['deal_time']);
                $condition_html .= '<tr><td  style=" border-width: 1px;border-color:white white gray " colspan="2">&nbsp;' . $list['DATE / TIME:'] .'&nbsp;'.$stage1_deal_date .'&nbsp;&nbsp;&nbsp;'. $list['SIGNATURE:'] .$pic_html.'</td><td  style="border-width: 1px;border-color:white gray gray " colspan="2" align="center" valign="bottom"> &nbsp;' . $list['SIGNATURE:'] .$checker_pic_html.'</td></tr>';
                $condition_html .= '</table>';
//                var_dump($condition_html);
//                exit;
                $pdf->writeHTML($condition_html, true, false, true, false, '');
            }

            $pdf->SetFont('times', '', 10, '', true); //英文

            if($i != 'title' && $i != 'stage' && $i != 'stage1' ){
                //$stage_deal_type = $progress_list[$e]['deal_type'];
                if($i == 'stage3' && $type_id == 'SHSD002' && $sc_apply_tag == '0') {
                    $e--;
                }
                if($i == 'stage3' && $type_id == 'SHSD003' && $sc_apply_tag == '0') {
                    $e--;
                }
                if($i == 'stage2' && $type_id != 'SHSD007'&& $type_id != 'SHSD002' && $type_id != 'SHSD003' && $sc_apply_tag == '0') {
                    $e--;
                }
                //$stage_deal_type
//                if($i != 'stage2' && $type_id != 'SHSD002' && $type_id != 'SHSD003' && $stage_deal_type == '10') {
//                    $e++;
//                }

                $html = '';
                if($e >= $progress_cnt){
                    $stage_deal_user = 'NULL';
                    $stage_deal_model = 'NULL';
                    $signature_path = '';
                    $role_id = '';
                    $user_phone = '';
                    $role_name = '';
                    $deal_date = '';
                }else{
                    $stage_deal_user = $staff_list[$progress_list[$e]['deal_user_id']];
                    $stage_deal_model = Staff::model()->findByPk($progress_list[$e]['deal_user_id']);
                    $signature_path = $stage_deal_model->signature_path;
                    $role_id = $stage_deal_model->role_id;
                    $user_phone = $stage_deal_model->user_phone;
                    $role_name = $role_list[$role_id];
                    $deal_date = Utils::DateToEn($progress_list[$e]['deal_time']);
                }

//                $signature_path = '/filebase/record/2018/02/sign/pic/sign1518052755787_1.jpg';
                $pic_html= '<img src="'.$signature_path.'" height="30" width="30"  /> ';

                if(array_key_exists('checklist',$list)){
                    $pdf->Cell(0, 0, 'The type of services are summarized below:', 0, 1, 'L', 0, '', 0);

                    $html = '<table style="border-width: 1px;border-color:gray gray gray gray">';
                    $html.='<tr><td  width="20%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Type of Services</td><td  width="15%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Present</td><td  width="15%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Not Present</td><td  width="50%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Safety Measures</td></tr>';

                    $stage_checklist = '';
                    foreach($progress_list as $i => $j){
                        if($j['check_list'] != '' && $j['check_list'] != '{}'){
                            $stage_checklist = $j['check_list'];
                            $stage_checklist = json_decode($stage_checklist,true);
                            $stage_check_id = $stage_checklist[0]['check_id'];
                        }
                    }
//                    var_dump($check_list);
//                    exit;
                    if(!empty($stage_checklist)){
                        $check_list = RoutineCheck::detailList($stage_check_id);//例行检查单
                        $condition_set = json_decode($check_list[0]['condition_list'], true);
                        $resultText = RoutineCheck::resultText();
                        $condition_list = RoutineCondition::conditionList();
//                        var_dump($condition_set);
//                        exit;
                        foreach ($condition_set as $key => $row) {
                            $name_en = $condition_list[$row['condition_id']]['condition_name_en'];
                            $condition_name = $name_en;
                            $html.= '<tr><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray" >&nbsp;' . $condition_name . '</td>';
                            if($row['flatStatus'] == '0'){
                                $html.='<td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $resultText[$row['flatStatus']]  . '</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray" >&nbsp;'. '</td>';
                            }else{
                                $html.='<td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;'   . '</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray" >&nbsp;'. $resultText[$row['flatStatus']]. '</td>';
                            }
                            $html.='<td style="border-width: 1px;border-color:gray gray gray gray">&nbsp;'.$row['remarks']. '</td></tr>';
                        }
                    }else{
                        $html.='<tr><td style="border-width: 1px;border-color:gray gray gray gray" colspan="4">&nbsp;Nil</td></tr>';
                    }

                    $html .= '</table><br>';
                }
//                var_dump($html);
//                exit;
                $j = 0;
                $pdf->SetLineStyle(array('width' => 0, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
                $pdf->SetFillColor(255,255,128);

                $title = $list['title'];
                $html_title = "<b>$title</b>";
                $pdf->writeHTMLCell(0, 0, '15', '', $html_title, 0, 1, 0, true, 'L', true);
//                $pdf->Cell(0, 0, $list['title'], 0, 1, 'L', 0, '', 0);

                if($html){
                    $html.= '<table >';
                }else{
                    $html = '<table >';
                }

                if(array_key_exists('The above work was completed on (date)',$list)){
//                    $deal_date = '2019-06-13 10:33:16';
                    if($deal_date){
                        $date = substr($deal_date,0,11);
                        $time = substr($deal_date,11,9);
                        $html .= '<tr><td colspan="2"><br>&nbsp;The above work was completed on (date) '. $date .' at (Time) '.$time.' hrs' . '<br></td></tr>';
                    }else{
                        $html .= '<tr><td colspan="2"><br>&nbsp;The above work was completed on (date)   ' .' at (Time)   '.'   hrs' . '<br></td></tr>';
                    }
                }
                if(array_key_exists('I, (NAME)',$list)){
                    $html .= '<tr><td colspan="2"><br>&nbsp;I, (NAME) '.$stage_deal_user.'  acknowledge the issuance of the permit<br></td></tr>';
                }
                foreach($list as $name => $value){
                    if($name == 'note' ){
                        $html .= '<tr ><td colspan="2">' . $list['note'] . '<br></td></tr>';
                    }else if($name != 'title' && $name != 'The above work was completed on (date)' && $name !='checklist' && $name !='I, (NAME)'){
                        if($name == 'NAME:'){
                            $val = $stage_deal_user;
                        }else if($name == 'DESIGNATION :'){
                            $val = $role_name;
                        }else if($name == 'Bck_DATE / TIME:'){
                            $val = $deal_date.$bck_html;
                        }else if($name == 'DATE / TIME:'){
                            $val = $deal_date.$bck_html;
                        }else if($name == 'SIGN :'){
                            $val = $pic_html;
                        }else if($name == 'SIGNATURE:'){
                            $val = $pic_html;
                        }else if($name == 'Checker_NAME:'){
                            $val = $stage_deal_user.$bck_html;
                        }else if($name == 'Checker_DESIGNATION:'){
                            $val = $role_name.$bck_html;
                        }else if($name == 'Checker_DATE / TIME:'){
                            $val = $deal_date;
                        }else if($name == 'Checker_SIGNATURE:'){
                            $val = $pic_html;
                        }else if($name == 'HANDPHONE / CONTACT NO. :'){
                            $val = $user_phone;
                        }else if($name == 'DATE / TIME:_last'){
                            $val = $deal_date.$bck_html;
                        }else if($name == 'SIGNATURE:_last'){
                            $val = $pic_html;
                        }else if($name == 'NAME:_last'){
                            $val = $stage_deal_user.$bck_html;
                        }else{
                            $val = '';
                        }

                        if($i == 'stage2' && $type_id != 'SHSD002' && $type_id != 'SHSD003' && $type_id != 'SHSD007'){
                            if($j%2==0){
                                if($sc_apply_tag == '0' || $sc_approve_tag == '0'){
                                    $html .= '<tr><td height="30px">&nbsp;' . $value . '&nbsp;</td>';
                                }else{
                                    $html .= '<tr><td height="30px">&nbsp;' . $value . '&nbsp;' . $val .'</td>';
                                }
                            }else{
                                if($sc_apply_tag == '0' || $sc_approve_tag == '0'){
                                    $html .= '<td>&nbsp;' . $value . '&nbsp;<br></td></tr>';
                                }else{
                                    $html .= '<td>&nbsp;' . $value . '&nbsp;' . $val .'<br></td></tr>';
                                }
                            }
                        }else if($i == 'stage3' && $type_id == 'SHSD002' && $sc_apply_tag == '0'){
                            if($j%2==0){
                                $html .= '<tr><td height="30px">&nbsp;' . $value . '&nbsp;</td>';
                            }else{
                                $html .= '<td>&nbsp;' . $value . '&nbsp;<br></td></tr>';
                            }
                        }else if($i == 'stage3' && $type_id == 'SHSD003' && $sc_apply_tag == '0'){
                            if($j%2==0){
                                $html .= '<tr><td height="30px">&nbsp;' . $value . '&nbsp;</td>';
                            }else{
                                $html .= '<td>&nbsp;' . $value . '&nbsp;<br></td></tr>';
                            }
                        }else{
                            if($j%2==0){
                                $html .= '<tr><td height="30px">&nbsp;' . $value . '&nbsp;' . $val .'</td>';
                            }else{
                                $html .= '<td>&nbsp;' . $value . '&nbsp;' . $val .'<br></td></tr>';
                            }
                        }

                        $j++;
                    }
                }
                if($j%2==1){
                    $html .= '<td>&nbsp;</td></tr>';
                }

                $html .= '</table>';
//                var_dump($html);
//                exit;
                $pdf->MultiCell('', '', $html, 1, 'J',false, 1, '', '',  true, 0,true, true, $maxh=0, 'T', false);
                $pdf->Ln(4);
            }
            $e++;
        }

        //输出PDF
        if($params['tag'] == 0){
            $pdf->Output($filepath, 'F');  //保存到指定目录
        }else if($params['tag'] == 1){
            $pdf->Output($filepath, 'I');  //保存到指定目录
        }

        return $filepath;
//============================================================+
// END OF FILE
//============================================================+
    }

    //下载PDF
    /*
     * 1.申请人申请（总/分包） -----0
     * 2.审批人1审批（总/分包）-----1
     * 3.审批人2审批（总包）-----8
     * 4.批准人批准（总包）-----2
     * 5.确认人确认（总包） -----10
     * 6.申请人关闭（总/分包）-----3
     * 7.驳回（总包）-操作驳回或拒绝PTW的人的信息 -----6
     */
    public static function downloadShsdPDF1($params,$app_id){

        $id = $params['id'];
        $apply = ApplyBasic::model()->findByPk($id);//许可证基本信息表
        $device_list = ApplyDevice::getDeviceList($id);//许可证申请设备表
        $device_no = '';
        foreach($device_list as $device_id =>$device_info){
            $device_model = Device::model()->findByPk($device_id);
            $device_no = $device_model->device_id;
        }
        $worker_list = ApplyWorker::getWorkerList($apply->apply_id);//许可证申请人员表
        $company_list = Contractor::compAllList();//承包商公司列表
        $document_list = PtwDocument::queryDocument($id);//文档列表
        $staff_list = Staff::userAllList();
        //$program_list =  Program::programAllList();//获取承包商所有项目
        //$ptw_type = ApplyBasic::typeList();//许可证类型表
        $ptw_type = ApplyBasic::typelanguageList();//许可证类型表(双语)
        $role_list = Role::roleList();
        $type_id = $apply->type_id;//许可证类型编号
        $check_list = $apply->check_list;
        $program_id = $apply->program_id;
        //$programdetail_list = Program::getProgramDetail($program_id);
        //根据项目id得到总包商和根节点项目
        $region_list = PtwApplyBlock::regionList($id);//PTW项目区域
        $status_css = CheckApplyDetail::statusTxt();//PTW执行类型(成功)
        $reject_css = CheckApplyDetail::rejectTxt();//PTW执行类型(拒绝)

        $lang = "_en";
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($apply->record_time,0,4);//年
        $month = substr($apply->record_time,5,2);//月
        $day = substr($apply->record_time,8,2);//日
        $hours = substr($apply->record_time,11,2);//小时
        $minute = substr($apply->record_time,14,2);//分钟
        $time = $day.$month.$year.$hours.$minute;
        //报告路径存入数据库

        //$filepath = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/ptw'.'/PTW' . $id . '.pdf';
        $filepath = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/'.$program_id.'/ptw/'.$apply->apply_contractor_id.'/PTW' . $id . $time .'.pdf';
        ApplyBasic::updatepath($id,$filepath);

        //$full_dir = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/ptw';
        $full_dir = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/'.$program_id.'/ptw/'.$apply->apply_contractor_id;
        if(!file_exists($full_dir))
        {
            umask(0000);
            @mkdir($full_dir, 0777, true);
        }
        //$filepath = '/opt/www-nginx/web/test/ctmgr/attachment' . '/PTW' . $id . $lang . '.pdf';
        $title = $apply->program_name;

        //if (file_exists($filepath)) {
        //$show_name = $title;
        //$extend = 'pdf';
        //Utils::Download($filepath, $show_name, $extend);
        //return;
        //}
        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
        require_once($tcpdfPath);

        //Yii::import('application.extensions.tcpdf.TCPDF');
        //$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf = new ReportShsdPdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);
        //$pdf->SetKeywords('PDF, LICEN');
        $pro_model = Program::model()->findByPk($program_id);
        $pro_params = $pro_model->params;//项目参数
        $program_name = $pro_model->program_name;//项目名称
        $operator_id = $apply->apply_user_id;//申请人ID
        $add_operator = Staff::model()->findByPk($operator_id);//申请人信息
        $add_operator_conid = $add_operator->contractor_id;//总包编号
        $add_operator_model = Contractor::model()->findByPk($add_operator_conid);
        $apply_conid_name = $add_operator_model->contractor_name;
        $main_conid = $pro_model->contractor_id;//总包编号
        $main_model = Contractor::model()->findByPk($main_conid);
        $main_conid_name = $main_model->contractor_name;
        $start_date = $apply->start_date;
        $end_date = $apply->end_date;
        $days = Utils::days($start_date,$end_date);
        $start_time = $apply->start_time;
        $end_time = $apply->end_time;
        $logo_pic = '/opt/www-nginx/web'.$main_model->remark;

        $_SESSION['title'] = 'Permit-to-Work (PTW) No. (许可证申请编号):  ' . $apply->apply_id;
        $_SESSION['contractor_name'] = $main_conid_name;
        $_SESSION['contractor_id'] = $main_conid;
        $_SESSION['program_name'] = $program_name;
        $_SESSION['id'] = $id;

        $logo_pic = '/opt/www-nginx/web/filebase/company/146/shsd.png';

        $pdf->SetHeaderData($logo_pic, 20,  '',  '',array(0, 64, 255), array(0, 64, 128));

        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        $pdf->SetHeaderData('', 20,  '',  '',array(0, 64, 255), array(0, 64, 128));

        // 设置页眉和页脚字体
        $pdf->setHeaderFont(Array('droidsansfallback', '', '20')); //英文

        //$pdf->Header($logo_pic);
        $pdf->setFooterFont(Array('helvetica', '', '10'));
        $pdf->setCellPaddings(1,1,1,1);

        //设置默认等宽字体
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        //设置间距
        $pdf->SetMargins(15, 40, 15);
        //$pdf->SetHeaderMargin(35);
        $pdf->SetFooterMargin(10);

        //设置分页
        $pdf->SetAutoPageBreak(TRUE, 25);
        //set image scale factor
        $pdf->setImageScale(1.25);
        //set default font subsetting mode
        $pdf->setFontSubsetting(true);
        //设置字体
        $pdf->SetFont('times', '', 12, '', true); //英文
        $pdf->AddPage();

        $pdf->SetLineWidth(0.1);
        //审批步骤详情
        $progress_list = CheckApplyDetail::progressList( $app_id,$apply->apply_id,$year);//审批步骤详情
        $progress_cnt = count($progress_list);
        $background_path = 'https://shell.cmstech.sg/test/ctmgr/img/background.jpg';
        $err = file_exists($background_path);
        $checker_user_name = '';
        $checker_pic_html = '<img src="'.$background_path.'" height="30" width="30"  /> ';
        $checker_deal_time = '';
        $sc_approve_tag =0; //分包审批标识
        $sc_apply_tag = 0;//分包申请标识
        $ptwShsdList = ReportTemplate::ptwShsdList();

        $check_list = json_decode($check_list,true);
        if(count($check_list)>0){
            $check_id = $check_list[0]['check_id'];
            $check_list = RoutineCheck::detailList($check_id);//例行检查单
            $condition_set = json_decode($check_list[0]['condition_list'], true);
        }else{
            $condition_set = 'NULL';
        }

        $resultText = ReportShsdPdf::resultText();
        $condition_list = RoutineCondition::conditionList();
        //$data = $ptwShsdList['SHSD001'];
        $data = $ptwShsdList[$type_id];
        $_SESSION['type_id'] = $type_id;
        $bck_html= '<img src="'.$background_path.'" height="30" width="30"  />';

        $e = -2;
        $t = 0;
        if (!empty($region_list)){
            $secondary_str = "";
            foreach($region_list as $region => $secondary) {
                $secondary_str.=$region.' ';
            }
        }else{
            $secondary_str = '';
        }
        $pdf->SetFont('times', '', 9, '', true); //英文
        $section1_title = "<b>SECTION 1: TO BE COMPLETED BY PERMIT TO WORK APPLICANT</b>";
        $pdf->writeHTMLCell(0, 0, '15', '', $section1_title, 0, 1, 0, true, 'L', true);

        $duration_html = '<table style="border-width: 1px;border-color:gray gray gray gray">';
        $duration_html .= '<tr ><td colspan="3"><b>DURATION OF WORK</b><br></td></tr>';
        $duration_html .= '<tr><td height="20px">&nbsp;START DATE:&nbsp;' .Utils::DateToEn($start_date).'</td>';
        $duration_html .= '<td height="20px">&nbsp;END DATE: &nbsp;' .Utils::DateToEn($end_date).'</td>';
        $duration_html .= '<td height="20px">&nbsp;NO. OF DAY(S): &nbsp;' .$days.'</td></tr>';
        $duration_html .= '<tr><td height="20px">&nbsp;START TIME:&nbsp;' .$start_time.'</td>';
        $duration_html .= '<td height="20px">&nbsp;END TIME:&nbsp;' .$end_time.'</td><td></td></tr></table>';
        $pdf->writeHTML($duration_html, true, false, true, false, '');
        $pdf->Ln(4);
        $location_title = "<b>LOCATION OF WORK:</b> $secondary_str";
        $pdf->writeHTMLCell(0, 0, '15', '', $location_title, 0, 1, 0, true, 'L', true);
        $pdf->Ln(4);
        $type_title = "<b>TYPE OF WORK – TICK WHERE APPLICABLE.</b> ENSURE RELEVANT CHECKLISTS / DOCUMENTS ARE COMPLETED & SUBMITTED WITH THIS PTW APPLICATION.";
        $pdf->writeHTMLCell(0, 0, '15', '', $type_title, 0, 1, 0, true, 'L', true);
        $type_html = '<table style="border-width: 1px;border-color:gray gray gray gray">';
        $type_list = TypeList::typeText($main_conid);
        $type_key = 1;
        $pdf->SetFont('droidsansfallback', '', 9, '', true); //英文
        foreach ($type_list as $key => $type_name) {
            $type_tag = '';
            if($type_id == $key){
                $type_tag = $resultText[0];
            }
            //if($type_key <= 12){
                if($type_key%2 != 0){
                    $type_html.= '<tr><td width="5%" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $type_tag . '</td><td width="45%" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $type_name . '</td>';
                }else{
                    $type_html.= '<td width="5%" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $type_tag . '</td><td width="45%" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $type_name . '</td></tr>';
                }
            //}
            $type_key++;
        }
        if($type_key%2 == 0){
            $type_html.= '<td width="45%" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;</td></tr>';
        }
        //if($type_key%2 == 1){
        //  $type_html.= '<tr><td width="5%" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;</td><td width="45%" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Others: ______________________ (Please Specify)</td><td width="5%" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;</td><td width="45%" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;</td></tr>';
        //
        $type_html.="</table>";
        $pdf->writeHTML($type_html, true, false, true, false, '');
        $pdf->Ln(4);
        $pdf->SetFont('times', '', 9, '', true); //英文
        $content_title = 'DESCRIPTION OF WORK ACTIVITIY:<br>';
        $pdf->writeHTMLCell(0, 0, '15', '', $content_title, 0, 1, 0, true, 'L', true);
        $content = $apply->work_content;
        $pdf->writeHTMLCell(0, 0, '15', '', $content, 0, 1, 0, true, 'L', true);
        $pdf->Ln(4);
        $attach_title = 'ATTACHMENT:';
        $pdf->writeHTMLCell(0, 0, '15', '', $attach_title, 0, 1, 0, true, 'L', true);
        $doc_html = '<table style="border-width: 1px;border-color:gray gray gray gray">';
        $doc_html.= '<tr><td width="10%" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N</td><td width="90%" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Document Name</td></tr>';
        if(count($document_list)>0){
            foreach($document_list as $doc_id => $doc_name){
                $doc_id = $doc_id+1;
                $doc_html.= '<tr><td width="10%" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;'.$doc_id.'</td><td width="90%" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;'.$doc_name.'</td></tr>';
            }
        }else{
            $doc_html.= '<tr><td width="100%" colspan="2" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Nil</td></tr>';
        }
        $doc_html.= '</table>';
        $pdf->writeHTML($doc_html, true, false, true, false, '');
        //现场设备
        $device_title = 'DEVICE:';
        $pdf->writeHTMLCell(0, 0, '15', '', $device_title, 0, 1, 0, true, 'L', true);
        $primary_list = Device::primaryAllList();
        $device_type = DeviceType::deviceList();
        $devices_html = '<table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="10%" nowrap="nowrap"  align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N</td><td height="20px" width="30%" nowrap="nowrap"  align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Registration No.</td><td height="20px" width="30%" nowrap="nowrap"  align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Equipment Name</td><td height="20px" width="30%" nowrap="nowrap"  align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Equipment Type</td></tr>';
        if (!empty($device_list)){
            $j =1;
            foreach ($device_list as $id => $list) {
                $devicetype_model = DeviceType::model()->findByPk($list['type_no']);//设备类型信息
                $device_type_en = $devicetype_model->device_type_en;
                $devices_html .= '<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $j . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $primary_list[$id] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $list['device_name'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' .$device_type_en.'</td></tr>';
                $j++;
            }
        }else{
            $devices_html .= '<tr><td align="center" colspan="4" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Nil</td></tr>';
        }
        $devices_html .= '</table>';
        $pdf->writeHTML($devices_html, true, false, true, false, '');
        $pdf->Ln(4);
        $roleList = Role::roleList();
        $reject_tag = 0;
        //循环progress_list，num_1计算有多少条deal_type=1的记录
        $num_1=0;
        foreach ($progress_list as $key => $value) {
            if ($value['deal_type']=='1') {
                $num_1++;
            }
        }
        //如果有3条或更多申请审批记录，只留第一，二条
        if ($num_1>=3) {
            $num_2=0;
            foreach ($progress_list as $key => $value) {
                if ($value['deal_type'] =='1') {
                    if($num_2>=2){
                        unset($progress_list[$key]);
                    }
                    $num_2++;
                }
            }
        }
        $count_1=0;
        foreach ($progress_list as $key => $value) {
            if ($value['deal_type']=='1') {
                $count_1++;
            }
        }
        //如果有2条或更多申请批准记录，只留第一条
        if ($count_1>=2) {
            $count_2=0;
            foreach ($progress_list as $key => $value) {
                if ($value['deal_type'] =='2') {
                    if($count_2>=1){
                        unset($progress_list[$key]);
                    }
                    $count_2++;
                }
            }
        }
        $progress_list = array_merge($progress_list);
        $q=1;
        $sign = 0;
        for($i=0;$i<=6;$i++){
            //如果没有申请审批2，那第三条记录就是申请批准
            if ($q>3 && $sign == 'No') {
                $j = $progress_list[$i-1];
            }else{
                //如果有申请审批2，那第四条记录就是申请批准
                $j = $progress_list[$i];
            }
            $deal_model = Staff::model()->findByPk($j['deal_user_id']);
            $deal_contractor_id = $deal_model->contractor_id;
            $deal_user_name = $deal_model->user_name;
            $deal_role_name = $roleList[$deal_model->role_id];
            $deal_signature = $deal_model->signature_path;
            $deal_contractor_model = Contractor::model()->findByPk($deal_contractor_id);
            $deal_contractor_name = $deal_contractor_model->contractor_name;
            $deal_time = Utils::DateToEn($j['deal_time']);
            $deal_remark = $j['remark'];
            $date = substr($deal_time,0,11);
            $time = substr($deal_time,11,9);
            $pic_html= '<img src="'.$deal_signature.'" height="30" width="30"  /> ';
            if($j['deal_type'] == '6'){
                $reject_tag = 1;
                $reject_model = Staff::model()->findByPk($j['deal_user_id']);
                $reject_contractor_id = $reject_model->contractor_id;
                $reject_user_name = $reject_model->user_name;
                $reject_role_name = $roleList[$reject_model->role_id];
                $reject_signature = $reject_model->signature_path;
                $reject_contractor_model = Contractor::model()->findByPk($reject_contractor_id);
                $reject_contractor_name = $reject_contractor_model->contractor_name;
                $deal_time = Utils::DateToEn($j['deal_time']);
                $reject_deal_remark = $j['remark'];
                $reject_date = substr($deal_time,0,11);
                $reject_time = substr($deal_time,11,9);
                $reject_pic_html= '<img src="'.$deal_signature.'" height="30" width="30"  /> ';
            }
            //申请
            if($q == 1){
                if($j['deal_type'] == '0'){
                    $apply_html = '<table style="border-width: 1px;border-color:gray gray gray gray">';
                    $apply_html.= '<tr><td height="30px" width="100%">&nbsp;NAME OF PERMIT TO WORK APPLICANT: &nbsp;'.$deal_user_name.'</td></tr>';
                    $apply_html.= '<tr><td height="30px" width="65%">&nbsp;COMPANY: &nbsp;'.$deal_contractor_name.'</td><td height="30px" width="35%">&nbsp;DESIGNATION: &nbsp;'.$deal_role_name.'</td></tr>';
                    $apply_html.= '<tr><td width="100%">&nbsp;I fully understand the nature of the work and safety conditions that must be met. I have inspected the safety conditions relating to the work to be performed. I shall ensure that the safety conditions required is met throughout the duration of this permit.</td></tr>';
                    $apply_html.= '<tr><td height="30px" width="33%">&nbsp;DATE: &nbsp;'.$date.$bck_html.'</td><td height="30px" width="34%">&nbsp;TIME: &nbsp;'.$time.$bck_html.'</td><td height="30px" width="33%">&nbsp;SIGNATURE: &nbsp;'.$pic_html.'</td></tr>';
                    $apply_html.= '</table>';
                    $pdf->writeHTML($apply_html, true, false, true, false, '');
                }else{
                    $apply_html = '<table style="border-width: 1px;border-color:gray gray gray gray">';
                    $apply_html.= '<tr><td height="30px" width="100%">&nbsp;NAME OF PERMIT TO WORK APPLICANT: &nbsp;</td></tr>';
                    $apply_html.= '<tr><td height="30px" width="65%">&nbsp;COMPANY: &nbsp;</td><td height="30px" width="35%">&nbsp;DESIGNATION: &nbsp;</td></tr>';
                    $apply_html.= '<tr><td width="100%">&nbsp;I fully understand the nature of the work and safety conditions that must be met. I have inspected the safety conditions relating to the work to be performed. I shall ensure that the safety conditions required is met throughout the duration of this permit.</td></tr>';
                    $apply_html.= '<tr><td height="30px" width="33%">&nbsp;DATE: &nbsp;</td><td height="30px" width="34%">&nbsp;TIME: &nbsp;</td><td height="30px" width="33%">&nbsp;SIGNATURE: &nbsp;</td></tr>';
                    $apply_html.= '</table>';
                    $pdf->writeHTML($apply_html, true, false, true, false, '');
                }
                $pdf->SetFont('droidsansfallback', '', 9, '', true); //英文
                //安全条件
                if($apply->condition_set != '{}' && $apply->condition_set != '[]') {
                    $condition_html = '<h2 align="center">Safety Conditions (安全条件)</h2><table style="border-width: 1px;border-color:gray gray gray gray"><tr><td height="20px" width="10%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N(序号)</td><td height="20px" width="75%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Safety Conditions (安全条件)</td><td height="20px" width="15%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">YES / NO / N/A</td></tr>';
                    $condition_set = json_decode($apply->condition_set, true);
                    $resultText = ApplyBasic::resultText();
                    $condition_list = PtwCondition::conditionList();

                    if (array_key_exists('name', $condition_set[0])) {
                        foreach ($condition_set as $key => $row) {
                            $condition_name = '';
                            $condition_name = $row['name'] . '<br>' . $row['name_en'];
                            $condition_html .= '<tr><td  align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . ($key + 1) . '</td><td>&nbsp;' . $condition_name . '</td><td  align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $resultText[$row['check']] . '</td></tr>';
                        }
                    }
                    if (array_key_exists('id', $condition_set[0])) {
                        foreach ($condition_set as $key => $row) {
                            $condition_name = '';
                            $name = $condition_list[$row['id']]['condition_name'];
                            $name_en = $condition_list[$row['id']]['condition_name_en'];
                            $condition_name = $name . '<br>' . $name_en;
                            //$condition_name = $row['name'].'<br>'.$row['name_en'];
                            $condition_html .= '<tr><td  align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . ($key + 1) . '</td><td  style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $condition_name . '</td><td  align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $resultText[$row['check']] . '</td></tr>';
                        }
                    }
                    $condition_html .= '</table>';
                    $pdf->writeHTML($condition_html, true, false, true, false, '');
                }
            }
            $pdf->SetFont('times', '', 9, '', true);
            //审批1
            if($q == 2){
                if($j['deal_type'] == '1'){
                    $section2_title = "<b>SECTION 2: TO BE ASSESSED & ENDORSED BY PERMIT TO WORK</b>";
                    $pdf->writeHTMLCell(0, 0, '15', '', $section2_title, 0, 1, 0, true, 'L', true);
                    $asessor_html = '<table style="border-width: 1px;border-color:gray gray gray gray">';
                    $asessor_html.= '<tr><td height="30px" width="100%">&nbsp;NAME OF PERMIT TO WORK ASESSOR: &nbsp;'.$deal_user_name.'</td></tr>';
                    $asessor_html.= '<tr><td height="30px" width="65%">&nbsp;COMPANY: &nbsp;'.$deal_contractor_name.'</td><td height="30px" width="35%">&nbsp;DESIGNATION: &nbsp;'.$deal_role_name.'</td></tr>';
                    $asessor_html.= '<tr><td width="100%">&nbsp;I have inspected & assessed the intended work operation & its work area together with the PTW applicant & verified that the above safety and health control measures have  been implemented by the PTW applicant and am satisfied that it is SAFE for the specified work to commence.</td></tr>';
                    $asessor_html.= '<tr><td height="30px" width="33%">&nbsp;DATE: &nbsp;'.$date.$bck_html.'</td><td height="30px" width="34%">&nbsp;TIME: &nbsp;'.$time.$bck_html.'</td><td height="30px" width="33%">&nbsp;SIGNATURE: &nbsp;'.$pic_html.'</td></tr>';
                    $asessor_html.= '</table>';
                    $pdf->writeHTML($asessor_html, true, false, true, false, '');
                }else{
                    $section2_title = "<b>SECTION 2: TO BE ASSESSED & ENDORSED BY PERMIT TO WORK</b>";
                    $pdf->writeHTMLCell(0, 0, '15', '', $section2_title, 0, 1, 0, true, 'L', true);
                    $asessor_html = '<table style="border-width: 1px;border-color:gray gray gray gray">';
                    $asessor_html.= '<tr><td height="30px" width="100%">&nbsp;NAME OF PERMIT TO WORK ASESSOR: &nbsp;</td></tr>';
                    $asessor_html.= '<tr><td height="30px" width="65%">&nbsp;COMPANY: &nbsp;</td><td height="30px" width="35%">&nbsp;DESIGNATION: &nbsp;</td></tr>';
                    $asessor_html.= '<tr><td width="100%">&nbsp;I have inspected & assessed the intended work operation & its work area together with the PTW applicant & verified that the above safety and health control measures have  been implemented by the PTW applicant and am satisfied that it is SAFE for the specified work to commence.</td></tr>';
                    $asessor_html.= '<tr><td height="30px" width="33%">&nbsp;DATE: &nbsp;</td><td height="30px" width="34%">&nbsp;TIME: &nbsp;</td><td height="30px" width="33%">&nbsp;SIGNATURE: &nbsp;</td></tr>';
                    $asessor_html.= '</table>';
                    $pdf->writeHTML($asessor_html, true, false, true, false, '');
                }
            }
            //第三条记录可能是申请审批2=>deal_type=1，也可能是申请批准=>deal_type=2
            //申请审批2
            if($q == 3){
                if($j['deal_type'] == '1'){
                    $sign = 'Yes';//有申请审批2
                    $section3_title = "<b>SECTION 3: TO BE VERIFIED BY HDKH SITE ENGINEER / SUPERVISOR IN CHARGE</b>";
                    $pdf->writeHTMLCell(0, 0, '15', '', $section3_title, 0, 1, 0, true, 'L', true);
                    $supervisor_html = '<table style="border-width: 1px;border-color:gray gray gray gray">';
                    $supervisor_html.= '<tr><td height="30px" width="100%">&nbsp;NAME OF ENGINEER / SUPERVISOR: &nbsp;'.$deal_user_name.'</td></tr>';
                    $supervisor_html.= '<tr><td height="30px" width="65%">&nbsp;COMPANY: &nbsp;'.$deal_contractor_name.'</td><td height="30px" width="35%">&nbsp;DESIGNATION: &nbsp;'.$deal_role_name.'</td></tr>';
                    $supervisor_html.= '<tr><td width="100%">&nbsp;I have verified intended work operation & its work with this PTW application and its’ relevant documents, designs and plan and am satisfied that the specified work can be carried out.</td></tr>';
                    $supervisor_html.= '<tr><td height="30px" width="33%">&nbsp;DATE: &nbsp;'.$date.$bck_html.'</td><td height="30px" width="34%">&nbsp;TIME: &nbsp;'.$time.$bck_html.'</td><td height="30px" width="33%">&nbsp;SIGNATURE: &nbsp;'.$pic_html.'</td></tr>';
                    $supervisor_html.= '</table>';
                    $pdf->writeHTML($supervisor_html, true, false, true, false, '');
                }else{
                    $sign='No';//没有申请审批2
                    $section3_title = "<b>SECTION 3: TO BE VERIFIED BY HDKH SITE ENGINEER / SUPERVISOR IN CHARGE</b>";
                    $pdf->writeHTMLCell(0, 0, '15', '', $section3_title, 0, 1, 0, true, 'L', true);
                    $supervisor_html = '<table style="border-width: 1px;border-color:gray gray gray gray">';
                    $supervisor_html.= '<tr><td height="30px" width="100%">&nbsp;NAME OF ENGINEER / SUPERVISOR: &nbsp;</td></tr>';
                    $supervisor_html.= '<tr><td height="30px" width="65%">&nbsp;COMPANY: &nbsp;</td><td height="30px" width="35%">&nbsp;DESIGNATION: &nbsp;</td></tr>';
                    $supervisor_html.= '<tr><td width="100%">&nbsp;I have verified intended work operation & its work with this PTW application and its’ relevant documents, designs and plan and am satisfied that the specified work can be carried out.</td></tr>';
                    $supervisor_html.= '<tr><td height="30px" width="33%">&nbsp;DATE: &nbsp;</td><td height="30px" width="34%">&nbsp;TIME: &nbsp;</td><td height="30px" width="33%">&nbsp;SIGNATURE: &nbsp;</td></tr>';
                    $supervisor_html.= '</table>';
                    $pdf->writeHTML($supervisor_html, true, false, true, false, '');
                }

            }
            //申请批准
            if($q == 4){
                if($j['deal_type'] == '2'){
                    $section4_title = "<b>SECTION 4: TO BE APPROVED BY AUTHORISED MANAGER</b>";
                    $pdf->writeHTMLCell(0, 0, '15', '', $section4_title, 0, 1, 0, true, 'L', true);
                    $manager_html = '<table style="border-width: 1px;border-color:gray gray gray gray">';
                    $manager_html.= '<tr><td height="30px" width="100%">&nbsp;NAME OF AUTHORISED MANAGER:  &nbsp;'.$deal_user_name.'</td></tr>';
                    $manager_html.= '<tr><td height="30px" width="65%">&nbsp;COMPANY: &nbsp;'.$deal_contractor_name.'</td><td height="30px" width="35%">&nbsp;DESIGNATION: &nbsp;'.$deal_role_name.'</td></tr>';
                    $manager_html.= '<tr><td width="100%">&nbsp;I have evaluated this PTW application and am satisfied that a proper evaluation of the risks and hazards involved in carrying out this work activity has been conducted; no incompatible work will be carried out at the same time and same vicinity and all reasonably practicable measures have been taken and all persons who are to carry out this high risk construction work have been informed of the hazards associated with it and the mitigating safety and health measures, and hereby APPROVE this PTW application.</td></tr>';
                    $manager_html.= '<tr><td height="30px" width="33%">&nbsp;DATE: &nbsp;'.$date.$bck_html.'</td><td height="30px" width="34%">&nbsp;TIME: &nbsp;'.$time.$bck_html.'</td><td height="30px" width="33%">&nbsp;SIGNATURE: &nbsp;'.$pic_html.'</td></tr>';
                    $manager_html.= '</table>';
                    $pdf->writeHTML($manager_html, true, false, true, false, '');
                }else{
                    $section4_title = "<b>SECTION 4: TO BE APPROVED BY AUTHORISED MANAGER</b>";
                    $pdf->writeHTMLCell(0, 0, '15', '', $section4_title, 0, 1, 0, true, 'L', true);
                    $manager_html = '<table style="border-width: 1px;border-color:gray gray gray gray">';
                    $manager_html.= '<tr><td height="30px" width="100%">&nbsp;NAME OF AUTHORISED MANAGER:  &nbsp;</td></tr>';
                    $manager_html.= '<tr><td height="30px" width="65%">&nbsp;COMPANY: &nbsp;</td><td height="30px" width="35%">&nbsp;DESIGNATION: &nbsp;</td></tr>';
                    $manager_html.= '<tr><td width="100%">&nbsp;I have evaluated this PTW application and am satisfied that a proper evaluation of the risks and hazards involved in carrying out this work activity has been conducted; no incompatible work will be carried out at the same time and same vicinity and all reasonably practicable measures have been taken and all persons who are to carry out this high risk construction work have been informed of the hazards associated with it and the mitigating safety and health measures, and hereby APPROVE this PTW application.</td></tr>';
                    $manager_html.= '<tr><td height="30px" width="33%">&nbsp;DATE: &nbsp;</td><td height="30px" width="34%">&nbsp;TIME: &nbsp;</td><td height="30px" width="33%">&nbsp;SIGNATURE: &nbsp;</td></tr>';
                    $manager_html.= '</table>';
                    $pdf->writeHTML($manager_html, true, false, true, false, '');
                }
            }
            //确认
            if($q == 5){
                if($j['deal_type'] == '10'){
                    $section5_title = "<b>SECTION 5: TO BE ACKNOWLEDGED BY HDKH HSE PERSONNEL</b>";
                    $pdf->writeHTMLCell(0, 0, '15', '', $section5_title, 0, 1, 0, true, 'L', true);
                    $personnel_html = '<table style="border-width: 1px;border-color:gray gray gray gray">';
                    $personnel_html.= '<tr><td height="30px" width="100%">&nbsp;NAME OF HSE PERSONNEL:   &nbsp;'.$deal_user_name.'</td></tr>';
                    $personnel_html.= '<tr><td height="30px" width="65%">&nbsp;COMPANY: &nbsp;'.$deal_contractor_name.'</td><td height="30px" width="35%">&nbsp;DESIGNATION: &nbsp;'.$deal_role_name.'</td></tr>';
                    $personnel_html.= '<tr><td height="30px" width="100%">&nbsp;COMMENTS:   &nbsp;'.$deal_remark.'</td></tr>';
                    $personnel_html.= '<tr><td width="100%">&nbsp;I have verified the appointments and competency of the PTW applicants, assessors and approval manager as well as the PTW application and its’ relevant documents, designs and plan.</td></tr>';
                    $personnel_html.= '<tr><td height="30px" width="33%">&nbsp;DATE: &nbsp;'.$date.$bck_html.'</td><td height="30px" width="34%">&nbsp;TIME: &nbsp;'.$time.$bck_html.'</td><td height="30px" width="33%">&nbsp;SIGNATURE: &nbsp;'.$pic_html.'</td></tr>';
                    $personnel_html.= '</table>';
                    $pdf->writeHTML($personnel_html, true, false, true, false, '');
                }else{
                    $section5_title = "<b>SECTION 5: TO BE ACKNOWLEDGED BY HDKH HSE PERSONNEL</b>";
                    $pdf->writeHTMLCell(0, 0, '15', '', $section5_title, 0, 1, 0, true, 'L', true);
                    $personnel_html = '<table style="border-width: 1px;border-color:gray gray gray gray">';
                    $personnel_html.= '<tr><td height="30px" width="100%">&nbsp;NAME OF HSE PERSONNEL:   &nbsp;</td></tr>';
                    $personnel_html.= '<tr><td height="30px" width="65%">&nbsp;COMPANY: &nbsp;</td><td height="30px" width="35%">&nbsp;DESIGNATION: &nbsp;</td></tr>';
                    $personnel_html.= '<tr><td height="30px" width="100%">&nbsp;COMMENTS:   &nbsp;</td></tr>';
                    $personnel_html.= '<tr><td width="100%">&nbsp;I have verified the appointments and competency of the PTW applicants, assessors and approval manager as well as the PTW application and its’ relevant documents, designs and plan.</td></tr>';
                    $personnel_html.= '<tr><td height="30px" width="33%">&nbsp;DATE: &nbsp;</td><td height="30px" width="34%">&nbsp;TIME: &nbsp;</td><td height="30px" width="33%">&nbsp;SIGNATURE: &nbsp;</td></tr>';
                    $personnel_html.= '</table>';
                    $pdf->writeHTML($personnel_html, true, false, true, false, '');
                }
            }
            //关闭
            if($q == 6){
                if($j['deal_type'] == '3'){
                    // var_dump($deal_user_name)
                    $section6_title = "<b>SECTION 6: NOTIFICATION OF WORK COMPLETION</b>";
                    $pdf->writeHTMLCell(0, 0, '15', '', $section6_title, 0, 1, 0, true, 'L', true);
                    $applicant_html = '<table style="border-width: 1px;border-color:gray gray gray gray">';
                    $applicant_html.= '<tr><td height="30px" width="100%">&nbsp;NAME OF APPLICANT:   &nbsp;'.$deal_user_name.'</td></tr>';
                    $applicant_html.= '<tr><td height="30px" width="65%">&nbsp;COMPANY: &nbsp;'.$deal_contractor_name.'</td><td height="30px" width="35%">&nbsp;DESIGNATION: &nbsp;'.$deal_role_name.'</td></tr>';
                    $applicant_html.= '<tr><td height="30px" width="100%">&nbsp;DATE & TIME COMPLETED:    &nbsp;</td></tr>';
                    $applicant_html.= '<tr><td width="100%">&nbsp;The above work has been completed.</td></tr>';
                    $applicant_html.= '<tr><td height="30px" width="33%">&nbsp;DATE: &nbsp;'.$date.$bck_html.'</td><td height="30px" width="34%">&nbsp;TIME: &nbsp;'.$time.$bck_html.'</td><td height="30px" width="33%">&nbsp;SIGNATURE: &nbsp;'.$pic_html.'</td></tr>';
                    $applicant_html.= '</table>';
                    $pdf->writeHTML($applicant_html, true, false, true, false, '');
                }else{
                    $section6_title = "<b>SECTION 6: NOTIFICATION OF WORK COMPLETION</b>";
                    $pdf->writeHTMLCell(0, 0, '15', '', $section6_title, 0, 1, 0, true, 'L', true);
                    $applicant_html = '<table style="border-width: 1px;border-color:gray gray gray gray">';
                    $applicant_html.= '<tr><td height="30px" width="100%">&nbsp;NAME OF APPLICANT:   &nbsp;</td></tr>';
                    $applicant_html.= '<tr><td height="30px" width="65%">&nbsp;COMPANY: &nbsp;</td><td height="30px" width="35%">&nbsp;DESIGNATION: &nbsp;</td></tr>';
                    $applicant_html.= '<tr><td height="30px" width="100%">&nbsp;DATE & TIME COMPLETED:    &nbsp;</td></tr>';
                    $applicant_html.= '<tr><td width="100%">&nbsp;The above work has been completed.</td></tr>';
                    $applicant_html.= '<tr><td height="30px" width="33%">&nbsp;DATE: &nbsp;</td><td height="30px" width="34%">&nbsp;TIME: &nbsp;</td><td height="30px" width="33%">&nbsp;SIGNATURE: &nbsp;</td></tr>';
                    $applicant_html.= '</table>';
                    $pdf->writeHTML($applicant_html, true, false, true, false, '');
                }
            }
            //驳回
            if($q == 7){
                if($reject_tag == 1){
                    $section7_title = "<b>SECTION 7: REVOCATION OF PERMIT TO WORKS (BY HDKV PERSONNEL)  </b>";
                    $pdf->writeHTMLCell(0, 0, '15', '', $section7_title, 0, 1, 0, true, 'L', true);
                    $revocat_html = '<table style="border-width: 1px;border-color:gray gray gray gray">';
                    $revocat_html.= '<tr><td height="30px" width="100%">&nbsp;NAME:   &nbsp;'.$reject_user_name.'</td></tr>';
                    $revocat_html.= '<tr><td height="30px" width="65%">&nbsp;COMPANY: &nbsp;'.$reject_contractor_name.'</td><td height="30px" width="35%">&nbsp;DESIGNATION: &nbsp;'.$reject_role_name.'</td></tr>';
                    $revocat_html.= '<tr><td height="30px" width="33%">&nbsp;DATE: &nbsp;'.$reject_date.$bck_html.'</td><td height="30px" width="34%">&nbsp;TIME: &nbsp;'.$reject_time.$bck_html.'</td><td height="30px" width="33%">&nbsp;SIGNATURE: &nbsp;'.$reject_pic_html.'</td></tr>';
                    $revocat_html.= '<tr><td width="100%">&nbsp;1)**All persons and equipment have been removed from the works area and the task has been suspended.</td></tr>';
                    $revocat_html.= '<tr><td width="100%">&nbsp;2)**The following observation of compliance is noted for attention prior to undertaking further work.</td></tr>';
                    $revocat_html.= '<tr><td height="30px" width="100%">&nbsp;Comment:   &nbsp;'.$reject_deal_remark.'</td></tr>';
                    $revocat_html.= '</table>';
                    $pdf->writeHTML($revocat_html, true, false, true, false, '');
                }else{
                    $section7_title = "<b>SECTION 7: REVOCATION OF PERMIT TO WORKS (BY HDKV PERSONNEL)  </b>";
                    $pdf->writeHTMLCell(0, 0, '15', '', $section7_title, 0, 1, 0, true, 'L', true);
                    $revocat_html = '<table style="border-width: 1px;border-color:gray gray gray gray">';
                    $revocat_html.= '<tr><td height="30px" width="100%">&nbsp;NAME:   &nbsp;</td></tr>';
                    $revocat_html.= '<tr><td height="30px" width="65%">&nbsp;COMPANY: &nbsp;</td><td height="30px" width="35%">&nbsp;DESIGNATION: &nbsp;</td></tr>';
                    $revocat_html.= '<tr><td height="30px" width="33%">&nbsp;DATE: &nbsp;</td><td height="30px" width="34%">&nbsp;TIME: &nbsp;</td><td height="30px" width="33%">&nbsp;SIGNATURE: &nbsp;</td></tr>';
                    $revocat_html.= '<tr><td width="100%">&nbsp;1)**All persons and equipment have been removed from the works area and the task has been suspended.</td></tr>';
                    $revocat_html.= '<tr><td width="100%">&nbsp;2)**The following observation of compliance is noted for attention prior to undertaking further work.</td></tr>';
                    $revocat_html.= '<tr><td height="30px" width="100%">&nbsp;Comment:   &nbsp;</td></tr>';
                    $revocat_html.= '</table>';
                    $pdf->writeHTML($revocat_html, true, false, true, false, '');
                }
            }
            $q++;
        }
        //输出PDF
        if($params['tag'] == 0){
            $pdf->Output($filepath, 'I');  //保存到指定目录
        }else if($params['tag'] == 1){
            $pdf->Output($filepath, 'I');  //保存到指定目录
        }

        return $filepath;
//============================================================+
// END OF FILE
//============================================================+
    }

    //下载HDBPDF
    public static function downloadZjnyPDF($params,$app_id){

        $id = $params['id'];
        $apply = ApplyBasic::model()->findByPk($id);//许可证基本信息表
        $device_list = ApplyDevice::getDeviceList($id);//许可证申请设备表
        $worker_list = ApplyWorker::getWorkerList($apply->apply_id);//许可证申请人员表
        $company_list = Contractor::compAllList();//承包商公司列表
        $document_list = PtwDocument::queryDocument($id);//文档列表
        $roleList = Role::roleallList();//岗位列表
        //$program_list =  Program::programAllList();//获取承包商所有项目
        //$ptw_type = ApplyBasic::typeList();//许可证类型表
        $ptw_type = ApplyBasic::typelanguageList();//许可证类型表(双语)
        $type_id = $apply->type_id;//许可证类型编号
        $program_id = $apply->program_id;
        //$programdetail_list = Program::getProgramDetail($program_id);
        //根据项目id得到总包商和根节点项目
        $region_list = PtwApplyBlock::regionList($id);//PTW项目区域

        $lang = "_en";
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($apply->record_time,0,4);//年
        $month = substr($apply->record_time,5,2);//月
        $day = substr($apply->record_time,8,2);//日
        $hours = substr($apply->record_time,11,2);//小时
        $minute = substr($apply->record_time,14,2);//分钟
        $time = $day.$month.$year.$hours.$minute;
        //报告路径存入数据库

        //$filepath = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/ptw'.'/PTW' . $id . '.pdf';
        $filepath = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/'.$program_id.'/ptw/'.$apply->apply_contractor_id.'/PTW' . $id . $time .'.pdf';
        ApplyBasic::updatepath($id,$filepath);

        //$full_dir = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/ptw';
        $full_dir = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/'.$program_id.'/ptw/'.$apply->apply_contractor_id;
        if(!file_exists($full_dir))
        {
            umask(0000);
            @mkdir($full_dir, 0777, true);
        }
        //$filepath = '/opt/www-nginx/web/test/ctmgr/attachment' . '/PTW' . $id . $lang . '.pdf';
        $title = $apply->program_name;

        //if (file_exists($filepath)) {
        //$show_name = $title;
        //$extend = 'pdf';
        //Utils::Download($filepath, $show_name, $extend);
        //return;
        //}
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
        $pro_model = Program::model()->findByPk($program_id);
        $pro_params = $pro_model->params;//项目参数
        if($pro_params != '0'){
            $pro_params = json_decode($pro_params,true);
            if (array_key_exists('ptw_mode', $pro_params)) {
                $ptw_mode = $pro_params['ptw_mode'];
            } else {
                $ptw_mode = 'A';
            }
            //判断是否是迁移的
            if(array_key_exists('transfer_con',$pro_params)){
                $main_conid = $pro_params['transfer_con'];
            }else{
                $main_conid = $pro_model->contractor_id;//总包编号
            }
        }else{
            $ptw_mode = 'A';
            $main_conid = $pro_model->contractor_id;//总包编号
        }
        $status_css = CheckApplyDetail::hdbReportStatus($ptw_mode);//PTW执行类型(成功)
        $reject_css = CheckApplyDetail::rejectTxt();//PTW执行类型(拒绝)
        $main_model = Contractor::model()->findByPk($main_conid);
        $main_conid_name = $main_model->contractor_name;
        $logo_pic = '/opt/www-nginx/web'.$main_model->remark;

        $_SESSION['title'] = 'Permit-to-Work (PTW) No. (许可证申请编号):  ' . $apply->apply_id;

        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        // 设置页眉和页脚字体
        $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //英文

        $pdf->Header($logo_pic);
        $pdf->setFooterFont(Array('helvetica', '', '10'));
        $pdf->setCellPaddings(1,1,1,1);

        //设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');

        //设置间距
        $pdf->SetMargins(15, 23, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        //设置分页
        $pdf->SetAutoPageBreak(TRUE, 25);
        //set image scale factor
        $pdf->setImageScale(1.25);
        //set default font subsetting mode
        $pdf->setFontSubsetting(true);
        //设置字体
        $pdf->SetFont('droidsansfallback', '', 8, '', true); //英文

        $pdf->AddPage();
        $pdf->SetLineWidth(0.1);
        $operator_id = $apply->apply_user_id;//申请人ID
        $add_operator = Staff::model()->findByPk($operator_id);//申请人信息
        $add_role = $add_operator->role_id;
        $roleList = Role::roleallList();//岗位列表
        $apply_first_time = Utils::DateToEn(substr($apply->record_time,0,10));
        $apply_second_time = substr($apply->record_time,11,18);
        //$path = '/opt/www-nginx/appupload/4/0000002314_TBMMEETINGPHOTO.jpg';
        //标题(许可证类型+项目)
        $title_html = "<h1 style=\"font-size: 300%\" align=\"center\">{$main_conid_name}</h1><br/><h2  style=\"font-size: 200%\" align=\"center\">Project (项目) : {$apply->program_name}</h2>
            <h2 style=\"font-size: 200%\" align=\"center\">PTW Type (许可证类型): {$ptw_type[$type_id]['type_name_en']} ({$ptw_type[$type_id]['type_name']})</h2><br/>";
        $html =$title_html;
        $pdf->writeHTML($html, true, false, true, false, '');
        $apply_y = $pdf->GetY();
        //申请人资料
        $apply_info_html = "<h2 align=\"center\"> Applicant Details (申请人详情)</h2><table style=\"border-width: 1px;border-color:gray gray gray gray\">";
        $apply_info_html .="<tr><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Name(姓名)</td><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Designation(职位)</td><td height=\"20px\" width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">ID Number (身份证号码)</td></tr>";
        if($add_operator->work_pass_type = 'IC' || $add_operator->work_pass_type = 'PR'){
            if(substr($add_operator->work_no,0,1) == 'S' && strlen($add_operator->work_no) == 9){
                $work_no = 'SXXXX'.substr($add_operator->work_no,5,8);
            }else{
                $work_no = $add_operator->work_no;
            }
        }else{
            $work_no = $add_operator->work_no;
        }
        $apply_info_html .="<tr><td height=\"50px\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$add_operator->user_name}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$roleList[$add_role]}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$work_no}</td></tr>";
        $apply_info_html .="<tr><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Company (公司)</td><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Date of Application (申请时间)</td><td height=\"20px\" width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Electronic Signature (电子签名)</td></tr>";
        $apply_info_html .="<tr><td height=\"50px\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$company_list[$apply->apply_contractor_id]}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$apply_first_time}  {$apply_second_time}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">&nbsp;</td></tr>";
        $apply_info_html .="</table>";
        //判断电子签名是否存在 $add_operator->signature_path
//        $content = '/opt/www-nginx/web/filebase/record/2018/04/sign/pic/sign1523249465052_1.jpg';
        $content = $add_operator->signature_path;
        if($content){
            $pdf->Image($content, 150, $apply_y+40, 20, 9, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
        }
        //工作内容
        $work_content_html = "<h2 align=\"center\">Nature of Work (工作详情)</h2><table style=\"border-width: 1px;border-color:gray gray gray gray\" >";
        $work_content_html .="<tr><td height=\"20px\" width=\"100%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Title (标题)</td></tr>";
        $work_content_html .="<tr><td height=\"40px\" width=\"100%\" nowrap=\"nowrap\"  style=\"border-width: 1px;border-color:gray gray gray gray\"><br/>{$apply->title}</td></tr>";
        $work_content_html .="<tr><td height=\"20px\" width=\"100%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Description (描述)</td></tr>";
        $work_content_html .="<tr><td height=\"215px\" width=\"100%\" nowrap=\"nowrap\"  style=\"border-width: 1px;border-color:gray gray gray gray\">{$apply->work_content}</td></tr>";
        $work_content_html .="<tr><td height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Start Time (开始时间)</td><td height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">End Time (结束时间)</td></tr>";
        $work_content_html .="<tr><td height=\"50px\" width=\"50%\" nowrap=\"nowrap\"  align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>{$apply->start_time}</td><td height=\"50px\" width=\"50%\" nowrap=\"nowrap\"  align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>{$apply->end_time}</td></tr></table>";


        $progress_list = CheckApplyDetail::progressList( $app_id,$apply->apply_id,$year);//审批步骤详情

        $html_1 = $apply_info_html . $work_content_html;

        $pdf->writeHTML($html_1, true, false, true, false, '');

        //现场照片
        $pdf->AddPage();
        $pic_title_html = '<h2 align="center">Site Photo(s) (现场照片)</h2>';
        $pdf->writeHTML($pic_title_html, true, false, true, false, '');
        $y2= $pdf->GetY();
        //判断每一页图片边框的高度
        $total_height = array();
        if (!empty($progress_list)){
            foreach ($progress_list as $key => $row) {
                if($row['pic'] != '') {
                    $pic = explode('|', $row['pic']);
                    if($y2 > 266){
                        $y2 = 30;
                    }
                    $info_x = 15+3;
                    $info_y = $y2;
                    $toatl_width  =0;
                    $title_height =48+3;
                    $cnt = 0;
                    foreach ($pic as $key => $content) {
                        $content = $pic[0];
                        if($content != '' && $content != 'nil' && $content != '-1') {
                            if(file_exists($content)) {
                                $ratio_width = 55;
                                //超过固定宽度换行
                                if($toatl_width > 190){
                                    if($info_y < 220){
                                        $toatl_width = 0;
                                        $info_x = 15+3;
                                        $info_y+=45+3;
                                        $title_height+=45+3;
                                    }
                                }
                                //超过纵坐标换新页
                                if($info_y >= 220){
                                    $total_height[$cnt] = $title_height-43;
                                    $info_y = 10;
                                    $info_x = 15+3;
                                    $toatl_width = 0;
                                    $title_height = 45+10;
                                    $cnt++;
                                }else{
                                    $total_height[$cnt] = $title_height;
                                }
                                //一行中按间距排列图片
                                $info_x += $ratio_width+3;
                                if($toatl_width == 0){
                                    $toatl_width = $ratio_width;
                                }
                                $toatl_width+=$ratio_width+3;
                            }
                        }
                    }
                }
            }
        }
        $table_count = count($total_height);
        $table_height = 3.5*$total_height[0];
        $pdf->Ln(2);
        if($table_count>1){
            $pic_html = '<table style="border-width: 1px;border-color:lightslategray lightslategray white lightslategray">
                <tr><td width ="100%" height="'.$table_height.'"></td></tr>';
            $pic_html .= '</table>';
        }else{
            $pic_html = '<table style="border-width: 1px;border-color:lightslategray lightslategray lightslategray lightslategray">
                <tr><td width ="100%" height="840px"></td></tr>';
            $pic_html .= '</table>';
        }
        $y2= $pdf->GetY();
        $pdf->writeHTML($pic_html, true, false, true, false, '');

        if (!empty($progress_list)){
            foreach ($progress_list as $key => $row) {
                if($row['pic'] != '') {
                    $pic = explode('|', $row['pic']);
//                    var_dump($pic);
//                    exit;
                    //for($o=0;$o<=8;$o++){
                    //    $pic[$o] =  "/opt/www-nginx/web/filebase/record/2019/02/tbm/pic/tbm_1550054744258_1.jpg";
                    //}
                    if($y2 > 266){
                        $y2 = 23;
                    }
                    $info_x = 15+3;
                    $info_y = $y2;
                    $toatl_width  =0;
                    $j = 1;
                    foreach ($pic as $key => $content) {
//                        $content = $pic[0];
                        if($content != '' && $content != 'nil' && $content != '-1') {
                            if(file_exists($content)) {
//                                $img_array = explode('/',$content);
//                                $index = count($img_array) -1;
//                                $img_array[$index] = 'middle_'.$img_array[$index];
//                                $thumb_img = implode('/',$img_array);
//                                //压缩业务图片  middle开头
//                                $stat = Utils::img2thumb($content, $thumb_img, $width = 0, $height = 200, $cut = 0, $proportion = 0);
//                                if($stat){
//                                    $img = $thumb_img;
//                                }else{
//                                    $img = $content;
//                                }
                                $img = $content;
                                $ratio_width = 55;
                                //超过固定宽度换行
                                if($toatl_width > 190){
                                    $toatl_width = 0;
                                    $info_x = 15+3;
                                    $info_y+=45+3;
                                }
                                //超过纵坐标换新页
                                if($info_y >= 220 ){
                                    $j++;
                                    $pdf->AddPage();
                                    $pdf->setPrintHeader(false);
                                    $info_y = $pdf->GetY();
                                    $table_height = 3.5*$total_height[$j-1];
                                    if($table_count == $j){
                                        $pic_html = '<br/><table style="border-width: 1px;border-color:lightslategray lightslategray lightslategray lightslategray">
                <tr><td width ="100%" height="'.$table_height.'"></td></tr>';
                                        $pic_html .= '</table>';
                                    }else{
                                        $pic_html = '<br/><table style="border-width: 1px;border-color:lightslategray lightslategray white lightslategray">
                <tr><td width ="100%" height="'.$table_height.'"></td></tr>';
                                        $pic_html .= '</table>';
                                    }
                                    $pdf->writeHTML($pic_html, true, false, true, false, '');
                                    $info_x = 15+3;
                                    $toatl_width = 0;
                                }
                                $file_type = Utils::getReailFileType($img);
                                if($file_type == 'png'){
                                    $pdf->Image($img, $info_x, $info_y, '55', '45', 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);
                                }else if($file_type == 'jpg'){
                                    $pdf->Image($img, $info_x, $info_y, '55', '45', 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                                }
                                //一行中按间距排列图片
                                $info_x += $ratio_width+3;
                                if($toatl_width == 0){
                                    $toatl_width = $ratio_width;
                                }
                                $toatl_width+=$ratio_width+3;
                            }
                        }
                    }
                }
            }
        }

        //安全条件
        $condition_html = '<h2 align="center">Safety Conditions (安全条件)</h2><table style="border-width: 1px;border-color:gray gray gray gray"><tr><td height="20px" width="10%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N(序号)</td><td height="20px" width="75%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Safety Conditions (安全条件)</td><td height="20px" width="15%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">YES / NO / N/A</td></tr>';
        if($apply->condition_set != '{}' && $apply->condition_set != '[]') {
            $condition_set = json_decode($apply->condition_set, true);
            $resultText = ApplyBasic::resultText();
            $condition_list = PtwCondition::conditionList();

            if (array_key_exists('name', $condition_set[0])) {
                foreach ($condition_set as $key => $row) {
                    $condition_name = '';
                    $condition_name = $row['name'] . '<br>' . $row['name_en'];
                    $condition_html .= '<tr><td  align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . ($key + 1) . '</td><td>&nbsp;' . $condition_name . '</td><td  align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $resultText[$row['check']] . '</td></tr>';
                }
            }
            if (array_key_exists('id', $condition_set[0])) {
                foreach ($condition_set as $key => $row) {
                    $condition_name = '';
                    $name = $condition_list[$row['id']]['condition_name'];
                    $name_en = $condition_list[$row['id']]['condition_name_en'];
                    $condition_name = $name . '<br>' . $name_en;
                    //$condition_name = $row['name'].'<br>'.$row['name_en'];
                    $condition_html .= '<tr><td  align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . ($key + 1) . '</td><td  style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $condition_name . '</td><td  align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $resultText[$row['check']] . '</td></tr>';
                }
            }
        }
        $condition_html .= '</table>';
        $remark_html = "<table style=\"border-width: 1px;border-color:gray gray gray gray\"><tr><td height=\"20px\" width=\"100%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Remark (备注)</td></tr>";
        $remark_html .="<tr><td height=\"20px\" width=\"100%\" nowrap=\"nowrap\"  align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$apply->devices}</td></tr></table>";

        $region_html = '<h2 align="center">Work Location (工作地点)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="100%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Project Area (项目区域)</td></tr>';
        if (!empty($region_list)){
            foreach($region_list as $region => $secondary){
                $secondary_str = '';
                if(!empty($secondary))
                    foreach($secondary as $num => $secondary_region){
                        $secondary_str .= '['.$num.']:'.$secondary_region.'  ';
                    }

                $region_html .= '<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $region . '</td></tr>';
            }
        }else{
            $region_html .= '<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Nil</td></tr>';
        }

        $region_html .= '</table>';

        $html_2 =$condition_html . $remark_html . $region_html;
        $pdf->writeHTML($html_2, true, false, true, false, '');

        $pdf->AddPage();
        $approval_y = $pdf->GetY();
        $audit_html = '<h2 align="center">Approval Process (审批流程)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Person in Charge<br>(执行人)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray"> Status<br>(状态)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray"> Date & Time<br>(日期&时间)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Remark<br>(备注)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Electronic Signature<br>(电子签名)</td></tr>';
        $audit_html = '<h2 align="center">Approval Process (审批流程)</h2><table style="border-width: 1px;border-color:gray gray gray gray">';
        //$audit_close_html = '<h5 align="center">' . Yii::t('license_licensepdf', 'progress_close_list') . '</h5><table border="1">
        //<tr><td width="10%">&nbsp;' . Yii::t('license_licensepdf', 'seq') . '</td><td width="30%">&nbsp;' . Yii::t('license_licensepdf', 'audit_person') . '</td><td width="30%"> '. Yii::t('license_licensepdf','audit_result').'</td><td width="30%"> ' . Yii::t('tbm_meeting', 'audit_date') . '</td></tr>';

        //$progress_close_list = WorkflowProgressDetail::progressList('PTW_CLOSE', $apply->apply_id);

        $progress_result = CheckApplyDetail::resultTxt();
        $j = 1;
        $y = 1;
        $info_xx = 170;
        if($approval_y > 260){
            $info_yy = 46;
        }else{
            $info_yy = $approval_y+24;
        }
        $declare_list = self::declareText();
        if (!empty($progress_list))
            foreach ($progress_list as $key => $row) {
                $content_list = Staff::model()->findAllByPk($row['deal_user_id']);
                $content = $content_list[0]['signature_path'];
                $role_id = $content_list[0]['role_id'];
                $ptw_role = ProgramUser::SelfPtwRole($row['deal_user_id'],$program_id);
                $role_list = ProgramUser::AllRoleList($main_conid);
                if($content != '' && $content != 'nil' && $content != '-1') {
                    if(file_exists($content)){
                        $content = '<img src="'.$content.'" height="30" width="30"  /> ';
//                        $pdf->Image($content, $info_xx, $info_yy, 21, 10, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                    }
                }
                if($row['status'] == 2){
                    $status = $reject_css[$row['deal_type']];
                }else{
                    $status = $status_css[$row['deal_type']];
                }
                $record_time = Utils::DateToEn($row['deal_time']);
                $date = substr($record_time,0,11);
                $time = substr($record_time,12,18);
                if($declare_list[$ptw_mode][$key]){
                    $declare = $declare_list[$ptw_mode][$key];
                    $audit_html .= '<tr><td colspan="4" style="border-width: 1px;border-color:gray gray white gray">'.$declare.'</td>';
                    $audit_html .= '<td rowspan="3" style="border-width: 1px;border-color:gray gray gray gray" align="center">'.$status.'<br>'.$date.'<br>'.$time.'</td></tr>';
                    $audit_html .= '<tr><td >Name</td><td align="left">'.$row['user_name'].'</td><td align="center">PTW Role</td><td>'.$role_list['ptw_role'][$ptw_role[0]['ptw_role']].'</td></tr>';
                    $audit_html .= '<tr><td style="border-width: 1px;border-color:white white gray gray">Designation</td><td align="left">'.$roleList[$role_id].'</td><td align="center">Signature</td><td style="border-width: 1px;border-color:white gray gray white">'.$content.'</td></tr>';
                }else{
                    $audit_html .= '<tr><td >Name</td><td align="left" style="border-width: 1px;border-color:gray white white white">'.$row['user_name'].'</td><td align="center" style="border-width: 1px;border-color:gray white white white">PTW Role</td><td>'.$role_list['ptw_role'][$ptw_role[0]['ptw_role']].'</td>';
                    $audit_html .= '<td rowspan="2" style="border-width: 1px;border-color:gray gray gray gray" align="center">'.$status.'<br>'.$date.'<br>'.$time.'</td></tr>';
                    $audit_html .= '<tr><td style="border-width: 1px;border-color:white white gray gray">Designation</td><td align="left">'.$roleList[$role_id].'</td><td align="center">Signature</td><td style="border-width: 1px;border-color:white gray gray white">'.$content.'</td></tr>';
                }

                $audit_html .= '<tr><td colspan="5" style="border-width: 1px;border-color:gray gray gray gray">Remarks:   '.$row['remark'].'</td></tr>';

                $j++;
            }
        $audit_html .= '</table>';
        $pdf->writeHTML($audit_html, true, false, true, false, '');

//        $pic_html = '<h4 align="center">Site Photo(s) (现场照片)</h4><table border="1">
//                <tr><td width ="100%" height="107px"></td></tr>';
//        $pic = $progress_list[0]['pic'];
//        if($pic != '') {
//            $pic = explode('|', $pic);
////            var_dump($pic);
////            exit;
//            $info_x = 40;
//            $info_y = 148;
//            foreach ($pic as $key => $content) {
////                $pic = 'C:\Users\minchao\Desktop\5.png';
//                if($content != '' && $content != 'nil' && $content != '-1') {
//                    if(file_exists($content)){
//                        $pdf->Image($content, $info_x, $info_yy + 13, 30, 23, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
//                        $info_x += 50;
//                    }
//                }
//            }
//        }
//        $pic_html .= '</table>';
        //现场人员
        $worker_html = '<br/><br/><h2 align="center">Member(s) (施工人员)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="5%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N<br>(序号)</td><td  height="20px" width="15%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;ID Number<br>(身份证号码)</td><td  height="20px" width="25%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Name<br>(姓名)</td><td height="20px" width="10%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Employee ID<br>(员工编号)</td><td height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Designation<br>(职位)</td><td height="20px" width="25%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Signature<br>(电子签名)</td></tr>';

        if (!empty($worker_list)){
            $i = 1;
            foreach ($worker_list as $user_id => $r) {
                $worker_model = Staff::model()->findAllByPk($user_id);//负责人
                if($worker_model[0]['work_pass_type'] = 'IC' || $worker_model[0]['work_pass_type'] = 'PR'){
                    if(substr($worker_model[0]['work_no'],0,1) == 'S' && strlen($worker_model[0]['work_no']) == 9){
                        $work_no = 'SXXXX'.substr($worker_model[0]['work_no'],5,8);
                    }else{
                        $work_no = $r['wp_no'];
                    }
                }else{
                    $work_no = $r['wp_no'];
                }
                $worker_sign_html = '<img src="' . $worker_model[0]['signature_path'] . '" height="30" width="30"  />';
                $worker_html .= '<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $i . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $work_no . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $r['user_name'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $user_id . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' .$roleList[$r['role_id']].  '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' .$worker_sign_html.  '</td></tr>';
                $i++;
            }
        }
        $worker_html .= '</table>';

        //现场设备
        $primary_list = Device::primaryAllList();
        $device_type = DeviceType::deviceList();
        $devices_html = '<br/><br/><h2 align="center">Equipment (设备)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="10%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N<br>(序号)</td><td height="20px" width="30%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Registration No.<br>(设备编码)</td><td height="20px" width="30%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Equipment Name<br>(设备名称)</td><td height="20px" width="30%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Equipment Type<br>(设备类型)</td></tr>';
        if (!empty($device_list)){
            $j =1;
            foreach ($device_list as $id => $list) {
                $devicetype_model = DeviceType::model()->findByPk($list['type_no']);//设备类型信息
                $device_type_ch = $devicetype_model->device_type_ch;
                $device_type_en = $devicetype_model->device_type_en;
                $devices_html .= '<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $j . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $primary_list[$id] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $list['device_name'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' .$device_type_en.'<br>'.$device_type_ch . '</td></tr>';
                $j++;
            }
        }else{
            $devices_html .= '<tr><td align="center" colspan="4" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Nil</td></tr>';
        }
        $devices_html .= '</table>';

        //文档标签
        $document_html = '<br/><br/><h2 align="center">Attachment(s) (附件)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">S/N (序号)</td><td  height="20px" width="80%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">Document Name (文档名称)</td></tr>';
        if(!empty($document_list)){
            $i =1;
            foreach($document_list as $cnt => $name){
                $document_html .='<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $i . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $name . '</td></tr>';
                $i++;
            }
        }else{
            $document_html .='<tr><td colspan="2" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Nil</td></tr>';
        }
        $document_html .= '</table>';

        $region_html = '<h2 align="center">Work Location (工作地点)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="100%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Project Area (项目区域)</td></tr>';
        if (!empty($region_list))
            foreach($region_list as $region => $secondary){
                $secondary_str = '';
                if(!empty($secondary))
                    foreach($secondary as $num => $secondary_region){
                        $secondary_str .= '['.$num.']:'.$secondary_region.'  ';
                    }

                $region_html .= '<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $region . '</td></tr>';
            }
        $region_html .= '</table>';

        $html_3 = $worker_html . $devices_html . $document_html;

        $pdf->writeHTML($html_3, true, false, true, false, '');


        //输出PDF
        if($params['tag'] == 0){
            $pdf->Output($filepath, 'F');  //保存到指定目录
        }else if($params['tag'] == 1){
            $pdf->Output($filepath, 'I');  //保存到指定目录
        }

        return $filepath;
//============================================================+
// END OF FILE
//============================================================+
    }

    //按项目查询安全检查次数（按类别分组）2019-03-19修改
    public static function AllCntList($args){
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $pro_model =Program::model()->findByPk($args['program_id']);
        // $type_list = PtwType::typeByContractor($args['program_id']);
        $month = Utils::MonthToCn($args['date']);
        //分包项目
        if($args['contractor_id'] != '' && $pro_model->main_conid != $args['contractor_id'] ){
            $root_proid = $pro_model->root_proid;
            // $sql = "select count(apply_id) as cnt,program_id,type_id from ptw_apply_basic where program_id = '".$args['program_id']."' and record_time like '".$month."%' and apply_contractor_id = '".$args['contractor_id']."'  GROUP BY type_id";
            $sql = "select count(t.apply_id) as cnt,t.program_id,t.type_id,li.type_name_en from ptw_apply_basic as t LEFT JOIN ptw_type_list as li ON li.type_id=t.type_id where t.program_id = '".$root_proid."' and t.record_time like '".$month."%' and t.apply_contractor_id = '".$args['contractor_id']."'  GROUP BY t.type_id";

        }else{
            //总包项目
            // $sql = "select count(apply_id) as cnt,program_id,type_id from ptw_apply_basic where record_time like '".$month."%' and program_id ='".$args['program_id']."'  GROUP BY type_id";
            $sql = "select count(t.apply_id) as cnt,t.program_id,t.type_id,li.type_name_en from ptw_apply_basic as t LEFT JOIN ptw_type_list as li ON li.type_id=t.type_id where t.record_time like '".$month."%' and t.program_id ='".$args['program_id']."'  GROUP BY t.type_id";
        }
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if(!empty($rows)){
            foreach($rows as $num => $list){
                $r[$num]['cnt'] = $list['cnt'];
                $r[$num]['type_name'] = $list['type_name_en'];

            }
        }
        return $r;
    }
    //按项目查询安全检查次数（按公司分组）
    public static function CompanyCntList($args){
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $pro_model =Program::model()->findByPk($args['program_id']);
        $month = Utils::MonthToCn($args['date']);
        $contractor_list = Contractor::compAllList();

        //分包项目
        if($pro_model->main_conid != $args['contractor_id']){
            $root_proid = $pro_model->root_proid;
            $sql = "select count(apply_id) as cnt,apply_contractor_id from ptw_apply_basic where program_id = '".$root_proid."' and record_time like '".$month."%' and apply_contractor_id = '".$args['contractor_id']."'  GROUP BY apply_contractor_id";
        }else{
            //总包项目
            $sql = "select count(apply_id) as cnt,apply_contractor_id from ptw_apply_basic  where record_time like '".$month."%' and program_id ='".$args['program_id']."'  GROUP BY apply_contractor_id";
        }

        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if(!empty($rows)){
            foreach($rows as $num => $list){
                $r[$num]['cnt'] = $list['cnt'];
                $r[$num]['contractor_name'] = $contractor_list[$list['apply_contractor_id']];
            }
        }
        return $r;
    }

    //按项目查询（按stutas把ptw_apply_basic表里的数据分组）
    public static function TestCntList($args){
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $pro_model =Program::model()->findByPk($args['program_id']);
        // $type_list = PtwType::typeByContractor($args['program_id']);
        $month = Utils::MonthToCn($args['date']);
        $year = substr($month,0,4);
        $table = "bac_check_apply_detail_" . $year;
        if($pro_model->main_conid != $args['contractor_id']){
            //SUBSTRING_INDEX(a.add_operator, '|', 1)
            $root_proid = $pro_model->root_proid;
            $sql = "select count(a.apply_id) as cnt,a.program_id,b.deal_type from ptw_apply_basic a,$table b where a.program_id = '".$root_proid."' and a.record_time like '".$month."%' and a.apply_contractor_id = '".$args['contractor_id']."' and a.apply_id = b.apply_id and b.step = SUBSTRING_INDEX(a.add_operator, '|', 1)  GROUP BY b.deal_type";
//            $sql = "select count(apply_id) as cnt,program_id,status from ptw_apply_basic where program_id = '".$args['program_id']."' and record_time like '".$month."%' and apply_contractor_id = '".$args['contractor_id']."'  GROUP BY status";
        }else{
//            $sql = "select count(apply_id) as cnt,program_id,status from ptw_apply_basic where record_time like '".$month."%' and program_id ='".$args['program_id']."'  GROUP BY status";
            $sql = "select count(a.apply_id) as cnt,a.program_id,b.deal_type from ptw_apply_basic a,$table b where a.record_time like '".$month."%' and a.program_id ='".$args['program_id']."' and a.apply_id = b.apply_id and b.step = SUBSTRING_INDEX(a.add_operator, '|', 1) GROUP BY b.deal_type";
        }
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if(!empty($rows)){
            foreach($rows as $num => $list){
                $r[$num]['cnt'] = $list['cnt'];
                $r[$num]['status'] =CheckApplyDetail::statusText($list['deal_type']);
            }
        }
        return $r;
    }

    //按项目查询（按stutas把ptw_apply_basic表里的数据分组）
    public static function StatusExcelList($args){
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $pro_model =Program::model()->findByPk($args['program_id']);
        // $type_list = PtwType::typeByContractor($args['program_id']);
//        $args['start_date'] = Utils::DateToCn($args['start_date']);
//        $args['end_date'] = Utils::DateToCn($args['end_date']) . " 23:59:59";
        $args['month'] = Utils::MonthToCn($args['month']);

        if($pro_model->main_conid != $args['contractor_id']){
            $root_proid = $pro_model->root_proid;
            $sql = "select count(apply_id) as cnt,program_id,SUBSTRING_INDEX(SUBSTRING_INDEX(add_operator, '|', 2), '|', -1) as dealtype from ptw_apply_basic where program_id = '".$root_proid."' and record_time like '%".$args['month']."%' and apply_contractor_id = '".$args['contractor_id']."'  GROUP BY SUBSTRING_INDEX(SUBSTRING_INDEX(add_operator, '|', 2), '|', -1)";
        }else{
            $sql = "select count(apply_id) as cnt,program_id,SUBSTRING_INDEX(SUBSTRING_INDEX(add_operator, '|', 2), '|', -1) as dealtype from ptw_apply_basic where record_time like '%".$args['month']."%'  and program_id ='".$args['program_id']."'  GROUP BY SUBSTRING_INDEX(SUBSTRING_INDEX(add_operator, '|', 2), '|', -1)";
        }
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $status_css = CheckApplyDetail::dealtypeTxt();//PTW执行类型(成功)
        if(!empty($rows)){
            foreach($rows as $num => $list){
                $r[$num]['cnt'] = $list['cnt'];
                $r[$num]['status'] =$status_css[$list['dealtype']];
            }
        }

        return $r;
    }

    /**
     * 导出表格查询
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function queryExcelList($args = array()) {

        $condition = '';
        $params = array();

        if ($args['program_id'] != '') {
            $pro_model =Program::model()->findByPk($args['program_id']);
            //分包项目
            if($pro_model->main_conid != $args['contractor_id']){
                $condition.= ( $condition == '') ? ' t.program_id ='.$args["program_id"] : ' AND t.program_id ='.$args["program_id"];
                $condition.= ( $condition == '') ? ' apply_contractor_id ='.$args["contractor_id"] : ' AND apply_contractor_id ='.$args["contractor_id"];
            }else{
                //总包项目
                $condition.= ( $condition == '') ? ' t.program_id ='.$args["program_id"] : ' AND t.program_id ='.$args["program_id"];
            }
        }

        //type_id
        if ($args['type_id'] != '') {
            $condition.= ( $condition == '') ? ' t.type_id='.$args['type_id'] : ' AND t.type_id='.$args['type_id'];
        }
        //操作开始时间
        if ($args['start_date'] != '') {
            $start_date = Utils::DateToCn($args['start_date']);
            $condition.= ( $condition == '') ? ' t.record_time >='."'$start_date'" : ' AND t.record_time >='."'$start_date'";
        }
        //操作结束时间
        if ($args['end_date'] != '') {
            $end_date = Utils::DateToCn($args['end_date']) . " 23:59:59" ;
            $condition.= ( $condition == '') ? ' t.record_time <='."'$end_date'" : ' AND t.record_time <='."'$end_date'";
        }

        if ($args['month'] != '') {
            $month = Utils::MonthToCn($args['month']);
            $condition.= ( $condition == '') ? ' t.record_time like'."'%$month%'" : ' AND t.record_time like '."'$month%'";
        }

        //Contractor
        if ($args['con_id'] != ''){
            //我提交+我审批＝我参与
            $condition.= ( $condition == '') ? ' t.apply_contractor_id = '.$args['con_id'] : ' AND t.apply_contractor_id = '.$args['con_id'];
        }
        if ($_REQUEST['q_order'] == '') {

            $order = 't.record_time desc';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = 't'.substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = 't'.$_REQUEST['q_order'] . ' ASC';
        }
        $sql_1 = "select t.* from ptw_apply_basic as t where  ".$condition." order by t.record_time desc";
        $command = Yii::app()->db->createCommand($sql_1);
        $rows = $command->queryAll();

//        var_dump($sql_1);
//        exit;
//        $sql_2 = "select t.apply_id,bl.* from ptw_apply_basic as t ,ptw_apply_block AS bl  where bl.apply_id = t.apply_id and".$condition ;
//        var_dump($sql_2);
//        exit;
//        $command_2 = Yii::app()->db->createCommand($sql_2);
//        $s = $command_2->queryAll();

        foreach($rows as $x => $y){
            $r[$y['apply_id']] = $y;
        }

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['rows'] = $r;
//        $rs['s'] = $s;



        return $rs;
    }

    //生成二维码（根据模型）
    public static function createQr($apply_id,$program_id){

        $PNG_TEMP_DIR = Yii::app()->params['upload_data_path'] . '/qrcode/' . $program_id . '/model/';

        if (!file_exists($PNG_TEMP_DIR))
            @mkdir($PNG_TEMP_DIR, 0777, true);

        //processing form input
        //remember to sanitize user input in real-life solution !!!
        $tcpdfPath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . 'phpqrcode' . DIRECTORY_SEPARATOR . 'qrlib.php';
        require_once($tcpdfPath);

        $errorCorrectionLevel = 'L';
        if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L', 'M', 'Q', 'H')))
            $errorCorrectionLevel = $_REQUEST['level'];

        $matrixPointSize = 6;
        if (isset($_REQUEST['size']))
            $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);
        $filename = $PNG_TEMP_DIR . $apply_id . '.png';

//        $content = array();
//        $content['apply_id'] = $apply_id;
//        $content = json_encode($content);
//        $content = base64_encode($content);
        $content = 'ptw|'.$apply_id;
        QRcode::png($content, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
        return $filename;
    }

    //下载PDF
    public static function downloadQrPdf($data,$program_id){
        $pro_model = Program::model()->findByPk($program_id);
        $program_id = $pro_model->root_proid;
        $program_name = $pro_model->program_name;
        $company_list = Contractor::compAllList();//承包商公司列表
        $typeList = ApplyBasic::typeList();
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
        foreach($data as $i => $apply_id){
            $html = "";
            $pdf->AddPage('L', 'A7');
            $ptw_model = ApplyBasic::model()->findByPk($apply_id);
            $ptw_title = $ptw_model->title;
            $apply_contractor_id = $ptw_model->apply_contractor_id;
            $type_id = $ptw_model->type_id;
            $filename = self::createQr($apply_id,$program_id);
            $record_time = Utils::DateToEn($ptw_model->record_time);
//            $pdf->Image($cms, 84, 60, 20, 5, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
            $title = "<h2 style=\"font-size: 200% \" align=\"center\">$program_name</h2>";
            $html.= "<table width=\"100%\" border=\"1\" cellpadding=\"4\">
                    <tr>
  	                    <td  align=\"left\" height=\"20px\" width=\"25%\" >Title</td>
  	                    <td  align=\"center\" width=\"42%\" >$ptw_title</td>
  	                    <td rowspan=\"4\" align=\"center\" width=\"33%\"><br><br><img src=\"$filename\" height=\"100\" width=\"100\" align=\"middle\"/></td>
                    </tr>
                    <tr>
                        <td  align=\"left\" height=\"20px\" width=\"25%\" >Contractor</td>
  	                    <td  align=\"center\" width=\"42%\" >$company_list[$apply_contractor_id]</td>
                    </tr>
                   <tr>
                        <td  align=\"left\" height=\"20px\" width=\"25%\" >Type</td>
  	                    <td  align=\"center\" width=\"42%\" >$typeList[$type_id]</td>
                    </tr>
                    <tr>
                        <td  align=\"left\" height=\"20px\" width=\"25%\" >Record Time</td>
  	                    <td  align=\"center\" width=\"42%\" >$record_time</td>
                    </tr>
                </table><br><div style=\"text-align:right\"><img src=\"$cms\" height=\"20\" width=\"65\" /></div>";
            $pdf->writeHTML($title, true, false, true, false, '');
            $pdf->writeHTML($html, true, false, true, false, '');
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
            $filepath_cnt = $redis->get('ptw_cnt');
            $x = 0;
            for($j=0;$j<=$filepath_cnt;$j++){
                $path = $redis->lPop('ptw-list');
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

}
