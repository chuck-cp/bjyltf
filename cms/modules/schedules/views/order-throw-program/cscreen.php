<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\schedules\models\search\OrderThrowProgramSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->beginBlock('AppPage');
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
$this->endBlock();
$this->title = 'C屏区域排期';
$this->params['breadcrumbs'][] = 'C屏区域排期';
?>
<script src="http://libs.baidu.com/jquery/2.1.4/jquery.min.js"></script>
<script type="text/javascript">
    $(function(){
        $('ul').on('click','.click',function (){
            var id = $(this).attr('id');
           // alert(id);
            var nextSel = $(this).parents('.row-h').next().next().find('ul');
            var selObj = $(this).parents('.row-h');
            selObj.nextAll().find('ul').find('li').find('span').find('a').remove();
            $(this).parents("ul").find(".click").removeClass("cur");
            $(this).addClass("cur");
            $.ajax({
                url: '<?=\yii\helpers\Url::to(['address','advert_key'=>'c'])?>',
                type: 'get',
                dataType: 'json',
                data:{'parent_id':id},
                success:function (phpdata) {
                    $.each(phpdata,function (i,item) {
                        if(i.length ==12){
                            nextSel.append('<li ><span class="zone"><a style="color: #FF0000;"  id='+i+'  class="select">'+item.name+'</a><a style="margin-left:15px;"  >'+item.status+'</a></span></li>');
                        }else{
                            nextSel.append('<li ><span class="zone"><a id='+i+' class="click" >'+item.name+'</a><a style="color: #FF0000;margin-left:15px;">'+item.status+'</a></span></li>');
                        }
                    })
                },error:function (phpdata) {
                    layer.msg('获取失败！');
                }
            })
        })
        $('ul').on('click','.select',function(){

            var startat=$('input[name="startat"]').val();//获取开始时间
            var endat=$('input[name="endat"]').val();    //获取结束时间
            var area_id =$(this).attr('id');
            var table = $(this).parents().parents('.row').find('.table');
            var selObj = $(this).parents().parents('.row');
            selObj.nextAll().find('table').remove();
            if(!startat){
                layer.msg('请选择开始时间！',{icon:2})
                return false;
            }
            if(!endat){
                layer.msg('请选择结束时间！',{icon:2})
                return false;
            }
            //判断结束时间不能再开始时间之前
            if (parseInt(startat.replace(/-/g, ''), 10) > parseInt(endat.replace(/-/g, ''), 10)) {
                layer.msg('结束时间不能在开始时间之前！',{icon:2})
                return false;
            }
            //判断时间不能大于七天
            if(DateDiff(startat,endat)>7){
                layer.msg('所选时间必须在7天之内！',{icon:2})
                return false;
            }
            var layerMsg = layer.load('正在查询，请稍后...',{
                icon: 0,
                shade: [0.1,'black']
            });
            $.ajax({
                url: '<?=\yii\helpers\Url::to(['cscreen'])?>',
                type: 'get',
                dataType: 'json',
                data:{'area_id':area_id,'startat':startat,'endat':endat},
                success:function (phpdata) {
                    if(phpdata.code==1){
                        table.append('<table style="border-collapse: collapse"><tr class="thead"><td>日期（分：秒）时段</td></tr><tr><td>00：00-60：00</td></tr></table>');
                        var html = '';
                        $.each(phpdata.ProgramListRst,function(k1,v1){
                            html += '<table style="border-collapse: collapse"><tr class="thead"><td>'+k1+'</td></tr>';
                            $.each(v1,function (k, v) {
                                if(v.count==0){
                                    html += '<tr><td> 0张 </td></tr>';
                                }else{
                                    html += '<tr><td><a style="cursor: pointer" class="order" order_id="'+v.order_id+'">'+v.count+'张</a></td></tr>';
                                }
                            })
                            html += '</table>';
                        })
                        table.append(html);
                        $("table").each(function(){
                            $(this).find(".thead").css("background-color","#199ed8")
                        })
                        layer.closeAll();
                    }else{
                        layer.closeAll();
                        layer.msg('当前街道没有排期', {icon: 1});
                    }
                },error:function (phpdata){
                    layer.msg('获取失败！');
                }
            })

        })
        $('.table').on('click','.order',function(){
            var order_id=$(this).attr('order_id');
            var pageup = layer.open({
                type: 2,
                title: '订单列表',
                shadeClose: true,
                shade: 0.8,
                area: ['60%', '40%'],
                content: '<?=\yii\helpers\Url::to(['/schedules/order-throw-program/order'])?>&order_id='+order_id
            });
        })
    })
    function DateDiff(sDate1, sDate2) {  //sDate1和sDate2是yyyy-MM-dd格式
        var aDate, oDate1, oDate2, iDays;
        aDate = sDate1.split("-");
        oDate1 = new Date(aDate[1] + '-' + aDate[2] + '-' + aDate[0]);  //转换为yyyy-MM-dd格式
        aDate = sDate2.split("-");
        oDate2 = new Date(aDate[1] + '-' + aDate[2] + '-' + aDate[0]);
        iDays = parseInt(Math.abs(oDate1 - oDate2) / 1000 / 60 / 60 / 24); //把相差的毫秒数转换为天数
        return iDays+1;  //返回相差天数
    }
</script>
<div class="order-throw-program-index">
    <span class="search">查询日期：</span>
    <input type="text" class="form-control fm datepicker" name="startat" placeholder='开始时间'> -
    <input type="text" class="form-control fm datepicker" name="endat" placeholder='结束时间'>
    <h1></h1>
    <style type="text/css">
         .ul{ width:98%;height:200px;overflow-y:scroll;overflow-x: hidden; }
         .ul li{list-style: none;margin-top: 10px;}
        .list{text-align: center;height: 35px;line-height: 35px;font-weight: 700;font-size: 14px;}
        .zone{cursor: pointer;}
        .row{overflow: hidden;padding-left: 20px;}
        .row-h{float: left;height:245px; width:200px;border:1px solid #666}
        .row p{float: left;height:245px;line-height:245px;padding: 0 20px;}
        .cur{font-weight:bold;color:#000;}
         table{border-collapse:collapse;}
         .table table{float: left;}
         .table table tr td{border:1px solid #666;text-align:center;height: 30px;width:76%;border-left: 0;}
         .table table:first-child tr td{border-left:1px solid #666;}
    </style>
    <div class="row" style="margin-bottom: 50px;">
        <div class="row-h">
            <div class="list">省</div>
            <ul class="ul first">
                <? foreach ($province as $k => $v):?>
                    <li>
                        <span class="zone">
                            <a id="<?=Html::encode($v['id'])?>" class="click" >
                                <?=Html::encode($v['name'])?>
                            </a>
                            <a style="color: #FF0000;margin-left:15px;"><?=Html::encode($v['status'])?></a>
                        </span>
                    </li>
                <? endforeach;?>
            </ul>
        </div>
        <p><img src="static/img/schedules.png"></p>
        <div class="row-h">
            <div class="list">市</div>
            <ul class="ul second">

            </ul>
        </div>
        <p><img src="static/img/schedules.png"></p>
        <div class="row-h">
            <div class="list">区</div>
            <ul class="ul third">
            </ul>
        </div>
        <p><img src="static/img/schedules.png"></p>
        <div class="row-h"  style="width: 220px; ">
            <div class="list" >街道</div>
            <ul class="ul third">
            </ul>
        </div>
    </div>
    <div class="table">

    </div>

</div>

