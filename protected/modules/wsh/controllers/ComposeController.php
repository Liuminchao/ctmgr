
<?php
//namespace setasign\Fpdi;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/24
 * Time: 15:06
 */

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;

class ComposeController extends AuthBaseController
{
    /**
     * 合并PDF
     */
    public function actionMerge() {
//        var_dump(333333333);
//        exit;
//        $fields = func_get_args();
//
//        $args = $_GET['q']; //查询条件
//
//        if(count($fields) == 4 && $fields[0] != null ) {
//            $args['program_id'] = $fields[0];
//            $args['user_id'] = $fields[1];
//            $args['deal_type'] = $fields[2];
//            $args['safety_level'] = $fields[3];
//        }
//        $args['contractor_id'] = Yii::app()->user->contractor_id;
//        $list = SafetyCheck::queryExcelList($args);
        $tag = $_REQUEST['tag'];
        $tag_arr = explode('|',$tag);
        $files = array();
//        var_dump($tag_arr);
//        exit;
        foreach($tag_arr as $k=>$check_id){

            $params['check_id'] = $check_id;
            $app_id = 'WSH';
            $check_list = SafetyCheck::detailList($check_id);//安全检查单
            $program_id = $check_list[0]['root_proid'];
            $pro_model = Program::model()->findByPk($program_id);
            $pro_params = $pro_model->params;//项目参数
            if($pro_params != '0') {
                $pro_params = json_decode($pro_params, true);
                //判断是否是迁移的
                if (array_key_exists('wsh_report', $pro_params)) {
                    $params['type'] = $pro_params['wsh_report'];
                } else {
                    $params['type'] = 'A';
                }
            }else{
                $params['type'] = 'A';
            }
            $params['month_tag'] = 0;
            $params['action'] = '1';
            $filepath = DownloadPdf::transferDownload($params,$app_id);
            if(file_exists($filepath)){
                $files[] = '/opt/www-nginx/web'.$filepath;
            }
        }
//        $fpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'fpdf'.DIRECTORY_SEPARATOR.'fpdf.php';
        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'fpdf'.DIRECTORY_SEPARATOR.'fpdf.php';
        require_once($tcpdfPath);
        $fpdiPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'FPDI'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'autoload.php';
//        require_once($fpdfPath);
        require_once($fpdiPath);
        // define some files to concatenate
//        $files = array(
//            '/opt/www-nginx/web/filebase/data/ra/146/qq.pdf',
//            '/opt/www-nginx/web/filebase/data/ra/146/RA Installation of Gantry.pdf'
//        );

// initiate FPDI
        $pdf = new Fpdi();

// iterate through the files
        foreach ($files AS $file) {
            // get the page count
            $pageCount = $pdf->setSourceFile($file);
            // iterate through all pages
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                // import a page
                $templateId = $pdf->importPage($pageNo);
                // get the size of the imported page
                $size = $pdf->getTemplateSize($templateId);
//          print_r($size);break;
                // create a page (landscape or portrait depending on the imported page size)
                if ($size['width'] > $size['height']) {
                    $pdf->AddPage('L', array($size['width'], $size['height']));
                } else {
                    $pdf->AddPage('P', array($size['width'], $size['height']));
                }

// use the imported page
                $pdf->useTemplate($templateId);

                $pdf->SetFont('Helvetica');
                $pdf->SetXY(5, 5);
//        $pdf->Write(8, 'A simple concatenation demo with FPDI');
            }
        }

// Output the new PDF
//$pdf->Output();     //直接输出
        $filepath = '/opt/www-nginx/web/filebase/tmp/Inspection Merge.pdf';
        $pdf->output($filepath, "F");   //下载到本地
//        $title = 'Inspection Merge.pdf';//标题
//        Utils::Download($filepath, $title, 'pdf');
//        echo $filepath;
        $r['filename'] = $filepath;
        echo json_encode($r);
    }

    public function actionDownload() {
        $filename = $_REQUEST['filename'];
//        var_dump($filename);
//        exit;
        if (file_exists($filename) == false) {
            header("Content-type:text/html;charset=utf-8");
            echo "<script>alert('".Yii::t('common','Document not found')."');</script>";
            return;
        }
        $file = fopen($filename, "r"); // 打开文件
        header('Content-Encoding: none');
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Accept-Length: " . filesize($filename));
        header('Content-Transfer-Encoding: binary');
        $name = 'InspectionMerge.pdf';
        header("Content-Disposition: attachment; filename=" . $name); //以真实文件名提供给浏览器下载
        header('Pragma: no-cache');
        header('Expires: 0');
        echo fread($file, filesize($filename));
        fclose($file);
        if (!unlink($filename))
        {
            echo ("Error deleting ");
        }
        else
        {
            echo ("Deleted successed");
        }
    }

}