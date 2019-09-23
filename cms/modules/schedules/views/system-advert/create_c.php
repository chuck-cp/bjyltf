<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use cms\models\AdvertPosition;

$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
/* @var $this yii\web\View */
/* @var $model cms\modules\schedules\models\SystemAdvert */
/* @var $form yii\widgets\ActiveForm */
$this->title = '新增广告';
?>

<div class="system-advert-form">

    <?php $form = ActiveForm::begin(); ?>

    <table class="table table-hover" >
        <tr>
            <td style="width: 95px;">*广告位:</td>
            <td colspan="3">
                <?= $form->field($model,'advert_position_id')->dropDownList(AdvertPosition::getAllAdvertname(1),['class'=>'form-control fm'])->label(false)?>
                <?= Html::activeHiddenInput($model,'advert_position_key',array('value'=>$dataone['key']))?>
            </td>
        </tr>
        <tr>
            <td style="width: 95px;">*投放时长:</td>
            <td colspan="3">
                <?= $form->field($model,'advert_time')->dropDownList($dataone['time'],['class'=>'form-control fm'])->label(false);?>
            </td>
        </tr>
        <tr>
            <td style="width: 95px;">*投放频次:</td>
            <td colspan="3">
                <?= $form->field($model,'throw_rate')->dropDownList($dataone['rate'],['class'=>'form-control fm'])->label(false)?>
            </td>
        </tr>
        <tr>
            <td style="width: 95px;">*广告名称:</td>
            <td colspan="3">
                <?= $form->field($model,'advert_name')->textInput([])->label(false)?>
            </td>
        </tr>
        <tr>
            <td style="width: 95px;">*投放时间:</td>
            <td colspan="3">
                <?=$form->field($model,'start_at')->textInput(['class'=>'form-control datepicker fm collection-width','placeholder'=>'开始时间'])->label(false);?>
                <?=$form->field($model,'end_at')->textInput(['class'=>'form-control datepicker fm mtop22 collection-width','placeholder'=>'结束时间'])->label(false);?>
            </td>
        </tr>
        <tr>
            <td style="width: 95px;">*投放地区:</td>
            <td colspan="3">
                <div class="row" style="margin-bottom: 50px;">
                    <div id="province_check" class="row-h">
                        <div class="list">省</div>
                        <ul class="ul first">
                           <!-- <input id="province_all" name="province_name[]" type="checkbox" value="101" /><a id="101" >全国</a>-->
                            <? foreach ($province as $k => $v):?>
                                <li>
                                    <span class="zone">
                                        <!--<input name="province_name[]" type="checkbox" class="province_c" value="<?/*=Html::encode($v['id'])*/?>" />-->
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
                            <input id="city_all" name="city_name[]" type="checkbox" value="" /><a  >全部</a>
                        </ul>
                    </div>
                    <p><a style="cursor: pointer" class="putin-area">查看已投放地区</a></p>
                </div>
                <!--<a id="addarea" style="margin-right: 30px; cursor:pointer;">选择投放区域</a><a style="cursor:pointer;">查看已投放地区</a>-->
            </td>
        </tr>
        <tr>
            <td style="width: 95px;">*上传素材:</td>
            <td colspan="3" class="imgs" <?if($dataone['type'] == 1):?>style="display:none;"<?endif;?>>
                <?= $form->field($model,'image_url')->widget('yidashi\uploader\SingleWidget')->label(false); ?>
            </td>
            <td colspan="3" class="videos" <?if($dataone['type'] == 2):?>style="display:none;"<?endif;?>>
                <form id="form1">
                    <input id="uploadVideoNow-file" type="file" style="display:none;"/>
                </form>
                <input type="button" id="uploadVideoNow" value="上传" /><br />
                <div class="row" style="margin-left:7%; margin-bottom:20px;display:none" id="txvideo">
                </div>
                <div class="row" id="resultBox" style="padding:0 0 10px 27px;">

                </div>
                <input type="hidden" id="videoSha1" name="sha1_video" >
                <input type="hidden" id="size1" name="size_video" >
                <input type="hidden" id="video_url" name="video_url" >
            </td>
        </tr>
    </table>
    <div class="form-group">
        <?= Html::Button('提交',['class'=>'btn btn-primary'])?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>


