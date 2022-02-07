<?php

/**
 * 工资计算
 * @author LiuMinchao
 */
class SalaryController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';
//    const STATUS_NORMAL = 0; //正常

    public function init() {
        parent::init();
        
        $this->bigMenu = Yii::t('pay_payroll', 'bigMenu');
    }
    /**
     * 汇总工资
     */
    public function actionList() {
        $this->contentHeader = Yii::t('pay_payroll', 'contentHeader_salary_calculate');
        $this->smallHeader = Yii::t('pay_payroll', 'smallHeader Salary List');
//        $args = array(
//            'ation_type'  =>  'PRC',
//        );
        $this->render('list');
    }
    /**
     * 详细工资
     */
    public function actionDetailList() {
        $summary_id = $_GET['summary_id'];
        $start_date = $_GET['start_date'];
//        var_dump($summary_id);
//        exit;
//        if($summary_id <> '')
//            $father_model = Program::model()->findByPk($father_proid);
        $this->contentHeader = Yii::t('pay_payroll', 'contentHeader_salary_calculate');
        $this->smallHeader = Yii::t('pay_payroll', 'smallHeader Salary List');
       
        $this->render('detaillist', array('summary_id'=>$summary_id,'start_date'=>$start_date));
    }
     /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=payroll/salary/grid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('pay_payroll', 'start_date'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'end_date'), '', '');
	$t->set_header(Yii::t('comp_staff','User_name'), '', '');
