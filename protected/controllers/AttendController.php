<?php
/*
 * ATTEND: 
 */
class AttendController extends BaseController
{
    public $bigMenu = '';

    public $attend_base_url='';

    public $layout = '//layouts/main_1';
    
    public function init()
    {
        parent::init();
        $this->bigMenu = Yii::t('dboard', 'Menu Attend');
        $this->contentHeader = Yii::t('dboard', 'Menu Attend2');
        
        $base_url = Yii::app()->params['attend_params']['base_url'];
        $base_url .= '/index.php'.'?c=main&a=ssoLogin';
        $base_url .= '&system='.Yii::app()->params['attend_params']['from'];
        $base_url .= '&key='.Yii::app()->params['attend_params']['key'];
		$base_url .= '&lang='.Yii::app()->language;
        $base_url .= '&orgid='.Yii::app()->user->getState('contractor_id');
        $base_url .= '&userid='.Yii::app()->user->id;
        $this->attend_base_url = $base_url;
        
    }
    /*public function accessRules()
    {
        return array(
            array('allow',
                'users' => array('@'),
            ),
            array('deny',
                'actions' => array(),
            ),
        );
    }*/
    
    //设备管理
    public function actionDevice()
	{		
        $this -> smallHeader = Yii::t('dboard', 'Menu Attend device');
            
		$url = $this->attend_base_url.'&ca=attendance_index';//var_dump($url);
        $this->render('index',array('goUrl'=>$url));
	}
	
    //班次时段管理
	public function actionDayManage()
	{		
        $this -> smallHeader = Yii::t('dboard', 'Menu Attend dayManage');
            
		$url = $this->attend_base_url.'&ca=dayManage_index';
        $this->render('index',array('goUrl'=>$url));
	}
	
    //排班管理
	public function actionPolicyManage()
	{		
        $this -> smallHeader = Yii::t('dboard', 'Menu Attend policyManage');
            
		$url = $this->attend_base_url.'&ca=policyManage_index';
        $this->render('index',array('goUrl'=>$url));
	}
	
    //考勤规则
	public function actionSchedule()
	{		
        $this -> smallHeader = Yii::t('dboard', 'Menu Attend schedule');
            
		$url = $this->attend_base_url.'&ca=schedule_index';
        $this->render('index',array('goUrl'=>$url));
    }
    	
    //手动考勤
	public function actionManual()
	{		
        $this -> smallHeader = Yii::t('dboard', 'Menu Attend manual');
            
		$url = $this->attend_base_url.'&ca=manual_index';
        $this->render('index',array('goUrl'=>$url));
    }
        	
    //刷卡记录
	public function actionRecord()
	{		
        $this -> smallHeader = Yii::t('dboard', 'Menu Attend record');
        $this->bigMenu = Yii::t('dboard', 'Menu Attend');

		$url = $this->attend_base_url.'&ca=record_index';
        $this->render('index',array('goUrl'=>$url));
    }
    
    //考勤报表
	public function actionReport()
	{		
        $this -> smallHeader = Yii::t('dboard', 'Menu Attend report');
        $this->bigMenu = Yii::t('dboard', 'Menu Attend');
        //$this->contentHeader = Yii::t('dboard', 'Menu Attend');
            
		$url = $this->attend_base_url.'&ca=report_index';
        $this->render('index',array('goUrl'=>$url));
    }

    public function actiontxtDemo(){
        $filename='Cms Sample timeing data for text format.txt';//要导出的文件的文件名需要加上文件后缀

        $chars = "04/02/2020!0027!LIU XIJIN!MCS!FORMWORK SUPERVISOR!0658!\r\n"; //需要导出的文件的内容
        $chars.= "04/02/2020!0027!LIU XIJIN!MCS!FORMWORK SUPERVISOR!2035!\r\n";
        $chars.= "\r\n";
        $chars.= "Date!Emp Code!Name!Dept!Designation!Time In!\r\n"; //需要导出的文件的内容
        $chars.= "Date!Emp Code!Name!Dept!Designation!Time Out!\r\n";
        $chars.= "\r\n";
        $chars.= "Legend\r\n";
        $chars.= "Emp Cod      => Employee code\r\n";
        $chars.= "Dept         => Department\r\n";
        $chars.= "\r\n";
        $chars.= "\r\n";
        $chars.= "Notes: Actual data must without the explanation.\r\n";

        header('Content-Type: text/x-sql');

        header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');

        header('Content-Disposition: attachment; filename="' .$filename. '"');

        $is_ie = 'IE';

        if ($is_ie == 'IE') {

            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

            header('Pragma: public');

        } else {

            header('Pragma: no-cache');

            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');

        }

        echo $chars;

        exit();

    }

}