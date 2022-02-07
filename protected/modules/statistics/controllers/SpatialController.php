<?php

/**
 * 空间统计
 * @author LiuMinChao
 */
class SpatialController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';
    const STATUS_NORMAL = 0; //正常

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('comp_statistics', 'contentHeader_day');
        $this->bigMenu = Yii::t('comp_statistics', 'bigMenu');
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=statistics/spatial/grid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('comp_contractor', 'Contractor_name'), '', 'center');
        $t->set_header(Yii::t('comp_statistics', 'attribute_size'), '', 'center');
        $t->set_header(Yii::t('comp_statistics', 'flow_pic_size'), '', 'center');
        $t->set_header(Yii::t('comp_statistics', 'documnet_size'), '', 'center');
//        $t->set_header(Yii::t('comp_accident','type'),'','');
        $t->set_header(Yii::t('comp_statistics','total_size'),'','center');
//        $t->set_header(Yii::t('comp_statistics', 'max_size'), '', 'center');
        $t->set_header(Yii::t('common', 'statistics_date'), '', 'center');
//        $t->set_header(Yii::t('comp_statistics', 'status'), '', 'center');
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
//        exit;
        $t = $this->genDataGrid();
        $this->saveUrl();
//        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
//        ExcelExport::Export();
        $list = StatsContractorStore::queryAllList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {
//        var_dump($_GET['id']);
//            $this->contentHeader = $contractor_name;
        $this->smallHeader = Yii::t('comp_statistics', 'spatial_statistics');
//            $this->contentHeader = $contractor_name;
        $this->render('list');

    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genBusinessDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=statistics/spatial/businessgrid';
        $t->updateDom = 'datagrid';
//        $t->set_compound_header(用户信息, '', 'center','','','2');
//        $t->set_compound_header(Yii::t('comp_staff', 'User_id'), '', '','','','2');
        $t->set_compound_header(Yii::t('proj_project', 'contentHeader'), '', 'center','','','2');
        $t->set_compound_header(Yii::t('proj_project', 'contractor_name'), '300', 'center','','','2');
        $t->set_compound_header(PTW, '', 'center','','2');
        $t->set_compound_header(TBM, '', 'center','','2');
        $t->set_compound_header(Checklist, '', 'center','','1');
        $t->set_compound_header(Inspection, '', 'center','','1');
        $t->set_compound_header(Meeting, '', 'center','','2');
        $t->set_compound_header(Training, '', 'center','','2');
        $t->set_compound_header(RA, '', 'center','','1');
        $t->set_compound_header(Incident, '', 'center','','1');
//        $t->set_compound_header(File, '', 'center','','2');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_staffs'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_staffs'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'),'','');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_staffs'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_staffs'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', '');
//        $t->set_compound_header(Yii::t('comp_statistics', 'day_number'), '', '');
//        $t->set_compound_header(Yii::t('comp_statistics', 'day_size'), '', '');
        return $t;
    }

    /**
     * 查询
     */
    public function actionBusinessGrid() {
        //var_dump($_GET['page']);
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
//        var_dump($args);
//        exit;
        if(empty($args)){
            $args['date'] = Utils::DateToEn(date('Y-m-d',strtotime('-1 day')));
        }
        $t = $this->genBusinessDataGrid();
        $this->saveUrl();
//        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
//        ExcelExport::Export();
        $list = StatsDateApp::BusinessStatistics($page, $this->pageSize,$args);
        $this->renderPartial('business_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num']));
    }

    /**
     * 列表
     */
    public function actionBusinessList() {
//        var_dump($_GET['id']);
//            $this->contentHeader = $contractor_name;
        $this->smallHeader = Yii::t('dboard', 'Business Statistics');
//            $this->contentHeader = $contractor_name;
        $this->render('businesslist');

    }

    /**
     * 业务统计图表
     */
    public function actionBusinessGraph() {
        $this->smallHeader = Yii::t('dboard', 'Business Statistics Graph');
        $this->render('businessgraph');
    }

    /**
     * 统计图表查询数据
     */
    public function actionModuleByDay() {
        $module = $_REQUEST['module'];
        $date = $_REQUEST['date'];
        $data = StatsDateApp::ModuleByDay($module,$date);
        print_r(json_encode($data));
    }

    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['spatial/list'] = str_replace("r=statistics/spatial/grid", "r=statistics/spatial/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }
}
