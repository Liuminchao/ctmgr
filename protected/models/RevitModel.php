<?php

/**
 * RevitModel
 *
 * @author liuxy
 */
class RevitModel extends CActiveRecord {

    //状态
    public static function statusText($key = null) {
        $rs = array(
            '0' => Yii::t('common', 'normal'),
            '1' => Yii::t('common', 'expiring'),
            '2' => Yii::t('common', 'expired'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            '0'=> 'label-info',
            '1' => 'label-success',
            '2' => 'label-danger',
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
    public static function queryList($project_id) {

        $contractor_id = Yii::app()->user->getState('contractor_id');
        $pro_model =Program::model()->findByPk($project_id);
        if($pro_model->main_conid != $contractor_id) {
            $root_proid = $pro_model->root_proid;
        }else{
            $root_proid = $project_id;
        }
        $operator_id = Yii::app()->user->id;
        $user = Staff::userByPhone($operator_id);
        $user_id = $user[0]['user_id'];
        $data = array(
            'uid' => $user_id,
            'token' => 'lalala',
            'project_id' => $root_proid,
            'page' => '1',
            'pagesize' => '10000',
        );
        foreach ($data as $key => $value) {
            $post_data[$key] = $value;
        }
        $data = json_encode($post_data);
        $module = 'ProjRevitModelList';
        $url = "https://shell.cmstech.sg/cms_bim/dbapi?cmd=".$module."";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true); //post提交
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $rs = json_decode($output,true);
        $r = array();
        $x = 0;
        if($rs['code'] == 0 ){
            foreach ($rs['result']['result'] as $k => $v){
                $r[$x] = $v;
                $x++;
            }
        }
        if ($output === FALSE) {
            echo "cURL Error: " . curl_error($ch);
            return null;
        }
        // 4. 释放curl句柄
        curl_close($ch);

        $res['status'] = 0;
        $res['desc'] = '成功';
        $res['rows'] = $r;

        return $r;
    }
}