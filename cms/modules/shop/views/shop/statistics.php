<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model cms\modules\sign\models\SignTeam */
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
$this->title = '店铺数据统计 ' ;
$this->params['breadcrumbs'][] = '店铺数据统计';
?>
<div class="log-payment-search">
    <?php $areas= max($searchModel->province,$searchModel->city,$searchModel->area,$searchModel->town); $form = ActiveForm::begin([
        'action' => ['statistics'],
        'method' => 'get',
    ]); ?>
    <div class="row">
        <table class="grid table table-striped table-bordered search">
            <tr>
                <td>
                    <p>店铺类型</p>
                    <?= $form->field($searchModel, 'store_type')->dropDownList(['1'=>'签约店铺','2'=>'安装店铺'],['class'=>'form-control fm type','prompt'=>'全部'])->label(false); ?>
                </td>
                <td>
                    <p class="date" >按时间统计</p>
                    <?=$form->field($searchModel,'create_at_start')->textInput(['class'=>'form-control datepicker collection-width fm date','placeholder'=>'开始时间'])->label(false);?>
                </td>
                <td  colspan="3">
                    <?=$form->field($searchModel,'create_at_end')->textInput(['class'=>'form-control datepicker mtop22 collection-width fm   date','placeholder'=>'结束时间'])->label(false);?>
                </td>
            </tr>
            <tr>
                <td>
                    <p>所属省</p>
                    <?php  echo $form->field($searchModel, 'province')->dropDownList(\cms\models\SystemAddress::getAreasByPid(101),['prompt'=>'全部','key'=>'province','class'=>'form-control fm area'])->label(false) ?>
                </td>
                <td>
                    <p>所属市</p>
                    <?php  echo $form->field($searchModel, 'city')->dropDownList(\cms\models\SystemAddress::getAreasByPid($searchModel->province),['prompt'=>'全部','key'=>'city','class'=>'form-control fm area'])->label(false) ?>
                </td>
                <td>
                    <p>所属区</p>
                    <?php  echo $form->field($searchModel, 'area')->dropDownList(\cms\models\SystemAddress::getAreasByPid($searchModel->city),['prompt'=>'全部','key'=>'area','class'=>'form-control fm area'])->label(false) ?>
                </td>
                <td>
                    <p>所属街道</p>
                    <?php  echo $form->field($searchModel, 'town')->dropDownList(\cms\models\SystemAddress::getAreasByPid($searchModel->area),['prompt'=>'全部','key'=>'town','class'=>'form-control fm'])->label(false) ?>
                </td>
                <td colspan="2">
                    <br />
                    <?= Html::submitButton('搜索', ['class' => 'btn btn-primary', 'name'=>'search', 'value'=>1]) ?>
                    <?if($searchModel->store_type==1):?>
                        <?=Html::a('查看详情',['signing-shop','create_at_start'=>$searchModel->create_at_start,'create_at_end'=>$searchModel->create_at_end,'areas'=>$areas?$areas:0], ['class' => 'btn btn-success'])?>
                    <?elseif ($searchModel->store_type==2):?>
                        <?=Html::a('查看详情',['install-shop','create_at_start'=>$searchModel->create_at_start,'create_at_end'=>$searchModel->create_at_end,'areas'=>$areas?$areas:0], ['class' => 'btn btn-success'])?>
                    <?else:?>
                        <?=Html::a('查看详情',['signing-shop','create_at_start'=>$searchModel->create_at_start,'create_at_end'=>$searchModel->create_at_end,'areas'=>$areas?$areas:0], ['class' => 'btn btn-success'])?>
                    <?endif;?>
                </td>
            </tr>
        </table>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<div>
    <table class="table table-bordered number-shop" style="text-align: center;">
        <tr>
            <td>安装店铺总量&nbsp;&nbsp;&nbsp;<img style=" vertical-align: middle;"  width="4%" src="static/img/gaodemap/1_1.png"></td>
            <td>未安装店铺总量&nbsp;&nbsp;&nbsp;<img style=" vertical-align: middle;" width="4%" src="static/img/gaodemap/2_1.png"></td>
            <td>新增店铺签约量&nbsp;&nbsp;&nbsp;<img style=" vertical-align: middle;" width="4%" src="static/img/gaodemap/3_1.png"></td>
            <td>新增店铺安装量&nbsp;&nbsp;&nbsp;<img style=" vertical-align: middle;" width="4%" src="static/img/gaodemap/4_1.png"></td>
        </tr>
        <tr>
            <th style="text-align: center;">
                <span class="number"><?=Html::encode($stat['total_shop'])?></span><b/>个
            </th>
            <th style="text-align: center;">
                <?if(!$searchModel->store_type):?>
                    <span class="number">
                        <?=Html::encode($stat['not_install_total_shop'])?>
                    </span><b/>个
                <?else:?>
                    <span class="number">---</span>
                <?endif;?>
            </th>
            <th style="text-align: center;">
                <?if($searchModel->store_type==2):?>
                    <span class="number">---</span>

                <?else:?>
                    <span class="number">
                        <?=Html::encode($stat['new_signing_total_shop'])?>
                    </span><b/>个
                <?endif;?>
            </th>
            <th style="text-align: center;">
                <?if($searchModel->store_type==1):?>
                    <span class="number">---</span>
                <?else:?>
                    <span class="number">
                        <?=Html::encode($stat['new_install_total_shop'])?>
                    </span><b/>个
                <?endif;?>
            </th>
        </tr>
        <tr>

        </tr>
    </table>
