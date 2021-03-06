<?php

/**
 * 各模块统计信息
 * @author LiuMinChao
 */
class ModuleController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';
    const STATUS_NORMAL = 0; //正常
    public $layout = '//layouts/main_1';

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
        $t->url = 'index.php?r=statistics/module/grid';
        $t->updateDom = 'datagrid';
        //$t->set_compound_header(用户信息, '', 'center','','','2');
        //$t->set_compound_header(Yii::t('comp_staff', 'User_id'), '', '','','','2');
        $t->set_compound_header(Yii::t('comp_statistics', 'date'), '', 'center','','','2');
        $t->set_compound_header(Yii::t('comp_statistics', 'sc_program'), '300', 'center','','','2');
        $t->set_compound_header(PTW, '', 'center','','2');
        $t->set_compound_header(TBM, '', 'center','','2');
        $t->set_compound_header(Inspection, '', 'center','','1');
        $t->set_compound_header(Meeting, '', 'center','','2');
        $t->set_compound_header(Training, '', 'center','','2');
        $t->set_compound_header(RA, '', 'center','','1');
        $t->set_compound_header(Checklist, '', 'center','','1');
        $t->set_compound_header(Incident, '', 'center','','1');
        //$t->set_compound_header(File, '', 'center','','2');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_staffs'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_staffs'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'),'','');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_staffs'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_staffs'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', '');
        //$t->set_compound_header(Yii::t('comp_statistics', 'day_number'), '', '');
        //$t->set_compound_header(Yii::t('comp_statistics', 'day_size'), '', '');
        return $t;
    }

    /**
     * 月报表头
     * @return SimpleGrid
     */
    private function genMonthGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=statistics/module/monthgrid';
        $t->updateDom = 'datagrid';
//        $t->set_compound_header(用户信息, '', 'center','','','2');
//        $t->set_compound_header(Yii::t('comp_staff', 'User_id'), '', '','','','2');
        $t->set_compound_header(Yii::t('comp_statistics', 'date'), '', 'center','','','2');
        $t->set_compound_header(Yii::t('comp_statistics', 'sc_program'), '300', 'center','','','2');
        $t->set_compound_header(PTW, '', 'center','','2');
        $t->set_compound_header(TBM, '', 'center','','2');
        $t->set_compound_header(Inspection, '', 'center','','1');
        $t->set_compound_header(Meeting, '', 'center','','2');
        $t->set_compound_header(Training, '', 'center','','2');
        $t->set_compound_header(RA, '', 'center','','1');
        $t->set_compound_header(Checklist, '', 'center','','1');
        $t->set_compound_header(Incident, '', 'center','','1');
//        $t->set_compound_header(File, '', 'center','','2');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_staffs'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_staffs'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'),'','');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_staffs'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_staffs'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', '');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', '');
//        $t->set_compound_header(Yii::t('comp_statistics', 'day_number'), '', '');
//        $t->set_compound_header(Yii::t('comp_statistics', 'day_size'), '', '');
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

        if($args['status'] == ''){
            $args['status'] = '0';
        }
        $t = $this->genDataGrid();
        $this->saveUrl();
        $args['contractor_type'] = Staff::CONTRACTOR_TYPE_SC;
