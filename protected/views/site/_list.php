<style type="text/css">
    .omit{
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }
    .progress_bar .pro-bar {
        background: hsl(0, 0%, 97%);
        box-shadow: 0 1px 2px hsla(0, 0%, 0%, 0.1) inset;
        height:4px;
        margin-bottom: 12px;
        margin-top: 50px;
        position: relative;
    }
    .progress_bar .progress_bar_title{
        /*color: hsl(218, 4%, 50%);*/
        color: #8B8378;
        font-size: 15px;
        font-weight: 300;
        position: relative;
        top: -28px;
        z-index: 1;
    }
    .progress_bar .progress_number{
        float: right;
        margin-top: -24px;
    }
    .progress_bar .progress-bar-inner {
        background-color: hsl(0, 0%, 88%);
        display: block;
        width: 0;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        transition: width 1s linear 0s;
        animation: animate-positive 2s;
    }
    .progress_bar .progress-bar-inner:before {
        content: "";
        background-color: hsl(0, 0%, 100%);
        border-radius: 50%;
        width: 4px;
        height: 4px;
        position: absolute;
        right: 1px;
        top: 0;
        z-index: 1;
    }
    .progress_bar .progress-bar-inner:after {
        content: "";
        width: 14px;
        height: 14px;
        background-color: inherit;
        border-radius: 50%;
        position: absolute;
        right: -4px;
        top: -5px;
    }
    @-webkit-keyframes animate-positive{
        0% { width: 0%; }
    }
    @keyframes animate-positive{
        0% { width: 0%; }
    }
    span.counter { display: block; margin: 15px auto; font-size: 48px; font-family: Arial; font-weight: bold; }
