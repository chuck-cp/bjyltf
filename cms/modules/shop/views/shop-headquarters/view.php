<?php

use yii\helpers\Html;
use cms\modules\examine\models\ShopHeadquarters;
use cms\modules\member\models\Member;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '总部信息', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-headquarters-view">
    <?$member = Member::findOne(['id'=>$model->member_id]);
    $parent = Member::findOne(['id' => $member->parent_id]);?>
    <table class="table table-hover">
        <h4><b>总部信息</b></h4>
        <tr>
            <td>总部编号：</td>
            <td><?=Html::encode($model->id)?></td>
            <td>公司名称：</td>
            <td><?=Html::encode($model->company_name)?></td>
        </tr>
        <tr>
            <td>法人代表：</td>
            <td><?=Html::encode($model->name)?></td>
            <td>所属地区：</td>
            <td><?=Html::encode(html_entity_decode($model->company_area_name))?></td>
        </tr>
        <tr>
            <td>身份证号：</td>
            <td><?=Html::encode($model->identity_card_num)?></td>
            <td>详细地址：</td>
            <td><?=Html::encode($model->company_address)?></td>
        </tr>
        <tr>
            <td>联系电话：</td>
            <td><?=Html::encode($model->mobile)?></td>
            <td>统一社会信用代码：</td>
            <td><?=Html::encode($model->registration_mark)?></td>
        </tr>
        <tr>
            <td>业务员：</td>
            <td><?=Html::encode($model->member_name?$model->member_name:'---')?></td>
            <td>业务员电话：</td>
            <td><?=Html::encode($model->member_mobile?$model->member_mobile:'---')?></td>
        </tr>
        <tr>
            <td>上级业务员：</td>
            <td><?=Html::encode($parent?$parent->name:'---')?></td>
            <td>上级业务员电话：</td>
            <td><?=Html::encode($parent?$parent->mobile:'---')?></td>
        </tr>
        <tr>
            <td>申请时间：</td>
            <td><?=Html::encode($model->create_at)?></td>
            <td>状态：</td>
            <td>
                <?=Html::encode(ShopHeadquarters::getStatusByNum($model->examine_status))?>
            </td>
        </tr>
        <tr>
            <? if($model->examine_status==1):?>
                <td>协议：</td>
                <td>
                    <a class="media" href="https://i1.bjyltf.com/agreement/<?=Html::encode($model->agreement_name)?>" target="_blank">预览</a>
                    &nbsp;&nbsp;&nbsp;
                    <a href="<?=\yii\helpers\Url::to(['/examine/order/confirm'])?>&url=https://i1.bjyltf.com/agreement/<?=Html::encode($model->agreement_name)?>&filename=视频播放安装协议.pdf"><i>下载</i></a>
                </td>
            <? else: ?>
                <td></td>
                <td></td>
            <? endif; ?>
            <td></td>
            <td></td>
        </tr>
        <tr><th colspan="4" style="font-size: 18px;"><b>证件信息</b></th></tr>
        <tr>
            <td>
                <?php if($model->identity_card_front):?>
                    <img width="150" height="auto" src="<?=Html::encode($model->identity_card_front)?>" title="身份证正面照" alt="">
                <?php endif;?>
            </td>
            <td>
                <?php if($model->identity_card_back):?>
                    <img width="150" height="auto" src="<?=Html::encode($model->identity_card_back)?>" title="身份证背面照" alt="">
                <?php endif;?>
            </td>
            <td></td>
            <td></td>
        </tr>
        <tr><th colspan="4" style="font-size: 18px;"><b>营业执照</b></th></tr>
        <tr>
            <td>
                <?php if($model->business_licence):?>
                    <img width="150" height="auto" src="<?=Html::encode($model->business_licence)?>" title="营业执照" alt="">
                <?php endif;?>
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr><th colspan="4" style="font-size: 18px;"><b>其他图片</b></th></tr>
        <tr>
            <td colspan="4">
                <?if($model->other_image):?>
                    <?foreach (explode(',',$model->other_image) as $vv):?>
                        <img style="margin: 0 5% 0 0" width="150" height="auto" src="<?=Html::encode($vv)?>" title="其他图片" alt="">
                    <?endforeach;?>
                <?endif;?>
            </td>
        </tr>
    </table>
    <?php  echo $this->render('lists',['id'=>$model->id,'arraylist'=>$arraylist,'pages'=>$pages,'lastpage'=>$lastpage]); ?>
    <table class="table table-hover">
        <?if(!empty($desc)):?>
            <tr><th colspan="4" style="font-size: 18px;"><b>审核信息</b></th></tr>
            <?php foreach ($desc as $v):?>
                <tr>
                    <td colspan="4">
                        日期：<?=Html::encode($v['create_at'])?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        操作人：<?=Html::encode($v['create_user_name'])?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <? if($v['examine_result']==2):?>
                            结果：<?=Html::encode($v['examine_desc'])?>
                        <? elseif ($v['examine_result']==1):?>
                            结果：已通过审核
                        <? endif;?>
                    </td>
                </tr>
            <?php endforeach;?>
        <?endif;?>
    </table>
    <div class="row text-center" style="margin-top: 50px;" shop_id="<?=Html::encode($model->id)?>">
        <button type="button" class="btn btn-primary firm" data-type="close">确定</button>
    </div>
</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script src="/static/js/viewer-jquery.min.js"></script>
<script type="text/javascript">
    $('.shop-headquarters-view').viewer({
        url: 'src',
    });
    $('.firm').bind('click',function () {
        history.back();
    });
</script>