//        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $tag_date = '2018-01-01';
        $tag_time = strtotime($tag_date);
        if($args['end_date']){
            $end_date = Utils::DateToCn($args['end_date']);
            $post_time = strtotime($end_date);
        }else{
            $post_time = $tag_time-1;
        }
        if($post_time > $tag_time ){
            $list = StatsDateApp::queryListByDate($page, $this->pageSize, $args);
        }else{
            $list = Statistic::queryOldDay($page, $this->pageSize, $args);
        }

        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }
    /**
     * 标签页
     */
    public function actionTabs() {
        $id = $_REQUEST['id'];
        $name = $_REQUEST['name'];
        $this->render('tabs',array('id' => $id,'name'=>$name));
    }
    /**
     * 标签页
     */
    public function actionMonthTabs() {
        $id = $_REQUEST['id'];
        $name = $_REQUEST['name'];
        $this->render('month_tabs',array('id' => $id,'name'=>$name));
    }
    /**
     * 完整版日统计表头
     * @return SimpleGrid
     */
    private function genDateAppGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=statistics/module/dateappgrid';
        $t->updateDom = 'datagrid';
        $t->set_compound_header(Yii::t('comp_statistics', 'date'), '', 'center','','','2');
        $t->set_compound_header(Yii::t('comp_statistics', 'sc_program'), '300', 'center','','','2');
        $t->set_compound_header(PTW, '', 'center','','2');
        $t->set_compound_header(TBM, '', 'center','','2');
        $t->set_compound_header(Inspection, '', 'center','','1');
        $t->set_compound_header(Meeting, '', 'center','','2');
        $t->set_compound_header(Training, '', 'center','','2');
        $t->set_compound_header(RA, '', 'center','','1');
        $t->set_compound_header(Checklist, '', 'center','','1');
        $t->set_compound_header(Incident, '', 'center','','1');
        //$t->set_compound_header(File, '', 'center','','2');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', 'center');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_staffs'), '', 'center');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', 'center');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_staffs'), '', 'center');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', 'center');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'),'','center');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_staffs'), '', 'center');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', 'center');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_staffs'), '', 'center');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', 'center');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', 'center');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', 'center');
        //$t->set_compound_header(Yii::t('comp_statistics', 'day_number'), '', '');
        //$t->set_compound_header(Yii::t('comp_statistics', 'day_size'), '', '');
        return $t;
    }
    /**
     * Lite版日统计表头
     * @return SimpleGrid
     */
    private function genDateAppLiteGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=statistics/module/dateappgrid';
        $t->updateDom = 'datagrid';
        $t->set_compound_header(Yii::t('comp_statistics', 'date'), '', 'center','','','2');
        $t->set_compound_header(Yii::t('comp_statistics', 'sc_program'), '300', 'center','','','2');
        $t->set_compound_header(PTW, '', 'center','','2');
        $t->set_compound_header(TBM, '', 'center','','2');
        $t->set_compound_header(Inspection, '', 'center','','1');
        $t->set_compound_header(Incident, '', 'center','','1');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', 'center');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_staffs'), '', 'center');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', 'center');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_staffs'), '', 'center');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', 'center');
        
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', 'center');
        return $t;
    }
    /**
     * 完整版月统计表头
     * @return SimpleGrid
     */
    private function genMonthAppGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=statistics/module/monthappgrid';
        $t->updateDom = 'datagrid';
        $t->set_compound_header(Yii::t('comp_statistics', 'date'), '', 'center','','','2');
        $t->set_compound_header(Yii::t('comp_statistics', 'sc_program'), '300', 'center','','','2');
        $t->set_compound_header(PTW, '', 'center','','2');
        $t->set_compound_header(TBM, '', 'center','','2');
        $t->set_compound_header(Inspection, '', 'center','','1');
        $t->set_compound_header(Meeting, '', 'center','','2');
        $t->set_compound_header(Training, '', 'center','','2');
        $t->set_compound_header(RA, '', 'center','','1');
        $t->set_compound_header(Checklist, '', 'center','','1');
        $t->set_compound_header(Incident, '', 'center','','1');
        //$t->set_compound_header(File, '', 'center','','2');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', 'center');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_staffs'), '', 'center');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', 'center');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_staffs'), '', 'center');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', 'center');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'),'','center');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_staffs'), '', 'center');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', 'center');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_staffs'), '', 'center');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', 'center');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', 'center');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', 'center');
        //$t->set_compound_header(Yii::t('comp_statistics', 'day_number'), '', '');
        //$t->set_compound_header(Yii::t('comp_statistics', 'day_size'), '', '');
        return $t;
    }
    /**
     * Lite版月统计表头
     * @return SimpleGrid
     */
    private function genMonthAppLiteGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=statistics/module/monthappgrid';
        $t->updateDom = 'datagrid';
        $t->set_compound_header(Yii::t('comp_statistics', 'date'), '', 'center','','','2');
        $t->set_compound_header(Yii::t('comp_statistics', 'sc_program'), '300', 'center','','','2');
        $t->set_compound_header(PTW, '', 'center','','2');
        $t->set_compound_header(TBM, '', 'center','','2');
        $t->set_compound_header(Inspection, '', 'center','','1');
        $t->set_compound_header(Incident, '', 'center','','1');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', 'center');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_staffs'), '', 'center');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', 'center');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_staffs'), '', 'center');
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', 'center');
       
        $t->set_compound_header(Yii::t('comp_statistics', 'day_sum'), '', 'center');
        return $t;
    }
    /**
     * 按日查询
     */
    public function actionDateAppGrid() {
        //var_dump($_GET['page']);
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        //0-完整版 1-lite版（lite版只保留TBM, PTW,Inspection和Incident模块）
        $proj_id = Yii::app()->user->getState('program_id');
        $program_app = ProgramApp::getIslite($proj_id);
        if ($program_app['is_lite'] == '1') {
            $t = $this->genDateAppLiteGrid();
        }else{
            $t = $this->genDateAppGrid();
        }
        $this->saveUrl();
        $tag_date = '2018-01-01';
        $tag_time = strtotime($tag_date);
        if($args['end_date']){
            $end_date = Utils::DateToCn($args['end_date']);
            $post_time = strtotime($end_date);
        }else{
            $post_time = $tag_time +1;
        }

        if($post_time > $tag_time ){
            $list = StatsDateApp::queryListByDate($page, $this->pageSize, $args);
        }else{
            $list = Statistic::queryOldDay($page, $this->pageSize, $args);
        }
        $this->renderPartial('dateapp_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num'],'program_id'=>$args['program_id']));
    }
    /**
     * 按月查询
     */
    public function actionMonthAppGrid() {
        //var_dump($_GET['page']);
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        //0-完整版 1-lite版（lite版只保留TBM, PTW,Inspection和Incident模块）
        $proj_id = Yii::app()->user->getState('program_id');
        $program_app = ProgramApp::getIslite($proj_id);
        if ($program_app['is_lite'] == '1') {
            $t = $this->genMonthAppLiteGrid();
        }else{
            $t = $this->genMonthAppGrid();
        }
        
        $this->saveUrl();
        //$args['contractor_id'] = Yii::app()->user->getState('contractor_id');

        if($args['month']){
            $month = Utils::MonthToCn($args['month']);
            $mon_arg = explode('-',$month);
            $query_month = $mon_arg[0];
        }else{
            $query_month = 2018;
        }

        $tag_month = 2018;

        if($query_month >= $tag_month ){
            $list = StatsDateApp::queryListByMonth($page, $this->pageSize, $args);
        }else{
            $list = Statistic::queryOldMonth($page, $this->pageSize, $args);
        }

        $this->renderPartial('monthapp_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num'],'program_id'=>$args['program_id']));
    }
    /**
     * 表头
     * @return SimpleGrid
     */
    private function genBarchartGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=statistics/module/barchartgrid';
        $t->updateDom = 'datagrid';
        $t->set_header('Ptw Cnt', '', 'center');
        $t->set_header('Tbm Cnt', 'attribute_size', '', 'center');
        $t->set_header('Checklist Cnt', '', 'center');
        $t->set_header('Inspection Cnt', '', 'center');
//        $t->set_header(Yii::t('comp_accident','type'),'','');
        $t->set_header('Meeting Cnt','','center');
        $t->set_header('Training Cnt', '', 'center');
        $t->set_header('Ra Cnt', '', 'center');
        $t->set_header('Incident Cnt', '', 'center');
