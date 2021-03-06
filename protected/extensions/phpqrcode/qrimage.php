<?php
/*
 * PHP QR Code encoder
 *
 * Image output of code using GD2
 *
 * PHP QR Code is distributed under LGPL 3
 * Copyright (C) 2010 Dominik Dzienia <deltalab at poczta dot fm>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */

    define('QR_IMAGE', true);

    class QRimage {

        //----------------------------------------------------------------------
        public static function png($frame, $filename = false, $pixelPerPoint = 4, $outerFrame = 4,$saveandprint=FALSE,$label,$top_label,$logo)
        {
            $image = self::image($frame, $pixelPerPoint, $outerFrame,$label,$top_label,$logo);

            if ($filename === false) {
                Header("Content-type: image/png");
                ImagePng($image);
            } else {
                if($saveandprint===TRUE){
                    ImagePng($image, $filename);
                    header("Content-type: image/png");
                    ImagePng($image);
                }else{
                    ImagePng($image, $filename);
                }
            }

            ImageDestroy($image);
        }

        //----------------------------------------------------------------------
        public static function jpg($frame, $filename = false, $pixelPerPoint = 8, $outerFrame = 4, $q = 85)
        {
            $image = self::image($frame, $pixelPerPoint, $outerFrame);

            if ($filename === false) {
                Header("Content-type: image/jpeg");
                ImageJpeg($image, null, $q);
            } else {
                ImageJpeg($image, $filename, $q);
            }

            ImageDestroy($image);
        }


        /**
         * @param resource $sourceImage
         * @param string   $logoPath
         * @param int      $logoWidth
         *
         * @return resource
         */
        public static  function addLogo($sourceImage, $logoPath, $logoWidth = null)
        {
            $logoImage = imagecreatefromstring(file_get_contents($logoPath));
            $logoSourceWidth = imagesx($logoImage);
            $logoSourceHeight = imagesy($logoImage);
            $logoTargetWidth = $logoWidth;

            if ($logoTargetWidth === null) {
                $logoTargetWidth = $logoSourceWidth;
                $logoTargetHeight = $logoSourceHeight;
            } else {
                $scale = $logoTargetWidth / $logoSourceWidth;
                $logoTargetHeight = intval($scale * imagesy($logoImage));
            }

            $logoX = imagesx($sourceImage) / 2 - $logoTargetWidth / 2;
            $logoY = imagesy($sourceImage) / 2 - $logoTargetHeight / 2;

            imagecopyresampled(
                $sourceImage,
                $logoImage,
                $logoX,
                $logoY,
                0,
                0,
                $logoTargetWidth,
                $logoTargetHeight,
                $logoSourceWidth,
                $logoSourceHeight
            );

            return $sourceImage;
        }

        //----------------------------------------------------------------------
        private static function image($frame, $pixelPerPoint = 4, $outerFrame = 4,$label,$top_label,$logo)
        {

            $h = count($frame);
            $w = strlen($frame[0]);

            $imgW = $w + 2*$outerFrame;
            $imgH = $h + 2*$outerFrame;

            $base_image =ImageCreate($imgW, $imgH);

            $col[0] = ImageColorAllocate($base_image,255,255,255);
            $col[1] = ImageColorAllocate($base_image,0,0,0);

            imagefill($base_image, 0, 0, $col[0]);

            for($y=0; $y<$h; $y++) {
                for($x=0; $x<$w; $x++) {
                    if ($frame[$y][$x] == '1') {
                        ImageSetPixel($base_image,$x+$outerFrame,$y+$outerFrame,$col[1]);
                    }
                }
            }

            $target_image =ImageCreate($imgW * $pixelPerPoint, $imgH * $pixelPerPoint);
            ImageCopyResized($target_image, $base_image, 0, 0, 0, 0, $imgW * $pixelPerPoint, $imgH * $pixelPerPoint, $imgW, $imgH);

            if($logo){
                $path = "https://dss0.bdstatic.com/-0U0bnSm1A5BphGlnYG/tam-ogel/9a315cb59cf499d994573994d773eb12_222_222.png";

                $path_width = 40;

                $target_image = QRimage::addLogo($target_image, $logo, $path_width);
            }

            $labelFontSize = '15';

            $labelX = $imgW/2;

            $top_labelY = 26;

            $labelFontPath =  __DIR__ . '/fonts/noto_sans.otf';

            $foregroundColor = $col[1];

            $top_content = "";
            if($top_label){
                for ($i = 0; $i < mb_strlen($top_label); $i++) {
                    $top_letter[] = mb_substr($top_label, $i, 1);
                }
                foreach ($top_letter as $l) {
                    $top_content .= $l;
                    if(strlen($top_content)%35==0){
                        $top_array[] = $top_content;
                        $top_content = "";
                    }
                }
                $top_array[] = $top_content;
                foreach($top_array as $i => $j){
                    imagettftext($target_image, $labelFontSize, 0, 50, $top_labelY, $foregroundColor, $labelFontPath,$j);
                    $top_labelY = $top_labelY+20;
                }
            }

            if($label){
                $SourceHeight = imagesy($target_image);

                $labelY = $SourceHeight-27;

                $content = "";

                for ($i = 0; $i < mb_strlen($label); $i++) {
                    $letter[] = mb_substr($label, $i, 1);
                }

                foreach ($letter as $l) {
                    $content .= $l;
                    if(strlen($content)%35==0){
                        $array[] = $content;
                        $content = "";
                    }
                }
                $array[] = $content;
                foreach($array as $x => $y){
                    imagettftext($target_image, $labelFontSize, 0, 50, $labelY, $foregroundColor, $labelFontPath,$y);
                    $labelY = $labelY+20;
                }
            }


            ImageDestroy($base_image);

            return $target_image;
        }
    }
