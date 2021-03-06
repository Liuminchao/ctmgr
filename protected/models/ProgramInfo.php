<?php

class ProgramInfo extends CActiveRecord {

    const STATUS_NORMAL = 0; //正常
    const STATUS_DISABLE = 1; //注销
    const CONTRACTOR_TYPE_MC = 'MC'; //总包
    const CONTRACTOR_TYPE_SC = 'SC'; //分包

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_program_info';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('proj_project', 'id'),
            'program_id' => Yii::t('proj_project', 'program_id'),
            'program_name' => Yii::t('proj_project', 'program_name'),
            'program_content' => Yii::t('proj_project', 'program_content'),
            'program_address' => Yii::t('proj_project', 'program_address'),
            'program_amount' => Yii::t('proj_project', 'program_amount'),
            'program_bp_no' => Yii::t('proj_project', 'program_bp_no'),
            'program_construction_no' => Yii::t('proj_project', 'program_construction_no'),
            'construction_start' => Yii::t('proj_project', 'construction_start'),
            'construction_end' => Yii::t('proj_project', 'construction_end'),
            'contractor_id' => Yii::t('proj_project', 'contractor_id'),
            'add_operator' => Yii::t('proj_project', 'add_operator'),
            'status' => Yii::t('proj_project', 'status'),
            'record_time' => Yii::t('proj_project', 'record_time'),
            'subcomp_name' => Yii::t('proj_project', 'sub_contractor_name'),
            'subcon_type' => Yii::t('proj_project', 'subcon_type'),
            'subcomp_sn'  => Yii::t('comp_contractor', 'company_sn'),
            'way_attendance'  => Yii::t('proj_project','way_attendance'),
            'start_sign'  => Yii::t('proj_project','start_sign'),
            'start_face'  => Yii::t('proj_project','start_face'),
            'close_face'  => Yii::t('proj_project','close_face'),
            'start_app'  => Yii::t('proj_project','start_app'),
            'start_attendance'  => Yii::t('proj_project','start_attendance'),
            'independent'  => Yii::t('proj_project','independent'),
            'independent_no'  => Yii::t('proj_project','independent_no'),
            'independent_yes'  => Yii::t('proj_project','independent_yes'),
            'ptw_mode'  => Yii::t('proj_project','ptw_mode'),
            'tbm_mode'  => Yii::t('proj_project','tbm_mode'),
            'acci_mode'  => Yii::t('proj_project','acci_mode'),
            'wsh_mode'  => Yii::t('proj_project','wsh_mode'),
            'train_mode'  => Yii::t('proj_project','train_mode'),
            'location_require' => Yii::t('proj_project','location_requires'),
            'location_require_no'  => Yii::t('proj_project','location_require_no'),
            'location_require_yes'  => Yii::t('proj_project','location_require_yes'),
            'struct_progress'  => Yii::t('proj_project','struct_progress'),
            'arch_progress'  => Yii::t('proj_project','arch_progress'),
            'me_progress' => Yii::t('proj_project','me_progress'),
            'program_gfa'  => Yii::t('proj_project','program_gfa'),
            'developer_client'  => Yii::t('proj_project','developer_client'),
            'consultant'  => Yii::t('proj_project','consultant'),
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Operator the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    //插入数据
    public static function insertProgramInfo($id,$infoargs) {
        if(empty($infoargs)){
            return;
        }

        $infomodel = new StaffInfo('create');
        $transaction=$infomodel->dbConnection->beginTransaction();
        //图片转二进制
        /*if ($infoargs['face_img'] <> '') {
            $attach1 = $infoargs['face_img'];
            $fsize = filesize($attach1);
            $handle = fopen($attach1, "r");
            $infomodel->face_img = fread($handle, $fsize);
            //var_dump($infomodel->face_img);
            fclose($handle);
        }*/

        try{
            $infomodel->project_id = $id;
            foreach ($infoargs as $key => $v) {
                if($v != ''&& $key != 'tag'){
                    $infomodel->$key = $v;
                }
            }
            $res = $infomodel->save();

            if(!$res){
                $r['msg'] = Yii::t('common', 'error_insert');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        }
        catch(Exception $e){
            $transaction->rollBack();
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }
    //修改数据
    public static function updateProjectInfo($args,$infoargs) {
        //判断是否有此user_id的一条记录
        $connection = Yii::app()->db;
        $sql = "select count(*) from bac_program_info where program_id = :program_id";
        $command = $connection->createCommand($sql);
        $command->bindParam(":program_id", $args['program_id'], PDO::PARAM_STR);
        $exist_data = $command->queryAll();
        $data = $exist_data[0]["count(*)"];
//        var_dump($data);
//        exit;
        if ($data ==0) {
            //插入一条此user_id的记录
            $promodel = new ProgramInfo('create');
            $promodel->program_id = $args['program_id'];
            $res = $promodel->save();
        }

        $infomodel = ProgramInfo::model()->findByPk($args['program_id']);
        $trans = Yii::app()->db->beginTransaction();
        try{
            foreach ($infoargs as $key => $v) {
//                if($v != ''){
                //$infomodel->$key = Utils::DateToCn($v);
                $infomodel->$key = $v;
//                }
            }
            $res = $infomodel->save();
            $trans->commit();
            if(!$res){
                $r['msg'] = Yii::t('common', 'error_update');
                $r['status'] = -1;
                $r['refresh'] = false;
            }else{
                $r['msg'] = Yii::t('common', 'success_update');
                $r['status'] = 1;
                $r['refresh'] = true;
                $r['user_id'] = $args['user_id'];
            }
        }
        catch(Exception $e){
            $trans->rollBack();
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }

}