</div>
<div id="container" class="map" tabindex="0" style="width: 1600px;height: 800px;"></div>
<style>
    html,body{
        margin:0;
        width:100%;
        height:100%;
        background:#ffffff;
    }
    #map{
        width:100%;
        height:750px;
        border:#ccc solid 1px;font-size:12px
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
    .number{
        font-size: 26px;
        margin-right: 3px;
    }
    .number-shop tr td{
        font-size: 16px;
        font-weight: bold;
    }
    .guanbi{
        display: inline-block;
        float: right;
    }
</style>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script>
    $(function () {
        $('.area').change(function () {
            var type = $(this).attr('key');
            var selObj = $('[key='+type+']').parents('td');
            selObj.nextAll().find('select').find('option:not(:first)').remove();
            var parent_id = $(this).val();
            if(!parent_id){
                return false;
            }
            $.ajax({
                url: '<?=\yii\helpers\Url::to(['/member/member/address'])?>',
                type: 'POST',
                dataType: 'json',
                data:{'parent_id':parent_id},
                success:function (phpdata) {
                    $.each(phpdata,function (i,item) {
                        selObj.next().find('select').append('<option value='+i+'>'+item+'</option>');
                    })
                },error:function (phpdata) {
                    layer.msg('获取失败！');
                }
            })
        })
        var store_type = $('#shopsearch-store_type').val();
        if(!store_type){
            $('.date').hide();
        }
        $('.type').change(function(){
           var type = $(this).val();
           if(type){
               $('.date').show();
           }else{
               $('.date').hide();
           }
        })
    })
</script>
<script type="text/javascript"
        src="https://webapi.amap.com/maps?v=1.4.12&key=5a157ca18a863919d096de8adc4e88e6&plugin=AMap.Geocoder"></script>
<script type="text/javascript">
    var citys  = <?php echo $stat['citys']?>;
    //var citys=[{"title":"","name":"<b>店铺编号:<\/b> 768 <br\/><b>店名:<\/b> 诗呀阁理发店<br\/> <b>地区:<\/b> 北京市北京市东城区东华门街道 <br\/><b>详址:<\/b> 北京市北京市东城区东华门街道北京市东城区东华门街道诗呀阁理发店 <br\/><b>安装台数:<\/b> 5<br\/><b>镜面数量:<\/b> 5","lnglat":[116.40323991709,39.915204120254],"name2":"北京市北京市东城区东华门街道北京市东城区东华门街道诗呀阁理发店","city":"北京市","style":0}];
    console.log(citys)
    var map = new AMap.Map('container', {
        zoom: 10,
        center: [116.397477,39.908692]
    });

    var style = [{
        url: 'static/img/gaodemap/1_1.png',
        anchor: new AMap.Pixel(6, 6),
        size: new AMap.Size(15, 23)
    }, {
        url: 'static/img/gaodemap/2_1.png',
        anchor: new AMap.Pixel(4, 4),
        size: new AMap.Size(15, 23)
    }, {
        url: 'static/img/gaodemap/4_1.png',
        anchor: new AMap.Pixel(3, 3),
        size: new AMap.Size(15, 23)
    }, {
        url: 'static/img/gaodemap/3_1.png',
        anchor: new AMap.Pixel(3, 3),
        size: new AMap.Size(15, 23)
    }
    ];

    var mass = new AMap.MassMarks(citys, {
        opacity: 0.8,
        zIndex: 111,
        cursor: 'pointer',
        style: style
    });

    var marker = new AMap.Marker({content: ' ', map: map});

    mass.on('click', function (e) {

        marker.setPosition(e.data.lnglat);
        marker.setLabel({content: e.data.name})
    });

    mass.setMap(map);

    function setStyle(multiIcon) {
        if (multiIcon) {
            mass.setStyle(style);
        } else {
            mass.setStyle(style[2]);
        }
    }
    function geoCode(address,i,type) {
        var geocoder,marker;
        if(!geocoder){
            geocoder = new AMap.Geocoder({
                city: "010", //城市设为北京，默认：“全国”
            });
        }
        var icon = new AMap.Icon({
            size: new AMap.Size(40, 50),    // 图标尺寸
            image: '//webapi.amap.com/theme/v1.3/images/newpc/way_btn2.png',  // Icon的图像
            imageOffset: new AMap.Pixel(0, -60),  // 图像相对展示区域的偏移量，适于雪碧图等
            imageSize: new AMap.Size(40, 50)   // 根据所设置的大小拉伸或压缩图片
        });
        // var address  = "北京市朝阳区阜荣街10号";
        // console.log(address['name2'])
        // console.log(citys4[i]['name2'])
        geocoder.getLocation(address['name2'], function(status, result) {
            if (status === 'complete'&&result.geocodes.length) {
                var lnglat = result.geocodes[0].location
                // if (type==2) {
                //     citys4[i]['lnglat']=[lnglat['lng'],lnglat['lat']];

                // }else{
                //     citys2[i]['lnglat']=[lnglat['lng'],lnglat['lat']];
                // }
                if(!marker){
                    marker = new AMap.Marker();
                    map.add(marker);
                }
                marker.setPosition(lnglat);

            }
        });
    }
    function feng(){
        marker.setLabel({content: ""})
    }
    // for(var i=0;i<citys2.length;i++){
    //     geoCode(citys2[i],i,2)
    //    // console.log(lnglat)
    // }
    // for(var i=0;i<citys4.length;i++){
    //     geoCode(citys4[i],i,4)
    //    // console.log(lnglat)
    // }
    // var csz = citys2.concat(citys4)
    // cs = citys.concat(csz)

</script>
