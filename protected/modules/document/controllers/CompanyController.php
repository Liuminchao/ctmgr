<?php

/**
 * 企业文档管理
 * @author LiuMinchao
 */
class CompanyController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';
    const STATUS_NORMAL = 0; //正常
    public $layout = '//layouts/main_1';

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('comp_document', 'contentHeader_company');
        $this->bigMenu = Yii::t('comp_document', 'bigMenu_company');
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=document/company/grid';
        $t->updateDom = 'datagrid';
        // $t->set_header('文档编号', '', '');
        $t->set_header(Yii::t('comp_document', 'document_name'), '', 'center');
        $t->set_header(Yii::t('comp_document', 'commonly_used'), '', 'center');
        $t->set_header(Yii::t('comp_document', 'label'), '', 'center');
        $t->set_header(Yii::t('comp_document', 'upload_time'),'','center');
        $t->set_header(Yii::t('common', 'action'), '15%', 'center');
        return $t;
    }

    /**
     * 查询
     */
    public function actionGrid() {
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件

        $t = $this->genDataGrid();
        $args['type'] = 2;
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        //$this->saveUrl();
        $list = Document::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'],'rs' =>$rs, 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {

        $this->smallHeader = Yii::t('comp_document', 'smallHeader List Company');
        $this->render('list');
    }

    /**
     * 展示
     */
    public function actionShow() {
        $this->render('show');
    }
    /**
     * 上传
     */
    public function actionUpload() {
        $this->renderPartial('upload');
    }
    /**
     * 来源
     */
    public function actionSource() {
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $sql = "SELECT label_id,label_name,label_name_en FROM bac_document_label where type=2 and contractor_id = '".$contractor_id."' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if(count($rows) <= 0 ){
            $sql = "SELECT label_id,label_name,label_name_en FROM bac_document_label where type=2 and (contractor_id='' or contractor_id is Null )";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
        }

        $i = 1;
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$i]['id'] = $row['label_id'];
                if (Yii::app()->language == 'zh_CN') {
                    $rs[$i]['name'] = $row['label_name'];
                }else{
                    $rs[$i]['name'] = $row['label_name_en'];
                }
                $i++;
            }
        }
        print_r( json_encode($rs,true));
    }
    /**
     * 将上传的图片移动到正式路径下
     */
    public function actionMove() {
        $file_src = $_REQUEST['file_src'];
        $args['type'] = 2;
        $r = Document::movePic($file_src,$args);
        print_r(json_encode($r));
    }
    /**
     * 设置标签
     */
    public function actionSettags() {
        $doc_id = $_REQUEST['doc_id'];
        $label_id = $_REQUEST['value'];
        $rs = Document::SetTag($doc_id,$label_id);
        echo json_encode($rs);
    }
    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['document/list'] = str_replace("r=document/platform/grid", "r=document/platform/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }
    /**
     * 设置常用
     */
    public function actionSetused() {
        $doc_id = trim($_REQUEST['doc_id']);
        $doc_use = trim($_REQUEST['doc_use']);
        $r = array();
        $r = Document::setUsed($doc_id,$doc_use);
        //var_dump($r);
        echo json_encode($r);
    }
    /**
     * 在线预览文件
     */
    public function actionPreview() {
        $doc_path = $_REQUEST['doc_path'];
        $doc_id = $_REQUEST['doc_id'];
        $this->renderPartial('preview',array('doc_path'=>$doc_path,'doc_id'=>$doc_id));
    }
    /**
     * 删除文档
     */
    public function actionDelete() {
        $doc_id = trim($_REQUEST['doc_id']);
        $doc_path = trim($_REQUEST['doc_path']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = Document::deleteFile($doc_id,$doc_path);
        }
        echo json_encode($r);
    }

    /**
     * 下载文档
     */
    public function actionDownload() {
        $doc_path = $_REQUEST['doc_path'];
        $rows = Document::queryFile($doc_path);
        if(count($rows)>0){
            $show_name = $rows[0]['doc_name'];
            $filepath = '/opt/www-nginx/web'.$rows[0]['doc_path'];
            $extend = $rows[0]['doc_type'];
            Utils::Download($filepath, $show_name, $extend);
            return;
        }
    }

    /**
     * 下载文档
     */
    public function actionDownloadAttachment() {
        $doc_id = $_REQUEST['doc_id'];
        $exist_data = Document::model()->count('doc_id=:doc_id', array('doc_id' => $doc_id));
        $document_model = Document::model()->findByPk($doc_id);
        $doc_path = $document_model->doc_path;
        $doc_name = $document_model->doc_name;
        $doc_type = $document_model->doc_type;
        if ($exist_data != 0) {
            $show_name = $doc_name;
            $filepath = '/opt/www-nginx/web'.$doc_path;
            $extend = $doc_type;
            Utils::Download($filepath, $show_name, $extend);
            return;
        }
    }
}
