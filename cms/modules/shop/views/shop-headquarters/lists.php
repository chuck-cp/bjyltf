<?php

use yii\helpers\Html;
use cms\modules\shop\models\Shop;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $searchModel cms\modules\examine\models\search\ShopHeadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
//$this->registerCss();
//$this->registerCssFile()

?>
<!--<link href="https://cdn.bootcss.com/bootstrap/4.1.1/css/bootstrap-grid.css" rel="stylesheet">-->
<div class="shop-headquarters-index">
    <h4><b>分店信息</b></h4>
    <table class="table table-hover">
        <th style="text-align: center;">店铺编号</th>
        <th style="text-align: center;">商家名称</th>
        <th style="text-align: center;">所属地区</th>
        <th style="text-align: center;">详细地址</th>
        <th style="text-align: center;">申请时间</th>
        <th style="text-align: center;">状态</th>
        <th style="text-align: center;">操作</th>
        <?foreach($arraylist as $key=>$value):?>
            <tr style="text-align: center;">
                <td><?=Html::encode($value['shop']['id'] == ''? '---':$value['shop']['id'])?></td>
                <td><?=Html::encode($value['branch_shop_name'])?></td>
                <td><?=Html::encode($value['branch_shop_area_name'])?></td>
                <td><?=Html::encode($value['branch_shop_address'])?></td>
                <td><?=Html::encode($value['shop']['create_at'] == ''? '---':$value['shop']['create_at'])?></td>
                <td><?=Html::encode($value['shop']['status'] == ''? '---':Shop::getStatusByNum($value['shop']['status']))?></td>
                <td>
                    <?if($value['shop']['id'] != ''):?>
                        <?=Html::a('查看',['/shop/shop-headquarters/listview','id'=>$value['shop']['id']],['target'=>'_blank'])?>
                    <?else:?>
                        <?=Html::encode('---')?>
                    <?endif;?>
                </td>
            </tr>
        <?endforeach?>
        <tr style="text-align: center;">
            <td colspan="6">
                <?= LinkPager::widget([
                    'pagination' => $pages,
                    'nextPageLabel' => '下一页',
                    'prevPageLabel' => '上一页',
                    'firstPageLabel' => '首页',
                    'lastPageLabel' => '尾页',
                ]); ?>
            </td>
        </tr>
    </table>
</div>