</style>
<?php
$t->echo_grid_header();
if (is_array($rows)) {
    $j = 1;

    $tool = true;
    //$tool = false;验证权限
    if (Yii::app()->user->checkAccess('mchtm')) {
        $tool = true;
    }
    $status_list = Program::statusText(); //状态text
    $status_css = Program::statusCss(); //状态css
    $compList = Contractor::compAllList(); //所有发展商
    $cnt_list = ProgramUser::AllCntList();//公司下各项目入场人数
    $background_list = Utils::backgroundList();

//        for($i=1;$i<=9;$i++) {
    foreach ($rows as $i => $row) {
            if ($i <= 3) {
                ?>

                <div class="column">

                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box <?php echo $background_list[$i]; ?>">
                            <div class="inner">
                                <span class="counter"><h3> <?php
                                        if($cnt_list[$row['root_proid']]['cnt']){
                                            echo $cnt_list[$row['root_proid']]['cnt'];
                                        }else{
                                            echo '0';
                                        }
                                        ?>  </h3></span>
                                <p><?php echo Yii::t('proj_project','in_staffs') ?></p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-home"></i>
                            </div>
                            <?php if($row['father_proid']<>0){ ?>
                                <a class="small-box-footer" href="#" onclick="itemMenu('<?php echo $row['program_id'] ?>','SC')">
                            <?php }else{ ?>
                                <a class="small-box-footer" href="#" onclick="itemMenu('<?php echo $row['program_id'] ?>','MC')">
                            <?php } ?>
                                    <?php
                                        if(strlen($row['program_name']) > 30){
                                            echo substr($row['program_name'],0,30).'...';
                                        }else{
                                            echo $row['program_name'];
                                        }
                                    ?>
                                <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php
                    } else {
                ?>
                <div class="column display_none" style="display:none ">

                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box <?php echo $background_list[$i]; ?>">
                            <div class="inner">
                                <span class="counter"><h3> <?php
                                        if($cnt_list[$row['root_proid']]['cnt']){
                                            echo $cnt_list[$row['root_proid']]['cnt'];
                                        }else{
                                            echo '0';
                                        }
                                        ?> </h3></span>
                                <p><?php echo Yii::t('proj_project','in_staffs') ?></p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-home"></i>
                            </div>
                            <?php if($row['father_proid']<>0){ ?>
                                <a class="small-box-footer"  style="text-overflow:ellipsis;white-space:nowrap" href="#" onclick="itemMenu('<?php echo $row['program_id'] ?>','SC')">
                            <?php }else{ ?>
                                <a class="small-box-footer"  style="text-overflow:ellipsis;white-space:nowrap" href="#" onclick="itemMenu('<?php echo $row['program_id'] ?>','MC')">
                            <?php } ?>
                                    <?php echo $row['program_name'] ?>
                                <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
$t->end_row();
}
?>
<?php if($i > 3){ ?>
    <div class="row">
        <div class="col-xs-12" style="margin-right: 0px;padding-right: 0px">
            <a class="display_block" id="btn" onclick="display()"><?php echo Yii::t('common','pack_up') ?></a>
        </div>
    </div>
<?php } ?>
<?php
$t->echo_grid_floor();
$pager = new CPagination($new_cnt);
$pager->pageSize = 50;
//var_dump($this->pageSize);
$pager->itemCount = $new_cnt;
?>

<!--<div class="row">-->
<!--    <div class="col-xs-3">-->
<!--        <div class="dataTables_info" id="example2_info">-->
<!--            --><?php //echo Yii::t('common', 'page_total'); ?><!-- --><?php //echo $new_cnt; ?><!-- --><?php //echo Yii::t('common', 'page_cnt'); ?>
<!--        </div>-->
<!--    </div>-->
<!--    <div class="col-xs-9">-->
<!--        <div class="dataTables_paginate paging_bootstrap">-->
<!--            --><?php //$this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

<section class="content">

    <div class="row">
        <div class="col-md-6">
            <!-- 地图 -->
            <div class="box box-info">
                <div class="box-header">
                    <!-- tools box -->
                    <!--                    <div class="pull-right box-tools">-->
                    <!--                        <button class="btn btn-primary btn-sm pull-right" data-widget='collapse' data-toggle="tooltip" title="Collapse" style="margin-right: 5px;"><i class="fa fa-minus"></i></button>-->
                    <!--                    </div><!-- /. tools -->
                    <!---->
                    <!--                    <i class="fa fa-map-marker"></i>-->
                    <!--                    <h3 class="box-title">-->
                    <!--                        --><?php //echo Yii::t('common','file_space'); ?>
                    <!--                    </h3>-->
                    <h6 class="box-title">
                        <?php $total_size=$main_proj_cnt*2+2; echo Yii::t('common','storage_space').': '.$total_size; ?>GB
                    </h6>
                </div>
                <div class="box-body chart-responsive">
                    <div id="file-space" style="height: 300px;"></div>
                </div>
            </div>
            <!-- /.box -->

            <!-- 折线图 -->
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title"><?php echo Yii::t('common','Project_violation'); ?></h3>
                </div>
                <div class="box-body chart-responsive">
                    <div class="chart" id="revenue-chart" style="height: 300px;"></div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->



        </div><!-- /.col (LEFT) -->
        <div class="col-md-6">
            <!-- 饼状图 -->
            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title"><?php echo Yii::t('common','Project_user'); ?></h3>
                </div>
                <div class="box-body chart-responsive">
                    <div class="chart" id="pie-chart" style="height: 300px; position: relative;"></div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->

            <!-- BAR CHART -->
            <div class="box box-success">
                <div class="box-header">
                    <h3 class="box-title"><?php echo Yii::t('common','Inductrial_situation'); ?></h3>
                </div>
                <div class="box-body chart-responsive">
                    <div class="chart" id="bar-chart" style="height: 300px;"></div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->

        </div><!-- /.col (RIGHT) -->
    </div><!-- /.row -->

</section>
<div class="row">
    <div class="col-md-12">
        <div class="box box-danger">
            <div class="box-header">
                <i class="fa fa-map-marker"></i>
                <h3 class="box-title">
                    <?php echo Yii::t('common','Project_location'); ?>
                </h3>
            </div>
            <div class="box-body chart-responsive">
                <div id="big-map" class="box-success" style="height: 400px;">

                </div><!-- /.box -->
            </div>
        </div>
    </div>
</div>
<!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAhcLgIHv_6VXLS9Kyt4GTTPlsZF_srA4o&libraries=places&callback=initMap" async defer></script>-->

<script src="js/jquery.waypoints.min.js"></script>
<script type="text/javascript" src="js/jquery.countup.min.js"></script>
<script src="js/morris.min.js" type="text/javascript"></script>
<script src="js/raphael.js" type="text/javascript"></script>
<script src="js/jquery.flot.js" type="text/javascript"></script>
<script src="js/jquery.flot.time.js" type="text/javascript"></script>
<script src="js/jquery.flot.pie.js" type="text/javascript"></script>
<script src="js/jquery.flot.pie.min.js" type="text/javascript"></script>
<script src="js/morris.js" type="text/javascript"></script>
<!--<script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=lS14hFRnEXU07GKiVDqNL4is&s=1"></script>-->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAhcLgIHv_6VXLS9Kyt4GTTPlsZF_srA4o&libraries=places&callback=initMap" async defer></script>
<script type="text/javascript">
     window.HOST_TYPE='2';
     var map;
     var infowindow;
     window.onload=function () {//加载完毕
         $('.counter').countUp(
             {
                 delay: 30,
                 time: 500
             }
         );//数字动画效果
         btn=document.getElementById('btn'),
         hflag=1;//标记是否隐藏
         btn.onclick=function () {
             if (hflag) {//当前为收起状态，展开函数
                 $(".display_none").attr("style", "display:block;");
                 btn.innerHTML="<?php echo Yii::t('common','spread') ?>";
                 hflag=0;
             }else {//当前为展开状态，收起函数
                 $(".display_none").attr("style", "display:none;");
                 btn.innerHTML="<?php echo Yii::t('common','pack_up') ?>";
                 hflag=1;
             }
         }
     }

     function display() {
         $(".display_none").attr("style", "display:block;");
     };

     // 百度地图API功能
//     var map = new BMap.Map("big-map");
//     var point = new BMap.Point(103.865936,1.358053);
//     map.centerAndZoom(point, 15);
//     callback();
//     function callback() {
//         $.ajax({
//             url: "index.php?r=dboard/programaddress",
//             type: "GET",
//             dataType: "json",
//             success: function(data) {
//                 $.each(data, function(i, field){
//                     lat = field.lat;
//                     lng = field.lng;
//                     var point = new BMap.Point(lng,lat);
//                     var marker = new BMap.Marker(point);  // 创建标注
//                     map.addOverlay(marker);              // 将标注添加到地图中
//                     var opts = {
//                         width : 200,     // 信息窗口宽度
//                         height: 100,     // 信息窗口高度
//                         title : "Project Info" , // 信息窗口标题
//                         enableMessage:true,//设置允许信息窗发送短息
//                         message:""
//                     }
//                     var infoWindow = new BMap.InfoWindow('<?php //echo Yii::t('proj_project_user','program_name') ?>//'+':'+field.pron_ame+','+'<?php //echo Yii::t('proj_project_user','entry_number') ?>//'+':'+field.count, opts);  // 创建信息窗口对象
//                     marker.addEventListener("click", function(){
//                         map.openInfoWindow(infoWindow,point); //开启信息窗口
//                     });
//                 });
//             }
//         });
//     }


     function initMap() {
         var Singapore = {lat: 1.3056160000, lng: 103.8234040000};

         map = new google.maps.Map(document.getElementById('big-map'), {
             center: Singapore,
             zoom: 12
         });

         infowindow = new google.maps.InfoWindow();

         var service = new google.maps.places.PlacesService(map);
         service.nearbySearch({
             location: Singapore,
             radius: 500,
             types: ['store']
         }, callback);
     }

     function callback(results, status) {
         $.ajax({
             url: "index.php?r=dboard/programaddress",
             type: "GET",
             dataType: "json",
             success: function(data) {
                 $.each(data, function(i, field){
                     createMarker(field);
                 });
             }
         });

            if (status === google.maps.places.PlacesServiceStatus.OK) {
                for (var i = 0; i < results.length; i++) {
                    createMarker(results[i]);
                }
            }
         }

    function createMarker(place) {
//        var placeLoc = place.geometry.location;
//        alert(placeLoc);
        lat = place.lat;
        lng = place.lng;
        var myLatLng = new google.maps.LatLng(lat, lng);
        var marker = new google.maps.Marker({
            map: map,
            position: myLatLng
        });

        google.maps.event.addListener(marker, 'click', function() {
            var contentString = '<div style="color:black;text-align:center;">' +'<?php echo Yii::t('proj_project_user','program_name') ?>'+':'+place.pron_ame+','+'<?php echo Yii::t('proj_project_user','entry_number') ?>'+':'+place.count+'</div>';
            infowindow.setContent(contentString);
            infowindow.open(map, this);
        });
    }
     $(function() {
         "use strict";
//        var  jsonData = '{"addr":{"city":"guangzhou","province":"guangdong"}}';
//        var json = JSON.parse(jsonData);
//        alert(json.addr.city);

         //$('#world-map').vectorMap();
         $.ajax({
             url: "index.php?r=dboard/violationscnt",
             type: "GET",
             dataType: "json",
             success: function(data) {
                 var j = 0;
                 var datasets = [];
                 $.each(data, function(i, field){
                     datasets[j] = {
                         label: field.label,
                         data: field.data,
                     }
                     j++;
                 });
//                var datasets = [];
//                datasets[data];
                 var i = 0;
                 $.each(datasets, function(key, val) {
                     val.color = i;
                     ++i;
                 });

                 $.plot("#revenue-chart", datasets, {
                     yaxis: {
                         min: 0
                     },
                     xaxis: {
                         tickDecimals: 0
                     }
                 });
             }
         });

         function onDataReceived(series) {

             // Extract the first coordinate pair; jQuery has parsed it, so
             // the data is now just an ordinary JavaScript object

             var firstcoordinate = "(" + series.data[0][0] + ", " + series.data[0][1] + ")";
//            button.siblings("span").text("Fetched " + series.label + ", first point: " + firstcoordinate);

             // Push the new data onto our existing data array

             if (!alreadyFetched[series.label]) {
                 alreadyFetched[series.label] = true;

             }
         }


//        var datasets = [
//            {
//                label: "USA",
//                data: [[1988, 483994], [1989, 479060], [1990, 457648], [1991, 401949], [1992, 424705], [1993, 402375], [1994, 377867], [1995, 357382], [1996, 337946], [1997, 336185], [1998, 328611], [1999, 329421], [2000, 342172], [2001, 344932], [2002, 387303], [2003, 440813], [2004, 480451], [2005, 504638], [2006, 528692]]
//            },
//            {
//                label: "Russia",
//                data: [[1988, 218000], [1989, 203000], [1990, 171000], [1992, 42500], [1993, 37600], [1994, 36600], [1995, 21700], [1996, 19200], [1997, 21300], [1998, 13600], [1999, 14000], [2000, 19100], [2001, 21300], [2002, 23600], [2003, 25100], [2004, 26100], [2005, 31100], [2006, 34700]]
//            },
//            {
//                label: "UK",
//                data: [[1988, 62982], [1989, 62027], [1990, 60696], [1991, 62348], [1992, 58560], [1993, 56393], [1994, 54579], [1995, 50818], [1996, 50554], [1997, 48276], [1998, 47691], [1999, 47529], [2000, 47778], [2001, 48760], [2002, 50949], [2003, 57452], [2004, 60234], [2005, 60076], [2006, 59213]]
//            },
//            {
//                label: "Germany",
//                data: [[1988, 55627], [1989, 55475], [1990, 58464], [1991, 55134], [1992, 52436], [1993, 47139], [1994, 43962], [1995, 43238], [1996, 42395], [1997, 40854], [1998, 40993], [1999, 41822], [2000, 41147], [2001, 40474], [2002, 40604], [2003, 40044], [2004, 38816], [2005, 38060], [2006, 36984]]
//            },
//            {
//                label: "Denmark",
//                data: [[1988, 3813], [1989, 3719], [1990, 3722], [1991, 3789], [1992, 3720], [1993, 3730], [1994, 3636], [1995, 3598], [1996, 3610], [1997, 3655], [1998, 3695], [1999, 3673], [2000, 3553], [2001, 3774], [2002, 3728], [2003, 3618], [2004, 3638], [2005, 3467], [2006, 3770]]
//            },
//            {
//                label: "Sweden",
//                data: [[1988, 6402], [1989, 6474], [1990, 6605], [1991, 6209], [1992, 6035], [1993, 6020], [1994, 6000], [1995, 6018], [1996, 3958], [1997, 5780], [1998, 5954], [1999, 6178], [2000, 6411], [2001, 5993], [2002, 5833], [2003, 5791], [2004, 5450], [2005, 5521], [2006, 5271]]
//            },
//            {
//                label: "Norway",
//                data: [[1988, 4382], [1989, 4498], [1990, 4535], [1991, 4398], [1992, 4766], [1993, 4441], [1994, 4670], [1995, 4217], [1996, 4275], [1997, 4203], [1998, 4482], [1999, 4506], [2000, 4358], [2001, 4385], [2002, 5269], [2003, 5066], [2004, 5194], [2005, 4887], [2006, 4891]]
//            }
//        ];

         // hard-code color indices to prevent them from shifting as
         // countries are turned on/off


         // LINE CHART
//        var line = new Morris.Line({
//            element: 'line-chart',
//            resize: true,
//            data: [
//                {y: '2011 Q1', item1: 2666},
//                {y: '2011 Q2', item1: 2778},
//                {y: '2011 Q3', item1: 4912},
//                {y: '2011 Q4', item1: 3767},
//                {y: '2012 Q1', item1: 6810},
//                {y: '2012 Q2', item1: 5670},
//                {y: '2012 Q3', item1: 4820},
//                {y: '2012 Q4', item1: 15073},
//                {y: '2013 Q1', item1: 10687},
//                {y: '2013 Q2', item1: 8432}
//            ],
//            xkey: 'y',
//            ykeys: ['item1'],
//            labels: ['Item 1'],
//            lineColors: ['#3c8dbc'],
//            hideHover: 'auto'
//        });

         //DONUT CHART
//        var donut = new Morris.Donut({
//            element: 'sales-chart',
//            resize: true,
//            colors: ["#3c8dbc", "#f56954", "#00a65a"],
//            data: [
//                {label: "Download Sales", value: 12},
//                {label: "In-Store Sales", value: 30},
//                {label: "Mail-Order Sales", value: 20}
//            ],
//            hideHover: 'auto'
//        });
         /*
          * DONUT CHART
          * -----------
          */
         var placeholder = $("#pie-chart");
         placeholder.unbind();
         var data = [],
             series = Math.floor(Math.random() * 6) + 3;

         for (var i = 0; i < series; i++) {
             data[i] = {
                 label: "项目成员" + (i + 1),
                 data: Math.floor(Math.random() * 100) + 1
             }
         }

         var arr = [];


         $.getJSON("index.php?r=dboard/cnt",function(result){
             var j = 0;
             $.each(result, function(i, field){
                 arr[j] = {
                     label: field.program_name,
                     data: field.cnt
                 }
                 j++;
             });
             $.plot(placeholder, arr, {
                 series: {
                     pie: {
                         show: true,
                         combine: {
                             color: "#999",
                             threshold: 0.01
                         }
                     }
                 },
                 legend: {
                     show: false
                 }
             });
         });
//        alert(arr);

         /*
          * END DONUT CHART
          */
         /**
          *环状图
          */
//        var placeholder = $("#world-map");
//        placeholder.unbind();
         var data = [],
             series = Math.floor(Math.random() * 6) + 3;

         var donutData = [];

//        var donutData = [
//            { label: 'Series2', data: 30, color: '#3c8dbc' },
//            { label: 'Series3', data: 20, color: '#0073b7' },
//            { label: 'Series4', data: 50, color: '#00c0ef' }
//        ];
         $.getJSON("index.php?r=dboard/filespace",function(result){
             var j = 0;
             $.each(result, function(i, field){
//                alert(field.label);
//                alert(field.data);
                 if(field.label == 'Remaining Size'|| field.label == '剩余空间'){
                     donutData[j] = {
                         label: field.label,
                         data: field.data,
                         color:'#CFCFCF'
                     }
                 }else{
                     donutData[j] = {
                         label: field.label,
                         data: field.data,
                         color:getRandomColor()
                     }
                 }
                 j++;
             });
             $.plot('#file-space', donutData, {
                 series: {
                     pie: {
                         show : true,
                         radius : 1,
                         innerRadius: 0.5,
                         label : {
                             show : true,
                             radius : 2 / 3,
                             formatter: labelFormatter,
                             threshold: 0.01
                         }
                     }
                 },
                 legend: {
                     show: true
                 }
             })
         });



         //随机颜色
         function getRandomColor(){
             return "#"+("00000"+((Math.random()*16777215+0.5)>>0).toString(16)).slice(-6);
         }
         //BAR CHART

         $.ajax({
             url: "index.php?r=dboard/accidentscnt",
             type: "GET",
             dataType: "json",
             success: function(data) {
                 var j = 0;
                 var datasets = [];
                 var letter = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'];
                 if(data != null) {
                     $.each(data, function (i, field) {
                         datasets[j] = {
                             y: field.label,
                             a: field.data,
                         }
                         j++;
                     });
                 }else{
                     datasets[0] = {
                         y: 'No Data',
                         a: 0,
                     }
                 }
                 var i = 0;
                 $.each(datasets, function(key, val) {
                     val.color = i;
                     ++i;
                 });
                 var bar = new Morris.Bar({
                     element: 'bar-chart',
                     resize: true,
                     data: datasets,
//            data: [
//                {y: '2011', a: 30, b: 24},
//                {y: '2012', a: 25, b: 21},
//                {y: '2013', a: 18, b: 17},
//                {y: '2014', a: 19, b: 20},
//                {y: '2015', a: 15, b: 11},
//                {y: '2016', a: 9, b: 7},
//                {y: '2017', a: 7, b: 7}
//            ],
                     barColors: ['#00a65a', '#f56954'],
//                    barColors: ['#00a65a'],
                     xkey: 'y',
                     ykeys: ['a'],
                     labels: ['CPU', 'DISK'],
                     hideHover: 'auto'
                 });

             }
         });

     });
     //    select count(check_id),root_proid,root_proname from bac_safety_check where contractor_id = '101' GROUP BY root_proid
     function labelFormatter(label, series) {
         return "<div style='font-size:8pt; text-align:center; padding:22px; color:black;'>" +  Math.round(series.percent) + "%</div>";
     }
     function setCode(lines) {
         $("#code").text(lines.join("\n"));
     }
</script>
