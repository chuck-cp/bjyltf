<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
\cms\assets\AppAsset::register($this);
$this->beginBlock('AppPage');
$this->registerJs('jQuery(document).ready(function() { App.setPage("elements");  App.init(); });');
$this->endBlock();
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <head>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody(); ?>
    <table class="table">
        <th></th>
        <th>名称</th>
        <th>banner数量</th>
        <th>操作</th>
        <tr>
            <td>
                <?if(isset($bannerInfo[0]['num']) && $bannerInfo[0]['num'] >5):?>
                <fieldset disabled>
                    <input type="checkbox" name="type" value="1" onclick="return false;">
                </fieldset>
                <?else:?>
                    <input type="checkbox" name="type" value="1">
                <?endif;?>
            </td>
            <td>首页banner</td>
            <td>
                <?if(isset($bannerInfo[0]['num'])):?>
                    <?=Html::encode($bannerInfo[0]['num'])?>
                <?else:?>
                    0
                <?endif;?>
            </td>
            <td>
                <a href="javascript:void(0);" id="fir">查看</a>
            </td>
        </tr>
        <tr>
            <td>
                <?if(isset($bannerInfo[1]['num']) && $bannerInfo[1]['num'] >5):?>
                    <fieldset disabled>
                        <input type="checkbox" name="type" value="2" onclick="return false;">
                    </fieldset>
                <?else:?>
                    <input type="checkbox" name="type" value="2">
                <?endif;?>
            </td>
            <td>广告页banner</td>
            <td>
                <?if(isset($bannerInfo[1]['num'])):?>
                    <?=Html::encode($bannerInfo[1]['num'])?>
                <?else:?>
                    0
                <?endif;?>
            </td>
            <td><a href="javascript:void(0);" id="sec">查看</a></td>
        </tr>
    </table>
    <div class="row" style="text-align: center;">
        <?=Html::button('确定',['class'=>'btn btn-primary confirm'])?>
        <?=Html::button('取消',['class'=>'btn cancel'])?>
    </div>
    <?php $this->endBody() ?>

    </body>
    </html >
<?php $this->endPage() ?>
<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
<script type="text/javascript">
    $("#fir").click(function () {
        parent.window.open('<?=\yii\helpers\Url::to(["banner/index"])?>');
    })
    $("#sec").click(function () {
        parent.window.open('<?=\yii\helpers\Url::to(["banner/index",'type'=>2])?>');
    })
    $(".cancel").click(function () {
        var pg = parent.layer.getFrameIndex(window.name);
        parent.layer.close(pg);
        $(window.parent.document).find('#sp input').remove();
        $('input[type="radio"]', window.parent.document).attr('checked',true);
    })
    $('.confirm').click(function () {
        var jump = false;
        var trr = [];
        $('input[type="checkbox"]').each(function () {
            if($(this).is(':checked')){
                var vl = $(this).val();
                if(!isInArray(vl,trr)){
                    trr.push(vl);
                }
                jump = true;
            }
        })
        if(!jump){
            layer.msg('请选择推送目标！');
            return false;
        }
        for (var i=0; i<trr.length; i++){
            var doc = $(window.parent.document).find('#sp input[value='+trr[i]+'][type="hidden"]').length;
            if(doc < 1){
                $(window.parent.document).find('body #sp').append('<input type="hidden" name="type[]" value="'+trr[i]+'">');
            }
        }
        var pg = parent.layer.getFrameIndex(window.name);
        parent.layer.close(pg);
    })
    function isInArray(arr,value){
        var index = $.inArray(value,arr);
        if(index >= 0){
            return true;
        }
        return false;
    }
</script>    

