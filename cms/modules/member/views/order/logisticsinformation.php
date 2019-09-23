<?php

use cms\modules\examine\models\ShopLogistics;
\cms\assets\AppAsset::register($this);
$this->registerCss('
    .summary{visibility:hidden;}
    #w0{padding:0 10px;}
');

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
<div class="container">
    <h5>物流单号</h5>
<!--    <div class="line"></div>-->

        <table class="table table-bordered">
            <tr>
                <td>
                    <form>
                        物流单号：<input type="text" class="danhao" name="tracking_number" value="<?php echo $model['tracking_number']?>" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        物流公司：
                        <select class="gongsi" name="logistics_name">
                            <?php foreach (ShopLogistics::getLogistList('all') as $k=>$v):?>
                                <option <?php if($model['logistics_name']==$k):?>selected="selected"<?php endif;?> value="<?php echo $k?>"> <?php echo $v?></option>
                            <?php endforeach;?>
                        </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a class="btn btn-success material" width="2%" jdh="<?php echo $model['tracking_number']?>">提交</a>
                    </form>
                </td>
            </tr>
        </table>
    <div style="text-align: center; display: none;" id="qrwl">
        <a class="btn btn-primary confirm " id="<?php echo $model['id']?>" >确认物流信息</a>
    </div>
    <?php if($model->logistics_name && $model->order_num):?>
        <h5>物流信息：</h5>
        <table class="table table-bordered">

            <?php if($wlinformation):?>
                <tr>
                    <td>时间</td>
                    <td>状态</td>
                    <td>地址</td>
                </tr>
                <?php foreach ($wlinformation as $v):?>
                    <tr>
                        <td><?php echo $v['time']?></td>
                        <td><?php echo $v['context']?></td>
                        <td><?php echo $v['location']?></td>
                    </tr>
                <?php endforeach;?>
            <?php else:?>
                暂无快递信息
            <?php endif;?>

        </table>
    <?php endif;?>

</div>
<?php $this->endBody() ?>
</body>
</html >
<?php $this->endPage() ?>
<style type="text/css">
    h5{font-weight: bold;}
    .material{margin-left: 150px;}
   .danhao{width:25%;height:35px;border-radius:5px;border:1px solid #ddd;}
   .gongsi{width:12%;height:35px;border-radius:5px;border:1px solid #ddd;}
</style>
<script src="/static/js/common.js"></script>
<script type="text/javascript">
    $(function(){
        //点击修改
        $(".material").click(function(){
            var tracking_number= $('input[name="tracking_number"]').val();
            var jdh=$(this).attr('jdh');
            if(tracking_number==jdh){
                layer.msg('请填写新的物流单号',{icon:2});
                return false;
            }
            if(!tracking_number){
                layer.msg('请填写物流单号',{icon:2});
                return false;
            }
            $("#qrwl").show();
        })
        //点击确认物流信息
        $(".confirm").click(function(){
            var id=$(this).attr('id');
            var tracking_number= $('input[name="tracking_number"]').val();
            var logistics_name= $('select[name="logistics_name"]').val();
            $.ajax({
                url : '<?=\yii\helpers\Url::to(['confirmwl'])?>',
                type : 'POST',
                dataType : 'json',
                data : {'id':id,'tracking_number':tracking_number,'logistics_name':logistics_name},
                success:function (phpdata) {
                    if(phpdata.code == 1){
                        layer.msg(phpdata.msg,{icon:1});
                        setTimeout(function(){
                            window.parent.location.reload();
                        },2000);
                    }else{
                        layer.msg(phpdata.msg,{icon:2});
                    }
                },
                error:function () {
                    layer.msg('操作失败！');
                }
            });

        })


    })
</script>
