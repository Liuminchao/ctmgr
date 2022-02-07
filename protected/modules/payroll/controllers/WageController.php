<?php

/**
 * 时薪
 * @author LiuMinchao
 */
class WageController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';
//    const STATUS_NORMAL = 0; //正常

    public function init() {
        parent::init();
        
        $this->bigMenu = Yii::t('pay_payroll', 'bigMenu');
    }
    public function actionList() {
        $this->contentHeader = Yii::t('pay_payroll', 'contentHeader_wage');
        $this->smallHeader = Yii::t('pay_payroll', 'smallHeader Wage List');
//        $args = array(
//            'ation_type'  =>  'PRC',
//        );
        $this->render('list');
    }
     /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=payroll/wage/grid';
        $t->updateDom = 'datagrid';
	$t->set_header(Yii::t('sys_role', 'role_name'), '', '');
        $t->set_header(Yii::t('sys_role', 'team_name'), '', '');
        $t->set_header(Yii::t('comp_staff', 'Nation_type'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'wage'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'wage_overtime'), '', '');
//        $t->set_header(Yii::t('pay_payroll','record_time'),'','');
//        $t->set_header(Yii::t('pay_payroll', 'status'), '', '');
//        $t->set_header(Yii::t('comp_staff', 'Action'), '', '');
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
       
        if($args['status'] == ''){
            $args['status'] = Role::STATUS_NORMAL;
        }
        $t = $this->genDataGrid();
        $this->saveUrl();
        if($args['ation_type']){
            $ation_type = $args['ation_type'];
        }else{
            $ation_type = 'PRC';
        }
//        var_dump($ation_type);
//        $args['contractor_type'] = Staff::CONTRACTOR_TYPE_SC;
        $rgs['contractor_id'] = Yii::app()->user->getState('contractor_id');
//        $programlist = PayrollWorkHour::programList($args);
        $list = Role::queryList($page, $this->pageSize, $args);
        $wage_list = PayrollWage::wageList($ation_type);
//        var_dump($ation_type);
//        var_dump($wage_list);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'],'wage_rows' => $wage_list,'ation_type'=>$ation_type, 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }
     /**
     * 表头
     * @return SimpleGrid
     */
    private function genEditDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=payroll/workhour/query';
        $t->updateDom = 'datagrid';
	$t->set_header(Yii::t('pay_payroll', 'working_date'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'program'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'user_name'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'work_hours'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'overtime_hours'), '', '');
//        $t->set_header(Yii::t('comp_staff', 'Action'), '', '');
        return $t;
    }
    /**
     * 编辑表格内容
     */
    public function actionEdit() {
        $args['name'] = $_REQUEST['name'];
        $args['role_id'] = $_REQUEST['role_id'];
        $args['pk'] = $_REQUEST['pk'];
        $args['value'] = $_REQUEST['value'];
        $args['ation_type'] = $_REQUEST['ation_type'];
        $args['contractor_id'] = Yii::app()->user->contractor_id;
        $args['status'] = PayrollWage::STATUS_NORMAL;
 
        $rs = PayrollWage::setwage($args);
        echo json_encode($rs);
    }    
    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['workhour/list'] = str_replace("r=payroll/workhour/grid", "r=payroll/workhour/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }
    
}