//        $t->set_header(Yii::t('comp_statistics', 'status'), '', 'center');
        return $t;
    }


    /**
     * 查询
     */
    public function actionBarchartGrid() {
        //var_dump($_GET['page']);
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        $t = $this->genBarchartGrid();
        $this->saveUrl();
        $list = StatsDateApp::cntByDate($args);
        $this->renderPartial('barchart_list', array('t' => $t, 'rows' => $list));
    }

    /**
     * 列表
     */
    public function actionBarchartList() {
        //$this->contentHeader = $contractor_name;
        $this->smallHeader = Yii::t('comp_statistics', 'spatial_statistics');
        //$this->contentHeader = $contractor_name;
        $this->render('barchartlist');

    }
    /**
     * 日统计
     */
    public function actionDateAppList() {
        $this->render('dateapplist');
    }
    /**
     * 日统计
     */
    public function actionDayList() {
        $this->smallHeader = Yii::t('comp_statistics', 'day_statistics');
        $this->render('statistics');
    }
    /**
     * 月统计
     */
    public function actionMonList() {
        // $proj_id = Yii::app()->session['program_id'];
        //             var_dump($proj_id);die();
        $this->smallHeader = Yii::t('comp_statistics', 'month_statistics');
        $this->render('month_statistics');
    }
    /**
     *按日统计查询项目使用次数
     */
    public function actionModuleDayCnt() {
        $args['program_id'] = $_REQUEST['id'];
        $args['start_date'] = $_REQUEST['start_date'];
        $args['end_date'] = $_REQUEST['end_date'];
        $tag_date = '2018-01-01';
        $tag_time = strtotime($tag_date);
        if($args['end_date']){
            $end_date = Utils::DateToCn($args['end_date']);
            $post_time = strtotime($end_date);
        }else{
            $post_time = $tag_time+1;
        }
        if($post_time > $tag_time ){
            $r = StatsDateApp::cntByDate($args);
        }else{
            $r = Statistic::oldDayList($args);
        }
        print_r(json_encode($r));
    }
    /**
     *按月统计查询项目使用次数
     */
    public function actionModuleMonthCnt() {
        $args['program_id'] = $_REQUEST['id'];
        $args['month'] = $_REQUEST['month'];
        if($args['month']){
            $month = Utils::MonthToCn($args['month']);
            $mon_arg = explode('-',$month);
            $query_month = $mon_arg[0];
        }else{
            $query_month = 2018;
        }
        $tag_month = 2018;
        if($query_month >= $tag_month ){
            $r = StatsDateApp::cntByMonth($args);
        }else{
            $r = Statistic::oldMonthList($args);
        }
        print_r(json_encode($r));
    }
    /**
     * 列表
     */
    public function actionList() {
        $fields = func_get_args();
        if(count($fields)==2){
            $contractor_id = $fields[0];
            $contractor_name = $fields[1];
            //$this->contentHeader = $contractor_name;
            $this->smallHeader = Yii::t('comp_statistics', 'smallHeader List Company');
            //$this->contentHeader = $contractor_name;
            $this->renderPartial('list',array('contractor_id'=>$contractor_id,'contractor_name'=>$contractor_name));
        }else{
            $contractor_id = Yii::app()->user->getState('contractor_id');
            $model = Contractor::model()->findByPk($contractor_id);
            $contractor_name = $model->contractor_name;
            $this->smallHeader = Yii::t('comp_statistics', 'smallHeader List Day');
            $this->render('list',array('contractor_id'=>$contractor_id,'contractor_name'=>$contractor_name));
        }

    }
    /**
     * 月报列表
     */
    public function actionMonthList() {
        $fields = func_get_args();
        if(count($fields)==2){
            $contractor_id = $fields[0];
            $contractor_name = $fields[1];
            //$this->contentHeader = $contractor_name;
            $this->smallHeader = Yii::t('comp_statistics', 'smallHeader List Month');
            //$this->contentHeader = $contractor_name;
            $this->renderPartial('monthlist',array('contractor_id'=>$contractor_id,'contractor_name'=>$contractor_name));
        }else{
            $contractor_id = Yii::app()->user->getState('contractor_id');
            $model = Contractor::model()->findByPk($contractor_id);
            $contractor_name = $model->contractor_name;
            $this->smallHeader = Yii::t('comp_statistics', 'smallHeader List Month');
            $this->render('monthlist',array('contractor_id'=>$contractor_id,'contractor_name'=>$contractor_name));
        }

    }
    /**
     * 月报查询
     */
    public function actionMonthGrid() {
        //var_dump($_GET['page']);
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        if($args['status'] == ''){
            $args['status'] = '0';
        }
        $t = $this->genMonthGrid();
        $this->saveUrl();
        $args['contractor_type'] = Staff::CONTRACTOR_TYPE_SC;
        //$args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        if($args['month']){
            $month = Utils::MonthToCn($args['month']);
            $mon_arg = explode('-',$month);
            $query_month = $mon_arg[0];
        }else{
            $query_month = 2018;
        }

        $tag_month = 2018;

        if($query_month >= $tag_month ){
            $list = StatsDateApp::queryListByMonth($page, $this->pageSize, $args);
        }else{
            $list = Statistic::queryOldMonth($page, $this->pageSize, $args);
        }

        $this->renderPartial('month_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }
    /**
     * 日统计完整版导出Excel
     */
    public function actionExport(){
        //$page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        //$_GET['page'] = $_GET['page'] + 1;
        $page = 'all';
        $args = $_GET['q'];
        if($args['status'] == ''){
            $args['status'] = '0';
        }
        $args['contractor_type'] = Staff::CONTRACTOR_TYPE_SC;
        //$args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $tag_date = '2018-01-01';
        $tag_time = strtotime($tag_date);
        if($args['end_date']){
            $end_date = Utils::DateToCn($args['end_date']);
            $post_time = strtotime($end_date);
        }else{
            $post_time = $tag_time-1;
        }
        if($post_time > $tag_time ){
            $list = StatsDateApp::queryListByDate($page, $this->pageSize, $args);
        }else{
            $list = Statistic::queryOldDay($page, $this->pageSize, $args);
        }
        $rows = $list['all'];
        //为指定表单制定表格
        //echo Yii::app()->basePath;
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();
        $objectPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objectPHPExcel->getActiveSheet();
        $objActSheet->setTitle('Sheet1');
        //spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭yii的自动加载功能

        //报表头的输出
        $objectPHPExcel->getActiveSheet()->mergeCells('A1:A2');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('A1',Yii::t('comp_statistics', 'date'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->mergeCells('B1:B2');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('B1',Yii::t('comp_statistics', 'sc_program'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->mergeCells('C1:D1');
        $objectPHPExcel->getActiveSheet()->setCellValue('C1','PTW');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('C1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('C1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('C2',Yii::t('comp_statistics', 'day_sum'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('C2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('C2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('D2',Yii::t('comp_statistics', 'day_staffs'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('D2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('D2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->mergeCells('E1:F1');
        $objectPHPExcel->getActiveSheet()->setCellValue('E1','TBM');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('E1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('E1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('E2',Yii::t('comp_statistics', 'day_sum'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('E2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('E2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('F2',Yii::t('comp_statistics', 'day_staffs'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('F2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('F2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //$objectPHPExcel->getActiveSheet()->mergeCells('F1:H1');
        $objectPHPExcel->getActiveSheet()->setCellValue('G1','Inspection');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('G1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('G1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('G2',Yii::t('comp_statistics', 'day_sum'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('G2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('G2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //$objectPHPExcel->getActiveSheet()->setCellValue('H2',Yii::t('comp_statistics', 'day_staffs'));
        //$objectPHPExcel->setActiveSheetIndex(0)->getStyle('H2')->getFont()->setSize(10);
        //$objectPHPExcel->setActiveSheetIndex(0)->getStyle('H2')
        //->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->mergeCells('H1:I1');
        $objectPHPExcel->getActiveSheet()->setCellValue('H1','Meeting');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('H1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('H1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('H2',Yii::t('comp_statistics', 'day_sum'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('H2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('H2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('I2',Yii::t('comp_statistics', 'day_staffs'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('I2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('I2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->mergeCells('J1:K1');
        $objectPHPExcel->getActiveSheet()->setCellValue('J1','Training');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('J1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('J1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('J2',Yii::t('comp_statistics', 'day_sum'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('J2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('J2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('K2',Yii::t('comp_statistics', 'day_staffs'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('K2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('K2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //$objectPHPExcel->getActiveSheet()->mergeCells('M1:N1');
        $objectPHPExcel->getActiveSheet()->setCellValue('L1','LA');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('L1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('L1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('L2',Yii::t('comp_statistics', 'day_sum'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('L2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('L2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //$objectPHPExcel->getActiveSheet()->mergeCells('O1:P1');
        $objectPHPExcel->getActiveSheet()->setCellValue('M1','Checklist');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('M1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('M1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('M2',Yii::t('comp_statistics', 'day_sum'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('M2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('M2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //$objectPHPExcel->getActiveSheet()->mergeCells('Q1:R1');
        $objectPHPExcel->getActiveSheet()->setCellValue('N1','Incident');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('N1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('N1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('N2',Yii::t('comp_statistics', 'day_sum'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('N2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('N2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //$objectPHPExcel->getActiveSheet()->mergeCells('S1:T1');

        // //设置居中
        // $objectPHPExcel->getActiveSheet()->getStyle('A3:D3')
        //     ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // //设置边框
        // $objectPHPExcel->getActiveSheet()->getStyle('A3:D3' )
        //     ->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        // //设置颜色
        // $objectPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFill()
        //     ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');
        //写入数据
        $mc_sum = 0;
        $ptw_sum = 0;
        $tbm_sum =0;
        $ins_sum =0;
        $met_sum=0;
        $train_sum=0;
        $ra_sum=0;
        $checklist_sum=0;
        $incident_sum=0;
        $qa_sum=0;
        $ptw_staffs = 0;
        $tbm_staffs = 0;
        $ins_staffs =0;
        $met_staffs =0;
        $train_staffs =0;
        $ra_staffs=0;
        $checklist_staffs=0;
        $incident_staffs=0;
        $contractor_list = Contractor::compList();//承包商列表
        if($rows){
            $n = 3;
            foreach($rows as $i=>$row){
                $mc_sum++;
                //$record_time = date('Y-m-d',strtotime('-1 day',strtotime($row['record_time'])));
                $objectPHPExcel->getActiveSheet()->setCellValue('A'.($n),Utils::DateToEn($row['date']));
                $objectPHPExcel->getActiveSheet()->setCellValue('B'.($n),$contractor_list[$row['con_id']]);
                $objectPHPExcel->getActiveSheet()->setCellValue('C'.($n),$row['ptw_cnt']);
                $ptw_sum += $row['ptw_cnt'];
                $objectPHPExcel->getActiveSheet()->setCellValue('D'.($n),$row['ptw_pcnt']);
                $ptw_staffs += $row['ptw_pcnt'];
                $objectPHPExcel->getActiveSheet()->setCellValue('E'.($n),$row['tbm_cnt']);
                $tbm_sum += $row['tbm_cnt'];
                $objectPHPExcel->getActiveSheet()->setCellValue('F'.($n),$row['tbm_pcnt']);
                $tbm_staffs += $row['tbm_pcnt'];
                $objectPHPExcel->getActiveSheet()->setCellValue('G'.($n),$row['ins_cnt']);
                $ins_sum += $row['ins_cnt'];
                $objectPHPExcel->getActiveSheet()->setCellValue('H'.($n),$row['mee_cnt']);
                $met_sum += $row['mee_cnt'];
                $objectPHPExcel->getActiveSheet()->setCellValue('I'.($n),$row['mee_pcnt']);
                $met_staffs += $row['mee_pcnt'];
                $objectPHPExcel->getActiveSheet()->setCellValue('J'.($n),$row['tra_cnt']);
                $train_sum += $row['tra_cnt'];
                $objectPHPExcel->getActiveSheet()->setCellValue('K'.($n),$row['tra_pcnt']);
                $train_staffs += $row['tra_pcnt'];
                $objectPHPExcel->getActiveSheet()->setCellValue('L'.($n),$row['ra_cnt']);
                $ra_sum += $row['ra_cnt'];
                $objectPHPExcel->getActiveSheet()->setCellValue('M'.($n),$row['che_cnt']);
                $checklist_sum += $row['che_cnt'];
                $objectPHPExcel->getActiveSheet()->setCellValue('N'.($n),$row['inc_cnt']);
                $incident_sum += $row['inc_cnt'];
                //设置边框
                // $currentRowNum = $n+4;
                // $objectPHPExcel->getActiveSheet()->getStyle('A'.($n+4).':D'.$currentRowNum )
                //     ->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $n++;
            }
        }else{
            static $n = 1;
        }
        //合并列
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue("A".$n,Yii::t('comp_statistics', 'day_toatl'));
        $objectPHPExcel->getActiveSheet()->setCellValue("B".$n,$mc_sum);
        $objectPHPExcel->getActiveSheet()->setCellValue("C".$n,$ptw_sum);
        $objectPHPExcel->getActiveSheet()->setCellValue("D".$n,$ptw_staffs);
        $objectPHPExcel->getActiveSheet()->setCellValue("E".$n,$tbm_sum);
        $objectPHPExcel->getActiveSheet()->setCellValue("F".$n,$tbm_staffs);
        $objectPHPExcel->getActiveSheet()->setCellValue("G".$n,$ins_sum);
        $objectPHPExcel->getActiveSheet()->setCellValue("H".$n,$met_sum);
        $objectPHPExcel->getActiveSheet()->setCellValue("I".$n,$met_staffs);
        $objectPHPExcel->getActiveSheet()->setCellValue("J".$n,$train_sum);
        $objectPHPExcel->getActiveSheet()->setCellValue("K".$n,$train_staffs);
        $objectPHPExcel->getActiveSheet()->setCellValue("L".$n,$ra_sum);
        $objectPHPExcel->getActiveSheet()->setCellValue("M".$n,$checklist_sum);
        $objectPHPExcel->getActiveSheet()->setCellValue("N".$n,$incident_sum);
        //下载输出
        ob_end_clean();
        //ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.Yii::t('comp_statistics', 'daily_statistics').'-'.date("Ymj").'.xls"');
        $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter->save('php://output');
    }
    /**
     * 日统计Lite版导出Excel exportlite
     */
    public function actionExportlite(){
        $page = 'all';
        $args = $_GET['q'];
        if($args['status'] == ''){
            $args['status'] = '0';
        }
        $args['contractor_type'] = Staff::CONTRACTOR_TYPE_SC;
        //$args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $tag_date = '2018-01-01';
        $tag_time = strtotime($tag_date);
        if($args['end_date']){
            $end_date = Utils::DateToCn($args['end_date']);
            $post_time = strtotime($end_date);
        }else{
            $post_time = $tag_time-1;
        }
        if($post_time > $tag_time ){
            $list = StatsDateApp::queryListByDate($page, $this->pageSize, $args);
        }else{
            $list = Statistic::queryOldDay($page, $this->pageSize, $args);
        }
        $rows = $list['all'];
        //为指定表单制定表格
        //echo Yii::app()->basePath;
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();
        $objectPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objectPHPExcel->getActiveSheet();
        $objActSheet->setTitle('Sheet1');
        //spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭yii的自动加载功能

        //报表头的输出
        $objectPHPExcel->getActiveSheet()->mergeCells('A1:A2');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('A1',Yii::t('comp_statistics', 'date'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->mergeCells('B1:B2');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('B1',Yii::t('comp_statistics', 'sc_program'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->mergeCells('C1:D1');
        $objectPHPExcel->getActiveSheet()->setCellValue('C1','PTW');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('C1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('C1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('C2',Yii::t('comp_statistics', 'day_sum'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('C2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('C2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('D2',Yii::t('comp_statistics', 'day_staffs'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('D2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('D2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->mergeCells('E1:F1');
        $objectPHPExcel->getActiveSheet()->setCellValue('E1','TBM');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('E1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('E1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('E2',Yii::t('comp_statistics', 'day_sum'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('E2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('E2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('F2',Yii::t('comp_statistics', 'day_staffs'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('F2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('F2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //$objectPHPExcel->getActiveSheet()->mergeCells('F1:H1');
        $objectPHPExcel->getActiveSheet()->setCellValue('G1','Inspection');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('G1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('G1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('G2',Yii::t('comp_statistics', 'day_sum'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('G2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('G2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    
        $objectPHPExcel->getActiveSheet()->setCellValue('H1','Incident');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('H1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('H1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('H2',Yii::t('comp_statistics', 'day_sum'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('H2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('H2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //写入数据
        $mc_sum = 0;
        $ptw_sum = 0;
        $tbm_sum =0;
        $ins_sum =0;
        $incident_sum=0;
        $qa_sum=0;
        $ptw_staffs = 0;
        $tbm_staffs = 0;
        $ins_staffs =0;
        $incident_staffs=0;
        $contractor_list = Contractor::compList();//承包商列表
        if($rows){
            $n = 3;
            foreach($rows as $i=>$row){
                $mc_sum++;
                //$record_time = date('Y-m-d',strtotime('-1 day',strtotime($row['record_time'])));
                $objectPHPExcel->getActiveSheet()->setCellValue('A'.($n),Utils::DateToEn($row['date']));
                $objectPHPExcel->getActiveSheet()->setCellValue('B'.($n),$contractor_list[$row['con_id']]);
                $objectPHPExcel->getActiveSheet()->setCellValue('C'.($n),$row['ptw_cnt']);
                $ptw_sum += $row['ptw_cnt'];
                $objectPHPExcel->getActiveSheet()->setCellValue('D'.($n),$row['ptw_pcnt']);
                $ptw_staffs += $row['ptw_pcnt'];
                $objectPHPExcel->getActiveSheet()->setCellValue('E'.($n),$row['tbm_cnt']);
                $tbm_sum += $row['tbm_cnt'];
                $objectPHPExcel->getActiveSheet()->setCellValue('F'.($n),$row['tbm_pcnt']);
                $tbm_staffs += $row['tbm_pcnt'];
                $objectPHPExcel->getActiveSheet()->setCellValue('G'.($n),$row['ins_cnt']);
                $ins_sum += $row['ins_cnt'];
                $objectPHPExcel->getActiveSheet()->setCellValue('H'.($n),$row['inc_cnt']);
                $incident_sum += $row['inc_cnt'];
                //设置边框
                // $currentRowNum = $n+4;
                // $objectPHPExcel->getActiveSheet()->getStyle('A'.($n+4).':D'.$currentRowNum )
                //     ->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $n++;
            }
        }else{
            static $n = 1;
        }
        //合并列
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue("A".$n,Yii::t('comp_statistics', 'day_toatl'));
        $objectPHPExcel->getActiveSheet()->setCellValue("B".$n,$mc_sum);
        $objectPHPExcel->getActiveSheet()->setCellValue("C".$n,$ptw_sum);
        $objectPHPExcel->getActiveSheet()->setCellValue("D".$n,$ptw_staffs);
        $objectPHPExcel->getActiveSheet()->setCellValue("E".$n,$tbm_sum);
        $objectPHPExcel->getActiveSheet()->setCellValue("F".$n,$tbm_staffs);
        $objectPHPExcel->getActiveSheet()->setCellValue("G".$n,$ins_sum);
        $objectPHPExcel->getActiveSheet()->setCellValue("H".$n,$incident_sum);
        //下载输出
        ob_end_clean();
        //ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.Yii::t('comp_statistics', 'daily_statistics').'-'.date("Ymj").'.xls"');
        $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter->save('php://output');
    }

    /**
     * 月统计完整版导出Excel
     */
    public function actionMonthExport(){
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        //$_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q'];
        if($args['status'] == ''){
            $args['status'] = '0';
        }
        $args['contractor_type'] = Staff::CONTRACTOR_TYPE_SC;
        if($args['month']){
            $month = Utils::MonthToCn($args['month']);
            $mon_arg = explode('-',$month);
            $query_month = $mon_arg[0];
        }else{
            $query_month = 2018;
        }

        $tag_month = 2018;

        if($query_month >= $tag_month ){
            $list = StatsDateApp::queryListByMonth($page, $this->pageSize, $args);
        }else{
            $list = Statistic::queryOldMonth($page, $this->pageSize, $args);
        }
        //$args['contractor_id'] = Yii::app()->user->getState('contractor_id');

        $rows = $list['all'];
        //为指定表单制定表格
        //echo Yii::app()->basePath;
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();
        $objectPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objectPHPExcel->getActiveSheet();
        $objActSheet->setTitle('Sheet1');
        //spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭yii的自动加载功能

        //报表头的输出
        $objectPHPExcel->getActiveSheet()->mergeCells('A1:A2');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('A1',Yii::t('comp_statistics', 'date'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->mergeCells('B1:B2');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('B1',Yii::t('comp_statistics', 'sc_program'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //$objectPHPExcel->getActiveSheet()->mergeCells('C1:D1');
        $objectPHPExcel->getActiveSheet()->setCellValue('C1','PTW');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('C1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('C1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('C2',Yii::t('comp_statistics', 'day_sum'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('C2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('C2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // $objectPHPExcel->getActiveSheet()->setCellValue('D2',Yii::t('comp_statistics', 'day_staffs'));
        // $objectPHPExcel->setActiveSheetIndex(0)->getStyle('D2')->getFont()->setSize(10);
        // $objectPHPExcel->setActiveSheetIndex(0)->getStyle('D2')
        //     ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->mergeCells('D1:E1');
        $objectPHPExcel->getActiveSheet()->setCellValue('D1','TBM');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('D1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('D1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('D2',Yii::t('comp_statistics', 'day_sum'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('D2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('D2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('E2',Yii::t('comp_statistics', 'day_staffs'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('E2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('E2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // $objectPHPExcel->getActiveSheet()->mergeCells('F1:H1');
        $objectPHPExcel->getActiveSheet()->setCellValue('F1','Inspection');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('F1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('F1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('F2',Yii::t('comp_statistics', 'day_sum'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('F2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('F2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // $objectPHPExcel->getActiveSheet()->setCellValue('H2',Yii::t('comp_statistics', 'day_staffs'));
        // $objectPHPExcel->setActiveSheetIndex(0)->getStyle('H2')->getFont()->setSize(10);
        // $objectPHPExcel->setActiveSheetIndex(0)->getStyle('H2')
        //     ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->mergeCells('G1:H1');
        $objectPHPExcel->getActiveSheet()->setCellValue('G1','Meeting');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('G1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('G1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('G2',Yii::t('comp_statistics', 'day_sum'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('G2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('G2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('H2',Yii::t('comp_statistics', 'day_staffs'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('H2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('H2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->mergeCells('I1:J1');
        $objectPHPExcel->getActiveSheet()->setCellValue('I1','Training');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('I1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('I1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('I2',Yii::t('comp_statistics', 'day_sum'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('I2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('I2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('J2',Yii::t('comp_statistics', 'day_staffs'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('J2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('J2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // $objectPHPExcel->getActiveSheet()->mergeCells('M1:N1');
        $objectPHPExcel->getActiveSheet()->setCellValue('K1','RA');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('K1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('K1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('K2',Yii::t('comp_statistics', 'day_sum'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('K2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('K2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // $objectPHPExcel->getActiveSheet()->mergeCells('O1:P1');
        $objectPHPExcel->getActiveSheet()->setCellValue('L1','Checklist');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('L1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('L1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('L2',Yii::t('comp_statistics', 'day_sum'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('L2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('L2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // $objectPHPExcel->getActiveSheet()->mergeCells('Q1:R1');
        $objectPHPExcel->getActiveSheet()->setCellValue('M1','Incident');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('M1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('M1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('M2',Yii::t('comp_statistics', 'day_sum'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('M2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('M2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // $objectPHPExcel->getActiveSheet()->mergeCells('S1:T1');
        $objectPHPExcel->getActiveSheet()->setCellValue('N1','QA');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('N1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('N1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('N2',Yii::t('comp_statistics', 'day_sum'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('N2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('N2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        // //设置居中
        // $objectPHPExcel->getActiveSheet()->getStyle('A3:D3')
        //     ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // //设置边框
        // $objectPHPExcel->getActiveSheet()->getStyle('A3:D3' )
        //     ->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        // //设置颜色
        // $objectPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFill()
        //     ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');
        //写入数据
        $mc_sum = 0;
        $ptw_sum = 0;
        $tbm_sum =0;
        $ins_sum =0;
        $met_sum=0;
        $train_sum=0;
        $ra_sum=0;
        $checklist_sum=0;
        $incident_sum=0;
        $qa_sum=0;
        $tbm_staffs = 0;
        $ins_staffs =0;
        $met_staffs =0;
        $train_staffs =0;
        $contractor_list = Contractor::compList();//承包商列表
        if($rows){
            $n = 3;
            foreach($rows as $i=>$row){
                $mc_sum++;
                $record_time = date('Y-m-d',strtotime('-1 day',strtotime($row['record_time'])));
                $objectPHPExcel->getActiveSheet()->setCellValue('A'.($n),Utils::DateToEn($row['date']));
                $objectPHPExcel->getActiveSheet()->setCellValue('B'.($n),$contractor_list[$row['con_id']]);
                $objectPHPExcel->getActiveSheet()->setCellValue('C'.($n),$row['ptw_cnt']);
                $ptw_sum += $row['ptw_cnt'];
                $objectPHPExcel->getActiveSheet()->setCellValue('D'.($n),$row['tbm_cnt']);
                $tbm_sum += $row['tbm_cnt'];
                $objectPHPExcel->getActiveSheet()->setCellValue('E'.($n),$row['tbm_pcnt']);
                $tbm_staffs += $row['tbm_pcnt'];
                $objectPHPExcel->getActiveSheet()->setCellValue('F'.($n),$row['ins_cnt']);
                $ins_sum += $row['ins_cnt'];
                $objectPHPExcel->getActiveSheet()->setCellValue('G'.($n),$row['mee_cnt']);
                $met_sum += $row['mee_cnt'];
                $objectPHPExcel->getActiveSheet()->setCellValue('H'.($n),$row['mee_pcnt']);
                $met_staffs += $row['mee_pcnt'];
                $objectPHPExcel->getActiveSheet()->setCellValue('I'.($n),$row['tra_cnt']);
                $train_sum += $row['train_sum'];
                $objectPHPExcel->getActiveSheet()->setCellValue('J'.($n),$row['tra_pcnt']);
                $train_staffs += $row['tra_pcnt'];
                $objectPHPExcel->getActiveSheet()->setCellValue('K'.($n),$row['ra_cnt']);
                $ra_sum += $row['ra_cnt'];
                $objectPHPExcel->getActiveSheet()->setCellValue('L'.($n),$row['che_cnt']);
                $checklist_sum += $row['che_cnt'];
                $objectPHPExcel->getActiveSheet()->setCellValue('M'.($n),$row['inc_cnt']);
                $incident_sum += $row['inc_cnt'];
                $objectPHPExcel->getActiveSheet()->setCellValue('N'.($n),$row['qa_cnt']);
                $qa_sum += $row['qa_cnt'];
                //设置边框
                // $currentRowNum = $n+4;
                // $objectPHPExcel->getActiveSheet()->getStyle('A'.($n+4).':D'.$currentRowNum )
                //     ->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $n++;
            }
        }else{
            static $n = 1;
        }
        //合并列
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue("A".$n,Yii::t('comp_statistics', 'day_toatl'));
        $objectPHPExcel->getActiveSheet()->setCellValue("B".$n,$mc_sum);
        $objectPHPExcel->getActiveSheet()->setCellValue("C".$n,$ptw_sum);
        $objectPHPExcel->getActiveSheet()->setCellValue("D".$n,$tbm_sum);
        $objectPHPExcel->getActiveSheet()->setCellValue("E".$n,$tbm_staffs);
        $objectPHPExcel->getActiveSheet()->setCellValue("F".$n,$ins_sum);
        $objectPHPExcel->getActiveSheet()->setCellValue("G".$n,$met_sum);
        $objectPHPExcel->getActiveSheet()->setCellValue("H".$n,$met_staffs);
        $objectPHPExcel->getActiveSheet()->setCellValue("I".$n,$train_sum);
        $objectPHPExcel->getActiveSheet()->setCellValue("J".$n,$train_staffs);
        $objectPHPExcel->getActiveSheet()->setCellValue("K".$n,$ra_sum);
        $objectPHPExcel->getActiveSheet()->setCellValue("L".$n,$checklist_sum);
        $objectPHPExcel->getActiveSheet()->setCellValue("M".$n,$incident_sum);
        $objectPHPExcel->getActiveSheet()->setCellValue("N".$n,$qa_sum);
        //下载输出
        ob_end_clean();
        //ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.Yii::t('comp_statistics', 'month_statistics').'-'.date("Ymj").'.xls"');
        $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter->save('php://output');
    }
    /**
     * 月统计Lite版导出Excel
     */
    public function actionMonthExportlite(){
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        //$_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q'];
        if($args['status'] == ''){
            $args['status'] = '0';
        }
        $args['contractor_type'] = Staff::CONTRACTOR_TYPE_SC;
        if($args['month']){
            $month = Utils::MonthToCn($args['month']);
            $mon_arg = explode('-',$month);
            $query_month = $mon_arg[0];
        }else{
            $query_month = 2018;
        }

        $tag_month = 2018;

        if($query_month >= $tag_month ){
            $list = StatsDateApp::queryListByMonth($page, $this->pageSize, $args);
        }else{
            $list = Statistic::queryOldMonth($page, $this->pageSize, $args);
        }
        //$args['contractor_id'] = Yii::app()->user->getState('contractor_id');

        $rows = $list['all'];
        //为指定表单制定表格
        //echo Yii::app()->basePath;
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();
        $objectPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objectPHPExcel->getActiveSheet();
        $objActSheet->setTitle('Sheet1');
        //spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭yii的自动加载功能

        //报表头的输出
        $objectPHPExcel->getActiveSheet()->mergeCells('A1:A2');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('A1',Yii::t('comp_statistics', 'date'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->mergeCells('B1:B2');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('B1',Yii::t('comp_statistics', 'sc_program'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //$objectPHPExcel->getActiveSheet()->mergeCells('C1:D1');
        $objectPHPExcel->getActiveSheet()->setCellValue('C1','PTW');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('C1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('C1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('C2',Yii::t('comp_statistics', 'day_sum'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('C2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('C2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // $objectPHPExcel->getActiveSheet()->setCellValue('D2',Yii::t('comp_statistics', 'day_staffs'));
        // $objectPHPExcel->setActiveSheetIndex(0)->getStyle('D2')->getFont()->setSize(10);
        // $objectPHPExcel->setActiveSheetIndex(0)->getStyle('D2')
        //     ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->mergeCells('D1:E1');
        $objectPHPExcel->getActiveSheet()->setCellValue('D1','TBM');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('D1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('D1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('D2',Yii::t('comp_statistics', 'day_sum'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('D2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('D2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('E2',Yii::t('comp_statistics', 'day_staffs'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('E2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('E2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // $objectPHPExcel->getActiveSheet()->mergeCells('F1:H1');
        $objectPHPExcel->getActiveSheet()->setCellValue('F1','Inspection');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('F1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('F1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('F2',Yii::t('comp_statistics', 'day_sum'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('F2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('F2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('G1','Incident');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('G1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('G1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('G2',Yii::t('comp_statistics', 'day_sum'));
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('G2')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('G2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
        // //设置居中
        // $objectPHPExcel->getActiveSheet()->getStyle('A3:D3')
        //     ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // //设置边框
        // $objectPHPExcel->getActiveSheet()->getStyle('A3:D3' )
        //     ->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        // //设置颜色
        // $objectPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFill()
        //     ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');
        //写入数据
        $mc_sum = 0;
        $ptw_sum = 0;
        $tbm_sum =0;
        $ins_sum =0;
        $incident_sum=0;
        $tbm_staffs = 0;
        $ins_staffs =0;
        $contractor_list = Contractor::compList();//承包商列表
        if($rows){
            $n = 3;
            foreach($rows as $i=>$row){
                $mc_sum++;
                $record_time = date('Y-m-d',strtotime('-1 day',strtotime($row['record_time'])));
                $objectPHPExcel->getActiveSheet()->setCellValue('A'.($n),Utils::DateToEn($row['date']));
                $objectPHPExcel->getActiveSheet()->setCellValue('B'.($n),$contractor_list[$row['con_id']]);
                $objectPHPExcel->getActiveSheet()->setCellValue('C'.($n),$row['ptw_cnt']);
                $ptw_sum += $row['ptw_cnt'];
                $objectPHPExcel->getActiveSheet()->setCellValue('D'.($n),$row['tbm_cnt']);
                $tbm_sum += $row['tbm_cnt'];
                $objectPHPExcel->getActiveSheet()->setCellValue('E'.($n),$row['tbm_pcnt']);
                $tbm_staffs += $row['tbm_pcnt'];
                $objectPHPExcel->getActiveSheet()->setCellValue('F'.($n),$row['ins_cnt']);
                $ins_sum += $row['ins_cnt'];
                $objectPHPExcel->getActiveSheet()->setCellValue('G'.($n),$row['inc_cnt']);
                $incident_sum += $row['inc_cnt'];
                //设置边框
                // $currentRowNum = $n+4;
                // $objectPHPExcel->getActiveSheet()->getStyle('A'.($n+4).':D'.$currentRowNum )
                //     ->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $n++;
            }
        }else{
            static $n = 1;
        }
        //合并列
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue("A".$n,Yii::t('comp_statistics', 'day_toatl'));
        $objectPHPExcel->getActiveSheet()->setCellValue("B".$n,$mc_sum);
        $objectPHPExcel->getActiveSheet()->setCellValue("C".$n,$ptw_sum);
        $objectPHPExcel->getActiveSheet()->setCellValue("D".$n,$tbm_sum);
        $objectPHPExcel->getActiveSheet()->setCellValue("E".$n,$tbm_staffs);
        $objectPHPExcel->getActiveSheet()->setCellValue("F".$n,$ins_sum);
        $objectPHPExcel->getActiveSheet()->setCellValue("G".$n,$incident_sum);
        //下载输出
        ob_end_clean();
        //ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.Yii::t('comp_statistics', 'month_statistics').'-'.date("Ymj").'.xls"');
        $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter->save('php://output');
    }


    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['module/list'] = str_replace("r=statistics/module/grid", "r=statistics/module/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }
}
