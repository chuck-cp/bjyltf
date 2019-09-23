<!DOCTYPE HTML>
<html>
<head>
    <title>加载海量点</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <style type="text/css">
        html,body{
            margin:0;
            width:100%;
            height:100%;
            background:#ffffff;
        }
        #map{
            width:100%;
            height:100%;
        }
        #panel {
            position: absolute;
            top:30px;
            left:10px;
            z-index: 999;
            color: #fff;
        }
        #login{
            position:absolute;
            width:300px;
            height:40px;
            left:50%;
            top:50%;
            margin:-40px 0 0 -150px;
        }
        #login input[type=password]{
            width:200px;
            height:30px;
            padding:3px;
            line-height:30px;
            border:1px solid #000;
        }
        #login input[type=submit]{
            width:80px;
            height:38px;
            display:inline-block;
            line-height:38px;
        }
        .BMap_bubble_content{
            font-size: 12px;
        }
    </style>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=uX28OgIzOvbBvfcCFMqxzORy6AGBvEHO"></script>
    <script type="text/javascript" src="http://lbsyun.baidu.com/jsdemo/data/points-sample-data.js"></script>
</head>
<body>
<div id="map"></div>
<script type="text/javascript">
    var markerArr=<?php echo $citys;?>;
    var map = new BMap.Map("map", {enableMapClick:false});                        // 创建Map实例
   // map.centerAndZoom(new BMap.Point(105.000, 38.000), 5);     // 初始化地图,设置中心点坐标和地图级别
    map.enableScrollWheelZoom();
    <?if($province==101):?>
        var pointss = new BMap.Point(105.000, 38.000);
        map.centerAndZoom(pointss,5);
    <?else:?>
        var myGeo = new BMap.Geocoder();
        // 灏嗗湴鍧€瑙ｆ瀽缁撴灉鏄剧ず鍦ㄥ湴鍥句笂锛屽苟璋冩暣鍦板浘瑙嗛噹
        myGeo.getPoint("<?php echo $province;?>", function(pointss){
                if (pointss) {
                    map.centerAndZoom(pointss, 10);
                    // map.addOverlay(new BMap.Marker(point));
                }
            },
            "北京");
    <?endif;?>
    //启用滚轮放大缩小
    if (document.createElement('canvas').getContext) {  // 判断当前浏览器是否支持绘制海量点
        //瀹夎£呭簵閾烘€婚噺
        var points = [];  // 娣诲姞娴烽噺鐐规暟鎹®
        for (var i = 0; i < markerArr.length; i++) {
            var json = markerArr[i];
            points.push(new BMap.Point(json.j, json.w));
        }
        var options = {
            shape: 2,
        }
        var pointCollection = new BMap.PointCollection(points, options);
        pointCollection.addEventListener('click', function (e) {
            var content ="";
            for (var i = 0; i < markerArr.length; i++) {
                var json = markerArr[i];
                points.push(new BMap.Point(json.j, json.w));
                if (json.j == e.point.lng && json.w == e.point.lat) {
                    content = json.content
                    break;
                }
            }
            var point = new BMap.Point(e.point.lng, e.point.lat);
            var opts = {
                width: 250,
                height: 100,
                title:"",
                enableMessage: false,
            }
            var infowindow = new BMap.InfoWindow(content, opts);
            map.openInfoWindow(infowindow, point);


        });
        map.addOverlay(pointCollection);  // 娣诲姞Overlay
    } else {
        alert('请在chrome、safari、IE8+以上浏览器查看本示例');
    }
</script>
</body>
</html>

