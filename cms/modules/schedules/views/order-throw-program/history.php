<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\schedules\models\search\OrderThrowProgramSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->beginBlock('AppPage');
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
$this->endBlock();
$this->title = '历史排期';
$this->params['breadcrumbs'][] = '历史排期';
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
                url: '<?=\yii\helpers\Url::to(['addressls'])?>',
                type: 'get',
                dataType: 'json',
                data:{'parent_id':id},
                success:function (phpdata) {
                    $.each(phpdata,function (i,item) {
                        if(i.length ==12){
                            nextSel.append('<li ><span class="zone"><a style="color: #FF0000;"  id='+i+'  class="select">'+item.name+'</a></span></li>');
                        }else{
                            nextSel.append('<li ><span class="zone"><a id='+i+' class="click" >'+item.name+'</a></span></li>');
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
            var advert_key=$("#advert_key").val();       //获取分类的value值
           // var status=$("#status").val();       //获取分类的value值
            //var advert_name=$('select[name="advert_key"]').text();//获取分类的name值
           // var advert_name=$("#advert_key option:selected").text();//获取分类的name值
            var area_id =$(this).attr('id');             //获取地区ID
            var table = $(this).parents().parents('.row').find('.table');
            var selObj = $(this).parents().parents('.row');
            var date7 = fun_date(8);
            /*if(advert_key!=='A1' && advert_key!=='A2' && advert_key!=='B'){
                layer.msg(advert_name+'还需等几天吆',{icon:6})
                return false;
            }*/
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
            selObj.nextAll().find('table').remove();
            $.ajax({
                url: '<?=\yii\helpers\Url::to(['history'])?>',
                type: 'get',
                dataType: 'json',
                data:{'area_id':area_id,'startat':startat,'endat':endat,'advert_key':advert_key/*,'status':status*/},
                success:function (phpdata) {
                    if(phpdata.code==1){
                        if(phpdata.advert_key=='a'){
                            table.append('<table style="border-collapse: collapse"><tr class="thead"><td>日期（分：秒）时段</td></tr><tr><td>00：00-05：00</td></tr><tr><td>05：00-06：00</td></tr><tr><td>06：00-11：00</td></tr><tr><td>11：00-12：00</td></tr><tr><td>12：00-17：00</td></tr><tr><td>17：00-18：00</td></tr><tr><td>18：00-23：00</td></tr><tr><td>23：00-24：00</td></tr><tr><td>24：00-29：00</td></tr><tr><td>29：00-30：00</td></tr><tr><td>30：00-35：00</td></tr><tr><td>35：00-36：00</td></tr><tr><td>36：00-41：00</td></tr><tr><td>41：00-42：00</td></tr><tr><td>42：00-47：00</td></tr><tr><td>47：00-48：00</td></tr><tr><td>48：00-53：00</td></tr><tr><td>53：00-54：00</td></tr><tr><td>54：00-59：00</td></tr><tr><td>59：00-60：00</td></tr></table>');
                            var html = '';
                            $.each(phpdata.ProgramListRst,function(k1,v1){
                                html += '<table style="border-collapse: collapse"><tr class="thead"><td>'+k1+'</td></tr>';
                                $.each(v1,function (k, v) {
                                    if(v.total_time_sum){
                                        if(v.total_time_sum==0){
                                            html += '<tr><td> 0s </td></tr>';
                                        }else{
                                            html += '<tr><td><a class="order" order_id="'+v.order_id+'">'+v.total_time_sum+'s</a></td></tr>';
                                        }
                                    }else{
                                        html += '<tr><td> 0s </td></tr>';
                                    }
                                    /*if(getTimee(k1)>getTimee(date7)){
                                        if(v.total_time_sum){
                                            html += '<tr><td>'+v.total_time_sum+'s</td></tr>';
                                        }else{
                                            html += '<tr><td> 0s </td></tr>';
                                        }
                                    }else{
                                        if(v.total_time_sum){
                                            html += '<tr><td><a class="order" order_id="'+v.order_id+'">'+v.total_time_sum+'s</a></td></tr>';
                                        }else{
                                            html += '<tr><td> 0s </td></tr>';
                                        }
                                    }*/

                                })
                                html += '</table>';
                            })
                            table.append(html);
                            $("table").each(function(){
                                $(this).find("tr:even").css("background-color","#e4e4e4")
                                $(this).find(".thead").css("background-color","#199ed8")
                            })
                            layer.closeAll();
                        }else{
                            table.append('<table style="border-collapse: collapse"><tr class="thead"><td>日期（分：秒）时段</td></tr><tr><td>00：00-60：00</td></tr></table>');
                            var html = '';
                            $.each(phpdata.ProgramListRst,function(k1,v1){
                                html += '<table style="border-collapse: collapse"><tr class="thead"><td>'+k1+'</td></tr>';
                                $.each(v1,function (k, v) {
                                    if(v.count){
                                        if(v.count==0){
                                            html += '<tr><td> 0张 </td></tr>';
                                        }else{
                                            html += '<tr><td ><a style="cursor: pointer" class="order"  order_id="'+v.order_id+'" >'+v.count+'张</a></td></tr>';
                                        }
                                    }else{
                                        html += '<tr><td> 0张 </td></tr>';
                                    }


                                })
                                html += '</table>';
                            })
                            table.append(html);
                            $("table").each(function(){
                                $(this).find(".thead").css("background-color","#199ed8")
                            })
                            layer.closeAll();
                        }

                    }else{
                        layer.closeAll();
                        layer.msg('当前街道没有排期', {icon: 2});
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

    /**
     * 获取七天后的日期或七天前的日期（）
     * fun_date(7),fun_date(-7)
     * @param aa
     */
    function fun_date(aa){
        var date1 = new Date(),
        time1=date1.getFullYear()+"-"+(date1.getMonth()+1)+"-"+date1.getDate();//time1表示当前时间
        var date2 = new Date(date1);
        date2.setDate(date1.getDate()+aa);
        var time2 = date2.getFullYear()+"-"+(date2.getMonth()+1)+"-"+date2.getDate();
        return time2;
    }

    /**
     * 日期转化时间戳
     */
    function getTimee(datetime){
        var T = new Date(datetime);  // 将指定日期转换为标准日期格式。Fri Dec 08 2017 20:05:30 GMT+0800 (中国标准时间)
        return T.getTime() // 将转换后的标准日期转换为时间戳。
    }


</script>
<style type="text/css">

    .ul{ width:98%;height:150px;overflow-y:scroll;overflow-x: hidden; }
    .ul li{list-style: none;margin-top: 10px;}
    .list{text-align: center;height: 35px;line-height: 35px;font-weight: 700;font-size: 14px;}
    .zone{cursor: pointer;}
    .row{overflow: hidden;padding-left: 20px;}
    .row-h{float: left;height:200px; width:200px;border:1px solid #666}
    .row p{float: left;height:200px;line-height:245px;padding: 0 20px;}
    .cur{font-weight:bold;color:#000;}
    table{border-collapse:collapse;}
    .table table{float: left;}
    .table table tr td{border:1px solid #666;text-align:center;height: 30px;width:76%;border-left: 0;}
    .table table:first-child tr td{border-left:1px solid #666;}
    .search{font-weight: bold;}
</style>
<div class="order-throw-program-index">
    <span class="search">查询日期：</span>
    <input type="text" class="form-control fm datepicker" name="startat" placeholder='开始时间'> -
    <input type="text" class="form-control fm datepicker" name="endat" placeholder='结束时间'>
    <span class="search" style="margin-left: 30px;">查询类别：</span>
    <select name="advert_key" class="form-control fm" style="width: 150px;" id="advert_key">
        <option value="A">A屏广告</option>
        <?php foreach ($KeyName as $v):?>
            <?php if($v['name']!=='CD屏图片广告' && $v['name']!=='A屏内容广告' && $v['name']!=='A屏视频广告'):?>
                <option value="<?php echo $v['key']?>"><?php echo $v['name']?></option>
            <?php endif;?>
        <?php endforeach;?>
       <!-- <option value="A">A屏内容广告</option>
        <option value="B">B屏图片广告</option>-->
    </select>
  <!--  <span class="search" style="margin-left: 30px;">锁定（su）—— 未锁定（xu）：</span>
    <select name="status" class="form-control fm" style="width: 150px;" id="status">
         <option value="1">锁定</option>
         <option value="2">未锁定</option>
    </select>-->
    <h1></h1>

  <!--  <select name="father" class="form-control fm" style="width: 150px;" id="father">
        <option value="A">A屏</option>
        <option value="B">B屏</option>
    </select>
    -->



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

   <!-- <p>
        <?/*= Html::a('Create Order Throw Program', ['create'], ['class' => 'btn btn-success']) */?>
    </p>-->

    <div class="table">

    </div>

</div>

