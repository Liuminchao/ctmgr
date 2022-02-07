<?php

/**
 * 补贴
 * @author LiuMinchao
 */
class AllowanceController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';

    public function init() {
        parent::init();
        
        $this->bigMenu = Yii::t('pay_payroll', 'bigMenu');
    }
    public function actionList() {
        $this->contentHeader = Yii::t('pay_payroll', 'contentHeader_allowance');
        $this->smallHeader = Yii::t('pay_payroll', 'smallHeader Allowance List');
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
        $t->url = 'index.php?r=payroll/allowance/grid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('pay_payroll', 'date'), '', '');
	$t->set_header(Yii::t('comp_staff','User_name'), '', '');
//        $t->set_header(Yii::t('comp_staff','User_phone'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'allowance'), '', '');
        $t->set_header(Yii::t('pay_payroll', 'allowance_content'), '', '');
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
//        var_dump($ation_type);
//        $args['contractor_type'] = Staff::CONTRACTOR_TYPE_SC;
        $rgs['contractor_id'] = Yii::app()->user->getState('contractor_id');
//        $programlist = PayrollWorkHour::programList($args);
        $list = PayrollAllowance::queryList($page, $this->pageSize, $args);
//        var_dump($wage_list);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'],'wage_rows' => $wage_list,'ation_type'=>$ation_type, 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }
    /**
     * 添加员工补贴
     */
    public function actionNew() {

        $this->smallHeader = Yii::t('pay_payroll', 'contentHeader_allowance');
        $model = new PayrollAllowance('create');
        $r = array();
        $this->renderPartial('new', array('model' => $model, 'msg' => $r));
    }
    
    public function actionAnew() {
        
        $this->smallHeader = Yii::t('pay_payroll', 'contentHeader_allowance');
        $model = new PayrollAllowance('create');
        $r = array();
        if (isset($_REQUEST['PayrollAllowance'])) {
            $args = $_REQUEST['PayrollAllowance'];
            $args['status'] = PayrollAllowance::STATUS_DISABLE;
            if($args['user_phone'] != ''){
                $r = PayrollAllowance::insertAllowance($args);
            }else{
                $r = PayrollAllowance::insertbatchAllowance($args);
            }
        //print_r($r);
        print_r(json_encode($r));
        }
    }
    /**
     * 编辑员工补贴
     */
    public function actionEdit() {

        $this->smallHeader = Yii::t('pay_payroll', 'contentHeader_allowance');
        $model = new PayrollAllowance('modify');
        $allowance_id = $_REQUEST['allowance_id'];
        $model->_attributes = PayrollAllowance::model()->findByPk($allowance_id);
        $r = array();
        $this->renderPartial('edit', array('model' => $model,'allowance_id'=> $allowance_id, 'msg' => $r));
    }
    
    public function actionAedit() {
        
        $this->smallHeader = Yii::t('proj_project', 'smallHeader Edit');
        $model = new Task('modify');
        $r = array();
        $allowance_id = $_REQUEST['allowance_id'];

        if (isset($_REQUEST['PayrollAllowance'])) {
            
            $args = $_REQUEST['PayrollAllowance'];
            $args['allowance_id'] = $allowance_id;
           
            $r = PayrollAllowance::updateAllowance($args);
           
        }
        print_r(json_encode($r));
    }
    /**
     * 删除
     */
    public function actionDelete() {
        $allowance_id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = PayrollAllowance::deleteAllowance($allowance_id);
        }
        echo json_encode($r);
    }
    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['allowance/list'] = str_replace("r=payroll/allowance/grid", "r=payroll/allowance/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }
    
}
