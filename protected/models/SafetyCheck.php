<?php

/**
 * 安全检查
 * @author LiuMinchao
 */
class SafetyCheck extends CActiveRecord {

     //状态：0-进行中，1－已关闭，2-超时强制关闭。
    const Pending_Review = '-1';//进行中
    const STATUS_ONGOING = '0'; //进行中
    const STATUS_CLOSE = '1'; //已关闭
    const STATUS_TIMEOUT_CLOSE = '2'; //超时强制关闭
    const STATUS_REPLIED = '99';

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_safety_check';
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

    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::Pending_Review => Yii::t('comp_safety', 'Pending_Review'),
            self::STATUS_ONGOING => Yii::t('comp_safety', 'STATUS_ONGOING'),
            self::STATUS_CLOSE => Yii::t('comp_safety', 'STATUS_CLOSE'),
            self::STATUS_TIMEOUT_CLOSE => Yii::t('comp_safety', 'STATUS_TIMEOUT_CLOSE'),
            self::STATUS_REPLIED=> 'Replied',
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::Pending_Review => 'label-info', //进行中
            self::STATUS_ONGOING => 'label-info', //进行中
            self::STATUS_CLOSE => ' label-success', //已关闭
            self::STATUS_TIMEOUT_CLOSE => ' label-warning', //超时强制关闭
            self::STATUS_REPLIED=> 'label-info',
        );
        return $key === null ? $rs : $rs[$key];
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
        $contactor_id = Yii::app()->user->getState('contractor_id');
        $new_sql = "select a.* from bac_safety_check a join bac_staff b on a.apply_user_id = b.user_id and b.contractor_id = '".$contactor_id."' where root_proid = '".$args['program_id']."' 
        union
        select a.* from bac_safety_check a where a.root_proid = '".$args['program_id']."' ";

        //Apply
        if ($args['check_id'] != '') {
            $new_sql.= " and a.check_id = '".$args['check_id']."'";
            $condition.= ( $condition == '') ? ' check_id=:check_id ' : ' AND check_id=:check_id';
            $params['check_id'] = $args['check_id'];
        }
        //Type_id
        if ($args['type_id'] != '') {
            $new_sql.= " and a.type_id = '".$args['type_id']."'";
            $condition.= ( $condition == '') ? ' type_id=:type_id ' : ' AND type_id=:type_id';
            $params['type_id'] = $args['type_id'];
        }
        //Findings_id
        if ($args['findings_id'] != '') {
            $new_sql.= " and a.findings_id = '".$args['findings_id']."'";
            $condition.= ( $condition == '') ? ' findings_id=:findings_id ' : ' AND findings_id=:findings_id';
            $params['findings_id'] = $args['findings_id'];
        }
        //safety_level
        if ($args['safety_level'] != '') {
            $new_sql.= " and a.safety_level = '".$args['safety_level']."'";
            $condition.= ( $condition == '') ? ' safety_level=:safety_level ' : ' AND safety_level=:safety_level';
            $params['safety_level'] = $args['safety_level'];
        }
        //Program Name
        if ($args['root_proname'] != '') {
            $new_sql.= " and a.root_proname = '".$args['root_proname']."'";
            $condition.= ( $condition == '') ? ' root_proname=:root_proname ' : ' AND root_proname=:root_proname';
            $params['root_proname'] = $args['root_proname'];
        }

        //Contractor
        if ($args['con_id'] != ''){
            $new_sql.= " and a.contractor_id = '".$args['con_id']."'";
            $condition.= ( $condition == '') ? ' contractor_id =:contractor_id ' : ' AND contractor_id =:contractor_id';
            $params['contractor_id'] = $args['con_id'];
        }
        //发起人
        if($args['initiator'] !=''){
            $model = Staff::model()->find('user_name=:user_name',array(':user_name'=>$args['initiator']));
            if($model) {
                $initiator = $model->user_id;
                $new_sql.= " and a.apply_user_id = '".$initiator."'";
                $condition.= ( $condition == '') ? ' apply_user_id =:apply_user_id ' : ' AND apply_user_id =:apply_user_id';
                $params['apply_user_id'] = $initiator;
            }else{
                $condition.= ( $condition == '') ? ' apply_user_id =:apply_user_id ' : ' AND apply_user_id =:apply_user_id';
                $params['apply_user_id'] = '';
            }
        }
        //负责人
        if($args['person_in_charge'] !=''){
            $model = Staff::model()->find('user_name=:user_name',array(':user_name'=>$args['person_in_charge']));
            if($model) {
                $person_in_charge_id = $model->user_id;
                $new_sql.= " and a.person_in_charge_id = '".$person_in_charge_id."'";
                $condition.= ( $condition == '') ? ' person_in_charge_id =:person_in_charge_id ' : ' AND person_in_charge_id =:person_in_charge_id';
                $params['person_in_charge_id'] = $person_in_charge_id;
            }else{
                $condition.= ( $condition == '') ? ' person_in_charge_id =:person_in_charge_id ' : ' AND person_in_charge_id =:person_in_charge_id';
                $params['person_in_charge_id'] = '';
            }
        }
        //责任人
        if($args['person_responsible'] !=''){
            $model = Staff::model()->find('user_name=:user_name',array(':user_name'=>$args['person_responsible']));
            if($model) {
                $person_responsible = $model->user_id;
                $new_sql.= " left join bac_violation_record b on a.check_id = b.check_id and b.user_id = '".$person_responsible."'";
                $sql = 'SELECT check_id FROM bac_violation_record where user_id = '.$person_responsible.'';
                $command = Yii::app()->db->createCommand($sql);
                $rows = $command->queryAll();
                $i = '';
                foreach($rows as $n => $r){
                    $i.=$r['check_id'].',';
                }

                if ($i != '')
                    $check_id = substr($i, 0, strlen($i) - 1);
                $condition.= ( $condition == '') ? ' check_id IN ('.$check_id.') ' : ' AND check_id IN ('.$check_id.')';
                $new_sql.= " and a.check_id in '('. '".$args['check_id']."' . ')' ";
            }else{
                $condition.= ( $condition == '') ? ' check_id = :check_id ' : ' AND check_id = :check_id';
                $params['check_id'] = '';
                $new_sql.= " and a.check_id in '('. '".$args['check_id']."' . ')' ";
            }
        }
        //操作开始时间
        // if ($args['apply_time'] != '') {
        //     $new_sql.= " and a.apply_time='".Utils::DateToCn($args['apply_time'])."'";
        //     $condition.= ( $condition == '') ? ' apply_time ==:apply_time' : ' AND apply_time ==:apply_time';
        //     $params['apply_time'] = Utils::DateToCn($args['apply_time']);
        // }
        // //操作结束时间
        // if ($args['stipulation_time'] != '') {
        //     $new_sql.= " and a.stipulation_time='".Utils::DateToCn($args['stipulation_time'])."'";
        //     $condition.= ( $condition == '') ? ' stipulation_time ==:stipulation_time' : ' AND stipulation_time ==:stipulation_time';
        //     $params['stipulation_time'] = Utils::DateToCn($args['stipulation_time']);
        // }

        //操作开始时间
        if ($args['start_date'] != '') {
            $condition.= ( $condition == '') ? ' apply_time >=:start_date' : ' AND apply_time >=:start_date';
            $params['start_date'] = Utils::DateToCn($args['start_date']);
        }
        //操作结束时间
        if ($args['end_date'] != '') {
            $condition.= ( $condition == '') ? ' apply_time <=:end_date' : ' AND apply_time <=:end_date';
            $params['end_date'] = Utils::DateToCn($args['end_date']) . " 23:59:59";
        }

        $contractor_list = Contractor::Mc_scCompList($args);
        if ($args['program_id'] != '') {
            $pro_model =Program::model()->findByPk($args['program_id']);
            //分包项目
            if($pro_model->main_conid != $args['contractor_id']){
                $condition.= ( $condition == '') ? ' root_proid =:program_id' : ' AND root_proid =:program_id';
                $condition.= ( $condition == '') ? ' contractor_id =:contractor_id ' : ' AND contractor_id =:contractor_id ';
                $root_proid = $pro_model->root_proid;
                $params['program_id'] = $root_proid;
                //$params['program_id'] = $args['program_id'];
                $params['contractor_id'] = $args['contractor_id'];
            }else{
                //总包项目
                $condition.= ( $condition == '') ? ' root_proid =:program_id' : ' AND root_proid =:program_id';
                $params['program_id'] = $args['program_id'];
            }
        }else{
            $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
            $program_list = Program::McProgramList($args);
            $key_list = array_keys($program_list);
            $program_id = $key_list[0];
            $pro_model =Program::model()->findByPk($program_id);
            //分包项目
            if($pro_model->main_conid != $args['contractor_id']){
                $condition.= ( $condition == '') ? ' root_proid =:program_id' : ' AND root_proid =:program_id';
                $condition.= ( $condition == '') ? ' contractor_id =:contractor_id ' : ' AND contractor_id =:contractor_id ';
                $params['program_id'] = $program_id;
                $params['contractor_id'] = $args['contractor_id'];
            }else{
                //总包项目
                $condition.= ( $condition == '') ? ' root_proid =:program_id' : ' AND root_proid =:program_id';
                $params['program_id'] = $program_id;
            }
        }

        if($args['user_id'] != ''){
            if($args['deal_type'] == 1) {
                $sql = "SELECT check_id FROM bac_safety_check  WHERE apply_user_id = '".$args['user_id']."' and root_proid = '".$args['program_id']."' and safety_level = '".$args['safety_level']."'";
                $command = Yii::app()->db->createCommand($sql);
                $rows = $command->queryAll();
                if (count($rows) > 0) {
                    foreach ($rows as $key => $row) {
                        $args['check_id'] .= $row['check_id'] . ',';
                    }
                }
                if ($args['check_id'] != '')
                    $args['check_id'] = substr($args['check_id'], 0, strlen($args['check_id']) - 1);
                $condition .= ($condition == '') ? ' check_id IN (' . $args['check_id'] . ')' : ' AND check_id IN (' . $args['check_id'] . ')';
                $new_sql.= " and a.check_id in '('. '".$args['check_id']."' . ')' ";
            }else if($args['deal_type'] == 2){
                $sql = "SELECT check_id FROM bac_safety_check  WHERE person_in_charge_id = '".$args['user_id']."' and root_proid = '".$args['program_id']."' and safety_level = '".$args['safety_level']."'";
                $command = Yii::app()->db->createCommand($sql);
                $rows = $command->queryAll();
                if (count($rows) > 0) {
                    foreach ($rows as $key => $row) {
                        $args['check_id'] .= $row['check_id'] . ',';
                    }
                }
                if ($args['check_id'] != '')
                    $args['check_id'] = substr($args['check_id'], 0, strlen($args['check_id']) - 1);
                $condition .= ($condition == '') ? ' check_id IN (' . $args['check_id'] . ')' : ' AND check_id IN (' . $args['check_id'] . ')';
                $new_sql.= " and a.check_id in '('. '".$args['check_id']."' . ')' ";
            }else{
                $sql = "SELECT b.check_id FROM bac_violation_record a,bac_safety_check b  where a.user_id = '".$args['user_id']."' and a.check_id=b.check_id and b.root_proid = '".$args['program_id']."' and b.safety_level = '".$args['safety_level']."'";
                $command = Yii::app()->db->createCommand($sql);
                $rows = $command->queryAll();
                if (count($rows) > 0) {
                    foreach ($rows as $key => $row) {
                        $args['check_id'] .= $row['check_id'] . ',';
                    }
                }
                if ($args['check_id'] != '')
                    $args['check_id'] = substr($args['check_id'], 0, strlen($args['check_id']) - 1);
                $condition .= ($condition == '') ? ' check_id IN (' . $args['check_id'] . ')' : ' AND check_id IN (' . $args['check_id'] . ')';
                $new_sql.= " and a.check_id in '('. '".$args['check_id']."' . ')' ";
            }
        }

        //Record Time
        // if ($args['record_time'] != '') {
        //     $args['record_time'] = Utils::DateToCn($args['record_time']);
        //     $condition.= ( $condition == '') ? ' apply_time LIKE :record_time' : ' AND apply_time LIKE :record_time';
        //     $params['record_time'] = '%'.$args['record_time'].'%';
        // }

        $total_num = SafetyCheck::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {
            $new_sql.=' order by a.apply_time desc';
            $order = 'apply_time desc';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }
        $criteria->order = $order;
        // if($args['initiator']){
        //     $criteria->join = 'LEFT JOIN bac_staff b ON b.user_name='.$args['initiator'].'and t.apply_user_id = b.user_id';
        // }elseif($args['person_in_charge']){
        //     $criteria->join = 'LEFT JOIN bac_staff b ON b.user_name='.$args['person_in_charge'].'and t.person_in_charge_id = b.user_id';
        // }elseif($args['person_responsible']){

        // }
        $criteria->condition = $condition;
        $criteria->params = $params;
        $pages = new CPagination($total_num);
        $pages->pageSize = $pageSize;
        $pages->setCurrentPage($page);
        $pages->applyLimit($criteria);
        //var_dump($criteria);
        $rows = SafetyCheck::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
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
        $sql = '';

        if ($args['type_id'] != '') {
            $type_id = $args['type_id'];
            $condition .= "and a.type_id = '$type_id'";
        }

        //Findings_id
        if ($args['findings_id'] != '') {
            $findings_id = $args['findings_id'];
            $condition .= "and a.findings_id = '$findings_id'";
        }
        //safety_level
        if ($args['safety_level'] != '') {
            $safety_level = $args['safety_level'];
            $condition .= "and a.safety_level = '$safety_level'";
        }


        if ($args['start_date'] != '') {
            $start_date = Utils::DateToCn($args['start_date']);
            $condition .= "and a.apply_time >='$start_date' ";
        }

        if ($args['end_date'] != '') {
            $end_date = Utils::DateToCn($args['end_date']);
            $condition .= "and a.apply_time <='$end_date 23:59:59' ";
        }

        if ($args['status'] != '') {
            $status = $args['status'];
            if($status == '99'){
                $condition .=" and a.status='0' and a.current_step%2=0 ";
            }else{
                $condition .= "and a.status = '$status'";
            }
        }

        if ($args['program_id'] != '') {
            $pro_model =Program::model()->findByPk($args['program_id']);
            //分包项目
            if($pro_model->main_conid != $args['contractor_id']){
                $root_proid = $pro_model->root_proid;
                $args['program_id'] = $root_proid;
                $args['con_id'] = Yii::app()->user->contractor_id;
            }
        }

        if ($args['con_id'] != '') {
            $sql = "SELECT
                a.check_id, a.title, a.type_id, a.findings_name_en, a.contractor_id, c.description_en as safety_level, c.description as safety_level_cn, a.stipulation_time, a.person_in_charge_id, a.apply_user_id, a.apply_time, b.contractor_name,
                IF(a.status='0' && a.current_step%2=0, '99', a.status) as status,
                (CASE a.status WHEN '2' THEN '1' ELSE a.status END) as order_str
            FROM
                bac_safety_check a
            JOIN bac_contractor b ON a.contractor_id = b.contractor_id 
            LEFT JOIN bac_safety_level c ON a.safety_level = c.safety_level
            JOIN bac_staff d ON a.apply_user_id = d.user_id ";
            //发起人
            if($args['initiator'] !=''){
                $initiator = $args['initiator'];
                $sql .= " and d.user_name like '$initiator%'";
            }
            //$sql.=" or d.contractor_id = '".$args['con_id']."' and a.apply_user_id = d.user_id ";
            //负责人
            if($args['person_in_charge'] !=''){
                $person_in_charge = $args['person_in_charge'];
                $sql .= " JOIN bac_satff e ON e.user_name like '$person_in_charge%' and a.person_in_charge_id = e.user_id ";
            }
            //责任人
            if($args['person_responsible'] !=''){
                $model = Staff::model()->find('user_name=:user_name',array(':user_name'=>$args['person_responsible']));
                if($model) {
                    $person_responsible = $model->user_id;
                    $sql.= "  join bac_violation_record f on a.check_id = f.check_id and f.user_id = '".$person_responsible."'";
                }else{
                    $sql.= "  join bac_violation_record f on a.check_id = f.check_id and f.user_id = ''";
                }
            }
            $sql.= "  WHERE 
                a.root_proid = '".$args['program_id']."' and a.contractor_id = '".$args['con_id']."'  $condition
            order by a.apply_time desc";
        }else{
            $sql = "SELECT
                a.check_id, a.title, a.type_id, a.findings_name_en, a.contractor_id, c.description_en as safety_level, c.description as safety_level_cn, a.stipulation_time, a.person_in_charge_id, a.apply_user_id, a.apply_time, b.contractor_name,
                IF(a.status='0' && a.current_step%2=0, '99', a.status) as status,
                (CASE a.status WHEN '2' THEN '1' ELSE a.status END) as order_str
            FROM
                bac_safety_check a
            JOIN bac_contractor b ON a.contractor_id = b.contractor_id 
            LEFT JOIN bac_safety_level c ON a.safety_level = c.safety_level
            JOIN bac_staff d ON a.apply_user_id = d.user_id  ";
            //发起人
            if($args['initiator'] !=''){
                $initiator = $args['initiator'];
                $sql .= " and d.user_name like '$initiator%'";
            }

            //负责人
            if($args['person_in_charge'] !=''){
                $person_in_charge = $args['person_in_charge'];
                $sql .= " JOIN bac_staff e ON e.user_name like '$person_in_charge%' and a.person_in_charge_id = e.user_id ";
            }
            //责任人
            if($args['person_responsible'] !=''){
                $model = Staff::model()->find('user_name=:user_name',array(':user_name'=>$args['person_responsible']));
                if($model) {
                    $person_responsible = $model->user_id;
                    $sql.= "  join bac_violation_record e on a.check_id = e.check_id and e.user_id = '".$person_responsible."'";
                }else{
                    $sql.= "  join bac_violation_record e on a.check_id = e.check_id and e.user_id = ''";
                }
            }
            $sql.= " WHERE 
                a.root_proid = '".$args['program_id']."' $condition
            order by a.apply_time desc";
        }

        $command = Yii::app()->db->createCommand($sql);
        $retdata = $command->queryAll();


        $start=$page*$pageSize; #计算每次分页的开始位置
        $count = count($retdata);
        $pagedata=array();
        if($count>0){
            $pagedata=array_slice($retdata,$start,$pageSize);
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


    /**
     * 查询
     * @param array $args
     * @return array
     */
    public static function queryAllList($args = array()) {

        $condition = '';
        $params = array();

        //Apply
        if ($args['check_id'] != '') {
            $condition.= ( $condition == '') ? ' check_id=:check_id ' : ' AND check_id=:check_id';
            $params['check_id'] = $args['check_id'];
        }
        //Type_id
        if ($args['type_id'] != '') {
            $condition.= ( $condition == '') ? ' type_id=:type_id ' : ' AND type_id=:type_id';
            $params['type_id'] = $args['type_id'];
        }
        //Findings_id
        if ($args['findings_id'] != '') {
            $condition.= ( $condition == '') ? ' findings_id=:findings_id ' : ' AND findings_id=:findings_id';
            $params['findings_id'] = $args['findings_id'];
        }
        //safety_level
        if($args['tag'] == '1'){
            $condition.= ( $condition == '') ? ' safety_level<>:safety_level ' : ' AND safety_level<>:safety_level';
            $params['safety_level'] = '0';
        }else{
            if ($args['safety_level'] != '') {
                $condition.= ( $condition == '') ? ' safety_level=:safety_level ' : ' AND safety_level=:safety_level';
                $params['safety_level'] = '0';
            }

        }

        //Program Name
        if ($args['root_proname'] != '') {
            $condition.= ( $condition == '') ? ' root_proname=:root_proname ' : ' AND root_proname=:root_proname';
            $params['root_proname'] = $args['root_proname'];
        }

        //Contractor
        if ($args['con_id'] != ''){
            $condition.= ( $condition == '') ? ' contractor_id =:contractor_id ' : ' AND contractor_id =:contractor_id';
            $params['contractor_id'] = $args['con_id'];
        }
        //发起人
        if($args['initiator'] !=''){
            $model = Staff::model()->find('user_name=:user_name',array(':user_name'=>$args['initiator']));
            if($model) {
                $initiator = $model->user_id;
                $condition.= ( $condition == '') ? ' apply_user_id =:apply_user_id ' : ' AND apply_user_id =:apply_user_id';
                $params['apply_user_id'] = $initiator;
            }else{
                $condition.= ( $condition == '') ? ' apply_user_id =:apply_user_id ' : ' AND apply_user_id =:apply_user_id';
                $params['apply_user_id'] = '';
            }
        }
        //负责人
        if($args['person_in_charge'] !=''){
            $model = Staff::model()->find('user_name=:user_name',array(':user_name'=>$args['person_in_charge']));
            if($model) {
                $person_in_charge_id = $model->user_id;
                $condition.= ( $condition == '') ? ' person_in_charge_id =:person_in_charge_id ' : ' AND person_in_charge_id =:person_in_charge_id';
                $params['person_in_charge_id'] = $person_in_charge_id;
            }else{
                $condition.= ( $condition == '') ? ' person_in_charge_id =:person_in_charge_id ' : ' AND person_in_charge_id =:person_in_charge_id';
                $params['person_in_charge_id'] = '';
            }
        }
        //责任人
        if($args['person_responsible'] !=''){
            $model = Staff::model()->find('user_name=:user_name',array(':user_name'=>$args['person_responsible']));
            if($model) {
                $person_responsible = $model->user_id;
                $sql = 'SELECT check_id FROM bac_violation_record where user_id = '.$person_responsible.'';
                $command = Yii::app()->db->createCommand($sql);
                $rows = $command->queryAll();
                $i = '';
                foreach($rows as $n => $r){
                    $i.=$r['check_id'].',';
                }

                if ($i != '')
                    $check_id = substr($i, 0, strlen($i) - 1);
                $condition.= ( $condition == '') ? ' check_id IN ('.$check_id.') ' : ' AND check_id IN ('.$check_id.')';
            }else{
                $condition.= ( $condition == '') ? ' check_id = :check_id ' : ' AND check_id = :check_id';
                $params['check_id'] = '';
            }
        }
        $contractor_list = Contractor::Mc_scCompList($args);
        if ($args['program_id'] != '') {
            $pro_model =Program::model()->findByPk($args['program_id']);
            //分包项目
            if($pro_model->main_conid != $args['contractor_id']){
                $condition.= ( $condition == '') ? ' root_proid =:program_id' : ' AND root_proid =:program_id';
                $condition.= ( $condition == '') ? ' contractor_id =:contractor_id ' : ' AND contractor_id =:contractor_id ';
                $params['program_id'] = $args['program_id'];
                $params['contractor_id'] = $args['contractor_id'];
            }else{
                //总包项目
                $condition.= ( $condition == '') ? ' root_proid =:program_id' : ' AND root_proid =:program_id';
                $params['program_id'] = $args['program_id'];
            }
        }else{
            $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
            $program_list = Program::McProgramList($args);
            $key_list = array_keys($program_list);
            $program_id = $key_list[0];
            $pro_model =Program::model()->findByPk($program_id);
            //分包项目
            if($pro_model->main_conid != $args['contractor_id']){
                $condition.= ( $condition == '') ? ' root_proid =:program_id' : ' AND root_proid =:program_id';
                $condition.= ( $condition == '') ? ' contractor_id =:contractor_id ' : ' AND contractor_id =:contractor_id ';
                $params['program_id'] = $program_id;
                $params['contractor_id'] = $args['contractor_id'];
            }else{
                //总包项目
                $condition.= ( $condition == '') ? ' root_proid =:program_id' : ' AND root_proid =:program_id';
                $params['program_id'] = $program_id;
            }
        }

        if($args['user_id'] != ''){
            if($args['deal_type'] == 1) {
                $sql = "SELECT check_id FROM bac_safety_check  WHERE apply_user_id = '".$args['user_id']."' and root_proid = '".$args['program_id']."' and safety_level = '".$args['safety_level']."'";
                $command = Yii::app()->db->createCommand($sql);
                $rows = $command->queryAll();
                if (count($rows) > 0) {
                    foreach ($rows as $key => $row) {
                        $args['check_id'] .= $row['check_id'] . ',';
                    }
                }
                if ($args['check_id'] != '')
                    $args['check_id'] = substr($args['check_id'], 0, strlen($args['check_id']) - 1);
                $condition .= ($condition == '') ? ' check_id IN (' . $args['check_id'] . ')' : ' AND check_id IN (' . $args['check_id'] . ')';
            }else if($args['deal_type'] == 2){
                $sql = "SELECT check_id FROM bac_safety_check  WHERE person_in_charge_id = '".$args['user_id']."' and root_proid = '".$args['program_id']."' and safety_level = '".$args['safety_level']."'";
                $command = Yii::app()->db->createCommand($sql);
                $rows = $command->queryAll();
                if (count($rows) > 0) {
                    foreach ($rows as $key => $row) {
                        $args['check_id'] .= $row['check_id'] . ',';
                    }
                }
                if ($args['check_id'] != '')
                    $args['check_id'] = substr($args['check_id'], 0, strlen($args['check_id']) - 1);
                $condition .= ($condition == '') ? ' check_id IN (' . $args['check_id'] . ')' : ' AND check_id IN (' . $args['check_id'] . ')';
            }else{
                $sql = "SELECT b.check_id FROM bac_violation_record a,bac_safety_check b  where a.user_id = '".$args['user_id']."' and a.check_id=b.check_id and b.root_proid = '".$args['program_id']."' and b.safety_level = '".$args['safety_level']."'";
                $command = Yii::app()->db->createCommand($sql);
                $rows = $command->queryAll();
                if (count($rows) > 0) {
                    foreach ($rows as $key => $row) {
                        $args['check_id'] .= $row['check_id'] . ',';
                    }
                }
                if ($args['check_id'] != '')
                    $args['check_id'] = substr($args['check_id'], 0, strlen($args['check_id']) - 1);
                $condition .= ($condition == '') ? ' check_id IN (' . $args['check_id'] . ')' : ' AND check_id IN (' . $args['check_id'] . ')';
            }
        }

        //Record Time
        // if ($args['record_time'] != '') {
        //     $args['record_time'] = Utils::DateToCn($args['record_time']);
        //     $condition.= ( $condition == '') ? ' apply_time LIKE :record_time' : ' AND apply_time LIKE :record_time';
        //     $params['record_time'] = '%'.$args['record_time'].'%';
        // }
        //操作开始时间
        if ($args['start_date'] != '') {
            $condition.= ( $condition == '') ? ' apply_time >=:start_date' : ' AND apply_time >=:start_date';
            $params['start_date'] = Utils::DateToCn($args['start_date']);
        }
        //操作结束时间
        if ($args['end_date'] != '') {
            $condition.= ( $condition == '') ? ' apply_time <=:end_date' : ' AND apply_time <=:end_date';
            $params['end_date'] = Utils::DateToCn($args['end_date']) . " 23:59:59";
        }

        $total_num = SafetyCheck::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'apply_time desc';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }
        $criteria->order = $order;
        // if($args['initiator']){
        //     $criteria->join = 'LEFT JOIN bac_staff b ON b.user_name='.$args['initiator'].'and t.apply_user_id = b.user_id';
        // }elseif($args['person_in_charge']){
        //     $criteria->join = 'LEFT JOIN bac_staff b ON b.user_name='.$args['person_in_charge'].'and t.person_in_charge_id = b.user_id';
        // }elseif($args['person_responsible']){

        // }
        $criteria->condition = $condition;
        $criteria->params = $params;
        $rows = SafetyCheck::model()->findAll($criteria);
        // $rs['status'] = 0;
        // $rs['desc'] = '成功';
        // $rs['total_num'] = $total_num;
        // $rs['rows'] = $rows;

        return $rows;
    }

    public static function queryRankingList($page, $pageSize, $args = array()) {
        $start_date = Utils::MonthToCn($args['start_date']);
        $end_date = Utils::MonthToCn($args['end_date']);
        $first_sql = "SELECT count(b.user_id) as count,c.user_name,c.contractor_id,a.person_in_charge_id FROM bac_safety_check a,bac_violation_record b,bac_staff c where a.check_id = b.check_id and a.root_proid = '".$args['program_id']."' and b.user_id = c.user_id and a.apply_time like '".$start_date."%'  group by b.user_id order by count desc limit 10";
        $first_command = Yii::app()->db->createCommand($first_sql);
        $rows_1 = $first_command->queryAll();
        $second_sql = "SELECT count(b.user_id) as count,c.user_name,c.contractor_id,a.person_in_charge_id FROM bac_safety_check a,bac_violation_record b,bac_staff c where a.check_id = b.check_id and a.root_proid = '".$args['program_id']."' and b.user_id = c.user_id and a.apply_time like '".$end_date."%'  group by b.user_id order by count desc limit 10";

        $second_command = Yii::app()->db->createCommand($second_sql);
        $rows_2 = $second_command->queryAll();
        $y = 0;
        foreach($rows_1 as $i => $m){
            foreach($rows_2 as $j => $n) {
                if($m['user_name'] == $n['user_name']){
                    if($n['count'] > $m['count']) {
                        $def[$y]['count'] = $n['count'] - $m['count'];
                        $def[$y]['user_name'] = $m['user_name'];
                        $y++;
                    }
                }
            }
        }
        if(is_array($def)) {
            $max_value = $def[0]['count'];
            foreach ($def as $t => $s) {
                if ($max_value < $s['count']) {
                    $max_value = $s['count'];
                }
            }
            foreach ($def as $t => $s) {
                if ($max_value == $s['count']) {
                    $max[] = $s['user_name'];
                }
            }
            $rs['max'] = $max;
        }
        $start=$page*$pageSize; #计算每次分页的开始位置
        $count_1 = count($rows_1);
        $pagedata_1=array();
        $pagedata_1=array_slice($rows_1,$start,$pageSize);

        $count_2 = count($rows_2);
        $pagedata_2=array();
        $pagedata_2=array_slice($rows_2,$start,$pageSize);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num_1'] = $count_1;
        $rs['num_of_page'] = $pageSize;
        $rs['rows_1'] = $pagedata_1;
        $rs['total_num_2'] = $count_2;
        $rs['rows_2'] = $pagedata_2;

        //两数组进行比较,两个月同时出现的人次数相减，这些人继续比较得到增长最多者
        return $rs;
    }


    /**
     * 个人违规查询
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function queryListByUser($page, $pageSize, $args = array()) {
        //var_dump($args);
        $condition = '';
        $params = array();

        //Apply
        if ($args['check_id'] != '') {
            $condition .= 'check_id IN ('.$args['check_id'].')';
            //$params['program_id'] = '('.$args['program_id'].')';
        }

        //Contractor
        if ($args['contractor_id'] != ''){
            $condition.= ( $condition == '') ? ' contractor_id =:contractor_id ' : ' AND contractor_id =:contractor_id';
            $params['contractor_id'] = $args['contractor_id'];
        }

        $total_num = SafetyCheck::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'apply_time desc';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }
        $criteria->order = $order;
        $criteria->condition = $condition;
        $criteria->params = $params;
        $pages = new CPagination($total_num);
        $pages->pageSize = $pageSize;
        $pages->setCurrentPage($page);
        $pages->applyLimit($criteria);
        //var_dump($criteria);
        $rows = SafetyCheck::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    //查询安全单
    public static function detailList($check_id){

        $sql = "SELECT * FROM bac_safety_check WHERE  check_id = '".$check_id."' ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;

    }

    //根据时间日期查询安全单
    public static function quertListByTime($args){
        $contractor_id = Yii::app()->user->contractor_id;
        $sql = "SELECT * FROM bac_safety_check WHERE  contractor_id = '".$contractor_id."' AND apply_time >= '".$args['start_date']."' AND apply_time <= '".$args['end_date']."' AND status != 0";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }

    //修改路径
    public static function updatePath($check_id,$save_path) {
        $save_path = substr($save_path,18);
        $sql = "update bac_safety_check set save_path = '".$save_path."' where check_id = '".$check_id."' ";
        $command = Yii::app()->db->createCommand($sql);
        $command->execute();
    }

    //根据月份统计承包商下各项目违规次数
    public static function summaryByMonth() {
        $contractor_id = Yii::app()->user->contractor_id;
        $args['contractor_id'] = $contractor_id;
        $program_list = Program::McProgramList($args);
        $i = 0;
        $year = date('Y');
        foreach($program_list as $program_id => $program_name) {
            $sql = "select DATE_FORMAT(apply_time,'%c') as date,count(check_id) as cnt,root_proname from bac_safety_check where contractor_id = '" . $contractor_id . "' and root_proid = '" . $program_id . "' and apply_time like '" . $year ."%' group by DATE_FORMAT(apply_time,'%c') order by apply_time ";//var_dump($sql);
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
            if ($rows) {
                $i++;
                $j = 0;
                $data = array();
                foreach ($rows as $cnt => $list) {
                    $data[$j][0] = (int)$list['date'];
                    $data[$j][1] = (int)$list['cnt'];
                    $r[$i]['data'] = $data;
                    $r[$i]['label'] = $program_name;
                    $j++;
                }
            }
        }
        return $r;
    }

    //人员按申请,负责,成员进行统计
    public static function findBySummary($user_id,$program_id){
        $sql_1 = "SELECT a.safety_level,a.type_name,a.type_name_en,'Apply User' as deal_type, count(distinct a.check_id) as cnt
                  FROM bac_safety_check a
                  WHERE a.apply_user_id = '".$user_id."' and a.root_proid = '".$program_id."'
                  group by type_name";
                // UNION
        $sql_2="SELECT b.safety_level,b.type_name,b.type_name_en,'Person In Charge' as deal_type, count(distinct b.check_id) as cnt
                  FROM bac_safety_check b
                  WHERE b.person_in_charge_id = '".$user_id."' and b.root_proid = '".$program_id."'
                  group by type_name";
                // UNION

        $sql_3="SELECT d.safety_level,d.type_name,d.type_name_en,'Member' as deal_type, count(distinct c.check_id) as cnt
                  FROM bac_violation_record c inner join bac_safety_check d
                  on c.user_id = '".$user_id."' and c.check_id=d.check_id and d.root_proid = '".$program_id."'
                  group by d.type_name";
        $inspection_type = SafetyLevel::levelText();//安全检查安全等级
        $command1 = Yii::app()->db->createCommand($sql_1);
        $rows_1 = $command->queryAll();
        $command2 = Yii::app()->db->createCommand($sql_2);
        $rows_2 = $command->queryAll();
        $command3 = Yii::app()->db->createCommand($sql_3);
        $rows_3 = $command->queryAll();
        $rows = array_merge($rows_1, $rows_2,$rows_3);
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$key]['safety_level'] = $row['safety_level'];
                //$rs[$key]['safety_level'] = $inspection_type[$row['safety_level']];
                if (Yii::app()->language == 'zh_CN') {
                    $rs[$key]['type_name'] = $row['type_name'];
                }else{
                    $rs[$key]['type_name'] = $row['type_name_en'];
                }
                $rs[$key]['deal_type'] = $row['deal_type'];
                $rs[$key]['cnt'] = $row['cnt'];
            }
        }
        return $rs;
    }
    //统计总次数
    public static function cntBySummary($user_id,$program_id){
        $sql = "select count(DISTINCT aa.check_id) as cnt
                  from (  SELECT a.check_id,a.safety_level, 'Apply User' as deal_type
                  FROM bac_safety_check a
                  WHERE a.apply_user_id = '".$user_id."' and a.root_proid = '".$program_id."'
                UNION
                SELECT b.check_id,b.safety_level, 'Person In Charge' as deal_type
                  FROM bac_safety_check b
                  WHERE b.person_in_charge_id = '".$user_id."' and b.root_proid = '".$program_id."'
                UNION
                SELECT c.check_id,d.safety_level, 'Member' as deal_type
                  FROM bac_violation_record c inner join bac_safety_check d
                  on c.user_id = '".$user_id."' and c.check_id=d.check_id and d.root_proid = '".$program_id."'
                  )aa";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //人员按申请权限统计使用次数
    public static function findByApply($user_id,$program_id){
        $sql = "SELECT count(DISTINCT check_id) as apply_cnt,safety_level FROM bac_safety_check  WHERE apply_user_id = '".$user_id."' and root_proid = '".$program_id."' group by safety_level ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //人员按负责权限统计使用次数
    public static function findByCharge($user_id,$program_id){
        $sql = "SELECT count(DISTINCT check_id) as charge_cnt,safety_level FROM bac_safety_check  WHERE person_in_charge_id = '".$user_id."' and root_proid = '".$program_id."' group by safety_level ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //人员按成员统计使用次数
    public static function findByMember($user_id,$program_id){
        $sql = "SELECT b.safety_level, count(a.check_id) as cnt FROM bac_violation_record a,bac_safety_check b  where a.user_id = '".$user_id."' and a.check_id=b.check_id and b.root_proid = '".$program_id."' group by b.safety_level ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //统计使用总次数
    public static function findByAll($user_id,$program_id){
        $sql = "select count(DISTINCT aa.check_id) as cnt from (SELECT check_id FROM bac_safety_check WHERE root_proid = '".$program_id."' and (apply_user_id = '".$user_id."' or person_in_charge_id = '".$user_id."' ) UNION ALL SELECT c.check_id FROM bac_violation_record c,bac_safety_check d where c.user_id = '".$user_id."' and c.check_id=d.check_id and d.root_proid = '".$program_id."')aa ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //设备按成员统计使用次数
    public static function deviceByMember($device_id,$program_id){
        $sql = "SELECT b.safety_level,b.type_name,b.type_name_en, count(a.check_id) as cnt
                FROM bac_violation_device a inner join bac_safety_check b
                on a.device_id = '".$device_id."' and a.check_id=b.check_id and b.root_proid = '".$program_id."'
                group by b.type_name ";
        $inspection_type = SafetyLevel::levelText();//安全检查安全等级
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$key]['safety_level'] = $row['safety_level'];
                $rs[$key]['safety_level'] = $inspection_type[$row['safety_level']];
                if (Yii::app()->language == 'zh_CN') {
                    $rs[$key]['type_name'] = $row['type_name'];
                }else{
                    $rs[$key]['type_name'] = $row['type_name_en'];
                }
                $rs[$key]['cnt'] = $row['cnt'];
            }
        }
        return $rs;
    }
    //设备统计使用总次数
    public static function o($device_id,$program_id){
        $sql = "select count(DISTINCT a.check_id) as cnt
                FROM bac_violation_device a inner join bac_safety_check b
                on a.device_id = '".$device_id."' and a.check_id=b.check_id and b.root_proid = '".$program_id."' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }

    //下载PDF
    public static function downloadPDF($params,$app_id){
        $type = $params['type'];
        if($type == 'A'){
            if(array_key_exists('month_tag',$params)){
                if($params['month_tag'] == 1){
                    //生成月报
                    $filepath = self::downloadShsdMonthPDF($params,$app_id);
                }else{
                    $filepath = self::downloaddefaultPDF($params,$app_id);
                }
            }else{
                $filepath = self::downloaddefaultPDF($params,$app_id);
            }
        }else if($type == 'B'){
            if(array_key_exists('month_tag',$params)){
                if($params['month_tag'] == 1){
                    //生成月报
                    $filepath = self::downloadShsdMonthPDF($params,$app_id);
                }else{
                    $filepath = self::downloadShsdPDF($params,$app_id);
                }
            }else{
                $filepath = self::downloadShsdPDF($params,$app_id);
            }
        }
        return $filepath;
    }

    //下载PDF
    public  static function downloaddefaultPDF($params,$app_id){
        $check_id = $params['check_id'];
        //$a = SafetyCheckDetail::detailAllList();
        $check_list = SafetyCheck::detailList($check_id);//安全检查单
        $detail_list = SafetyCheckDetail::detailList($check_id);//安全检查单详情
        $level_list = SafetyLevel::levelText();//安全等级详情
        $type_list = SafetyCheckType::typeText();//安全类型详情
        $findings_list = SafetyCheckFindings::typeText();//检查类型
        $record_list = ViolationRecord::recordList($check_id);//违规记录
        $record_device_list = ViolationDevice::recordList($check_id);//设备违规记录
        $company_list = Contractor::compAllList();//承包商公司列表
        $deal_list = SafetyCheckDetail::dealList();//处理类型列表
        $device_type = DeviceType::deviceList();//设备类型
        $document_list = SafetyDocument::queryDocument($check_id);//文档列表
        $title = $check_list[0]['title'];//标题
        $contractor_id = $check_list[0]['contractor_id'];
        $contractor_name = $company_list[$contractor_id];//承包商名称
        $root_proname = $check_list[0]['root_proname'];//总包项目名称
        $root_proid = $check_list[0]['root_proid'];//总包项目ID
        $root_company = Program::ProgramCompany();//根据项目ID获取企业名称
        $block = $check_list[0]['block'];//一级区域
        $secondary_region = $check_list[0]['secondary_region'];//二级区域
        $description = $level_list[$check_list[0]['safety_level']];//安全等级描述
        $stipulation_time = $check_list[0]['stipulation_time'];//规定时间
        $person_in_charge_id = $check_list[0]['person_in_charge_id'];//负责人ID
        $person_in_charge = Staff::model()->findAllByPk($person_in_charge_id);//负责人
        $apply_user_id = $check_list[0]['apply_user_id'];//申请人ID
        $apply_user =  Staff::model()->findAllByPk($apply_user_id);//申请人
        $apply_time = $check_list[0]['apply_time'];//申请时间
        $close_time = $check_list[0]['close_time'];//关闭时间
        $violations_user = '';
        foreach($record_list as $n => $m){
            $record_user =  Staff::model()->findByPk($m['user_id']);//申请人
            $record_user_name = $record_user->user_name;
            $violations_user .= '  '.$record_user_name;
        }
        $lang = "_en";
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($apply_time,0,4);//年
        $month = substr($apply_time,5,2);//月
        $day = substr($apply_time,8,2);//日
        $hours = substr($apply_time,11,2);//小时
        $minute = substr($apply_time,14,2);//分钟
        $time = $day.$month.$year.$hours.$minute;
        $program_id = $check_list[0]['root_proid'];
        $filepath = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/'.$program_id.'/wsh/'.$contractor_id.'/WSH' . $check_id . $time .'.pdf';
        $full_dir = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/'.$program_id.'/wsh/'.$contractor_id;
        //$filepath = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/wsh'.'/WSH' . $check_id . '.pdf';
        //$full_dir = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/wsh'.'/WSH';
        if(!file_exists($full_dir))
        {
            umask(0000);
            @mkdir($full_dir, 0777, true);
        }
        SafetyCheck::updatepath($check_id,$filepath);
        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
        require_once($tcpdfPath);
        $pdf = new ReportPdf('P', 'mm', 'A4', true, 'UTF-8', false);

        //Yii::import('application.extensions.tcpdf.TCPDF');
        //$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);
        //$pdf->SetKeywords('PDF, LICEN');
        // 设置页眉和页脚信息
        $main_model = Contractor::model()->findByPk($contractor_id);
        $_SESSION['title'] = 'WSH Inspection Records No. (安全检查记录编号): ' . $check_id; // 把标题存在$_SESSION['user'] 里面
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
        //if($logo_pic){
        //$logo = '/opt/www-nginx/web'.$logo_pic;
        //$pdf->SetHeaderData($logo, 20,  'WSH Inspection Records No. (安全检查记录编号:)' . $check_id,  $contractor_name,array(0, 64, 255), array(0, 64, 128));
        //else{
        //$pdf->SetHeaderData('', 0, '', 'WSH Inspection Records No. (安全检查记录编号:)' . $check_id, array(0, 64, 255), array(0, 64, 128));
        //}
        $pdf->Header($logo_pic);
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
        $pdf->setCellPaddings(1,1,1,1);

        // 设置页眉和页脚字体
        $pdf->setHeaderFont(Array('droidsansfallback', '', '30')); //英文

        $pdf->setFooterFont(Array('helvetica', '', '10'));

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
        $roleList = Role::roleallList();//岗位列表
        $apply_role = $apply_user[0]['role_id'];//发起人角色
        //标题(许可证类型+项目)
        $pro_model = Program::model()->findByPk($root_proid);
        $program_name = $pro_model->program_name;
        $pro_params = $pro_model->params;//项目参数

        $apply_contractor_id = $apply_user[0]['contractor_id'];//申请人公司
        $pro_contractor_id = $pro_model->contractor_id;//总包公司

        if($pro_params != '0') {
            $pro_params = json_decode($pro_params, true);
            //判断是否是迁移的
            if (array_key_exists('transfer_con', $pro_params)) {
                if($apply_contractor_id == $pro_contractor_id){
                    $main_conid = $pro_params['transfer_con'];
                    $apply_contractor_id = $pro_params['transfer_con'];
                }else{
                    $main_conid = $pro_model->contractor_id;//总包编号
                }
            } else {
                $main_conid = $pro_model->contractor_id;//总包编号
            }
        }else{
            $main_conid = $pro_model->contractor_id;//总包编号
        }
        $title_html = "<h1 style=\"font-size: 300% \" align=\"center\">{$company_list[$main_conid]}</h1><br/><h2 style=\"font-size: 200%\" align=\"center\">Project (项目) : {$root_proname}</h2>
            <h2 style=\"font-size: 200%\" align=\"center\">WSH Inspection Title (安全检查标题): {$title}</h2><br/>";
        $pdf->writeHTML($title_html, true, false, true, false, '');
        $y = $pdf->GetY();

        //发起人详情
        $apply_time = Utils::DateToEn($apply_time);
        $apply_info_html = "<br/><br/><h2 align=\"center\">Initiator Details (发起人详情)</h2><table style=\"border-width: 1px;border-color:gray gray gray gray\">";
        $apply_info_html .="<tr><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Name (姓名)</td><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Designation (职位)</td><td height=\"20px\" width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">ID Number (身份证号码)</td></tr>";
        if($apply_user[0]['work_pass_type'] = 'IC' || $apply_user[0]['work_pass_type'] = 'PR'){
            if(substr($apply_user[0]['work_no'],0,1) == 'S' && strlen($apply_user[0]['work_no']) == 9){
                $work_no = 'SXXXX'.substr($apply_user[0]['work_no'],5,8);
            }else{
                $work_no = $apply_user[0]['work_no'];
            }
        }else{
            $work_no = $apply_user[0]['work_no'];
        }
        $apply_info_html .="<tr><td height=\"50px\" style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$apply_user[0]['user_name']}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$roleList[$apply_role]}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$work_no}</td></tr>";
        $apply_info_html .="<tr><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Company (公司)</td><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Date of Initiation (发起时间)</td><td height=\"20px\" width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Electronic Signature (电子签名)</td></tr>";
        $apply_info_html .="<tr><td height=\"50px\" style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$company_list[$apply_contractor_id]}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$apply_time}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;</td></tr>";
        $apply_info_html .="</table>";
        //判断电子签名是否存在 $add_operator->signature_path
        $apply_user_model = Staff::model()->findByPk($apply_user_id);
        $content = $apply_user_model->signature_path;
        //$content = '/opt/www-nginx/web/filebase/record/2018/04/sign/pic/sign1523249465052_1.jpg';
        if(file_exists($content)){
            $pdf->Image($content, 150, $y+50, 20, 9, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
        }
        $charge_role = $person_in_charge[0]['role_id'];//发起人角色
        //负责人详情
        $charge_info_html = "<br/><br/><h2 align=\"center\">Person In Charge Details (负责人详情)</h2><table style=\"border-width: 1px;border-color:gray gray gray gray\">";
        $charge_info_html .="<tr><td height=\"20px\" width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Name (姓名)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Designation (职位)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">ID Number (身份证号码)</td><td  width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Company (公司)</td></tr>";
        if($person_in_charge[0]['work_pass_type'] = 'IC' || $person_in_charge[0]['work_pass_type'] = 'PR'){
            if(substr($person_in_charge[0]['work_no'],0,1) == 'S' && strlen($person_in_charge[0]['work_no']) == 9){
                $work_no = 'SXXXX'.substr($person_in_charge[0]['work_no'],5,8);
            }else{
                $work_no = $person_in_charge[0]['work_no'];
            }
        }else{
            $work_no = $person_in_charge[0]['work_no'];
        }
        $charge_info_html .="<tr><td height=\"50px\" style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$person_in_charge[0]['user_name']}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$roleList[$charge_role]}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$work_no}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$company_list[$person_in_charge[0]['contractor_id']]}</td></tr>";
        $charge_info_html .="</table>";
        $charge_role = $person_in_charge[0]['role_id'];//发起人角色

        //安全检查详情
        if($check_list[0]['work_location']){
            if($block){
                $work_location = $block.',';
            }
            $work_location.= $check_list[0]['work_location'];
        }else{
            $work_location = $block.'--'.$secondary_region;
        }
        $work_content_html = "<br/><br/><h2 align=\"center\">WSH Inspection Details (安全检查详情)</h2><table style=\"border-width: 1px;border-color:gray gray gray gray\" >";
        $work_content_html .="<tr><td height=\"20px\" width=\"20%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Work Location<br>(工作地点)</td><td width=\"20%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Severity Level<br>(严重性等级)</td><td width=\"20%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Type of Inspection<br>(检查类型)</td><td width=\"20%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Type of Findings<br>(检查类型)</td><td width=\"20%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Expected Completion Time<br>(预计完成时间)</td></tr>";
        $work_content_html .="<tr><td height=\"50px\" style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">".$work_location."</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">".$check_list[0]['safety_level'].'-' .$description."</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">".$type_list[$check_list[0]['type_id']]. "</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">".$findings_list[$check_list[0]['findings_id']]. "</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">".Utils::DateToEn($check_list[0]['stipulation_time']). "</td></tr></table>";

        //责任人详情
        $responsible_info_html = "<br/><br/><h2 align=\"center\">Responsible Person(s) Details (责任人详情)</h2>";
        $responsible_info_html .= "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
        if($record_list) {
            foreach ($record_list as $n => $m) {
                $responsible_userid = $m['user_id'];
                $responsible_user = Staff::model()->findAllByPk($responsible_userid);//责任人
                $responsible_role = $responsible_user[0]['role_id'];//责任人角色
                if($responsible_user[0]['work_pass_type'] = 'IC' || $responsible_user[0]['work_pass_type'] = 'PR'){
                    if(substr($responsible_user[0]['work_no'],0,1) == 'S' && strlen($responsible_user[0]['work_no']) == 9){
                        $work_no = 'SXXXX'.substr($responsible_user[0]['work_no'],5,8);
                    }else{
                        $work_no = $responsible_user[0]['work_no'];
                    }
                }else{
                    $work_no = $responsible_user[0]['work_no'];
                }
                $responsible_info_html .= "<tr><td height=\"20px\" width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Name (姓名)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Designation (职位)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">ID Number (身份证号码)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Company (公司)</td></tr>";
                $responsible_info_html .= "<tr><td height=\"50px\" style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$responsible_user[0]['user_name']}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$roleList[$responsible_role]}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$work_no}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$company_list[$responsible_user[0]['contractor_id']]}</td></tr>";
            }
            $responsible_info_html .= "</table>";
        }else{
            $responsible_info_html .= "<tr><td height=\"20px\" width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Name (姓名)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Designation (职位)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">ID Number (身份证号码)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Company (公司)</td></tr>";
            $responsible_info_html .='<tr><td colspan="4" style="text-align: center;border-width: 1px;border-color:gray gray gray gray">Nil</td></tr>';
            $responsible_info_html .="</table>";
        }

        //设备详情
        $primary_list = Device::primaryAllList();
        $device_info_html = "<br/><br/><h2 align=\"center\">Equipment Details (设备详情)</h2>";
        $device_info_html .= "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
        if($record_device_list) {
            foreach ($record_device_list as $n => $m) {
                $device_id = $m['device_id'];
                $device_model = Device::model()->findByPk($device_id);//设备
                $type_no = $device_model->type_no;
                $devicetype_model = DeviceType::model()->findByPk($type_no);//设备类型信息
                $device_type_ch = $devicetype_model->device_type_ch;
                $device_type_en = $devicetype_model->device_type_en;
                $device_info_html .= "<tr><td height=\"20px\" width=\"10%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">S/N<br>(序号)</td><td height=\"20px\" width=\"30%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Registration No.<br>(设备编码)</td><td height=\"20px\" width=\"30%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Equipment Name <br>(设备名称)</td><td width=\"30%\"  nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Equipment Type <br>(设备类型)</td></tr>";
                $device_info_html .= "<tr><td height=\"50px\" style=\"text - align: center;border - width: 1px;border - color:gray gray gray gray\">&nbsp;1</td><td height=\"50px\" style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$primary_list[$device_id]}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$device_model->device_name}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$device_type_ch}<br>{$device_type_en}</td></tr>";
            }
            $device_info_html .= "</table>";
        }else{
            $device_info_html .= "<tr><td height=\"20px\" width=\"10%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">S/N<br>(序号)</td><td height=\"20px\" width=\"30%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Registration No.<br>(设备编码)</td><td height=\"20px\" width=\"30%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Equipment Name <br>(设备名称)</td><td width=\"30%\"  nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Equipment Type <br>(设备类型)</td></tr>";
            $device_info_html .='<tr><td colspan="4" style="text-align: center;border-width: 1px;border-color:gray gray gray gray">Nil</td></tr>';
            $device_info_html .="</table>";
        }

        //文档标签
        $document_html = '<br/><br/><h2 align="center">Attachment(s) (附件)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N (序号)</td><td  width="80%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Document Name (文档名称)</td></tr>';
        if(!empty($document_list)){
            $i =1;
            foreach($document_list as $cnt => $name){
                $document_html .='<tr><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $i . '</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $name . '</td></tr>';
                $i++;
            }
        }else{
            $document_html .='<tr><td colspan="2" style="text-align: center;border-width: 1px;border-color:gray gray gray gray">Nil</td></tr>';
        }
        $document_html .= '</table>';

        $info_x = 44;//X方向距离
        $info_y_1 = 227;//第一页Y方向距离
        $cnt_1 = 0;
        $cnt_2 = 0;

        $html_1 = $apply_info_html . $charge_info_html . $work_content_html;
        $pdf->writeHTML($html_1, true, false, true, false, '');
        $num = count($detail_list);

        if (!empty($detail_list)) {
            $pdf->AddPage();
        }
        $info_x_2 = $pdf->GetX()+2;//17
        $info_y_2 = $pdf->GetY()+13;//33
        $first_html = '<table style="border-width: 1px;border-color:lightslategray lightslategray lightslategray lightslategray"><tr><td><h2 align="center">Photo(s) - Before (照片 - 之前)</h2></td><td><h2 align="center">Photo(s) - After (照片 - 之后)</h2></td></tr><table style="border-width: 1px;border-color:lightslategray lightslategray lightslategray lightslategray"> <tr><td width="50%" border="1px"; height="840px"></td><td width="50%" border="1px"; height="840px"></td></tr></table>';
        //循环出0,1,5最后的一条记录
        if (!empty($detail_list)) {
            $content_before =[];
            $content_after = [];
            $pic = [];
            foreach ($detail_list as $key => $value) {
                if ($value['pic'] != '' && $value['pic'] != '-1') {
                    //检查单处理类型是0，1，5的都是发起，照片显示在before列
                    if ($value['deal_type'] =='0' || $value['deal_type'] =='1' || $value['deal_type'] =='5') {
                        if (strpos($value['pic'],'|') !== false) {
                            //$value['pic']里包含|==》有多张照片
                            $content_before = explode("|",$value['pic']);
                        }else{
                            //一条记录有一张图片
                            $content_before[0] = $value['pic'];
                        }
                    }else if ($value['deal_type'] =='2') {
                        //检查单是2的是回复，照片显示在after列
                        if (strpos($value['pic'],'|') !== false) {
                            //$value['pic']里包含|==》有多张照片
                            $content_after = explode("|",$value['pic']);    
                        }else{
                            //一条记录有一张图片
                            $content_after[0] = $value['pic'];
                        }
                    }
                }
            }
            //获取before照片数组
            if (isset($content_before)) {
                $n=0;
                foreach ($content_before as $key => $value) {
                    $pic[$n]['before'] = $value;
                    $n++;
                }
            }
            //获取after照片数组
            if (isset($content_after)) {
                $n=0;
                foreach ($content_after as $key => $value) {
                    $pic[$n]['after'] = $value;
                    $n++;
                }
            }
            //调整照片数组顺序
            ksort($pic);
            $i = 1;
            foreach ($pic as $k => $v) {
                if(count($v)==2){
                    //before和after都有照片,先一行一行判断，在前后判断
                    foreach ($v as $key => $content) {
                        if ($content != '' && $content != 'nil' && $content != '-1') {
                            if(file_exists($content)){
                                $img = $content;
                                $type = Utils::getReailFileType($img);
                                //$type = getimagesize($content);
                                //一页可放两行照片，if-第一行照片 else-第二行照片
                                if ($i%2!=0) {
                                    if ($key=='before') {
                                        if($type == 'jpg'){
                                            $pdf->Image($img, $info_x_2, $info_y_2, 85, 110, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                                        }else{
                                            $pdf->Image($img, $info_x_2, $info_y_2, 85, 110, 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);
                                        }
                                    }
                                    if ($key=='after') {
                                        if($type == 'jpg'){
                                            $pdf->Image($img, $info_x_2+90, $info_y_2, 85, 110, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                                        }else{
                                            $pdf->Image($img, $info_x_2+90, $info_y_2, 85, 110, 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);
                                        }
                                    }
                                }else{
                                    if ($key=='before') {
                                        if($type == 'jpg'){
                                            $pdf->Image($img, $info_x_2, $info_y_2+115, 85, 110, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                                        }else{
                                            $pdf->Image($img, $info_x_2, $info_y_2+115, 85, 110, 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);
                                        }
                                    }
                                    if($key=='after'){
                                        $pdf->writeHTML($first_html, true, false, true, false, '');
                                        if($type == 'jpg'){
                                            $pdf->Image($content, $info_x_2+90, $info_y_2+115, 85, 110, 'JPG', '', '', false, 300, '', false, false, 0, \false, false, false);
                                        }else{
                                            $pdf->Image($content, $info_x_2+90, $info_y_2+115, 85, 110, '', '', '', false, 300, '', false, false, 0, \false, false, false);
                                        }
                                        $pdf->AddPage();

                                    }
                                }
                            }
                        }
                    }
                }else{
                    //只有before有照片或只有after照片，先判断是前是后，在一行一行显示
                    foreach ($v as $key => $content) {
                        if ($content != '' && $content != 'nil' && $content != '-1') {
                            if(file_exists($content)){
                                $img = $content;
                                $type = Utils::getReailFileType($img);
                                //$type = getimagesize($content);
                                if ($key=='before') {
                                    if ($i%2!=0) {
                                        if($type == 'jpg'){
                                            $pdf->Image($img, $info_x_2, $info_y_2, 85, 110, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                                        }else{
                                            $pdf->Image($img, $info_x_2, $info_y_2, 85, 110, 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);
                                        }

                                    }else{
                                        $pdf->writeHTML($first_html, true, false, true, false, '');
                                        if($type == 'jpg'){
                                            $pdf->Image($img, $info_x_2, $info_y_2+115, 85, 110, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                                        }else{
                                            $pdf->Image($img, $info_x_2, $info_y_2+115, 85, 110, 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);
                                        }
                                        $pdf->AddPage();
                                    }
                                }else{
                                    if ($i%2!=0) {
                                        if($type == 'jpg'){
                                            $pdf->Image($img, $info_x_2+90, $info_y_2, 85, 110, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                                        }else{
                                            $pdf->Image($img, $info_x_2+90, $info_y_2, 85, 110, 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);
                                        }

                                    }else{
                                        $pdf->writeHTML($first_html, true, false, true, false, '');
                                        if($type == 'jpg'){
                                            $pdf->Image($img, $info_x_2+90, $info_y_2+115, 85, 110, 'JPG', '', '', false, 300, '', false, false, 0, \false, false, false);
                                        }else{
                                            $pdf->Image($img, $info_x_2+90, $info_y_2+115, 85, 110, 'PNG', '', '', false, 300, '', false, false, 0, \false, false, false);
                                        }
                                        $pdf->AddPage();
                                    }
                                }
                            }
                        }
                    }
                }
                $i++;
            }
            if (count($pic)%2==1) {
                $pdf->writeHTML($first_html, true, false, true, false, '');
                $pdf->AddPage();
            } 
        }
        //我注释的2019-04-03
        // if (!empty($detail_list)) {
        //     $pdf->AddPage();
        //
        $check_detail_html_2 = '<br/><br/><h2 align="center">WSH Inspection Process (安全检查流程)</h2><table style="border-width: 1px;border-color:lightslategray lightslategray lightslategray lightslategray">
            <tr><td  height="20px" width="10%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Step<br>(步骤)</td><td width="45%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Content<br>(内容)</td><td width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Correspondents<br>(对应人)</td><td width="25%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Date & Time<br>(日期&时间)</td></tr>';
        if (!empty($detail_list)){
            foreach ($detail_list as $cnt => $list) {
                if($list['step']%2==0) {
                    $check_detail_html_2 .= '<tr ><td height="80px"  style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $list['step'] . '</td><td style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $list['description'] . '</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' .$person_in_charge[0]['user_name'].'</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . Utils::DateToEn($list['record_time']) . '</td></tr>';
                }else{
                    $check_detail_html_2 .= '<tr ><td height="80px"  style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $list['step'] . '</td><td style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $list['description'] . '</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' .$apply_user[0]['user_name'].'</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . Utils::DateToEn($list['record_time']) . '</td></tr>';
                }
                $info_y_2 +=45;
            }
        }
        $check_detail_html_2 .= '</table>';
        $html_2 = $check_detail_html_2. $responsible_info_html . $device_info_html . $document_html;

        $pdf->writeHTML($html_2, true, false, true, false, '');
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
        // $title = $check_list[0]['title'];//标题
        // Utils::Download($filepath, $title, 'pdf');
        // echo $filepath;
        return $filepath;
        //============================================================+
        // END OF FILE
        //============================================================+
    }

    //下载PDF
    public static function downloadShsdPDF($params,$app_id){

        $check_id = $params['check_id'];
        //$a = SafetyCheckDetail::detailAllList();
        $check_list = SafetyCheck::detailList($check_id);//安全检查单
        $detail_list = SafetyCheckDetail::detailList($check_id);//安全检查单详情
        $level_list = SafetyLevel::levelText();//安全等级详情
        $type_list = SafetyCheckType::typeText();//安全类型详情
        $findings_list = SafetyCheckFindings::typeTextByplatform($check_list[0]['contractor_id']);//检查类型
        $record_list = ViolationRecord::recordList($check_id);//违规记录
        $company_list = Contractor::compAllList();//承包商公司列表
        $role_list = Role::roleList();

        $title = $check_list[0]['title'];//标题
        $contractor_id = $check_list[0]['contractor_id'];
        $root_proname = $check_list[0]['root_proname'];//总包项目名称
        $root_proid = $check_list[0]['root_proid'];//总包项目ID
        $pro_model = Program::model()->findByPk($root_proid);
        $program_name = $pro_model->program_name;//项目名称
        $contractor_id = $pro_model->contractor_id;
        $_SESSION['program_name'] = $program_name;
        $root_company = Program::ProgramCompany();//根据项目ID获取企业名称
        $description = $level_list[$check_list[0]['safety_level']];//安全等级描述
        $person_in_charge_id = $check_list[0]['person_in_charge_id'];//负责人ID
        $person_in_charge = Staff::model()->findAllByPk($person_in_charge_id);//负责人
        $apply_user_list = SafetyTeam::queryTeam($check_id);
        $apply_user_name = SafetyTeam::queryStaff($check_id);
        $apply_user_id = $check_list[0]['apply_user_id'];//申请人ID
        $apply_user =  Staff::model()->findAllByPk($apply_user_id);//申请人
        $role_id = $apply_user[0]['role_id'];
        $apply_sign_html= '<img src="'.$apply_user[0]['signature_path'].'" height="30" width="30"  /> ';
        $apply_contractor_id = $apply_user[0]['contractor_id'];
        $_SESSION['apply_user_name'] = $apply_user[0]['user_name'];
        $_SESSION['apply_user_name'] = '';
        foreach($apply_user_name as $i => $user_name){
            $_SESSION['apply_user_name'].= $user_name.' ';
        }
        $apply_time = $check_list[0]['apply_time'];//申请时间
        $_SESSION['apply_time'] = $apply_time;
        $_SESSION['month_tag'] = $params['month_tag'];
        $close_time = $check_list[0]['close_time'];//关闭时间
        $violations_user = '';
        foreach($record_list as $n => $m){
            $record_user =  Staff::model()->findAllByPk($m['user_id']);//申请人
            $record_user_name = $record_user->user_name;
            $violations_user .= '  '.$record_user_name;
        }
        $lang = "_en";
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($apply_time,0,4);//年
        $month = substr($apply_time,5,2);//月
        $day = substr($apply_time,8,2);//日
        $hours = substr($apply_time,11,2);//小时
        $minute = substr($apply_time,14,2);//分钟
        $time = $day.$month.$year.$hours.$minute;
        $program_id = $check_list[0]['root_proid'];
        $filepath = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/'.$program_id.'/wsh/'.$contractor_id.'/WSH' . $check_id . $time .'.pdf';
        $full_dir = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/'.$program_id.'/wsh/'.$contractor_id;
        if(!file_exists($full_dir))
        {
            umask(0000);
            @mkdir($full_dir, 0777, true);
        }
        SafetyCheck::updatepath($check_id,$filepath);
        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
        require_once($tcpdfPath);
        $pdf = new WshShsdPdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        //Yii::import('application.extensions.tcpdf.TCPDF');
        //$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);
        //$pdf->SetKeywords('PDF, LICEN');
        // 设置页眉和页脚信息
        $main_model = Contractor::model()->findByPk($contractor_id);
        $logo_pic = '/opt/www-nginx/web'.$main_model->remark;
        $_SESSION['title'] = 'WSH Inspection Records No. (安全检查记录编号): ' . $check_id; // 把标题存在$_SESSION['user'] 里面
        $_SESSION['contractor_id'] = $contractor_id;
        $_SESSION['contractor_name'] = $main_model->contractor_name;

        $pdf->Header();
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
        $pdf->setCellPaddings(1,1,1,1);

        // 设置页眉和页脚字体
        $pdf->setHeaderFont(Array('droidsansfallback', '', '30')); //英文

        $pdf->setFooterFont(Array('helvetica', '', '10'));

        //设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');

        //设置间距
        $pdf->SetMargins(15, 45, 15);
        $pdf->SetHeaderMargin(30);
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

        if (!empty($detail_list)) {
            $detail_count = count($detail_list);
            $content_before = explode('|', $detail_list[0]['pic']);//之前
            if($detail_count != 1){
                if($detail_list[$detail_count-2]['pic'] != '' && $detail_list[$detail_count-2]['pic'] != '-1'){
                    $content_after  = explode('|', $detail_list[$detail_count-2]['pic']);//之后
                }else{
                    $content_after = '';
                }
            }else{
                $content_after = '';
            }
            foreach($detail_list as $i => $j){
                if($j['deal_type'] == '2'){
                    if($j['pic'] != '' && $j['pic'] != '-1'){
                        $content_after  = explode('|', $j['pic']);
                    }
                }
            }

            $info_html = "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
            $info_html .="<tr><td  style=\"border-width: 1px;border-color:gray gray gray gray\" width=\"5%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">S/N</td><td  width=\"35%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Item</td><td  width=\"30%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Description</td><td  width=\"30%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">After rectification/improved</td></tr>";
            //$checker_pic_html = '<img src="'.$background_path.'" height="30" width="30"  /> ';

            $findings_detail = $findings_list[$check_list[0]['findings_id']];
            $remark = $detail_list[0]['remark'];
            $contractor_name = $company_list[$contractor_id];//承包商名称

            $n=0;
            foreach ($content_before as $key => $value) {
                // $img_array = explode('/',$value);
                // $index = count($img_array) -1;
                // $img_array[$index] = 'middle_'.$img_array[$index];
                // $thumb_img = implode('/',$img_array);
                // //压缩业务图片  middle开头
                // $stat = Utils::img2thumb($value, $thumb_img, $width = 0, $height = 500, $cut = 0, $proportion = 0);
                // if($stat){
                //     $img = $thumb_img;
                // }else{
                //     $img = $value;
                // }
                $img = $value;
                $list[$n]['before'] = $img;
                $n++;
            }
            $n=0;

            if(is_array($content_after)){
                foreach ($content_after as $key => $value) {
                    // $img_array = explode('/',$value);
                    // $index = count($img_array) -1;
                    // $img_array[$index] = 'middle_'.$img_array[$index];
                    // $thumb_img = implode('/',$img_array);
                    // //压缩业务图片  middle开头
                    // $stat = Utils::img2thumb($value, $thumb_img, $width = 0, $height = 500, $cut = 0, $proportion = 0);
                    // if($stat){
                    //     $img = $thumb_img;
                    // }else{
                    //     $img = $value;
                    // }
                    $img = $value;
                    $list[$n]['after'] = $img;
                    $n++;
                }
            }else{
                foreach ($content_before as $key => $value) {
                    $list[$n]['after'] = '';
                    $n++;
                }
            }
            foreach ($list as $i => $j){
                $c = $i+1;
                $before_pic= '<img src="'.$j['before'].'" height="120" width="120" />';
                if(array_key_exists('after',$j) && $j['after'] != ''){
                    $after_pic= '<img src="'.$j['after'].'" height="120" width="120" />';
                }else{
                    $after_pic = '';
                }
                $info_html .="<tr><td style=\"border-width: 1px;border-color:gray gray gray gray\" width=\"5%\"   nowrap=\"nowrap\"  align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$c}</td><td width=\"35%\" nowrap=\"nowrap\"  align=\"center\" style=\"border-width:1px;border-color:gray gray gray gray\">{$before_pic}</td><td width=\"30%\" nowrap=\"nowrap\"  align=\"left\" style=\"border-width:1px;border - color:gray gray gray gray\">Company: {$contractor_name}<br> Findings: {$findings_detail}<br> Recommendation: {$check_list[0]['Violation_record']}</td><td width=\"30%\" nowrap=\"nowrap\"  align=\"center\" style=\"border-width:1px;border-color:gray gray gray gray\">{$after_pic}</td></tr>";
            }
        }
        $info_html .="<tr><td  width=\"5%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">S/N</td><td  width=\"35%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">NAME</td><td  width=\"20%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">COMPANY</td><td  width=\"20%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">DESIGNATION</td><td  width=\"20%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">SIGNATURE</td></tr>";

        if(!empty($apply_user_list)){
            foreach($apply_user_list as $i => $user_id){
                $user_model  = Staff::model()->findByPk($user_id);
                $i = $i+1;
                $info_html .="<tr><td style=\"border-width: 1px;border-color:gray gray gray gray\" width=\"5%\" nowrap=\"nowrap\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">$i</td><td style=\"border-width: 1px;border-color:gray gray gray gray\" width=\"35%\" nowrap=\"nowrap\" align=\"center\" style=\"border-width: 1px;border - color:gray gray gray gray\">{$user_model->user_name}</td><td width=\"20%\" nowrap=\"nowrap\"  align=\"center\" style=\"border-width:1px;border-color:gray gray gray gray\">{$company_list[$user_model->contractor_id]}</td><td width=\"20%\" nowrap=\"nowrap\"  align=\"center\" style=\"border-width:1px;border - color:gray gray gray gray\">{$role_list[$user_model->role_id]}</td><td width=\"20%\" nowrap=\"nowrap\"  align=\"center\" style=\"border-width:1px;border-color:gray gray gray gray\"></td></tr>";
            }
        }



        $info_html .="</table>";
        $pdf->writeHTML($info_html, true, false, true, false, '');
        $table_2_2 = "A condition/practice with potential to cause death,permanent disablement or serious injury to personnel(fractures,eyes injury etc.) or extensive loss to property or process";
        $table_3_2 = "A condition/practice with the potential to cause minor injury or illnessto personnel(minor cuts.brulses lacerations etc.).minor property damage or loss to process,or contravention of a legislative requirement";
        $table_4_2 = "A condition/practice with the potential to personnel,or disruption to process";
        $table_2_3 = "Immediate";
        $table_3_3 = "1 to 3 Days(Depends of severity)";
        $table_4_3 = "7 Days";
        $table = "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
        $table .="<tr><td style=\"border-width: 1px;border-color:gray gray gray gray\" width=\"100%\" nowrap=\"nowrap\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Hazard Classification Table</td></tr>";
        $table .="<tr><td style=\"border-width: 1px;border-color:gray gray gray gray\" width=\"20%\" nowrap=\"nowrap\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"> <b>Hazard Classification</b> </td><td style=\"border-width: 1px;border-color:gray gray gray gray\" width=\"60%\" nowrap=\"nowrap\" align=\"center\" style=\"border-width: 1px;border - color:gray gray gray gray\"><b>Substandard Safety Practice/Condition</b></td><td width=\"20%\" nowrap=\"nowrap\"  align=\"center\" style=\"border-width:1px;border-color:gray gray gray gray\"> <b>Action Time</b> </td></tr>";
        $table .="<tr><td style=\"border-width: 1px;border-color:gray gray gray gray\" width=\"20%\" nowrap=\"nowrap\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">A</td><td style=\"border-width: 1px;border-color:gray gray gray gray\" width=\"60%\" nowrap=\"nowrap\" align=\"left\" style=\"border-width: 1px;border - color:gray gray gray gray\">{$table_2_2}</td><td width=\"20%\" nowrap=\"nowrap\"  align=\"center\" style=\"border-width:1px;border-color:gray gray gray gray\">{$table_2_3}</td></tr>";
        $table .="<tr><td style=\"border-width: 1px;border-color:gray gray gray gray\" width=\"20%\" nowrap=\"nowrap\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">B</td><td style=\"border-width: 1px;border-color:gray gray gray gray\" width=\"60%\" nowrap=\"nowrap\" align=\"left\" style=\"border-width: 1px;border - color:gray gray gray gray\">{$table_3_2}</td><td width=\"20%\" nowrap=\"nowrap\"  align=\"center\" style=\"border-width:1px;border-color:gray gray gray gray\">{$table_3_3}</td></tr>";
        $table .="<tr><td style=\"border-width: 1px;border-color:gray gray gray gray\" width=\"20%\" nowrap=\"nowrap\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">B</td><td style=\"border-width: 1px;border-color:gray gray gray gray\" width=\"60%\" nowrap=\"nowrap\" align=\"left\" style=\"border-width: 1px;border - color:gray gray gray gray\">{$table_4_2}</td><td width=\"20%\" nowrap=\"nowrap\"  align=\"center\" style=\"border-width:1px;border-color:gray gray gray gray\">{$table_4_3}</td></tr>";
        $table .="</table>";
        $pdf->Ln(10);
        $pdf->writeHTML($table, true, false, true, false, '');
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
    public static function downloadShsdMonthPDF($params,$app_id){

        $month = $params['month'];
        $program_id = $params['program_id'];
        $remark = $params['remark'];
        $_SESSION['month_tag'] = $params['month_tag'];
        $pro_model = Program::model()->findByPk($program_id);
        $program_name = $pro_model->program_name;//项目名称
        $contractor_id = $pro_model->contractor_id;
        $main_model = Contractor::model()->findByPk($contractor_id);
        $_SESSION['contractor_id'] = $contractor_id;
        $_SESSION['contractor_name'] = $main_model->contractor_name;
        $_SESSION['program_name'] = $program_name;
        $sql = "SELECT * FROM bac_safety_check WHERE root_proid = '$program_id' and apply_time like '%$month%'";
        $sql .= "  order by apply_time desc";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $startrow = $params['startrow'];
        $per_read_cnt = $params['per_read_cnt'];
        if($startrow != '' && $per_read_cnt != ''){
            $rows=array_slice($rows,$startrow,$per_read_cnt);
        }
        //$a = SafetyCheckDetail::detailAllList();

        $findings_list = SafetyCheckFindings::typeText();//检查类型
        $company_list = Contractor::compAllList();//承包商公司列表
        $role_list = Role::roleList();

        $title = 'Shsd Month Report';//标题

        $lang = "_en";
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }

        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
        require_once($tcpdfPath);
        $pdf = new WshShsdPdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        //Yii::import('application.extensions.tcpdf.TCPDF');
        //$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);
        //$pdf->SetKeywords('PDF, LICEN');
        // 设置页眉和页脚信息

        $pdf->Header();
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
        $pdf->setCellPaddings(1,1,1,1);

        // 设置页眉和页脚字体
        $pdf->setHeaderFont(Array('droidsansfallback', '', '30')); //英文

        $pdf->setFooterFont(Array('helvetica', '', '10'));

        //设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');

        //设置间距
        $pdf->SetMargins(15, 45, 15);
        $pdf->SetHeaderMargin(30);
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
        $year = substr($month,0,4);//年
        $month = substr($month,5,2);//月
        $filepath = Yii::app()->params['upload_tmp_path'] . '/wsh_'.$year.$month.'_'.$program_id.'/' . $startrow . '.pdf';
        $full_dir = Yii::app()->params['upload_tmp_path'] . '/wsh_'.$year.$month.'_'.$program_id.'/';
        if(!file_exists($full_dir))
        {
            umask(0000);
            @mkdir($full_dir, 0777, true);
        }
        //$filepath = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/'.$program_id.'/wsh/'.$contractor_id.'/WSH'  . $year . $month .'.pdf';
        $n = 0;
        $info_html = "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
        $info_html .= "<tr><td  style=\"border-width: 1px;border-color:gray gray gray gray\" width=\"5%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">S/N</td><td  width=\"35%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Item</td><td  width=\"30%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Description</td><td  width=\"30%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">After rectification/improved</td></tr>";
        foreach($rows as $i => $j){
            $check_list = SafetyCheck::detailList($j['check_id']);//安全检查单
            $detail_list = SafetyCheckDetail::detailList($j['check_id']);//安全检查单详情
            if (!empty($detail_list)) {
                $detail_count = count($detail_list);
                $content_before = explode('|', $detail_list[0]['pic']);//之前
                $content_after = explode('|', $detail_list[$detail_count - 2]['pic']);//之后
                //$checker_pic_html = '<img src="'.$background_path.'" height="30" width="30"  /> ';

                $findings_detail = $findings_list[$check_list[0]['findings_id']];
                $remark = $detail_list[0]['remark'];
                $contractor_name = $company_list[$check_list[0]['contractor_id']];//承包商名称

                $file_type = Utils::getReailFileType($content_before[0]);
                if($file_type == 'jpg'){
                    $before = $content_before[0];
                    // $img_array = explode('/',$before);
                    // $index = count($img_array) -1;
                    // $img_array[$index] = 'middle_'.$img_array[$index];
                    // $before_thumb_img = implode('/',$img_array);
                    // //压缩业务图片  middle开头
                    // $stat = Utils::img2thumb($before, $before_thumb_img, $width = 0, $height = 500, $cut = 0, $proportion = 0);
                    // if($stat){
                    //     $before_img = $before_thumb_img;
                    // }else{
                    //     $before_img = $before;
                    // }
                    $before_img = $before;
                    $after_img = 'https://shell.cmstech.sg/ctmgr/img/background.jpg';
                }else{
                    $before_img = 'https://shell.cmstech.sg/ctmgr/img/background.jpg';
                }
                $value = $content_after[0];
                if($value != '' && $value != '-1'){
                    $file_type = Utils::getReailFileType($value);
                    if($file_type == 'jpg'){
                        $after = $content_after[0];
                        // $img_array = explode('/',$after);
                        // $index = count($img_array) -1;
                        // $img_array[$index] = 'middle_'.$img_array[$index];
                        // $after_thumb_img = implode('/',$img_array);
                        // //压缩业务图片  middle开头
                        // $stat = Utils::img2thumb($after, $after_thumb_img, $width = 0, $height = 500, $cut = 0, $proportion = 0);
                        // if($stat){
                        //     $after_img = $after_thumb_img;
                        // }else{
                        //     $after_img = $after;
                        // }
                        $after_img = $after;
                    }
                }

                $c = $i+1;
                $before_pic= '<img src="'.$before_img.'" height="120" width="120" />';
                $after_pic= '<img src="'.$after_img.'" height="120" width="120" />';
                $info_html .="<tr><td style=\"border-width: 1px;border-color:gray gray gray gray\" width=\"5%\"   nowrap=\"nowrap\"  align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$c}</td><td width=\"35%\" nowrap=\"nowrap\"  align=\"center\" style=\"border-width:1px;border-color:gray gray gray gray\">{$before_pic}</td><td width=\"30%\" nowrap=\"nowrap\"  align=\"left\" style=\"border-width:1px;border - color:gray gray gray gray\">Company: {$contractor_name}<br> Findings: {$findings_detail}<br> Recommendation: {$check_list[0]['Violation_record']}</td><td width=\"30%\" nowrap=\"nowrap\"  align=\"center\" style=\"border-width:1px;border-color:gray gray gray gray\">{$after_pic}</td></tr>";
                $n++;
            }
        }
        $declare_remark = $params['remark'];
        $info_html .="<tr><td  width=\"100%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"left\" style=\"border-width: 1px;border-color:gray gray gray gray\">Note:<br>$declare_remark</td></tr>";
        $info_html .="<tr><td  width=\"5%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">S/N</td><td  width=\"35%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">NAME</td><td  width=\"20%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">COMPANY</td><td  width=\"20%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">DESIGNATION</td><td  width=\"20%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">SIGNATURE</td></tr>";
        $_SESSION['apply_user_name'] = '';
        foreach($rows as $i => $j) {
            $check_list = SafetyCheck::detailList($j['check_id']);//安全检查单
            $apply_user_id = $check_list[0]['apply_user_id'];//申请人ID
            $apply_user = Staff::model()->findAllByPk($apply_user_id);//申请人
            $role_id = $apply_user[0]['role_id'];
            $apply_contractor_id = $apply_user[0]['contractor_id'];
            $apply_sign_html = '<img src="' . $apply_user[0]['signature_path'] . '" height="30" width="30"  />';
            if (!empty($detail_list)) {
                $apply_user_name = $apply_user[0]['user_name'];
                $info_html .="<tr><td style=\"border-width: 1px;border-color:gray gray gray gray\" width=\"5%\" nowrap=\"nowrap\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">1</td><td style=\"border-width: 1px;border-color:gray gray gray gray\" width=\"35%\" nowrap=\"nowrap\" align=\"center\" style=\"border-width: 1px;border - color:gray gray gray gray\">{$apply_user[0]['user_name']}</td><td width=\"20%\" nowrap=\"nowrap\"  align=\"center\" style=\"border-width:1px;border-color:gray gray gray gray\">{$company_list[$apply_contractor_id]}</td><td width=\"20%\" nowrap=\"nowrap\"  align=\"center\" style=\"border-width:1px;border - color:gray gray gray gray\">{$role_list[$role_id]}</td><td width=\"20%\" nowrap=\"nowrap\"  align=\"center\" style=\"border-width:1px;border-color:gray gray gray gray\">{$apply_sign_html}</td></tr>";
            }
        }
        $_SESSION['apply_user_name'] .= $apply_user_name.'...';
        //$_SESSION['apply_user_name'] = substr($_SESSION['apply_user_name'], 0, strlen($_SESSION['apply_user_name']) - 1);
        $info_html .="</table>";

        $pdf->writeHTML($info_html, true, false, true, false, '');
        //输出PDF
        $pdf->Output($filepath, 'F');  //保存到指定目录
        //$pdf->Output($filepath, 'I');
        return $filepath;
//============================================================+
// END OF FILE
//============================================================+
    }

    //按项目查询安全检查次数（按类别分组）
    public static function AllNcrCntList($args){
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $pro_model =Program::model()->findByPk($args['program_id']);
        $month = Utils::MonthToCn($args['date']);
        $findings_list = SafetyCheckFindings::typeByContractor($args['program_id']);
        //分包项目
        if($pro_model->main_conid != $args['contractor_id']){
            $root_proid = $pro_model->root_proid;
            $sql = "select count(check_id) as cnt,root_proid,findings_id from bac_safety_check  where root_proid = '".$root_proid."' and apply_time like '".$month."%' and contractor_id = '".$args['contractor_id']."' and safety_level <> '0'  GROUP BY findings_id";
        }else{
            //总包项目
            $sql = "select count(check_id) as cnt,root_proid,findings_id from bac_safety_check where  apply_time like '".$month."%' and root_proid ='".$args['program_id']."' and safety_level <> '0'  GROUP BY findings_id";
        }
        if(!$args['contractor_id']){
            //总包项目
            $sql = "select count(check_id) as cnt,root_proid,findings_id from bac_safety_check where  apply_time like '".$month."%' and root_proid ='".$args['program_id']."' and safety_level <> '0'  GROUP BY findings_id";
        }
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        if(!empty($rows)){
            $count = count($rows);
            for($i=0;$i<$count;$i++){
                for($j=$i+1;$j<$count;$j++){
                    $tmp_cnt = $rows[$i]['cnt'];
                    $tmp_name = $rows[$i]['findings_id'];
                    if($rows[$i]['cnt'] < $rows[$j]['cnt'])
                    {
                        $rows[$i]['cnt'] = $rows[$j]['cnt'];
                        $rows[$i]['findings_id'] = $rows[$j]['findings_id'];
                        $rows[$j]['cnt'] = $tmp_cnt;
                        $rows[$j]['findings_id'] = $tmp_name;
                    }
                }
            }

            foreach($rows as $num => $list){
                $r[$num]['cnt'] = $list['cnt'];
                $r[$num]['type_name'] = $findings_list[$list['findings_id']];
            }
        }
        return $r;
    }

    //按项目查询安全检查次数（按类别分组）
    public static function AllGoodCntList($args){
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $pro_model =Program::model()->findByPk($args['program_id']);
        $month = Utils::MonthToCn($args['date']);
        $findings_list = SafetyCheckFindings::typeByContractor($args['program_id']);
        //分包项目
        if($args['contractor_id'] != '' && $pro_model->main_conid != $args['contractor_id']){
            $root_proid = $pro_model->root_proid;
            $sql = "select count(check_id) as cnt,root_proid,findings_id from bac_safety_check  where root_proid = '".$root_proid."' and apply_time like '".$month."%' and contractor_id = '".$args['contractor_id']."' and safety_level = '0'  GROUP BY findings_id";
        }else{
            //总包项目
            $sql = "select count(check_id) as cnt,root_proid,findings_id from bac_safety_check where  apply_time like '".$month."%' and root_proid ='".$args['program_id']."' and safety_level = '0'  GROUP BY findings_id";
        }
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if(!empty($rows)){
            $count = count($rows);
            for($i=0;$i<$count;$i++){
                for($j=$i+1;$j<$count;$j++){
                    $tmp_cnt = $rows[$i]['cnt'];
                    $tmp_name = $rows[$i]['findings_id'];
                    if($rows[$i]['cnt'] < $rows[$j]['cnt'])
                    {
                        $rows[$i]['cnt'] = $rows[$j]['cnt'];
                        $rows[$i]['findings_id'] = $rows[$j]['findings_id'];
                        $rows[$j]['cnt'] = $tmp_cnt;
                        $rows[$j]['findings_id'] = $tmp_name;
                    }
                }
            }

            foreach($rows as $num => $list){
                $r[$num]['cnt'] = $list['cnt'];
                $r[$num]['type_name'] = $findings_list[$list['findings_id']];
            }
        }
        return $r;
    }

    //按项目查询安全检查次数（按类别分组）
    public static function CntExcelList($args){
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $pro_model =Program::model()->findByPk($args['program_id']);
        $args['month'] = Utils::MonthToCn($args['month']);
        //$args['start_date'] = Utils::DateToCn($args['start_date']);
        //$args['end_date'] = Utils::DateToCn($args['end_date']) . " 23:59:59";
        $findings_list = SafetyCheckFindings::typeByContractor($args['program_id']);
        //分包项目
        if($pro_model->main_conid != $args['contractor_id']){
            $root_proid = $pro_model->root_proid;
            $sql = "select count(check_id) as cnt,root_proid,findings_id from bac_safety_check  where root_proid = '".$root_proid."' and apply_time like '%".$args['month']."%'  and contractor_id = '".$args['contractor_id']."'  GROUP BY findings_id";
        }else{
            //总包项目
            $sql = "select count(check_id) as cnt,root_proid,findings_id from bac_safety_check where  apply_time like '%".$args['month']."%' and root_proid ='".$args['program_id']."' GROUP BY findings_id";
        }
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if(!empty($rows)){
            foreach($rows as $num => $list){
                $r[$num]['cnt'] = $list['cnt'];
                $r[$num]['type_name'] = $findings_list[$list['findings_id']];
            }
        }
        return $r;
    }

    //按项目查询安全检查次数（按分包公司分组）
    public static function ScCompanyExcelList($args){
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $pro_model =Program::model()->findByPk($args['program_id']);
        $contractor_list = Contractor::compAllList();
        //$args['start_date'] = Utils::DateToCn($args['start_date']);
        //$args['end_date'] = Utils::DateToCn($args['end_date']) . " 23:59:59";
        $args['month'] = Utils::MonthToCn($args['month']);

        //分包项目
        if($pro_model->main_conid != $args['contractor_id']){
            $root_proid = $pro_model->root_proid;
            $sql = "select count(check_id) as cnt,contractor_id from bac_safety_check  where  apply_time like '%".$args['month']."%' and  root_proid ='".$root_proid."' and contractor_id = '".$args['contractor_id']."'  GROUP BY contractor_id";
            //$sql = "select count(check_id) as cnt,contractor_id from bac_safety_check  where  apply_time >='".$args['start_date']."' and apply_time <='".$args['end_date']."' and root_proid ='".$args['program_id']."' and contractor_id = '".$args['contractor_id']."'  GROUP BY contractor_id";
        }else{
            //总包项目
            $sql = "select count(check_id) as cnt,contractor_id from bac_safety_check  where  apply_time like '%".$args['month']."%' and root_proid ='".$args['program_id']."'  GROUP BY contractor_id";
        }


        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if(!empty($rows)){
            foreach($rows as $num => $list){
                //if($list['contractor_id'] != $args['contractor_id']){
                $r[$num]['cnt'] = $list['cnt'];
                $r[$num]['contractor_name'] = $contractor_list[$list['contractor_id']];
                //}
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
            $sql = "select count(check_id) as cnt,contractor_id from bac_safety_check  where  apply_time like '".$month."%' and root_proid ='".$root_proid."' and contractor_id = '".$args['contractor_id']."'  GROUP BY contractor_id";
        }else{
            //总包项目
            $sql = "select count(check_id) as cnt,contractor_id from bac_safety_check  where  apply_time like '".$month."%' and root_proid ='".$args['program_id']."'  GROUP BY contractor_id";
        }


        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if(!empty($rows)){
            foreach($rows as $num => $list){
                $r[$num]['cnt'] = $list['cnt'];
                $r[$num]['contractor_name'] = $contractor_list[$list['contractor_id']];
            }
        }
        return $r;
    }
    //按项目查询安全检查次数（按分包公司分组）
    public static function ScCompanyCntList($args){
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $pro_model =Program::model()->findByPk($args['program_id']);
        $month = Utils::MonthToCn($args['date']);
        $contractor_list = Contractor::compAllList();

        //分包项目
        if($pro_model->main_conid != $args['contractor_id']){
            $root_proid = $pro_model->root_proid;
            $sql = "select count(check_id) as cnt,contractor_id from bac_safety_check  where  apply_time like '".$month."%' and root_proid ='".$root_proid."' and contractor_id = '".$args['contractor_id']."'  GROUP BY contractor_id";
        }else{
            //总包项目
            $sql = "select count(check_id) as cnt,contractor_id from bac_safety_check  where  apply_time like '".$month."%' and root_proid ='".$args['program_id']."'  GROUP BY contractor_id";
        }


        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if(!empty($rows)){
            foreach($rows as $num => $list){
                    //if($list['contractor_id'] != $args['contractor_id']){
                    $r[$num]['cnt'] = $list['cnt'];
                    $r[$num]['contractor_name'] = $contractor_list[$list['contractor_id']];
                    //}
            }
        }
        return $r;
    }

    //对比分析得出结论(项目类别统计图)
    public static function ShowProgramData($r,$tag,$date){
        $max = $r[0]['cnt'];
        foreach($r as $cnt => $v1){
            if($max < $v1['cnt']){
                $max = $v1['cnt'];
            }
        }
        $data['type_name'] = '';
        foreach($r as $cnt => $v1){
            if($max == $v1['cnt']){
                $data['cnt'] = $v1['cnt'];
                $data['type_name'].= $v1['type_name'].' ';
                $data['date'] = $date;
            }
        }

        if($tag == '1'){
            if (Yii::app()->language == 'zh_CN'){
                $data['tag'] = Yii::t('comp_safety','in').$date.'，'.$data['type_name'].Yii::t('comp_safety','project_category_alert');
            }else{
                $data['tag'] = Yii::t('comp_safety','in').' '.$date.','.$data['type_name'].' '.Yii::t('comp_safety','project_category_alert');
            }
        }else{
            if (Yii::app()->language == 'zh_CN'){
                $data['tag'] = Yii::t('comp_safety','in').$date.'，'.$data['type_name'].Yii::t('comp_safety','good_practices_alert');
            }else{
                $data['tag'] = Yii::t('comp_safety','in').' '.$date.','.$data['type_name'].' '.Yii::t('comp_safety','good_practices_alert');
            }
        }
        return $data;
    }

    //对比分析得出结论(公司统计图)
    public static function ShowCompanyData($r,$date){
        $max = $r[0]['cnt'];
        foreach($r as $cnt => $v1){
            if($max < $v1['cnt']){
                $max = $v1['cnt'];
            }
        }
        $data['contractor_name'] = '';
        foreach($r as $cnt => $v1){
            if($max == $v1['cnt']){
                $data['cnt'] = $v1['cnt'];
                $data['contractor_name'].= $v1['contractor_name'].' ';
                $data['date'] = $date;
            }
        }
        if (Yii::app()->language == 'zh_CN'){
            $data['tag'] = Yii::t('comp_safety','in').$date.'，'.$data['contractor_name'].' '.Yii::t('comp_safety','company_category_alert');
        }else{
            $data['tag'] = Yii::t('comp_safety','in').' '.$date.', '.$data['contractor_name'].' '.Yii::t('comp_safety','company_category_alert');
        }
        return $data;
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
        $sql = '';

        if ($args['type_id'] != '') {
            $type_id = $args['type_id'];
            $condition .= "and a.type_id = '$type_id'";
        }

        //Findings_id
        if ($args['findings_id'] != '') {
            $findings_id = $args['findings_id'];
            $condition .= "and a.findings_id = '$findings_id'";
        }
        //safety_level
        if ($args['safety_level'] != '') {
            $safety_level = $args['safety_level'];
            $condition .= "and a.safety_level = '$safety_level'";
        }


        if ($args['start_date'] != '') {
            $start_date = Utils::DateToCn($args['start_date']);
            $condition .= "and a.apply_time >='$start_date' ";
        }

        if ($args['end_date'] != '') {
            $end_date = Utils::DateToCn($args['end_date']);
            $condition .= "and a.apply_time <='$end_date 23:59:59' ";
        }

        if ($args['month'] != '') {
            $month = Utils::MonthToCn($args['month']);
            $condition .= "and a.apply_time like '$month%' ";
        }

        if ($args['status'] != '') {
            $status = $args['status'];
            if($status == '99'){
                $condition .=" and a.status='0' and a.current_step%2=0 ";
            }else{
                $condition .= "and a.status = '$status'";
            }
        }

        if ($args['program_id'] != '') {
            $pro_model =Program::model()->findByPk($args['program_id']);
            //分包项目
            if($pro_model->main_conid != $args['contractor_id']){
                $root_proid = $pro_model->root_proid;
                $args['program_id'] = $root_proid;
                $args['con_id'] = Yii::app()->user->contractor_id;
            }
        }

        if ($args['con_id'] != '') {
            $sql = "SELECT
                a.check_id, a.root_proid, a.contractor_id, a.title, a.type_id, a.findings_id, a.findings_name_en, a.contractor_id, c.description_en as safety_level, c.description as safety_level_cn, a.stipulation_time, a.person_in_charge_id, a.apply_user_id, a.apply_time, b.contractor_name,
                IF(a.status='0' && a.current_step%2=0, '99', a.status) as status,a.block,a.secondary_region,
                (CASE a.status WHEN '2' THEN '1' ELSE a.status END) as order_str
            FROM
                bac_safety_check a
            JOIN bac_contractor b ON a.contractor_id = b.contractor_id 
            LEFT JOIN bac_safety_level c ON a.safety_level = c.safety_level
            JOIN bac_staff d ON a.apply_user_id = d.user_id ";
            //发起人
            if($args['initiator'] !=''){
                $initiator = $args['initiator'];
                $sql .= " and d.user_name like '$initiator%'";
            }
            //$sql.=" or d.contractor_id = '".$args['con_id']."' and a.apply_user_id = d.user_id ";
            //负责人
            if($args['person_in_charge'] !=''){
                $person_in_charge = $args['person_in_charge'];
                $sql .= " JOIN bac_satff e ON e.user_name like '$person_in_charge%' and a.person_in_charge_id = e.user_id ";
            }
            //责任人
            if($args['person_responsible'] !=''){
                $model = Staff::model()->find('user_name=:user_name',array(':user_name'=>$args['person_responsible']));
                if($model) {
                    $person_responsible = $model->user_id;
                    $sql.= "  join bac_violation_record f on a.check_id = f.check_id and f.user_id = '".$person_responsible."'";
                }else{
                    $sql.= "  join bac_violation_record f on a.check_id = f.check_id and f.user_id = ''";
                }
            }
            $sql.= "  WHERE 
                a.root_proid = '".$args['program_id']."' and a.contractor_id = '".$args['con_id']."'  $condition
            order by a.apply_time desc";
        }else{
            $sql = "SELECT
                a.check_id, a.root_proid, a.contractor_id, a.title, a.type_id, a.findings_id, a.findings_name_en, a.contractor_id, c.description_en as safety_level, c.description as safety_level_cn, a.stipulation_time, a.person_in_charge_id, a.apply_user_id, a.apply_time, b.contractor_name,
                IF(a.status='0' && a.current_step%2=0, '99', a.status) as status,a.block,a.secondary_region,
                (CASE a.status WHEN '2' THEN '1' ELSE a.status END) as order_str
            FROM
                bac_safety_check a
            JOIN bac_contractor b ON a.contractor_id = b.contractor_id 
            LEFT JOIN bac_safety_level c ON a.safety_level = c.safety_level
            JOIN bac_staff d ON a.apply_user_id = d.user_id  ";
            //发起人
            if($args['initiator'] !=''){
                $initiator = $args['initiator'];
                $sql .= " and d.user_name like '$initiator%'";
            }

            //负责人
            if($args['person_in_charge'] !=''){
                $person_in_charge = $args['person_in_charge'];
                $sql .= " JOIN bac_staff e ON e.user_name like '$person_in_charge%' and a.person_in_charge_id = e.user_id ";
            }
            //责任人
            if($args['person_responsible'] !=''){
                $model = Staff::model()->find('user_name=:user_name',array(':user_name'=>$args['person_responsible']));
                if($model) {
                    $person_responsible = $model->user_id;
                    $sql.= "  join bac_violation_record e on a.check_id = e.check_id and e.user_id = '".$person_responsible."'";
                }else{
                    $sql.= "  join bac_violation_record e on a.check_id = e.check_id and e.user_id = ''";
                }
            }
            $sql.= " WHERE 
                a.root_proid = '".$args['program_id']."' $condition
            order by a.apply_time desc";
        }

        $command = Yii::app()->db->createCommand($sql);
        $retdata = $command->queryAll();

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['rows'] = $retdata;

        return $rs;
    }

    //获取记录数
    public static function RecordCnt($args){
        $program_id = $args['id'];
        $month = Utils::MonthToCn($args['month']);
        $sql = "SELECT count(*) as cnt FROM bac_safety_check WHERE root_proid = '$program_id' and apply_time like '%$month%'";
        $sql .= "  order by apply_time desc";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $data['cnt'] = $rows[0]['cnt'];
        return $data;
    }

    //根据记录数生成pdf
    public static function RecordPdf($args){
        $program_id = $args['id'];
        $remark = $args['remark'];
        $month = Utils::MonthToCn($args['month']);
        $app_id = 'WSH';
        $pro_model = Program::model()->findByPk($program_id);
        $pro_params = $pro_model->params;//项目参数
        if($pro_params != '0') {
            $pro_params = json_decode($pro_params, true);
            //判断是否是迁移的
            if (array_key_exists('wsh_report', $pro_params)) {
                $params['type'] = $pro_params['wsh_report'];
            } else {
                $params['type'] = 'A';
            }
        }else{
            $params['type'] = 'A';
        }
        $params['program_id'] = $program_id;
        $params['remark'] = $remark;
        $params['month'] = $month;
        $params['month_tag'] = 1;
        $params['startrow'] = $args['startrow'];
        $params['per_read_cnt'] = $args['per_read_cnt'];
        $filepath = DownloadPdf::transferDownload($params,$app_id);

        return $filepath;
    }
}
