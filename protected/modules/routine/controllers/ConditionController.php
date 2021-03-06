<?php
class ConditionController extends BaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = "";
    public $bigMenu = "";

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('license_condition', 'contentHeader');
        $this->bigMenu = Yii::t('license_condition', 'bigMenu');
    }
    
    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid($id) {
        $model = RoutineCheckType::model()->findByPk($id);
        $contractor_id = $model->contractor_id;
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=routine/condition/grid&type_id='.$id;
        $t->updateDom = 'datagrid';
        $t->set_header('Condition', '', '');
        $t->set_header('Condition Name', '', '');
        $t->set_header('Condition Name En', '', '');
        $t->set_header('Status', '', '');
//        $t->set_header('Record Time', '', '');
//        if($contractor_id != 0){
            $t->set_header('Action', '15%', '');
//        }
        return $t;
    }

    /**
     * 查询
     */
    public function actionGrid() {
        $fields = func_get_args();
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        if(count($fields) == 1 && $fields[0] != null ) {
            $id = $fields[0];
        }
        if($_GET['type_id']){
            $id = $_GET['type_id'];
        }
        $t = $this->genDataGrid($id);
        $this->saveUrl();

        $args['type_id'] = $id;
        $args['status'] = '00';
        $list = RoutineCondition::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t,'id'=>$id, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {
        $id = $_REQUEST['id'];
        $this->smallHeader = Yii::t('license_condition', 'smallHeader List');
        $this->render('list',array('id'=>$id));
    }

    /**
     * 添加
     */
    public function actionNew() {

        $this->smallHeader = Yii::t('license_condition', 'smallHeader New');
        $model = new RoutineCondition('create');
        $r = array();
        $type_id = $_REQUEST['type_id'];
        if (isset($_POST['RoutineCondition'])) {

            $args = $_POST['RoutineCondition'];
            $r = RoutineCondition::insertRoutineCondition($args);

            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['RoutineCondition'];
            }
        }
        $this->render('new', array('model' => $model, 'msg' => $r, 'type_id'=>$type_id));
    }

    /**
     * 修改
     */
    public function actionEdit() {

        $this->smallHeader = Yii::t('license_condition', 'smallHeader Edit');
        $r = array();
        $condition_id = $_REQUEST['id'];
        $type_id = $_REQUEST['type_id'];
        $model = new RoutineCondition('modify');
        if (isset($_POST['RoutineCondition'])) {
            $args = $_POST['RoutineCondition'];
            $args['condition_id'] = $condition_id;
            $r = RoutineCondition::updateRoutineCondition($args);
            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['RoutineCondition'];
            }
        }
        $model = RoutineCondition::model()->findByPk($condition_id);
        $this->renderPartial('edit', array('model' => $model,'condition_id'=>$condition_id,'msg' => $r));
    }

    /*
     * 编辑
     */
    public function actionEditCondition(){
        $args = $_REQUEST['RoutineCondition'];
        $r = RoutineCondition::updateRoutineCondition($args);
        print_r(json_encode($r));
    }

    /*
     * 添加
     */
    public function actionNewCondition(){
        $args = $_REQUEST['RoutineCondition'];
        $r = RoutineCondition::setRoutineCondition($args);
        print_r(json_encode($r));
    }

    /**
     * 删除
     */
    public function actionDelete() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {

            $r = RoutineCondition::deleteRoutineCondition($id);
        }
        echo json_encode($r);
    }
    
     /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['condition/list'] = str_replace("r=license/condition/grid", "r=license/condition/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

}
