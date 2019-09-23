<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
cms\assets\AppAsset::register($this);
$this->registerJsFile('/static/js/tcplayer/videojs-ie8.js');
$this->registerCssFile('/static/css/tcplayer/tcplayer.css');
$this->registerCss('
    .summary{visibility:hidden;}
    #w0{padding:0 10px;}
');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<head>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody(); ?>
<div class="system-startup-form aa" >
    <?php $form = ActiveForm::begin([
        'options'=>['enctype'=>'multipart/form-data','class' => 'form-horizontal'],
    ]); ?>
    <input type="hidden" name="filename" value="back-stage">
        <label class="col-sm-2 control-label">资料名称：</label>
        <div class="col-sm-3 upload" style="width:80%;">
        <?= $form->field($model,'id')->hiddenInput(['id'=>$model->id,'readonly'=>false])->label(false)?>
        <?= $form->field($model,'name')->textInput()->label(false);?>
        </div>
        <label class="col-sm-2 control-label">缩略图：</label>
        <div class="col-sm-3 upload" style="width:80%;">
        <?= $form->field($model,'thumbnail')->widget('yidashi\uploader\SingleWidget')->label(false); ?>
        </div>
        <label class="col-sm-2 control-label">视频：</label>
        <div class="col-sm-3 upload" style="width:80%;">
            <input class="btn btn-success" type="button" id="uploadVideoNow" value="选择视频资料" style="margin-left:-12px;"/>
            <div class="row" id="resultBox"  ></div>
            <form id="form1" style="margin-bottom: 30px;">
                <input id="uploadVideoNow-file" type="file" style="display:none;"/>
            </form >
            <input type="hidden" value="" id="resource" name="content">
            <input type="hidden" name="type" value="2">
        </div>
        <label class="col-sm-2 control-label"></label>
        <div style="margin-left:-12px;margin-top: 15px;" id="video">

            <video  controls  width="480" height="260" ><source src=<?php echo $model->content;?> type="video/mp4" />  </video>
        </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <?= Html::Button('提交', ['class' =>  'btn btn-primary submit']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<?php $this->endBody() ?>
</body>
</html >
<?php $this->endPage() ?>
<style type="text/css">
    .aa {margin-left: 10px;width: 85%}
</style>
<script src="//imgcache.qq.com/open/qcloud/video/tcplayer/tcplayer.min.js"></script>
<script src="//imgcache.qq.com/open/qcloud/js/vod/sdk/ugcUploader.js"></script>
<script type="text/javascript">
    $(function(){
        $(".submit").click(function(){
            var data=$('#w0').serialize();
           // alert(data);
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['update'])?>',
                type : 'POST',
                dataType : 'json',
                data : data,
                success:function (data) {
                    if(data.code==1){
                        layer.msg(data.msg,{icon:1});
                        setTimeout(function(){
                            parent.location.reload();
                        },2000);
                    }else{
                        layer.msg(data.msg,{icon:2});
                    }
                },error:function (error) {
                    layer.msg('操作失败！',{icon:7});
                }
            });
        })
        var index = 0;
        var cosBox = [];
        var getSignature = function(callback){
            $.ajax({
                url: "<?php echo \yii\helpers\Url::to(['/config/system-train/qm']); ?>",
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
                    'fileId：<span id="fieldid" name="videofileId'+index+'">   </span>；' +
                    '上传结果：<span name="videoresult'+index+'">   </span>；<br>' /*+*/
                    /*'地址：<span name="videourl'+index+'">   </span>；'+*/
                    /*'<a href="javascript:void(0);" name="cancel'+index+'" cosnum='+index+' act="cancel-upload">取消上传</a><br>'*/;
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
                    $("#resource").attr("value",result.videoUrl);
                    if(result.message) {
                        $('[name=videofileId'+num+']').text(result.message);
                    }
                    if(result.fileId){
                        $("#video").html('<video controls  width="480" height="260" ><source src='+result.videoUrl+' type="video/mp4" />  </video>');
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

