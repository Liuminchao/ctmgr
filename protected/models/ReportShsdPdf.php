<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/7
 * Time: 9:20
 */
$tcpdfPath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . 'tcpdf' . DIRECTORY_SEPARATOR . 'tcpdf.php';
require_once($tcpdfPath);

// Extend the TCPDF class to create custom Header and Footer
class ReportShsdPdf extends TCPDF {
    const RESULT_YES = 0;
    const RESULT_NO = 1;
    const RESULT_NA = 2;

    //检查条件
    public static function resultText($key = null) {
        $rs = array(
            self::RESULT_YES => '√',
            self::RESULT_NO => '×',
            self::RESULT_NA => 'N/A',
        );
        return $key == null ? $rs : $rs[$key];
    }

    public function  FooterList(){
        $list = array(
            'SHSD001' => 'STEC- OSHE - 001',
            'SHSD002' => 'STEC- OSHE - 002',
            'SHSD003' => 'STEC- OSHE - 003',
            'SHSD004' => 'STEC- OSHE - 004',
            'SHSD005' => 'STEC- OSHE - 005',
            'SHSD006' => 'STEC- OSHE - 006',
            'SHSD007' => 'STEC- OSHE - 007',
            'SHSD008' => 'STEC- OSHE - 008',
            'SHSD009' => 'STEC- OSHE - 009',
            'SHSD010' => 'STEC- OSHE - 010',
            'SHSD011' => 'STEC- OSHE - 011',
            'SHSD012' => 'STEC- OSHE - 012',
        );
        return $list;
    }
    // //Page header
    public function Header($logo_pic= null) {
        $contractor_id = $_SESSION['contractor_id'];
        if($contractor_id == '595'){
            self::Header1($logo_pic);
        }else if($contractor_id == '799'){
            self::Header2($logo_pic);
        }else{
            self::Header3($logo_pic);
        }
    }
    
