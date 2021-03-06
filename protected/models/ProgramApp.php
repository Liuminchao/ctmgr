<?php

/**
 * 项目权限
 * @author Liumc
 */
class ProgramApp extends CActiveRecord {

    const STATUS_NORMAL = '0'; //正常
    const STATUS_STOP = '1'; //停用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_program_app';
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
            self::STATUS_NORMAL => Yii::t('sys_role', 'STATUS_NORMAL'),
            self::STATUS_STOP => Yii::t('sys_role', 'STATUS_STOP'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_NORMAL => 'label-success', //正常
            self::STATUS_STOP => ' label-danger', //停用
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

        //contractor_id
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' contractor_id=:contractor_id' : ' AND contractor_id=:contractor_id';
            $params['contractor_id'] = $args['contractor_id'];
        }

        //app_id
        if ($args['app_id'] != '') {
            $condition.= ( $condition == '') ? ' app_id=:app_id' : ' AND app_id=:app_id';
            $params['app_id'] = $args['app_id'];
        }

        //Status
        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }

        $total_num = CompanyApp::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'record_time ASC';
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
        $rows = CompanyApp::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    //编辑数据
    public static function editProgramApp($args) {
        $status = self::STATUS_NORMAL;
        $sql = 'DELETE FROM bac_program_app WHERE program_id=:program_id';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":program_id", $args['program_id'], PDO::PARAM_INT);
        $rs = $command->execute();
        $date = date('Y-m-d H:i:s',time());//获取精确时间
        if(array_key_exists('sc_list', $args)) {
            foreach ($args["sc_list"] as $key => $app_id) {
                $sql = 'INSERT INTO bac_program_app(program_id,app_id,status) VALUES(:program_id,:app_id,:status)';
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":program_id", $args['program_id'], PDO::PARAM_INT);
                $command->bindParam(":app_id", $app_id, PDO::PARAM_STR);
                $command->bindParam(":status", $status, PDO::PARAM_STR);

                $rs = $command->execute();
            }
        }

        if ($rs) {
//                OperatorLog::savelog(OperatorLog::MODULE_ID_MAINCOMP, Yii::t('comp_company', 'Add Company'), self::insertLog($model, $contractor_id));

            $r['msg'] = Yii::t('common', 'success_update');
            $r['status'] = 1;
            $r['refresh'] = true;
        } else {
            $r['msg'] = Yii::t('common', 'error_update');
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }


    //查询项目中已经选择的模块
    public static function myAppList($program_id,$app) {
        $status = self::STATUS_NORMAL;
        if($app == ''){
            $sql = "SELECT a.app_id FROM bac_program_app a left join bac_app b on a.app_id = b.app_id 
              WHERE a.program_id=:program_id and a.status =:status";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":program_id", $program_id, PDO::PARAM_INT);
            $command->bindParam(":status", $status, PDO::PARAM_INT);
            $rows = $command->queryAll();
        }else{
            $sql = "SELECT a.app_id FROM bac_program_app a left join bac_app b on a.app_id = b.app_id 
              WHERE a.program_id=:program_id and a.status =:status and b.app = :app ";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":program_id", $program_id, PDO::PARAM_INT);
            $command->bindParam(":status", $status, PDO::PARAM_INT);
            $command->bindParam(":app", $app, PDO::PARAM_INT);
            $rows = $command->queryAll();
        }
        foreach($rows as $key => $value){
            $rs[$value['app_id']] = $value['app_id'];
        }
        if($app == '2' && count($rs)==0){
            $rs['EPSS'] = 'EPSS';
        }
        return $rs;
    }

    public static function myModuleList($program_id) {
        $sql = "select
                app_id,ifnull(start_date, '') as start_date, ifnull(end_date, '') as end_date, modules, status
            from
                bac_program_app
            where
                program_id = :program_id and app_id in ('SAF', 'EPSS')";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":program_id", $program_id, PDO::PARAM_INT);
        $rows = $command->queryAll();
        return $rows;
    }
    //根据项目id和app(SAF)查询项目的is_lite字段（是完整版还是lite版）
    public static function getIslite($program_id) {
        $pro_model = Program::model()->findByPk($program_id);
        $root_proid = $pro_model->root_proid;

        $sql = "select is_lite
            from
                bac_program_app
            where
                program_id = :program_id and app_id ='SAF' ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":program_id", $root_proid, PDO::PARAM_INT);
        $rows = $command->queryAll();
        if (empty($rows)) {
            $rows=[];
            $rows[0]['is_lite']='0';
        }
        return $rows[0];
    }
}
