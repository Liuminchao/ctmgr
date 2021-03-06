<?php
class UserTransferCommand extends CConsoleCommand
{
    //0,10 3-4 * * * php /opt/www-nginx/web/test/ctmgr/protected/yiic usertransfer userbach
    //yiic 自定义命令类名称 动作名称 --参数1=参数值 --参数2=参数值 --参数3=参数值……
    public function actionUserBach()
    {
        $contractor_id = '685';
        $transfer_conid = '113';
        $user_id = '15535';
        $sql = "select * from bac_staff where contractor_id = '685' and user_name like '%(Dong Zhou)%' and user_id != '35055' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        foreach($rows as $i => $j){
            echo $j['user_id'];
            $model = Staff::model()->findByPk($j['user_id']);
            $model->status =  '1';
            $model->user_phone =  $j['user_phone'].'[del]';
            $model->work_no =  $j['work_no'].'[del]';
            $model->save();
            $staffinfo_model = StaffInfo::model()->findByPk($j['user_id']);//员工资质信息
            $aptitude_list = UserAptitude::queryAll($j['user_id']);
            $exist_data = Staff::model()->count('user_phone=:user_phone', array('user_phone' => $j['user_phone']));
            if ($exist_data != 0) {
                goto end;
            }
            $exist_dat = Staff::model()->count('work_no=:work_no and user_phone=:user_phone and contractor_id=:contractor_id', array('work_no' => $j['work_no'],'user_phone' => $j['user_phone'],'contractor_id' => $transfer_conid));
            if ($exist_dat !=0){
                goto end;
            }
            $model = new Staff('create');
            if($j['qrcode'] != ''){
                $qr_array = explode('/',$j['qrcode']);
                if(stristr($j['qrcode'],'/opt/www-nginx/web') == false){
                    $qr_array[4] = $transfer_conid;
                    $qr_str = implode('/',$qr_array);
                    $file_1 = '/opt/www-nginx/web'.$j['qrcode'];
                    $file_2 = '/opt/www-nginx/web'.$qr_str;
                }else{
                    $qr_array[7] = $transfer_conid;
                    $qr_str = implode('/',$qr_array);
                    $file_1 = $j['qrcode'];
                    $file_2 = $qr_str;
                }
                if (copy($file_1,$file_2)) {
                    $j['qrcode'] = $qr_str;
                }
            }
            foreach($j as $staff_key => $staff_val){
                if($staff_key != 'contractor_id' && $staff_key != 'original_conid'){
                    if($staff_key != 'user_id'){
                        $model->$staff_key = $staff_val;
                    }
                }else{
                    $model->$staff_key = $transfer_conid;
                }
            }
            $result = $model->save();
            $id = $model->user_id;
            $user_id = $j['user_id'];
            $sql_1 = "select * from bac_staff_info where user_id = $user_id ";
            $command_1 = Yii::app()->db->createCommand($sql_1);
            $rs = $command_1->queryAll();
            $infomodel = new StaffInfo('create');
            if($rs[0]['face_img'] != ''){
                $img_array = explode('/',$rs[0]['face_img']);
                if(stristr($rs[0]['face_img'],'/opt/www-nginx/web') == false){
                    $img_array[4] = $transfer_conid;
                    $img_str = implode('/',$img_array);
                    $file_1 = '/opt/www-nginx/web'.$rs[0]['face_img'];
                    $file_2 = '/opt/www-nginx/web'.$img_str;
                }else{
                    $img_array[7] = $transfer_conid;
                    $img_str = implode('/',$img_array);
                    $file_1 = $rs[0]['face_img'];
                    $file_2 = $img_str;
                }
                if (copy($file_1,$file_2)) {
                    $rs[0]['face_img'] = $img_str;
                }
            }

            foreach($rs[0] as $staff_info_key => $staff_info_val ){
                if($staff_info_key == 'user_id'){
                    $infomodel->$staff_info_key = $id;
                }else{
                    $infomodel->$staff_info_key = $staff_info_val;
                }
            }
            $result = $infomodel->save();
            $sql_2 = "select * from bac_aptitude where user_id = $user_id ";
            $command_2 = Yii::app()->db->createCommand($sql_2);
            $r = $command_2->queryAll();
            if(count($r) > 0){
                foreach($r as $x => $y){
                    $ap_model = UserAptitude::model()->findByPk($y['aptitude_id']);
                    $ap_model->status =  '1';
                    $ap_model->save();
                    $array = explode('/',$y['aptitude_photo']);
                    if(stristr($y['aptitude_photo'],'/opt/www-nginx/web') == false){
                        $array[4] = $transfer_conid;
                        $str = implode('/',$array);
                        $file_1 = '/opt/www-nginx/web'.$y['aptitude_photo'];
                        $file_2 = '/opt/www-nginx/web'.$str;
                    }else{
                        $array[7] = $transfer_conid;
                        $str = implode('/',$array);
                        $file_1 = $y['aptitude_photo'];
                        $file_2 = $str;
                    }
                    if (copy($file_1,$file_2)) {
                        $aptitude_model = new UserAptitude('create');
                        $aptitude_model->user_id = $id;
                        $aptitude_model->aptitude_name = $y['aptitude_name'];
                        $aptitude_model->aptitude_photo = $str;
                        $aptitude_model->aptitude_content = $y['aptitude_content'];
                        $aptitude_model->contractor_id = $transfer_conid;
                        $aptitude_model->permit_startdate = $y['permit_startdate'];
                        $aptitude_model->permit_enddate = $y['permit_enddate'];
                        $aptitude_model->certificate_type = $y['certificate_type'];
                        $aptitude_model->aptitude_type = $y['aptitude_type'];
                        $aptitude_model->aptitude_size = $y['aptitude_size'];
                        $result = $aptitude_model->save();
                    }
                }
            }
            end:
            echo 'Success';
        }
    }

    public function actionDeviceBach()
    {
        $contractor_id = '532';
        $transfer_conid = '337';
        $sql = "select * from bac_device where contractor_id = $contractor_id  and status = '00' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        foreach($rows as $i => $j){
            echo $j['primary_id'];
            $model = Device::model()->findByPk($j['primary_id']);
            $model->status =  '01';
            $model->user_phone =  $j['device_id'].'[del]';
            $model->work_no =  $j['device_name'].'[del]';
            $model->save();
            $exist_data = Device::model()->count('device_id=:device_id and contractor_id=:contractor_id', array('device_id' => $j['device_id'],'contractor_id' => $transfer_conid));
            if ($exist_data != 0) {
                goto end;
            }
            $model = new Device('create');
            if($j['qrcode'] != ''){
                $qr_array = explode('/',$j['qrcode']);
                if(stristr($j['qrcode'],'/opt/www-nginx/web') == false){
                    $qr_array[4] = $transfer_conid;
                    $qr_str = implode('/',$qr_array);
                    $file_1 = '/opt/www-nginx/web'.$j['qrcode'];
                    $file_2 = '/opt/www-nginx/web'.$qr_str;
                }else{
                    $qr_array[7] = $transfer_conid;
                    $qr_str = implode('/',$qr_array);
                    $file_1 = $j['qrcode'];
                    $file_2 = $qr_str;
                }
                if (copy($file_1,$file_2)) {
                    $j['qrcode'] = $qr_str;
                }
            }
            if($j['device_img'] != ''){
                $img_array = explode('/',$j['device_img']);
                if(stristr($j['device_img'],'/opt/www-nginx/web') == false){
                    $img_array[4] = $transfer_conid;
                    $img_str = implode('/',$img_array);
                    $file_1 = '/opt/www-nginx/web'.$j['device_img'];
                    $file_2 = '/opt/www-nginx/web'.$img_str;
                }else{
                    $img_array[7] = $transfer_conid;
                    $img_str = implode('/',$img_array);
                    $file_1 = $j['device_img'];
                    $file_2 = $img_str;
                }
                if (copy($file_1,$file_2)) {
                    $j['device_img'] = $qr_str;
                }
            }
            foreach($j as $device_key => $device_val){
                if($device_key != 'contractor_id'){
                    if($device_key != 'primary_id'){
                        $model->$device_key = $device_val;
                    }
                }else{
                    $model->$device_key = $transfer_conid;
                }
            }
            $result = $model->save();
            $device_id = $model->device_id;
            $primary_id = $j['primary_id'];
            $id = $model->primary_id;
            $sql_1 = "select * from bac_device_info where primary_id = $primary_id ";
            $command_1 = Yii::app()->db->createCommand($sql_1);
            $r = $command_1->queryAll();
            if(count($r) > 0){
                foreach($r as $x => $y){
                    if($y['certificate_photo'] != ''){
                        $array = explode('/',$y['certificate_photo']);
                        if(stristr($y['certificate_photo'],'/opt/www-nginx/web') == false){
                            $array[4] = $transfer_conid;
                            $str = implode('/',$array);
                            $file_1 = '/opt/www-nginx/web'.$y['certificate_photo'];
                            $file_2 = '/opt/www-nginx/web'.$str;
                        }else{
                            $array[7] = $transfer_conid;
                            $str = implode('/',$array);
                            $file_1 = $y['certificate_photo'];
                            $file_2 = $str;
                        }
                        if (copy($file_1,$file_2)) {
                            $y['certificate_photo'] = $str;
                            $deviceinfo_model = new DeviceInfo('create');
                            $deviceinfo_model->device_id = $y['device_id'];
                            $deviceinfo_model->primary_id = $id;
                            $deviceinfo_model->certificate_photo = $y['certificate_photo'];
                            $deviceinfo_model->certificate_title = $y['certificate_title'];
                            $deviceinfo_model->certificate_content = $y['certificate_content'];
                            $deviceinfo_model->contractor_id = $transfer_conid;
                            $deviceinfo_model->permit_startdate = $y['permit_startdate'];
                            $deviceinfo_model->permit_enddate = $y['permit_enddate'];
                            $deviceinfo_model->certificate_type = $y['certificate_type'];
                            $deviceinfo_model->certificate_size = $y['certificate_size'];
                            $deviceinfo_model->notify_enddate = $y['notify_enddate'];
                            $deviceinfo_model->notify_cycle = $y['notify_cycle'];
                            $deviceinfo_model->type = $y['type'];
                            $result = $deviceinfo_model->save();
                        }
                    }
                }
            }
            end:
            echo 'Success';
        }
    }
}