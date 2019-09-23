<?php
/**
 * 系统公告数据类
 */

namespace common\libs;


class DataClass {
    //广告位对应的时间启使位置
    public static function advertPositionStartTime(){
        return [
            'A1'=>[0,360,720,1080,1440,1800,2160,2520,2880,3240],
            'A2'=>[300,660,1020,1380,1740,2100,2460,2820,3180,3540],
            'B'=>[0,360,720,1080,1440,1800,2160,2520,2880,3240],
            'C'=>[0,360,720,1080,1440,1800,2160,2520,2880,3240],
            'D'=>[0,360,720,1080,1440,1800,2160,2520,2880,3240]
        ];
    }
    //广告位排期
    public static function advertPositionSpaceTime(){
        return [
            'A1'=>[300,300,300,300,300,300,300,300,300,300],
            'A2'=>[60,60,60,60,60,60,60,60,60,60],
            'B'=>[300,300,300,300,300,300,300,300,300,0],
            'C'=>[360,360,360,360,360,360,360,360,360,360],
            'D'=>[360,360,360,360,360,360,360,360,360,360]
        ];
    }

    //媒体类型
    public static function media_type(){
        return [
          1=>'图片',
          2=>'视频',
        ];
    }
    //app版本号更新类型
    public static function app_version_update_type(){
        return [
            1=>'强制更新',
            2=>'不强制更新',
        ];
    }
    //系统费用配置类型
    public static function system_price_config_type(){
        return [
            1=>'企业店铺',2=>'个人店铺'
        ];
    }
    //系统配置类型
    public static function system_config_type(){
        return [
            'system'=>'系统设置',
            'examine'=>'审核信息配置',
            'sample'=>'资质范本',
            'title_keyword_description'=>'频道信息配置',
        ];
    }
    //获取货款抵保证金状态
    public static function shop_bond_status(){
        return [
            0=>'<font color="gray">未参加活动</font>',
            1=>'<font color="green">参加活动</font>',
            2=>'<font color="red">违规被停止</font>'
        ];
    }
    //媒体处理状态
    public static function media_informed_status(){
        return [
            0=>'<font color="red">未处理</font>',
            1=>'<font color="green">已处理</font>',
            2=>'<font color="gray">不处理</font>'
        ];
    }
    //获取所有小时
    public static function hours(){
        return [
            '00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23'
        ];
    }
    //审核日志状态
    public static function examine_log_status(){
        return [
            0=>'通过',1=>'不通过',2=>'审核完成'
        ];
    }
    /*
     * 商品搜索页的搜索字段
     * @shop_category int 1、公司 3、个人
     * */
    public static function goods_search_field($shop_category=1){
        if($shop_category == 3){
            return [
                'shop_name'=>'店铺名称',
                'shop_id'=>'店铺序号',
                'member_name'=>'买家用户名',
                'member_contact_name'=>'买家联系人姓名',
                'member_contact_email'=>'买家联系人邮箱',
                'member_contact_mobile'=>'买家联系人手机',
                'company_contact_name'=>'紧急联系人',
                'company_contact_mobile'=>'紧急联系人手机'
            ];
        }else{
            return [
                'shop_name'=>'店铺名称',
                'shop_id'=>'店铺序号',
                'member_name'=>'买家用户名',
                'member_contact_name'=>'买家联系人姓名',
                'member_contact_email'=>'买家联系人邮箱',
                'member_contact_mobile'=>'买家联系人手机',
                'company_name'=>'公司名称',
                'company_mobile'=>'公司电话',
                'company_contact_name'=>'公司紧急联系人',
                'company_contact_mobile'=>'公司紧急联系人手机'
            ];
        }

    }

    /**
     * @审核商品搜索
     * @return array
     */
    public static function good_search_field(){
        return [
            'id'=>'商品ID',
            'shop_id'=>'店铺ID',
            'shop_name'=>'店铺名称',
            'short_name'=>'商品名称',

        ];
    }


