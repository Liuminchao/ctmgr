<?php
class RfController extends BaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = "";
    public $bigMenu = "";

    public function init() {
        parent::init();
        $this->contentHeader = 'RFI/RFA';
        $this->bigMenu = 'Download Report';
    }


    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=rf/rf/grid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('license_licensepdf', 'apply_id'), '', 'center');
        $t->set_header('Ref No.', '', 'center');
        $t->set_header('Subject', '', 'center');
        $t->set_header('Created on', '', 'center');
        $t->set_header('Latest Date to Reply', '', 'center');
        $t->set_header(Yii::t('license_licensepdf', 'status'), '', 'center');
        $t->set_header(Yii::t('common', 'action'), '15%', 'center');
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
        if(count($fields) == 4 && $fields[0] != null ) {
            $args['program_id'] = $fields[0];
        }
        $t = $this->genDataGrid();
        $this->saveUrl();
        $args['contractor_id'] = Yii::app()->user->contractor_id;
        $app_id = 'RFI';

        $list = RfList::queryList($page, $this->pageSize, $args);
//        var_dump($list['rows']);
        $this->renderPartial('_list', array('t' => $t,'program_id'=>$args['program_id'],'app_id'=>$app_id,'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {

        $program_id = $_REQUEST['program_id'];
        $this->smallHeader = 'RFI';
        $this->render('list',array('program_id'=> $program_id));
    }
    /**
     * 类型选择
     */
    public function actionSelectType() {
        $args['program_id'] = $_REQUEST['program_id'];
        $this->renderPartial('select_type',array('program_id'=>$args['program_id']));
    }
    /**
     * 添加
     */
    public function actionAddChat() {
        $args['program_id'] = $_REQUEST['program_id'];
        $args['type'] = $_REQUEST['type'];
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $contractor_list = Program::ProgramAllCompany($args['program_id']);
        $this->render('_form',array('program_id'=>$args['program_id'],'type'=>$args['type'],'data_id'=>$data_id,'contractor_list'=>$contractor_list));
    }

    /**
     * 编辑
     */
    public function actionEditChat() {
        $check_id = $_REQUEST['check_id'];
        $rf_model = RfList::model()->findByPk($check_id);
        $step = $rf_model->step;
        $program_id = $rf_model->program_id;
        $list_list = RfList::dealList($check_id);
        $detail_list = RfDetail::dealList($check_id);
        $detail_info_list = RfDetail::dealListByStep($check_id,$step);
        if($detail_info_list[0]['deal_type'] == '7'){
            $step = $detail_info_list[0]['step'] -1;
        }
        $rf_user_list = RfUser::userListByStep($check_id,$step);
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $user_list = ProgramUser::OperatorListByRf($list_list[0]['program_id'],$contractor_id);
        $to_user_str = '';
        $cc_user_str = '';
        $to_radio = '';
        if(!empty($rf_user_list)){
            foreach($rf_user_list as $i => $j){
                if($j['type'] == '1'){
                    $to_user_str.=$j['user_id'].',';
                }else{
                    $cc_user_str.=$j['user_id'].',';
                }
            }
            $to_user_str = substr($to_user_str, 0, strlen($to_user_str) - 1);
            $cc_user_str = substr($cc_user_str, 0, strlen($cc_user_str) - 1);
        }
        $contractor_list = Program::ProgramAllCompany($program_id);
        $attachment_list = RfAttachment::dealListBystep($check_id,$step);
        $this->render('_editform',array('check_id'=>$check_id,'step'=>$step,'list_list'=>$list_list,'detail_list'=>$detail_list,'user_list'=>$user_list,'rf_user_list'=>$rf_user_list,'contractor_list'=>$contractor_list,'to_user_str'=>$to_user_str,'cc_user_str'=>$cc_user_str));
    }
    /**
     * 详情
     */
    public function actionInfo() {
        $check_id = $_REQUEST['check_id'];
        $rf_model = RfList::model()->findByPk($check_id);
        $step = $rf_model->step;
        $list_list = RfList::dealList($check_id);
        $detail_list = RfDetail::dealList($check_id);
        $user_list = RfUser::dealList($check_id);
        $attachment_list = RfAttachment::dealList($check_id);
        $component_list = RfModelComponent::dealList($check_id,$step);
        $view_list = RfModelView::dealList($check_id,$step);
        $this->render('_info',array('check_id'=>$check_id,'list_list'=>$list_list,'detail_list'=>$detail_list,'user_list'=>$user_list,'attachment_list'=>$attachment_list,'component_list'=>$component_list,'view_list'=>$view_list));
    }
    /**
     * ModelComponent
     */
    public function actionEditComponent() {
        $step = $_REQUEST['step'];
        $check_id = $_REQUEST['check_id'];
        $program_id = $_REQUEST['program_id'];
        $this->renderPartial('edit_component',array('check_id'=>$check_id,'step'=>$step,'program_id'=>$program_id));
    }

    /**
     * ModelView
     */
    public function actionEditView() {
        $step = $_REQUEST['step'];
        $check_id = $_REQUEST['check_id'];
        $program_id = $_REQUEST['program_id'];
        $this->renderPartial('edit_view',array('step'=>$step,'check_id'=>$check_id,'program_id'=>$program_id));
    }

    /**
     * ModelComponent
     */
    public function actionAddComponent() {
        $program_id = $_REQUEST['program_id'];
        $this->renderPartial('add_component',array('program_id'=>$program_id));
    }

    /**
     * ModelView
     */
    public function actionAddView() {
        $program_id = $_REQUEST['program_id'];
        $this->renderPartial('add_view',array('program_id'=>$program_id));
    }

    /**
     * ModelComponent
     */
    public function actionShowComponent() {
        $step = $_REQUEST['step'];
        $check_id = $_REQUEST['check_id'];
        $this->renderPartial('show_component',array('check_id'=>$check_id,'step'=>$step));
    }

    /**
     * ModelView
     */
    public function actionShowView() {
        $step = $_REQUEST['step'];
        $check_id = $_REQUEST['check_id'];
        $this->renderPartial('show_view',array('step'=>$step,'check_id'=>$check_id));
    }

    /**
     * 保存草稿
     */
    public function  actionSaveDraft() {
        $rf = $_REQUEST['rf'];
        $data_id = RfList::queryIndex();
        $rf['data_id'] = $data_id;
        $index =  str_pad((String)$data_id, 5, '0', STR_PAD_LEFT);
        $date = date("Ymd");
        if($rf['type_id'] == '1'){
            $rf['check_id'] = 'CMS-RFI-'.$date.'-'.$index;
        }else{
            $rf['check_id'] = 'CMS-RFA-'.$date.'-'.$index;
        }
        $rf['status'] = RfList::STATUS_DRAFT;
        $rf['deal_type'] = RfDetail::STATUS_DRAFT;
        $r = RfList::insertList($rf);
        print_r(json_encode($r));
    }

    /**
     * 发起
     */
    public function  actionSend() {
        $rf = $_REQUEST['rf'];
        $exist_data = RfList::model()->count('check_id=:check_id',array('check_id' => $rf['check_id']));
        if ($exist_data != 0) {
            $rf['status'] = RfList::STATUS_SUBMIT;
            $rf['deal_type'] = RfDetail::STATUS_SUBMIT;
            $r = RfList::sendList($rf);
        }else{
            $data_id = RfList::queryIndex();
            $rf['data_id'] = $data_id;
            $index =  str_pad((String)$data_id, 5, '0', STR_PAD_LEFT);
            $date = date("Ymd");
            if($rf['type_id'] == '1'){
                $rf['check_id'] = 'CMS-RFI-'.$date.'-'.$index;
            }else{
                $rf['check_id'] = 'CMS-RFA-'.$date.'-'.$index;
            }
            $rf['status'] = RfList::STATUS_SUBMIT;
            $rf['deal_type'] = RfDetail::STATUS_SUBMIT;
            $r = RfList::insertList($rf);
        }
        print_r(json_encode($r));
    }

    /**
     * 结束对话
     */
    public function actionEnd() {

        $args['program_id'] = $_REQUEST['program_id'];
        $args['check_id'] = $_REQUEST['check_id'];
        $operator_id = Yii::app()->user->id;
        $r = RfList::endList($args);
        $this->redirect('index.php?r=rf/rfi/list&program_id='.$args['program_id']);
    }

    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['licensepdf/list'] = str_replace("r=rf/rfi/grid", "r=rf/rfi/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

    /**
     * 在线预览文件
     */
    public function actionPreview() {
        $doc_path = $_REQUEST['doc_path'];
//        var_dump($file_path);
//        exit;
        $this->renderPartial('preview',array('doc_path'=>$doc_path));
    }

    /**
     * 下载文档
     */
    public function actionDownload() {
        $doc_path = $_REQUEST['doc_path'];
        $doc_name = $_REQUEST['doc_name'];
        $filepath = '/opt/www-nginx/web'.$doc_path;
        if(file_exists($filepath)) {
            $extend = substr($filepath,-4,4);
            Utils::Download($filepath, $doc_name, $extend);
            return;
        }
    }

    /**
     * 转发
     */
    public function actionForward() {
        $check_id = $_REQUEST['check_id'];
        $rf_model = RfList::model()->findByPk($check_id);
        $program_id = $rf_model->program_id;
        $contractor_list = Program::ProgramAllCompany($program_id);
        $deal_type = '3';
        $this->smallHeader = 'RFI/RFA';
        $this->render('_returnform',array('check_id'=> $check_id,'contractor_list'=>$contractor_list,'deal_type'=>$deal_type));
    }
    /**
     * 保存转发
     */
    public function actionSaveForward() {
        $rf = $_REQUEST['rf'];
        $r = RfList::forwardList($rf);
        print_r(json_encode($r));
    }

    /**
     * 回复
     */
    public function actionReply() {
        $check_id = $_REQUEST['check_id'];
        $rf_model = RfList::model()->findByPk($check_id);
        $program_id = $rf_model->program_id;
        $contractor_list = Program::ProgramAllCompany($program_id);
        $deal_type = '2';
        $this->smallHeader = 'RFI/RFA';
        $this->render('_returnform',array('check_id'=> $check_id,'contractor_list'=>$contractor_list,'deal_type'=>$deal_type));
    }
    /**
     * 保存回复
     */
    public function actionSaveReply() {
        $rf = $_REQUEST['rf'];
        $r = RfList::replyList($rf);
        print_r(json_encode($r));
    }
    /**
     * 拒绝
     */
    public function actionReject() {
        $check_id = $_REQUEST['check_id'];
        $rf_model = RfList::model()->findByPk($check_id);
        $program_id = $rf_model->program_id;
        $contractor_list = Program::ProgramAllCompany($program_id);
        $deal_type = '6';
        $this->smallHeader = 'RFI/RFA';
        $this->render('_returnform',array('check_id'=> $check_id,'contractor_list'=>$contractor_list,'deal_type'=>$deal_type));
    }
    /**
     * 保存拒绝
     */
    public function actionSaveReject() {
        $rf = $_REQUEST['rf'];
        $r = RfList::rejectList($rf);
        print_r(json_encode($r));
    }
    /**
     * 批准
     */
    public function actionApprove() {
        $check_id = $_REQUEST['check_id'];
        $rf_model = RfList::model()->findByPk($check_id);
        $program_id = $rf_model->program_id;
        $contractor_list = Program::ProgramAllCompany($program_id);
        $deal_type = '4';
        $this->smallHeader = 'RFI/RFA';
        $this->render('_returnform',array('check_id'=> $check_id,'contractor_list'=>$contractor_list,'deal_type'=>$deal_type));
    }
    /**
     * 保存批准
     */
    public function actionSaveApprove() {
        $rf = $_REQUEST['rf'];
        $r = RfList::approveList($rf);
        print_r(json_encode($r));
    }
    /**
     * 批准(带评论)
     */
    public function actionApproveComment() {
        $check_id = $_REQUEST['check_id'];
        $rf_model = RfList::model()->findByPk($check_id);
        $program_id = $rf_model->program_id;
        $contractor_list = Program::ProgramAllCompany($program_id);
        $deal_type = '5';
        $this->smallHeader = 'RFI/RFA';
        $this->render('_returnform',array('check_id'=> $check_id,'contractor_list'=>$contractor_list,'deal_type'=>$deal_type));
    }
    /**
     * 保存批准(带评论)
     */
    public function actionSaveApproveComment() {
        $rf = $_REQUEST['rf'];
        $r = RfList::approveCommentList($rf);
        print_r(json_encode($r));
    }
    /**
     * 撤销
     */
    public function actionWithdraw() {
        $args['check_id'] = $_REQUEST['check_id'];
        $r = RfList::withdrawList($args);
        print_r(json_encode($r));
    }
    /**
     * 确认
     */
    public function actionConfirm() {
        $args['check_id'] = $_REQUEST['check_id'];
        $r = RfList::confirmList($args);
        print_r(json_encode($r));
    }
    /**
     * 关闭
     */
    public function actionClose() {
        $args['check_id'] = $_REQUEST['check_id'];
        $r = RfList::closeList($args);
        print_r(json_encode($r));
    }

    /**
     * 根据项目和承包商查询入场人员
     */
    public function actionQueryUser() {
        $contractor_id = $_POST['from'];
        $program_id = $_POST['program_id'];
        $rows = ProgramUser::UserListByMcProgram($contractor_id,$program_id);

        print_r(json_encode($rows));
    }

    /**
     * 根据项目和承包商查询入场人员
     */
    public function actionModelList() {
        $project_id = $_POST['project_id'];
        $rows = RevitModel::queryList($project_id);
        print_r(json_encode($rows));
    }

    /**
     * 详情
     */
    public static function actionDownloadPdf() {
        $id = $_REQUEST['check_id'];
        $params['id'] = $id;
        $app_id = 'RF';
        $apply = RfList::model()->findByPk($id);//许可证基本信息表
        //报告定制化
        $program_id = $apply->program_id;
        $pro_model = Program::model()->findByPk($program_id);
        $title = $pro_model->program_name;
        $filepath = DownloadPdf::transferDownload($params,$app_id);
        Utils::Download($filepath, $title, 'pdf');
    }

}
