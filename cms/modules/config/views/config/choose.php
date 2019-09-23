<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
\cms\assets\AppAsset::register($this);
$this->registerCss('
    .summary{visibility:hidden;}
    #w0{padding:0 10px;}
    .sub{text-align:center;margin-top:15px;}
');
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <head>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody(); ?>
    <style type="text/css">
        .ul{ width:100%;float: left;margin-left: 15px;margin-top: 8px;}
        li{list-style: none;margin-top: 10px;position: relative; padding-left: 10px; padding-top: 3px; }
        .row-h{float: left;}
        .list{text-align: center;line-height: 35px;font-weight: 700;font-size: 14px;}
        .zone{cursor: pointer;}
        .hover{ background: #ddd}
        .redhover{background:#e4b9c0}
        .ck{ position: absolute;left: 0; width: 80%; opacity: 0}
    </style>
    <div class="row">
        <div class="row-h"  style=" height: 400px; overflow-y:scroll;overflow-x: hidden; width:200px">
            <div class="list">省级行政区</div>
            <ul class="ul first">
                <? foreach ($province as $k => $v):?>
                    <li data_id="<?=Html::encode($k)?>">
                        <input type="checkbox" class="ck" value="<?=Html::encode($k)?>">
                        <span class="zone"  >
                            <?=Html::encode($v)?>
                        </span>
                    </li>
                <? endforeach;?>
            </ul>
        </div>
        <div class="row-h"  style=" height: 400px; overflow-y:scroll;overflow-x: hidden; width:200px">
            <div class="list">地级行政区</div>
            <ul class="ul second">

            </ul>
        </div>
        <div class="row-h"  style=" height: 400px; overflow-y:scroll;overflow-x: hidden; width:200px">
            <div class="list">县级行政区</div>
            <ul class="ul third">

            </ul>
        </div>
    </div>
    <div class="row sub">
        <input class="redisarealevel" type="hidden" name="area" value=""/>
        <input class="level" type="hidden" name="level" value="<?=Html::encode($level)?>"/>
        <button type="button" class="btn btn-primary" data-type="pass">确定</button>
    </div>
    <script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
    <script type="text/javascript">
        $(function () {
            $('ul').on('click','.ck',function () {
                var index = $(this).index();
                var parent_id =  $(this).parent().attr('data_id');
                //redis存储等级区域价格
                if(parent_id.length ==7){
                    var areahtml = parent_id+',';
                    $('.redisarealevel').val($('.redisarealevel').val() + areahtml);
                }
                //地区选择
                if($(this).hasClass('zone')){
                    $(this).prev().attr('checked',true);
                }
                var ulName=$(this).parents('ul').attr('class');
                var liClassN=$(this).parent().attr('class');
                var sxbs=$(this).attr('data-ar');  //获取不可修改标识
                if(ulName=="ul third"){
                    if(sxbs!='0'){
                        if(liClassN=='hover'){
                            $(this).parent().removeClass('hover');
                        }else{
                            $(this).parent().addClass('hover');
                        }
                    }
                }else{
                    $(this).parent().siblings().find('input').attr('checked',false);
                    if(liClassN=='hover'){
                        $(this).parent().removeClass('hover');
                    }else{
                        $(this).parent().addClass('hover');
                        $(this).parent().siblings().removeClass('hover');
                    }
                }
                var nextSel = $(this).parents('.row-h').next().find('ul');
                var nextAll = $(this).parents('.row-h').nextAll().find('ul');
                var level = "<?=Html::encode($level)?>";
                if(parent_id.length < 9){
                    $.ajax({
                        url: '<?=\yii\helpers\Url::to(['address'])?>',
                        type: 'get',
                        dataType: 'json',
                        data:{'parent_id':parent_id,'level':level},
                        success:function (phpdata) {
                            nextAll.find('li').remove();
                            if(parent_id.length < 7){
                                $.each(phpdata,function (i,item) {
                                    nextSel.append('<li data_id='+i+'><input type="checkbox" class="ck" value='+i+'><span class="zone">'+item+'</span></li>');
                                })
                            }else{
                                $.each(phpdata,function (i,item) {
                                    if(item.check == 0 && item.disable == 0){
                                        nextSel.append('<li data_id='+i+'><input type="checkbox"  class="ck" value='+i+'><span class="zone">'+item.name+'</span></li>');
                                    }else if(item.check == 1 && item.disable == 1){
                                        nextSel.append('<li class="redhover" data_id='+i+'><input type="checkbox" checked="checked" data-ar="0"  class="ck" value='+i+'><span class="zone">'+item.name+'</span></li>');
                                    }else if(item.check == 1 && item.disable == 0){
                                        nextSel.append('<li class="hover" data_id='+i+' ><input type="checkbox" checked="checked"  class="ck" value='+i+'><span class="zone">'+item.name+'</span></li>');
                                    }
                                })
                            }
                        },error:function (data) {

                        }
                    });
                }else{
                    //如果是最后的地区直接标记到数据库
                    //查看当前是否禁用
                    if($(this).attr('data-ar')==0){
                        // $(this).attr('disabled','disabled');
                        layer.msg('当前区域不可选！');
                        return false;
                    }
                    //获取当前area_id
                    var area_id = $(this).parent().attr('data_id');
                    //当前是否选中
                    var ck = $(this).parent().find('input').is(':checked');
                    if(ck){
                        var isck = 1;
                    }else{
                        var isck = 0;
                    }
                    $.ajax({
                        url: '<?=\yii\helpers\Url::to(['remark'])?>',
                        type: 'get',
                        dataType: 'json',
                        data:{'area_id':area_id,'level':level,'isck':isck},
                        success:function (phpdata) {
//                            console.log(phpdata);
                        }
                    });
                }
            })
            //点击确定
            $('.sub button').click(function () {
                var level = "<?=Html::encode($level)?>";
                var areas = $('.redisarealevel').val();
                $.ajax({
                    url: '<?=\yii\helpers\Url::to(['area-level'])?>',
                    type: 'post',
                    dataType: 'json',
                    data:{'areas':areas,'level':level},
                    success:function (phpdata) {

                    }
                });
                layer.closeAll('page');
                //window.close();
                parent.location.reload();
            })
            //全选
            // $('.selectAll').click(function(){
            //    $(this).find('span').text('取消全选')
            //    if($(this).find('input').prop('checked')==true){
            //       $('.third li').each(function(){
            //         if($(this).attr('class')!='redhover'){
            //            $(this).addClass('hover');
            //            $(this).find('input').attr('checked',true);
            //          }
            //       })
            //    }else{
            //       $(this).find('span').text('全选') ;
            //       $('.third li').each(function(){
            //         if($(this).attr('class')!='redhover'){
            //            $(this).removeClass('hover');
            //            $(this).find('input').attr('checked',false);
            //          }
            //       })
            //    }
            // })
        })
    </script>
    <?php $this->endBody() ?>
    </body>
    </html >
<?php $this->endPage() ?>