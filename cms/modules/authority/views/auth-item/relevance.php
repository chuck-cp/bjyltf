<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
\cms\assets\AppAsset::register($this);
$this->registerCss('
    .summary{visibility:hidden;}
    #w0{padding:0 10px;}
');

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\authority\models\search\AuthRuleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = '关联权限';
//$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<head>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody(); ?>
<div class="auth-rule-relevance">
    <?php $form = ActiveForm::begin([
        'action' => ['add-item-rule'],
        'method' => 'post',
    ]); ?>
    <input type="hidden" name="item-name" value="<?=Html::encode($ruleid['ruleid'])?>"/>
    <div class="sy_dp_paenl">
         <? foreach($dataProvider as $key=>$value):?>
         <div class="sy_dp_list">
             <p><span class="sy_zk zkbox">></span><input type="checkbox" class="sy_dp_inp" name="one[]" value="<?=Html::encode($value['name']) ?>"><?=Html::encode($value['data']) ?></p>
             <ul style="display: flex;">
                 <? foreach($value['list'] as $keyl=>$valuel):?>
                 <li style="width: 25%;"><span class="sy_zk zkbox">></span><input type="checkbox" class="sy_second" name="two[]" value="<?=Html::encode($valuel['name']) ?>"><?=Html::encode($valuel['data']) ?>
                 <ul class="sy_threebox">
                     <? foreach($valuel['list'] as $keyt=>$valuet):?>
                     <li><input type="checkbox" name="three[]" class="sy_three"  value="<?=Html::encode($valuet['name']) ?>" <?if(in_array($valuet['name'],$checkRule)):?>checked="true"<?endif;?> /><?=Html::encode($valuet['data']) ?></li>
                     <? endforeach ?>
                 </ul>
                 </li>
                <? endforeach ?>
             </ul>
         </div>
         <? endforeach ?>
    </div>
    <tr style="text-align: center;">
        <td colspan=""><?= Html::submitButton('提交',['class'=>'btn btn-primary'])?></td>
    </tr>
    <?php ActiveForm::end(); ?>
</div>
<?php $this->endBody() ?>
</body>
</html >
<?php $this->endPage() ?>
<style>
 .sy_dp_list p em{ background: #c03 }   
</style>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
//    $(function(){
//        var initselect=true;
//        $('.sy_threebox').each(function(){
//            console.log(111);
//            $(this).find('.sy_three').each(function(){
//                if($(this).prop('checked')==false){
//                    initselect=false
//                    return
//                }
//            })
//            console.log(initselect)
//          if(initselect){
//                $(this).parents('.sy_dp_list').find('.sy_second').prop('checked',true);
//            }
//        })
//    })
$('.sy_dp_inp').on('click',function(){
    if($(this).prop('checked')==true){
        $(this).parent().siblings('ul').find('input').prop('checked',true);

    }else{
       $(this).parent().siblings('ul').find('input').prop('checked',false);
        $('.sy_dp_inp').prop('checked',false)
    }
})
//二级选中
$('.sy_second').on('click',function(){
    if($(this).prop('checked')==true){
        $(this).siblings('ul').find('input').prop('checked',true);
        var djselect=true
        $(this).parents('.sy_dp_list').find('.sy_second').each(function(){
            if($(this).prop('checked')==false){
                djselect=false;
            }
        })
        if(djselect){$(this).parents('.sy_dp_list').find('.sy_dp_inp').prop('checked',true)
        }

    }else{
        $(this).parents('.sy_dp_list').find('.sy_dp_inp').prop('checked',false)
        $(this).siblings('ul').find('input').prop('checked',false)
    }
})
//三级选中判断
$('.sy_three').on('click',function(){
    if($(this).prop('checked')==true){
        var isselect=true;
        var allselect=true;
        $(this).parents('.sy_threebox').find('li').each(function(){
            if($(this).find('input').prop('checked')==false){
                 isselect=false;
                return;
            }
        })
        $(this).parents('.sy_dp_list').find('.sy_three').each(function(){
            if($(this).prop('checked')==false){
                allselect=false;
                return;
            }
        })
        if(allselect){
            $(this).parents('.sy_dp_list').find('.sy_dp_inp').prop('checked',true)
        }
        if(isselect){
            $(this).parents('.sy_threebox').siblings('.sy_second').prop('checked',true)
        }
    }else{
        $(this).parents('.sy_threebox').siblings('.sy_second').prop('checked',false)
        $(this).parents('.sy_dp_list').find('.sy_dp_inp').prop('checked',false)

    }
})
$('.sy_zk').click(function(){
    if($(this).hasClass('zkbox')){
      $(this).parent().siblings('ul').hide();
      $(this).removeClass('zkbox');
    }else{
       $(this).parent().siblings('ul').show();
      $(this).addClass('zkbox');
    }
})
</script>