<script src="//imgcache.qq.com/open/qcloud/js/vod/sdk/ugcUploader.js"></script>
<script src="//imgcache.qq.com/open/qcloud/video/tcplayer/tcplayer.min.js"></script>

<script src="/static/js/sha1.js"></script>
<script type="text/javascript">

    $('ul').on('click','.click',function (){
        var id = $(this).attr('id');
        var advert_id=$('input[name="advert_id"]').val();
        var nextSel = $(this).parents('.row-h').next().next().find('ul');
        var selObj = $(this).parents('.row-h');
        selObj.nextAll().find('ul').find('li').find('span').find('a').remove();
        selObj.nextAll().find('ul').find('li').find('span').find('input').remove();
        $(this).parents("ul").find(".click").removeClass("cur");
        $(this).addClass("cur");
        $.ajax({
            url: '<?=\yii\helpers\Url::to(['addresscity'])?>',
            type: 'get',
            dataType: 'json',
            data:{'parent_id':id,'advert_id':advert_id},
            success:function (phpdata) {
                $.each(phpdata,function (i,item) {
                    nextSel.append('<li ><span class="zone"><input  class="city_c" id="city_all" name="city_name[]" type="checkbox" value='+i+' /><a id='+i+' class="click" >'+item.name+'</a></span></li>');
                })
            },error:function (phpdata) {
                layer.msg('获取失败！');
            }
        })
    });
    $("#systemadvert-advert_position_id").change(function() {
        var advertid = $("#systemadvert-advert_position_id option:checked").val();
        $.ajax({
            url:'<?=\yii\helpers\Url::to(['/config/advert-config/ajaxplace'])?>',
            type : 'POST',
            ContentType: "application/json; charset=utf-8",
            dataType : 'json',
            data : {'advertid':advertid},
            success:function(phpdata){
                $('#systemadvert-advert_time').empty();
                $('#systemadvert-throw_rate').empty();
                $.each(phpdata.time,function (i,item) {
                    $('#systemadvert-advert_time').append('<option value='+i+'>'+item+'</option>');
                });
                $.each(phpdata.rate,function (ir,itemr) {
                    $('#systemadvert-throw_rate').append('<option value='+ir+'>'+itemr+'</option>');
                });
                $('#systemadvert-advert_position_key').attr('value', phpdata.key);
                if(phpdata.type == 2){
                    $('.imgs').show();
                    $('.videos').hide();
                }else if(phpdata.type == 1){
                    $('.imgs').hide();
                    $('.videos').show();
                }
            },
            error:function(){
               layer.msg('操作失败！');
            }
        })
    });
    $('#city_all').click(function () {
        // prop()方法可以设置布尔类型的属性true或false
        // thisChecked  等于true时复选框为选中状态
        var thisChecked = $(this).prop('checked');
        // 让.second中的input的checked的值等于thisChecked
        $('.second input').prop('checked',thisChecked);

    })
    $('ul').on('click','.city_c',function(){
        // .first 中被选中的复选框个数
        var num = $('.second input:checked').length;
        // .first 中所有复选框的个数
        var sum = $('.second input').length;
        if(num == sum){
            // num == sum 说明是全选
            $('#city_all').prop('checked',true);
        }else{
            $('#city_all').prop('checked', false);
        }

    });

    $('.btn-primary').click(function(){
        var data=$('#w0').serialize();
        $.ajax({
            url:'<?=\yii\helpers\Url::to(['create_c'])?>',
            type : 'POST',
            dataType : 'json',
            data : data,
            success:function (data) {
                if(data.code==1){
                    layer.msg(data.msg,{icon:1});
                    setTimeout(function(){
                        self.location=document.referrer;
                    },2000);
                }else{
                    layer.msg(data.msg,{icon:2});
                }
            },error:function (error) {
                layer.msg('操作失败！',{icon:7});
            }
        });
    })
    $('.putin-area').click(function(){
        var pageup = layer.open({
            type: 2,
            title: '查看已投放地区',
            shadeClose: true,
            shade: 0.8,
            area: ['70%', '70%'],
            content: '<?=\yii\helpers\Url::to(['/schedules/system-advert/putin-area'])?>'
        });
    })
    $(function(){
        //上传视屏
        var index = 0;
        var cosBox = [];
        var getSignature = function(callback){
            $.ajax({
                url: "<?php echo \yii\helpers\Url::to(['/examine/order/qm']); ?>",
                type: 'get',
                dataType: 'json',
                success: function(res){
                    if(res.token) {
                        callback(res.token);
                    } else {
                        return '获取签名失败';
                    }
                }
            });
        };



        //添加上传信息模块
        var addUploaderMsgBox = function(type){
            var html = '<div class="uploaderMsgBox" name="box'+index+'">';
            if(!type || type == 'hasVideo') {
                html += /*'视频名称：<span name="videoname'+index+'"></span>；' +*/
                    '计算进度：<span name="videosha'+index+'">0%</span>；' +
                    '上传进度：<span name="videocurr'+index+'">0%</span>；' +
                    'fileId：<span name="videofileId'+index+'">   </span>；' +
                    '上传结果：<span name="videoresult'+index+'">   </span>；<br>' +
                    /*'地址：<span name="videourl'+index+'">   </span>；'+*/
                    '<a href="javascript:void(0);" name="cancel'+index+'" cosnum='+index+' act="cancel-upload">取消上传</a><br>';
            }

            if(!type || type == 'hasCover') {
                html += '封面名称：<span name="covername'+index+'"></span>；' +
                    '计算进度：<span name="coversha'+index+'">0%</span>；' +
                    '上传进度：<span name="covercurr'+index+'">0%</span>；' +
                    '上传结果：<span name="coverresult'+index+'">   </span>；<br>' +
                    '地址：<span name="coverurl'+index+'">   </span>；<br>' +
                    '</div>'
            }
            html += '</div>';

            $('#resultBox').append(html);
            return index++;
        };
        // 示例1：直接上传视频
        $('#uploadVideoNow-file').on('change', function (e) {
            var num = addUploaderMsgBox('hasVideo');
            var videoFile = this.files[0];
            sha1File(videoFile);
            $('#size1').val(videoFile.size);
            $('#result').append(videoFile.name +　'\n');
            var resultMsg = qcVideo.ugcUploader.start({
                videoFile: videoFile,
                getSignature: getSignature,
                allowAudio: 1,
                success: function(result){
                    if(result.type == 'video') {
                        $('[name=videoresult'+num+']').text('上传成功');
                        $('[name=cancel'+num+']').remove();
                        cosBox[num] = null;
                    } else if (result.type == 'cover') {
                        $('[name=coverresult'+num+']').text('上传成功');
                    }
                },
                error: function(result){
                    if(result.type == 'video') {
                        $('[name=videoresult'+num+']').text('上传失败>>'+result.msg);
                    } else if (result.type == 'cover') {
                        $('[name=coverresult'+num+']').text('上传失败>>'+result.msg);
                    }
                },
                progress: function(result){
                    if(result.type == 'video') {
                        $('[name=videoname'+num+']').text(result.name);
                        $('[name=videosha'+num+']').text(Math.floor(result.shacurr*100)+'%');
                        $('[name=videocurr'+num+']').text(Math.floor(result.curr*100)+'%');
                        $('[name=cancel'+num+']').attr('taskId', result.taskId);
                        cosBox[num] = result.cos;
                    } else if (result.type == 'cover') {
                        $('[name=covername'+num+']').text(result.name);
                        $('[name=coversha'+num+']').text(Math.floor(result.shacurr*100)+'%');
                        $('[name=covercurr'+num+']').text(Math.floor(result.curr*100)+'%');
                    }

                },
                finish: function(result){
                    console.log(result);
                    $('[name=videofileId'+num+']').text(result.fileId);
                    $('[name=videourl'+num+']').text(result.videoUrl);
                    $("#videoid").attr("value",result.fileId);
                    $("#videourl").attr("value",result.videoUrl);
                    $("#video_url").attr("value",result.videoUrl);
                    if(result.message) {
                        $('[name=videofileId'+num+']').text(result.message);
                    }
                    if(result.fileId){
                        $("#txvideo").show();
                        $("#txvideo").html('<video controls  width="480" height="260" ><source src='+result.videoUrl+' type="video/mp4" />  </video>');
                    }
                }
            });
            if(resultMsg){
                $('[name=box'+num+']').text(resultMsg);
            }
            $('#form1')[0].reset();
            console.log($('#form1')[0].reset());
        });
        $('#uploadVideoNow').on('click', function () {
            $('#uploadVideoNow-file').click();
        });
    })

</script>
<style type="text/css">
    .fm{width: 150px;}
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