    //Page header
    public function Header1($logo_pic = null) {
        // Logo
        // $image_file = '/opt/www-nginx/web/filebase/record/2018/04/sign/pic/sign1523249465052_1.jpg';
        // $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        //设置字体
        $this->SetHeaderMargin(35);
        $logo_pic = '/opt/www-nginx/web/filebase/company/146/shsd.png';
        //$logo_pic = 'http://shell.cmstech.sg/opt/www-nginx/web/filebase/company/146/shsd.png';
        //$this->setHeaderFont(Array('droidsansfallback', '', '15')); //英文OR中文
        $this->SetFont('droidsansfallback', '', 25, '', true); //英文OR中文
        // if($logo_pic){
            $contractor_id = $_SESSION['contractor_id'];
            $contractor_name = $_SESSION['contractor_name'];
            // if($contractor_id == '595'){
                // $this->Image($logo_pic, 55, 5, 90, 16, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
                // $this->Image($logo_pic, 15, 5, 45, 13, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
                if(array_key_exists('logo', $_SESSION) && $_SESSION['logo']!=''){
                    var_dump($_SESSION['logo']);
                    $img_info = getimagesize('https://shell.cmstech.sg'.$_SESSION['logo']);
                    $img_x = $img_info[0];
                    $img_y = $img_info[1];
                    $scale = $img_x/$img_y;
                    $this->Image('https://shell.cmstech.sg'.$_SESSION['logo'], 0, 0, 0, 17, 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
                }
            // }

        // }
        $this->setPrintHeader(true);
    }

    //Page header
    public function Header2($logo_pic) {
        // Logo
        // $image_file = '/opt/www-nginx/web/filebase/record/2018/04/sign/pic/sign1523249465052_1.jpg';
        // $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        //设置字体
        $this->SetHeaderMargin(35);
        $logo_pic = 'https://shell.cmstech.sg/test/ctmgr/img/logo.png';
        // $logo_pic = 'http://shell.cmstech.sg/opt/www-nginx/web/filebase/company/146/shsd.png';
        // $this->setHeaderFont(Array('droidsansfallback', '', '15')); //英文OR中文
        $this->SetFont('droidsansfallback', '', 25, '', true); //英文OR中文
        if($logo_pic){
            $contractor_id = $_SESSION['contractor_id'];
            $contractor_name = $_SESSION['contractor_name'];
            $this->Image($logo_pic, 15, 5, 45, 13, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            $this->Ln(10);
            $this->Cell(0, 15, 'PERMIT TO WORK', 0, 1, 'C', 0, '', 0, false, 'M', 'M');
            $program_name = $_SESSION['program_name'];
            $id = $_SESSION['id'];
            $html_left1 = "Project: {$program_name}";
            $html_left2 = "Permit No.: {$id}";
            $html_right = "Form: HDKH/SSPO/HSE/11/PTW/R0 ";

            // Print text using writeHTMLCell()
            $this->SetFont('droidsansfallback', '', 12, '', true); //英文OR中文
            $this->writeHTMLCell(0, 0, '15', '', $html_left1, 0, 0, 0, true, 'L', true);
            $this->writeHTMLCell(0, 0, '95', '', $html_right, 0, 1, 0, true, 'R', true);
            $this->writeHTMLCell(0, 0, '15', '', $html_left2, 0, 0, 0, true, 'L', true);
        }
        $this->setPrintHeader(true);
    }

    //Page header
    public function Header3($logo_pic= null) {
        // Logo
        //设置字体
        $this->SetHeaderMargin(15);
        // $logo_pic = '/opt/www-nginx/web/filebase/company/146/图片1.png';
        $this->setHeaderFont(Array('droidsansfallback', '', '15')); //英文OR中文
        $this->SetFont('droidsansfallback', '', 15, '', true); //英文OR中文
        // $this->Cell(0, 15, $title_html, 0, 1, 'C', 0, '', 0, false, 'M', 'M');
        // $this->Cell(0, 25, $_SESSION['title'], 0, false, 'C', 0, '', 0, false, 'M', 'M');
        if(array_key_exists('logo', $_SESSION) && $_SESSION['logo']!=''){
            $img_info = getimagesize('https://shell.cmstech.sg'.$_SESSION['logo']);
            $img_x = $img_info[0];
            $img_y = $img_info[1];
            $scale = $img_x/$img_y;
            $this->Image('https://shell.cmstech.sg'.$_SESSION['logo'], 0, 0, 0, 17, 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        }
        $this->setPrintHeader(true);
    }

    //Page header
    public function Footer() {
        $contractor_id = $_SESSION['contractor_id'];
        if($contractor_id == '595'){
            self::Footer1();
        }
        if($contractor_id == '799'){
            self::Footer2();
        }
    }

    // Page footer
    public function Footer1() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('times', '', 8);
//        $footer_pic = '/opt/www-nginx/web/filebase/company/146/cms_footer.png';
        $this->Cell(0, 10, 'Copyright ©2019', 0, false, 'L', 0, '', 0, false, 'T', 'M');
//        $this->Cell(0, 10, 'Copyright ©2019', 0, false, 'L', 0, '', 0, false, 'T', 'M');
        // Page number
        //STEC-OSHE-003
        $footer_list = self::FooterList();
        $this->Cell(0, 10, $footer_list[$_SESSION['type_id']], 0, false, 'R', 0, '', 0, false, 'T', 'C');

//        $this->writeHTMLCell(0, 0, '170', '', $footer_list[$_SESSION['type_id']], 0, 0, 0, true, 'R', true);
//                $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'C');
//        $this->Image($footer_pic, '115', '', 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    }

    // Page footer
    public function Footer2() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('droidsansfallback', 'I', 8);
        $footer_pic = '/opt/www-nginx/web/filebase/company/146/cms_footer.png';
        $this->Cell(0, 10, 'Powered by', 0, false, 'C', 0, '', 0, false, 'T', 'M');
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'C');
        $this->Image($footer_pic, '115', '', 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    }

    // Load table data from file
    public function LoadData($file) {
//        $data = array();
//        foreach($file as $line) {
        $data = explode(',', $file);
//        }
        return $data;
    }

    // Colored table
    public function ColoredTable($header,$data) {
        // Colors, line width and bold font
        $this->SetFillColor(229, 229, 229);
        $this->SetTextColor(255);
        $this->SetDrawColor(170, 170, 170);
        $this->SetLineWidth(0.1);
        // Header
        $w = array(40, 35, 40, 45);
        $num_headers = count($header);
        for($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        }
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Data
        $fill = 0;
        foreach($data as $row) {
            $this->Cell($w[0], 6, $row[0], 'LR', 0, 'C', $fill);
            $this->Cell($w[1], 6, $row[1], 'LR', 0, 'C', $fill);
            $this->Cell($w[2], 6, $row[2], 'LR', 0, 'C', $fill);
            $this->Cell($w[3], 6, $row[3], 'LR', 0, 'C', $fill);
            $this->Ln();
            $fill=!$fill;
        }
        $this->Cell(array_sum($w), 0, '', 'T');
    }
}