    //开店填写步骤,搜索栏使用
    public static function shop_step_by_search(){
        return [
            6=>"填写完整",
            1=>'未填写完整'
        ];
    }
    //店铺类型
    public static function shop_category(){
        return [
            '1'=>'企业店铺',
            '2'=>'企业店铺',
            '3'=>'个人店铺'
        ];
    }
    //短信发送状态
    public static function mobile_message_status(){
        return [1=>'未验证',2=>'已验证'];
    }
    //系统节点类型
    public static function sys_note_type(){
        return [1=>'节点',2=>'任务'];
    }
    //财务收款表状态
    public static function receivables_status(){
        return [1=>'交易中',2=>'交易成功',3=>'已退款'];
    }
    //财务收款表开票状态
    public static function receivables_individual_status(){
        return [1=>'已开票',2=>'未开票'];
    }
    //发票处理状态
    public static function shop_invoice_status(){
        return [0=>'未开票',1=>'已开票'];
    }
    //发票类型
    public static function shop_invoice_type(){
        return [1=>'普通发票',2=>'增值税发票'];
    }
    //退款类型
    public static function refund_type(){
        return [1=>'原路返回',2=>'线下打款'];
    }
    //移动端升级类型
    public static function get_app_update_type(){
        return [1=>'升级',0=>'不升级',2=>'强制升级'];
    }
    //移动端类型
    public static function get_app_id(){
        return [1=>'安卓',2=>'苹果'];
    }
    //买家提现打款状态
    public static function get_refund_status(){
        return [0=>'未处理',1=>'已退款','2'=>'退款失败','3'=>'等待线下打款'];
    }
    //审核驳回类型
    public static function examine_type(){
        return [1=>"店铺",2=>'商品',3=>'品牌',4=>'店铺故事'];
    }
    //广告统计页面时间类型
    public static function market_advert_count_date_type(){
        return [0=>"今日",1=>'昨日',6=>'最近7天',29=>'最近30天'];
    }
    //至臻展位状态
    public static function market_booth_status(){
        return [1=>'待审核',2=>'有修改',3=>'已通过'];
    }
    //竞价推广统计报表搜索时间类型
    public static function market_keyword_search_date_type(){
        return [0=>'自定义时间',29=>'最近一个月',6=>'最近一周',2=>'最近三天'];
    }
    //竞价商品按时间搜索
    public static function market_keyword_time(){
        return [89=>'最近三个月',29=>'最近一个月',6=>'最近一周',2=>'最近三天'];
    }
    //竞价产品状态
    public static function keyword_goods_status(){
        return [-1=>'强制停止',0=>'关闭',1=>'推广中',2=>'已暂停'];
    }
    //通用状态
    public static function status(){
        return [1=>'开启',2=>'关闭'];
    }
    //通用状态
    public static function app_advert_status(){
        return [1=>'正常',2=>'已关闭'];
    }
    //竞价关键词添加方式
    public static function market_keyword_add_style(){
        return [1=>'后台添加',2=>'卖家前台添加'];
    }
    //广告位图片张数
    public static function market_advert_number(){
        return [1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10];
    }
    //广告位使用平台
    public static function market_advert_platform(){
        return ['PC'=>'PC','APP'=>'APP'];
    }
    //店铺注册步骤
    public static function shop_step(){
        return [1=>'未填写完整',2=>'未填写完整',3=>'未填写完整',4=>'未填写完整',5=>'未填写完整',6=>'填写完整'];
    }
    //app链接类型
    public static function app_like_type(){
        return [1=>'商品ID',2=>'店铺ID'];
    }
    public static function pc_like_type(){
        return [3=>'商品ID',4=>'店铺ID'];
    }
    public static function app_pc_like_type(){
        return [1=>'APP商品ID',2=>'APP店铺ID',3=>'PC商品ID',4=>'PC店铺ID'];
    }
    //app广告类型
    public static function app_image_type(){
        return [1=>'轮播广告',2=>'行家推荐',9=>'热门搜索',10=>'热门品牌',11=>'商品推荐',12=>'热门分类',13=>'猜你喜欢广告'];
    }
    //品牌类型
    public static function brand_type(){
        return [1=>'国内品牌',2=>'国外品牌'];
    }
    //品牌经营类型
    public static function brand_operate_type(){
        return [1=>'自产品牌',2=>'特许经营品牌'];
    }
    //结算中心-卖家提现状态
    public static function withdraw_status(){
        return [
            1=>'提现成功',2=>'提现中',3=>'提现失败',0=>'未提现'
        ];
    }
    //投诉类型
    public static function order_dispute_type(){
        return [
            1=>'退货投诉',2=>'普通投诉'
        ];
    }
    //投诉结果处理状态
    public static function order_result_status(){
        return [0=>'未处理',1=>'已处理'];
    }
    //投诉梳理结果
    public static function order_dispute_result(){
        return ['不作处理'=>'不作处理','警告处理'=>'警告处理','冻结店铺'=>'冻结店铺','信用降低一级'=>'信用降低一级','扣保证金'=>'扣保证金'];
    }
    //投诉梳理结果
    public static function order_dispute_refund_result(){
        return ['需要退货'=>'需要退货','不需要退货'=>'不需要退货','返还运费'=>'返还运费'];
    }
    //卖家子账号状态
    public static function shop_child_status(){
        return [1=>'使用中',2=>'已停用',3=>'已冻结'];
    }
    //买家婚姻状况
    public static function member_marital_status(){
        return [1=>'未婚',2=>'已婚',3=>'保密'];
    }
    //买家学历
    public static function member_education(){
        return [1=>'小学',2=>'初中',3=>'高中或中专',4=>'本科',5=>'保密',6=>'研究生或硕士',7=>'博士'];
    }
    //买家性别
    public static function member_sex(){
        return [1=>'男',2=>'女',3=>'保密'];
    }
    //买家收入状况
    public static function member_earning(){
        return [1=>'2000~5000',2=>'5000~1万',3=>'1万以上'];
    }
    //买家用户审核状态.
    public static function member_examine_status(){
        return [
            '2,0'=>'冻结审核中','2,1'=>'冻结审核未通过','2,2'=>'冻结审核已通过',
            '3,0'=>'封杀审核中','3,1'=>'封杀审核未通过','3,2'=>'封杀审核已通过',
            '1,0'=>'恢复正常审核中','1,1'=>'恢复正常审核未通过','1,2'=>'恢复正常审核已通过',
        ];
    }
    //审核的三种状态
    public static function examine_status(){
        return [
            0=>'审核中',1=>'审核未通过',2=>'审核已通过'
        ];
    }
    //买家用户状态
    public static function member_status(){
        return [
          1=>'正常',2=>'已冻结',3=>'已封杀'
        ];
    }
    //举报类型
    public static function report_status_by_shop(){
        return [
            1=>'恶意投诉',2=>'恶意评价',3=>'恶意骚扰',4=>'违背承诺',5=>'其他',
        ];
    }
    public static function report_status_by_member(){
        return [
          1=>'恶意骚扰',2=>'违背承诺',3=>'延迟发货',4=>'未按约定时间发货',5=>'违反交易流程',
          6=>'拒绝使用信用卡',7=>'承诺的没做到',8=>'拒绝交易',9=>'未按成交价格进行交易',10=>'其他',
        ];
    }
    //系统现金流类型
    public static function capital_pay_type(){
        return [
            2=>'<font color="red">支出</font>',
            1=>'<font color="green">收入</font>'
        ];
    }
    //系统现金流类型
    public static function capital_pay_type_no_color(){
        return [
            2=>'支出',
            1=>'收入'
        ];
    }
    //流水账单系统费用的消费类型
    public static function get_account_record_pay_note(){
        return [
            '保证金'=>'保证金',
            '平台服务费'=>'平台服务费',
            '购买广告'=>'购买广告',
            '购买展位'=>'购买展位',
            '商家保障'=>'商家保障'
        ];
    }
    //买家流水类型
    public static function member_account(){
        return [
            1=>'消费',
            3=>'充值',
            2=>'退款',
            4=>'提现',
            5=>'系统费用',
            6=>'系统扣款',
            7=>'返佣金',
        ];
    }
    //买家流水支付方式
    public static function member_pay_type(){
        return [
            0=>'系统余额',
            1=>'支付宝',
            2=>'银联B2C',
            3=>'微信APP',
            4=>'银联B2B',
            5=>'微信PC',
        ];
    }
    //买家流水状态
    public static function member_account_status(){
        return [
            1=>'交易成功',
            2=>'交易中',
            3=>'交易失败',
            0=>'未交易',
        ];
    }
    //买家退款流水状态
    public static function member_refund_account_status(){
        return [
            1=>'退款成功',
            2=>'退款失败',
            0=>'未处理',
        ];
    }
    //订单投诉内容评价
    public static function order_evaluate(){
        return [
            1=>'恶意骚扰',
            2=>'违背承诺',
            3=>'延迟发货',
            4=>'未按约定时间发货',
            5=>'违反交易流程',
            6=>'拒绝使用信用卡',
            7=>'承诺的没做到',
            8=>'拒绝交易',
            9=>'未按成交价格进行交易',
            10=>'其他',
        ];
    }
    //订单投诉责任比例
    public static function order_duty_ratio(){
        return [
            1=>'付全部责任',2=>'付部分责任',3=>'不承担责任'
        ];
    }
    //订单投诉责任承担人
    public static function order_duty_bear(){
        return [
            1=>'投诉发起人',2=>'被投诉人',3=>'投诉发起人和被投诉人'
        ];
    }
    //订单状态
    public static function refund_status(){
        return [
            1=>'同意',2=>'拒绝',3=>'退款成功',0=>'申请中'
        ];
    }
    //订单状态
    public static function dispute_status(){
        return [
            1=>'等待受理',2=>'处理中',3=>'处理完毕',0=>'未介入',4=>'已撤销'
        ];
    }
    //用户反馈模块-满意度
    public static function help_satisfy(){
        return [
            1=>'非常满意',2=>'满意',3=>'一般',4=>'不满意',5=>'非常不满意',
        ];
    }
    //用户反馈模块-满意度
    public static function help_question(){
        return [
            1=>'找不到或不好找想要的东西',2=>'文字太多没时间看',3=>'结构不清晰',4=>'页面设计难看',5=>'其他',
        ];
    }
    //用户反馈模块-是否能解决问题
    public static function help_problem(){
        return [
            1=>'解决',2=>'部分能解决',3=>'完全解决不了',
        ];
    }
    //开启状态
    public static function status_common(){
        return [
            1=>'开启',0=>'关闭'
        ];
    }
    //开启状态
    public static function admin_status(){
        return [
            10=>'开启',0=>'关闭'
        ];
    }
    //支付方式
    public static function pay_type(){
        return [
            1=>'平台支付',2=>'储蓄卡支付',3=>'信用卡支付'
        ];
    }
    //订单详情页面小订单退款状态
    public static function order_shop_pay_status(){
        return [0=>'未退款',1=>'有退款',2=>'退款完成'];
    }
    //订单状态
    public static function order_status(){
        return [
            //-1=>'未付款',1=>'付款成功',2=>'商品出库',3=>'已完成',4=>'申请退款',5=>'提交投诉',6=>'关闭订单'
            -1=>'关闭订单',0=>'提交订单',1=>'买家付款',2=>'卖家发货',3=>'买家确认收货',4=>'交易完成'
        ];
    }
    //订单状态统图页面
    public static function order_chart_status(){
        return [
            //-1=>'未付款',1=>'付款成功',2=>'商品出库',3=>'已完成',4=>'申请退款',5=>'提交投诉',6=>'关闭订单'
            1=>'付款订单',2=>'已发货订单',3=>'交易成功',4=>'申请退款',5=>'提交投诉',-1=>'交易关闭'
        ];
    }
    //修改订单状态详情页面
    public static function order_status2(){
        return [
          0=>'提交订单',1=>'买家付款',2=>'卖家发货',3=>'买家确认收货',4=>'交易完成',-1=>'交易关闭'
        ];
    }
    //订单退货状态
    public static function order_pay_status(){
        return [0=>'无退款',1=>'有退款',2=>'退款完成'];
    }
    //订单退货状态
    public static function order_ship_status(){
        return [0=>'需要退货',1=>'不需要退货'];
    }
    //修改订单状态详情页面
    public static function order_status3(){
        return [
            0=>'提交订单',1=>'买家付款',2=>'卖家发货',3=>'买家确认收货'
        ];
    }
    //审核数据类型
    public static function examine_data_type(){
        return [
            1=>'店铺审核',
            2=>'商品审核',
            3=>'品牌审核',
            4=>'店铺故事',
            5=>'店铺基本信息',
            6=>'店铺资质',
            7=>'广告素材',
            8=>'订单修改',
            9=>'买家状态修改',
            10=>'卖家状态修改',
            11=>'店铺分类',
            12=>'增税发票',
            13=>'至臻展位',
            14=>'普通广告',
            15=>'买家退款申请',
            16=>'卖家提现',
            17=>'卖家发票申请',
        ];
    }
    //修改订单状态审核模块，审核状态
    public static function order_update_examine_status(){
        return [
            0=>'等待审核',1=>'审核不通过',2=>'审核通过'
        ];
    }
    //修改订单状态申请模块，处理状态
    public static function order_update_apply_status(){
        return [
            1=>'未反馈用户',2=>'反馈成功'
        ];
    }
    public static function is_have($is,$t1 = '是',$t2 = '否'){
        return $is == 1  ? $t1 : $t2;
    }
    public static function get_brand_operate_type($type){
        if($type == 1){
            return '自主品牌';
        }else{
            return '特许经营';
        }
    }
    public static function get_brand_type($type){
        if($type == 1){
            return '国内品牌';
        }else{
            return '国外品牌';
        }
    }
    //活动按小时搜素
    public static function getActivityTime(){
        return [
          '00:00'=>'00:00',
            '01:00'=>'01:00',
            '02:00'=>'02:00',
            '03:00'=>'03:00',
            '04:00'=>'04:00',
            '05:00'=>'05:00',
            '06:00'=>'06:00',
            '23:00'=>'23:00',
        ];
    }
    //获取优惠卷面值
    public static function getCouponsType(){
        return [
          5=>'5元',10=>'10元',20=>'20元',50=>'50元',100=>'100元',200=>'200元',
        ];
    }
    //获取活动使用渠道
    public static function getActivityChannelType(){
        return [
            1=>'网站',2=>'手机',3=>'全部',
        ];
    }
    //获取活动使用类型
    public static function getActivityType(){
        return [
          1=>'满就送',2=>'限时抢购',3=>'满就优惠'
        ];
    }
    public static function getSex($sex){
        return $sex === 1 ? '男' : '女' ;
    }
    //获取卖家违规扣款提示信息
    public static function MessageByShopDebit($title,$content,$price){
        return [
            'title'=>'最低捞店铺信息通知',
            'content'=>[
                'system'=>"尊敬的用户：由于您{$content}，系统扣除了您{$title}".DataSource::convert_price(abs($price))."。如有任何疑问，可以与我们的客服联系。客服电话:".\Yii::$app->params['SYS_SERVICE_MOBILE'],
                'email'=>'system',
            ]
        ];
    }
    //开店审核通过的提示信息
    public static function MessageByExamineShop($username,$password){
        return [
            'title'=>'最低捞开店审核通知',
            'content'=>[
                'system'=>'尊敬的用户：恭喜您提交的开店信息已审核通过，我们已将您的账号和密码下发到您的手机和邮箱，请注意查收。请您尽快修改密码。为了不影响您发布商品请尽快缴纳保证金。',
                'email'=>'&nbsp;&nbsp;&nbsp;恭喜您提交的开店信息已审核通过，您的账号：'.$username.' 密码：'.$password.' <br>&nbsp;&nbsp;&nbsp;请您尽快修改密码。为了不影响您发布商品请尽快缴纳保证金。',
                'message'=>'尊敬的用户：恭喜您提交的开店信息已审核通过，您的账号：'.$username.' 密码：'.$password.'  请您尽快修改密码。为了不影响您发布商品请尽快缴纳保证金。',
            ]
        ];
    }
    //所有操作提示信息
    public static function MessageArray(){
        return [
            //店铺审核失败,给高世龙发邮件
            'shop_examine_error'=>[
                'title'=>'店铺审核失败',
                'content'=>[
                    'email'=>'店铺名称：{shop_name} 原因：{desc}',
                ]
            ],
            //最低价承诺投诉审核成功(买家)
            'price_appeal_examine_member_success'=>[
                'title'=>'最低捞价格举报审核结果 ',
                'content'=>[
                    'system'=>'尊敬的最低捞用户您好，我们在核对用户对您的价格举报后，发现情况属实，根据本平台相关政策，已从您的保证金中扣除三倍差价赔付给买家，希望得到您的理解。',
                    'email'=>'尊敬的最低捞用户您好，我们在核对用户对您的价格举报后，发现情况属实，根据本平台相关政策，已从您的保证金中扣除三倍差价赔付给买家，希望得到您的理解。',
                    'message'=>'尊敬的最低捞用户您好，您的商品被用户“价格举报”，情况属实，已从您的保证金中扣除相应三倍差价，希望得到您的理解。'
                ],
            ],
            //最低价承诺投诉审核失败(买家)
            'price_appeal_examine_member_error'=>[
                'title'=>'最低捞价格举报审核结果 ',
                'content'=>[
                    'system'=>'尊敬的最低捞用户您好，您提交的“价格举报”已被驳回，您可请登录最低捞APP或网站查看详情；给您造照成的不便敬请谅解。',
                    'email'=>'尊敬的最低捞用户您好，您提交的“价格举报”已被驳回，您可请登录最低捞APP或网站查看详情；给您造照成的不便敬请谅解。',
                    'message'=>'尊敬的最低捞用户您好，您提交的“价格举报”已被驳回，您可请登录最低捞APP或网站查看详情；给您造成的不便敬请谅解。'
                ],
            ],
            //最低价承诺投诉审核成功(卖家)
            'price_appeal_examine_success'=>[
                'title'=>'最低捞价格举报审核结果 ',
                'content'=>[
                    'system'=>'尊敬的最低捞用户您好，您提交的“价格举报”已通过审核，我们将会在24小时之内把相应的金额打到您的账户余额内，请注意查收。',
                    'email'=>'尊敬的最低捞用户您好，您提交的“价格举报”已通过审核，我们将会在24小时之内把相应的金额打到您的账户余额内，请注意查收。',
                    'message'=>'尊敬的最低捞用户您好，您提交的“价格举报”已通过审核，我们将会在24小时之内把相应的账户金额打到您的余额内，请注意查收。'
                ],
            ],
            //最低价承诺投诉审核失败(卖家)
            'price_appeal_examine_error'=>[
                'title'=>'最低捞价格举报审核结果 ',
                'content'=>[
                    'system'=>'尊敬的最低捞用户您好，我们在核对用户对您的价格举报后，发现情况并不属实，举报已经处理，不会对您造成任何影响，请您放心，祝您生意兴隆。',
                    'email'=>'尊敬的最低捞用户您好，我们在核对用户对您的价格举报后，发现情况并不属实，举报已经处理，不会对您造成任何影响，请您放心，祝您生意兴隆。',
                    'message'=>'尊敬的最低捞用户您好，您店铺内的商品被用户“价格举报”，但情况并不属实，不会对您造成任何影响，祝您生意兴隆。'
                ],
            ],
            //开店审核失败的提示信息
            'examine_shop_error'=>[
                'title'=>'最低捞开店审核通知',
                'content'=>[
                    'system'=>'尊敬的用户：由于{desc}，您提交的开店信息审核未通过，您可修改后重新提交。如有任何疑问，可以与我们的客服联系。客服电话：'.\Yii::$app->params['SYS_SERVICE_MOBILE'],
                    'email'=>'&nbsp;&nbsp;&nbsp;尊敬的用户：由于{desc}，您提交的开店信息审核未通过，您可修改后重新提交。<br>&nbsp;&nbsp;如有任何疑问，可以与我们的客服联系。客服电话：'.\Yii::$app->params['SYS_SERVICE_MOBILE'],
                ],
            ],
            'shop_dongjie'=>[
                'title'=>'最低捞账号冻结通知',
                'content'=>[
                    'email'=> '&nbsp;&nbsp;由于"{desc}"原因，现已将您的账号"{username}"冻结，账号冻结后可能会造成如下影响：<br>&nbsp;&nbsp;不能登录和使用最低捞平台，该账号下的所有店铺、产品、增值服务都只能展示但不能生效，各种处于审核中的店铺、产品等将不能通过审核。解冻后才可操作。<br>&nbsp;&nbsp;为保证账号安全以及网站的运营秩序，给您带来的不便，我们深表歉意！如果您有任何疑问，可以与我们的客服联系，并进行申诉，我们将及时处理。客服电话：'.\Yii::$app->params['SYS_SERVICE_MOBILE'],
                    'message'=>'亲爱的用户您好,由于“{desc}”原因，现已将您的账号“{username}”冻结。您将不能登录最低捞平台，如有疑问，请及时与我们的客服联系，并进行申诉，我们将及时处理。客服电话：'.\Yii::$app->params['SYS_SERVICE_MOBILE']
                ]
            ],
            'shop_jiedong'=>[
                'title'=>'最低捞账号解冻通知',
                'content'=>[
                    'email'=> '&nbsp;&nbsp;&nbsp;由于您及时进行了申诉，经过最低捞网站的审核，现已将您的账户"{username}"恢复正常！<br>
      &nbsp;&nbsp;&nbsp;给您带来的不便，我们深表歉意！感谢您对最低捞的支持！如在操作过程中有任何疑问，可以与我们的客服联系，给您一对一的指导。客服电话：'.\Yii::$app->params['SYS_SERVICE_MOBILE'],
                    'message'=>'亲爱的用户您好,最低捞平台已通过您的申诉，现已将您的账户“{username}”恢复正常！给您带来的不便，我们深表歉意！感谢您对最低捞的信任与支持！',
                    'system'=>'由于您及时进行了申诉，经过最低捞网站的审核，现已将您的账户“{username}”恢复正常！
     给您带来的不便，我们深表歉意！感谢您对最低捞的支持！
     如在操作过程中有任何疑问，可以与我们的客服联系，给您一对一的指导。客服电话：'.\Yii::$app->params['SYS_SERVICE_MOBILE'],
                ]
            ],
            'shop_fengsha'=>[
                'title'=>'最低捞账号封杀通知',
                'content'=>[
                    'email'=> ' &nbsp;&nbsp;&nbsp;由于"{desc}"原因，现已将您的账号"{username}"封杀，账号封杀后可能会造成如下影响：<br>
    &nbsp;&nbsp;&nbsp;1.账号不能登录最低捞平台。<br>
    &nbsp;&nbsp;&nbsp;2.店铺无法在网站上展示，无法开店、缴费、续费等操作，正处于缴费、续费、审核等过程的店铺，将全部失败。<br>
    &nbsp;&nbsp;&nbsp;3.账号下所有产品将自动强制下架，如果买家已拍下此产品则自动取消订单，自动退款给买家（已发货的产品除外）。<br>
    &nbsp;&nbsp;&nbsp;4.账号下所有广告类增值服务将全部失效、下架。<br>
    &nbsp;&nbsp;&nbsp;为保证账号安全以及网站的运营秩序，给您带来的不便，我们深表歉意！<br>
    &nbsp;&nbsp;&nbsp;如果您有任何疑问，可以与我们的客服联系，并进行申诉，我们将及时处理。客服电话：'.\Yii::$app->params['SYS_SERVICE_MOBILE'],
                    'message'=>'由于“{desc}”原因，现已将您的账号“{username}”封杀。请及时与我们的客服联系，并进行申诉，我们将及时处理。客服电话：'.\Yii::$app->params['SYS_SERVICE_MOBILE']
                ]
            ],
            'shop_jiefeng'=>[
                'title'=>'最低捞账号解封通知',
                'content'=>[
                    'email'=>'&nbsp;&nbsp;&nbsp;由于您及时进行了申诉，经过最低捞网站的审核，现已将您的账户"{username}"恢复正常！<br>
     &nbsp;&nbsp;&nbsp;给您带来的不便，我们深表歉意！感谢您对最低捞的支持！
     如在操作过程中有任何疑问，可以与我们的客服联系，给您一对一的指导。客服电话：'.\Yii::$app->params['SYS_SERVICE_MOBILE'],

                    'system'=>'由于您及时进行了申诉，经过最低捞网站的审核，现已将您的账户“{username}”恢复正常！
     给您带来的不便，我们深表歉意！感谢您对最低捞的支持！
     如在操作过程中有任何疑问，可以与我们的客服联系，给您一对一的指导。客服电话：'.\Yii::$app->params['SYS_SERVICE_MOBILE'],

                    'message'=>'亲爱的用户您好，最低捞平台已通过您的申诉申请，现已将您的账户“{username}”恢复正常！
     给您带来的不便，我们深表歉意！感谢您对最低捞的支持！',
                ]
            ],
            'member_fengsha'=>[
                'title'=>'最低捞账号封杀通知',
                'content'=>[
                   'email'=> '&nbsp;&nbsp;&nbsp;由于"{desc}"原因，现已将您的账号"{username}"封杀，账号封杀后，您将不能登录最低捞平台。<br>
    &nbsp;&nbsp;&nbsp;为保证账号安全以及网站的运营秩序，给您带来的不便，我们深表歉意！
    如果您有任何疑问，可以与我们的客服联系，并进行申诉，我们将及时处理。客服电话：'.\Yii::$app->params['SYS_SERVICE_MOBILE'],
                    'message'=>'由于“{desc}”原因，现已将您的账号“{username}”封杀，账号封杀后，您将不能登录最低捞平台。
    如果您有任何疑问，可以与我们的客服联系，并进行申诉，我们将及时处理。客服电话：'.\Yii::$app->params['SYS_SERVICE_MOBILE']
                ]
            ],
            'member_jiefeng'=>[
                'title'=>'最低捞账号解封通知',
                'content'=>[
                    'email'=>'&nbsp;&nbsp;&nbsp;由于您及时进行了申诉，经过最低捞网站的审核，现已将您的账户"{username}"恢复正常！<br>
     &nbsp;&nbsp;&nbsp;给您带来的不便，我们深表歉意！感谢您对最低捞的支持！
     如在操作过程中有任何疑问，可以与我们的客服联系，给您一对一的指导。客服电话：'.\Yii::$app->params['SYS_SERVICE_MOBILE'],
                    'system'=>'由于您及时进行了申诉，经过最低捞网站的审核，现已将您的账户“{username}”恢复正常！
     给您带来的不便，我们深表歉意！感谢您对最低捞的支持！
     如在操作过程中有任何疑问，可以与我们的客服联系，给您一对一的指导。客服电话：'.\Yii::$app->params['SYS_SERVICE_MOBILE'],
                    'message'=>'亲爱的用户:最低捞平台已通过您的申诉，现已将您的账户“{username}”恢复正常！
     给您带来的不便，我们深表歉意！感谢您对最低捞的支持！',
                ]
            ],
            'goods_close'=>[
                'title'=>'最低捞商品强制关闭通知',
                'content'=>[
                    'system'=>' 您好，很抱歉，由于“{desc}”原因，您发布的商品违反了最低捞网站规则，故此将您发布的商品进行关闭处理，详情请查看“卖家中心”-“商品管理”-“未发布的商品”-“已下架”。点击“已下架”状态可查看原因。
   如在操作过程中有任何疑问，可以与我们的客服联系，给您一对一的指导。客服电话：'.\Yii::$app->params['SYS_SERVICE_MOBILE'],
                    'email'=>'&nbsp;&nbsp;&nbsp;很抱歉，由于"{desc}"原因，您发布的商品违反了最低捞网站规则，故此将您发布的商品进行关闭处理，详情请查看"卖家中心"-"商品管理"-"未发布的商品"-"已下架"。点击"已下架"状态可查看原因。<br>
   &nbsp;&nbsp;&nbsp; 如在操作过程中有任何疑问，可以与我们的客服联系，给您一对一的指导。客服电话：'.\Yii::$app->params['SYS_SERVICE_MOBILE'],
                ]
            ]
        ];
    }
} 