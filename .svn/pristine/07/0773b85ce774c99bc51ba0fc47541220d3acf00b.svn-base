<?php

/**
 * 邮件发送
 * @author LiuMinchao
 */
class MailType extends CActiveRecord {

    const STATUS_EXPIRING = '1'; //即将过期
    const STATUS_EXPIRED = '2'; //已过期
    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_mail';
    }
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Meeting the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_EXPIRING => Yii::t('comp_aptitude', 'expiring'),
            self::STATUS_EXPIRED => Yii::t('comp_aptitude', 'expired'),
        );
        return $key === null ? $rs : $rs[$key];
    }
    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_EXPIRING => 'label-success', //已启用
            self::STATUS_EXPIRED => ' label-danger', //未启用
        );
        return $key === null ? $rs : $rs[$key];
    }
    //看当前用户所在项目的超期人员证书列表
    public static function queryAptitude($page, $pageSize, $args = array()){
        $args['date'] = date('Y-m-d', strtotime("+30 day"));
        $sql = "select
            a.user_id, d.user_name, e.certificate_name, e.certificate_name_en, a.permit_enddate,
            f.contractor_name, g.program_name
                from
             bac_aptitude a
        join bac_program_user b on a.user_id = b.user_id
        join bac_program_user c on b.root_proid = c.root_proid
        join bac_staff d on a.user_id = d.user_id
        join bac_certificate e on a.certificate_type = e.certificate_type
        join bac_contractor f on d.contractor_id = f.contractor_id
        join bac_program g on c.root_proid = g.program_id
        WHERE
              a.status = '0' and a.permit_enddate < '".$args['date']."'
              and b.check_status not in('12','21')
              and c.root_proid = '".$args['root_proid']."'
              and c.contractor_id = '".$args['contractor_id']."'
              and c.user_id = '".$args['user_id']."' and c.root_proid = c.program_id and c.check_status not in('12','21')
        order by
            g.program_id desc";
        $command = Yii::app()->db->createCommand($sql);
        $data = $command->queryAll();

        $start=$page*$pageSize; #计算每次分页的开始位置
        $count = count($data);
        $pagedata=array();
        $pagedata=array_slice($data,$start,$pageSize);
//        var_dump($pagedata);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $count;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $pagedata;
        $rs['data'] = $data;

        return $rs;
    }
}
