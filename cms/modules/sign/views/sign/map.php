
<div id="allmap"></div>
    <input id="longitude" type="hidden" value="<?echo $longitude?>" style="width:100px; margin-right:10px;" />
    <input id="latitude" type="hidden"  value="<?echo $latitude?>" style="width:100px; margin-right:10px;" />

<style type="text/css">
    body, html{width: 100%;height: 100%;margin:0;font-family:"微软雅黑";}
    #allmap{height:500px;width:100%;}
    #r-result{width:100%; font-size:14px;}
</style>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<!--<script type="text/javascript" src="http://api.map.baidu.com/api?key=&v=1.1&services=true"></script>-->
<!--<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=uX28OgIzOvbBvfcCFMqxzORy6AGBvEHO"></script>-->
<script type="text/javascript" src="http://api.map.baidu.com/getscript?v=2.0&ak=uX28OgIzOvbBvfcCFMqxzORy6AGBvEHO&services=&t=20181029172410"></script>
<script type="text/javascript">
    // 百度地图API功能
    var map = new BMap.Map("allmap");
    map.centerAndZoom(new BMap.Point(116.331398,39.897445),11);
    map.enableScrollWheelZoom(true);

    // 用经纬度设置地图中心点
    map.clearOverlays();
    var new_point = new BMap.Point(document.getElementById("longitude").value,document.getElementById("latitude").value);
    var marker = new BMap.Marker(new_point);  // 创建标注
    map.addOverlay(marker);              // 将标注添加到地图中
    map.panTo(new_point);
</script>