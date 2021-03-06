<?php
class BachCommand extends CConsoleCommand
{
    //php /opt/www-nginx/web/test/ctmgr/protected/yiic bach devicethumbimg
    public function actionUserQrcode(){
        $sql = "select qrcode,contractor_id,user_id from bac_staff  ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        foreach($rows as $key => $value){
            if(!isset($value['qrcode'])){
                Staff::buildQrCode($value['contractor_id'],$value['user_id']);
                echo 'OK';
            }
        }
    }
    public function actionDeviceQrcode(){
        $sql = "select qrcode,contractor_id,primary_id from bac_device ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        foreach($rows as $key => $value){
            if(!isset($value['qrcode'])){
                Device::buildQrCode($value['contractor_id'],$value['primary_id']);
                echo 'OK';
            }
        }
    }

    public function actionSelectThumbImg(){
        $sql = "SELECT a.user_id,b.face_img FROM bac_staff a,bac_staff_info b where a.user_id = b.user_id and b.face_img <> '' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $index = 0;
        foreach($rows as $key => $value) {
            if (file_exists($value['face_img'])) {
                $filepath_array = explode('/',$value['face_img']);
                $count = count($filepath_array);
                $file = 'thumb_'.$filepath_array[$count-1];
                $contractor_id =  $filepath_array[$count-2];
                $filepath = '/opt/www-nginx/web/filebase/data/face/'.$contractor_id.'/'.$file;
                if(!file_exists($filepath)){
                    echo $value['user_id'];
                    echo '________';
                }
            }else{
                $path = '/opt/www-nginx/web'.$value['face_img'];
                if(file_exists($path)){
                    $img_array = explode('/',$path);
                    $index = count($img_array) -1;
                    $img_array[$index] = 'thumb_'.$img_array[$index];
                    $thumb_img = implode('/',$img_array);
                    $stat = Utils::img2thumb($path, $thumb_img, $width = 0, $height = 100, $cut = 0, $proportion = 0);
                    if($stat){
                        echo 'Resize Image Success!<br />';
                        echo $value['face_img'];
                        echo '<br>';
                        echo $thumb_img;
                        $index++;
                    }else{
                        echo 'Resize Image Fail!';
                    }
                }
            }
        }
        echo $index;
    }

    public function actionDeviceThumbImg(){
        $sql = "SELECT primary_id,device_img FROM bac_device where  device_img <> '' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $index = 0;
        ///filebase/data/device/243/0025676692_device.png
        foreach($rows as $key => $value) {
            if (file_exists($value['device_img'])) {
                $path = $value['device_img'];
                $img_array = explode('/',$path);
                $index = count($img_array) -1;
                $img_array[$index] = 'thumb_'.$img_array[$index];
                $thumb_img = implode('/',$img_array);
                $stat = Utils::img2thumb($path, $thumb_img, $width = 0, $height = 100, $cut = 0, $proportion = 0);
                if($stat){
                    echo 'Resize Image Success!<br />';
                    echo $value['device_img'];
                    echo '<br>';
                    echo $thumb_img;
                    $index++;
                }else{
                    echo 'Resize Image Fail!';
                }
            }else{
                $path = '/opt/www-nginx/web'.$value['device_img'];
                if(file_exists($path)){
                    $img_array = explode('/',$path);
                    $index = count($img_array) -1;
                    $img_array[$index] = 'thumb_'.$img_array[$index];
                    $thumb_img = implode('/',$img_array);
                    $stat = Utils::img2thumb($path, $thumb_img, $width = 0, $height = 100, $cut = 0, $proportion = 0);
                    if($stat){
                        echo 'Resize Image Success!<br />';
                        echo $value['device_img'];
                        echo '<br>';
                        echo $thumb_img;
                        $index++;
                    }else{
                        echo 'Resize Image Fail!';
                    }
                }
            }
        }
        echo $index;
    }

    public function actionBatchThumbImg(){
//        $sql = "select pic from qa_checklist_record_detail where pic <> ''";
        //qa_defect_record_detail
        //ACCI  TBM  PTW   TRAIN
        $sql = "SELECT check_id,current_step,pic FROM `bac_routine_check` where  apply_time >= '2020-01-01' and pic <> '-1' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        foreach($rows as $key => $value) {
            $pic_array = explode('|',$value['pic']);
            foreach($pic_array as $i => $j){
                if(file_exists($j)){
                    $img_array = explode('/',$j);
                    $index = count($img_array) -1;
                    $img_array[$index] = 'thumb_'.$img_array[$index];
                    $thumb_img = implode('/',$img_array);
                    if(!file_exists($thumb_img)){
                        $file_type = Utils::getReailFileType($j);
                        echo $file_type;
                        echo $value['check_id'].'@';
                        echo $value['current_step'].'@';
                        echo $j;
                        echo $thumb_img;
                        $stat = Utils::img2thumb($j, $thumb_img, $width = 0, $height = 100, $cut = 0, $proportion = 0);
                        if($stat){
                            echo 'Resize Image Success!<br />';
                            echo $j;
                            echo '<br>';
                        }else{
                            echo 'Resize Image Fail!';
                        }
                    }
                }
            }
        }
        echo 'Over';
    }

    public function actionThumbImg(){
        $sql = "SELECT a.user_id,b.face_img FROM bac_staff a,bac_staff_info b where a.user_id = b.user_id and b.face_img <> ''  ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        foreach($rows as $key => $value){

            if(file_exists($value['face_img'])){
                $img_array = explode('/',$value['face_img']);
                $index = count($img_array) -1;
                $img_array[$index] = 'thumb_'.$img_array[$index];
                $thumb_img = implode('/',$img_array);
                $stat = Utils::img2thumb($value['face_img'], $thumb_img, $width = 0, $height = 100, $cut = 0, $proportion = 0);
                if($stat){
                    echo 'Resize Image Success!<br />';
                    echo $value['face_img'];
                    echo '<br>';
                    echo $thumb_img;
                }else{
                    echo 'Resize Image Fail!';
                }
            }
        }
    }

    public function actionTest()
    {
        $bca_type= CertificateType::passType();
        $sql = "select a.contractor_id,a.work_no,a.work_pass_type,b.* from bac_staff a,bac_staff_info b where a.user_id =b.user_id and a.status = 0 ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        foreach($rows as $k => $v){
            if($v['bca_photo'] != ''){
                $certificate_type = $bca_type[$v['work_pass_type']];
                if(substr($v['bca_photo'],0,9) == '/filebase')
                    $bca_src =  '/opt/www-nginx/web'.$v['bca_photo'];

                if(file_exists($bca_src)) {
                    $name = substr($v['bca_photo'],25);
                    $file_name = explode('.',$name);
                    $size = filesize($bca_src)/1024;
                    $aptitude_size = sprintf('%.2f',$size);
                    $aptitude_name = $file_name[0];
                    if(array_key_exists('1',$file_name)){
                        $aptitude_type = $file_name[1];
                    }else{
                        $aptitude_type = '';
                    }
                    $model = new UserAptitude('create');
                    try {
                        $model->user_id = $v['user_id'];
                        $model->aptitude_name = $aptitude_name;
                        $model->aptitude_photo = $v['bca_photo'];
                        $model->aptitude_content = $v['work_no'];
                        $model->contractor_id = $v['contractor_id'];
                        $model->permit_startdate = $v['bca_issue_date'];
                        $model->permit_enddate = $v['bca_expire_date'];
                        $model->certificate_type = $certificate_type;
                        $model->aptitude_type = $aptitude_type;
                        $model->aptitude_size = $aptitude_size;
                        $model->save();
                    } catch (Exception $e) {
                        //$trans->rollBack();
                        $r['status'] = -1;
                        $r['msg'] = $e->getmessage();
                        $r['refresh'] = false;
                        return $r;
                    }
                }
            }
            if($v['ppt_photo'] != ''){
                if(substr($v['ppt_photo'],0,9) == '/filebase')
                    $ppt_src =  '/opt/www-nginx/web'.$v['ppt_photo'];

                if(file_exists($ppt_src)) {
                    $name = substr($v['ppt_photo'],25);
                    $file_name = explode('.',$name);
                    $size = filesize($ppt_src)/1024;
                    $aptitude_size = sprintf('%.2f',$size);
                    $aptitude_name = $file_name[0];
                    if(array_key_exists('1',$file_name)){
                        $aptitude_type = $file_name[1];
                    }else{
                        $aptitude_type = '';
                    }
                    $model = new UserAptitude('create');
                    try {
                        $model->user_id = $v['user_id'];
                        $model->aptitude_name = $aptitude_name;
                        $model->aptitude_photo = $v['ppt_photo'];
                        $model->aptitude_content = $v['passport_no'];
                        $model->contractor_id = $v['contractor_id'];
                        $model->permit_startdate = $v['ppt_issue_date'];
                        $model->permit_enddate = $v['ppt_expire_date'];
                        $model->certificate_type = '40';
                        $model->aptitude_type = $aptitude_type;
                        $model->aptitude_size = $aptitude_size;
                        $model->save();
                    } catch (Exception $e) {
                        //$trans->rollBack();
                        $r['status'] = -1;
                        $r['msg'] = $e->getmessage();
                        $r['refresh'] = false;
                        return $r;
                    }
                }
            }
            if($v['csoc_photo'] != ''){
                if(substr($v['csoc_photo'],0,9) == '/filebase')
                    $csoc_src =  '/opt/www-nginx/web'.$v['csoc_photo'];

                if(file_exists($csoc_src)) {
                    $name = substr($v['csoc_photo'],25);
                    $file_name = explode('.',$name);
                    $size = filesize($csoc_src)/1024;
                    $aptitude_size = sprintf('%.2f',$size);
                    $aptitude_name = $file_name[0];
                    if(array_key_exists('1',$file_name)){
                        $aptitude_type = $file_name[1];
                    }else{
                        $aptitude_type = '';
                    }

                    $model = new UserAptitude('create');
                    try {
                        $model->user_id = $v['user_id'];
                        $model->aptitude_name = $aptitude_name;
                        $model->aptitude_photo = $v['csoc_photo'];
                        $model->aptitude_content = $v['csoc_no'];
                        $model->contractor_id = $v['contractor_id'];
                        $model->permit_startdate = $v['csoc_issue_date'];
                        $model->permit_enddate = $v['csoc_expire_date'];
                        $model->certificate_type = '31';
                        $model->aptitude_type = $aptitude_type;
                        $model->aptitude_size = $aptitude_size;
                        $model->save();
                    } catch (Exception $e) {
                        //$trans->rollBack();
                        $r['status'] = -1;
                        $r['msg'] = $e->getmessage();
                        $r['refresh'] = false;
                        return $r;
                    }
                }
            }
        }
    }
    public function  actionParams() {
        $sql = "select * from bac_contractor ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        foreach($rows as $key => $list){
            $proj_cnt = Program::model()->count('contractor_id=:contractor_id AND status=:status', array('contractor_id' => $list['contractor_id'],'status'=>Program::STATUS_NORMAL));
            $con_model = Contractor::model()->findByPk($list['contractor_id']);
            if($proj_cnt <1){
                $params['pro_cnt'] = 1;
                $json_params = json_encode($params);
                $con_model->params = $json_params;
            }else{
                $params['pro_cnt'] = $proj_cnt;
                $json_params = json_encode($params);
                $con_model->params = $json_params;
            }
            $con_model->save();
        }
    }

    public function actionUpdate(){
        $sql = "select * from ptw_apply_basic where program_id = '482' and record_time >='2018-10-28' and record_time <= '2019-06-27' and status = '4' and add_operator = '6|5|1' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        foreach($rows as $i => $j){
            $year = substr($j['record_time'],0,4);//???
            $table = "bac_check_apply_detail_" . $year;
            $step = 4;
            $sql = "select * from $table where apply_id=:apply_id and step=:step";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":apply_id", $j['apply_id'], PDO::PARAM_STR);
            $command->bindParam(":step", $step, PDO::PARAM_STR);
            $r = $command->queryAll();

            $date = substr($r[0]['apply_time'],0,10);
            $date_1 = $date.' 21:45:01';
            $date_2 = $date.' 21:48:03';
            $step_1 = '5';
            $step_2 = '6';

            $sql = 'UPDATE $table set apply_time=:apply_time,deal_time=:deal_time WHERE apply_id=:apply_id and step = :step';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":apply_time", $date_1, PDO::PARAM_STR);
            $command->bindParam(":deal_time", $date_1, PDO::PARAM_STR);
            $command->bindParam(":step", $step_1, PDO::PARAM_STR);
            $command->bindParam(":apply_id", $j['apply_id'], PDO::PARAM_STR);
            $rs = $command->execute();

            $sql = 'UPDATE $table set apply_time=:apply_time,deal_time=:deal_time WHERE apply_id=:apply_id and step = :step';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":apply_time", $date_2, PDO::PARAM_STR);
            $command->bindParam(":deal_time", $date_2, PDO::PARAM_STR);
            $command->bindParam(":step", $step_2, PDO::PARAM_STR);
            $command->bindParam(":apply_id", $j['apply_id'], PDO::PARAM_STR);
            $rs = $command->execute();


