<?php
class TypeController extends BaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = "";
    public $bigMenu = "";

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('routine_type', 'contentHeader');
        $this->bigMenu = Yii::t('routine_type', 'bigMenu');
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=routine/type/grid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('routine_type','seq'), '', '');
        $t->set_header(Yii::t('routine_type','type_name'), '', '');
        $t->set_header(Yii::t('routine_type','type_name_en'), '', '');
        $t->set_header(Yii::t('routine_type','status'), '', '');
//        $t->set_header(Yii::t('common','record_time'), '', '');
        $t->set_header(Yii::t('common','action'), '15%', '');
        return $t;
    }

    /**
     * 查询
     */
    public function actionGrid() {
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件

        if(!$args['type_id']){
            $args['type_id'] = 1;
        }

        $t = $this->genDataGrid();
        $this->saveUrl();
        $args['status'] = '00';
        $list = RoutineCheckType::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {
        $this->smallHeader = Yii::t('routine_type', 'smallHeader List');
        $this->render('list');
    }

    /**
     * 添加
     */
    public function actionNew() {

        $this->smallHeader = Yii::t('routine_type', 'smallHeader New');
        $this->render('method_statement',array('mode'=>'add'));
    }

    /**
     * 修改
     */
    public function actionEdit() {

        $this->smallHeader = Yii::t('routine_type', 'smallHeader Edit');
        $model = new PtwType('modify');
        $r = array();
        $id = trim($_REQUEST['id']);
        if (isset($_POST['PtwType'])) {
            $args = $_POST['PtwType'];
            $r = PtwType::updatePtwType($args);
            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['PtwType'];
            }
        }
        $model->_attributes = PtwType::model()->findByPk($id);
        $this->render('edit', array('model' => $model, 'msg' => $r));
    }

    /**
     * Method Statement with Risk Assessment
     */
    public function actionMethod() {
        $this->smallHeader = Yii::t('routine_type', 'smallHeader New');
        $type_id = $_REQUEST['id'];
        $type_model = RoutineCheckType::model()->findByPk($type_id);
        $this->render('method_statement',array('type_id'=>$type_id,'mode'=>'copy','type_model'=>$type_model));
    }

    /**
     * Method Statement with Risk Assessment
     */
    public function actionSaveMethod() {
        $json = $_REQUEST['json_data'];
        $operator_type   =  Yii::app()->user->getState('operator_type');
        if($operator_type == '00'){
            $args['contractor_id'] = 0;
        }else{
            $args['contractor_id'] = Yii::app()->user->contractor_id;
        }
        $args['type_id'] = $_REQUEST['type'];
        $args['type_name'] = $_REQUEST['type_name'];
        $args['type_name_en'] = $_REQUEST['type_name_en'];
        $args['module'] = $_REQUEST['module'];
        $args['device_type'] = $_REQUEST['device_type'];


//        $json = '[{"tempId":1614842926300,"condition_name":"屋顶（7和8楼) - 冷水机房","condition_name_en":"Roof (Levels 7 & 8) - Chiller Plant Room","LAY_TABLE_INDEX":0},{"tempId":1614843090237,"condition_name":"屋顶（7和8楼) - 发电机组区域（房东）","condition_name_en":"Roof (Levels 7 & 8) - Gen-set Area (Landlord)","LAY_TABLE_INDEX":1},{"tempId":1614843090452,"condition_name":"屋顶（7和8楼) - 编造箱","condition_name_en":"Roof (Levels 7 & 8) - Make-up Tank","LAY_TABLE_INDEX":2},{"tempId":1614843090612,"condition_name":"屋顶（7和8楼) - 家用储水箱[以[m]为单位补充水位]","condition_name_en":"Roof (Levels 7 & 8) - Domestic Water Tank [Fill Up Water Level In (m)]","LAY_TABLE_INDEX":3},{"tempId":1614843090988,"condition_name":"屋顶（7和8楼) - Newater储水箱[以[m]为单位补充水位]","condition_name_en":"Roof (Levels 7 & 8) - Newater Tank [Fill Up Water Level In (m)]","LAY_TABLE_INDEX":4},{"tempId":1614843091180,"condition_name":"屋顶（7和8楼) - 洒水箱","condition_name_en":"Roof (Levels 7 & 8) - Sprinkler Tank","LAY_TABLE_INDEX":5},{"tempId":1614843816837,"condition_name":"屋顶（7和8楼) - 升降机房","condition_name_en":"Roof (Levels 7 & 8) - Lift Motor Rooms","LAY_TABLE_INDEX":6},{"tempId":1614843817013,"condition_name":"屋顶（7和8楼) - CDX单位","condition_name_en":"Roof (Levels 7 & 8) - CDX Units","LAY_TABLE_INDEX":7},{"tempId":1614843817173,"condition_name":"屋顶（7和8楼) - ESMB室","condition_name_en":"Roof (Levels 7 & 8) - ESMB Room","LAY_TABLE_INDEX":8},{"tempId":1614843817341,"condition_name":"屋顶（7和8楼) - 房客必备设备","condition_name_en":"Roof (Levels 7 & 8) - Tenants\' Essential Equipment","LAY_TABLE_INDEX":9},{"tempId":1614843817509,"condition_name":"屋顶（7和8楼) - 新鲜空气风扇","condition_name_en":"Roof (Levels 7 & 8) - Fresh Air Fans","LAY_TABLE_INDEX":10},{"tempId":1614843817630,"condition_name":"屋顶（7和8楼) - 排气扇","condition_name_en":"Roof (Levels 7 & 8) - Exhaust Air Fans","LAY_TABLE_INDEX":11},{"tempId":1614843901581,"condition_name":"屋顶（7和8楼) - 套件排气扇","condition_name_en":"Roof (Levels 7 & 8) - Kit Exhaust Fans","LAY_TABLE_INDEX":12},{"tempId":1614843901749,"condition_name":"1楼 - BMC/FCC","condition_name_en":"Level 1 - BMC/FCC","LAY_TABLE_INDEX":13},{"tempId":1614843901917,"condition_name":"1楼 - 大堂（南北）","condition_name_en":"Level 1 - Lobby (North & South)","LAY_TABLE_INDEX":14},{"tempId":1614843902077,"condition_name":"1楼 - 放下区域","condition_name_en":"Level 1 - Drop-Off Area","LAY_TABLE_INDEX":15},{"tempId":1614843902245,"condition_name":"1楼 - 停车场路障","condition_name_en":"Level 1 - Car-park Barrier","LAY_TABLE_INDEX":16},{"tempId":1614843902405,"condition_name":"1楼 - 鱼池","condition_name_en":"Level 1 - Fish Pond","LAY_TABLE_INDEX":17},{"tempId":1614843902581,"condition_name":"1楼 - 旗杆池","condition_name_en":"Level 1 - Flag Pole Pond","LAY_TABLE_INDEX":18},{"tempId":1614843902741,"condition_name":"1楼 - 洒水阀（南北）","condition_name_en":"Level 1 - Sprinkler Valves (North & South)","LAY_TABLE_INDEX":19},{"tempId":1614843902917,"condition_name":"冷却塔（藻类）","condition_name_en":"Cooling Towers (Algae)","LAY_TABLE_INDEX":20},{"tempId":1614843903077,"condition_name":"MDF 南核心室 - B1","condition_name_en":"MDF Room South Core - B1","LAY_TABLE_INDEX":21},{"tempId":1614843903237,"condition_name":"MDF 北核心室 - B1","condition_name_en":"MDF Room North Core - B1","LAY_TABLE_INDEX":22},{"tempId":1614843903421,"condition_name":"喷水泵房 - B1 [满水位(m)]","condition_name_en":"Sprinkler Pump Room - B1 [Fill Up Water Level In (m)]","LAY_TABLE_INDEX":23},{"tempId":1614844314582,"condition_name":"LT开关室 - B1","condition_name_en":"LT Switch Room - B1","LAY_TABLE_INDEX":24},{"tempId":1614844314757,"condition_name":"HT开关室 - B1","condition_name_en":"HT Switch Room - B1","LAY_TABLE_INDEX":25},{"tempId":1614844314942,"condition_name":"变压器房 - B1","condition_name_en":"Transformer Room - B1","LAY_TABLE_INDEX":26},{"tempId":1614844315094,"condition_name":"水车水箱[满水位(米)] ","condition_name_en":"Water Wheel Tank [Fill Up Water Level In (m)]","LAY_TABLE_INDEX":27},{"tempId":1614844315238,"condition_name":"内部照明 - L1南大堂","condition_name_en":"Internal Lightings - L1 South Lobby","LAY_TABLE_INDEX":28},{"tempId":1614844315381,"condition_name":"内部照明 - L1北大堂","condition_name_en":"Internal Lightings - L1 North Lobby","LAY_TABLE_INDEX":29},{"tempId":1614844422534,"condition_name":"内部照明 - 1-3区商场","condition_name_en":"Internal Lightings - Zones 1-3 Arcade","LAY_TABLE_INDEX":30},{"tempId":1614844422694,"condition_name":"内部照明 - 4-6区CLM","condition_name_en":"Internal Lightings - Zones 4-6 CLM","LAY_TABLE_INDEX":31},{"tempId":1614844422878,"condition_name":"内部照明 - 7-9区CLM","condition_name_en":"Internal Lightings - Zones 7-9 CLM","LAY_TABLE_INDEX":32},{"tempId":1614844423022,"condition_name":"冷水机 - 运行Ch 1/2/3","condition_name_en":"Chiller - Running Ch 1/2/3","LAY_TABLE_INDEX":33},{"tempId":1614844503094,"condition_name":"冷水机 - 运行Ch 4/5","condition_name_en":"Chiller - Running Ch 4/5","LAY_TABLE_INDEX":34},{"tempId":1614844503293,"condition_name":"柴油泵房B1 - 室","condition_name_en":"Diesel Pump Room B1 - Room","LAY_TABLE_INDEX":35},{"tempId":1614844553429,"condition_name":"柴油泵房B1 - 柴油水平[加油量(升)]","condition_name_en":"Diesel Pump Room B1 - Diesel Level [Fill Up In (litres)]","LAY_TABLE_INDEX":36}]';
//        $args['contractor_id'] = 0;
//        $args['type_id'] = 'JEC-002';
//        $args['type_name'] = '每日M＆E检查清单(AM)';
//        $args['type_name_en'] = 'Daily M&E Inspection Checklist (AM)';
//        $args['module'] = '2';
//        $args['device_type'] = '';


//        var_dump($args);
//        exit;
        $args['status'] = '00';
        $data = json_decode($json);
        $r = RoutineCondition::insertRoutineCondition($args,$data);
        echo json_encode($r);
        // var_dump($data);
        // exit;
    }

    /**
     * json数据
     */
    public function actionDemoData() {
        $type_id = $_REQUEST['id'];
        $detail_list = RoutineCondition::detailList($type_id);
        print_r(json_encode($detail_list));
    }

    /**
     * json数据
     */
    public function actionDeviceType() {
        $detail_list = DeviceType::deviceList();
        print_r(json_encode($detail_list));
    }

    /**
     * 启用
     */
    public function actionStart() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {

            $r = RoutineCheckType::startType($id);
        }
        echo json_encode($r);
    }

    /**
     * 停用
     */
    public function actionStop() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {

            $r = RoutineCheckType::stopType($id);
        }
        echo json_encode($r);
    }

    /**
     * 二维码
     */
    public function actionQrCode() {
        $type_id = $_REQUEST['id'];
        $primary_id = $_REQUEST['primary_id'];
        $program_id = $_REQUEST['program_id'];
        $type_model = RoutineCheckType::model()->findByPk($type_id);
        $type_name_en = $type_model->type_name_en;
        $filepath = RoutineCheckType::buildQrCode($type_id,$primary_id,$program_id);
        $extend = 'png';
        Utils::Download($filepath, $type_name_en, $extend);
    }


     /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['type/list'] = str_replace("r=license/type/grid", "r=license/type/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

}
