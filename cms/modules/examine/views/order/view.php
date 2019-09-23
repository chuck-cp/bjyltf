<?php

use yii\helpers\Html;
use yii\helpers\Url;
use cms\modules\member\models\OrderCopyright;
use cms\modules\member\models\OrderDate;
/* @var $this yii\web\View */
/* @var $model cms\modules\examine\models\Order */

$this->title = $model->id;
$this->params['breadcrumbs'][] = '审核管理';
$this->params['breadcrumbs'][] = ['label' => '广告审核', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('/static/css/tcplayer/tcplayer.css');
$this->registerJsFile('/static/js/tcplayer/videojs-ie8.js');
?>

<div class="member-view">
    <table class="table table-hover">
        <tr><h4><strong>基本信息</strong></h4></tr>
        <tr>
            <td>订单号：<?= Html::encode($model->order_code)?></td>
            <td>手机号：<?= Html::encode($model->salesman_mobile)?></td>
            <td>业务合作人：<?= Html::encode($model->salesman_name)?>
                <?php if($model->salesman_name!=''): ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=Html::a('查看',['/member/member/view','id'=>$model->member_id],['target'=>'_blank'])?>
                <?php endif;?>
            </td>
            <td>广告位：<?= Html::encode($model->advert_name)?></td>
            <td></td>
        </tr>
        <tr>
            <td>广告时长：<?= Html::encode($model->advert_time)?></td>
            <td>播放频次：<?= Html::encode($model->rate)?></td>
            <td>投放日期：<?=Html::encode(OrderDate::getOrderDate($model->id))?></td>
            <td></td>
        </tr>
        <tr>
            <td>
                <div class="dropdown">
                    <a target="_blank" href="<?=Url::to(['/report/report/schedule', 'id'=>$model->id])?>">点击查看投放地区列表</a>
                </div>
               <!-- 投放地区：--><?/*= Html::encode($model->area_name)*/?>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td><h4><strong>广告内容</strong></h4></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="4">
                <?php if(!empty($model->video_id)): ?>
                    <video width="600" height="400" controls class="video">
                        <source src="<?=Html::encode($model->video_trans_url)?>" type="video/mp4" />
                    </video>
                    <form id="form1" style="margin-bottom: 30px;">
                        <input id="uploadVideoNow-file" type="file" style="display:none;"/>
                    </form >
                    <input class="btn btn-success" type="button" id="uploadVideoNow" value="上传" style="margin:0 30px 0 30px;"/>
                    <!--<a href="<?/*=Html::encode($model->video_trans_url)*/?>" onclick="if(confirm('确定下载此视频?')==false)return false;" download="" class="btn btn-success">下载</a>-->
                    <a href="<?=\yii\helpers\Url::to(['confirm'])?>&url=<?php echo $model->video_trans_url?>&filename=广告内容.mp4" onclick="if(confirm('确定下载此视频?')==false)return false;" class="btn btn-success">下载</a>
                       <!-- --><?/*=Html::button('确定',['class'=>'btn cancel'])*/?>
                    <div class="row" id="resultBox" style="padding:0 0 10px 27px;"></div>
                <?php else: ?>
                    <?php /*if($model->advert_key=='CD'):*/?><!--
                        <?php /*foreach (explode(',',$model->video_trans_url) as $vcd):*/?>
                            <?/*=Html::tag('img','',['src'=>$vcd])*/?>
                        <?php /*endforeach;*/?>
                    <?php /*else:*/?>
                        <?/*=Html::tag('img','',['src'=>$model->video_trans_url])*/?>
                    --><?php /*endif;*/?>
                    <?=Html::tag('img','',['src'=>$model->resource])?>
                <?php endif ;?>
            </td>
        </tr>
        <tr>
            <td><h4><strong>知识产权</strong></h4></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="5">
                <?php foreach(OrderCopyright::getImgUrl($model->id) as $k=>$v):?>
                    <?=Html::tag('img','',['src'=>$v['image_url'],'height'=>'150px','width'=>'auto'])?>
                <?php endforeach;?>
            </td>
        </tr>
        <tr>
            <td><h4><strong>审核信息</strong></h4></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <?foreach ($rejectAll as $v):?>
            <tr>
                <td colspan="5">
                    时间：<?=Html::encode($v['create_at'])?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    操作人：<?=Html::encode($v['create_user_name'])?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?if($v['examine_result']==2):?>
                        结果：<?=Html::encode($v['examine_desc'])?>
                    <?elseif ($v['examine_result']==1):?>
                        结果：已通过审核
                    <?endif;?>
                </td>
            </tr>
        <?endforeach;?>
        <th>
            <div class="row text-center" foreign_id="<?=Html::encode($model->id)?>">
                <?php if($model->examine_status=='1'): ?>
                <button type="button" class="btn btn-primary ck" data-type="pass">通过</button>
                <button type="button" class="btn btn-danger ck" data-type="bohui" id="tijiao">驳回</button>
                <?php endif;?>
                <button type="button" class="btn btn-primary fh">返回</button>
                <!--<a href="<?/*=\yii\helpers\Url::to(['/examine/order/index'])*/?>" class="btn btn-primary ck">返回</a>-->
            </div>
        </th>
    </table>
    <input type="hidden" value="<?=Html::encode($model->id)?>" name="id">
</div>
<style>
    video::-internal-media-controls-download-button {
        display:none;
    }
    video::-webkit-media-controls-enclosure {
        overflow:hidden;
    }
    video::-webkit-media-controls-panel {
        width: calc(100% + 30px);
    }
    img{padding: 0px 0px 40px 40px; }
    #CuPlayer div.CuPc{ width:50%; height:450px;margin:0 auto; }
    /*移动端宽高设定*/
    #CuPlayer div.CuMob{ width:50%; height:400px;margin:0 auto;position: relative; }
    #CuPlayer div.CuPad{ width:50%; height:450px;margin:0 auto; }
    #CuPlayer .video-js{ width:100%; height:100%;}

</style>
<script src="http://libs.baidu.com/jquery/2.1.4/jquery.min.js"></script>
<script src="//imgcache.qq.com/open/qcloud/video/tcplayer/tcplayer.min.js"></script>
<script src="//imgcache.qq.com/open/qcloud/js/vod/sdk/ugcUploader.js"></script>
<script src="/static/js/viewer-jquery.min.js"></script>
<script type="text/javascript">
    $('.member-view').viewer({
        url: 'src',
    });
    $(function(){
        $('.fh').bind('click',function(){
            history.go(-1);
        })
        $('.ck').bind('click',function(){
            var type = $(this).attr('data-type');
            var foreign_id = $(this).parent('.row').attr('foreign_id');
            if(type=='bohui'){
                pg = layer.open({
                    type: 1,
                    skin: 'layui-layer-rim', //加上边框
                    area: ['450px', '320px'], //宽高
                    shadeClose: true,
                    content: '<div class="row scroll form-horizontal"  style="margin-top: 15px;"><label class="col-sm-3 control-label" for="formGroupInputLarge">驳回原因</label><div class="col-sm-5"><select name="reason" id="" class="form-control"><option value="1">视频长度不合适</option><option value="2">投放日期不符</option><option value="3">广告内容不符合标准</option><option value="4">缺少相关内容产权资料</option><option value="5">其他</option></select></div></div><div class="row scroll form-horizontal txa" style="margin-top: 15px;"><label for="" class="col-sm-3 control-label">其他原因：</label><div class="col-sm-6"><textarea class="form-control" rows="3"></textarea></div></div><div class="row scroll text-center" style="margin-top: 60px;"><button type="button" class="btn btn-primary confirm" data-type="rebut">驳回</button></div>'
                });
                $('.confirm').bind('click',function () {
                    var desc = $('[name="reason"]').val();
                    if(desc == 5){
                        var descc = $('.txa textarea').val();
                        if(!descc){
                            layer.msg('请填写驳回原因！');
                            return false;
                        }
                    }
                    $.ajax({
                        url : '<?=\yii\helpers\Url::to(['dismissaladd'])?>',
                        type : 'GET',
                        dataType : 'json',
                        data : {'type':type, 'foreign_id': foreign_id, 'desc':desc,'descc':descc},
                        success:function (phpdata) {
                            if(phpdata == 1){
                                layer.msg('驳回成功！',{icon:1});
                                layer.closeAll('page');
                                history.back();
                            }else if(phpdata == 0){
                                layer.msg('驳回失败！');
                                layer.closeAll('page');
                                history.back();
                            }
                        },
                        error:function () {
                            layer.msg('操作失败！');
                        }
                    });
                })
            }
            else if(type=='pass'){
                layer.confirm('您确定要通过审核吗？', {
                    btn: ['通过','取消'] //按钮
                }, function(){
                    $.ajax({
                        url:'<?=\yii\helpers\Url::to(['adopt'])?>',
                        type: 'post',
                        dataType : 'json',
                        data : {'type':type, 'order_id': foreign_id},
                        success:function(phpdata){
                            if(phpdata==true){
                                layer.msg('审核通过！',{icon:1});
                                layer.closeAll('page');
                                parent.location.reload();
                            }
                            else if(phpdata==false){
                                layer.msg('审核失败！',{icon:2});
                                layer.closeAll('page');
                            }
                        },
                        error:function(){
                            layer.msg('操作失败！',{icon:2});
                        }
                    });
                }, function(){

                });

            }
        })
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

        /**
         * 添加上传信息模块
         */

        var addUploaderMsgBox = function(type){
            var html = '<div style="margin-top:30px;" class="uploaderMsgBox" name="box'+index+'">';
            if(!type || type == 'hasVideo') {
                html += /*'视频名称：<span name="videoname'+index+'"></span>；' +*/
                    '计算sha进度：<span name="videosha'+index+'">0%</span>；' +
                    '上传进度：<span name="videocurr'+index+'">0%</span>；' +
                    'fileId：<span name="videofileId'+index+'">   </span>；' +
                    '上传结果：<span name="videoresult'+index+'">   </span>；<br>' +
                    /*'地址：<span name="videourl'+index+'">   </span>；'+*/
                    '<a href="javascript:void(0);" name="cancel'+index+'" cosnum='+index+' act="cancel-upload">取消上传</a><br>';
            }

            if(!type || type == 'hasCover') {
                html += '封面名称：<span name="covername'+index+'"></span>；' +
                    '计算sha进度：<span name="coversha'+index+'">0%</span>；' +
                    '上传进度：<span name="covercurr'+index+'">0%</span>；' +
                    '上传结果：<span name="coverresult'+index+'">   </span>；<br>' +
                    '地址：<span name="coverurl'+index+'">   </span>；<br>' +
                    '</div>'
            }
            html += '</div>';

            $('#resultBox').append(html);
            return index++;
        };
        /**
         * 示例1：直接上传视频
         **/
        $('#uploadVideoNow-file').on('change', function (e) {
            var id = $('input[name="id"]').val();
            var num = addUploaderMsgBox('hasVideo');
            var videoFile = this.files[0];
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
                    $('[name=videofileId'+num+']').text(result.fileId);
                    $('[name=videourl'+num+']').text(result.videoUrl);
                    $("#url").attr("value",result.fileId);
                    if(result.message) {
                        $('[name=videofileId'+num+']').text(result.message);
                    }
                    if(result.fileId){
                        $.ajax({
                            url:'<?=\yii\helpers\Url::to(['videoid'])?>',
                            type: 'get',
                            dataType : 'json',
                            data : {'id':id, 'video_id': result.fileId,'resource':result.videoUrl},
                            success:function(phpdata){
                                if(phpdata.code==1){
                                    window.location.reload();
                                }else{
                                    layer.msg('视频信息修改失败，请重新上传',{icon:2});
                                }
                            },
                            error:function(){
                                layer.msg('操作失败！',{icon:2});
                            }
                        });
                    }
                }
            });
            if(resultMsg){
                $('[name=box'+num+']').text(resultMsg);
            }
            $('#form1')[0].reset();
        });
        $('#uploadVideoNow').on('click', function () {
            $('#uploadVideoNow-file').click();
        });
    })

</script>