//            $date = substr($j['record_time'],0,10);
//            $date_1 = $date.' 21:45:01';
//            $date_2 = $date.' 21:48:03';
//            $params_1['app_id'] = 'PTW';
//            $params_1['deal_type'] = '4';
//            $params_1['step'] = '5';
//            $params_1['deal_user_id'] = '14002';
//            $params_1['remark'] = '';
//            $params_1['longitude'] = '103.6767214';
//            $params_1['address'] = '3E Gul Cir, Singapore 629633';
//            $params_1['latitude'] = '1.3125137';
//            $params_1['pic'] = '-1';
//            $params_1['status'] = '1';
//            $params_1['apply_time'] =$date_1;
//            $params_1['deal_time'] = $date_1;
//            $params_1['check_list'] = '';
//            $sql = "INSERT INTO  $table (apply_id,app_id,deal_type,step,deal_user_id,remark,longitude,address,latitude,pic,status,apply_time,deal_time,check_list)VALUES(:apply_id,:app_id,:deal_type,:step,:deal_user_id,:remark,:longitude,:address,:latitude,:pic,:status,:apply_time,:deal_time,:check_list)";
//            $command = Yii::app()->db->createCommand($sql);
//            $command->bindParam(":apply_id", $j['apply_id'], PDO::PARAM_STR);
//            $command->bindParam(":app_id", $params_1['app_id'], PDO::PARAM_STR);
//            $command->bindParam(":deal_type", $params_1['deal_type'], PDO::PARAM_STR);
//            $command->bindParam(":step", $params_1['step'], PDO::PARAM_STR);
//            $command->bindParam(":deal_user_id", $params_1['deal_user_id'], PDO::PARAM_STR);
//            $command->bindParam(":remark", $params_1['remark'], PDO::PARAM_STR);
//            $command->bindParam(":longitude", $params_1['longitude'], PDO::PARAM_STR);
//            $command->bindParam(":address", $params_1['address'], PDO::PARAM_STR);
//            $command->bindParam(":latitude", $params_1['latitude'], PDO::PARAM_STR);
//            $command->bindParam(":pic", $params_1['pic'], PDO::PARAM_STR);
//            $command->bindParam(":status", $params_1['status'], PDO::PARAM_STR);
//            $command->bindParam(":apply_time", $params_1['apply_time'], PDO::PARAM_STR);
//            $command->bindParam(":deal_time", $params_1['deal_time'], PDO::PARAM_STR);
//            $command->bindParam(":check_list", $params_1['check_list'], PDO::PARAM_STR);
//            $rs = $command->execute();
//
//            $params_2['app_id'] = 'PTW';
//            $params_2['deal_type'] = '5';
//            $params_2['step'] = '6';
//            $params_2['deal_user_id'] = '7277';
//            $params_2['remark'] = '';
//            $params_2['longitude'] = '103.6767214';
//            $params_2['address'] = '3E Gul Cir, Singapore 629633';
//            $params_2['latitude'] = '1.3125137';
//            $params_2['pic'] = '-1';
//            $params_2['status'] = '1';
//            $params_2['apply_time'] =$date_2;
//            $params_2['deal_time'] = $date_2;
//            $params_2['check_list'] = '';
//            $sql = "INSERT INTO  $table (apply_id,app_id,deal_type,step,deal_user_id,remark,longitude,address,latitude,pic,status,apply_time,deal_time,check_list)VALUES(:apply_id,:app_id,:deal_type,:step,:deal_user_id,:remark,:longitude,:address,:latitude,:pic,:status,:apply_time,:deal_time,:check_list)";
//            $command = Yii::app()->db->createCommand($sql);
//            $command->bindParam(":apply_id", $j['apply_id'], PDO::PARAM_STR);
//            $command->bindParam(":app_id", $params_2['app_id'], PDO::PARAM_STR);
//            $command->bindParam(":deal_type",$params_2['deal_type'], PDO::PARAM_STR);
//            $command->bindParam(":step", $params_2['step'], PDO::PARAM_STR);
//            $command->bindParam(":deal_user_id", $params_2['deal_user_id'], PDO::PARAM_STR);
//            $command->bindParam(":remark", $params_2['remark'], PDO::PARAM_STR);
//            $command->bindParam(":longitude", $params_2['longitude'], PDO::PARAM_STR);
//            $command->bindParam(":address", $params_2['address'], PDO::PARAM_STR);
//            $command->bindParam(":latitude", $params_2['latitude'], PDO::PARAM_STR);
//            $command->bindParam(":pic", $params_2['pic'], PDO::PARAM_STR);
//            $command->bindParam(":status", $params_2['status'], PDO::PARAM_STR);
//            $command->bindParam(":apply_time", $params_2['apply_time'], PDO::PARAM_STR);
//            $command->bindParam(":deal_time", $params_2['deal_time'], PDO::PARAM_STR);
//            $command->bindParam(":check_list", $params_2['check_list'], PDO::PARAM_STR);
//            $rs = $command->execute();

//            $params_4['add_operator'] = '6|5|1';
//            $params_4['status'] = '4';
//            $sql = 'UPDATE ptw_apply_basic set add_operator=:add_operator,status=:status WHERE apply_id=:apply_id';
//            $command = Yii::app()->db->createCommand($sql);
//            $command->bindParam(":add_operator", $params_4['add_operator'], PDO::PARAM_STR);
//            $command->bindParam(":status", $params_4['status'], PDO::PARAM_STR);
//            $command->bindParam(":apply_id", $j['apply_id'], PDO::PARAM_STR);
//            $rs = $command->execute();

            echo 'OK';
        }
    }
}