//        $t->set_header(Yii::t('pay_payroll', 'wage'), '', '');
//        $t->set_header(Yii::t('pay_payroll', 'work_hours'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'basic_wage'), '', '');
//        $t->set_header(Yii::t('pay_payroll', 'wage_overtime'), '', '');
//        $t->set_header(Yii::t('pay_payroll', 'overtime_hours'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'overtime_wage'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'allowance'), '', '');
//        $t->set_header(Yii::t('pay_payroll', 'deduction_wage'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'total_wage'), '', '');
        $t->set_header(Yii::t('pay_payroll','record_time'),'','');
        $t->set_header(Yii::t('pay_payroll', 'status'), '', '');
        $t->set_header(Yii::t('comp_staff', 'Action'), '', '');
        return $t;
    }
    /**
     * 查询
     */
    public function actionGrid() {
        //var_dump($_GET['page']);
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
//        var_dump($args);
       
        $t = $this->genDataGrid();
        $this->saveUrl();
//        var_dump($_SERVER);
        if($args['month']){
            $month = Utils::MonthToCn($args['month']);
            $args['month'] = $month;
        }
        $args['status'] = PayrollSalary::STATUS_DISABLE;
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
//        $programlist = PayrollWorkHour::programList($args);
        $list = PayrollSalarysummary::queryList($page, $this->pageSize, $args);
//        var_dump($list);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'],'wage_rows' => $wage_list,'ation_type'=>$ation_type, 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 计算工资
     */
    public function actioncalculate() {
        $this->smallHeader = Yii::t('pay_payroll', 'contentHeader_salary_calculate');
        $this->contentHeader = Yii::t('pay_payroll', 'contentHeader_salary_calculate');
        $model = new PayrollSalary();
        $r = array();
        $this->renderPartial('calculate_form', array('model' => $model, 'msg' => $r));
    }
    public function actionAcalculate() {
        if (isset($_REQUEST['PayrollSalary'])) {          
            $args = $_REQUEST['PayrollSalary'];
        }
        $r = array();
        $r = PayrollSalary::calculateStaff($args);

        echo json_encode($r);
    }
    /**
     * 工资入库
     */
    public function actionstorage() {
        $this->smallHeader = Yii::t('pay_payroll', 'contentHeader_salary_storage');
        $model = new PayrollSalary();
        $r = array();
        $this->renderPartial('storage_form', array('model' => $model, 'msg' => $r));
    }
    public function actionastorage() {
        if (isset($_REQUEST['Storage'])) {
            $args = $_REQUEST['Storage'];
        }
        $r = array();
        $r = PayrollSalary::storage($args);
        
        echo json_encode($r);
    }
     /**
     * 表头
     * @return SimpleGrid
     */
    private function genDetailGrid($summary_id,$start_date) {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=payroll/salary/detailgrid&summary_id='.$summary_id.'&start_date='.$start_date;
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('pay_payroll', 'wage_date'), '', '');
	$t->set_header(Yii::t('comp_staff','User_name'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'wage'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'work_hours'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'basic_wage'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'wage_overtime'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'overtime_hours'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'overtime_wage'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'allowance'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'allowance_content'), '', '');
//        $t->set_header(Yii::t('pay_payroll', 'deduction_wage'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'total_wage'), '', '');
        $t->set_header(Yii::t('pay_payroll','record_time'),'','');
        $t->set_header(Yii::t('pay_payroll', 'status'), '', '');
        $t->set_header(Yii::t('comp_staff', 'Action'), '', '');
        return $t;
    }
    /**
     * 查询
     */
    public function actionDetailGrid($summary_id,$start_date) {
//        var_dump($summary_id);
//        exit;
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        if($_REQUEST['summary_id']){
            $summary_id = $_REQUEST['summary_id'];
        }
        $t = $this->genDetailGrid($summary_id,$start_date);
        $this->saveUrl();
//        var_dump($args);
//        exit;
        $args['summary_id'] = $summary_id;
        $args['start_date'] = $start_date;
        $args['status'] = PayrollSalary::STATUS_DISABLE;
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
//        $programlist = PayrollWorkHour::programList($args);
        $list = PayrollSalary::queryList($page, $this->pageSize, $args);
//        var_dump($list);
        $this->renderPartial('detail_list', array('t' => $t, 'rows' => $list['rows'],'wage_rows' => $wage_list,'ation_type'=>$ation_type, 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 编辑详细信息
     */
    public function actionEdit() {
        $this->smallHeader = Yii::t('pay_payroll', 'smallHeader Edit');
        $this->contentHeader = Yii::t('pay_payroll', 'contentHeader_salary_calculate');
        $user_id = trim($_REQUEST['user_id']);
        $user_name = trim($_REQUEST['user_name']);
        $wage_date = $_REQUEST['wage_date'];
        $month = substr($wage_date,0,6);
//        PayrollSalary::settableName($month);
        $model = new PayrollSalary($month);
        $table = PayrollSalary::createSalaryTable($month);
        $summary_id = $_REQUEST['summary_id'];
        $r = array();
        if (isset($_POST['PayrollSalary'])) {
            $args = $_POST['PayrollSalary'];
            $args['user_id'] = $user_id;
            $args['wage_date'] = $wage_date;
//            var_dump($args);
//            exit;
            $r = PayrollSalary::updateDetail($args);
            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['PayrollSalary'];
            }
        }
        $rgs = array();
        $rgs = PayrollSalary::model()->find('user_id=:id AND wage_date=:date', array(':id' => $user_id,':date'=>$wage_date));
//        $sql ="select * from ".$table."  where user_id='".$user_id."' AND wage_date='".$wage_date."'";
//        $command = Yii::app()->db->createCommand($sql);
//        $t = $command->queryAll();
        $model->_attributes = PayrollSalary::model()->find('user_id=:id AND wage_date=:date', array(':id' => $user_id,':date'=>$wage_date));
//        exit;
        $this->render('_form', array('model' => $model, 'msg' => $r,'summary_id'=>$summary_id,'rgs' => $rgs,'user_id'=>$user_id,'user_name'=>$user_name,'wage_date'=>$wage_date));
    }
    /**
     * 根据电话查询员工名称
     */
    public function actionQueryuser() {
        $user_phone = $_POST['user_phone'];
                
        $rs = Staff::model()->find('user_phone=:phone', array(':phone'=>$user_phone));
        if($rs['user_name'] <> ''){
            $data['status'] = 0;
            $data['id'] = $rs['user_id'];
            $data['name'] = $rs['user_name'];
        }else{
            $data['status'] = 1;
            $data['msg'] = Yii::t('pay_payroll', 'Error user_phone is null');
        }
        print_r(json_encode($data));
    }
    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['salary/list'] = str_replace("r=payroll/salary/grid", "r=payroll/salary/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }
    
}
