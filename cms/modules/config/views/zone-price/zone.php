<?php

use yii\helpers\Html;
use common\libs\ToolsClass;
use yii\bootstrap\ActiveForm;
use cms\modules\config\models\SystemAddressLevel;
$this->title = '区域价格设置';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCss('
    .form-control-select {
        width: 100px;
        height: 34px;
        padding: 6px 12px;
        font-size: 14px;
        color: #555555;
        vertical-align: middle;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    }
    .yw{
        line-height: 35px;
        font-size: 14px;
        font-weight: 700;
        width: 160px;
    }
    .left{
        float: right;
        margin: 0px 20px;
    }
')
?>
<div class="system-zone-list-index">

    <?php $from=ActiveForm::begin([
//        'action'=>[],
        'method'=>'post',
    ])?>
    <div class="row">
        <div class="col-md-2 yw">
            每月补助发放日期：
        </div>
        <select id="" class="form-control-select" name="subsidy_date">
            <option value="1"  <?if($subdate['subsidy_date'] == 1 ):?>selected<?endif;?>>1 </option>
            <option value="2"  <?if($subdate['subsidy_date'] == 2 ):?>selected<?endif;?>>2 </option>
            <option value="3"  <?if($subdate['subsidy_date'] == 3 ):?>selected<?endif;?>>3 </option>
            <option value="4"  <?if($subdate['subsidy_date'] == 4 ):?>selected<?endif;?>>4 </option>
            <option value="5"  <?if($subdate['subsidy_date'] == 5 ):?>selected<?endif;?>>5 </option>
            <option value="6"  <?if($subdate['subsidy_date'] == 6 ):?>selected<?endif;?>>6 </option>
            <option value="7"  <?if($subdate['subsidy_date'] == 7 ):?>selected<?endif;?>>7 </option>
            <option value="8"  <?if($subdate['subsidy_date'] == 8 ):?>selected<?endif;?>>8 </option>
            <option value="9"  <?if($subdate['subsidy_date'] == 9 ):?>selected<?endif;?>>9 </option>
            <option value="10" <?if($subdate['subsidy_date'] == 10):?>selected<?endif;?>>10</option>
            <option value="11" <?if($subdate['subsidy_date'] == 11):?>selected<?endif;?>>11</option>
            <option value="12" <?if($subdate['subsidy_date'] == 12):?>selected<?endif;?>>12</option>
            <option value="13" <?if($subdate['subsidy_date'] == 13):?>selected<?endif;?>>13</option>
            <option value="14" <?if($subdate['subsidy_date'] == 14):?>selected<?endif;?>>14</option>
            <option value="15" <?if($subdate['subsidy_date'] == 15):?>selected<?endif;?>>15</option>
            <option value="16" <?if($subdate['subsidy_date'] == 16):?>selected<?endif;?>>16</option>
            <option value="17" <?if($subdate['subsidy_date'] == 17):?>selected<?endif;?>>17</option>
            <option value="18" <?if($subdate['subsidy_date'] == 18):?>selected<?endif;?>>18</option>
            <option value="19" <?if($subdate['subsidy_date'] == 19):?>selected<?endif;?>>19</option>
            <option value="20" <?if($subdate['subsidy_date'] == 20):?>selected<?endif;?>>20</option>
            <option value="21" <?if($subdate['subsidy_date'] == 21):?>selected<?endif;?>>21</option>
            <option value="22" <?if($subdate['subsidy_date'] == 22):?>selected<?endif;?>>22</option>
            <option value="23" <?if($subdate['subsidy_date'] == 23):?>selected<?endif;?>>23</option>
            <option value="24" <?if($subdate['subsidy_date'] == 24):?>selected<?endif;?>>24</option>
            <option value="25" <?if($subdate['subsidy_date'] == 25):?>selected<?endif;?>>25</option>
            <option value="26" <?if($subdate['subsidy_date'] == 26):?>selected<?endif;?>>26</option>
            <option value="27" <?if($subdate['subsidy_date'] == 27):?>selected<?endif;?>>27</option>
            <option value="28" <?if($subdate['subsidy_date'] == 28):?>selected<?endif;?>>28</option>
        </select>
        <?= Html::submitButton('保存',['class'=>'btn btn-primary'])?>
    </div>
    <?php ActiveForm::end(); ?>
    <table class="table table-striped table-bordered" style="margin-top: 30px;">
        <thead>
            <tr>
                <th>等级</th>
                <th>区域价格(元)</th>
                <th>每月补助(元)</th>
                <th>区域名称</th>
                <th class="action-column">操作</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($RegionalpriceAll as $k=>$v):?>
            <tr data-key="4">
                <td width="6%"><?php echo SystemAddressLevel::getNameByLevel($v['id'])?></td>
                <td width="6%"><?php echo ToolsClass::priceConvert($v['regionalprice'])?></td>
                <td width="6%"><?php echo ToolsClass::priceConvert($v['subsidyprice'])?></td>
                <td><?php echo SystemAddressLevel::getAreaBylevl($v['id'])?> </td>
                <td width="5%"><a href="/index.php?r=config%2Fzone-price%2Fview&amp;id=<?php echo $v['id']?>&amp;regionalprice=<?php echo ToolsClass::priceConvert($v['regionalprice'])?>&amp;subsidyprice=<?php echo ToolsClass::priceConvert($v['subsidyprice'])?>">查看详情</a> <!--<a class="delprice" href="javascript:void(0);" data_id="4">删除</a></td>-->
            </tr>
        <?php endforeach;?>
        </tbody>

    </table>
</div>
<script src="/static/js/jquery/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
    //取消操作返回列表
    $('.delprice').on('click', function () {
        var priceid = $(this).attr('data_id');
        layer.confirm('您确定需要删除该项设置？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                url:'<?=\yii\helpers\Url::to(['delprice'])?>',
                type : 'GET',
                dataType : 'json',
                data : {'priceid':priceid},
                success:function (resdata) {
                    if(resdata ==1){
                        layer.msg('删除成功');
                    }else{
                        layer.msg('删除失败');
                    }
                    setTimeout(function(){
                        window.parent.location.reload();
                    },2000);
                },
                error:function (error) {
                    layer.msg('操作失败！');
                }
            })
        }, function(){
            layer.msg('您已取消');
        });
    })
</script>